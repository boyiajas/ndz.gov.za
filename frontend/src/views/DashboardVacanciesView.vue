<template>
  <DashboardLayout
    title="Vacancies & Careers Manager"
    subtitle="Publish municipal job opportunities, internship programmes, manage application deadlines, and upload official advert specification packs."
  >
    <template #header-actions>
      <button type="button" class="btn-primary-action" @click="openCreateModal">
        <span>+</span> Post New Vacancy
      </button>
    </template>

    <div v-if="notice" class="portal-alert success">
      <i class="bi bi-check-circle-fill me-2"></i> {{ notice }}
    </div>
    <div v-if="error" class="portal-alert danger">
      <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ error }}
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="stat-card" style="background: #fff; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
          <div>
            <span class="text-muted small text-uppercase fw-bold">Total Advertised</span>
            <h3 class="mb-0 fw-bold text-dark mt-1">{{ vacancies.length }}</h3>
          </div>
          <div style="width: 44px; height: 44px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-briefcase-fill"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card" style="background: #fff; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
          <div>
            <span class="text-muted small text-uppercase fw-bold">Active Open Positions</span>
            <h3 class="mb-0 fw-bold text-success mt-1">{{ openCount }}</h3>
          </div>
          <div style="width: 44px; height: 44px; border-radius: 8px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-check-circle-fill"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card" style="background: #fff; padding: 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
          <div>
            <span class="text-muted small text-uppercase fw-bold">Closed / Archived</span>
            <h3 class="mb-0 fw-bold text-secondary mt-1">{{ closedCount }}</h3>
          </div>
          <div style="width: 44px; height: 44px; border-radius: 8px; background: #f1f5f9; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="bi bi-archive-fill"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Bar -->
    <section class="portal-panel filter-panel mb-4">
      <div class="filter-controls">
        <div class="search-wrap">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by job title, reference number, or department..."
          />
        </div>

        <div class="filter-dropdowns">
          <select v-model="filterStatus">
            <option value="all">All Statuses</option>
            <option value="open">Open Only</option>
            <option value="closed">Closed Only</option>
          </select>

          <select v-model="filterDepartment">
            <option value="all">All Departments</option>
            <option v-for="dept in departmentsList" :key="dept" :value="dept">{{ dept }}</option>
          </select>
        </div>
      </div>
    </section>

    <!-- Vacancies Table -->
    <section class="portal-panel">
      <div v-if="loading" class="loading-state">Loading municipal vacancies...</div>
      <div v-else-if="filteredVacancies.length === 0" class="empty-state">
        <i class="bi bi-briefcase text-muted" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
        <p class="mb-3">No vacancies found matching your current filter criteria.</p>
        <button type="button" class="btn-primary-action" @click="openCreateModal">
          Post First Vacancy
        </button>
      </div>

      <div v-else class="table-responsive">
        <table class="manager-table">
          <thead>
            <tr>
              <th>Ref No</th>
              <th>Job Title & Scope</th>
              <th>Department</th>
              <th>Status</th>
              <th>Closing Date</th>
              <th>Advert Pack</th>
              <th style="text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="v in filteredVacancies" :key="v.id">
              <td>
                <span class="ref-badge">{{ v.reference_no || 'N/A' }}</span>
              </td>
              <td>
                <div class="table-title">{{ v.title }}</div>
                <div class="table-subtext" v-if="v.remuneration">
                  <i class="bi bi-cash me-1 text-primary"></i> {{ v.remuneration }}
                </div>
                <div class="table-subtext" v-if="v.location">
                  <i class="bi bi-geo-alt me-1 text-danger"></i> {{ v.location }}
                </div>
              </td>
              <td>
                <span class="dept-badge-sm">{{ v.department }}</span>
              </td>
              <td>
                <button
                  type="button"
                  class="badge-toggle"
                  :class="v.status === 'open' ? 'status-open' : 'status-closed'"
                  :title="`Click to toggle to ${v.status === 'open' ? 'closed' : 'open'}`"
                  @click="toggleVacancyStatus(v)"
                >
                  <i :class="v.status === 'open' ? 'bi bi-check-circle-fill' : 'bi bi-x-circle-fill'"></i>
                  {{ v.status === 'open' ? 'Open' : 'Closed' }}
                </button>
              </td>
              <td>
                <div class="closing-cell">
                  <span>{{ formatDate(v.closing_date) }}</span>
                  <small v-if="v.closing_date && v.status === 'open'" :class="isPastDate(v.closing_date) ? 'text-danger' : 'text-success'">
                    {{ isPastDate(v.closing_date) ? 'Expired' : 'Active' }}
                  </small>
                </div>
              </td>
              <td>
                <a
                  v-if="v.document_url"
                  :href="v.document_url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="btn-file-download"
                  title="Download attached advert document"
                >
                  <i class="bi bi-file-earmark-pdf-fill"></i>
                  <span>{{ v.document_name || 'Download PDF' }}</span>
                </a>
                <span v-else class="text-muted small">No file attached</span>
              </td>
              <td style="text-align: right;">
                <div class="row-actions">
                  <button
                    type="button"
                    class="btn-action edit"
                    title="Edit vacancy details"
                    @click="openEditModal(v)"
                  >
                    <i class="bi bi-pencil-fill"></i> Edit
                  </button>
                  <button
                    type="button"
                    class="btn-action delete"
                    title="Delete vacancy"
                    @click="confirmDelete(v)"
                  >
                    <i class="bi bi-trash-fill"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Create / Edit Modal -->
    <div v-if="showModal" class="modal-backdrop">
      <div class="modal-dialog-custom">
        <div class="modal-header-custom">
          <h3>{{ editingVacancy ? 'Edit Vacancy Advert' : 'Post New Municipal Vacancy' }}</h3>
          <button type="button" class="btn-close-custom" @click="closeModal">&times;</button>
        </div>

        <form @submit.prevent="saveVacancy" class="modal-body-custom">
          <div class="form-row-2">
            <div class="form-group">
              <label>Job Title <span class="required">*</span></label>
              <input
                v-model="form.title"
                type="text"
                placeholder="e.g. Senior Internal Auditor"
                required
              />
            </div>

            <div class="form-group">
              <label>Reference Number</label>
              <input
                v-model="form.reference_no"
                type="text"
                placeholder="e.g. NDZ-MM-02/2026"
              />
            </div>
          </div>

          <div class="form-row-2">
            <div class="form-group">
              <label>Municipal Department <span class="required">*</span></label>
              <select v-model="form.department" required>
                <option value="" disabled>Select Department</option>
                <option v-for="dept in departmentsList" :key="dept" :value="dept">{{ dept }}</option>
              </select>
            </div>

            <div class="form-group">
              <label>Recruitment Status <span class="required">*</span></label>
              <select v-model="form.status" required>
                <option value="open">Open (Accepting Applications)</option>
                <option value="closed">Closed (Archive)</option>
              </select>
            </div>
          </div>

          <div class="form-row-3">
            <div class="form-group">
              <label>Closing Date & Time</label>
              <input
                v-model="form.closing_date"
                type="date"
              />
            </div>

            <div class="form-group">
              <label>Remuneration / Salary Scale</label>
              <input
                v-model="form.remuneration"
                type="text"
                placeholder="e.g. Task Grade 14"
              />
            </div>

            <div class="form-group">
              <label>Work Location</label>
              <input
                v-model="form.location"
                type="text"
                placeholder="e.g. Creighton Main Office"
              />
            </div>
          </div>

          <div class="form-group">
            <label>Online Application Link</label>
            <input
              v-model="form.application_url"
              type="url"
              placeholder="https://forms.cloud.microsoft/..."
            />
            <small class="form-text-muted">Defaults to municipal Microsoft Forms application link.</small>
          </div>

          <div class="form-group">
            <label>Role Overview / Job Summary</label>
            <textarea
              v-model="form.description"
              rows="3"
              placeholder="Provide a concise description of key responsibilities and duties..."
            ></textarea>
          </div>

          <div class="form-group">
            <label>Key Requirements / Minimum Qualifications</label>
            <textarea
              v-model="form.requirements"
              rows="3"
              placeholder="e.g. • Relevant tertiary qualification&#10;• 3 years local government experience&#10;• Valid Driver's license"
            ></textarea>
          </div>

          <!-- Document / Advert Pack Upload -->
          <div class="form-group document-upload-group">
            <label>Upload Advert Document / Specification Pack (PDF)</label>
            <div v-if="form.document_url && !selectedFile" class="existing-doc-box">
              <div class="doc-info">
                <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                <a :href="form.document_url" target="_blank">{{ form.document_name || 'View Current Advert Document' }}</a>
              </div>
              <button type="button" class="btn-remove-doc" @click="removeExistingDoc">
                <i class="bi bi-x-circle"></i> Remove File
              </button>
            </div>

            <div class="file-drop-area">
              <input
                type="file"
                id="vacancy-file-input"
                accept=".pdf,.doc,.docx,.zip"
                @change="handleFileChange"
              />
              <label for="vacancy-file-input" class="file-drop-label">
                <i class="bi bi-cloud-arrow-up text-primary" style="font-size: 1.75rem;"></i>
                <span v-if="!selectedFile">Choose a PDF or Word document to attach (up to 25MB)</span>
                <span v-else class="fw-bold text-success">
                  <i class="bi bi-check-lg"></i> {{ selectedFile.name }} ({{ formatFileSize(selectedFile.size) }})
                </span>
              </label>
            </div>
          </div>

          <div class="modal-footer-custom">
            <button type="button" class="btn-cancel" @click="closeModal" :disabled="saving">
              Cancel
            </button>
            <button type="submit" class="btn-primary-action" :disabled="saving">
              {{ saving ? 'Saving Vacancy...' : (editingVacancy ? 'Save Changes' : 'Publish Vacancy') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="deletingVacancy" class="modal-backdrop">
      <div class="modal-dialog-custom modal-confirm">
        <div class="modal-header-custom border-0 pb-0">
          <h4 class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Vacancy</h4>
          <button type="button" class="btn-close-custom" @click="deletingVacancy = null">&times;</button>
        </div>
        <div class="modal-body-custom pt-2">
          <p>
            Are you sure you want to delete <strong>{{ deletingVacancy.title }}</strong>
            ({{ deletingVacancy.reference_no || 'No Ref' }})?
          </p>
          <p class="text-secondary small">This will remove the advertisement and any associated documents.</p>
        </div>
        <div class="modal-footer-custom pt-0">
          <button type="button" class="btn-cancel" @click="deletingVacancy = null" :disabled="saving">
            Cancel
          </button>
          <button type="button" class="btn-danger-confirm" @click="executeDelete" :disabled="saving">
            {{ saving ? 'Deleting...' : 'Delete Permanently' }}
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../components/DashboardLayout.vue'
import api from '../api/axios'

export default {
  name: 'DashboardVacanciesView',
  components: {
    DashboardLayout,
  },
  data() {
    return {
      vacancies: [],
      loading: false,
      saving: false,
      notice: null,
      error: null,
      searchQuery: '',
      filterStatus: 'all',
      filterDepartment: 'all',
      showModal: false,
      editingVacancy: null,
      deletingVacancy: null,
      selectedFile: null,
      departmentsList: [
        'Office of the Municipal Manager',
        'Budget & Treasury',
        'Public Works & Basic Services',
        'Corporate Support Services',
        'Community and Social Services',
        'Development and Town Planning Services',
      ],
      form: {
        title: '',
        reference_no: '',
        department: '',
        status: 'open',
        closing_date: '',
        remuneration: '',
        location: 'Creighton Main Office',
        description: '',
        requirements: '',
        application_url: 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y',
        document_url: '',
        document_name: '',
        remove_document: false,
      },
    }
  },
  computed: {
    openCount() {
      return this.vacancies.filter((v) => v.status === 'open').length
    },
    closedCount() {
      return this.vacancies.filter((v) => v.status === 'closed').length
    },
    filteredVacancies() {
      let list = this.vacancies

      if (this.filterStatus !== 'all') {
        list = list.filter((v) => v.status === this.filterStatus)
      }

      if (this.filterDepartment !== 'all') {
        list = list.filter((v) => v.department === this.filterDepartment)
      }

      if (this.searchQuery.trim()) {
        const q = this.searchQuery.toLowerCase()
        list = list.filter(
          (v) =>
            v.title?.toLowerCase().includes(q) ||
            v.reference_no?.toLowerCase().includes(q) ||
            v.department?.toLowerCase().includes(q) ||
            v.description?.toLowerCase().includes(q)
        )
      }

      return list
    },
  },
  mounted() {
    this.fetchVacancies()
  },
  methods: {
    async fetchVacancies() {
      this.loading = true
      this.error = null
      try {
        const { data } = await api.get('/api/admin/vacancies')
        this.vacancies = data.data || []
      } catch (err) {
        this.error = 'Failed to load vacancies. Please try again.'
      } finally {
        this.loading = false
      }
    },
    openCreateModal() {
      this.editingVacancy = null
      this.selectedFile = null
      this.form = {
        title: '',
        reference_no: '',
        department: 'Budget & Treasury',
        status: 'open',
        closing_date: '',
        remuneration: '',
        location: 'Creighton Main Office',
        description: '',
        requirements: '',
        application_url: 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y',
        document_url: '',
        document_name: '',
        remove_document: false,
      }
      this.showModal = true
    },
    openEditModal(vacancy) {
      this.editingVacancy = vacancy
      this.selectedFile = null

      let formattedDate = ''
      if (vacancy.closing_date) {
        formattedDate = vacancy.closing_date.split('T')[0]
      }

      this.form = {
        title: vacancy.title || '',
        reference_no: vacancy.reference_no || '',
        department: vacancy.department || '',
        status: vacancy.status || 'open',
        closing_date: formattedDate,
        remuneration: vacancy.remuneration || '',
        location: vacancy.location || 'Creighton Main Office',
        description: vacancy.description || '',
        requirements: vacancy.requirements || '',
        application_url: vacancy.application_url || 'https://forms.cloud.microsoft/r/Rvv4zeUt9Y',
        document_url: vacancy.document_url || '',
        document_name: vacancy.document_name || '',
        remove_document: false,
      }
      this.showModal = true
    },
    closeModal() {
      this.showModal = false
      this.editingVacancy = null
      this.selectedFile = null
    },
    handleFileChange(event) {
      const file = event.target.files[0]
      if (file) {
        this.selectedFile = file
        this.form.remove_document = false
      }
    },
    removeExistingDoc() {
      this.form.document_url = ''
      this.form.document_name = ''
      this.form.remove_document = true
      this.selectedFile = null
    },
    async saveVacancy() {
      this.saving = true
      this.notice = null
      this.error = null

      try {
        const formData = new FormData()
        formData.append('title', this.form.title)
        formData.append('reference_no', this.form.reference_no || '')
        formData.append('department', this.form.department)
        formData.append('status', this.form.status)
        if (this.form.closing_date) {
          formData.append('closing_date', this.form.closing_date)
        }
        formData.append('remuneration', this.form.remuneration || '')
        formData.append('location', this.form.location || '')
        formData.append('description', this.form.description || '')
        formData.append('requirements', this.form.requirements || '')
        formData.append('application_url', this.form.application_url || '')

        if (this.selectedFile) {
          formData.append('file', this.selectedFile)
        }

        if (this.form.remove_document) {
          formData.append('remove_document', '1')
        }

        if (this.editingVacancy) {
          formData.append('_method', 'PUT')
          await api.post(`/api/admin/vacancies/${this.editingVacancy.id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
          })
          this.notice = 'Vacancy updated successfully.'
        } else {
          await api.post('/api/admin/vacancies', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
          })
          this.notice = 'Vacancy published successfully.'
        }

        this.closeModal()
        await this.fetchVacancies()
      } catch (err) {
        const msg = err.response?.data?.message || err.message || 'Failed to save vacancy.'
        this.error = msg
      } finally {
        this.saving = false
      }
    },
    async toggleVacancyStatus(vacancy) {
      this.notice = null
      this.error = null
      try {
        const { data } = await api.post(`/api/admin/vacancies/${vacancy.id}/toggle-status`)
        vacancy.status = data.data.status
        this.notice = data.message || `Vacancy status changed to ${vacancy.status}.`
      } catch (err) {
        this.error = 'Failed to toggle vacancy status.'
      }
    },
    confirmDelete(vacancy) {
      this.deletingVacancy = vacancy
    },
    async executeDelete() {
      if (!this.deletingVacancy) return
      this.saving = true
      this.notice = null
      this.error = null
      try {
        await api.delete(`/api/admin/vacancies/${this.deletingVacancy.id}`)
        this.notice = 'Vacancy deleted successfully.'
        this.deletingVacancy = null
        await this.fetchVacancies()
      } catch (err) {
        this.error = 'Failed to delete vacancy.'
      } finally {
        this.saving = false
      }
    },
    formatDate(dateStr) {
      if (!dateStr) return 'Not specified'
      const d = new Date(dateStr)
      return d.toLocaleDateString('en-ZA', { year: 'numeric', month: 'short', day: 'numeric' })
    },
    isPastDate(dateStr) {
      if (!dateStr) return false
      return new Date(dateStr) < new Date()
    },
    formatFileSize(bytes) {
      if (!bytes) return ''
      const kb = bytes / 1024
      if (kb < 1024) return `${kb.toFixed(1)} KB`
      return `${(kb / 1024).toFixed(1)} MB`
    },
  },
}
</script>

<style scoped>
.portal-panel {
  background: #fff;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  padding: 1.5rem;
}

.filter-panel {
  padding: 1.25rem 1.5rem;
}

.filter-controls {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  justify-content: space-between;
  align-items: center;
}

.search-wrap {
  flex: 1;
  min-width: 280px;
}

.search-wrap input {
  width: 100%;
  padding: 0.65rem 1rem;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 0.9rem;
}

.filter-dropdowns {
  display: flex;
  gap: 0.75rem;
}

.filter-dropdowns select {
  padding: 0.65rem 1rem;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 0.9rem;
  background-color: #fff;
}

.btn-primary-action {
  background: var(--primary, #1f9c58);
  color: #fff;
  border: none;
  padding: 0.65rem 1.25rem;
  border-radius: 6px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-primary-action:hover {
  background: var(--primary-mid, #188249);
}

.manager-table {
  width: 100%;
  border-collapse: collapse;
}

.manager-table th {
  background: #f8fafc;
  padding: 0.85rem 1rem;
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: uppercase;
  color: #475569;
  border-bottom: 2px solid #e2e8f0;
  text-align: left;
}

.manager-table td {
  padding: 1rem;
  border-bottom: 1px solid #e2e8f0;
  font-size: 0.9rem;
  vertical-align: middle;
}

.ref-badge {
  display: inline-block;
  background: #f1f5f9;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-weight: 600;
  font-size: 0.8rem;
  color: #334155;
  border: 1px solid #cbd5e1;
}

.table-title {
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.2rem;
}

.table-subtext {
  font-size: 0.8rem;
  color: #64748b;
}

.dept-badge-sm {
  font-size: 0.85rem;
  color: #334155;
}

.badge-toggle {
  border: none;
  padding: 0.35rem 0.75rem;
  border-radius: 50px;
  font-size: 0.8rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  cursor: pointer;
  transition: transform 0.15s, opacity 0.15s;
}

.badge-toggle:hover {
  transform: scale(1.05);
  opacity: 0.9;
}

.status-open {
  background: #dcfce7;
  color: #15803d;
}

.status-closed {
  background: #fee2e2;
  color: #b91c1c;
}

.closing-cell {
  display: flex;
  flex-direction: column;
}

.closing-cell small {
  font-size: 0.75rem;
  font-weight: 600;
}

.btn-file-download {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  padding: 0.35rem 0.75rem;
  border-radius: 6px;
  font-size: 0.8rem;
  color: #0284c7;
  text-decoration: none;
  max-width: 180px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.btn-file-download:hover {
  background: #e0f2fe;
}

.row-actions {
  display: flex;
  gap: 0.5rem;
  justify-content: flex-end;
}

.btn-action {
  border: 1px solid #cbd5e1;
  background: #fff;
  padding: 0.4rem 0.65rem;
  border-radius: 4px;
  font-size: 0.8rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  transition: all 0.2s;
}

.btn-action.edit:hover {
  background: #f1f5f9;
  color: #0284c7;
  border-color: #0284c7;
}

.btn-action.delete {
  color: #dc2626;
}

.btn-action.delete:hover {
  background: #fee2e2;
  border-color: #dc2626;
}

/* Modal styles */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
  padding: 1.5rem;
}

.modal-dialog-custom {
  background: #fff;
  border-radius: 10px;
  max-width: 750px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.modal-dialog-custom.modal-confirm {
  max-width: 450px;
}

.modal-header-custom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.modal-header-custom h3 {
  font-size: 1.25rem;
  font-weight: 700;
  margin: 0;
  color: #1e293b;
}

.btn-close-custom {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #64748b;
}

.modal-body-custom {
  padding: 1.5rem;
}

.form-row-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
}

.form-row-3 {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 0.4rem;
  color: #334155;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 0.65rem 0.85rem;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 0.9rem;
}

.form-text-muted {
  font-size: 0.75rem;
  color: #64748b;
  margin-top: 0.25rem;
  display: block;
}

.required {
  color: #dc2626;
}

.existing-doc-box {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 0.75rem 1rem;
  border-radius: 6px;
  margin-bottom: 0.75rem;
}

.existing-doc-box .doc-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
}

.existing-doc-box a {
  color: #0284c7;
  text-decoration: underline;
}

.btn-remove-doc {
  background: none;
  border: none;
  color: #dc2626;
  font-size: 0.8rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
}

.file-drop-area {
  position: relative;
  border: 2px dashed #cbd5e1;
  border-radius: 8px;
  padding: 1.25rem;
  text-align: center;
  background: #f8fafc;
}

.file-drop-area input[type='file'] {
  position: absolute;
  inset: 0;
  opacity: 0;
  cursor: pointer;
  width: 100%;
  height: 100%;
}

.file-drop-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.85rem;
  color: #64748b;
  cursor: pointer;
}

.modal-footer-custom {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding-top: 1rem;
  border-top: 1px solid #e2e8f0;
}

.btn-cancel {
  background: #f1f5f9;
  border: 1px solid #cbd5e1;
  padding: 0.65rem 1.25rem;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  color: #475569;
}

.btn-danger-confirm {
  background: #dc2626;
  border: none;
  color: #fff;
  padding: 0.65rem 1.25rem;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
}

.portal-alert {
  padding: 0.75rem 1.25rem;
  border-radius: 6px;
  margin-bottom: 1.25rem;
  font-size: 0.9rem;
}

.portal-alert.success {
  background: #dcfce7;
  color: #15803d;
  border: 1px solid #86efac;
}

.portal-alert.danger {
  background: #fee2e2;
  color: #b91c1c;
  border: 1px solid #fca5a5;
}

.loading-state,
.empty-state {
  text-align: center;
  padding: 3rem 1.5rem;
  color: #64748b;
}

@media (max-width: 768px) {
  .form-row-2,
  .form-row-3 {
    grid-template-columns: 1fr;
  }
}
</style>

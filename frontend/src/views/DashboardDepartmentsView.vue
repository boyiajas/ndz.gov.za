<template>
  <DashboardLayout
    title="Municipal Departments"
    subtitle="Configure municipal directorates, leadership titles, and document permission groupings."
  >
    <template #header-actions>
      <div class="header-action-group">
        <button
          type="button"
          class="btn-secondary-action"
          :disabled="seeding"
          title="Verify and re-sync default municipal directorates"
          @click="seedDefaultDepartments"
        >
          <span>🔄</span> {{ seeding ? 'Syncing Directorates...' : 'Verify Default Directorates' }}
        </button>
        <button
          type="button"
          class="btn-primary-action"
          @click="openAddModal"
        >
          <span>＋</span> New Department
        </button>
      </div>
    </template>

    <div v-if="notice" class="portal-alert success">{{ notice }}</div>
    <div v-if="error" class="portal-alert danger">{{ error }}</div>

    <!-- Summary metrics -->
    <section class="dashboard-grid">
      <article class="portal-stat-card">
        <div class="portal-stat-icon dept-icon">🏛</div>
        <div>
          <strong>{{ summary.total_departments || departments.length }}</strong>
          <span>Total Directorates</span>
        </div>
      </article>

      <article class="portal-stat-card">
        <div class="portal-stat-icon active-icon">✓</div>
        <div>
          <strong>{{ summary.active_departments || activeCount }}</strong>
          <span>Active Departments</span>
        </div>
      </article>

      <article class="portal-stat-card">
        <div class="portal-stat-icon cat-icon">🗂</div>
        <div>
          <strong>{{ summary.total_categories_mapped ?? totalCategoriesCount }}</strong>
          <span>Mapped Categories</span>
        </div>
      </article>

      <article class="portal-stat-card">
        <div class="portal-stat-icon doc-icon">📄</div>
        <div>
          <strong>{{ summary.total_documents_mapped ?? totalDocumentsCount }}</strong>
          <span>Managed Documents</span>
        </div>
      </article>
    </section>

    <!-- Main Content Panel -->
    <section class="portal-panel mt-4">
      <div class="panel-header-controls">
        <div class="search-box">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by department name, code, email, or leadership..."
            @input="filterDepartments"
          />
        </div>

        <div class="status-filter">
          <select v-model="filterStatus" @change="filterDepartments">
            <option value="all">All Statuses</option>
            <option value="active">Active Only</option>
            <option value="inactive">Inactive Only</option>
          </select>
        </div>
      </div>

      <!-- Loading / Empty / Table -->
      <div v-if="loading" class="loading-state">Loading municipal departments...</div>
      <div v-else-if="filteredDepartments.length === 0" class="empty-state">
        <p>No municipal departments found matching your criteria.</p>
        <button type="button" class="btn-create-first" @click="openAddModal">Create First Department</button>
      </div>

      <div v-else class="table-responsive">
        <table class="manager-table">
          <thead>
            <tr>
              <th style="width: 80px;">Code</th>
              <th>Department & Directorate</th>
              <th>Leadership Title</th>
              <th>Official Contact</th>
              <th style="text-align: center;">Categories</th>
              <th style="text-align: center;">Files</th>
              <th style="text-align: center;">Staff</th>
              <th>Status</th>
              <th style="text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="dept in filteredDepartments" :key="dept.id">
              <td>
                <span class="dept-code-tag">{{ dept.code }}</span>
              </td>
              <td>
                <div class="dept-name-cell">
                  <strong>{{ dept.name }}</strong>
                  <small v-if="dept.description" class="dept-desc-preview">{{ dept.description }}</small>
                </div>
              </td>
              <td>
                <span class="leadership-title">{{ dept.head_title || '—' }}</span>
              </td>
              <td>
                <a v-if="dept.email" :href="`mailto:${dept.email}`" class="dept-email-link">
                  {{ dept.email }}
                </a>
                <span v-else class="text-muted">—</span>
              </td>
              <td style="text-align: center;">
                <span class="metric-pill cat-pill" :title="`${dept.categories_count || 0} document categories mapped`">
                  {{ dept.categories_count || 0 }}
                </span>
              </td>
              <td style="text-align: center;">
                <span class="metric-pill doc-pill" :title="`${dept.documents_count || 0} documents assigned`">
                  {{ dept.documents_count || 0 }}
                </span>
              </td>
              <td style="text-align: center;">
                <span class="metric-pill user-pill" :title="`${dept.users_count || 0} assigned portal users`">
                  {{ dept.users_count || 0 }}
                </span>
              </td>
              <td>
                <span :class="['status-badge', dept.is_active ? 'active' : 'inactive']">
                  {{ dept.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td style="text-align: right;">
                <div class="action-buttons">
                  <button type="button" class="btn-edit" @click="openEditModal(dept)">Edit</button>
                  <button
                    type="button"
                    class="btn-delete"
                    :disabled="dept.categories_count > 0 || dept.documents_count > 0"
                    :title="(dept.categories_count > 0 || dept.documents_count > 0) ? 'Cannot delete department with assigned categories/files' : 'Delete department'"
                    @click="deleteDepartment(dept)"
                  >
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Create / Edit Department Modal Sheet -->
    <div v-if="isModalOpen" class="modal-backdrop" @click.self="closeModal">
      <div class="modal-sheet">
        <div class="modal-sheet-header">
          <div>
            <p>{{ editingDeptId ? 'Modify Department' : 'New Directorate' }}</p>
            <h3>{{ editingDeptId ? 'Edit Municipal Department' : 'Create Municipal Department' }}</h3>
          </div>
          <button type="button" class="close-btn" @click="closeModal">&times;</button>
        </div>

        <form class="modal-sheet-form" @submit.prevent="saveDepartment">
          <div class="form-row">
            <label style="flex: 2;">
              Department Name *
              <input
                v-model="form.name"
                type="text"
                placeholder="e.g. Budget & Treasury"
                required
                @input="onNameInput"
              />
            </label>

            <label style="flex: 1;">
              Code *
              <input
                v-model="form.code"
                type="text"
                placeholder="e.g. BTO"
                maxlength="20"
                required
                style="text-transform: uppercase;"
              />
            </label>
          </div>

          <div class="form-row">
            <label style="flex: 1;">
              URL Slug
              <input
                v-model="form.slug"
                type="text"
                placeholder="e.g. budget-treasury"
              />
              <small class="field-hint">Leave blank to generate automatically from name.</small>
            </label>

            <label style="flex: 1;">
              Official Email
              <input
                v-model="form.email"
                type="email"
                placeholder="e.g. bto@ndz.gov.za"
              />
            </label>
          </div>

          <label>
            Leadership / Head of Department Title
            <input
              v-model="form.head_title"
              type="text"
              placeholder="e.g. Chief Financial Officer"
            />
          </label>

          <label>
            Department Description & Responsibilities
            <textarea
              v-model="form.description"
              rows="3"
              placeholder="Summary of core responsibilities, municipal mandate, and functions..."
            ></textarea>
          </label>

          <div class="form-row">
            <label style="flex: 1;">
              Display Sort Order
              <input
                v-model.number="form.sort_order"
                type="number"
                min="0"
                placeholder="0"
              />
            </label>

            <label class="check-row" style="flex: 1; align-items: center; margin-top: 1.5rem;">
              <input v-model="form.is_active" type="checkbox" />
              <span>Department is Active</span>
            </label>
          </div>

          <div class="modal-footer-actions">
            <button type="button" class="btn-cancel" @click="closeModal">Cancel</button>
            <button type="submit" class="btn-save" :disabled="saving">
              {{ saving ? 'Saving Department...' : (editingDeptId ? 'Update Department' : 'Create Department') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../components/DashboardLayout.vue'
import { useAuthStore } from '../stores/auth'
import api from '../api/axios'

export default {
  name: 'DashboardDepartmentsView',
  components: { DashboardLayout },
  setup() {
    return { auth: useAuthStore() }
  },
  data() {
    return {
      departments: [],
      filteredDepartments: [],
      summary: {},
      searchQuery: '',
      filterStatus: 'all',
      loading: true,
      saving: false,
      seeding: false,
      notice: '',
      error: '',
      isModalOpen: false,
      editingDeptId: null,
      form: {
        name: '',
        code: '',
        slug: '',
        email: '',
        head_title: '',
        description: '',
        sort_order: 0,
        is_active: true,
      },
    }
  },
  computed: {
    activeCount() {
      return this.departments.filter((d) => d.is_active).length
    },
    totalCategoriesCount() {
      return this.departments.reduce((sum, d) => sum + (d.categories_count || 0), 0)
    },
    totalDocumentsCount() {
      return this.departments.reduce((sum, d) => sum + (d.documents_count || 0), 0)
    },
  },
  mounted() {
    this.fetchDepartments()
  },
  methods: {
    async fetchDepartments() {
      this.loading = true
      this.error = ''
      try {
        const { data } = await api.get('/api/admin/departments')
        this.departments = data.data || []
        this.summary = data.summary || {}
        this.filterDepartments()
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to load municipal departments.'
      } finally {
        this.loading = false
      }
    },
    filterDepartments() {
      let list = [...this.departments]
      if (this.filterStatus !== 'all') {
        const isActive = this.filterStatus === 'active'
        list = list.filter((d) => Boolean(d.is_active) === isActive)
      }
      if (this.searchQuery.trim()) {
        const q = this.searchQuery.toLowerCase().trim()
        list = list.filter(
          (d) =>
            d.name?.toLowerCase().includes(q) ||
            d.code?.toLowerCase().includes(q) ||
            d.email?.toLowerCase().includes(q) ||
            d.head_title?.toLowerCase().includes(q) ||
            d.description?.toLowerCase().includes(q)
        )
      }
      this.filteredDepartments = list
    },
    onNameInput() {
      if (!this.editingDeptId && !this.form.code) {
        // Auto-generate suggested code
        const words = this.form.name.trim().split(/\s+/)
        if (words.length > 1) {
          this.form.code = words.map((w) => w[0]).join('').toUpperCase().slice(0, 5)
        }
      }
    },
    openAddModal() {
      this.editingDeptId = null
      this.form = {
        name: '',
        code: '',
        slug: '',
        email: '',
        head_title: '',
        description: '',
        sort_order: this.departments.length + 1,
        is_active: true,
      }
      this.isModalOpen = true
    },
    openEditModal(dept) {
      this.editingDeptId = dept.id
      this.form = {
        name: dept.name,
        code: dept.code,
        slug: dept.slug || '',
        email: dept.email || '',
        head_title: dept.head_title || '',
        description: dept.description || '',
        sort_order: dept.sort_order || 0,
        is_active: Boolean(dept.is_active),
      }
      this.isModalOpen = true
    },
    closeModal() {
      this.isModalOpen = false
      this.editingDeptId = null
    },
    async saveDepartment() {
      this.saving = true
      this.error = ''
      this.notice = ''

      try {
        if (this.editingDeptId) {
          await api.put(`/api/admin/departments/${this.editingDeptId}`, this.form)
          this.notice = 'Department updated successfully.'
        } else {
          await api.post('/api/admin/departments', this.form)
          this.notice = 'New municipal department created successfully.'
        }
        this.closeModal()
        await this.fetchDepartments()
        setTimeout(() => { this.notice = '' }, 4000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to save department.'
      } finally {
        this.saving = false
      }
    },
    async deleteDepartment(dept) {
      if (!confirm(`Are you sure you want to delete department "${dept.name}" (${dept.code})?`)) {
        return
      }

      try {
        await api.delete(`/api/admin/departments/${dept.id}`)
        this.notice = 'Department deleted successfully.'
        await this.fetchDepartments()
        setTimeout(() => { this.notice = '' }, 4000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to delete department.'
      }
    },
    async seedDefaultDepartments() {
      this.seeding = true
      this.error = ''
      this.notice = ''
      try {
        const { data } = await api.post('/api/admin/departments/seed-defaults')
        this.notice = data.message || 'Default municipal directorates synchronized.'
        await this.fetchDepartments()
        setTimeout(() => { this.notice = '' }, 4000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to sync default directorates.'
      } finally {
        this.seeding = false
      }
    },
  },
}
</script>

<style scoped>
.header-action-group {
  display: flex;
  gap: 0.65rem;
  align-items: center;
}

.btn-secondary-action {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  background: #ffffff;
  color: #334155;
  border: 1px solid #cbd5e1;
  padding: 0.55rem 1rem;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.15s ease;
}

.btn-secondary-action:hover {
  background: #f8fafc;
  border-color: #94a3b8;
}

.btn-primary-action {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  background: #0f6b3b;
  color: #ffffff;
  border: none;
  padding: 0.55rem 1.15rem;
  border-radius: 8px;
  font-weight: 800;
  font-size: 0.85rem;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(15, 107, 59, 0.2);
  transition: all 0.15s ease;
}

.btn-primary-action:hover {
  background: #0d5c32;
  box-shadow: 0 4px 8px rgba(15, 107, 59, 0.3);
}

.panel-header-controls {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.25rem;
  flex-wrap: wrap;
}

.search-box {
  flex: 1;
  min-width: 260px;
}

.search-box input {
  width: 100%;
  padding: 0.55rem 0.85rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.88rem;
  box-sizing: border-box;
}

.search-box input:focus {
  outline: none;
  border-color: #0f6b3b;
}

.status-filter select {
  padding: 0.55rem 0.85rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.88rem;
  background: #ffffff;
  cursor: pointer;
}

.dept-code-tag {
  display: inline-block;
  background: #1e293b;
  color: #f8fafc;
  font-weight: 800;
  font-size: 0.76rem;
  padding: 0.25rem 0.6rem;
  border-radius: 6px;
  letter-spacing: 0.05em;
}

.dept-name-cell strong {
  display: block;
  font-size: 0.94rem;
  color: #0f172a;
}

.dept-desc-preview {
  display: block;
  color: #64748b;
  font-size: 0.78rem;
  margin-top: 0.15rem;
  line-height: 1.3;
  max-width: 320px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.leadership-title {
  font-size: 0.84rem;
  color: #334155;
  font-weight: 600;
}

.dept-email-link {
  color: #0f6b3b;
  font-size: 0.82rem;
  font-weight: 600;
  text-decoration: none;
}

.dept-email-link:hover {
  text-decoration: underline;
}

.metric-pill {
  display: inline-block;
  min-width: 26px;
  padding: 0.15rem 0.45rem;
  border-radius: 999px;
  font-size: 0.76rem;
  font-weight: 800;
}

.cat-pill { background: #e0f2fe; color: #0369a1; }
.doc-pill { background: #fef3c7; color: #b45309; }
.user-pill { background: #ede9fe; color: #6d28d9; }

.status-badge {
  display: inline-block;
  padding: 0.2rem 0.6rem;
  border-radius: 999px;
  font-size: 0.72rem;
  font-weight: 800;
  text-transform: uppercase;
}

.status-badge.active {
  background: #dcfce7;
  color: #15803d;
}

.status-badge.inactive {
  background: #fee2e2;
  color: #b91c1c;
}

.action-buttons {
  display: flex;
  gap: 0.4rem;
  justify-content: flex-end;
}

.btn-edit {
  border: 1px solid #c2dbcd;
  background: #f0f7f3;
  color: #0f6b3b;
  padding: 0.3rem 0.75rem;
  border-radius: 6px;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
}

.btn-delete {
  border: 1px solid #fecaca;
  background: #fff1f2;
  color: #e11d48;
  padding: 0.3rem 0.75rem;
  border-radius: 6px;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
}

.btn-delete:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.btn-create-first {
  background: #0f6b3b;
  color: #ffffff;
  border: none;
  padding: 0.6rem 1.25rem;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
  margin-top: 1rem;
}

.field-hint {
  font-size: 0.74rem;
  color: #64748b;
  margin-top: 0.2rem;
  display: block;
}

.dept-icon { background: rgba(15, 107, 59, 0.12); color: #0f6b3b; }
.active-icon { background: rgba(22, 163, 74, 0.12); color: #16a34a; }
.cat-icon { background: rgba(2, 132, 199, 0.12); color: #0284c7; }
.doc-icon { background: rgba(217, 119, 6, 0.12); color: #d97706; }

.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.55);
  display: grid;
  place-items: center;
  z-index: 1200;
  padding: 1rem;
}

.modal-sheet {
  background: #ffffff;
  border-radius: 14px;
  width: 100%;
  max-width: 640px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-sheet-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #f1f5f9;
}

.modal-sheet-header p {
  font-size: 0.75rem;
  text-transform: uppercase;
  color: #64748b;
  font-weight: 800;
  margin: 0;
  letter-spacing: 0.05em;
}

.modal-sheet-header h3 {
  font-size: 1.2rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0.15rem 0 0 0;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: #94a3b8;
  cursor: pointer;
  line-height: 1;
}

.modal-sheet-form {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1.1rem;
}

.form-row {
  display: flex;
  gap: 1rem;
}

.modal-sheet-form label {
  display: flex;
  flex-direction: column;
  font-size: 0.84rem;
  font-weight: 700;
  color: #334155;
  gap: 0.35rem;
}

.modal-sheet-form input[type="text"],
.modal-sheet-form input[type="email"],
.modal-sheet-form input[type="number"],
.modal-sheet-form textarea {
  padding: 0.55rem 0.85rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.88rem;
  outline: none;
  box-sizing: border-box;
}

.modal-sheet-form input:focus,
.modal-sheet-form textarea:focus {
  border-color: #0f6b3b;
}

.check-row {
  flex-direction: row !important;
  cursor: pointer;
  user-select: none;
}

.check-row input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: #0f6b3b;
}

.modal-footer-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 0.5rem;
  padding-top: 1rem;
  border-top: 1px solid #f1f5f9;
}

.btn-cancel {
  background: #f1f5f9;
  border: 1px solid #cbd5e1;
  color: #475569;
  padding: 0.55rem 1.1rem;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
}

.btn-save {
  background: #0f6b3b;
  border: none;
  color: #ffffff;
  padding: 0.55rem 1.25rem;
  border-radius: 8px;
  font-weight: 800;
  cursor: pointer;
}

.btn-save:hover {
  background: #0d5c32;
}

@media (max-width: 640px) {
  .form-row {
    flex-direction: column;
  }
}
</style>

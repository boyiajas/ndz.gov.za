<template>
  <DashboardLayout
    title="Tenders & Procurement Manager"
    subtitle="Publish municipal tenders, request for quotes (RFQs), set closing dates, and upload bid specification packs."
  >
    <template #header-actions>
      <button type="button" class="btn-primary-action" @click="openCreateModal">
        <span>+</span> Post Tender / Quote
      </button>
    </template>

    <div v-if="notice" class="portal-alert success">{{ notice }}</div>
    <div v-if="error" class="portal-alert danger">{{ error }}</div>

    <!-- Filter Bar -->
    <section class="portal-panel filter-panel mb-4">
      <div class="filter-controls">
        <div class="search-wrap">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by tender title or reference number..."
            @input="filterNotices"
          />
        </div>

        <div class="filter-dropdowns">
          <select v-model="filterType" @change="filterNotices">
            <option value="all">All Types</option>
            <option value="tender">Tenders</option>
            <option value="quote">Quotations</option>
          </select>

          <select v-model="filterStatus" @change="filterNotices">
            <option value="all">All Statuses</option>
            <option value="open">Open</option>
            <option value="closed">Closed</option>
          </select>
        </div>
      </div>
    </section>

    <!-- Table of Procurement Notices -->
    <section class="portal-panel">
      <div v-if="loading" class="loading-state">Loading procurement listings...</div>
      <div v-else-if="filteredNotices.length === 0" class="empty-state">
        <p>No procurement notices found matching your filters.</p>
        <button type="button" class="btn-primary-action" @click="openCreateModal">
          Create Tender Notice
        </button>
      </div>

      <div v-else class="table-responsive">
        <table class="manager-table">
          <thead>
            <tr>
              <th>Reference No</th>
              <th>Title & Scope</th>
              <th>Type</th>
              <th>Financial Year</th>
              <th>Closing Date</th>
              <th>Document</th>
              <th>Status</th>
              <th style="text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="notice in filteredNotices" :key="notice.id">
              <td>
                <span class="ref-pill">{{ notice.reference_no }}</span>
              </td>
              <td>
                <strong class="notice-title">{{ notice.title }}</strong>
                <p v-if="notice.contact_person" class="notice-contact">Contact: {{ notice.contact_person }}</p>
              </td>
              <td>
                <span :class="['type-pill', notice.type]">
                  {{ notice.type === 'tender' ? 'Tender' : 'Quote' }}
                </span>
              </td>
              <td>
                <span class="year-text">{{ notice.financial_year }}</span>
              </td>
              <td>
                <span class="closing-date">{{ formatDate(notice.closing_date) }}</span>
              </td>
              <td>
                <a
                  v-if="notice.document_url"
                  :href="notice.document_url"
                  target="_blank"
                  rel="noopener"
                  class="doc-link"
                >
                  📄 View Pack
                </a>
                <span v-else class="no-doc">No document</span>
              </td>
              <td>
                <span :class="['status-pill', notice.status]">
                  {{ notice.status }}
                </span>
              </td>
              <td style="text-align: right;">
                <div class="action-buttons">
                  <button type="button" class="btn-edit" @click="openEditModal(notice)">Edit</button>
                  <button type="button" class="btn-delete" @click="deleteNotice(notice)">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Create / Edit Modal -->
    <div v-if="isModalOpen" class="modal-backdrop" @click.self="closeModal">
      <div class="modal-sheet">
        <div class="modal-sheet-header">
          <div>
            <p>{{ editingNoticeId ? 'Update SCM Listing' : 'New SCM Listing' }}</p>
            <h3>{{ editingNoticeId ? 'Edit Procurement Notice' : 'Post Tender / Quotation' }}</h3>
          </div>
          <button type="button" class="close-btn" @click="closeModal">&times;</button>
        </div>

        <form class="modal-sheet-form" @submit.prevent="saveNotice">
          <div class="form-grid-2">
            <label>
              Reference Number *
              <input v-model="form.reference_no" type="text" placeholder="e.g. NDZ-PW-004-2025" required />
            </label>

            <label>
              Financial Year *
              <input v-model="form.financial_year" type="text" placeholder="e.g. 2025/2026" required />
            </label>
          </div>

          <label>
            Tender / Quotation Title *
            <input v-model="form.title" type="text" placeholder="e.g. Construction of Community Access Road" required />
          </label>

          <div class="form-grid-2">
            <label>
              Procurement Type *
              <select v-model="form.type" required>
                <option value="tender">Tender</option>
                <option value="quote">Quotation (RFQ)</option>
              </select>
            </label>

            <label>
              Status *
              <select v-model="form.status" required>
                <option value="open">Open / Active</option>
                <option value="closed">Closed / Awarded</option>
              </select>
            </label>
          </div>

          <div class="form-grid-2">
            <label>
              Closing Date & Time
              <input v-model="form.closing_date" type="datetime-local" />
            </label>

            <label>
              Contact Person / Unit
              <input v-model="form.contact_person" type="text" placeholder="e.g. SCM Unit - scm@ndz.gov.za" />
            </label>
          </div>

          <label>
            Briefing Session Details
            <input v-model="form.briefing_date" type="text" placeholder="e.g. Compulsory briefing 25 March 2025 at 10:00 AM" />
          </label>

          <label>
            Description / Scope of Work
            <textarea v-model="form.description" rows="3" placeholder="Scope of services, CIDB grading, submission requirements..."></textarea>
          </label>

          <!-- Tender Document with FileUploadButton -->
          <div class="doc-upload-section">
            <label>Bid Specification Document / Pack</label>
            <div class="doc-input-row">
              <input v-model="form.document_url" type="text" placeholder="Enter document URL or click Upload Document..." />
              <FileUploadButton
                folder="tenders"
                accept=".pdf,.doc,.docx,.xls,.xlsx,.zip"
                label="Upload Spec Pack"
                icon="📎"
                @uploaded="onDocUploaded"
              />
            </div>
            <small class="hint">Accepts PDF, Word documents, or ZIP tender packs up to 25MB.</small>
          </div>

          <div class="modal-footer-actions">
            <button type="button" class="btn-cancel" @click="closeModal">Cancel</button>
            <button type="submit" class="btn-save" :disabled="saving">
              {{ saving ? 'Saving Notice...' : (editingNoticeId ? 'Update Notice' : 'Post Listing') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../components/DashboardLayout.vue'
import FileUploadButton from '../components/FileUploadButton.vue'
import api from '../api/axios'

export default {
  name: 'DashboardTendersView',
  components: { DashboardLayout, FileUploadButton },
  data() {
    return {
      notices: [],
      filteredNotices: [],
      filterType: 'all',
      filterStatus: 'all',
      searchQuery: '',
      loading: true,
      saving: false,
      notice: '',
      error: '',
      isModalOpen: false,
      editingNoticeId: null,
      form: {
        reference_no: '',
        title: '',
        type: 'tender',
        status: 'open',
        financial_year: '2025/2026',
        closing_date: '',
        briefing_date: '',
        contact_person: '',
        description: '',
        document_url: '',
      },
    }
  },
  mounted() {
    this.fetchNotices()
  },
  methods: {
    async fetchNotices() {
      this.loading = true
      try {
        const { data } = await api.get('/api/admin/procurement')
        this.notices = data.data || []
        this.filterNotices()
      } catch (err) {
        this.error = 'Failed to load procurement notices.'
      } finally {
        this.loading = false
      }
    },
    filterNotices() {
      let list = [...this.notices]
      if (this.filterType !== 'all') {
        list = list.filter((n) => n.type === this.filterType)
      }
      if (this.filterStatus !== 'all') {
        list = list.filter((n) => n.status === this.filterStatus)
      }
      if (this.searchQuery.trim()) {
        const q = this.searchQuery.toLowerCase()
        list = list.filter(
          (n) =>
            n.title?.toLowerCase().includes(q) ||
            n.reference_no?.toLowerCase().includes(q)
        )
      }
      this.filteredNotices = list
    },
    openCreateModal() {
      this.editingNoticeId = null
      this.form = {
        reference_no: '',
        title: '',
        type: 'tender',
        status: 'open',
        financial_year: '2025/2026',
        closing_date: '',
        briefing_date: '',
        contact_person: '',
        description: '',
        document_url: '',
      }
      this.isModalOpen = true
    },
    openEditModal(n) {
      this.editingNoticeId = n.id
      this.form = {
        reference_no: n.reference_no,
        title: n.title,
        type: n.type,
        status: n.status,
        financial_year: n.financial_year || '2025/2026',
        closing_date: n.closing_date ? n.closing_date.replace(' ', 'T').slice(0, 16) : '',
        briefing_date: n.briefing_date || '',
        contact_person: n.contact_person || '',
        description: n.description || '',
        document_url: n.document_url || '',
      }
      this.isModalOpen = true
    },
    closeModal() {
      this.isModalOpen = false
      this.editingNoticeId = null
    },
    onDocUploaded(url) {
      this.form.document_url = url
      this.notice = 'Tender specification file uploaded successfully.'
      setTimeout(() => { this.notice = '' }, 3000)
    },
    async saveNotice() {
      this.saving = true
      this.error = ''
      this.notice = ''

      try {
        if (this.editingNoticeId) {
          await api.put(`/api/admin/procurement/${this.editingNoticeId}`, this.form)
          this.notice = 'Procurement notice updated successfully.'
        } else {
          await api.post('/api/admin/procurement', this.form)
          this.notice = 'Procurement notice posted successfully.'
        }
        this.closeModal()
        await this.fetchNotices()
        setTimeout(() => { this.notice = '' }, 4000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to save procurement notice.'
      } finally {
        this.saving = false
      }
    },
    async deleteNotice(n) {
      if (!confirm(`Are you sure you want to delete "${n.reference_no}"?`)) {
        return
      }
      try {
        await api.delete(`/api/admin/procurement/${n.id}`)
        this.notice = 'Notice deleted.'
        await this.fetchNotices()
        setTimeout(() => { this.notice = '' }, 3000)
      } catch (err) {
        this.error = 'Failed to delete notice.'
      }
    },
    formatDate(d) {
      if (!d) return 'Not specified'
      return new Date(d).toLocaleDateString('en-ZA', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      })
    },
  },
}
</script>

<style scoped>
.btn-primary-action {
  background: #0f6b3b;
  color: #ffffff;
  border: 0;
  border-radius: 12px;
  padding: 0.65rem 1.25rem;
  font-weight: 800;
  font-size: 0.9rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  box-shadow: 0 4px 14px rgba(15, 107, 59, 0.25);
  transition: all 0.2s ease;
}

.btn-primary-action:hover {
  background: #0c562f;
  transform: translateY(-1px);
}

.filter-panel {
  padding: 1rem 1.25rem;
}

.filter-controls {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
}

.search-wrap {
  flex: 1;
  min-width: 260px;
}

.search-wrap input {
  width: 100%;
  padding: 0.65rem 1rem;
  border: 1px solid #d9e6de;
  border-radius: 12px;
  font-size: 0.9rem;
  background: #fbfdfc;
}

.filter-dropdowns {
  display: flex;
  gap: 0.5rem;
}

.filter-dropdowns select {
  padding: 0.6rem 0.9rem;
  border: 1px solid #d9e6de;
  border-radius: 10px;
  font-size: 0.85rem;
  background: #fbfdfc;
  color: #17231c;
  font-weight: 600;
}

.portal-panel {
  background: #ffffff;
  border: 1px solid #e4ece7;
  border-radius: 20px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(15, 107, 59, 0.04);
}

.table-responsive {
  overflow-x: auto;
}

.manager-table {
  width: 100%;
  border-collapse: collapse;
}

.manager-table th {
  text-align: left;
  padding: 0.8rem 1rem;
  font-size: 0.76rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: #6c7d73;
  border-bottom: 2px solid #edf3ef;
}

.manager-table td {
  padding: 1rem;
  border-bottom: 1px solid #f0f5f2;
  vertical-align: middle;
}

.ref-pill {
  background: #edf6f0;
  color: #0f6b3b;
  border: 1px solid #cce5d6;
  border-radius: 6px;
  padding: 0.25rem 0.6rem;
  font-size: 0.78rem;
  font-weight: 800;
  white-space: nowrap;
}

.notice-title {
  display: block;
  font-size: 0.92rem;
  color: #17231c;
}

.notice-contact {
  margin: 0.2rem 0 0;
  font-size: 0.76rem;
  color: #6c7d73;
}

.type-pill {
  display: inline-block;
  padding: 0.2rem 0.6rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
}

.type-pill.tender { background: #ede7f6; color: #512da8; }
.type-pill.quote { background: #e0f2fe; color: #0369a1; }

.year-text {
  font-size: 0.82rem;
  font-weight: 700;
  color: #17231c;
}

.closing-date {
  font-size: 0.82rem;
  color: #4a5c52;
}

.doc-link {
  color: #0f6b3b;
  font-size: 0.8rem;
  font-weight: 700;
  text-decoration: none;
}

.doc-link:hover {
  text-decoration: underline;
}

.no-doc {
  color: #a0afa6;
  font-size: 0.78rem;
}

.status-pill {
  display: inline-block;
  padding: 0.2rem 0.65rem;
  border-radius: 999px;
  font-size: 0.72rem;
  font-weight: 800;
  text-transform: uppercase;
}

.status-pill.open { background: #e8f5e9; color: #2e7d32; }
.status-pill.closed { background: #f5f5f5; color: #757575; }

.action-buttons {
  display: flex;
  gap: 0.4rem;
  justify-content: flex-end;
}

.btn-edit {
  border: 1px solid #c2dbcd;
  background: #f0f7f3;
  color: #0f6b3b;
  padding: 0.3rem 0.7rem;
  border-radius: 8px;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
}

.btn-delete {
  border: 1px solid #fcd2d2;
  background: #fff3f3;
  color: #d9383a;
  padding: 0.3rem 0.7rem;
  border-radius: 8px;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
}

/* Modal */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 19, 0.6);
  backdrop-filter: blur(4px);
  z-index: 999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
}

.modal-sheet {
  background: #ffffff;
  width: 100%;
  max-width: 700px;
  max-height: 90vh;
  overflow-y: auto;
  border-radius: 24px;
  padding: 2rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.modal-sheet-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid #edf3ef;
  padding-bottom: 1rem;
}

.modal-sheet-header p {
  margin: 0 0 0.2rem;
  color: #0f6b3b;
  font-size: 0.75rem;
  font-weight: 800;
  text-transform: uppercase;
}

.modal-sheet-header h3 {
  margin: 0;
  font-size: 1.35rem;
  font-weight: 800;
  color: #17231c;
}

.close-btn {
  background: transparent;
  border: 0;
  font-size: 1.8rem;
  line-height: 1;
  color: #6c7d73;
  cursor: pointer;
}

.modal-sheet-form {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.form-grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.modal-sheet-form label {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
  font-size: 0.85rem;
  font-weight: 700;
  color: #17231c;
}

.modal-sheet-form input,
.modal-sheet-form select,
.modal-sheet-form textarea {
  border: 1px solid #d9e6de;
  border-radius: 12px;
  padding: 0.65rem 0.85rem;
  font-size: 0.9rem;
  background: #fbfdfc;
  color: #17231c;
}

.doc-upload-section {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}

.doc-input-row {
  display: flex;
  gap: 0.5rem;
}

.doc-input-row input {
  flex: 1;
}

.hint {
  font-size: 0.75rem;
  color: #6c7d73;
}

.modal-footer-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 1rem;
  padding-top: 1.25rem;
  border-top: 1px solid #edf3ef;
}

.btn-cancel {
  border: 1px solid #d9e6de;
  background: #ffffff;
  color: #4a5c52;
  border-radius: 12px;
  padding: 0.65rem 1.25rem;
  font-weight: 700;
  cursor: pointer;
}

.btn-save {
  background: #0f6b3b;
  color: #ffffff;
  border: 0;
  border-radius: 12px;
  padding: 0.65rem 1.5rem;
  font-weight: 800;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(15, 107, 59, 0.25);
}

.btn-save:hover {
  background: #0c562f;
}

.portal-alert {
  padding: 0.85rem 1.25rem;
  border-radius: 12px;
  font-size: 0.88rem;
  font-weight: 600;
  margin-bottom: 1.25rem;
}

.portal-alert.success {
  background: #e8f5e9;
  color: #2e7d32;
  border: 1px solid #c8e6c9;
}

.portal-alert.danger {
  background: #ffebee;
  color: #c62828;
  border: 1px solid #ffcdd2;
}

.loading-state,
.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #6c7d73;
}
</style>

<template>
  <DashboardLayout
    title="Tenders & Procurement Manager"
    subtitle="Publish municipal tenders, set categories & financial tender years, upload bid packs, and monitor citizen downloads."
  >
    <template #header-actions>
      <button
        type="button"
        class="btn-secondary-action me-2"
        :disabled="checkingExpired"
        @click="runCloseExpired"
        title="Check and auto-close listings whose closing date has lapsed"
      >
        <span v-if="checkingExpired" class="spinner-border spinner-border-sm me-1"></span>
        <i v-else class="bi bi-clock-history me-1"></i>
        {{ checkingExpired ? 'Checking...' : 'Check & Close Expired' }}
      </button>
      <button type="button" class="btn-primary-action" @click="openCreateModal">
        <span>+</span> Upload Tender / Quote
      </button>
    </template>

    <div v-if="notice" class="portal-alert success">{{ notice }}</div>
    <div v-if="error" class="portal-alert danger">{{ error }}</div>

    <!-- Overview Stats -->
    <div class="stats-overview-grid mb-4">
      <div class="stat-box">
        <span class="stat-number">{{ notices.length }}</span>
        <span class="stat-label">Total Listings</span>
      </div>
      <div class="stat-box">
        <span class="stat-number green">{{ openCount }}</span>
        <span class="stat-label">Open Notices</span>
      </div>
      <div class="stat-box">
        <span class="stat-number purple">{{ availableYears.length }}</span>
        <span class="stat-label">Tender Years</span>
      </div>
      <div class="stat-box">
        <span class="stat-number blue">{{ totalDownloads.toLocaleString() }}</span>
        <span class="stat-label">Total Downloads</span>
      </div>
    </div>

    <!-- Filter Bar -->
    <section class="portal-panel filter-panel mb-4">
      <!-- Category Quick Filter Tabs -->
      <div class="category-tabs-row">
        <button
          v-for="cat in quickCategories"
          :key="cat.value"
          type="button"
          :class="['cat-tab-btn', { active: filterCategory === cat.value }]"
          @click="setCategoryFilter(cat.value)"
        >
          {{ cat.label }}
          <span class="cat-count">{{ cat.count }}</span>
        </button>
      </div>

      <div class="filter-controls">
        <div class="search-wrap">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by tender title, reference number, or category..."
            @input="resetPaginationAndFilter"
          />
        </div>

        <div class="filter-dropdowns">
          <select v-model="filterCategory" @change="resetPaginationAndFilter">
            <option value="all">All Categories</option>
            <option v-for="cat in availableCategories" :key="cat" :value="cat">
              {{ cat }}
            </option>
          </select>

          <select v-model="filterYear" @change="resetPaginationAndFilter">
            <option value="all">All Tender Years</option>
            <option v-for="yr in availableYears" :key="yr" :value="yr">
              {{ yr }}
            </option>
          </select>

          <select v-model="filterType" @change="resetPaginationAndFilter">
            <option value="all">All Types</option>
            <option value="tender">Tenders</option>
            <option value="quote">Quotations</option>
          </select>

          <select v-model="filterStatus" @change="resetPaginationAndFilter">
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
        <div class="table-meta-bar">
          <span class="meta-count">Showing {{ paginatedNotices.length }} of {{ filteredNotices.length }} notices</span>
          <div class="per-page-select">
            <span>Show</span>
            <select v-model.number="perPage" @change="currentPage = 1">
              <option :value="15">15</option>
              <option :value="30">30</option>
              <option :value="50">50</option>
              <option :value="100">100</option>
            </select>
          </div>
        </div>

        <table class="manager-table">
          <thead>
            <tr>
              <th style="width: 40px;">#</th>
              <th>Reference No</th>
              <th>Title & Category</th>
              <th>Tender Year</th>
              <th>Downloads</th>
              <th>Closing Date</th>
              <th>Document File</th>
              <th>Status</th>
              <th style="text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, idx) in paginatedNotices" :key="item.id">
              <td class="text-muted" style="font-size: 0.8rem; font-weight: 700;">
                {{ (currentPage - 1) * perPage + idx + 1 }}
              </td>
              <td>
                <span class="ref-pill">{{ item.reference_no || 'N/A' }}</span>
              </td>
              <td>
                <strong class="notice-title">{{ item.title }}</strong>
                <div class="notice-tags">
                  <span class="category-pill">{{ item.category || 'Open Tenders' }}</span>
                  <span :class="['type-pill', item.type]">
                    {{ item.type === 'tender' ? 'Tender' : 'Quote' }}
                  </span>
                </div>
              </td>
              <td>
                <span class="year-badge">{{ item.financial_year }}</span>
              </td>
              <td>
                <span class="download-badge">📥 {{ item.download_count ?? 0 }}</span>
              </td>
              <td>
                <span class="closing-date">{{ formatDate(item.closing_date) }}</span>
              </td>
              <td>
                <a
                  v-if="item.document_url"
                  :href="item.document_url"
                  target="_blank"
                  rel="noopener"
                  class="doc-link"
                >
                  📄 {{ item.file_name || 'Download Pack' }}
                  <span v-if="item.file_size" class="file-size-tag">({{ item.file_size }})</span>
                </a>
                <span v-else class="no-doc">No document</span>
              </td>
              <td>
                <span :class="['status-pill', item.status]">
                  {{ item.status }}
                </span>
              </td>
              <td style="text-align: right;">
                <div class="action-buttons">
                  <button type="button" class="btn-edit" @click="openEditModal(item)">Edit</button>
                  <button type="button" class="btn-delete" @click="deleteNotice(item)">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination Controls -->
        <div v-if="totalPages > 1" class="pagination-footer">
          <button
            type="button"
            class="pg-btn"
            :disabled="currentPage === 1"
            @click="currentPage--"
          >
            &laquo; Prev
          </button>
          <span class="pg-info">Page {{ currentPage }} of {{ totalPages }}</span>
          <button
            type="button"
            class="pg-btn"
            :disabled="currentPage === totalPages"
            @click="currentPage++"
          >
            Next &raquo;
          </button>
        </div>
      </div>
    </section>

    <!-- Create / Edit Modal -->
    <div v-if="isModalOpen" class="modal-backdrop" @click.self="closeModal">
      <div class="modal-sheet">
        <div class="modal-sheet-header">
          <div>
            <p>{{ editingNoticeId ? 'Update SCM Tender' : 'New SCM Listing' }}</p>
            <h3>{{ editingNoticeId ? 'Edit Tender Notice' : 'Upload Tender / Procurement Notice' }}</h3>
          </div>
          <button type="button" class="close-btn" @click="closeModal">&times;</button>
        </div>

        <form class="modal-sheet-form" @submit.prevent="saveNotice">
          <div class="form-grid-2">
            <label>
              Tender Year / Financial Year *
              <select v-model="form.financial_year" required>
                <option v-for="yr in defaultYearsList" :key="yr" :value="yr">
                  {{ yr }}
                </option>
              </select>
            </label>

            <label>
              Category *
              <select v-model="form.category" required>
                <option value="Open Tenders">Open Tenders</option>
                <option value="Bid Documents">Bid Documents</option>
                <option value="Quotations">Quotations</option>
                <option value="Quote Documents">Quote Documents</option>
                <option value="Intention To Award">Intention To Award</option>
                <option value="Briefing Link">Briefing Link</option>
                <option value="Erratum And Addendum">Erratum And Addendum</option>
                <option value="Online Submission">Online Submission</option>
                <option value="Other SCM Documents">Other SCM Documents</option>
              </select>
            </label>
          </div>

          <label>
            Tender / Quotation Title *
            <input
              v-model="form.title"
              type="text"
              placeholder="e.g. Appointment of a service provider for..."
              required
            />
          </label>

          <div class="form-grid-2">
            <label>
              Reference Number (optional)
              <input
                v-model="form.reference_no"
                type="text"
                placeholder="Leave blank to auto-generate (e.g. NDZ-TDR-...)"
              />
            </label>

            <label>
              Procurement Type *
              <select v-model="form.type" required>
                <option value="tender">Tender</option>
                <option value="quote">Quotation (RFQ)</option>
              </select>
            </label>
          </div>

          <div class="form-grid-2">
            <label>
              Status *
              <select v-model="form.status" required>
                <option value="open">Open / Active</option>
                <option value="closed">Closed / Awarded</option>
              </select>
            </label>

            <label>
              Initial Downloads Count
              <input v-model.number="form.download_count" type="number" min="0" />
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
            <input v-model="form.briefing_date" type="text" placeholder="e.g. Compulsory briefing 25 March at 10:00 AM" />
          </label>

          <label>
            Description / Scope of Work
            <textarea v-model="form.description" rows="3" placeholder="Scope of services, submission requirements..."></textarea>
          </label>

          <!-- Tender Document with FileUploadButton -->
          <div class="doc-upload-section">
            <label>Tender / Bid Specification Pack Document *</label>
            <div class="doc-input-row">
              <input
                v-model="form.document_url"
                type="text"
                placeholder="Upload file or enter URL..."
              />
              <FileUploadButton
                folder="tenders"
                accept=".pdf,.doc,.docx,.xls,.xlsx,.zip"
                label="Upload Tender Pack"
                icon="📎"
                @uploaded="onDocUploaded"
              />
            </div>
            <div v-if="form.file_name" class="uploaded-file-info">
              <span>Attached: <strong>{{ form.file_name }}</strong></span>
              <span v-if="form.file_size"> ({{ form.file_size }})</span>
            </div>
            <small class="hint">Accepts PDF, Word documents, or ZIP packs up to 25MB.</small>
          </div>

          <div class="modal-footer-actions">
            <button type="button" class="btn-cancel" @click="closeModal">Cancel</button>
            <button type="submit" class="btn-save" :disabled="saving">
              {{ saving ? 'Saving Notice...' : (editingNoticeId ? 'Update Tender' : 'Save & Publish') }}
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
      filterCategory: 'all',
      filterYear: 'all',
      filterStatus: 'all',
      searchQuery: '',
      loading: true,
      saving: false,
      checkingExpired: false,
      notice: '',
      error: '',
      isModalOpen: false,
      editingNoticeId: null,
      currentPage: 1,
      perPage: 30,
      defaultYearsList: [
        '2026-2027',
        '2025-2026',
        '2024-2025',
        '2023-2024',
        '2022-2023',
        '2021-2022',
        '2020-2021',
        '2018-2019',
        '2017-2018',
        '2016-2017',
      ],
      form: {
        reference_no: '',
        title: '',
        category: 'Open Tenders',
        type: 'tender',
        status: 'open',
        financial_year: '2025-2026',
        closing_date: '',
        briefing_date: '',
        contact_person: '',
        description: '',
        document_url: '',
        download_count: 0,
        file_name: '',
        file_size: '',
      },
    }
  },
  computed: {
    openCount() {
      return this.notices.filter((n) => n.status === 'open').length
    },
    totalDownloads() {
      return this.notices.reduce((acc, curr) => acc + (parseInt(curr.download_count, 10) || 0), 0)
    },
    availableYears() {
      const set = new Set(this.defaultYearsList)
      this.notices.forEach((n) => {
        if (n.financial_year) {
          set.add(n.financial_year.replace('/', '-'))
        }
      })
      return Array.from(set).sort().reverse()
    },
    availableCategories() {
      const set = new Set(['Open Tenders', 'Bid Documents', 'Quotations', 'Quote Documents', 'Intention To Award', 'Briefing Link', 'Erratum And Addendum', 'Other SCM Documents'])
      this.notices.forEach((n) => {
        if (n.category) set.add(n.category)
      })
      return Array.from(set).sort()
    },
    quickCategories() {
      const counts = {}
      this.notices.forEach((n) => {
        const cat = n.category || 'Open Tenders'
        counts[cat] = (counts[cat] || 0) + 1
      })
      return [
        { label: 'All Listings', value: 'all', count: this.notices.length },
        { label: 'Open Tenders', value: 'Open Tenders', count: counts['Open Tenders'] || 0 },
        { label: 'Quotations', value: 'Quotations', count: counts['Quotations'] || 0 },
        { label: 'Bid Documents', value: 'Bid Documents', count: counts['Bid Documents'] || 0 },
        { label: 'Quote Documents', value: 'Quote Documents', count: counts['Quote Documents'] || 0 },
        { label: 'Intention To Award', value: 'Intention To Award', count: counts['Intention To Award'] || 0 },
        { label: 'Briefing Link', value: 'Briefing Link', count: counts['Briefing Link'] || 0 },
        { label: 'Erratum And Addendum', value: 'Erratum And Addendum', count: counts['Erratum And Addendum'] || 0 },
      ]
    },
    totalPages() {
      return Math.ceil(this.filteredNotices.length / this.perPage) || 1
    },
    paginatedNotices() {
      const start = (this.currentPage - 1) * this.perPage
      return this.filteredNotices.slice(start, start + this.perPage)
    },
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
    resetPaginationAndFilter() {
      this.currentPage = 1
      this.filterNotices()
    },
    filterNotices() {
      let list = [...this.notices]

      if (this.filterType !== 'all') {
        list = list.filter((n) => n.type === this.filterType)
      }

      if (this.filterCategory !== 'all') {
        list = list.filter((n) => (n.category || 'Open Tenders') === this.filterCategory)
      }

      if (this.filterYear !== 'all') {
        const target = this.filterYear.toLowerCase()
        list = list.filter((n) => {
          const yr = (n.financial_year || '').toLowerCase().replace('/', '-')
          return yr === target
        })
      }

      if (this.filterStatus !== 'all') {
        list = list.filter((n) => n.status === this.filterStatus)
      }

      if (this.searchQuery.trim()) {
        const q = this.searchQuery.toLowerCase()
        list = list.filter(
          (n) =>
            n.title?.toLowerCase().includes(q) ||
            n.reference_no?.toLowerCase().includes(q) ||
            n.category?.toLowerCase().includes(q) ||
            n.financial_year?.toLowerCase().includes(q)
        )
      }

      list.sort((a, b) => {
        const dateA = new Date(a.created_at || a.updated_at || a.closing_date || 0).getTime()
        const dateB = new Date(b.created_at || b.updated_at || b.closing_date || 0).getTime()
        if (dateB !== dateA) {
          return dateB - dateA
        }
        return (b.id || 0) - (a.id || 0)
      })

      this.filteredNotices = list
    },
    setCategoryFilter(val) {
      this.filterCategory = val
      this.resetPaginationAndFilter()
    },
    openCreateModal() {
      this.editingNoticeId = null
      const defaultCategory = (this.filterCategory !== 'all' && this.filterCategory) ? this.filterCategory : 'Open Tenders'
      const defaultType = (defaultCategory === 'Quotations' || defaultCategory === 'Quote Documents') ? 'quote' : 'tender'
      this.form = {
        reference_no: '',
        title: '',
        category: defaultCategory,
        type: defaultType,
        status: 'open',
        financial_year: '2025-2026',
        closing_date: '',
        briefing_date: '',
        contact_person: '',
        description: '',
        document_url: '',
        download_count: 0,
        file_name: '',
        file_size: '',
      }
      this.isModalOpen = true
    },
    openEditModal(n) {
      this.editingNoticeId = n.id
      this.form = {
        reference_no: n.reference_no || '',
        title: n.title,
        category: n.category || 'Open Tenders',
        type: n.type,
        status: n.status,
        financial_year: (n.financial_year || '2025-2026').replace('/', '-'),
        closing_date: n.closing_date ? n.closing_date.replace(' ', 'T').slice(0, 16) : '',
        briefing_date: n.briefing_date || '',
        contact_person: n.contact_person || '',
        description: n.description || '',
        document_url: n.document_url || '',
        download_count: n.download_count ?? 0,
        file_name: n.file_name || '',
        file_size: n.file_size || '',
      }
      this.isModalOpen = true
    },
    closeModal() {
      this.isModalOpen = false
      this.editingNoticeId = null
    },
    formatFileSize(bytes) {
      if (!bytes) return ''
      if (bytes < 1024) return bytes + ' B'
      if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'
      return (bytes / (1024 * 1024)).toFixed(2) + ' MB'
    },
    onDocUploaded(url, data) {
      this.form.document_url = url
      if (data) {
        this.form.file_name = data.filename || data.original_name || ''
        if (data.size) {
          this.form.file_size = this.formatFileSize(data.size)
        }
      }
      this.notice = 'Tender specification file uploaded successfully.'
      setTimeout(() => { this.notice = '' }, 3500)
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
      const label = n.reference_no || n.title
      if (!confirm(`Are you sure you want to delete "${label}"?`)) {
        return
      }
      try {
        await api.delete(`/api/admin/procurement/${n.id}`)
        this.notice = 'Procurement notice deleted.'
        await this.fetchNotices()
        setTimeout(() => { this.notice = '' }, 3000)
      } catch (err) {
        this.error = 'Failed to delete notice.'
      }
    },
    async runCloseExpired() {
      this.checkingExpired = true
      this.notice = ''
      this.error = ''
      try {
        const { data } = await api.post('/api/admin/procurement/close-expired')
        this.notice = data.message || 'Auto-close check executed successfully.'
        await this.fetchNotices()
        setTimeout(() => { this.notice = '' }, 5000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to check and auto-close listings.'
      } finally {
        this.checkingExpired = false
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
  border: none;
  padding: 0.65rem 1.25rem;
  border-radius: 12px;
  font-weight: 700;
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

.btn-secondary-action {
  background: #ffffff;
  color: #1f2f4a;
  border: 1px solid #d1d5db;
  padding: 0.65rem 1.15rem;
  border-radius: 12px;
  font-weight: 700;
  font-size: 0.9rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  transition: all 0.2s ease;
}

.btn-secondary-action:hover:not(:disabled) {
  background: #f8fafc;
  border-color: #0f6b3b;
  color: #0f6b3b;
  transform: translateY(-1px);
}

.btn-secondary-action:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.stats-overview-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 1rem;
}

.stat-box {
  background: #ffffff;
  border: 1px solid #e4ece7;
  border-radius: 16px;
  padding: 1.25rem;
  display: flex;
  flex-direction: column;
  box-shadow: 0 4px 15px rgba(15, 107, 59, 0.03);
}

.stat-number {
  font-size: 1.8rem;
  font-weight: 800;
  color: #17231c;
}

.stat-number.green { color: #0f6b3b; }
.stat-number.purple { color: #512da8; }
.stat-number.blue { color: #0284c7; }

.stat-label {
  font-size: 0.8rem;
  font-weight: 700;
  color: #6c7d73;
  margin-top: 0.25rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.filter-panel {
  padding: 1.25rem;
}

.category-tabs-row {
  display: flex;
  gap: 0.5rem;
  overflow-x: auto;
  padding-bottom: 0.75rem;
  margin-bottom: 0.75rem;
  border-bottom: 1px solid #edf3ef;
}

.cat-tab-btn {
  background: #fbfdfc;
  border: 1px solid #d9e6de;
  color: #384d41;
  padding: 0.5rem 0.9rem;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.82rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  white-space: nowrap;
  transition: all 0.2s ease;
}

.cat-tab-btn:hover {
  background: #f0f7f3;
  border-color: #a3c9b4;
  color: #0f6b3b;
}

.cat-tab-btn.active {
  background: #0f6b3b;
  border-color: #0f6b3b;
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(15, 107, 59, 0.2);
}

.cat-tab-btn .cat-count {
  background: #edf6f0;
  color: #0f6b3b;
  font-size: 0.72rem;
  padding: 0.15rem 0.45rem;
  border-radius: 20px;
  font-weight: 800;
}

.cat-tab-btn.active .cat-count {
  background: rgba(255, 255, 255, 0.25);
  color: #ffffff;
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
  flex-wrap: wrap;
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

.table-meta-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  font-size: 0.85rem;
  color: #6c7d73;
  font-weight: 600;
}

.per-page-select {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.per-page-select select {
  padding: 0.25rem 0.5rem;
  border-radius: 6px;
  border: 1px solid #d9e6de;
  font-weight: 700;
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
  font-size: 0.75rem;
  font-weight: 800;
  white-space: nowrap;
}

.notice-title {
  display: block;
  font-size: 0.92rem;
  color: #17231c;
  margin-bottom: 0.35rem;
}

.notice-tags {
  display: flex;
  gap: 0.35rem;
  align-items: center;
  flex-wrap: wrap;
}

.category-pill {
  display: inline-block;
  background: #f1f5f9;
  color: #334155;
  padding: 0.15rem 0.5rem;
  border-radius: 6px;
  font-size: 0.72rem;
  font-weight: 700;
}

.year-badge {
  display: inline-block;
  background: #e6f4ea;
  color: #137333;
  padding: 0.25rem 0.55rem;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 800;
  white-space: nowrap;
}

.download-badge {
  display: inline-block;
  background: #f0f9ff;
  color: #0369a1;
  border: 1px solid #bae6fd;
  padding: 0.2rem 0.55rem;
  border-radius: 8px;
  font-size: 0.76rem;
  font-weight: 800;
  white-space: nowrap;
}

.type-pill {
  display: inline-block;
  padding: 0.15rem 0.5rem;
  border-radius: 999px;
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
}

.type-pill.tender { background: #ede7f6; color: #512da8; }
.type-pill.quote { background: #e0f2fe; color: #0369a1; }

.closing-date {
  font-size: 0.82rem;
  color: #4a5c52;
}

.doc-link {
  color: #0f6b3b;
  font-size: 0.82rem;
  font-weight: 700;
  text-decoration: none;
  display: inline-block;
  max-width: 250px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.doc-link:hover {
  text-decoration: underline;
}

.file-size-tag {
  color: #6c7d73;
  font-weight: 500;
  font-size: 0.75rem;
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
  padding: 0.35rem 0.75rem;
  border-radius: 8px;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
}

.btn-delete {
  border: 1px solid #fcd2d2;
  background: #fff3f3;
  color: #d9383a;
  padding: 0.35rem 0.75rem;
  border-radius: 8px;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
}

.pagination-footer {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin-top: 1.5rem;
  padding-top: 1rem;
  border-top: 1px solid #edf3ef;
}

.pg-btn {
  border: 1px solid #c2dbcd;
  background: #ffffff;
  color: #0f6b3b;
  padding: 0.45rem 1rem;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.85rem;
  cursor: pointer;
}

.pg-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pg-info {
  font-size: 0.85rem;
  font-weight: 700;
  color: #4a5c52;
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
  max-width: 720px;
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
  padding: 0.7rem 0.95rem;
  border: 1px solid #d9e6de;
  border-radius: 12px;
  font-size: 0.9rem;
  background: #fbfdfc;
  color: #17231c;
  font-family: inherit;
}

.modal-sheet-form input:focus,
.modal-sheet-form select:focus,
.modal-sheet-form textarea:focus {
  outline: none;
  border-color: #0f6b3b;
  background: #ffffff;
}

.doc-upload-section {
  background: #f8faf9;
  border: 1px dashed #c2dbcd;
  border-radius: 14px;
  padding: 1.2rem;
}

.doc-input-row {
  display: flex;
  gap: 0.75rem;
  align-items: center;
  margin-top: 0.4rem;
}

.doc-input-row input {
  flex: 1;
}

.uploaded-file-info {
  margin-top: 0.5rem;
  font-size: 0.8rem;
  color: #0f6b3b;
  background: #eef7f2;
  padding: 0.35rem 0.7rem;
  border-radius: 8px;
  display: inline-block;
}

.hint {
  display: block;
  font-size: 0.75rem;
  color: #6c7d73;
  margin-top: 0.4rem;
}

.modal-footer-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 1rem;
  border-top: 1px solid #edf3ef;
  padding-top: 1.25rem;
}

.btn-cancel {
  border: 1px solid #d9e6de;
  background: #ffffff;
  color: #4a5c52;
  padding: 0.65rem 1.25rem;
  border-radius: 12px;
  font-weight: 700;
  font-size: 0.88rem;
  cursor: pointer;
}

.btn-save {
  border: none;
  background: #0f6b3b;
  color: #ffffff;
  padding: 0.65rem 1.4rem;
  border-radius: 12px;
  font-weight: 700;
  font-size: 0.88rem;
  cursor: pointer;
}

.btn-save:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

.portal-alert {
  padding: 0.85rem 1.25rem;
  border-radius: 12px;
  margin-bottom: 1.25rem;
  font-weight: 600;
  font-size: 0.9rem;
}

.portal-alert.success {
  background: #e8f5e9;
  color: #1b5e20;
  border: 1px solid #c8e6c9;
}

.portal-alert.danger {
  background: #ffebee;
  color: #b71c1c;
  border: 1px solid #ffcdd2;
}

.loading-state,
.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #6c7d73;
}

@media (max-width: 768px) {
  .form-grid-2 {
    grid-template-columns: 1fr;
  }
}
</style>

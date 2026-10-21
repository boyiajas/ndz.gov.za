<template>
  <DashboardLayout
    title="System Audit Trail & Activity Logs"
    subtitle="Track user operations, administrative changes, document uploads, tender management, and security events."
  >
    <template #header-actions>
      <button
        type="button"
        class="btn-export-action"
        :disabled="exporting"
        @click="exportCsv"
      >
        <span>📥</span> {{ exporting ? 'Exporting...' : 'Export to CSV' }}
      </button>
    </template>

    <div v-if="notice" class="portal-alert success">{{ notice }}</div>
    <div v-if="error" class="portal-alert danger">{{ error }}</div>

    <!-- Overview Stats -->
    <div class="stats-overview-grid mb-4">
      <div class="stat-box">
        <span class="stat-number">{{ stats.total_logs ?? 0 }}</span>
        <span class="stat-label">Total System Events</span>
      </div>
      <div class="stat-box">
        <span class="stat-number green">{{ stats.today_logs ?? 0 }}</span>
        <span class="stat-label">Today's Activity</span>
      </div>
      <div class="stat-box">
        <span class="stat-number purple">{{ stats.unique_users ?? 0 }}</span>
        <span class="stat-label">Active Users</span>
      </div>
      <div class="stat-box">
        <span class="stat-number blue">{{ pagination.total ?? 0 }}</span>
        <span class="stat-label">Filtered Results</span>
      </div>
    </div>

    <!-- Quick Date Filter Presets -->
    <div class="date-presets-bar mb-3">
      <span class="preset-label">Timeframe:</span>
      <button
        type="button"
        :class="['preset-btn', { active: activePreset === 'all' }]"
        @click="applyPreset('all')"
      >
        All Time
      </button>
      <button
        type="button"
        :class="['preset-btn', { active: activePreset === 'today' }]"
        @click="applyPreset('today')"
      >
        Today
      </button>
      <button
        type="button"
        :class="['preset-btn', { active: activePreset === '7days' }]"
        @click="applyPreset('7days')"
      >
        Last 7 Days
      </button>
      <button
        type="button"
        :class="['preset-btn', { active: activePreset === '30days' }]"
        @click="applyPreset('30days')"
      >
        Last 30 Days
      </button>
    </div>

    <!-- Filter Bar -->
    <section class="portal-panel filter-panel mb-4">
      <div class="filter-controls-grid">
        <!-- Search Keyword -->
        <div class="filter-field search-field">
          <label>Search Keyword</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Search description, user, IP, or record..."
            @input="onSearchInput"
          />
        </div>

        <!-- Filter by User -->
        <div class="filter-field">
          <label>Filter by User</label>
          <select v-model="filters.user_id" @change="fetchLogs(1)">
            <option value="all">All Users ({{ availableUsers.length }})</option>
            <option v-for="u in availableUsers" :key="u.id" :value="u.id">
              {{ u.name }} ({{ u.email }})
            </option>
          </select>
        </div>

        <!-- Filter by Department -->
        <div class="filter-field">
          <label>Filter by Department</label>
          <select v-model="filters.department_id" @change="fetchLogs(1)">
            <option value="all">All Departments ({{ availableDepartments.length }})</option>
            <option v-for="d in availableDepartments" :key="d.id" :value="d.id">
              {{ d.name }}
            </option>
          </select>
        </div>

        <!-- Filter by Module -->
        <div class="filter-field">
          <label>Module / Area</label>
          <select v-model="filters.module" @change="fetchLogs(1)">
            <option value="all">All Modules</option>
            <option v-for="m in availableModules" :key="m" :value="m">
              {{ m }}
            </option>
          </select>
        </div>

        <!-- Filter by Action -->
        <div class="filter-field">
          <label>Action Type</label>
          <select v-model="filters.action" @change="fetchLogs(1)">
            <option value="all">All Actions</option>
            <option v-for="a in availableActions" :key="a" :value="a">
              {{ formatActionLabel(a) }}
            </option>
          </select>
        </div>

        <!-- Date Range: From -->
        <div class="filter-field date-field">
          <label>Date From</label>
          <input
            v-model="filters.date_from"
            type="date"
            @change="onDateChange"
          />
        </div>

        <!-- Date Range: To -->
        <div class="filter-field date-field">
          <label>Date To</label>
          <input
            v-model="filters.date_to"
            type="date"
            @change="onDateChange"
          />
        </div>

        <!-- Clear Filters Button -->
        <div class="filter-field clear-action-field">
          <label>&nbsp;</label>
          <button
            type="button"
            class="btn-reset-filters"
            :disabled="!hasActiveFilters"
            @click="resetFilters"
          >
            Clear Filters
          </button>
        </div>
      </div>
    </section>

    <!-- Table of Audit Logs -->
    <section class="portal-panel">
      <div v-if="loading" class="loading-state">
        <span class="spinner-icon">⏳</span> Loading audit trail logs...
      </div>
      <div v-else-if="logs.length === 0" class="empty-state">
        <p>No audit trail records found matching the specified criteria.</p>
        <button
          v-if="hasActiveFilters"
          type="button"
          class="btn-secondary-action"
          @click="resetFilters"
        >
          Reset All Filters
        </button>
      </div>

      <div v-else class="table-responsive">
        <div class="table-meta-bar">
          <span class="meta-count">
            Showing {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }} -
            {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }} of
            {{ pagination.total }} audit events
          </span>
          <div class="per-page-select">
            <span>Rows per page</span>
            <select v-model.number="pagination.per_page" @change="fetchLogs(1)">
              <option :value="15">15</option>
              <option :value="25">25</option>
              <option :value="50">50</option>
              <option :value="100">100</option>
            </select>
          </div>
        </div>

        <table class="manager-table audit-table">
          <thead>
            <tr>
              <th style="width: 50px;">#</th>
              <th style="width: 170px;">Date & Time</th>
              <th>User / Actor</th>
              <th>Department</th>
              <th>Module</th>
              <th>Action</th>
              <th>Description</th>
              <th>IP Address</th>
              <th style="text-align: right; width: 100px;">Details</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, idx) in logs"
              :key="item.id"
              class="audit-row"
              @click="openDetailModal(item)"
            >
              <td class="text-muted" style="font-size: 0.8rem; font-weight: 700;">
                {{ ((pagination.current_page - 1) * pagination.per_page) + idx + 1 }}
              </td>
              <td>
                <span class="audit-datetime">{{ formatDateTime(item.created_at) }}</span>
                <span class="audit-timeago">{{ formatRelativeTime(item.created_at) }}</span>
              </td>
              <td>
                <div class="actor-info">
                  <div class="actor-avatar">{{ getUserInitials(item.user_name) }}</div>
                  <div class="actor-names">
                    <router-link
                      v-if="item.user_id"
                      :to="{ path: '/dashboard/profile', query: { id: item.user_id } }"
                      class="actor-link"
                      @click.stop
                      title="View user profile"
                    >
                      <strong>{{ item.user_name }}</strong>
                    </router-link>
                    <strong v-else>{{ item.user_name || 'System / Guest' }}</strong>
                    <span class="actor-email">{{ item.user_email || 'No email' }}</span>
                    <span :class="['actor-role-pill', item.user_role]">
                      {{ formatRole(item.user_role) }}
                    </span>
                  </div>
                </div>
              </td>
              <td>
                <span v-if="item.department_name" class="dept-pill">
                  {{ item.department_name }}
                </span>
                <span v-else class="text-muted" style="font-size: 0.8rem;">
                  General / System
                </span>
              </td>
              <td>
                <span class="module-pill">{{ item.module }}</span>
              </td>
              <td>
                <span :class="['action-pill', item.action]">
                  {{ (item.action || '').toUpperCase() }}
                </span>
              </td>
              <td>
                <div class="log-description" :title="item.description">
                  {{ item.description }}
                </div>
              </td>
              <td>
                <code class="ip-code">{{ item.ip_address || '127.0.0.1' }}</code>
              </td>
              <td style="text-align: right;" @click.stop>
                <button
                  type="button"
                  class="btn-inspect"
                  @click="openDetailModal(item)"
                >
                  View
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination Controls -->
        <div v-if="pagination.last_page > 1" class="pagination-footer">
          <button
            type="button"
            class="pg-btn"
            :disabled="pagination.current_page === 1"
            @click="fetchLogs(pagination.current_page - 1)"
          >
            &laquo; Prev
          </button>
          <span class="pg-info">
            Page {{ pagination.current_page }} of {{ pagination.last_page }}
          </span>
          <button
            type="button"
            class="pg-btn"
            :disabled="pagination.current_page === pagination.last_page"
            @click="fetchLogs(pagination.current_page + 1)"
          >
            Next &raquo;
          </button>
        </div>
      </div>
    </section>

    <!-- Audit Log Detail Modal -->
    <div v-if="selectedLog" class="modal-backdrop" @click.self="selectedLog = null">
      <div class="modal-sheet detail-sheet">
        <div class="modal-sheet-header">
          <div>
            <p>Audit Log #{{ selectedLog.id }}</p>
            <h3>Event Activity Details</h3>
          </div>
          <button type="button" class="close-btn" @click="selectedLog = null">&times;</button>
        </div>

        <div class="detail-body">
          <!-- Summary Cards -->
          <div class="detail-cards-grid">
            <div class="detail-card">
              <span class="d-label">Actor / User</span>
              <strong>{{ selectedLog.user_name || 'System' }}</strong>
              <small>{{ selectedLog.user_email || 'No email' }}</small>
              <div class="mt-1">
                <span :class="['actor-role-pill', selectedLog.user_role]">
                  {{ formatRole(selectedLog.user_role) }}
                </span>
              </div>
            </div>

            <div class="detail-card">
              <span class="d-label">Department</span>
              <strong>{{ selectedLog.department_name || 'General / System' }}</strong>
              <small>Scope context</small>
            </div>

            <div class="detail-card">
              <span class="d-label">Module & Action</span>
              <div class="d-flex gap-2 align-items-center mt-1">
                <span class="module-pill">{{ selectedLog.module }}</span>
                <span :class="['action-pill', selectedLog.action]">
                  {{ (selectedLog.action || '').toUpperCase() }}
                </span>
              </div>
              <small class="mt-1">{{ formatDateTime(selectedLog.created_at) }}</small>
            </div>

            <div class="detail-card">
              <span class="d-label">Network Origin</span>
              <code>{{ selectedLog.ip_address || '127.0.0.1' }}</code>
              <small class="text-truncate d-block mt-1" :title="selectedLog.user_agent">
                {{ selectedLog.user_agent ? 'Browser Client' : 'Direct API' }}
              </small>
            </div>
          </div>

          <!-- Description Box -->
          <div class="detail-section">
            <h4>Description</h4>
            <div class="description-box">
              {{ selectedLog.description }}
            </div>
          </div>

          <!-- Changes Diff: Old vs New Values -->
          <div v-if="selectedLog.old_values || selectedLog.new_values" class="detail-section">
            <h4>Recorded Attribute Changes</h4>
            <div class="diff-grid">
              <div v-if="selectedLog.old_values" class="diff-panel old-panel">
                <span class="diff-title">Previous Values</span>
                <pre>{{ JSON.stringify(selectedLog.old_values, null, 2) }}</pre>
              </div>
              <div v-if="selectedLog.new_values" class="diff-panel new-panel">
                <span class="diff-title">New / Updated Values</span>
                <pre>{{ JSON.stringify(selectedLog.new_values, null, 2) }}</pre>
              </div>
            </div>
          </div>

          <!-- User Agent Client Info -->
          <div v-if="selectedLog.user_agent" class="detail-section">
            <h4>User Agent</h4>
            <code class="ua-box">{{ selectedLog.user_agent }}</code>
          </div>
        </div>

        <div class="modal-footer-actions">
          <button type="button" class="btn-cancel" @click="selectedLog = null">Close</button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../components/DashboardLayout.vue'
import api from '../api/axios'

export default {
  name: 'DashboardAuditTrailView',
  components: { DashboardLayout },
  data() {
    return {
      logs: [],
      availableUsers: [],
      availableDepartments: [],
      availableModules: [],
      availableActions: [],
      stats: {
        total_logs: 0,
        today_logs: 0,
        unique_users: 0,
      },
      filters: {
        search: '',
        user_id: 'all',
        department_id: 'all',
        module: 'all',
        action: 'all',
        date_from: '',
        date_to: '',
      },
      activePreset: 'all',
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 25,
        total: 0,
      },
      selectedLog: null,
      loading: false,
      exporting: false,
      notice: '',
      error: '',
      searchTimeout: null,
    }
  },
  computed: {
    hasActiveFilters() {
      return (
        this.filters.search.trim() !== '' ||
        this.filters.user_id !== 'all' ||
        this.filters.department_id !== 'all' ||
        this.filters.module !== 'all' ||
        this.filters.action !== 'all' ||
        this.filters.date_from !== '' ||
        this.filters.date_to !== ''
      )
    },
  },
  mounted() {
    this.fetchFilterOptions()
    this.fetchLogs(1)
  },
  methods: {
    async fetchFilterOptions() {
      try {
        const { data } = await api.get('/api/admin/audit-logs/filters')
        this.availableUsers = data.users || []
        this.availableDepartments = data.departments || []
        this.availableModules = data.modules || []
        this.availableActions = data.actions || []
        if (data.stats) {
          this.stats = data.stats
        }
      } catch (err) {
        console.error('Failed to load audit filters:', err)
      }
    },
    async fetchLogs(page = 1) {
      this.loading = true
      this.error = ''
      try {
        const params = {
          page,
          per_page: this.pagination.per_page,
        }

        if (this.filters.search.trim()) params.search = this.filters.search.trim()
        if (this.filters.user_id !== 'all') params.user_id = this.filters.user_id
        if (this.filters.department_id !== 'all') params.department_id = this.filters.department_id
        if (this.filters.module !== 'all') params.module = this.filters.module
        if (this.filters.action !== 'all') params.action = this.filters.action
        if (this.filters.date_from) params.date_from = this.filters.date_from
        if (this.filters.date_to) params.date_to = this.filters.date_to

        const { data } = await api.get('/api/admin/audit-logs', { params })

        this.logs = data.data || []
        this.pagination = {
          current_page: data.current_page || 1,
          last_page: data.last_page || 1,
          per_page: data.per_page || this.pagination.per_page,
          total: data.total || 0,
        }
      } catch (err) {
        this.error = 'Failed to load audit trail logs. Please refresh.'
      } finally {
        this.loading = false
      }
    },
    onSearchInput() {
      clearTimeout(this.searchTimeout)
      this.searchTimeout = setTimeout(() => {
        this.fetchLogs(1)
      }, 350)
    },
    onDateChange() {
      this.activePreset = 'custom'
      this.fetchLogs(1)
    },
    applyPreset(preset) {
      this.activePreset = preset
      const today = new Date().toISOString().slice(0, 10)

      if (preset === 'all') {
        this.filters.date_from = ''
        this.filters.date_to = ''
      } else if (preset === 'today') {
        this.filters.date_from = today
        this.filters.date_to = today
      } else if (preset === '7days') {
        const d = new Date()
        d.setDate(d.getDate() - 7)
        this.filters.date_from = d.toISOString().slice(0, 10)
        this.filters.date_to = today
      } else if (preset === '30days') {
        const d = new Date()
        d.setDate(d.getDate() - 30)
        this.filters.date_from = d.toISOString().slice(0, 10)
        this.filters.date_to = today
      }

      this.fetchLogs(1)
    },
    resetFilters() {
      this.filters = {
        search: '',
        user_id: 'all',
        department_id: 'all',
        module: 'all',
        action: 'all',
        date_from: '',
        date_to: '',
      }
      this.activePreset = 'all'
      this.fetchLogs(1)
    },
    async exportCsv() {
      this.exporting = true
      try {
        const params = {}
        if (this.filters.search.trim()) params.search = this.filters.search.trim()
        if (this.filters.user_id !== 'all') params.user_id = this.filters.user_id
        if (this.filters.department_id !== 'all') params.department_id = this.filters.department_id
        if (this.filters.module !== 'all') params.module = this.filters.module
        if (this.filters.action !== 'all') params.action = this.filters.action
        if (this.filters.date_from) params.date_from = this.filters.date_from
        if (this.filters.date_to) params.date_to = this.filters.date_to

        const response = await api.get('/api/admin/audit-logs/export', {
          params,
          responseType: 'blob',
        })

        const blob = new Blob([response.data], { type: 'text/csv' })
        const link = document.createElement('a')
        link.href = URL.createObjectURL(blob)
        link.download = `ndz-audit-trail-${new Date().toISOString().slice(0, 10)}.csv`
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)

        this.notice = 'Audit log report exported successfully.'
        setTimeout(() => { this.notice = '' }, 3500)
      } catch (err) {
        this.error = 'Failed to export audit logs.'
      } finally {
        this.exporting = false
      }
    },
    openDetailModal(log) {
      this.selectedLog = log
    },
    formatDateTime(dateStr) {
      if (!dateStr) return 'N/A'
      return new Date(dateStr).toLocaleString('en-ZA', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      })
    },
    formatRelativeTime(dateStr) {
      if (!dateStr) return ''
      const diffMs = new Date() - new Date(dateStr)
      const diffSec = Math.floor(diffMs / 1000)
      const diffMin = Math.floor(diffSec / 60)
      const diffHr = Math.floor(diffMin / 60)
      const diffDay = Math.floor(diffHr / 24)

      if (diffDay > 30) return ''
      if (diffDay > 0) return `${diffDay}d ago`
      if (diffHr > 0) return `${diffHr}h ago`
      if (diffMin > 0) return `${diffMin}m ago`
      return 'just now'
    },
    getUserInitials(name) {
      if (!name) return 'SYS'
      return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
    },
    formatRole(role) {
      const map = {
        admin: 'Admin',
        manager: 'Manager',
        editor: 'Editor',
        citizen: 'Citizen',
        system: 'System',
      }
      return map[role] || role || 'System'
    },
    formatActionLabel(act) {
      const labels = {
        create: 'Create (Add)',
        update: 'Update (Edit)',
        delete: 'Delete (Remove)',
        login: 'User Login',
        logout: 'User Logout',
        status_change: 'Status Change',
        upload: 'Document Upload',
        download: 'Document Download',
      }
      return labels[act] || act.toUpperCase()
    },
  },
}
</script>

<style scoped>
.btn-export-action {
  background: #ffffff;
  color: #0f6b3b;
  border: 1px solid #cce5d6;
  padding: 0.65rem 1.25rem;
  border-radius: 12px;
  font-weight: 700;
  font-size: 0.9rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 3px 10px rgba(15, 107, 59, 0.08);
  transition: all 0.2s ease;
}

.btn-export-action:hover:not(:disabled) {
  background: #f0f7f3;
  border-color: #0f6b3b;
  transform: translateY(-1px);
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

/* Date Presets */
.date-presets-bar {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.preset-label {
  font-size: 0.82rem;
  font-weight: 700;
  color: #55695e;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-right: 0.25rem;
}

.preset-btn {
  background: #ffffff;
  border: 1px solid #d9e6de;
  color: #405448;
  padding: 0.35rem 0.85rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.15s ease;
}

.preset-btn:hover {
  background: #f0f7f3;
  border-color: #a3c9b4;
  color: #0f6b3b;
}

.preset-btn.active {
  background: #0f6b3b;
  border-color: #0f6b3b;
  color: #ffffff;
}

/* Filter Controls Grid */
.filter-panel {
  padding: 1.25rem;
}

.filter-controls-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.filter-field {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
}

.filter-field.search-field {
  grid-column: span 2;
  min-width: 280px;
}

@media (max-width: 768px) {
  .filter-field.search-field {
    grid-column: span 1;
  }
}

.filter-field label {
  font-size: 0.78rem;
  font-weight: 700;
  color: #55695e;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.filter-field input,
.filter-field select {
  padding: 0.6rem 0.85rem;
  border: 1px solid #d9e6de;
  border-radius: 10px;
  font-size: 0.85rem;
  background: #fbfdfc;
  color: #17231c;
}

.filter-field input:focus,
.filter-field select:focus {
  outline: none;
  border-color: #0f6b3b;
  box-shadow: 0 0 0 3px rgba(15, 107, 59, 0.12);
}

.btn-reset-filters {
  background: #f3f6f4;
  border: 1px solid #d9e6de;
  color: #55695e;
  padding: 0.6rem 1rem;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-reset-filters:hover:not(:disabled) {
  background: #e6ede8;
  color: #17231c;
}

.btn-reset-filters:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Table */
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
  font-size: 0.74rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: #6c7d73;
  border-bottom: 2px solid #edf3ef;
  white-space: nowrap;
}

.manager-table td {
  padding: 0.95rem 1rem;
  border-bottom: 1px solid #f0f5f2;
  vertical-align: middle;
}

.audit-row {
  cursor: pointer;
  transition: background 0.15s ease;
}

.audit-row:hover {
  background: #fbfdfc;
}

.audit-datetime {
  display: block;
  font-size: 0.85rem;
  font-weight: 700;
  color: #17231c;
  white-space: nowrap;
}

.audit-timeago {
  display: block;
  font-size: 0.72rem;
  color: #8fa096;
}

.actor-info {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  min-width: 170px;
}

.actor-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: #edf6f0;
  color: #0f6b3b;
  font-weight: 800;
  font-size: 0.78rem;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  border: 1px solid #cce5d6;
}

.actor-names {
  display: flex;
  flex-direction: column;
  gap: 0.1rem;
}

.actor-link {
  color: inherit;
  text-decoration: none;
  display: inline-block;
}

.actor-link:hover strong {
  color: #0f6b3b;
  text-decoration: underline;
}

.actor-names strong {
  font-size: 0.85rem;
  color: #17231c;
}

.actor-email {
  font-size: 0.73rem;
  color: #6c7d73;
}

.actor-role-pill {
  display: inline-block;
  padding: 0.1rem 0.45rem;
  border-radius: 12px;
  font-size: 0.68rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  width: fit-content;
}

.actor-role-pill.admin { background: #fee2e2; color: #991b1b; }
.actor-role-pill.manager { background: #ede9fe; color: #5b21b6; }
.actor-role-pill.editor { background: #e0f2fe; color: #075985; }
.actor-role-pill.citizen { background: #f3f4f6; color: #374151; }

.dept-pill {
  background: #f0f7f3;
  color: #0f6b3b;
  border: 1px solid #d4e8dc;
  border-radius: 6px;
  padding: 0.2rem 0.55rem;
  font-size: 0.75rem;
  font-weight: 700;
  display: inline-block;
}

.module-pill {
  background: #f1f5f9;
  color: #334155;
  border-radius: 6px;
  padding: 0.2rem 0.55rem;
  font-size: 0.75rem;
  font-weight: 700;
  display: inline-block;
}

.action-pill {
  display: inline-block;
  padding: 0.2rem 0.55rem;
  border-radius: 6px;
  font-size: 0.72rem;
  font-weight: 800;
  letter-spacing: 0.05em;
  white-space: nowrap;
}

.action-pill.create,
.action-pill.upload {
  background: #dcfce7;
  color: #166534;
}

.action-pill.update,
.action-pill.status_change {
  background: #e0f2fe;
  color: #0369a1;
}

.action-pill.delete {
  background: #fee2e2;
  color: #991b1b;
}

.action-pill.login {
  background: #f3e8ff;
  color: #6b21a8;
}

.action-pill.logout {
  background: #f1f5f9;
  color: #64748b;
}

.action-pill.download {
  background: #fef3c7;
  color: #92400e;
}

.log-description {
  max-width: 320px;
  font-size: 0.85rem;
  color: #24352b;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.ip-code {
  font-size: 0.75rem;
  background: #f1f5f3;
  padding: 0.2rem 0.45rem;
  border-radius: 4px;
  color: #4b5f54;
}

.btn-inspect {
  background: transparent;
  border: 1px solid #cce5d6;
  color: #0f6b3b;
  padding: 0.35rem 0.75rem;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-inspect:hover {
  background: #0f6b3b;
  color: #ffffff;
}

/* Modal */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 30, 22, 0.6);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
  padding: 1.5rem;
}

.modal-sheet {
  background: #ffffff;
  border-radius: 24px;
  width: 100%;
  max-width: 720px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
  display: flex;
  flex-direction: column;
}

.modal-sheet-header {
  padding: 1.5rem 1.75rem;
  border-bottom: 1px solid #edf3ef;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.modal-sheet-header p {
  margin: 0;
  font-size: 0.8rem;
  font-weight: 700;
  color: #0f6b3b;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}

.modal-sheet-header h3 {
  margin: 0.25rem 0 0;
  font-size: 1.25rem;
  font-weight: 800;
  color: #17231c;
}

.close-btn {
  background: transparent;
  border: none;
  font-size: 1.6rem;
  line-height: 1;
  color: #8fa096;
  cursor: pointer;
  padding: 0.25rem;
}

.detail-body {
  padding: 1.75rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.detail-cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 0.85rem;
}

.detail-card {
  background: #fbfdfc;
  border: 1px solid #e4ece7;
  border-radius: 12px;
  padding: 0.85rem 1rem;
  display: flex;
  flex-direction: column;
}

.detail-card .d-label {
  font-size: 0.72rem;
  font-weight: 700;
  color: #6c7d73;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 0.3rem;
}

.detail-card strong {
  font-size: 0.9rem;
  color: #17231c;
}

.detail-card small {
  font-size: 0.75rem;
  color: #6c7d73;
}

.detail-section h4 {
  font-size: 0.85rem;
  font-weight: 800;
  color: #384d41;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin: 0 0 0.5rem;
}

.description-box {
  background: #f8faf9;
  border: 1px solid #e4ece7;
  border-radius: 12px;
  padding: 1rem 1.25rem;
  font-size: 0.92rem;
  line-height: 1.5;
  color: #17231c;
}

.diff-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

@media (max-width: 640px) {
  .diff-grid {
    grid-template-columns: 1fr;
  }
}

.diff-panel {
  border-radius: 12px;
  padding: 0.85rem 1rem;
  font-size: 0.78rem;
}

.diff-panel.old-panel {
  background: #fff5f5;
  border: 1px solid #fed7d7;
}

.diff-panel.new-panel {
  background: #f0fff4;
  border: 1px solid #c6f6d5;
}

.diff-title {
  display: block;
  font-weight: 800;
  font-size: 0.72rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 0.5rem;
}

.old-panel .diff-title { color: #991b1b; }
.new-panel .diff-title { color: #166534; }

.diff-panel pre {
  margin: 0;
  white-space: pre-wrap;
  word-break: break-all;
  font-family: monospace;
}

.ua-box {
  display: block;
  background: #f8faf9;
  border: 1px solid #e4ece7;
  padding: 0.75rem 1rem;
  border-radius: 10px;
  font-size: 0.75rem;
  color: #55695e;
  word-break: break-all;
}

.modal-footer-actions {
  padding: 1.25rem 1.75rem;
  border-top: 1px solid #edf3ef;
  display: flex;
  justify-content: flex-end;
}

.btn-cancel {
  background: #f3f6f4;
  border: 1px solid #d9e6de;
  padding: 0.6rem 1.4rem;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.85rem;
  color: #384d41;
  cursor: pointer;
}

.btn-cancel:hover {
  background: #e6ede8;
}

/* Pagination */
.pagination-footer {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  margin-top: 1.5rem;
  padding-top: 1rem;
  border-top: 1px solid #edf3ef;
}

.pg-btn {
  background: #ffffff;
  border: 1px solid #d9e6de;
  padding: 0.4rem 0.9rem;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.85rem;
  cursor: pointer;
  color: #17231c;
}

.pg-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.pg-info {
  font-size: 0.85rem;
  color: #6c7d73;
  font-weight: 600;
}

.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #6c7d73;
}

.btn-secondary-action {
  background: #0f6b3b;
  color: #ffffff;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.85rem;
  cursor: pointer;
  margin-top: 0.75rem;
}

.portal-alert {
  padding: 0.85rem 1.25rem;
  border-radius: 12px;
  margin-bottom: 1.25rem;
  font-weight: 600;
  font-size: 0.9rem;
}

.portal-alert.success {
  background: #edf7f0;
  color: #0f6b3b;
  border: 1px solid #c2e2ce;
}

.portal-alert.danger {
  background: #fdf2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
}
</style>

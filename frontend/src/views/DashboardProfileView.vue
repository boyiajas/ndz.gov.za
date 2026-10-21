<template>
  <DashboardLayout
    :title="isViewingOther ? `${profile.name || 'User'}'s Profile` : 'My Profile & Account Details'"
    :subtitle="isViewingOther ? 'Inspect municipal staff member details, assigned directorates, and audit history.' : 'Manage your personal details, municipal directorate assignment, and security credentials.'"
  >
    <template #header-actions>
      <router-link
        v-if="isViewingOther && auth.canManageUsers"
        to="/dashboard/users"
        class="btn-secondary-header"
      >
        &larr; Back to Users
      </router-link>
    </template>

    <div v-if="notice" class="portal-alert success">{{ notice }}</div>
    <div v-if="error" class="portal-alert danger">{{ error }}</div>

    <div v-if="loading && !profile.id" class="loading-state">
      <span class="spinner-icon">⏳</span> Loading profile details...
    </div>

    <div v-else class="profile-container">
      <!-- Top Hero Profile Card -->
      <section class="profile-hero-card mb-4">
        <div class="hero-main">
          <div class="hero-avatar">
            {{ userInitials }}
          </div>
          <div class="hero-info">
            <div class="hero-title-row">
              <h2>{{ profile.name }}</h2>
              <span :class="['role-badge', profile.role]">
                {{ formatRole(profile.role) }}
              </span>
            </div>
            <p v-if="profile.job_title" class="job-title-text">{{ profile.job_title }}</p>

            <div class="hero-meta-row">
              <span class="meta-item">
                <i class="icon">✉</i> {{ profile.email }}
              </span>
              <span v-if="profile.phone" class="meta-item">
                <i class="icon">📞</i> {{ profile.phone }}
              </span>
              <span class="meta-item">
                <i class="icon">🏛</i>
                <template v-if="assignedDepartments.length > 0">
                  {{ assignedDepartments.map(d => d.name).join(', ') }}
                </template>
                <template v-else>
                  Corporate Support Services (Default)
                </template>
              </span>
            </div>
          </div>
        </div>

        <div class="hero-stats">
          <div class="h-stat">
            <span class="h-num">{{ stats.total_actions_logged ?? 0 }}</span>
            <span class="h-label">Audit Events</span>
          </div>
          <div class="h-stat">
            <span class="h-num green">{{ stats.documents_count ?? 0 }}</span>
            <span class="h-label">Docs Uploaded</span>
          </div>
          <div class="h-stat">
            <span class="h-num purple">{{ stats.procurement_count ?? 0 }}</span>
            <span class="h-label">Tenders Managed</span>
          </div>
        </div>
      </section>

      <!-- Profile Tabs -->
      <div class="profile-tabs-bar mb-4">
        <button
          type="button"
          :class="['tab-btn', { active: activeTab === 'overview' }]"
          @click="activeTab = 'overview'"
        >
          👤 Overview & Permissions
        </button>
        <button
          v-if="!isViewingOther || auth.canManageUsers"
          type="button"
          :class="['tab-btn', { active: activeTab === 'edit' }]"
          @click="activeTab = 'edit'"
        >
          ✏ Edit Personal Info
        </button>
        <button
          v-if="!isViewingOther"
          type="button"
          :class="['tab-btn', { active: activeTab === 'security' }]"
          @click="activeTab = 'security'"
        >
          🔒 Security & Password
        </button>
        <button
          type="button"
          :class="['tab-btn', { active: activeTab === 'activity' }]"
          @click="activeTab = 'activity'"
        >
          📋 Audit Activity ({{ recentActivity.length }})
        </button>
      </div>

      <!-- TAB 1: OVERVIEW & PERMISSIONS -->
      <div v-if="activeTab === 'overview'" class="tab-content-grid">
        <!-- Account Details Card -->
        <div class="portal-panel">
          <h3 class="panel-title">Account Specifications</h3>
          <div class="info-list">
            <div class="info-item">
              <span class="info-label">Full Name</span>
              <strong class="info-value">{{ profile.name }}</strong>
            </div>
            <div class="info-item">
              <span class="info-label">Email Address</span>
              <strong class="info-value">{{ profile.email }}</strong>
            </div>
            <div class="info-item">
              <span class="info-label">Phone / Contact</span>
              <strong class="info-value">{{ profile.phone || 'Not specified' }}</strong>
            </div>
            <div class="info-item">
              <span class="info-label">Official Designation</span>
              <strong class="info-value">{{ profile.job_title || 'Municipal Staff' }}</strong>
            </div>
            <div class="info-item">
              <span class="info-label">System Role</span>
              <div class="info-value">
                <span :class="['role-badge', profile.role]">
                  {{ formatRole(profile.role) }}
                </span>
              </div>
            </div>
            <div class="info-item">
              <span class="info-label">Account Created</span>
              <strong class="info-value">{{ formatDate(profile.created_at) }}</strong>
            </div>
            <div class="info-item">
              <span class="info-label">Last Recorded Login</span>
              <strong class="info-value">{{ formatDateTime(stats.last_login_at) }}</strong>
            </div>
          </div>
        </div>

        <!-- Assigned Directorates & Permissions -->
        <div class="portal-panel">
          <h3 class="panel-title">Directorates & Permissions Matrix</h3>
          
          <div class="dept-box mb-3">
            <span class="dept-box-label">Assigned Municipal Directorates</span>
            <div v-if="assignedDepartments.length > 0" class="dept-badges-wrap">
              <div
                v-for="dept in assignedDepartments"
                :key="dept.id"
                class="dept-tag"
              >
                <span class="dept-code">{{ dept.code }}</span>
                <span class="dept-name">{{ dept.name }}</span>
              </div>
            </div>
            <div v-else class="text-muted" style="font-size: 0.85rem;">
              Corporate Support Services (Default Directorate)
            </div>
          </div>

          <div class="permissions-checklist">
            <span class="dept-box-label mb-2 d-block">Role Access Rights</span>
            <div
              v-for="(granted, perm) in permissions"
              :key="perm"
              class="permission-row"
            >
              <span :class="['perm-icon', { granted }]">
                {{ granted ? '✓' : '✕' }}
              </span>
              <span class="perm-name" :class="{ 'perm-disabled': !granted }">
                {{ perm }}
              </span>
              <span :class="['perm-status-tag', { granted }]">
                {{ granted ? 'Authorized' : 'Restricted' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 2: EDIT PERSONAL INFO -->
      <div v-else-if="activeTab === 'edit'" class="tab-content-single">
        <div class="portal-panel form-panel">
          <h3 class="panel-title">Update Personal Information</h3>
          <p class="panel-desc">Modify personal contact information and designation for official portal records.</p>

          <form @submit.prevent="savePersonalInfo">
            <div class="form-grid-2">
              <label>
                Full Name *
                <input
                  v-model="editForm.name"
                  type="text"
                  required
                  placeholder="e.g. Mfuneko Spengane"
                />
              </label>

              <label>
                Email Address *
                <input
                  v-model="editForm.email"
                  type="email"
                  required
                  placeholder="e.g. spenganem@ndz.gov.za"
                />
              </label>
            </div>

            <div class="form-grid-2">
              <label>
                Contact / Telephone Number
                <input
                  v-model="editForm.phone"
                  type="text"
                  placeholder="e.g. +27 39 833 1038"
                />
              </label>

              <label>
                Job Title / Municipal Designation
                <input
                  v-model="editForm.job_title"
                  type="text"
                  placeholder="e.g. IT Administrator / Municipal Manager"
                />
              </label>
            </div>

            <div class="form-actions mt-3">
              <button type="submit" class="btn-primary-action" :disabled="saving">
                {{ saving ? 'Saving Changes...' : 'Save Profile Changes' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- TAB 3: SECURITY & PASSWORD -->
      <div v-else-if="activeTab === 'security'" class="tab-content-single">
        <div class="portal-panel form-panel">
          <h3 class="panel-title">Change Password</h3>
          <p class="panel-desc">Keep your municipal administrator account secure with a strong password.</p>

          <form @submit.prevent="changePassword">
            <label>
              Current Password *
              <input
                v-model="passwordForm.current_password"
                type="password"
                required
                placeholder="Enter your current password"
              />
            </label>

            <div class="form-grid-2">
              <label>
                New Password *
                <input
                  v-model="passwordForm.password"
                  type="password"
                  required
                  minlength="8"
                  placeholder="At least 8 characters"
                />
              </label>

              <label>
                Confirm New Password *
                <input
                  v-model="passwordForm.password_confirmation"
                  type="password"
                  required
                  minlength="8"
                  placeholder="Re-enter new password"
                />
              </label>
            </div>

            <div class="form-actions mt-3">
              <button type="submit" class="btn-primary-action" :disabled="savingPassword">
                {{ savingPassword ? 'Updating Password...' : 'Update Password' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- TAB 4: AUDIT ACTIVITY -->
      <div v-else-if="activeTab === 'activity'" class="tab-content-single">
        <div class="portal-panel">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="panel-title mb-0">Recent User Operations</h3>
            <router-link
              v-if="auth.canViewAuditLogs"
              :to="{ path: '/dashboard/audit-trail', query: { user_id: profile.id } }"
              class="btn-view-all-audit"
            >
              View Full Audit Trail &rarr;
            </router-link>
          </div>

          <div v-if="recentActivity.length === 0" class="empty-state">
            No recent activity recorded for this user yet.
          </div>

          <div v-else class="table-responsive">
            <table class="manager-table">
              <thead>
                <tr>
                  <th style="width: 170px;">Date & Time</th>
                  <th>Module</th>
                  <th>Action</th>
                  <th>Description</th>
                  <th>IP Address</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="act in recentActivity" :key="act.id">
                  <td>
                    <span class="audit-datetime">{{ formatDateTime(act.created_at) }}</span>
                  </td>
                  <td>
                    <span class="module-pill">{{ act.module }}</span>
                  </td>
                  <td>
                    <span :class="['action-pill', act.action]">
                      {{ (act.action || '').toUpperCase() }}
                    </span>
                  </td>
                  <td>
                    <span class="activity-desc">{{ act.description }}</span>
                  </td>
                  <td>
                    <code class="ip-code">{{ act.ip_address || '127.0.0.1' }}</code>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../components/DashboardLayout.vue'
import { useAuthStore } from '../stores/auth'
import api from '../api/axios'

export default {
  name: 'DashboardProfileView',
  components: { DashboardLayout },
  setup() {
    const auth = useAuthStore()
    return { auth }
  },
  data() {
    return {
      profile: {},
      permissions: {},
      recentActivity: [],
      stats: {},
      activeTab: 'overview',
      loading: true,
      saving: false,
      savingPassword: false,
      notice: '',
      error: '',
      editForm: {
        name: '',
        email: '',
        phone: '',
        job_title: '',
      },
      passwordForm: {
        current_password: '',
        password: '',
        password_confirmation: '',
      },
    }
  },
  computed: {
    isViewingOther() {
      const targetId = this.$route.query.id || this.$route.params.id
      return targetId && Number(targetId) !== Number(this.auth.user?.id)
    },
    userInitials() {
      const name = this.profile.name || this.auth.user?.name || 'Portal User'
      return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
    },
    assignedDepartments() {
      if (Array.isArray(this.profile.departments) && this.profile.departments.length > 0) {
        return this.profile.departments
      }
      if (this.profile.department) {
        return [this.profile.department]
      }
      return []
    },
  },
  watch: {
    '$route.query.id'() {
      this.fetchProfile()
    },
  },
  mounted() {
    this.fetchProfile()
  },
  methods: {
    async fetchProfile() {
      this.loading = true
      this.error = ''
      try {
        const targetId = this.$route.query.id || this.$route.params.id
        const endpoint = (targetId && Number(targetId) !== Number(this.auth.user?.id))
          ? `/api/profile/${targetId}`
          : '/api/profile'

        const { data } = await api.get(endpoint)
        const d = data.data || {}

        this.profile = d
        this.permissions = d.permissions || {}
        this.recentActivity = d.recent_activity || []
        this.stats = d.stats || {}

        this.editForm = {
          name: d.name || '',
          email: d.email || '',
          phone: d.phone || '',
          job_title: d.job_title || '',
        }
      } catch (err) {
        this.error = 'Failed to load profile details.'
      } finally {
        this.loading = false
      }
    },
    async savePersonalInfo() {
      this.saving = true
      this.error = ''
      this.notice = ''

      try {
        const { data } = await api.put('/api/profile', this.editForm)
        this.notice = 'Profile details updated successfully.'
        if (data.data) {
          this.profile = data.data
          // Update pinia store
          if (this.auth.user) {
            this.auth.user.name = data.data.name
            this.auth.user.email = data.data.email
            this.auth.user.phone = data.data.phone
            this.auth.user.job_title = data.data.job_title
            localStorage.setItem('auth_user', JSON.stringify(this.auth.user))
          }
        }
        setTimeout(() => { this.notice = '' }, 4000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to update profile.'
      } finally {
        this.saving = false
      }
    },
    async changePassword() {
      if (this.passwordForm.password !== this.passwordForm.password_confirmation) {
        this.error = 'New password and confirmation do not match.'
        return
      }

      this.savingPassword = true
      this.error = ''
      this.notice = ''

      try {
        await api.put('/api/profile/password', this.passwordForm)
        this.notice = 'Account password updated successfully.'
        this.passwordForm = {
          current_password: '',
          password: '',
          password_confirmation: '',
        }
        setTimeout(() => { this.notice = '' }, 4000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to update password.'
      } finally {
        this.savingPassword = false
      }
    },
    formatRole(role) {
      const map = {
        admin: 'Administrator',
        manager: 'Municipal Manager',
        editor: 'Content Editor',
        citizen: 'Citizen User',
      }
      return map[role] || role || 'Staff User'
    },
    formatDate(dateStr) {
      if (!dateStr) return 'N/A'
      return new Date(dateStr).toLocaleDateString('en-ZA', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      })
    },
    formatDateTime(dateStr) {
      if (!dateStr) return 'No recorded login'
      return new Date(dateStr).toLocaleString('en-ZA', {
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
.profile-container {
  max-width: 1100px;
}

.btn-secondary-header {
  background: #ffffff;
  color: #0f6b3b;
  border: 1px solid #cce5d6;
  padding: 0.6rem 1.2rem;
  border-radius: 12px;
  font-weight: 700;
  font-size: 0.85rem;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  box-shadow: 0 2px 8px rgba(15, 107, 59, 0.08);
}

.btn-secondary-header:hover {
  background: #f0f7f3;
  border-color: #0f6b3b;
}

/* Profile Hero Card */
.profile-hero-card {
  background: linear-gradient(135deg, #ffffff 0%, #f7fbf8 100%);
  border: 1px solid #dbe8e0;
  border-radius: 24px;
  padding: 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;
  box-shadow: 0 6px 25px rgba(15, 107, 59, 0.05);
  flex-wrap: wrap;
}

.hero-main {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.hero-avatar {
  width: 80px;
  height: 80px;
  border-radius: 24px;
  background: #f59e0b;
  color: #1a2332;
  font-size: 1.9rem;
  font-weight: 800;
  display: grid;
  place-items: center;
  flex-shrink: 0;
  box-shadow: 0 8px 20px rgba(245, 158, 11, 0.25);
}

.hero-info {
  display: flex;
  flex-direction: column;
}

.hero-title-row {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  flex-wrap: wrap;
}

.hero-title-row h2 {
  margin: 0;
  font-size: 1.6rem;
  font-weight: 800;
  color: #17231c;
}

.job-title-text {
  margin: 0.2rem 0 0.5rem;
  font-size: 0.95rem;
  color: #55695e;
  font-weight: 600;
}

.hero-meta-row {
  display: flex;
  align-items: center;
  gap: 1.25rem;
  flex-wrap: wrap;
  margin-top: 0.4rem;
}

.meta-item {
  font-size: 0.85rem;
  color: #4b5f54;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  font-weight: 500;
}

.meta-item .icon {
  font-style: normal;
  color: #0f6b3b;
}

.hero-stats {
  display: flex;
  gap: 1.5rem;
  padding: 0.8rem 1.5rem;
  background: #ffffff;
  border: 1px solid #e4ece7;
  border-radius: 18px;
}

.h-stat {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.h-num {
  font-size: 1.4rem;
  font-weight: 800;
  color: #17231c;
}

.h-num.green { color: #0f6b3b; }
.h-num.purple { color: #512da8; }

.h-label {
  font-size: 0.72rem;
  font-weight: 700;
  color: #6c7d73;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  margin-top: 0.15rem;
}

/* Tabs Bar */
.profile-tabs-bar {
  display: flex;
  gap: 0.65rem;
  overflow-x: auto;
  padding-bottom: 0.25rem;
}

.tab-btn {
  background: #ffffff;
  border: 1px solid #d9e6de;
  color: #405448;
  padding: 0.65rem 1.2rem;
  border-radius: 12px;
  font-size: 0.88rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.tab-btn:hover {
  background: #f0f7f3;
  border-color: #a3c9b4;
  color: #0f6b3b;
}

.tab-btn.active {
  background: #0f6b3b;
  border-color: #0f6b3b;
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(15, 107, 59, 0.2);
}

/* Panels */
.tab-content-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

@media (max-width: 840px) {
  .tab-content-grid {
    grid-template-columns: 1fr;
  }
}

.portal-panel {
  background: #ffffff;
  border: 1px solid #e4ece7;
  border-radius: 20px;
  padding: 1.75rem;
  box-shadow: 0 4px 20px rgba(15, 107, 59, 0.04);
}

.panel-title {
  font-size: 1.15rem;
  font-weight: 800;
  color: #17231c;
  margin: 0 0 1.25rem;
}

.panel-desc {
  font-size: 0.88rem;
  color: #6c7d73;
  margin: -0.75rem 0 1.5rem;
}

/* Info List */
.info-list {
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
}

.info-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid #f0f5f2;
}

.info-label {
  font-size: 0.82rem;
  font-weight: 700;
  color: #6c7d73;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.info-value {
  font-size: 0.92rem;
  color: #17231c;
  text-align: right;
}

/* Department Box */
.dept-box {
  background: #fbfdfc;
  border: 1px solid #e4ece7;
  border-radius: 12px;
  padding: 1rem 1.25rem;
}

.dept-box-label {
  font-size: 0.78rem;
  font-weight: 800;
  color: #55695e;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 0.5rem;
  display: block;
}

.dept-badges-wrap {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.dept-tag {
  background: #edf7f0;
  border: 1px solid #c2e2ce;
  border-radius: 8px;
  padding: 0.35rem 0.65rem;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
}

.dept-code {
  background: #0f6b3b;
  color: #ffffff;
  font-size: 0.72rem;
  font-weight: 800;
  padding: 0.1rem 0.35rem;
  border-radius: 4px;
}

.dept-name {
  font-size: 0.85rem;
  font-weight: 700;
  color: #0f6b3b;
}

/* Permissions Checklist */
.permissions-checklist {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.permission-row {
  display: flex;
  align-items: center;
  padding: 0.5rem 0.75rem;
  border-radius: 8px;
  background: #fbfdfc;
  border: 1px solid #edf3ef;
  font-size: 0.85rem;
}

.perm-icon {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 800;
  margin-right: 0.65rem;
  background: #fee2e2;
  color: #991b1b;
}

.perm-icon.granted {
  background: #dcfce7;
  color: #166534;
}

.perm-name {
  flex: 1;
  font-weight: 600;
  color: #24352b;
}

.perm-disabled {
  color: #8fa096;
}

.perm-status-tag {
  font-size: 0.72rem;
  font-weight: 800;
  padding: 0.15rem 0.5rem;
  border-radius: 12px;
  background: #f3f4f6;
  color: #6b7280;
}

.perm-status-tag.granted {
  background: #dcfce7;
  color: #166534;
}

/* Role Badges */
.role-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.role-badge.admin { background: #fee2e2; color: #991b1b; }
.role-badge.manager { background: #ede9fe; color: #5b21b6; }
.role-badge.editor { background: #e0f2fe; color: #075985; }
.role-badge.citizen { background: #f3f4f6; color: #374151; }

/* Forms */
.form-panel {
  max-width: 680px;
}

.form-grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
}

@media (max-width: 640px) {
  .form-grid-2 {
    grid-template-columns: 1fr;
  }
}

.form-panel label {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  font-size: 0.8rem;
  font-weight: 700;
  color: #4b5f54;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  margin-bottom: 0.75rem;
}

.form-panel input {
  padding: 0.65rem 1rem;
  border: 1px solid #d9e6de;
  border-radius: 12px;
  font-size: 0.92rem;
  background: #fbfdfc;
  color: #17231c;
}

.form-panel input:focus {
  outline: none;
  border-color: #0f6b3b;
  box-shadow: 0 0 0 3px rgba(15, 107, 59, 0.12);
}

.btn-primary-action {
  background: #0f6b3b;
  color: #ffffff;
  border: none;
  padding: 0.7rem 1.6rem;
  border-radius: 12px;
  font-weight: 700;
  font-size: 0.92rem;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(15, 107, 59, 0.2);
  transition: all 0.2s ease;
}

.btn-primary-action:hover:not(:disabled) {
  background: #0c562f;
  transform: translateY(-1px);
}

.btn-primary-action:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Activity Table */
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
}

.manager-table td {
  padding: 0.85rem 1rem;
  border-bottom: 1px solid #f0f5f2;
  vertical-align: middle;
}

.audit-datetime {
  font-size: 0.82rem;
  font-weight: 700;
  color: #17231c;
  white-space: nowrap;
}

.module-pill {
  background: #f1f5f9;
  color: #334155;
  border-radius: 6px;
  padding: 0.2rem 0.55rem;
  font-size: 0.72rem;
  font-weight: 700;
}

.action-pill {
  display: inline-block;
  padding: 0.2rem 0.55rem;
  border-radius: 6px;
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 0.05em;
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

.activity-desc {
  font-size: 0.85rem;
  color: #17231c;
}

.ip-code {
  font-size: 0.75rem;
  background: #f1f5f3;
  padding: 0.2rem 0.45rem;
  border-radius: 4px;
  color: #4b5f54;
}

.btn-view-all-audit {
  color: #0f6b3b;
  font-weight: 700;
  font-size: 0.85rem;
  text-decoration: none;
}

.btn-view-all-audit:hover {
  text-decoration: underline;
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

.empty-state {
  text-align: center;
  padding: 2.5rem 1rem;
  color: #6c7d73;
}
</style>

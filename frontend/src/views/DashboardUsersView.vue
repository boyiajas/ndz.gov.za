<template>
  <DashboardLayout
    title="Users & Role Management"
    subtitle="Administer municipal user accounts, assign role-based permissions, and manage credentials."
  >
    <template #header-actions>
      <button v-if="activeTab === 'users'" type="button" class="btn-primary-action" @click="openAddModal">
        <span>+</span> Add User
      </button>
    </template>

    <div v-if="notice" class="portal-alert success">{{ notice }}</div>
    <div v-if="error" class="portal-alert danger">{{ error }}</div>

    <!-- Navigation Tabs -->
    <div class="users-tabs-bar mb-4">
      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'users' }"
        @click="activeTab = 'users'"
      >
        👥 Users Directory ({{ users.length }})
      </button>

      <button
        type="button"
        class="tab-btn"
        :class="{ active: activeTab === 'roles' }"
        @click="activeTab = 'roles'"
      >
        🛡 Roles & Permissions Matrix
      </button>
    </div>

    <!-- TAB 1: USERS DIRECTORY -->
    <div v-if="activeTab === 'users'">
      <!-- Filter Bar -->
      <section class="portal-panel filter-panel mb-4">
        <div class="filter-controls">
          <div class="search-wrap">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search user by name or email address..."
              @input="filterUsers"
            />
          </div>

          <div class="role-filter">
            <select v-model="filterRole" @change="filterUsers">
              <option value="all">All Roles</option>
              <option value="admin">Administrator</option>
              <option value="manager">Municipal Manager</option>
              <option value="editor">Content Editor</option>
              <option value="citizen">Citizen User</option>
            </select>
          </div>
        </div>
      </section>

      <!-- Users Table -->
      <section class="portal-panel">
        <div v-if="loading" class="loading-state">Loading users...</div>
        <div v-else-if="filteredUsers.length === 0" class="empty-state">
          <p>No users found matching your search.</p>
        </div>

        <div v-else class="table-responsive">
          <table class="manager-table">
            <thead>
              <tr>
                <th style="width: 50px;"></th>
                <th>Name & Account</th>
                <th>Security Role</th>
                <th>Joined Date</th>
                <th style="text-align: right;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in filteredUsers" :key="user.id">
                <td>
                  <div class="user-avatar-sm">{{ getInitials(user.name) }}</div>
                </td>
                <td>
                  <strong class="user-name">{{ user.name }}</strong>
                  <span class="user-email">{{ user.email }}</span>
                </td>
                <td>
                  <span :class="['role-badge', user.role]">
                    {{ getRoleLabel(user.role) }}
                  </span>
                </td>
                <td>
                  <span class="joined-date">{{ formatDate(user.created_at) }}</span>
                </td>
                <td style="text-align: right;">
                  <div class="action-buttons">
                    <button type="button" class="btn-edit" @click="openEditModal(user)">Edit Role</button>
                    <button
                      type="button"
                      class="btn-delete"
                      :disabled="user.id === auth.user?.id"
                      :title="user.id === auth.user?.id ? 'Cannot delete yourself' : 'Delete user'"
                      @click="deleteUser(user)"
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
    </div>

    <!-- TAB 2: ROLES & PERMISSIONS MATRIX -->
    <div v-else-if="activeTab === 'roles'">
      <section class="portal-panel">
        <div class="roles-intro mb-4">
          <h3>Role-Based Access Control (RBAC)</h3>
          <p>
            The Dr Nkosazana Dlamini-Zuma Local Municipality portal enforces strict privilege separation
            to safeguard sensitive citizen data, public procurement records, and official communications.
          </p>
        </div>

        <div class="roles-matrix-grid">
          <article v-for="role in rolesList" :key="role.id" class="role-matrix-card">
            <div class="role-matrix-header">
              <span :class="['role-badge', role.id]">{{ role.name }}</span>
              <p class="role-desc">{{ role.description }}</p>
            </div>

            <div class="permissions-list">
              <div
                v-for="(allowed, perm) in role.permissions"
                :key="perm"
                class="perm-row"
                :class="{ allowed }"
              >
                <span class="perm-icon">{{ allowed ? '✓' : '✗' }}</span>
                <span class="perm-name">{{ perm }}</span>
              </div>
            </div>
          </article>
        </div>
      </section>
    </div>

    <!-- Create / Edit User Modal -->
    <div v-if="isModalOpen" class="modal-backdrop" @click.self="closeModal">
      <div class="modal-sheet">
        <div class="modal-sheet-header">
          <div>
            <p>{{ editingUserId ? 'Modify Account' : 'New Account' }}</p>
            <h3>{{ editingUserId ? 'Edit User & Role' : 'Create Portal User' }}</h3>
          </div>
          <button type="button" class="close-btn" @click="closeModal">&times;</button>
        </div>

        <form class="modal-sheet-form" @submit.prevent="saveUser">
          <label>
            Full Name *
            <input v-model="form.name" type="text" placeholder="e.g. Sipho Ndlovu" required />
          </label>

          <label>
            Email Address *
            <input v-model="form.email" type="email" placeholder="e.g. sndlovu@ndz.gov.za" required />
          </label>

          <label>
            Assigned Security Role *
            <select v-model="form.role" required>
              <option value="admin">Administrator (Full Access)</option>
              <option value="manager">Municipal Manager (Operations & SCM)</option>
              <option value="editor">Content Editor (News & Documents)</option>
              <option value="citizen">Citizen (Read-Only Public Portal)</option>
            </select>
          </label>

          <label>
            {{ editingUserId ? 'New Password (leave blank to keep current)' : 'Account Password *' }}
            <input
              v-model="form.password"
              type="password"
              placeholder="Minimum 8 characters..."
              :required="!editingUserId"
            />
          </label>

          <div class="modal-footer-actions">
            <button type="button" class="btn-cancel" @click="closeModal">Cancel</button>
            <button type="submit" class="btn-save" :disabled="saving">
              {{ saving ? 'Saving User...' : (editingUserId ? 'Update User' : 'Create User') }}
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
  name: 'DashboardUsersView',
  components: { DashboardLayout },
  setup() {
    return { auth: useAuthStore() }
  },
  data() {
    return {
      activeTab: 'users',
      users: [],
      filteredUsers: [],
      rolesList: [],
      filterRole: 'all',
      searchQuery: '',
      loading: true,
      saving: false,
      notice: '',
      error: '',
      isModalOpen: false,
      editingUserId: null,
      form: {
        name: '',
        email: '',
        role: 'editor',
        password: '',
      },
    }
  },
  mounted() {
    this.fetchUsers()
    this.fetchRoles()
  },
  methods: {
    async fetchUsers() {
      this.loading = true
      try {
        const { data } = await api.get('/api/admin/users')
        this.users = data.data || []
        this.filterUsers()
      } catch (err) {
        this.error = 'Failed to load users directory.'
      } finally {
        this.loading = false
      }
    },
    async fetchRoles() {
      try {
        const { data } = await api.get('/api/admin/roles')
        this.rolesList = data.data || []
      } catch (err) {
        console.error('Failed to load roles list:', err)
      }
    },
    filterUsers() {
      let list = [...this.users]
      if (this.filterRole !== 'all') {
        list = list.filter((u) => u.role === this.filterRole)
      }
      if (this.searchQuery.trim()) {
        const q = this.searchQuery.toLowerCase()
        list = list.filter(
          (u) =>
            u.name?.toLowerCase().includes(q) ||
            u.email?.toLowerCase().includes(q)
        )
      }
      this.filteredUsers = list
    },
    openAddModal() {
      this.editingUserId = null
      this.form = {
        name: '',
        email: '',
        role: 'editor',
        password: '',
      }
      this.isModalOpen = true
    },
    openEditModal(user) {
      this.editingUserId = user.id
      this.form = {
        name: user.name,
        email: user.email,
        role: user.role,
        password: '',
      }
      this.isModalOpen = true
    },
    closeModal() {
      this.isModalOpen = false
      this.editingUserId = null
    },
    async saveUser() {
      this.saving = true
      this.error = ''
      this.notice = ''

      try {
        if (this.editingUserId) {
          await api.put(`/api/admin/users/${this.editingUserId}`, this.form)
          this.notice = 'User account updated successfully.'
        } else {
          await api.post('/api/admin/users', this.form)
          this.notice = 'New user created successfully.'
        }
        this.closeModal()
        await this.fetchUsers()
        setTimeout(() => { this.notice = '' }, 4000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to save user.'
      } finally {
        this.saving = false
      }
    },
    async deleteUser(user) {
      if (!confirm(`Are you sure you want to delete user account "${user.name}" (${user.email})?`)) {
        return
      }
      try {
        await api.delete(`/api/admin/users/${user.id}`)
        this.notice = 'User deleted successfully.'
        await this.fetchUsers()
        setTimeout(() => { this.notice = '' }, 3000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to delete user.'
      }
    },
    getInitials(name) {
      if (!name) return 'U'
      return name
        .split(' ')
        .map((p) => p[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
    },
    getRoleLabel(role) {
      const labels = {
        admin: 'Administrator',
        manager: 'Municipal Manager',
        editor: 'Content Editor',
        citizen: 'Citizen',
      }
      return labels[role] || role
    },
    formatDate(d) {
      if (!d) return '—'
      return new Date(d).toLocaleDateString('en-ZA', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
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

.users-tabs-bar {
  display: flex;
  gap: 0.75rem;
}

.tab-btn {
  border: 1px solid #d9e6de;
  background: #ffffff;
  color: #4a5c52;
  border-radius: 12px;
  padding: 0.65rem 1.25rem;
  font-size: 0.88rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
}

.tab-btn:hover,
.tab-btn.active {
  background: #0f6b3b;
  color: #ffffff;
  border-color: #0f6b3b;
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

.role-filter select {
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

.user-avatar-sm {
  width: 38px;
  height: 38px;
  border-radius: 12px;
  background: #0f6b3b;
  color: #ffffff;
  display: grid;
  place-items: center;
  font-weight: 800;
  font-size: 0.85rem;
}

.user-name {
  display: block;
  font-size: 0.92rem;
  color: #17231c;
}

.user-email {
  display: block;
  font-size: 0.78rem;
  color: #6c7d73;
  margin-top: 0.15rem;
}

.role-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.role-badge.admin { background: #f3e5f5; color: #7b1fa2; border: 1px solid #e1bee7; }
.role-badge.manager { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
.role-badge.editor { background: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb; }
.role-badge.citizen { background: #f5f5f5; color: #616161; border: 1px solid #e0e0e0; }

.joined-date {
  font-size: 0.82rem;
  color: #4a5c52;
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
  border-radius: 8px;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
}

.btn-delete {
  border: 1px solid #fcd2d2;
  background: #fff3f3;
  color: #d9383a;
  padding: 0.3rem 0.75rem;
  border-radius: 8px;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
}

.btn-delete:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

/* Matrix Layout */
.roles-intro h3 {
  margin: 0 0 0.4rem;
  font-size: 1.25rem;
  font-weight: 800;
  color: #17231c;
}

.roles-intro p {
  margin: 0;
  color: #6c7d73;
  font-size: 0.9rem;
  line-height: 1.6;
}

.roles-matrix-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 1.5rem;
}

.role-matrix-card {
  border: 1px solid #e4ece7;
  border-radius: 20px;
  padding: 1.5rem;
  background: #fafcfb;
  display: flex;
  flex-direction: column;
}

.role-matrix-header {
  border-bottom: 1px solid #edf3ef;
  padding-bottom: 1rem;
  margin-bottom: 1rem;
}

.role-desc {
  margin: 0.6rem 0 0;
  font-size: 0.8rem;
  color: #6c7d73;
  line-height: 1.5;
}

.permissions-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.perm-row {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-size: 0.8rem;
  color: #94a39b;
}

.perm-row.allowed {
  color: #17231c;
  font-weight: 600;
}

.perm-icon {
  font-size: 0.9rem;
  font-weight: 800;
  line-height: 1;
}

.perm-row.allowed .perm-icon {
  color: #0f6b3b;
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
  max-width: 550px;
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

.modal-sheet-form label {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
  font-size: 0.85rem;
  font-weight: 700;
  color: #17231c;
}

.modal-sheet-form input,
.modal-sheet-form select {
  border: 1px solid #d9e6de;
  border-radius: 12px;
  padding: 0.65rem 0.85rem;
  font-size: 0.9rem;
  background: #fbfdfc;
  color: #17231c;
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

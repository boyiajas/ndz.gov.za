<template>
  <div class="portal-shell">
    <aside class="portal-sidebar">
      <router-link class="portal-brand" to="/">
        <img src="/logo.png" alt="NDZ Logo" />
        <span>NDZ Portal</span>
      </router-link>

      <router-link
        to="/dashboard/profile"
        class="portal-user-card"
        title="View profile details"
      >
        <div class="portal-avatar">{{ initials }}</div>
        <div class="portal-user-meta">
          <strong>{{ auth.user?.name || 'Portal User' }}</strong>
          <span>{{ roleLabel }}</span>
        </div>
        <span class="user-card-arrow" aria-hidden="true">›</span>
      </router-link>

      <nav class="portal-nav" aria-label="Portal navigation">
        <div v-for="group in menuGroups" :key="group.title" class="portal-nav-group">
          <p>{{ group.title }}</p>

          <template v-for="item in group.items" :key="item.label">
            <details v-if="item.children" class="portal-nav-details" open>
              <summary>
                <span>{{ item.icon }}</span>
                {{ item.label }}
              </summary>
              <router-link
                v-for="child in item.children"
                :key="child.label"
                :to="child.to"
                class="portal-nav-link portal-nav-child"
              >
                <span>{{ child.icon }}</span>
                {{ child.label }}
              </router-link>
            </details>

            <router-link v-else :to="item.to" class="portal-nav-link">
              <span>{{ item.icon }}</span>
              {{ item.label }}
            </router-link>
          </template>
        </div>
      </nav>

      <button class="portal-logout" type="button" @click="handleLogout">
        <span>↩</span>
        Logout
      </button>
    </aside>

    <main class="portal-main">
      <header class="portal-header">
        <div>
          <p class="portal-eyebrow">NDZ Municipality</p>
          <h1>{{ title }}</h1>
          <span>{{ subtitle }}</span>
        </div>
        <slot name="header-actions"></slot>
      </header>

      <slot></slot>
    </main>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
import '../assets/dashboard-compact.css'

export default {
  name: 'DashboardLayout',
  props: {
    title: {
      type: String,
      required: true,
    },
    subtitle: {
      type: String,
      default: '',
    },
  },
  setup() {
    const auth = useAuthStore()
    const router = useRouter()

    async function handleLogout() {
      await auth.logout()
      router.push('/')
    }

    return { auth, handleLogout }
  },
  computed: {
    initials() {
      return (this.auth.user?.name || 'Portal User')
        .split(' ')
        .map((name) => name[0])
        .join('')
        .toUpperCase()
        .slice(0, 2)
    },
    roleLabel() {
      return this.auth.roleLabel
    },
    menuGroups() {
      const groups = [
        {
          title: 'Main',
          items: [
            { icon: '⊞', label: 'Overview', to: '/dashboard' },
          ],
        },
      ]

      const contentItems = []
      if (this.auth.canManageDocuments) {
        contentItems.push({ icon: '📁', label: 'Document Manager', to: '/dashboard/documents' })
      }
      if (this.auth.canManageContent) {
        contentItems.push({ icon: '📰', label: 'News & Blogs', to: '/dashboard/news' })
        contentItems.push({ icon: '🖼', label: 'Event Gallery', to: '/dashboard/gallery' })
      }
      if (this.auth.canManageProcurement) {
        contentItems.push({ icon: '📑', label: 'Tenders & Quotes', to: '/dashboard/tenders' })
      }
      if (this.auth.canManageVacancies) {
        contentItems.push({ icon: '💼', label: 'Vacancies / Careers', to: '/dashboard/vacancies' })
      }

      if (contentItems.length > 0) {
        groups.push({
          title: 'Content Management',
          items: contentItems,
        })
      }

      const adminItems = []
      if (this.auth.canManageUsers) {
        adminItems.push({ icon: '👥', label: 'Users & Roles', to: '/dashboard/users' })
      }
      if (this.auth.canManageDepartments) {
        adminItems.push({ icon: '🏛', label: 'Departments', to: '/dashboard/departments' })
      }
      if (this.auth.canViewAuditLogs) {
        adminItems.push({ icon: '📋', label: 'Audit Trail', to: '/dashboard/audit-trail' })
      }
      if (this.auth.canManageSettings) {
        adminItems.push({ icon: '⚙', label: 'Site Settings', to: '/dashboard/settings' })
      }

      if (adminItems.length > 0) {
        groups.push({
          title: 'Administration',
          items: adminItems,
        })
      }

      groups.push({
        title: 'Public Links',
        items: [
          { icon: '▣', label: 'Public Documents', to: '/documents' },
          { icon: '↗', label: 'View Website Home', to: '/' },
        ],
      })

      return groups
    },
  },
}
</script>

<style scoped>
.portal-shell {
  min-height: 100vh;
  display: flex;
  background: #f3f6f4;
  font-size: 13px;
}

.portal-sidebar {
  width: 240px;
  background: linear-gradient(180deg, #0f6b3b 0%, #158149 55%, #0e5634 100%);
  color: #ffffff;
  padding: 0.65rem 0.75rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  position: sticky;
  top: 0;
  min-height: 100vh;
  box-shadow: 10px 0 30px rgba(15, 107, 59, 0.15);
}

.portal-brand {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #ffffff;
  font-weight: 800;
  font-size: 0.92rem;
  padding: 0.2rem 0.2rem 0.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.15);
}

.portal-brand img {
  width: 36px;
  height: 36px;
  object-fit: contain;
  background: #ffffff;
  border-radius: 10px;
  padding: 0.15rem;
}

.portal-user-card {
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.18);
  border-radius: 12px;
  padding: 0.45rem 0.65rem;
  display: flex;
  align-items: center;
  gap: 0.6rem;
  text-decoration: none;
  color: #ffffff;
  cursor: pointer;
  transition: all 0.2s ease;
}

.portal-user-card:hover {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.35);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.portal-user-card.router-link-active {
  background: rgba(255, 255, 255, 0.24);
  border-color: rgba(255, 255, 255, 0.42);
  box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.25);
}

.portal-avatar {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  background: var(--accent);
  color: #1a2332;
  display: grid;
  place-items: center;
  font-weight: 800;
  font-size: 0.75rem;
  flex-shrink: 0;
}

.portal-user-meta {
  flex: 1;
  min-width: 0;
}

.portal-user-meta strong {
  display: block;
  font-size: 0.8rem;
  font-weight: 700;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.portal-user-meta span {
  display: block;
  color: rgba(255, 255, 255, 0.75);
  font-size: 0.68rem;
  margin-top: 0.05rem;
}

.user-card-arrow {
  color: rgba(255, 255, 255, 0.45);
  font-size: 1.1rem;
  font-weight: 300;
  line-height: 1;
  transition: transform 0.2s ease, color 0.2s ease;
}

.portal-user-card:hover .user-card-arrow {
  color: #ffffff;
  transform: translateX(2px);
}

.portal-nav {
  display: flex;
  flex-direction: column;
  gap: 0.45rem;
  overflow-y: auto;
  padding-right: 0.15rem;
}

.portal-nav-group p {
  margin: 0 0 0.2rem;
  color: rgba(255, 255, 255, 0.58);
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-size: 0.6rem;
  font-weight: 800;
}

.portal-nav-link,
.portal-nav-details summary {
  display: flex;
  align-items: center;
  gap: 0.45rem;
  color: rgba(255, 255, 255, 0.86);
  border-radius: 8px;
  padding: 0.38rem 0.55rem;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.18s ease, color 0.18s ease, transform 0.18s ease;
}

.portal-nav-link:hover,
.portal-nav-link.router-link-active,
.portal-nav-details summary:hover {
  background: rgba(255, 255, 255, 0.15);
  color: #ffffff;
  transform: translateX(2px);
}

.portal-nav-details {
  margin-bottom: 0.1rem;
}

.portal-nav-details summary {
  list-style: none;
}

.portal-nav-details summary::-webkit-details-marker {
  display: none;
}

.portal-nav-child {
  margin-left: 0.65rem;
  padding: 0.28rem 0.5rem;
  font-size: 0.72rem;
  font-weight: 600;
}

.portal-logout {
  margin-top: auto;
  border: 0;
  border-radius: 10px;
  padding: 0.45rem 0.75rem;
  background: rgba(255, 255, 255, 0.16);
  color: #ffffff;
  font-weight: 800;
  font-size: 0.76rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  cursor: pointer;
}

.portal-main {
  flex: 1;
  padding: 0.75rem 1rem;
  min-width: 0;
}

.portal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  background:
    linear-gradient(135deg, rgba(31, 156, 88, 0.94), rgba(18, 111, 62, 0.94)),
    radial-gradient(circle at top right, rgba(252, 191, 27, 0.35), transparent 32%);
  color: #ffffff;
  border-radius: 14px;
  padding: 0.65rem 1.1rem;
  margin-bottom: 0.65rem;
  box-shadow: 0 10px 28px rgba(31, 156, 88, 0.16);
}

.portal-eyebrow {
  margin: 0 0 0.1rem;
  color: rgba(255, 255, 255, 0.72);
  text-transform: uppercase;
  letter-spacing: 0.12em;
  font-size: 0.62rem;
  font-weight: 800;
}

.portal-header h1 {
  margin: 0;
  font-size: clamp(1.15rem, 1.8vw, 1.35rem);
  font-weight: 800;
  line-height: 1.2;
}

.portal-header span {
  display: block;
  color: rgba(255, 255, 255, 0.82);
  font-size: 0.76rem;
  margin-top: 0.15rem;
}

@media (max-width: 992px) {
  .portal-shell {
    display: block;
  }

  .portal-sidebar {
    width: 100%;
    min-height: auto;
    position: static;
    border-radius: 0 0 16px 16px;
  }

  .portal-main {
    padding: 0.65rem;
  }

  .portal-header {
    align-items: flex-start;
    flex-direction: column;
    border-radius: 14px;
  }
}
</style>

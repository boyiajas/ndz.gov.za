<template>
  <DashboardLayout
    title="Portal Overview"
    :subtitle="`Welcome back, ${auth.user?.name || 'Portal User'}. Manage municipal content, publications, and administrative settings.`"
  >
    <!-- Top Stat Cards -->
    <section class="dashboard-grid">
      <article class="portal-stat-card">
        <div class="portal-stat-icon doc-icon">📁</div>
        <div>
          <strong>{{ liveStats.documents_count !== null ? liveStats.documents_count : '--' }}</strong>
          <span>Public Documents</span>
        </div>
      </article>

      <article class="portal-stat-card">
        <div class="portal-stat-icon news-icon">📰</div>
        <div>
          <strong>{{ liveStats.news_count !== null ? liveStats.news_count : '--' }}</strong>
          <span>Articles & Blogs</span>
        </div>
      </article>

      <article class="portal-stat-card">
        <div class="portal-stat-icon gallery-icon">🖼</div>
        <div>
          <strong>{{ liveStats.gallery_count !== null ? liveStats.gallery_count : '--' }}</strong>
          <span>Gallery Photos</span>
        </div>
      </article>

      <article class="portal-stat-card">
        <div class="portal-stat-icon tender-icon">📑</div>
        <div>
          <strong>{{ (liveStats.tenders_count || 0) + (liveStats.quotes_count || 0) }}</strong>
          <span>Open Procurement</span>
        </div>
      </article>

      <article v-if="auth.canManageUsers" class="portal-stat-card">
        <div class="portal-stat-icon user-icon">👥</div>
        <div>
          <strong>{{ liveStats.users_count !== null ? liveStats.users_count : '--' }}</strong>
          <span>Registered Users</span>
        </div>
      </article>
    </section>

    <!-- Workspace & Profile Row -->
    <section class="portal-content-grid">
      <!-- Quick Operations Grid -->
      <article class="portal-panel portal-panel-large">
        <div class="panel-heading">
          <div>
            <p>Operations</p>
            <h2>Municipal Management Workspace</h2>
          </div>
          <span class="role-pill">{{ auth.roleLabel }}</span>
        </div>

        <div class="workspace-actions">
          <router-link v-if="auth.canManageDocuments" to="/dashboard/documents" class="workspace-action primary">
            <span class="action-icon">📁</span>
            <div>
              <strong>Document Manager</strong>
              <small>Maintain categories, subcategories, and public PDFs.</small>
            </div>
          </router-link>

          <router-link v-if="auth.canManageContent" to="/dashboard/news" class="workspace-action">
            <span class="action-icon">📰</span>
            <div>
              <strong>News & Blogs Manager</strong>
              <small>Publish stories and update the home page news feed.</small>
            </div>
          </router-link>

          <router-link v-if="auth.canManageContent" to="/dashboard/gallery" class="workspace-action">
            <span class="action-icon">🖼</span>
            <div>
              <strong>Event Gallery</strong>
              <small>Upload media for community events and tourism albums.</small>
            </div>
          </router-link>

          <router-link v-if="auth.canManageProcurement" to="/dashboard/tenders" class="workspace-action">
            <span class="action-icon">📑</span>
            <div>
              <strong>Tenders & Quotes</strong>
              <small>Post open tenders, RFQs, and contract awards.</small>
            </div>
          </router-link>

          <router-link v-if="auth.canManageUsers" to="/dashboard/users" class="workspace-action">
            <span class="action-icon">👥</span>
            <div>
              <strong>Users & Role Management</strong>
              <small>Manage municipal accounts and security access roles.</small>
            </div>
          </router-link>

          <router-link v-if="auth.canManageSettings" to="/dashboard/settings" class="workspace-action">
            <span class="action-icon">⚙</span>
            <div>
              <strong>Site Settings</strong>
              <small>Configure contact hotlines, emergency numbers, and banner.</small>
            </div>
          </router-link>
        </div>
      </article>

      <!-- Profile & Role Capabilities -->
      <article class="portal-panel">
        <div class="panel-heading compact">
          <div>
            <p>Security Profile</p>
            <h2>Signed in user</h2>
          </div>
        </div>
        <div class="profile-card">
          <div class="profile-avatar">{{ initials }}</div>
          <strong>{{ auth.user?.name }}</strong>
          <span class="profile-email">{{ auth.user?.email }}</span>
          <span class="profile-badge">{{ auth.roleLabel }}</span>

          <div class="role-capabilities">
            <p class="capabilities-title">Authorized Capabilities</p>
            <ul class="capabilities-list">
              <li :class="{ 'is-active': auth.canManageDocuments }">
                <span>{{ auth.canManageDocuments ? '✓' : '✗' }}</span> Document Catalog
              </li>
              <li :class="{ 'is-active': auth.canManageContent }">
                <span>{{ auth.canManageContent ? '✓' : '✗' }}</span> News & Media Publishing
              </li>
              <li :class="{ 'is-active': auth.canManageProcurement }">
                <span>{{ auth.canManageProcurement ? '✓' : '✗' }}</span> SCM & Procurement
              </li>
              <li :class="{ 'is-active': auth.canManageUsers }">
                <span>{{ auth.canManageUsers ? '✓' : '✗' }}</span> User & Role Administration
              </li>
              <li :class="{ 'is-active': auth.canManageSettings }">
                <span>{{ auth.canManageSettings ? '✓' : '✗' }}</span> Municipal Settings
              </li>
            </ul>
          </div>
        </div>
      </article>
    </section>

    <!-- Recent Activity Feeds -->
    <section class="activity-grid mt-4">
      <!-- Recent News Articles -->
      <article class="portal-panel">
        <div class="panel-heading compact">
          <div>
            <p>Live Feed</p>
            <h2>Recent News & Blogs</h2>
          </div>
          <router-link v-if="auth.canManageContent" to="/dashboard/news" class="panel-link">Manage →</router-link>
        </div>
        <div v-if="recentArticles.length === 0" class="empty-feed">No articles published yet.</div>
        <ul v-else class="feed-list">
          <li v-for="article in recentArticles" :key="article.id" class="feed-item">
            <div class="feed-copy">
              <strong>{{ article.title }}</strong>
              <small>{{ article.category }} • {{ article.published_at || 'Draft' }}</small>
            </div>
            <router-link v-if="auth.canManageContent" :to="`/dashboard/news`" class="feed-action">Edit</router-link>
          </li>
        </ul>
      </article>

      <!-- Recent Procurement Notices -->
      <article class="portal-panel">
        <div class="panel-heading compact">
          <div>
            <p>Procurement</p>
            <h2>Latest Tenders & Quotes</h2>
          </div>
          <router-link v-if="auth.canManageProcurement" to="/dashboard/tenders" class="panel-link">Manage →</router-link>
        </div>
        <div v-if="recentNotices.length === 0" class="empty-feed">No procurement notices recorded yet.</div>
        <ul v-else class="feed-list">
          <li v-for="notice in recentNotices" :key="notice.id" class="feed-item">
            <div class="feed-copy">
              <strong>{{ notice.title }}</strong>
              <small>{{ notice.reference_no }} • {{ notice.type.toUpperCase() }} ({{ notice.status }})</small>
            </div>
            <span class="status-tag" :class="notice.status">{{ notice.status }}</span>
          </li>
        </ul>
      </article>
    </section>
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../components/DashboardLayout.vue'
import { useAuthStore } from '../stores/auth'
import api from '../api/axios'

export default {
  name: 'DashboardView',
  components: { DashboardLayout },
  setup() {
    return { auth: useAuthStore() }
  },
  data() {
    return {
      liveStats: {
        documents_count: null,
        news_count: null,
        gallery_count: null,
        tenders_count: null,
        quotes_count: null,
        users_count: null,
      },
      recentArticles: [],
      recentDocuments: [],
      recentNotices: [],
    }
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
  },
  mounted() {
    this.fetchDashboardStats()
  },
  methods: {
    async fetchDashboardStats() {
      try {
        const { data } = await api.get('/api/admin/dashboard-stats')
        this.liveStats = data.stats || this.liveStats
        this.recentArticles = data.recent_articles || []
        this.recentDocuments = data.recent_documents || []
        this.recentNotices = data.recent_notices || []
      } catch (err) {
        console.error('Failed to load dashboard stats:', err)
      }
    },
  },
}
</script>

<style scoped>
.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.portal-stat-card {
  background: #ffffff;
  border: 1px solid #e4ece7;
  border-radius: 20px;
  padding: 1.25rem 1.4rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 4px 20px rgba(15, 107, 59, 0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.portal-stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(15, 107, 59, 0.08);
}

.portal-stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  background: #f0f7f3;
  color: #0f6b3b;
  display: grid;
  place-items: center;
  font-size: 1.35rem;
  font-weight: 800;
  flex-shrink: 0;
}

.portal-stat-icon.doc-icon { background: #e8f5e9; color: #2e7d32; }
.portal-stat-icon.news-icon { background: #e3f2fd; color: #1565c0; }
.portal-stat-icon.gallery-icon { background: #fff3e0; color: #e65100; }
.portal-stat-icon.tender-icon { background: #ede7f6; color: #512da8; }
.portal-stat-icon.user-icon { background: #fce4ec; color: #c2185b; }

.portal-stat-card strong {
  display: block;
  font-size: 1.6rem;
  font-weight: 800;
  color: #17231c;
  line-height: 1.1;
}

.portal-stat-card span {
  display: block;
  color: #6c7d73;
  font-size: 0.82rem;
  font-weight: 600;
  margin-top: 0.25rem;
}

.portal-content-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1.5rem;
}

.activity-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

.portal-panel {
  background: #ffffff;
  border: 1px solid #e4ece7;
  border-radius: 24px;
  padding: 1.6rem;
  box-shadow: 0 4px 20px rgba(15, 107, 59, 0.04);
}

.portal-panel-large {
  min-height: 380px;
}

.panel-heading {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 1.25rem;
}

.panel-heading p {
  margin: 0 0 0.2rem;
  color: #0f6b3b;
  font-size: 0.75rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.1em;
}

.panel-heading h2 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 800;
  color: #17231c;
}

.panel-link {
  font-size: 0.85rem;
  font-weight: 700;
  color: #0f6b3b;
  text-decoration: none;
}

.panel-link:hover {
  text-decoration: underline;
}

.role-pill {
  background: #e8f4ed;
  color: #0f6b3b;
  border: 1px solid #cce5d6;
  border-radius: 999px;
  padding: 0.35rem 0.85rem;
  font-size: 0.78rem;
  font-weight: 800;
}

.workspace-actions {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1rem;
}

.workspace-action {
  border: 1px solid #e4ece7;
  border-radius: 18px;
  padding: 1.1rem;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 0.9rem;
  transition: all 0.2s ease;
  background: #fafcfb;
}

.workspace-action:hover {
  border-color: #0f6b3b;
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(15, 107, 59, 0.08);
}

.workspace-action.primary {
  background: linear-gradient(135deg, #0f6b3b 0%, #158149 100%);
  color: #ffffff;
  border-color: #0f6b3b;
}

.workspace-action.primary strong {
  color: #ffffff;
}

.workspace-action.primary small {
  color: rgba(255, 255, 255, 0.82);
}

.action-icon {
  font-size: 1.4rem;
  line-height: 1;
}

.workspace-action strong {
  display: block;
  font-size: 0.92rem;
  font-weight: 800;
  color: #17231c;
}

.workspace-action small {
  display: block;
  font-size: 0.78rem;
  color: #6c7d73;
  margin-top: 0.2rem;
}

.profile-card {
  text-align: center;
  padding: 0.5rem 0;
}

.profile-avatar {
  width: 72px;
  height: 72px;
  border-radius: 24px;
  background: #0f6b3b;
  color: #ffffff;
  display: grid;
  place-items: center;
  font-size: 1.5rem;
  font-weight: 800;
  margin: 0 auto 0.9rem;
}

.profile-card strong {
  display: block;
  font-size: 1.1rem;
  font-weight: 800;
  color: #17231c;
}

.profile-email {
  display: block;
  font-size: 0.82rem;
  color: #6c7d73;
  margin: 0.2rem 0 0.6rem;
}

.profile-badge {
  display: inline-block;
  background: #f0f7f3;
  color: #0f6b3b;
  border: 1px solid #c2dbcd;
  border-radius: 999px;
  padding: 0.3rem 0.85rem;
  font-size: 0.78rem;
  font-weight: 800;
}

.role-capabilities {
  margin-top: 1.25rem;
  text-align: left;
  border-top: 1px solid #edf3ef;
  padding-top: 1rem;
}

.capabilities-title {
  margin: 0 0 0.6rem;
  font-size: 0.72rem;
  font-weight: 800;
  text-transform: uppercase;
  color: #6c7d73;
  letter-spacing: 0.08em;
}

.capabilities-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}

.capabilities-list li {
  font-size: 0.8rem;
  color: #94a39b;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
}

.capabilities-list li.is-active {
  color: #17231c;
}

.capabilities-list li span {
  font-weight: 800;
  font-size: 0.85rem;
}

.capabilities-list li.is-active span {
  color: #0f6b3b;
}

.feed-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.feed-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 0.75rem 0.9rem;
  border-radius: 12px;
  background: #fafcfb;
  border: 1px solid #edf3ef;
}

.feed-copy strong {
  display: block;
  font-size: 0.86rem;
  font-weight: 700;
  color: #17231c;
  line-height: 1.3;
}

.feed-copy small {
  display: block;
  font-size: 0.75rem;
  color: #6c7d73;
  margin-top: 0.2rem;
}

.feed-action {
  font-size: 0.78rem;
  font-weight: 700;
  color: #0f6b3b;
  text-decoration: none;
  padding: 0.25rem 0.5rem;
  border-radius: 6px;
  background: #edf6f0;
}

.status-tag {
  font-size: 0.72rem;
  font-weight: 800;
  text-transform: uppercase;
  padding: 0.2rem 0.55rem;
  border-radius: 6px;
}

.status-tag.open {
  background: #e8f5e9;
  color: #2e7d32;
}

.status-tag.closed {
  background: #f5f5f5;
  color: #757575;
}

.empty-feed {
  padding: 2rem 1rem;
  text-align: center;
  color: #94a39b;
  font-size: 0.85rem;
}

@media (max-width: 992px) {
  .portal-content-grid,
  .activity-grid {
    grid-template-columns: 1fr;
  }
  .workspace-actions {
    grid-template-columns: 1fr;
  }
}
</style>

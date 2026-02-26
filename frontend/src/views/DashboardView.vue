<template>
  <!-- Page header band -->
  <div class="dash-header">
    <div class="container">
      <h2>Welcome, {{ auth.user?.name || 'Citizen' }} 👋</h2>
      <p>NDZ Portal — My Dashboard | Manage your applications and services</p>
    </div>
  </div>

  <div class="dashboard-page">
    <div class="container">
      <!-- Stat cards -->
      <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3" v-for="stat in stats" :key="stat.label">
          <div class="dash-card">
            <div class="dash-accent-bar"></div>
            <div style="font-size:2rem;margin-bottom:0.5rem;">{{ stat.icon }}</div>
            <div class="dash-stat-num">{{ stat.value }}</div>
            <div class="dash-stat-lbl">{{ stat.label }}</div>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <!-- Recent Activity -->
        <div class="col-lg-8">
          <div class="dash-card">
            <h5 style="font-weight:700;color:var(--primary);margin-bottom:1.25rem;border-bottom:2px solid var(--accent);padding-bottom:0.5rem;display:inline-block;">
              Recent Activity
            </h5>
            <div class="dash-activity-item" v-for="item in activity" :key="item.id">
              <div class="dash-activity-dot" style="margin-top:4px;"></div>
              <div>
                <div style="font-size:0.9rem;font-weight:600;color:var(--text-dark);">{{ item.title }}</div>
                <div style="font-size:0.78rem;color:var(--text-light);">{{ item.time }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Profile -->
        <div class="col-lg-4">
          <div class="dash-card">
            <h5 style="font-weight:700;color:var(--primary);margin-bottom:1.25rem;border-bottom:2px solid var(--accent);padding-bottom:0.5rem;display:inline-block;">
              My Profile
            </h5>
            <div style="text-align:center;margin-bottom:1.25rem;">
              <div style="width:72px;height:72px;border-radius:50%;background:var(--primary);color:#fff;font-size:1.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
                {{ initials }}
              </div>
              <div style="font-weight:700;color:var(--text-dark);">{{ auth.user?.name }}</div>
              <div style="font-size:0.8rem;color:var(--text-light);">{{ auth.user?.email }}</div>
            </div>
            <hr style="border-color:var(--light-border);" />
            <div style="font-size:0.85rem;display:flex;justify-content:space-between;">
              <span style="color:var(--text-light);">Account Status</span>
              <span style="color:#27ae60;font-weight:700;">✅ Active</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="row g-3 mt-2">
        <div class="col-12">
          <div class="dash-card">
            <h5 style="font-weight:700;color:var(--primary);margin-bottom:1.25rem;border-bottom:2px solid var(--accent);padding-bottom:0.5rem;display:inline-block;">
              Quick Access
            </h5>
            <div class="d-flex flex-wrap gap-3">
              <a href="#" class="btn-gov">📋 View Tenders</a>
              <a href="#" class="btn-gov">💰 Quotations</a>
              <a href="#" class="btn-gov">📢 Notices</a>
              <a href="#" class="btn-gov">💼 Careers</a>
              <a href="#" class="btn-gov-outline">📁 My Documents</a>
              <a href="#" class="btn-gov-outline">📞 Contact Support</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth'
export default {
  name: 'DashboardView',
  setup() { return { auth: useAuthStore() } },
  computed: {
    initials() {
      return (this.auth.user?.name || '')
        .split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) || 'U'
    },
  },
  data() {
    return {
      stats: [
        { icon: '📋', value: '12', label: 'My Applications' },
        { icon: '✅', value: '8',  label: 'Approved' },
        { icon: '⏳', value: '3',  label: 'Pending Review' },
        { icon: '🔔', value: '5',  label: 'Notifications' },
      ],
      activity: [
        { id: 1, title: 'Application #NDZ-2841 submitted successfully', time: '2 hours ago' },
        { id: 2, title: 'Application #NDZ-2835 approved by officer', time: 'Yesterday, 14:30' },
        { id: 3, title: 'New public notice published — Traffic Department', time: '3 days ago' },
        { id: 4, title: 'Account email verified successfully', time: '5 days ago' },
      ],
    }
  },
}
</script>

<template>
  <DashboardLayout
    title="Site Settings"
    subtitle="Configure municipal contact channels, emergency service numbers, social media links, and public announcement alerts."
  >
    <div v-if="notice" class="portal-alert success">{{ notice }}</div>
    <div v-if="error" class="portal-alert danger">{{ error }}</div>

    <div v-if="loading" class="portal-panel loading-state">
      Loading settings...
    </div>

    <form v-else class="settings-form" @submit.prevent="saveSettings">
      <!-- Section 1: Municipal Information -->
      <section class="portal-panel mb-4">
        <div class="panel-heading">
          <div>
            <p>General</p>
            <h2>Municipal Information & Contact</h2>
          </div>
        </div>

        <div class="form-grid-2">
          <label>
            Municipality Official Name
            <input v-model="settings.municipality_name" type="text" required />
          </label>

          <label>
            Motto / Tagline
            <input v-model="settings.tagline" type="text" />
          </label>
        </div>

        <div class="form-grid-2 mt-3">
          <label>
            Customer Care / Helpdesk Email
            <input v-model="settings.contact_email" type="email" required />
          </label>

          <label>
            Main Telephone Number
            <input v-model="settings.contact_phone" type="text" required />
          </label>
        </div>

        <div class="form-grid-2 mt-3">
          <label>
            Physical Municipal Address
            <input v-model="settings.physical_address" type="text" required />
          </label>

          <label>
            Operating Office Hours
            <input v-model="settings.office_hours" type="text" required />
          </label>
        </div>
      </section>

      <!-- Section 2: Public Announcement Banner -->
      <section class="portal-panel mb-4">
        <div class="panel-heading">
          <div>
            <p>Public Alert</p>
            <h2>Announcement Banner</h2>
          </div>
          <label class="toggle-switch">
            <input v-model="settings.announcement_banner.is_active" type="checkbox" />
            <span class="toggle-slider"></span>
            <span class="toggle-label">{{ settings.announcement_banner.is_active ? 'Banner Active' : 'Disabled' }}</span>
          </label>
        </div>

        <p class="section-subtext">
          When active, a high-visibility notice banner is displayed to all citizens visiting the website.
        </p>

        <div class="form-grid-2 mt-3">
          <label>
            Banner Message Text
            <input
              v-model="settings.announcement_banner.text"
              type="text"
              placeholder="e.g. Council Budget Consultation meetings underway in all 13 wards."
            />
          </label>

          <label>
            Action Link (Optional)
            <input
              v-model="settings.announcement_banner.link"
              type="text"
              placeholder="e.g. /news or https://..."
            />
          </label>
        </div>
      </section>

      <!-- Section 3: Emergency Hotlines -->
      <section class="portal-panel mb-4">
        <div class="panel-heading">
          <div>
            <p>Safety & Rescue</p>
            <h2>Emergency Hotlines</h2>
          </div>
          <button type="button" class="btn-sm-add" @click="addEmergencyNumber">
            + Add Hotline
          </button>
        </div>

        <div class="emergency-table-wrap">
          <table class="emergency-table">
            <thead>
              <tr>
                <th>Service Name / Label</th>
                <th>Display Number</th>
                <th>Tel Dial Link</th>
                <th style="width: 50px;"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, idx) in settings.emergency_numbers" :key="idx">
                <td>
                  <input v-model="item.label" type="text" placeholder="e.g. Police Services" required />
                </td>
                <td>
                  <input v-model="item.display" type="text" placeholder="e.g. 10111" required />
                </td>
                <td>
                  <input v-model="item.tel" type="text" placeholder="e.g. 10111 or +2733..." required />
                </td>
                <td>
                  <button type="button" class="btn-remove-row" @click="removeEmergencyNumber(idx)">&times;</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Section 4: Social Media Links -->
      <section class="portal-panel mb-4">
        <div class="panel-heading">
          <div>
            <p>Social Channels</p>
            <h2>Official Media Profiles</h2>
          </div>
        </div>

        <div class="form-grid-3">
          <label>
            Facebook Page URL
            <input v-model="settings.social_links.facebook" type="url" placeholder="https://facebook.com/..." />
          </label>

          <label>
            Twitter / X Profile URL
            <input v-model="settings.social_links.twitter" type="url" placeholder="https://x.com/..." />
          </label>

          <label>
            YouTube Channel URL
            <input v-model="settings.social_links.youtube" type="url" placeholder="https://youtube.com/..." />
          </label>
        </div>
      </section>

      <!-- Bottom Sticky Save Bar -->
      <div class="settings-actions-bar">
        <button type="submit" class="btn-save-settings" :disabled="saving">
          {{ saving ? 'Saving Changes...' : 'Save All Settings' }}
        </button>
      </div>
    </form>
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../components/DashboardLayout.vue'
import api from '../api/axios'

export default {
  name: 'DashboardSettingsView',
  components: { DashboardLayout },
  data() {
    return {
      loading: true,
      saving: false,
      notice: '',
      error: '',
      settings: {
        municipality_name: 'Dr Nkosazana Dlamini-Zuma Local Municipality',
        tagline: 'Serving Our Communities with Dedication',
        contact_email: 'helpdesk@ndz.gov.za',
        contact_phone: '+27 39 833 1038',
        physical_address: 'Main Street, Creighton, 3263, KwaZulu-Natal',
        office_hours: 'Monday - Friday: 07:30 AM to 04:00 PM',
        announcement_banner: {
          is_active: false,
          text: '',
          link: '',
        },
        emergency_numbers: [],
        social_links: {
          facebook: '',
          twitter: '',
          youtube: '',
        },
      },
    }
  },
  mounted() {
    this.fetchSettings()
  },
  methods: {
    async fetchSettings() {
      this.loading = true
      try {
        const { data } = await api.get('/api/admin/settings')
        if (data.data) {
          this.settings = {
            ...this.settings,
            ...data.data,
            announcement_banner: data.data.announcement_banner || this.settings.announcement_banner,
            emergency_numbers: data.data.emergency_numbers || [],
            social_links: data.data.social_links || this.settings.social_links,
          }
        }
      } catch (err) {
        this.error = 'Failed to load site settings.'
      } finally {
        this.loading = false
      }
    },
    addEmergencyNumber() {
      this.settings.emergency_numbers.push({
        label: '',
        display: '',
        tel: '',
      })
    },
    removeEmergencyNumber(idx) {
      this.settings.emergency_numbers.splice(idx, 1)
    },
    async saveSettings() {
      this.saving = true
      this.error = ''
      this.notice = ''

      try {
        await api.post('/api/admin/settings', {
          settings: this.settings,
        })
        this.notice = 'Settings updated and published successfully.'
        setTimeout(() => { this.notice = '' }, 4000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to save settings.'
      } finally {
        this.saving = false
      }
    },
  },
}
</script>

<style scoped>
.portal-panel {
  background: #ffffff;
  border: 1px solid #e4ece7;
  border-radius: 20px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(15, 107, 59, 0.04);
}

.panel-heading {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 1rem;
}

.panel-heading p {
  margin: 0 0 0.2rem;
  color: #0f6b3b;
  font-size: 0.75rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.panel-heading h2 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: 800;
  color: #17231c;
}

.section-subtext {
  margin: -0.5rem 0 1rem;
  font-size: 0.82rem;
  color: #6c7d73;
}

.form-grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-grid-3 {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
}

label {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
  font-size: 0.85rem;
  font-weight: 700;
  color: #17231c;
}

input {
  border: 1px solid #d9e6de;
  border-radius: 12px;
  padding: 0.65rem 0.85rem;
  font-size: 0.9rem;
  background: #fbfdfc;
  color: #17231c;
}

input:focus {
  outline: none;
  border-color: #0f6b3b;
}

/* Toggle Switch */
.toggle-switch {
  display: inline-flex;
  flex-direction: row;
  align-items: center;
  gap: 0.6rem;
  cursor: pointer;
  user-select: none;
}

.toggle-switch input {
  display: none;
}

.toggle-slider {
  width: 44px;
  height: 24px;
  background: #d9e6de;
  border-radius: 999px;
  position: relative;
  transition: background 0.2s ease;
}

.toggle-slider::before {
  content: '';
  position: absolute;
  top: 3px;
  left: 3px;
  width: 18px;
  height: 18px;
  background: #ffffff;
  border-radius: 50%;
  transition: transform 0.2s ease;
}

.toggle-switch input:checked + .toggle-slider {
  background: #0f6b3b;
}

.toggle-switch input:checked + .toggle-slider::before {
  transform: translateX(20px);
}

.toggle-label {
  font-size: 0.85rem;
  font-weight: 700;
  color: #17231c;
}

/* Emergency Table */
.emergency-table-wrap {
  overflow-x: auto;
}

.emergency-table {
  width: 100%;
  border-collapse: collapse;
}

.emergency-table th {
  text-align: left;
  padding: 0.6rem 0.75rem;
  font-size: 0.76rem;
  color: #6c7d73;
  text-transform: uppercase;
  border-bottom: 2px solid #edf3ef;
}

.emergency-table td {
  padding: 0.5rem 0.75rem;
  vertical-align: middle;
}

.emergency-table input {
  width: 100%;
}

.btn-sm-add {
  border: 1px solid #c2dbcd;
  background: #f0f7f3;
  color: #0f6b3b;
  padding: 0.35rem 0.85rem;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 700;
  cursor: pointer;
}

.btn-remove-row {
  background: transparent;
  border: 0;
  font-size: 1.4rem;
  color: #d9383a;
  cursor: pointer;
}

/* Save Bar */
.settings-actions-bar {
  display: flex;
  justify-content: flex-end;
  margin-top: 1.5rem;
  margin-bottom: 3rem;
}

.btn-save-settings {
  background: #0f6b3b;
  color: #ffffff;
  border: 0;
  border-radius: 14px;
  padding: 0.85rem 2rem;
  font-size: 0.95rem;
  font-weight: 800;
  cursor: pointer;
  box-shadow: 0 4px 18px rgba(15, 107, 59, 0.3);
  transition: all 0.2s ease;
}

.btn-save-settings:hover:not(:disabled) {
  background: #0c562f;
  transform: translateY(-1px);
}

.btn-save-settings:disabled {
  opacity: 0.6;
  cursor: not-allowed;
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

.loading-state {
  text-align: center;
  padding: 3rem;
  color: #6c7d73;
}

@media (max-width: 768px) {
  .form-grid-2,
  .form-grid-3 {
    grid-template-columns: 1fr;
  }
}
</style>

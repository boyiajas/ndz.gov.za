<template>
  <DashboardLayout
    title="Event Gallery Manager"
    subtitle="Upload, curate, and organize photos for municipal events, tourism highlights, and public community albums."
  >
    <template #header-actions>
      <button type="button" class="btn-primary-action" @click="openAddModal">
        <span>+</span> Upload Photo
      </button>
    </template>

    <div v-if="notice" class="portal-alert success">{{ notice }}</div>
    <div v-if="error" class="portal-alert danger">{{ error }}</div>

    <!-- Category Filter Tabs -->
    <section class="portal-panel filter-panel mb-4">
      <div class="filter-controls">
        <div class="category-tabs">
          <button
            v-for="cat in categories"
            :key="cat"
            type="button"
            class="cat-tab"
            :class="{ active: selectedCategory === cat }"
            @click="selectCategory(cat)"
          >
            {{ cat }}
          </button>
        </div>
        <span class="count-pill">{{ filteredItems.length }} photo<span v-if="filteredItems.length !== 1">s</span></span>
      </div>
    </section>

    <!-- Gallery Grid -->
    <section class="portal-panel">
      <div v-if="loading" class="loading-state">Loading gallery items...</div>
      <div v-else-if="filteredItems.length === 0" class="empty-state">
        <p>No gallery photos found in this category.</p>
        <button type="button" class="btn-primary-action" @click="openAddModal">
          Add First Photo
        </button>
      </div>

      <div v-else class="gallery-cards-grid">
        <article v-for="item in filteredItems" :key="item.id" class="gallery-admin-card">
          <div class="gallery-card-media">
            <img :src="item.image_url" :alt="item.title" @error="handleImageFallback" />
            <span class="cat-badge-overlay">{{ item.category }}</span>
            <span :class="['active-dot', item.is_active ? 'active' : 'inactive']"></span>
          </div>

          <div class="gallery-card-body">
            <h4 class="gallery-card-title">{{ item.title }}</h4>
            <p v-if="item.description" class="gallery-card-desc">{{ item.description }}</p>

            <div class="gallery-card-footer">
              <small class="sort-meta">Order: #{{ item.sort_order }}</small>
              <div class="card-action-btns">
                <button type="button" class="btn-sm-edit" @click="openEditModal(item)">Edit</button>
                <button type="button" class="btn-sm-delete" @click="deleteItem(item)">Delete</button>
              </div>
            </div>
          </div>
        </article>
      </div>
    </section>

    <!-- Upload / Edit Modal -->
    <div v-if="isModalOpen" class="modal-backdrop" @click.self="closeModal">
      <div class="modal-sheet">
        <div class="modal-sheet-header">
          <div>
            <p>{{ editingItemId ? 'Update Photo' : 'New Photo' }}</p>
            <h3>{{ editingItemId ? 'Edit Gallery Photo' : 'Upload Gallery Photo' }}</h3>
          </div>
          <button type="button" class="close-btn" @click="closeModal">&times;</button>
        </div>

        <form class="modal-sheet-form" @submit.prevent="saveItem">
          <div class="form-grid-2">
            <label>
              Photo Title / Caption *
              <input v-model="form.title" type="text" placeholder="e.g. Mayoral Marathon Finish Line" required />
            </label>

            <label>
              Category *
              <select v-model="form.category" required>
                <option value="Events">Events</option>
                <option value="Tourism">Tourism</option>
                <option value="Community">Community</option>
                <option value="Council">Council</option>
                <option value="Infrastructure">Infrastructure</option>
              </select>
            </label>
          </div>

          <!-- Image URL & File Upload -->
          <div class="image-upload-section">
            <label>Image File / URL *</label>
            <div class="image-input-row">
              <input
                v-model="form.image_url"
                type="text"
                placeholder="Paste URL or click Upload File..."
                required
              />
              <FileUploadButton
                folder="gallery"
                accept="image/*"
                label="Upload Image"
                icon="🖼"
                @uploaded="onImageUploaded"
              />
            </div>
            <div v-if="form.image_url" class="photo-preview">
              <img :src="form.image_url" alt="Preview" @error="handleImageFallback" />
            </div>
          </div>

          <label>
            Description / Context
            <textarea
              v-model="form.description"
              rows="2"
              placeholder="Brief description or location details..."
            ></textarea>
          </label>

          <div class="form-grid-2">
            <label>
              Sort Display Order
              <input v-model.number="form.sort_order" type="number" min="0" />
            </label>

            <label class="check-label mt-4">
              <input v-model="form.is_active" type="checkbox" />
              Active on Public Website
            </label>
          </div>

          <div class="modal-footer-actions">
            <button type="button" class="btn-cancel" @click="closeModal">Cancel</button>
            <button type="submit" class="btn-save" :disabled="saving">
              {{ saving ? 'Saving Photo...' : (editingItemId ? 'Update Photo' : 'Save to Gallery') }}
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
  name: 'DashboardGalleryView',
  components: { DashboardLayout, FileUploadButton },
  data() {
    return {
      items: [],
      filteredItems: [],
      categories: ['All', 'Events', 'Tourism', 'Community', 'Council', 'Infrastructure'],
      selectedCategory: 'All',
      loading: true,
      saving: false,
      notice: '',
      error: '',
      isModalOpen: false,
      editingItemId: null,
      form: {
        title: '',
        category: 'Events',
        image_url: '',
        description: '',
        sort_order: 0,
        is_active: true,
      },
    }
  },
  mounted() {
    this.fetchItems()
  },
  methods: {
    async fetchItems() {
      this.loading = true
      try {
        const { data } = await api.get('/api/admin/gallery')
        this.items = data.data || []
        this.filterItems()
      } catch (err) {
        this.error = 'Failed to load gallery photos.'
      } finally {
        this.loading = false
      }
    },
    selectCategory(cat) {
      this.selectedCategory = cat
      this.filterItems()
    },
    filterItems() {
      if (this.selectedCategory === 'All') {
        this.filteredItems = [...this.items]
      } else {
        this.filteredItems = this.items.filter(
          (i) => i.category?.toLowerCase() === this.selectedCategory.toLowerCase()
        )
      }
    },
    openAddModal() {
      this.editingItemId = null
      this.form = {
        title: '',
        category: 'Events',
        image_url: '',
        description: '',
        sort_order: this.items.length + 1,
        is_active: true,
      }
      this.isModalOpen = true
    },
    openEditModal(item) {
      this.editingItemId = item.id
      this.form = {
        title: item.title,
        category: item.category,
        image_url: item.image_url,
        description: item.description || '',
        sort_order: item.sort_order || 0,
        is_active: !!item.is_active,
      }
      this.isModalOpen = true
    },
    closeModal() {
      this.isModalOpen = false
      this.editingItemId = null
    },
    onImageUploaded(url) {
      this.form.image_url = url
      this.notice = 'Photo uploaded successfully.'
      setTimeout(() => { this.notice = '' }, 3000)
    },
    async saveItem() {
      this.saving = true
      this.error = ''
      this.notice = ''

      try {
        if (this.editingItemId) {
          await api.put(`/api/admin/gallery/${this.editingItemId}`, this.form)
          this.notice = 'Gallery photo updated successfully.'
        } else {
          await api.post('/api/admin/gallery', this.form)
          this.notice = 'Photo added to gallery successfully.'
        }
        this.closeModal()
        await this.fetchItems()
        setTimeout(() => { this.notice = '' }, 4000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to save gallery photo.'
      } finally {
        this.saving = false
      }
    },
    async deleteItem(item) {
      if (!confirm(`Are you sure you want to delete "${item.title}" from gallery?`)) {
        return
      }
      try {
        await api.delete(`/api/admin/gallery/${item.id}`)
        this.notice = 'Gallery item deleted.'
        await this.fetchItems()
        setTimeout(() => { this.notice = '' }, 3000)
      } catch (err) {
        this.error = 'Failed to delete photo.'
      }
    },
    handleImageFallback(e) {
      e.target.src = 'https://ui-avatars.com/api/?name=Gallery&background=f0f7f3&color=0f6b3b'
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

.category-tabs {
  display: flex;
  gap: 0.4rem;
  flex-wrap: wrap;
}

.cat-tab {
  border: 1px solid #e0eae4;
  background: #f4f8f5;
  color: #4a5c52;
  border-radius: 999px;
  padding: 0.4rem 0.9rem;
  font-size: 0.8rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
}

.cat-tab:hover,
.cat-tab.active {
  background: #0f6b3b;
  color: #ffffff;
  border-color: #0f6b3b;
}

.count-pill {
  background: #edf6f0;
  color: #0f6b3b;
  font-size: 0.8rem;
  font-weight: 700;
  padding: 0.35rem 0.8rem;
  border-radius: 999px;
}

.portal-panel {
  background: #ffffff;
  border: 1px solid #e4ece7;
  border-radius: 20px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(15, 107, 59, 0.04);
}

.gallery-cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 1.25rem;
}

.gallery-admin-card {
  border: 1px solid #e4ece7;
  border-radius: 16px;
  overflow: hidden;
  background: #fafcfb;
  display: flex;
  flex-direction: column;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.gallery-admin-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(15, 107, 59, 0.08);
}

.gallery-card-media {
  position: relative;
  width: 100%;
  height: 160px;
  background: #eef3f0;
}

.gallery-card-media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.cat-badge-overlay {
  position: absolute;
  top: 10px;
  left: 10px;
  background: rgba(15, 107, 59, 0.85);
  backdrop-filter: blur(4px);
  color: #ffffff;
  font-size: 0.72rem;
  font-weight: 800;
  padding: 0.2rem 0.6rem;
  border-radius: 6px;
}

.active-dot {
  position: absolute;
  top: 12px;
  right: 12px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  border: 2px solid #ffffff;
}

.active-dot.active { background: #00c853; }
.active-dot.inactive { background: #d50000; }

.gallery-card-body {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.gallery-card-title {
  margin: 0;
  font-size: 0.92rem;
  font-weight: 800;
  color: #17231c;
  line-height: 1.3;
}

.gallery-card-desc {
  margin: 0.35rem 0 0;
  font-size: 0.78rem;
  color: #6c7d73;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  flex: 1;
}

.gallery-card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 1rem;
  padding-top: 0.75rem;
  border-top: 1px solid #edf3ef;
}

.sort-meta {
  color: #8fa096;
  font-size: 0.75rem;
  font-weight: 600;
}

.card-action-btns {
  display: flex;
  gap: 0.4rem;
}

.btn-sm-edit {
  border: 1px solid #c2dbcd;
  background: #f0f7f3;
  color: #0f6b3b;
  padding: 0.25rem 0.6rem;
  border-radius: 6px;
  font-size: 0.76rem;
  font-weight: 700;
  cursor: pointer;
}

.btn-sm-delete {
  border: 1px solid #fcd2d2;
  background: #fff3f3;
  color: #d9383a;
  padding: 0.25rem 0.6rem;
  border-radius: 6px;
  font-size: 0.76rem;
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
  max-width: 650px;
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

.image-input-row {
  display: flex;
  gap: 0.5rem;
}

.image-input-row input {
  flex: 1;
}

.photo-preview {
  margin-top: 0.6rem;
  width: 100%;
  height: 180px;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #d9e6de;
}

.photo-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.check-label {
  display: flex !important;
  flex-direction: row !important;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
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

<template>
  <DashboardLayout
    title="News & Blogs Manager"
    subtitle="Draft, publish, and manage municipal news articles, press notices, and featured homepage stories."
  >
    <template #header-actions>
      <button type="button" class="btn-primary-action" @click="openCreateModal">
        <span>+</span> New Article
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
            placeholder="Search articles by title or keyword..."
            @input="filterArticles"
          />
        </div>

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
      </div>
    </section>

    <!-- Articles Table / Cards -->
    <section class="portal-panel">
      <div v-if="loading" class="loading-state">Loading articles...</div>
      <div v-else-if="filteredArticles.length === 0" class="empty-state">
        <p>No articles found matching your criteria.</p>
        <button type="button" class="btn-primary-action" @click="openCreateModal">
          Create First Article
        </button>
      </div>

      <div v-else class="articles-table-wrap">
        <table class="manager-table">
          <thead>
            <tr>
              <th style="width: 80px;">Cover</th>
              <th>Article Title & Summary</th>
              <th>Category</th>
              <th>Date & Read Time</th>
              <th>Home Featured</th>
              <th>Status</th>
              <th style="text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="article in filteredArticles" :key="article.id">
              <td>
                <img
                  :src="article.image_url || '/placeholder-news.jpg'"
                  class="table-thumb"
                  alt="Cover"
                  @error="handleImageFallback"
                />
              </td>
              <td>
                <strong class="article-title">{{ article.title }}</strong>
                <p class="article-excerpt">{{ article.excerpt }}</p>
              </td>
              <td>
                <span class="category-badge">{{ article.category }}</span>
              </td>
              <td>
                <span class="meta-date">{{ article.published_at || 'Unpublished' }}</span>
                <small class="meta-time">{{ article.read_time }}</small>
              </td>
              <td>
                <span v-if="article.is_featured" class="badge-featured">⭐ Home Featured</span>
                <span v-else class="badge-subtle">Standard</span>
              </td>
              <td>
                <span :class="['status-badge', article.is_published ? 'published' : 'draft']">
                  {{ article.is_published ? 'Published' : 'Draft' }}
                </span>
              </td>
              <td style="text-align: right;">
                <div class="action-buttons">
                  <button type="button" class="btn-edit" @click="openEditModal(article)">Edit</button>
                  <button type="button" class="btn-delete" @click="deleteArticle(article)">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Create / Edit Article Modal -->
    <div v-if="isModalOpen" class="modal-backdrop" @click.self="closeModal">
      <div class="modal-sheet">
        <div class="modal-sheet-header">
          <div>
            <p>{{ editingArticleId ? 'Update Publication' : 'New Publication' }}</p>
            <h3>{{ editingArticleId ? 'Edit Article & Blog' : 'Create News / Blog Article' }}</h3>
          </div>
          <button type="button" class="close-btn" @click="closeModal">&times;</button>
        </div>

        <form class="modal-sheet-form" @submit.prevent="saveArticle">
          <div class="form-grid-2">
            <label>
              Article Title *
              <input v-model="form.title" type="text" placeholder="e.g. Mayor Announces New Ward Road Project" required />
            </label>

            <label>
              Category *
              <select v-model="form.category" required>
                <option value="News">News</option>
                <option value="Blog">Blog</option>
                <option value="Notice">Notice</option>
                <option value="Press Release">Press Release</option>
                <option value="Budget">Budget</option>
                <option value="Events">Events</option>
              </select>
            </label>
          </div>

          <label>
            Brief Excerpt / Summary *
            <textarea
              v-model="form.excerpt"
              rows="2"
              placeholder="Short 1-2 sentence overview shown on the homepage card and list feeds..."
              required
            ></textarea>
          </label>

          <!-- Cover Image with FileUploadButton -->
          <div class="image-upload-section">
            <label>Cover Image URL / Upload</label>
            <div class="image-input-row">
              <input
                v-model="form.image_url"
                type="text"
                placeholder="Enter image URL or click Upload File..."
              />
              <FileUploadButton
                folder="news"
                accept="image/*"
                label="Upload Cover"
                icon="🖼"
                @uploaded="onCoverUploaded"
              />
            </div>
            <div v-if="form.image_url" class="cover-preview">
              <img :src="form.image_url" alt="Cover Preview" @error="handleImageFallback" />
              <button type="button" class="btn-remove-img" @click="form.image_url = ''">Remove Image</button>
            </div>
          </div>

          <!-- Full Content with RichTextEditor -->
          <div class="editor-field">
            <span>Article Body / Full Story</span>
            <RichTextEditor
              v-model="form.content"
              placeholder="Write the full news article or blog post. Format headings, paragraphs, bullet points, and quotes..."
            />
          </div>

          <div class="form-grid-3">
            <label>
              Read Time
              <input v-model="form.read_time" type="text" placeholder="e.g. 3 min" />
            </label>

            <label>
              Publish Date
              <input v-model="form.published_at" type="date" />
            </label>

            <div class="checkbox-group">
              <label class="check-label">
                <input v-model="form.is_featured" type="checkbox" />
                Featured on Homepage
              </label>

              <label class="check-label">
                <input v-model="form.is_published" type="checkbox" />
                Publish Immediately
              </label>
            </div>
          </div>

          <div class="modal-footer-actions">
            <button type="button" class="btn-cancel" @click="closeModal">Cancel</button>
            <button type="submit" class="btn-save" :disabled="saving">
              {{ saving ? 'Saving Article...' : (editingArticleId ? 'Update Article' : 'Publish Article') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../components/DashboardLayout.vue'
import RichTextEditor from '../components/RichTextEditor.vue'
import FileUploadButton from '../components/FileUploadButton.vue'
import api from '../api/axios'

export default {
  name: 'DashboardNewsView',
  components: {
    DashboardLayout,
    RichTextEditor,
    FileUploadButton,
  },
  data() {
    return {
      articles: [],
      filteredArticles: [],
      categories: ['All', 'News', 'Blog', 'Notice', 'Press Release', 'Budget'],
      selectedCategory: 'All',
      searchQuery: '',
      loading: true,
      saving: false,
      notice: '',
      error: '',
      isModalOpen: false,
      editingArticleId: null,
      form: {
        title: '',
        category: 'News',
        excerpt: '',
        content: '',
        image_url: '',
        read_time: '2 min',
        published_at: new Date().toISOString().split('T')[0],
        is_featured: false,
        is_published: true,
      },
    }
  },
  mounted() {
    this.fetchArticles()
  },
  methods: {
    async fetchArticles() {
      this.loading = true
      try {
        const { data } = await api.get('/api/admin/news')
        this.articles = data.data || []
        this.filterArticles()
      } catch (err) {
        this.error = 'Failed to load news articles.'
      } finally {
        this.loading = false
      }
    },
    selectCategory(cat) {
      this.selectedCategory = cat
      this.filterArticles()
    },
    filterArticles() {
      let list = [...this.articles]
      if (this.selectedCategory !== 'All') {
        list = list.filter((a) => a.category?.toLowerCase() === this.selectedCategory.toLowerCase())
      }
      if (this.searchQuery.trim()) {
        const q = this.searchQuery.toLowerCase()
        list = list.filter(
          (a) =>
            a.title?.toLowerCase().includes(q) ||
            a.excerpt?.toLowerCase().includes(q)
        )
      }
      this.filteredArticles = list
    },
    openCreateModal() {
      this.editingArticleId = null
      this.form = {
        title: '',
        category: 'News',
        excerpt: '',
        content: '',
        image_url: '',
        read_time: '2 min',
        published_at: new Date().toISOString().split('T')[0],
        is_featured: false,
        is_published: true,
      }
      this.isModalOpen = true
    },
    openEditModal(article) {
      this.editingArticleId = article.id
      this.form = {
        title: article.title,
        category: article.category,
        excerpt: article.excerpt || '',
        content: article.content || '',
        image_url: article.image_url || '',
        read_time: article.read_time || '2 min',
        published_at: article.published_at ? article.published_at.split('T')[0] : '',
        is_featured: !!article.is_featured,
        is_published: !!article.is_published,
      }
      this.isModalOpen = true
    },
    closeModal() {
      this.isModalOpen = false
      this.editingArticleId = null
    },
    onCoverUploaded(url) {
      this.form.image_url = url
      this.notice = 'Cover image uploaded successfully.'
      setTimeout(() => { this.notice = '' }, 3000)
    },
    async saveArticle() {
      this.saving = true
      this.error = ''
      this.notice = ''

      try {
        if (this.editingArticleId) {
          await api.put(`/api/admin/news/${this.editingArticleId}`, this.form)
          this.notice = 'Article updated successfully.'
        } else {
          await api.post('/api/admin/news', this.form)
          this.notice = 'Article created and published successfully.'
        }
        this.closeModal()
        await this.fetchArticles()
        setTimeout(() => { this.notice = '' }, 4000)
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to save article.'
      } finally {
        this.saving = false
      }
    },
    async deleteArticle(article) {
      if (!confirm(`Are you sure you want to delete "${article.title}"?`)) {
        return
      }
      try {
        await api.delete(`/api/admin/news/${article.id}`)
        this.notice = 'Article deleted.'
        await this.fetchArticles()
        setTimeout(() => { this.notice = '' }, 3000)
      } catch (err) {
        this.error = 'Failed to delete article.'
      }
    },
    handleImageFallback(e) {
      e.target.src = 'https://ui-avatars.com/api/?name=News&background=f0f7f3&color=0f6b3b'
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

.portal-panel {
  background: #ffffff;
  border: 1px solid #e4ece7;
  border-radius: 20px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(15, 107, 59, 0.04);
}

.articles-table-wrap {
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

.table-thumb {
  width: 64px;
  height: 48px;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #e0eae4;
}

.article-title {
  display: block;
  font-size: 0.95rem;
  color: #17231c;
  line-height: 1.3;
}

.article-excerpt {
  margin: 0.25rem 0 0;
  font-size: 0.8rem;
  color: #6c7d73;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.category-badge {
  background: #eef7f1;
  color: #0f6b3b;
  padding: 0.25rem 0.65rem;
  border-radius: 6px;
  font-size: 0.76rem;
  font-weight: 700;
}

.meta-date {
  display: block;
  font-size: 0.82rem;
  font-weight: 700;
  color: #17231c;
}

.meta-time {
  display: block;
  font-size: 0.74rem;
  color: #6c7d73;
}

.badge-featured {
  background: #fff8e1;
  color: #f57f17;
  border: 1px solid #ffe082;
  border-radius: 999px;
  padding: 0.2rem 0.65rem;
  font-size: 0.72rem;
  font-weight: 800;
}

.badge-subtle {
  color: #94a39b;
  font-size: 0.78rem;
}

.status-badge {
  display: inline-block;
  padding: 0.25rem 0.65rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 800;
}

.status-badge.published {
  background: #e8f5e9;
  color: #2e7d32;
}

.status-badge.draft {
  background: #f5f5f5;
  color: #757575;
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
  justify-content: flex-end;
}

.btn-edit {
  border: 1px solid #c2dbcd;
  background: #f0f7f3;
  color: #0f6b3b;
  padding: 0.35rem 0.75rem;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 700;
  cursor: pointer;
}

.btn-delete {
  border: 1px solid #fcd2d2;
  background: #fff3f3;
  color: #d9383a;
  padding: 0.35rem 0.75rem;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 700;
  cursor: pointer;
}

.loading-state,
.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #6c7d73;
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

/* Modal Sheet */
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
  max-width: 780px;
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
  grid-template-columns: 2fr 1fr;
  gap: 1rem;
}

.form-grid-3 {
  display: grid;
  grid-template-columns: 1fr 1fr 1.4fr;
  gap: 1rem;
  align-items: flex-end;
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

.cover-preview {
  margin-top: 0.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.cover-preview img {
  width: 120px;
  height: 70px;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #d9e6de;
}

.btn-remove-img {
  background: transparent;
  border: 1px solid #fcd2d2;
  color: #d9383a;
  padding: 0.35rem 0.75rem;
  border-radius: 8px;
  font-size: 0.78rem;
  font-weight: 700;
  cursor: pointer;
}

.editor-field {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}

.editor-field span {
  font-size: 0.85rem;
  font-weight: 700;
  color: #17231c;
}

.checkbox-group {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
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

@media (max-width: 768px) {
  .form-grid-2,
  .form-grid-3 {
    grid-template-columns: 1fr;
  }
}
</style>

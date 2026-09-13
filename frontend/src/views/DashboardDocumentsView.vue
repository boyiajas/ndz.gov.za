<template>
  <DashboardLayout
    title="Document Manager"
    subtitle="Create document names, add sub names, and maintain public document listings."
  >
    <template #header-actions>
      <router-link class="public-doc-link" to="/documents">View Public Documents</router-link>
    </template>

    <div v-if="!auth.canManageDocuments" class="portal-alert danger">
      Your current role can view the portal, but cannot manage municipal documents.
    </div>

    <div v-else class="document-manager">
      <div v-if="notice" class="portal-alert success">{{ notice }}</div>
      <div v-if="error" class="portal-alert danger">{{ error }}</div>

      <section class="manager-grid">
        <article class="manager-panel">
          <div class="manager-heading">
            <div>
              <p>Step 1</p>
              <h2>Document names</h2>
            </div>
            <button v-if="editingCategoryId" type="button" class="text-button" @click="resetCategoryForm">Cancel edit</button>
          </div>

          <form class="manager-form" @submit.prevent="saveCategory">
            <label>
              Document name title
              <input v-model="categoryForm.name" type="text" placeholder="e.g. Policies" required />
            </label>
            <label>
              Subtitle
              <input v-model="categoryForm.subtitle" type="text" placeholder="e.g. Approved policies and municipal by-laws" />
            </label>
            <label>
              Picture URL
              <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input v-model="categoryForm.image_url" type="text" placeholder="/img/documents/policies.jpg" style="flex: 1;" />
                <FileUploadButton
                  folder="documents"
                  accept="image/*"
                  label="Upload"
                  icon="🖼"
                  :compact="true"
                  @uploaded="(url) => categoryForm.image_url = url"
                />
              </div>
            </label>
            <div class="editor-field">
              <span>Description</span>
              <RichTextEditor
                v-model="categoryForm.description"
                placeholder="Format the public description. Use headings, bold text, and bullet lists."
              />
            </div>
            <div class="form-row">
              <label>
                Order
                <input v-model.number="categoryForm.sort_order" type="number" min="0" />
              </label>
              <label class="check-row">
                <input v-model="categoryForm.is_active" type="checkbox" />
                Active
              </label>
            </div>
            <button type="submit" class="manager-button primary" :disabled="saving">
              {{ editingCategoryId ? 'Update document name' : 'Create document name' }}
            </button>
          </form>

          <div class="manager-list">
            <button
              v-for="category in catalog"
              :key="category.id"
              type="button"
              class="list-item"
              :class="{ active: selectedCategoryId === category.id }"
              @click="selectCategory(category.id)"
            >
              <span>
                <strong>{{ category.name }}</strong>
                <small>{{ category.subtitle || `${category.subcategories_count || category.subcategories?.length || 0} sub names` }}</small>
              </span>
              <span class="item-actions">
                <router-link
                  :to="{ name: 'document-category', params: { categorySlug: category.slug } }"
                  @click.stop
                >
                  Page
                </router-link>
                <i @click.stop="editCategory(category)">Edit</i>
                <i class="danger" @click.stop="deleteCategory(category)">Delete</i>
              </span>
            </button>
          </div>
        </article>

        <article class="manager-panel">
          <div class="manager-heading">
            <div>
              <p>Step 2</p>
              <h2>Document sub names</h2>
            </div>
            <button v-if="editingSubcategoryId" type="button" class="text-button" @click="resetSubcategoryForm">Cancel edit</button>
          </div>

          <form class="manager-form" @submit.prevent="saveSubcategory">
            <label>
              Parent document name
              <select v-model.number="subcategoryForm.document_category_id" required @change="selectCategory(subcategoryForm.document_category_id)">
                <option disabled :value="null">Choose document name</option>
                <option v-for="category in catalog" :key="category.id" :value="category.id">{{ category.name }}</option>
              </select>
            </label>
            <label>
              Sub name title
              <input v-model="subcategoryForm.name" type="text" placeholder="e.g. Financial Policies" required />
            </label>
            <div class="editor-field">
              <span>Description</span>
              <RichTextEditor
                v-model="subcategoryForm.description"
                placeholder="Format the description shown on the sub-name listing page."
              />
            </div>
            <div class="form-row">
              <label>
                Order
                <input v-model.number="subcategoryForm.sort_order" type="number" min="0" />
              </label>
              <label class="check-row">
                <input v-model="subcategoryForm.is_active" type="checkbox" />
                Active
              </label>
            </div>
            <button type="submit" class="manager-button primary" :disabled="saving || !subcategoryForm.document_category_id">
              {{ editingSubcategoryId ? 'Update sub name' : 'Create sub name' }}
            </button>
          </form>

          <div class="manager-list">
            <button
              v-for="subcategory in selectedSubcategories"
              :key="subcategory.id"
              type="button"
              class="list-item"
              :class="{ active: selectedSubcategoryId === subcategory.id }"
              @click="selectSubcategory(subcategory.id)"
            >
              <span>
                <strong>{{ subcategory.name }}</strong>
                <small>{{ subcategory.documents_count || 0 }} documents</small>
              </span>
              <span class="item-actions">
                <router-link
                  :to="{ name: 'document-listing', params: { categorySlug: selectedCategory?.slug, subcategorySlug: subcategory.slug } }"
                  @click.stop
                >
                  View
                </router-link>
                <i @click.stop="editSubcategory(subcategory)">Edit</i>
                <i class="danger" @click.stop="deleteSubcategory(subcategory)">Delete</i>
              </span>
            </button>
            <p v-if="selectedCategory && !selectedSubcategories.length" class="empty-note">No sub names yet for this document name.</p>
          </div>
        </article>
      </section>

      <section class="manager-panel mt-4">
        <div class="manager-heading wide">
          <div>
            <p>Step 3</p>
            <h2>Document listings for {{ selectedSubcategory?.name || 'selected sub name' }}</h2>
          </div>
          <button v-if="editingDocumentId" type="button" class="text-button" @click="resetDocumentForm">Cancel edit</button>
        </div>

        <form class="document-form" @submit.prevent="saveDocument">
          <label>
            Sub name
            <select v-model.number="documentForm.document_subcategory_id" required @change="selectSubcategory(documentForm.document_subcategory_id)">
              <option disabled :value="null">Choose sub name</option>
              <option v-for="subcategory in selectedSubcategories" :key="subcategory.id" :value="subcategory.id">{{ subcategory.name }}</option>
            </select>
          </label>
          <label>
            Document title
            <input v-model="documentForm.title" type="text" placeholder="e.g. 2024/2025 Approved Budget" required />
          </label>
          <label>
            File URL
            <div style="display: flex; gap: 0.5rem; align-items: center;">
              <input v-model="documentForm.file_url" type="text" placeholder="/documents/approved-budget.pdf" style="flex: 1;" />
              <FileUploadButton
                folder="documents"
                accept=".pdf,.doc,.docx,.xls,.xlsx"
                label="Upload File"
                icon="📄"
                :compact="true"
                @uploaded="(url) => documentForm.file_url = url"
              />
            </div>
          </label>
          <label>
            Status
            <select v-model="documentForm.status" required>
              <option value="draft">Draft</option>
              <option value="published">Published</option>
              <option value="archived">Archived</option>
            </select>
          </label>
          <label>
            Published date
            <input v-model="documentForm.published_at" type="date" />
          </label>
          <label>
            Order
            <input v-model.number="documentForm.sort_order" type="number" min="0" />
          </label>
          <div class="editor-field full">
            <span>Description</span>
            <RichTextEditor
              v-model="documentForm.description"
              placeholder="Format the document summary shown on the public listing page."
            />
          </div>
          <button type="submit" class="manager-button primary" :disabled="saving || !documentForm.document_subcategory_id">
            {{ editingDocumentId ? 'Update document' : 'Add document' }}
          </button>
        </form>

        <div class="documents-table-wrap">
          <table class="documents-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Published</th>
                <th>Downloads</th>
                <th>File</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="document in documents" :key="document.id">
                <td>
                  <strong>{{ document.title }}</strong>
                  <small>{{ textSummary(document.description) }}</small>
                </td>
                <td><span class="status-pill" :class="document.status">{{ document.status }}</span></td>
                <td>{{ formatDate(document.published_at) }}</td>
                <td>{{ document.download_count || 0 }}</td>
                <td>
                  <a v-if="document.file_url" :href="downloadUrl(document)" target="_blank" rel="noopener">Open file</a>
                  <span v-else>Pending file</span>
                </td>
                <td class="table-actions">
                  <button type="button" @click="editDocument(document)">Edit</button>
                  <button type="button" class="danger" @click="deleteDocument(document)">Delete</button>
                </td>
              </tr>
              <tr v-if="!documents.length">
                <td colspan="6" class="empty-table">No documents have been added to this sub name yet.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '../components/DashboardLayout.vue'
import RichTextEditor from '../components/RichTextEditor.vue'
import FileUploadButton from '../components/FileUploadButton.vue'
import api from '../api/axios'
import { useAuthStore } from '../stores/auth'
import { stripRichText } from '../utils/richText'

export default {
  name: 'DashboardDocumentsView',
  components: { DashboardLayout, RichTextEditor, FileUploadButton },
  setup() {
    return { auth: useAuthStore() }
  },
  data() {
    return {
      catalog: [],
      documents: [],
      selectedCategoryId: null,
      selectedSubcategoryId: null,
      editingCategoryId: null,
      editingSubcategoryId: null,
      editingDocumentId: null,
      saving: false,
      error: '',
      notice: '',
      categoryForm: { name: '', subtitle: '', description: '', image_url: '', sort_order: 0, is_active: true },
      subcategoryForm: { document_category_id: null, name: '', description: '', sort_order: 0, is_active: true },
      documentForm: {
        document_subcategory_id: null,
        title: '',
        description: '',
        file_url: '',
        status: 'published',
        published_at: new Date().toISOString().slice(0, 10),
        sort_order: 0,
      },
    }
  },
  computed: {
    selectedCategory() {
      return this.catalog.find((category) => category.id === this.selectedCategoryId) || null
    },
    selectedSubcategories() {
      return this.selectedCategory?.subcategories || []
    },
    selectedSubcategory() {
      return this.selectedSubcategories.find((subcategory) => subcategory.id === this.selectedSubcategoryId) || null
    },
  },
  mounted() {
    if (this.auth.canManageDocuments) {
      this.loadCatalog()
    }
  },
  methods: {
    defaultCategoryForm() {
      return { name: '', subtitle: '', description: '', image_url: '', sort_order: 0, is_active: true }
    },
    defaultSubcategoryForm() {
      return { document_category_id: null, name: '', description: '', sort_order: 0, is_active: true }
    },
    defaultDocumentForm() {
      return {
        document_subcategory_id: null,
        title: '',
        description: '',
        file_url: '',
        status: 'published',
        published_at: new Date().toISOString().slice(0, 10),
        sort_order: 0,
      }
    },
    async loadCatalog() {
      this.error = ''
      try {
        const { data } = await api.get('/api/admin/document-categories')
        this.catalog = data.data || []
        this.syncSelection()
        await this.loadDocuments()
      } catch (error) {
        this.error = this.formatError(error)
      }
    },
    syncSelection() {
      if (!this.catalog.length) {
        this.selectedCategoryId = null
        this.selectedSubcategoryId = null
        this.subcategoryForm.document_category_id = null
        this.documentForm.document_subcategory_id = null
        return
      }

      if (!this.catalog.some((category) => category.id === this.selectedCategoryId)) {
        this.selectedCategoryId = this.catalog[0].id
      }

      this.subcategoryForm.document_category_id = this.selectedCategoryId
      const subcategories = this.selectedSubcategories

      if (!subcategories.some((subcategory) => subcategory.id === this.selectedSubcategoryId)) {
        this.selectedSubcategoryId = subcategories[0]?.id || null
      }

      this.documentForm.document_subcategory_id = this.selectedSubcategoryId
    },
    async selectCategory(categoryId) {
      this.selectedCategoryId = categoryId
      this.selectedSubcategoryId = this.selectedSubcategories[0]?.id || null
      this.subcategoryForm.document_category_id = categoryId
      this.documentForm.document_subcategory_id = this.selectedSubcategoryId
      await this.loadDocuments()
    },
    async selectSubcategory(subcategoryId) {
      this.selectedSubcategoryId = subcategoryId
      this.documentForm.document_subcategory_id = subcategoryId
      await this.loadDocuments()
    },
    async loadDocuments() {
      if (!this.selectedSubcategoryId) {
        this.documents = []
        return
      }

      try {
        const { data } = await api.get('/api/admin/documents', {
          params: { document_subcategory_id: this.selectedSubcategoryId },
        })
        this.documents = data.data || []
      } catch (error) {
        this.error = this.formatError(error)
      }
    },
    async saveCategory() {
      await this.submit(async () => {
        const payload = { ...this.categoryForm }
        const response = this.editingCategoryId
          ? await api.put(`/api/admin/document-categories/${this.editingCategoryId}`, payload)
          : await api.post('/api/admin/document-categories', payload)

        this.notice = this.editingCategoryId ? 'Document name updated.' : 'Document name created.'
        this.selectedCategoryId = response.data.data.id
        this.resetCategoryForm()
        await this.loadCatalog()
      })
    },
    editCategory(category) {
      this.selectedCategoryId = category.id
      this.subcategoryForm.document_category_id = category.id
      this.editingCategoryId = category.id
      this.categoryForm = {
        name: category.name,
        subtitle: category.subtitle || '',
        description: category.description || '',
        image_url: category.image_url || '',
        sort_order: category.sort_order || 0,
        is_active: Boolean(category.is_active),
      }
    },
    resetCategoryForm() {
      this.editingCategoryId = null
      this.categoryForm = this.defaultCategoryForm()
    },
    async deleteCategory(category) {
      if (!window.confirm(`Delete "${category.name}" and all related sub names/documents?`)) return

      await this.submit(async () => {
        await api.delete(`/api/admin/document-categories/${category.id}`)
        this.notice = 'Document name deleted.'
        await this.loadCatalog()
      })
    },
    async saveSubcategory() {
      await this.submit(async () => {
        const payload = { ...this.subcategoryForm }
        const response = this.editingSubcategoryId
          ? await api.put(`/api/admin/document-subcategories/${this.editingSubcategoryId}`, payload)
          : await api.post('/api/admin/document-subcategories', payload)

        this.notice = this.editingSubcategoryId ? 'Document sub name updated.' : 'Document sub name created.'
        this.selectedCategoryId = payload.document_category_id
        this.selectedSubcategoryId = response.data.data.id
        this.resetSubcategoryForm()
        await this.loadCatalog()
      })
    },
    editSubcategory(subcategory) {
      this.selectedCategoryId = subcategory.document_category_id
      this.selectedSubcategoryId = subcategory.id
      this.editingSubcategoryId = subcategory.id
      this.subcategoryForm = {
        document_category_id: subcategory.document_category_id,
        name: subcategory.name,
        description: subcategory.description || '',
        sort_order: subcategory.sort_order || 0,
        is_active: Boolean(subcategory.is_active),
      }
    },
    resetSubcategoryForm() {
      this.editingSubcategoryId = null
      this.subcategoryForm = {
        ...this.defaultSubcategoryForm(),
        document_category_id: this.selectedCategoryId,
      }
    },
    async deleteSubcategory(subcategory) {
      if (!window.confirm(`Delete "${subcategory.name}" and all documents in it?`)) return

      await this.submit(async () => {
        await api.delete(`/api/admin/document-subcategories/${subcategory.id}`)
        this.notice = 'Document sub name deleted.'
        await this.loadCatalog()
      })
    },
    async saveDocument() {
      await this.submit(async () => {
        const payload = { ...this.documentForm }
        const response = this.editingDocumentId
          ? await api.put(`/api/admin/documents/${this.editingDocumentId}`, payload)
          : await api.post('/api/admin/documents', payload)

        this.notice = this.editingDocumentId ? 'Document updated.' : 'Document added.'
        this.selectedSubcategoryId = response.data.data.document_subcategory_id
        this.resetDocumentForm()
        await this.loadCatalog()
      })
    },
    editDocument(document) {
      this.editingDocumentId = document.id
      this.documentForm = {
        document_subcategory_id: document.document_subcategory_id,
        title: document.title,
        description: document.description || '',
        file_url: document.file_url || '',
        status: document.status,
        published_at: document.published_at ? document.published_at.slice(0, 10) : '',
        sort_order: document.sort_order || 0,
      }
    },
    resetDocumentForm() {
      this.editingDocumentId = null
      this.documentForm = {
        ...this.defaultDocumentForm(),
        document_subcategory_id: this.selectedSubcategoryId,
      }
    },
    async deleteDocument(document) {
      if (!window.confirm(`Delete "${document.title}"?`)) return

      await this.submit(async () => {
        await api.delete(`/api/admin/documents/${document.id}`)
        this.notice = 'Document deleted.'
        await this.loadDocuments()
        await this.loadCatalog()
      })
    },
    async submit(action) {
      this.saving = true
      this.error = ''
      this.notice = ''
      try {
        await action()
      } catch (error) {
        this.error = this.formatError(error)
      } finally {
        this.saving = false
      }
    },
    formatError(error) {
      const errors = error.response?.data?.errors
      if (errors) {
        return Object.values(errors).flat()[0]
      }
      return error.response?.data?.message || 'Request failed.'
    },
    formatDate(value) {
      if (!value) return 'Not set'
      return new Date(value).toLocaleDateString()
    },
    textSummary(value, fallback = 'No description provided') {
      return stripRichText(value, fallback)
    },
    downloadUrl(document) {
      return new URL(`/api/documents/${document.id}/download`, api.defaults.baseURL).toString()
    },
  },
}
</script>

<style scoped>
.public-doc-link,
.manager-button {
  border: 0;
  border-radius: 14px;
  font-weight: 800;
  padding: 0.7rem 1rem;
}

.public-doc-link {
  background: #ffffff;
  color: var(--primary);
}

.document-manager {
  display: block;
}

.portal-alert {
  border-radius: 16px;
  padding: 0.9rem 1rem;
  margin-bottom: 1rem;
  font-weight: 700;
}

.portal-alert.success {
  color: #0f6b3b;
  background: rgba(31, 156, 88, 0.12);
  border: 1px solid rgba(31, 156, 88, 0.22);
}

.portal-alert.danger {
  color: #a82626;
  background: rgba(229, 62, 62, 0.1);
  border: 1px solid rgba(229, 62, 62, 0.22);
}

.manager-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1.4rem;
}

.manager-panel {
  background: #ffffff;
  border: 1px solid #e4ece7;
  border-radius: 24px;
  box-shadow: 0 18px 45px rgba(26, 35, 50, 0.06);
  padding: 1.35rem;
}

.manager-heading {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 1rem;
}

.manager-heading p {
  margin: 0 0 0.2rem;
  color: var(--primary);
  font-weight: 800;
  font-size: 0.72rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
}

.manager-heading h2 {
  margin: 0;
  font-size: 1.2rem;
  color: var(--text-dark);
  font-weight: 800;
}

.text-button {
  border: 0;
  background: transparent;
  color: var(--primary);
  font-weight: 800;
  font-size: 0.82rem;
}

.manager-form,
.document-form {
  display: grid;
  gap: 0.85rem;
  margin-bottom: 1rem;
}

.document-form {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}

.manager-form label,
.document-form label,
.editor-field {
  display: grid;
  gap: 0.35rem;
  color: var(--text-mid);
  font-size: 0.78rem;
  font-weight: 800;
}

.manager-form input,
.manager-form select,
.manager-form textarea,
.document-form input,
.document-form select,
.document-form textarea {
  width: 100%;
  border: 1px solid #dbe5df;
  border-radius: 12px;
  padding: 0.72rem 0.8rem;
  color: var(--text-dark);
  font: inherit;
  font-weight: 600;
  background: #fbfcfb;
}

.manager-form textarea,
.document-form textarea {
  resize: vertical;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 0.8rem;
  align-items: end;
}

.check-row {
  min-height: 44px;
  display: flex !important;
  align-items: center;
  gap: 0.45rem !important;
}

.check-row input {
  width: auto;
}

.document-form .full {
  grid-column: 1 / -1;
}

.editor-field span {
  color: var(--text-mid);
}

.manager-button.primary {
  background: var(--primary);
  color: #ffffff;
}

.manager-button:disabled {
  opacity: 0.55;
  cursor: not-allowed;
}

.manager-list {
  display: grid;
  gap: 0.55rem;
  max-height: 420px;
  overflow-y: auto;
}

.list-item {
  width: 100%;
  border: 1px solid #e4ece7;
  border-radius: 16px;
  background: #ffffff;
  padding: 0.8rem;
  display: flex;
  justify-content: space-between;
  gap: 0.8rem;
  text-align: left;
}

.list-item.active {
  border-color: rgba(31, 156, 88, 0.5);
  background: rgba(31, 156, 88, 0.08);
}

.list-item strong,
.list-item small {
  display: block;
}

.list-item strong {
  color: var(--text-dark);
  font-weight: 800;
}

.list-item small {
  color: var(--text-light);
  font-weight: 700;
  font-size: 0.75rem;
  margin-top: 0.15rem;
}

.item-actions {
  display: flex;
  gap: 0.55rem;
  align-items: flex-start;
  flex-wrap: wrap;
}

.item-actions i,
.item-actions a {
  color: var(--primary);
  font-style: normal;
  font-size: 0.75rem;
  font-weight: 800;
}

.item-actions .danger {
  color: var(--danger);
}

.empty-note,
.empty-table {
  color: var(--text-light);
  font-weight: 700;
  font-size: 0.9rem;
}

.documents-table-wrap {
  overflow-x: auto;
}

.documents-table {
  width: 100%;
  border-collapse: collapse;
}

.documents-table th {
  color: var(--text-light);
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.documents-table th,
.documents-table td {
  padding: 0.9rem;
  border-bottom: 1px solid #edf2ee;
  text-align: left;
  vertical-align: top;
}

.documents-table td strong,
.documents-table td small {
  display: block;
}

.documents-table td small {
  color: var(--text-light);
  margin-top: 0.2rem;
}

.documents-table a {
  color: var(--primary);
  font-weight: 800;
}

.status-pill {
  border-radius: 999px;
  padding: 0.25rem 0.55rem;
  font-size: 0.72rem;
  font-weight: 800;
  text-transform: capitalize;
}

.status-pill.published {
  background: rgba(31, 156, 88, 0.12);
  color: var(--primary);
}

.status-pill.draft {
  background: rgba(252, 191, 27, 0.2);
  color: #8a6208;
}

.status-pill.archived {
  background: rgba(74, 85, 104, 0.12);
  color: var(--text-mid);
}

.table-actions {
  white-space: nowrap;
}

.table-actions button {
  border: 0;
  background: transparent;
  color: var(--primary);
  font-weight: 800;
  margin-right: 0.55rem;
}

.table-actions .danger {
  color: var(--danger);
}

@media (max-width: 1100px) {
  .manager-grid,
  .document-form {
    grid-template-columns: 1fr;
  }
}
</style>

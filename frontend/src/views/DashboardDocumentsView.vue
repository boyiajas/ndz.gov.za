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

      <!-- Department Scope Banner / Admin Department Filter -->
      <div v-if="!auth.isGlobalAdmin && auth.departments.length > 1" class="admin-dept-filter-bar">
        <div class="admin-dept-info">
          <span class="admin-globe-icon">🏛</span>
          <div>
            <strong>Multi-Department Assigned Access</strong>
            <p>Your account is assigned to {{ auth.departments.length }} directorates: {{ auth.departments.map(d => d.code).join(', ') }}.</p>
          </div>
        </div>
        <div class="admin-dept-select-wrap">
          <label for="user-dept-filter">Directorate Filter:</label>
          <select id="user-dept-filter" v-model="selectedDepartmentId" @change="onDepartmentFilterChange">
            <option value="all">All My Assigned Departments ({{ auth.departments.length }})</option>
            <option v-for="dept in auth.departments" :key="dept.id" :value="dept.id">
              {{ dept.name }} ({{ dept.code }})
            </option>
          </select>
        </div>
      </div>

      <div v-else-if="!auth.isGlobalAdmin && auth.departments.length === 1" class="dept-scope-banner">
        <div class="dept-scope-icon">🏛</div>
        <div class="dept-scope-content">
          <div class="dept-scope-title">
            Department Scope: <strong>{{ auth.departments[0].name }}</strong>
            <span class="dept-code-pill">{{ auth.departments[0].code }}</span>
          </div>
          <p class="dept-scope-desc">
            Your account is assigned to this municipal department. You can upload and manage documents specifically belonging to {{ auth.departments[0].name }}.
          </p>
        </div>
      </div>

      <div v-else-if="auth.isGlobalAdmin" class="admin-dept-filter-bar">
        <div class="admin-dept-info">
          <span class="admin-globe-icon">🌐</span>
          <div>
            <strong>Global Administrator Access</strong>
            <p>Managing documents across all municipal departments. Filter below to inspect a specific department.</p>
          </div>
        </div>
        <div class="admin-dept-select-wrap">
          <label for="admin-dept-filter">Filter Department:</label>
          <select id="admin-dept-filter" v-model="selectedDepartmentId" @change="onDepartmentFilterChange">
            <option value="all">All Departments</option>
            <option v-for="dept in departmentsList" :key="dept.id" :value="dept.id">
              {{ dept.name }} ({{ dept.code }})
            </option>
          </select>
        </div>
      </div>

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
            <label v-if="auth.isGlobalAdmin || auth.departments.length > 1">
              Assigned Municipal Department *
              <select v-model.number="categoryForm.department_id" required>
                <option :value="null" disabled>Choose Department</option>
                <option v-for="dept in (auth.isGlobalAdmin ? departmentsList : auth.departments)" :key="dept.id" :value="dept.id">
                  {{ dept.name }} ({{ dept.code }})
                </option>
              </select>
            </label>
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
                <small>
                  <span v-if="category.department" class="cat-dept-badge">{{ category.department.code }}</span>
                  {{ category.subcategories_count || category.subcategories?.length || 0 }} sub names
                  • {{ category.direct_documents_count ?? category.directDocuments_count ?? 0 }} direct files
                </small>
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
              v-if="selectedCategory"
              type="button"
              class="list-item direct-scope-btn"
              :class="{ active: selectedSubcategoryId === null }"
              @click="selectDirectScope"
            >
              <span>
                <strong>📁 Direct Files for {{ selectedCategory.name }}</strong>
                <small>Upload files directly to this document name without creating a sub name</small>
              </span>
              <span class="item-actions">
                <span class="direct-pill">Direct</span>
              </span>
            </button>

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
            <p v-if="selectedCategory && !selectedSubcategories.length" class="empty-note">
              No sub names created yet. You can attach documents directly to {{ selectedCategory.name }} in Step 3 below.
            </p>
          </div>
        </article>
      </section>

      <section class="manager-panel mt-4">
        <div class="manager-heading wide">
          <div>
            <p>Step 3</p>
            <h2>Document listings for {{ selectedDocumentScopeName }}</h2>
          </div>
          <button v-if="editingDocumentId" type="button" class="text-button" @click="resetDocumentForm">Cancel edit</button>
        </div>

        <form class="document-form" @submit.prevent="saveDocument">
          <label>
            Parent document name
            <select v-model.number="documentForm.document_category_id" required @change="onFormCategoryChange">
              <option disabled :value="null">Choose document name</option>
              <option v-for="cat in catalog" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </label>
          <label>
            Destination / Sub name
            <select v-model="documentForm.document_subcategory_id">
              <option :value="null">📁 Directly under Document name (No sub name)</option>
              <option v-for="subcategory in formAvailableSubcategories" :key="subcategory.id" :value="subcategory.id">
                ↳ Sub name: {{ subcategory.name }}
              </option>
            </select>
          </label>
          <label>
            Document title
            <input v-model="documentForm.title" type="text" placeholder="e.g. Municipal Manager Organogram" required />
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
          <button type="submit" class="manager-button primary" :disabled="saving || !documentForm.document_category_id">
            {{ editingDocumentId ? 'Update document' : 'Add document' }}
          </button>
        </form>

        <div class="documents-table-wrap">
          <table class="documents-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Destination</th>
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
                <td>
                  <span v-if="!document.document_subcategory_id" class="scope-pill direct">
                    📁 Direct File
                  </span>
                  <span v-else class="scope-pill sub">
                    ↳ {{ document.subcategory?.name || 'Sub name' }}
                  </span>
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
                <td colspan="7" class="empty-table">No documents have been added to this section yet.</td>
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
      departmentsList: [],
      selectedDepartmentId: 'all',
      selectedCategoryId: null,
      selectedSubcategoryId: null, // null means "Direct to category", or integer subcategory id
      editingCategoryId: null,
      editingSubcategoryId: null,
      editingDocumentId: null,
      saving: false,
      error: '',
      notice: '',
      categoryForm: { name: '', subtitle: '', department_id: null, description: '', image_url: '', sort_order: 0, is_active: true },
      subcategoryForm: { document_category_id: null, name: '', description: '', sort_order: 0, is_active: true },
      documentForm: {
        document_category_id: null,
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
    formAvailableSubcategories() {
      const cat = this.catalog.find((c) => c.id === this.documentForm.document_category_id)
      return cat?.subcategories || []
    },
    selectedDocumentScopeName() {
      if (this.selectedSubcategory) {
        return `${this.selectedCategory?.name || 'Category'} → ${this.selectedSubcategory.name}`
      }
      if (this.selectedCategory) {
        return `${this.selectedCategory.name} (Direct Files)`
      }
      return 'selected document name'
    },
  },
  async mounted() {
    if (this.auth.canManageDocuments) {
      if (this.auth.isGlobalAdmin) {
        await this.loadDepartments()
      }
      await this.loadCatalog()
    }
  },
  methods: {
    async loadDepartments() {
      try {
        const { data } = await api.get('/api/departments')
        this.departmentsList = data.data || []
      } catch (err) {
        console.error('Failed to load departments:', err)
      }
    },
    async onDepartmentFilterChange() {
      this.selectedCategoryId = null
      this.selectedSubcategoryId = null
      await this.loadCatalog()
    },
    defaultCategoryForm() {
      return {
        name: '',
        subtitle: '',
        department_id: (this.selectedDepartmentId !== 'all' ? Number(this.selectedDepartmentId) : null) || (this.auth.departmentId || null),
        description: '',
        image_url: '',
        sort_order: 0,
        is_active: true,
      }
    },
    defaultSubcategoryForm() {
      return { document_category_id: null, name: '', description: '', sort_order: 0, is_active: true }
    },
    defaultDocumentForm() {
      return {
        document_category_id: this.selectedCategoryId,
        document_subcategory_id: this.selectedSubcategoryId || null,
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
        const params = {}
        if (this.selectedDepartmentId !== 'all') {
          params.department_id = this.selectedDepartmentId
        }
        const { data } = await api.get('/api/admin/document-categories', { params })
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
        this.documentForm.document_category_id = null
        this.documentForm.document_subcategory_id = null
        return
      }

      if (!this.catalog.some((category) => category.id === this.selectedCategoryId)) {
        this.selectedCategoryId = this.catalog[0].id
      }

      this.subcategoryForm.document_category_id = this.selectedCategoryId
      this.documentForm.document_category_id = this.selectedCategoryId

      if (this.selectedSubcategoryId && !this.selectedSubcategories.some((sub) => sub.id === this.selectedSubcategoryId)) {
        this.selectedSubcategoryId = null
      }

      this.documentForm.document_subcategory_id = this.selectedSubcategoryId
    },
    async selectCategory(categoryId) {
      this.selectedCategoryId = categoryId
      this.selectedSubcategoryId = null
      this.subcategoryForm.document_category_id = categoryId
      this.documentForm.document_category_id = categoryId
      this.documentForm.document_subcategory_id = null
      await this.loadDocuments()
    },
    selectDirectScope() {
      this.selectedSubcategoryId = null
      this.documentForm.document_category_id = this.selectedCategoryId
      this.documentForm.document_subcategory_id = null
      this.loadDocuments()
    },
    async selectSubcategory(subcategoryId) {
      this.selectedSubcategoryId = subcategoryId
      this.documentForm.document_category_id = this.selectedCategoryId
      this.documentForm.document_subcategory_id = subcategoryId
      await this.loadDocuments()
    },
    onFormCategoryChange() {
      if (this.documentForm.document_category_id) {
        this.selectedCategoryId = this.documentForm.document_category_id
        this.selectedSubcategoryId = null
        this.documentForm.document_subcategory_id = null
        this.loadDocuments()
      }
    },
    async loadDocuments() {
      if (!this.selectedCategoryId) {
        this.documents = []
        return
      }

      try {
        const params = { document_category_id: this.selectedCategoryId }
        if (this.selectedSubcategoryId) {
          params.document_subcategory_id = this.selectedSubcategoryId
        } else {
          params.direct_only = true
        }
        if (this.selectedDepartmentId !== 'all') {
          params.department_id = this.selectedDepartmentId
        }
        const { data } = await api.get('/api/admin/documents', { params })
        const docs = data.data || []
        this.documents = docs.slice().sort((a, b) => {
          const dateA = new Date(a.published_at || a.created_at || a.updated_at || 0).getTime()
          const dateB = new Date(b.published_at || b.created_at || b.updated_at || 0).getTime()
          if (dateB !== dateA) return dateB - dateA
          return (b.id || 0) - (a.id || 0)
        })
      } catch (error) {
        this.error = this.formatError(error)
      }
    },
    async saveCategory() {
      await this.submit(async () => {
        const payload = { ...this.categoryForm }
        if (!payload.department_id && this.selectedDepartmentId !== 'all') {
          payload.department_id = Number(this.selectedDepartmentId)
        }
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
        department_id: category.department_id || null,
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
        const payload = {
          ...this.documentForm,
          document_category_id: this.documentForm.document_category_id || this.selectedCategoryId,
          document_subcategory_id: this.documentForm.document_subcategory_id || null,
        }
        const response = this.editingDocumentId
          ? await api.put(`/api/admin/documents/${this.editingDocumentId}`, payload)
          : await api.post('/api/admin/documents', payload)

        this.notice = this.editingDocumentId ? 'Document updated.' : 'Document added.'
        this.resetDocumentForm()
        await this.loadCatalog()
        await this.loadDocuments()
      })
    },
    editDocument(document) {
      this.editingDocumentId = document.id
      this.documentForm = {
        document_category_id: document.document_category_id || document.subcategory?.document_category_id || this.selectedCategoryId,
        document_subcategory_id: document.document_subcategory_id || null,
        title: document.title,
        description: document.description || '',
        file_url: document.file_url || '',
        status: document.status,
        published_at: document.published_at ? document.published_at.slice(0, 10) : '',
        sort_order: document.sort_order || 0,
      }
      if (document.document_category_id) {
        this.selectedCategoryId = document.document_category_id
      }
      this.selectedSubcategoryId = document.document_subcategory_id || null
    },
    resetDocumentForm() {
      this.editingDocumentId = null
      this.documentForm = {
        ...this.defaultDocumentForm(),
        document_category_id: this.selectedCategoryId,
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
  border-radius: 6px;
  font-weight: 800;
  padding: 0.32rem 0.75rem;
  font-size: 0.74rem;
}

.public-doc-link {
  background: #ffffff;
  color: var(--primary);
}

.document-manager {
  display: block;
}

.portal-alert {
  border-radius: 6px;
  padding: 0.35rem 0.65rem;
  margin-bottom: 0.5rem;
  font-weight: 700;
  font-size: 0.75rem;
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
  gap: 0.65rem;
}

.manager-panel {
  background: #ffffff;
  border: 1px solid #e4ece7;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(26, 35, 50, 0.04);
  padding: 0.65rem 0.85rem;
}

.manager-heading {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
}

.manager-heading p {
  margin: 0 0 0.1rem;
  color: var(--primary);
  font-weight: 800;
  font-size: 0.66rem;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.manager-heading h2 {
  margin: 0;
  font-size: 0.95rem;
  color: var(--text-dark);
  font-weight: 800;
}

.text-button {
  border: 0;
  background: transparent;
  color: var(--primary);
  font-weight: 800;
  font-size: 0.74rem;
}

.manager-form,
.document-form {
  display: grid;
  gap: 0.45rem;
  margin-bottom: 0.5rem;
}

.document-form {
  grid-template-columns: repeat(3, minmax(0, 1fr));
}

.manager-form label,
.document-form label,
.editor-field {
  display: grid;
  gap: 0.15rem;
  color: var(--text-mid);
  font-size: 0.72rem;
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
  border-radius: 6px;
  padding: 0.32rem 0.55rem;
  color: var(--text-dark);
  font: inherit;
  font-size: 0.76rem;
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
  gap: 0.5rem;
  align-items: end;
}

.check-row {
  min-height: 32px;
  display: flex !important;
  align-items: center;
  gap: 0.35rem !important;
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
  gap: 0.35rem;
  max-height: 420px;
  overflow-y: auto;
}

.list-item {
  width: 100%;
  border: 1px solid #e4ece7;
  border-radius: 8px;
  background: #ffffff;
  padding: 0.45rem 0.65rem;
  display: flex;
  justify-content: space-between;
  gap: 0.5rem;
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
  font-size: 0.78rem;
}

.list-item small {
  color: var(--text-light);
  font-weight: 700;
  font-size: 0.68rem;
  margin-top: 0.05rem;
}

.item-actions {
  display: flex;
  gap: 0.35rem;
  align-items: flex-start;
  flex-wrap: wrap;
}

.item-actions i,
.item-actions a {
  color: var(--primary);
  font-style: normal;
  font-size: 0.72rem;
  font-weight: 800;
}

.item-actions .danger {
  color: var(--danger);
}

.empty-note,
.empty-table {
  color: var(--text-light);
  font-weight: 700;
  font-size: 0.76rem;
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
  font-size: 0.66rem;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}

.documents-table th,
.documents-table td {
  padding: 0.32rem 0.5rem;
  border-bottom: 1px solid #edf2ee;
  text-align: left;
  vertical-align: top;
  font-size: 0.74rem;
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

.direct-scope-btn {
  border-left: 3px solid var(--primary);
  background: rgba(31, 156, 88, 0.04);
}

.direct-pill {
  background: var(--primary);
  color: #fff;
  font-size: 0.7rem;
  padding: 0.2rem 0.5rem;
  border-radius: 999px;
  font-weight: 800;
}

.scope-pill {
  display: inline-block;
  font-size: 0.72rem;
  padding: 0.2rem 0.55rem;
  border-radius: 999px;
  font-weight: 700;
}

.scope-pill.direct {
  background: rgba(31, 156, 88, 0.12);
  color: var(--primary);
}

.scope-pill.sub {
  background: #f1f5f9;
  color: var(--text-dark);
}

.dept-scope-banner {
  display: flex;
  align-items: center;
  gap: 1rem;
  background: linear-gradient(135deg, #f0fdf4 0%, #e6f7ec 100%);
  border: 1px solid #bbf7d0;
  border-radius: 12px;
  padding: 1rem 1.25rem;
  margin-bottom: 1.5rem;
}

.dept-scope-icon {
  font-size: 1.8rem;
  line-height: 1;
}

.dept-scope-content {
  flex: 1;
}

.dept-scope-title {
  font-size: 0.95rem;
  color: #166534;
  margin-bottom: 0.2rem;
}

.dept-code-pill {
  display: inline-block;
  background: #166534;
  color: #ffffff;
  font-size: 0.72rem;
  font-weight: 800;
  padding: 0.15rem 0.5rem;
  border-radius: 999px;
  margin-left: 0.5rem;
  letter-spacing: 0.05em;
}

.dept-scope-desc {
  font-size: 0.82rem;
  color: #374151;
  margin: 0;
}

.admin-dept-filter-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1rem;
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border: 1px solid #cbd5e1;
  border-radius: 12px;
  padding: 1rem 1.25rem;
  margin-bottom: 1.5rem;
}

.admin-dept-info {
  display: flex;
  align-items: center;
  gap: 0.85rem;
}

.admin-globe-icon {
  font-size: 1.6rem;
  line-height: 1;
}

.admin-dept-info strong {
  font-size: 0.95rem;
  color: #1e293b;
  display: block;
}

.admin-dept-info p {
  font-size: 0.8rem;
  color: #64748b;
  margin: 0.15rem 0 0 0;
}

.admin-dept-select-wrap {
  display: flex;
  align-items: center;
  gap: 0.65rem;
}

.admin-dept-select-wrap label {
  font-size: 0.82rem;
  font-weight: 700;
  color: #334155;
  white-space: nowrap;
}

.admin-dept-select-wrap select {
  padding: 0.45rem 0.85rem;
  border-radius: 8px;
  border: 1px solid #94a3b8;
  background: #ffffff;
  color: #1e293b;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  min-width: 240px;
}

.cat-dept-badge {
  display: inline-block;
  background: #e2e8f0;
  color: #334155;
  font-size: 0.68rem;
  font-weight: 800;
  padding: 0.1rem 0.4rem;
  border-radius: 4px;
  margin-right: 0.35rem;
  text-transform: uppercase;
}

@media (max-width: 1100px) {
  .manager-grid,
  .document-form {
    grid-template-columns: 1fr;
  }
}
</style>

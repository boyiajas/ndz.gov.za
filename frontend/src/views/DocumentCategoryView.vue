<template>
  <div class="page-wrapper bg-light document-category-view">
    <div class="category-hero">
      <div class="container category-hero-content">
        <p class="header-kicker">Document Name</p>
        <h1>{{ category?.name || 'Documents' }}</h1>
        <p>{{ category?.subtitle || 'NDZ Local Municipality document category' }}</p>

        <nav aria-label="breadcrumb" class="category-breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link to="/">Home</router-link></li>
            <li class="breadcrumb-item"><router-link to="/documents">Documents</router-link></li>
            <li class="breadcrumb-item active" aria-current="page">{{ category?.name || 'Category' }}</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="document-ribbon" aria-hidden="true"></div>

    <section class="py-5">
      <div class="container">
        <div v-if="loading" class="category-state">Loading document name page...</div>
        <div v-else-if="error" class="category-state error">{{ error }}</div>
        <div v-else>
          <div class="category-intro">
            <div>
              <span>{{ countLabel }}</span>
              <h2>{{ category.name }}</h2>
              <div v-if="category.image_url" class="category-intro-image-wrap">
                <img :src="formatImageUrl(category.image_url)" :alt="category.name" class="category-intro-image" />
              </div>
              <p v-if="category.subtitle" class="category-subtitle">{{ category.subtitle }}</p>
              <div
                v-if="category.description"
                class="rich-text-display"
                v-html="richText(category.description)"
              ></div>
              <p v-else>Browse the available document sub names and published municipal document listings.</p>
            </div>
            <router-link to="/documents" class="back-link">Back to Documents</router-link>
          </div>

          <!-- Print or Download File Attachments (Direct Category Documents) -->
          <div v-if="directDocuments.length" class="attachments-section mb-4">
            <div class="attachments-header">
              <h3>Print or Download File Attachments</h3>
              <span>{{ directDocuments.length }} {{ directDocuments.length === 1 ? 'file' : 'files' }}</span>
            </div>
            <div class="public-table-wrap">
              <table class="public-documents-table">
                <thead>
                  <tr>
                    <th style="width: 50px;">#</th>
                    <th>FILE</th>
                    <th style="width: 140px;">DOWNLOADS</th>
                    <th style="width: 130px;"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(doc, idx) in directDocuments" :key="doc.id">
                    <td class="num-cell">{{ idx + 1 }}</td>
                    <td>
                      <strong class="doc-title-text">{{ doc.title }}</strong>
                      <small v-if="doc.description" class="doc-desc-text">{{ textSummary(doc.description) }}</small>
                    </td>
                    <td>
                      <span class="download-count">{{ doc.download_count || 0 }}</span>
                    </td>
                    <td class="download-cell">
                      <a
                        v-if="doc.file_url"
                        :href="downloadUrl(doc)"
                        target="_blank"
                        rel="noopener"
                        class="download-link"
                      >
                        Download
                      </a>
                      <span v-else class="pending-link">File pending</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Document Sub Names (Subcategories Grid) -->
          <div v-if="subcategories.length" class="subcategories-section mb-4">
            <h3 v-if="directDocuments.length" class="section-sub-title">Document Sub Names</h3>
            <div class="subcategory-grid">
              <router-link
                v-for="subcategory in subcategories"
                :key="subcategory.id"
                class="subcategory-card"
                :to="{ name: 'document-listing', params: { categorySlug: category.slug, subcategorySlug: subcategory.slug } }"
              >
                <span class="subcategory-count">{{ subcategory.documents_count || 0 }}</span>
                <strong>{{ subcategory.name }}</strong>
                <small>{{ textSummary(subcategory.description, 'View published documents for this section.') }}</small>
                <em>Open listing →</em>
              </router-link>
            </div>
          </div>

          <div v-if="!subcategories.length && !directDocuments.length" class="category-state">
            No documents or sub names have been published under this document name yet.
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import api from '../api/axios'
import { sanitizeRichText, stripRichText } from '../utils/richText'

export default {
  name: 'DocumentCategoryView',
  data() {
    return {
      category: null,
      subcategories: [],
      directDocuments: [],
      loading: true,
      error: '',
    }
  },
  computed: {
    countLabel() {
      if (this.subcategories?.length) {
        return `${this.subcategories.length} document sub name${this.subcategories.length === 1 ? '' : 's'}`
      }
      if (this.directDocuments?.length) {
        return `${this.directDocuments.length} document file${this.directDocuments.length === 1 ? '' : 's'}`
      }
      return 'Document category'
    },
  },
  watch: {
    '$route.params.categorySlug': {
      handler() {
        this.loadCategory()
      },
      immediate: true,
    },
  },
  methods: {
    async loadCategory() {
      this.loading = true
      this.error = ''
      try {
        const { data } = await api.get(`/api/document-catalog/${this.$route.params.categorySlug}`)
        this.category = data.data
        this.subcategories = data.data?.subcategories || []
        this.directDocuments = data.data?.direct_documents || data.data?.directDocuments || []
      } catch (error) {
        this.error = 'This document name page could not be found or is not published yet.'
      } finally {
        this.loading = false
      }
    },
    downloadUrl(document) {
      const baseUrl = api.defaults.baseURL || window.location.origin
      return new URL(`/api/documents/${document.id}/download`, baseUrl).toString()
    },
    richText(value) {
      return sanitizeRichText(value)
    },
    textSummary(value, fallback) {
      return stripRichText(value, fallback)
    },
    formatImageUrl(url) {
      if (!url) return ''
      if (/^https?:\/\/localhost(?::80)?\/storage\//i.test(url)) {
        return url.replace(/^https?:\/\/localhost(?::80)?/i, 'http://localhost:8001')
      }
      return url
    },
  },
}
</script>

<style scoped>
.category-hero {
  position: relative;
  min-height: 340px;
  background:
    radial-gradient(circle at top right, rgba(252, 191, 27, 0.28), transparent 34%),
    linear-gradient(135deg, #1f9c58 0%, #0f6b3b 100%);
  color: #ffffff;
  overflow: hidden;
}

.category-hero-content {
  position: relative;
  z-index: 1;
  padding: 4rem 0;
}

.header-kicker {
  margin: 0 0 0.45rem;
  color: rgba(255, 255, 255, 0.72);
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.12em;
}

.category-hero h1 {
  margin: 0;
  font-size: clamp(2.1rem, 4vw, 3rem);
  font-weight: 800;
}

.category-hero p {
  margin-top: 0.5rem;
  max-width: 720px;
  opacity: 0.86;
}

.category-breadcrumb {
  margin-top: 1.5rem;
  display: flex;
}

.category-breadcrumb .breadcrumb {
  margin: 0;
  font-size: 0.95rem;
  background: rgba(255, 255, 255, 0.1);
  padding: 0.5rem 1rem;
  border-radius: 50px;
}

.category-breadcrumb a,
.category-breadcrumb .active {
  color: #ffffff;
}

.document-ribbon {
  height: 18px;
  background: repeating-linear-gradient(
    135deg,
    var(--accent) 0 10px,
    #ffffff 10px 20px,
    var(--primary) 20px 30px,
    #ffffff 30px 40px
  );
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.category-state {
  background: #ffffff;
  border: 1px solid #eef1f5;
  border-radius: 16px;
  padding: 1.2rem;
  color: var(--text-light);
  font-weight: 700;
}

.category-state.error {
  color: #a82626;
}

.category-intro {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
  align-items: flex-start;
  background: #ffffff;
  border: 1px solid #eef1f5;
  border-radius: 22px;
  padding: 1.4rem;
  margin-bottom: 1rem;
}

.category-intro span {
  color: var(--primary);
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-weight: 800;
}

.category-intro h2 {
  margin: 0.25rem 0;
  color: var(--text-dark);
  font-weight: 800;
}

.category-intro-image-wrap {
  margin: 0.85rem 0 1rem;
}

.category-intro-image {
  max-width: 320px;
  width: 100%;
  max-height: 280px;
  object-fit: cover;
  object-position: top center;
  border-radius: 14px;
  border: 1px solid #eef1f5;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
  display: block;
}

.category-subtitle {
  margin: 0.25rem 0 1rem;
  color: var(--text-mid);
  font-size: 1rem;
  font-weight: 700;
}

.category-intro p,
.rich-text-display {
  margin: 0;
  color: var(--text-light);
}

.rich-text-display :deep(h3),
.rich-text-display :deep(h4) {
  color: var(--text-dark);
  font-size: 1rem;
  font-weight: 800;
  margin: 0 0 1rem;
}

.rich-text-display :deep(p) {
  margin: 0 0 1.1rem;
  color: var(--text-mid);
}

.rich-text-display :deep(ul),
.rich-text-display :deep(ol) {
  margin: 1rem 0 1.2rem;
  padding-left: 1.25rem;
  color: var(--text-mid);
}

.rich-text-display :deep(li) {
  margin: 0.45rem 0;
}

.rich-text-display :deep(strong),
.rich-text-display :deep(b) {
  color: var(--text-dark);
  font-weight: 800;
}

.back-link {
  border-radius: 999px;
  padding: 0.65rem 1rem;
  font-weight: 800;
  white-space: nowrap;
  color: var(--primary);
  background: rgba(31, 156, 88, 0.1);
}

.subcategory-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 1rem;
}

.subcategory-card {
  background: #ffffff;
  border: 1px solid #eef1f5;
  border-radius: 20px;
  padding: 1.2rem;
  min-height: 190px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.04);
  transition: transform 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
}

.subcategory-card:hover {
  transform: translateY(-3px);
  border-color: rgba(31, 156, 88, 0.34);
  box-shadow: 0 18px 42px rgba(31, 156, 88, 0.1);
}

.subcategory-count {
  border-radius: 999px;
  background: rgba(31, 156, 88, 0.1);
  color: var(--primary);
  padding: 0.25rem 0.65rem;
  font-size: 0.75rem;
  font-weight: 800;
}

.subcategory-card strong {
  margin-top: 0.8rem;
  color: var(--text-dark);
  font-size: 1rem;
  font-weight: 800;
}

.subcategory-card small {
  color: var(--text-light);
  margin-top: 0.35rem;
}

.subcategory-card em {
  margin-top: auto;
  color: var(--primary);
  font-style: normal;
  font-weight: 800;
  font-size: 0.82rem;
}

.attachments-section {
  background: #ffffff;
  border: 1px solid #eef1f5;
  border-radius: 22px;
  padding: 1.5rem;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.04);
}

.attachments-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.25rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid #f1f5f9;
}

.attachments-header h3 {
  margin: 0;
  font-size: 1.15rem;
  font-weight: 800;
  color: var(--text-dark);
}

.attachments-header span {
  font-size: 0.8rem;
  color: var(--primary);
  font-weight: 800;
  background: rgba(31, 156, 88, 0.1);
  padding: 0.25rem 0.65rem;
  border-radius: 999px;
}

.section-sub-title {
  font-size: 1.1rem;
  font-weight: 800;
  color: var(--text-dark);
  margin-bottom: 1rem;
}

.public-table-wrap {
  overflow-x: auto;
}

.public-documents-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 600px;
}

.public-documents-table th {
  color: var(--text-light);
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  padding: 0.85rem 1rem;
  border-bottom: 2px solid #eef1f5;
  text-align: left;
}

.public-documents-table td {
  padding: 1rem;
  border-bottom: 1px solid #f8fafc;
  vertical-align: middle;
}

.public-documents-table tr:hover {
  background-color: #fbfdfc;
}

.num-cell {
  color: var(--text-light);
  font-weight: 700;
  font-size: 0.85rem;
}

.doc-title-text {
  display: block;
  color: var(--text-dark);
  font-size: 0.95rem;
  font-weight: 700;
}

.doc-desc-text {
  display: block;
  color: var(--text-light);
  margin-top: 0.25rem;
  font-size: 0.82rem;
}

.download-count {
  display: inline-block;
  background: #f1f5f9;
  color: var(--text-dark);
  padding: 0.25rem 0.65rem;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.85rem;
}

.download-cell {
  text-align: right;
}

.download-link {
  display: inline-block;
  background: var(--primary);
  color: #ffffff !important;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.82rem;
  padding: 0.45rem 1rem;
  border-radius: 999px;
  transition: opacity 0.15s ease;
}

.download-link:hover {
  opacity: 0.9;
}

.pending-link {
  color: var(--text-light);
  font-size: 0.82rem;
  font-style: italic;
}

@media (max-width: 992px) {
  .subcategory-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 768px) {
  .category-intro {
    flex-direction: column;
  }

  .subcategory-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<template>
  <div class="page-wrapper bg-light document-listing-view">
    <div class="page-header listing-header">
      <div class="container">
        <p class="header-kicker">Documents</p>
        <h1>{{ subcategory?.name || 'Document Listing' }}</h1>
        <p>{{ category?.name || 'NDZ Local Municipality' }}</p>

        <nav aria-label="breadcrumb" class="listing-breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link to="/">Home</router-link></li>
            <li class="breadcrumb-item"><router-link to="/documents">Documents</router-link></li>
            <li v-if="category" class="breadcrumb-item">
              <router-link :to="{ name: 'document-category', params: { categorySlug: category.slug } }">
                {{ category.name }}
              </router-link>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ subcategory?.name || 'Listing' }}</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="document-ribbon" aria-hidden="true"></div>

    <section class="py-5">
      <div class="container">
        <div v-if="loading" class="listing-state">Loading documents...</div>
        <div v-else-if="error" class="listing-state error">{{ error }}</div>
        <div v-else>
          <div class="listing-summary">
            <div>
              <span>{{ documents.length }} published documents</span>
              <h2>{{ subcategory.name }}</h2>
              <div
                v-if="subcategory.description"
                class="rich-text-display"
                v-html="richText(subcategory.description)"
              ></div>
              <p v-else>Official municipal document listing.</p>
            </div>
            <router-link
              v-if="category"
              :to="{ name: 'document-category', params: { categorySlug: category.slug } }"
              class="back-link"
            >
              Back to {{ category.name }}
            </router-link>
          </div>

          <div v-if="documents.length" class="public-table-wrap">
            <table class="public-documents-table">
              <thead>
                <tr>
                  <th>Document</th>
                  <th>Published</th>
                  <th>Downloads</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="document in documents" :key="document.id">
                  <td>
                    <span class="document-status">{{ document.status }}</span>
                    <strong>{{ document.title }}</strong>
                    <small>{{ textSummary(document.description, 'No description provided.') }}</small>
                  </td>
                  <td>{{ formatDate(document.published_at) }}</td>
                  <td>
                    <span class="download-count">{{ document.download_count || 0 }}</span>
                  </td>
                  <td class="download-cell">
                    <a
                      v-if="document.file_url"
                      :href="downloadUrl(document)"
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

          <div v-else class="empty-listing">
            <h3>No documents published yet</h3>
            <p>This section has been created, but no public documents have been added to it yet.</p>
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
  name: 'DocumentListingView',
  data() {
    return {
      category: null,
      subcategory: null,
      documents: [],
      loading: true,
      error: '',
    }
  },
  watch: {
    '$route.params': {
      handler() {
        this.loadDocuments()
      },
      immediate: true,
    },
  },
  methods: {
    async loadDocuments() {
      this.loading = true
      this.error = ''
      try {
        const { categorySlug, subcategorySlug } = this.$route.params
        const { data } = await api.get(`/api/document-catalog/${categorySlug}/${subcategorySlug}`)
        this.category = data.category
        this.subcategory = data.subcategory
        this.documents = data.documents || []
      } catch (error) {
        this.error = 'This document listing could not be found or is not published yet.'
      } finally {
        this.loading = false
      }
    },
    formatDate(value) {
      if (!value) return 'date not set'
      return new Date(value).toLocaleDateString()
    },
    downloadUrl(document) {
      return new URL(`/api/documents/${document.id}/download`, api.defaults.baseURL).toString()
    },
    richText(value) {
      return sanitizeRichText(value)
    },
    textSummary(value, fallback) {
      return stripRichText(value, fallback)
    },
  },
}
</script>

<style scoped>
.listing-header {
  background: var(--primary, #004d40);
  color: #ffffff;
  padding: 4rem 0;
  text-align: left;
}

.header-kicker {
  margin: 0 0 0.45rem;
  color: rgba(255, 255, 255, 0.72);
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.12em;
}

.listing-header h1 {
  margin: 0;
  font-size: clamp(2rem, 4vw, 2.7rem);
  font-weight: 800;
}

.listing-header p {
  margin-top: 0.5rem;
  opacity: 0.84;
}

.listing-breadcrumb {
  margin-top: 1.5rem;
  display: flex;
}

.listing-breadcrumb .breadcrumb {
  margin: 0;
  font-size: 0.95rem;
  background: rgba(255, 255, 255, 0.1);
  padding: 0.5rem 1rem;
  border-radius: 50px;
}

.listing-breadcrumb a,
.listing-breadcrumb .active {
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

.listing-state,
.empty-listing {
  background: #ffffff;
  border: 1px solid #eef1f5;
  border-radius: 16px;
  padding: 1.2rem;
  color: var(--text-light);
  font-weight: 700;
}

.listing-state.error {
  color: #a82626;
}

.listing-summary {
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

.listing-summary span {
  color: var(--primary);
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-weight: 800;
}

.listing-summary h2 {
  margin: 0.25rem 0;
  color: var(--text-dark);
  font-weight: 800;
}

.listing-summary p,
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

.back-link,
.download-link {
  border-radius: 999px;
  padding: 0.65rem 1rem;
  font-weight: 800;
  white-space: nowrap;
}

.back-link {
  color: var(--primary);
  background: rgba(31, 156, 88, 0.1);
}

.public-table-wrap {
  background: #ffffff;
  border: 1px solid #eef1f5;
  border-radius: 20px;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.04);
  overflow-x: auto;
}

.public-documents-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 720px;
}

.public-documents-table th {
  color: var(--text-light);
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.public-documents-table th,
.public-documents-table td {
  padding: 1rem;
  border-bottom: 1px solid #edf2ee;
  text-align: left;
  vertical-align: middle;
}

.public-documents-table tr:last-child td {
  border-bottom: 0;
}

.document-status {
  display: inline-flex;
  border-radius: 999px;
  background: rgba(31, 156, 88, 0.1);
  color: var(--primary);
  padding: 0.2rem 0.55rem;
  font-size: 0.7rem;
  font-weight: 800;
  text-transform: capitalize;
}

.public-documents-table td strong {
  display: block;
  margin: 0.45rem 0 0.2rem;
  font-weight: 800;
  color: var(--text-dark);
}

.public-documents-table td small {
  display: block;
  color: var(--text-light);
}

.download-count {
  display: inline-flex;
  min-width: 42px;
  justify-content: center;
  border-radius: 999px;
  background: rgba(31, 156, 88, 0.1);
  color: var(--primary);
  padding: 0.35rem 0.7rem;
  font-weight: 800;
}

.download-cell {
  text-align: right;
}

.download-link {
  background: var(--primary);
  color: #ffffff;
}

.pending-link {
  color: var(--text-light);
  background: #f1f4f2;
  border-radius: 999px;
  padding: 0.65rem 1rem;
  font-weight: 800;
  white-space: nowrap;
}

.empty-listing h3 {
  color: var(--text-dark);
  font-weight: 800;
}

@media (max-width: 768px) {
  .listing-summary {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>

<template>
  <div class="page-wrapper bg-light document-category-view">
    <div class="category-hero" :class="{ 'has-image': category?.image_url }">
      <img v-if="category?.image_url" :src="category.image_url" :alt="category.name" />
      <div class="category-hero-overlay"></div>
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
              <span>{{ subcategories.length }} document sub names</span>
              <h2>{{ category.name }}</h2>
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

          <div v-if="!subcategories.length" class="category-state">
            No document sub names have been published under this document name yet.
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
      loading: true,
      error: '',
    }
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
      } catch (error) {
        this.error = 'This document name page could not be found or is not published yet.'
      } finally {
        this.loading = false
      }
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
.category-hero {
  position: relative;
  min-height: 340px;
  background:
    radial-gradient(circle at top right, rgba(252, 191, 27, 0.28), transparent 34%),
    linear-gradient(135deg, #1f9c58 0%, #0f6b3b 100%);
  color: #ffffff;
  overflow: hidden;
}

.category-hero img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.category-hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg, rgba(10, 69, 39, 0.9), rgba(10, 69, 39, 0.58));
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

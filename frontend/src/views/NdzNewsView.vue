<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">NDZ News & Media</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Official news, municipal announcements, and press publications</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">Media</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">NDZ News</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Filter & Search Section -->
    <section class="filter-bar py-4 bg-white border-bottom">
      <div class="container">
        <div class="row g-3 align-items-center justify-content-between">
          <div class="col-md-5">
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
              <input
                v-model="searchQuery"
                type="text"
                class="form-control border-start-0 bg-light"
                placeholder="Search articles by keyword..."
                @input="filterArticles"
              />
            </div>
          </div>
          <div class="col-md-7">
            <div class="d-flex gap-2 flex-wrap justify-content-md-end">
              <button
                v-for="cat in categories"
                :key="cat"
                type="button"
                class="btn btn-sm rounded-pill px-3"
                :class="selectedCategory === cat ? 'btn-primary' : 'btn-outline-secondary'"
                @click="selectCategory(cat)"
              >
                {{ cat }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-5">
      <div class="container pb-5">
        <div v-if="loading" class="text-center py-5 text-muted">
          <div class="spinner-border text-primary mb-3" role="status"></div>
          <p>Loading news stories...</p>
        </div>

        <div v-else-if="filteredArticles.length === 0" class="text-center py-5">
          <h4 class="text-muted">No news stories found</h4>
          <p class="text-secondary">Try adjusting your category filter or search query.</p>
        </div>

        <div v-else class="row g-4">
          <div v-for="article in filteredArticles" :key="article.id" class="col-md-6 col-lg-4 d-flex">
            <div class="card border-0 shadow-sm w-100 news-card overflow-hidden">
              <img
                :src="article.image_url || '/placeholder-news.jpg'"
                class="card-img-top object-fit-cover"
                :alt="article.title"
                style="height: 220px;"
                @error="handleImageError"
              />
              <div class="card-body p-4 d-flex flex-column h-100 bg-white">
                <div class="mb-2 text-muted small d-flex justify-content-between align-items-center">
                  <span>
                    <i class="bi bi-calendar-event me-1"></i> {{ formatDate(article.published_at) }}
                  </span>
                  <span class="badge bg-light text-primary border">{{ article.category }}</span>
                </div>
                <h5 class="card-title fw-bold text-dark lh-sm mb-3">{{ article.title }}</h5>
                <p class="card-text text-secondary mb-4 flex-grow-1">
                  {{ article.excerpt }}
                </p>
                <button
                  type="button"
                  class="btn btn-outline-primary mt-auto align-self-start"
                  @click="openArticleModal(article)"
                >
                  Read Story <i class="bi bi-arrow-right ms-1"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Article Detail Modal -->
    <div v-if="selectedArticle" class="modal-overlay" @click="closeArticleModal">
      <div class="modal-dialog modal-lg modal-dialog-centered" @click.stop>
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
          <div class="modal-header border-0 pb-0 d-flex justify-content-between">
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold">
              {{ selectedArticle.category }}
            </span>
            <button type="button" class="btn-close" @click="closeArticleModal"></button>
          </div>
          <div class="modal-body p-4 p-md-5">
            <p class="text-muted small mb-2">
              <i class="bi bi-calendar3 me-1"></i> Published {{ formatDate(selectedArticle.published_at) }} • {{ selectedArticle.read_time }}
            </p>
            <h2 class="fw-bold mb-4 text-dark">{{ selectedArticle.title }}</h2>

            <img
              v-if="selectedArticle.image_url"
              :src="selectedArticle.image_url"
              class="img-fluid rounded-3 mb-4 w-100 object-fit-cover"
              style="max-height: 400px;"
              :alt="selectedArticle.title"
              @error="handleImageError"
            />

            <div class="article-content-body lh-lg text-secondary" v-html="selectedArticle.content || selectedArticle.excerpt"></div>
          </div>
          <div class="modal-footer border-0 bg-light px-4 py-3">
            <button type="button" class="btn btn-secondary rounded-pill px-4" @click="closeArticleModal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NdzNewsView',
  data() {
    return {
      articles: [],
      filteredArticles: [],
      categories: ['All', 'News', 'Blog', 'Notice', 'Press Release', 'Budget'],
      selectedCategory: 'All',
      searchQuery: '',
      loading: true,
      selectedArticle: null,
    }
  },
  mounted() {
    this.fetchNews()
  },
  methods: {
    async fetchNews() {
      this.loading = true
      try {
        const res = await fetch('/api/news')
        if (res.ok) {
          const json = await res.json()
          this.articles = json.data || []
          this.filterArticles()
        }
      } catch (err) {
        console.error('Failed to fetch public news:', err)
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
            a.excerpt?.toLowerCase().includes(q) ||
            a.content?.toLowerCase().includes(q)
        )
      }
      this.filteredArticles = list
    },
    openArticleModal(article) {
      this.selectedArticle = article
    },
    closeArticleModal() {
      this.selectedArticle = null
    },
    formatDate(d) {
      if (!d) return 'Recent'
      return new Date(d).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      })
    },
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=News+Image&background=f0f7f3&color=0f6b3b&size=400'
    },
  },
}
</script>

<style scoped>
.page-wrapper {
  background-color: #f8f9fa;
}

.text-primary {
  color: #0f6b3b !important;
}

.btn-primary {
  background-color: #0f6b3b !important;
  border-color: #0f6b3b !important;
  color: #fff !important;
}

.btn-outline-primary {
  color: #0f6b3b;
  border-color: #0f6b3b;
}

.btn-outline-primary:hover {
  background-color: #0f6b3b;
  color: #fff;
}

.news-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 14px;
}

.news-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 1rem 3rem rgba(0,0,0,.1)!important;
}

.card-title {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.card-text {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 19, 0.7);
  backdrop-filter: blur(5px);
  z-index: 1050;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
}

.modal-content {
  background: #ffffff;
}

.article-content-body {
  font-size: 1.05rem;
  line-height: 1.8;
}
</style>

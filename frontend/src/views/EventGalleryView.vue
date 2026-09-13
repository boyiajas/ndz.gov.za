<template>
  <div class="page-wrapper">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Event Gallery</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Discover community events, tourism landmarks, and municipal projects</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">Media</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Event Gallery</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Category Filter Bar -->
    <div class="filter-bar py-3 bg-white border-bottom">
      <div class="container d-flex justify-content-center flex-wrap gap-2">
        <button
          v-for="cat in categories"
          :key="cat"
          type="button"
          class="btn btn-sm rounded-pill px-4"
          :class="selectedCategory === cat ? 'btn-success' : 'btn-outline-secondary'"
          @click="selectCategory(cat)"
        >
          {{ cat }}
        </button>
      </div>
    </div>

    <!-- Gallery Content -->
    <section class="gallery-section py-5">
      <div class="container">
        <div v-if="loading" class="text-center py-5 text-muted">
          <div class="spinner-border text-success mb-3" role="status"></div>
          <p>Loading photo gallery...</p>
        </div>

        <div v-else-if="filteredItems.length === 0" class="text-center py-5 text-muted">
          <h4>No photos found</h4>
          <p>Check back soon for new photo albums.</p>
        </div>

        <div v-else class="row g-4">
          <div
            v-for="item in filteredItems"
            :key="item.id"
            class="col-md-6 col-lg-4"
            @click="openLightbox(item)"
          >
            <div class="gallery-card">
              <div class="img-wrapper">
                <img :src="item.image_url" :alt="item.title" @error="handleImageError" />
                <div class="img-overlay">
                  <span class="zoom-btn"><i class="bi bi-arrows-fullscreen"></i></span>
                  <div class="overlay-text">
                    <span class="badge bg-success mb-1">{{ item.category }}</span>
                    <h5>{{ item.title }}</h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Lightbox Modal -->
    <div v-if="activePhoto" class="lightbox-backdrop" @click="activePhoto = null">
      <div class="lightbox-content" @click.stop>
        <button class="lightbox-close" @click="activePhoto = null">&times;</button>
        <img :src="activePhoto.image_url" :alt="activePhoto.title" />
        <div class="lightbox-caption">
          <span class="badge bg-success mb-1">{{ activePhoto.category }}</span>
          <h4>{{ activePhoto.title }}</h4>
          <p v-if="activePhoto.description" class="m-0 text-white-50">{{ activePhoto.description }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EventGalleryView',
  data() {
    return {
      items: [],
      filteredItems: [],
      categories: ['All', 'Events', 'Tourism', 'Community', 'Council', 'Infrastructure'],
      selectedCategory: 'All',
      loading: true,
      activePhoto: null,
    }
  },
  mounted() {
    this.fetchGallery()
  },
  methods: {
    async fetchGallery() {
      this.loading = true
      try {
        const res = await fetch('/api/gallery')
        if (res.ok) {
          const json = await res.json()
          this.items = json.data || []
          this.filterGallery()
        }
      } catch (err) {
        console.error('Failed to fetch gallery:', err)
      } finally {
        this.loading = false
      }
    },
    selectCategory(cat) {
      this.selectedCategory = cat
      this.filterGallery()
    },
    filterGallery() {
      if (this.selectedCategory === 'All') {
        this.filteredItems = [...this.items]
      } else {
        this.filteredItems = this.items.filter(
          (i) => i.category?.toLowerCase() === this.selectedCategory.toLowerCase()
        )
      }
    },
    openLightbox(item) {
      this.activePhoto = item
    },
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=Gallery+Photo&background=f0f7f3&color=0f6b3b&size=400'
    },
  },
}
</script>

<style scoped>
.btn-success {
  background-color: #0f6b3b !important;
  border-color: #0f6b3b !important;
}

.gallery-card {
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  background: #ffffff;
  cursor: pointer;
}

.img-wrapper {
  position: relative;
  width: 100%;
  height: 320px;
  overflow: hidden;
  background: #eef2ef;
}

.img-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.img-wrapper:hover img {
  transform: scale(1.08);
}

.img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.1) 60%, transparent 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 1.5rem;
}

.img-wrapper:hover .img-overlay {
  opacity: 1;
}

.zoom-btn {
  align-self: flex-end;
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(4px);
  color: #ffffff;
  width: 38px;
  height: 38px;
  border-radius: 50%;
  display: grid;
  place-items: center;
}

.overlay-text h5 {
  color: #ffffff;
  margin: 0;
  font-weight: 700;
  font-size: 1.1rem;
}

/* Lightbox Modal */
.lightbox-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(10, 20, 15, 0.9);
  backdrop-filter: blur(8px);
  z-index: 1100;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.lightbox-content {
  position: relative;
  max-width: 900px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  border-radius: 18px;
  overflow: hidden;
  background: #000000;
}

.lightbox-content img {
  max-height: 70vh;
  object-fit: contain;
  width: 100%;
}

.lightbox-caption {
  padding: 1.25rem 1.5rem;
  background: #111a14;
  color: #ffffff;
}

.lightbox-caption h4 {
  margin: 0 0 0.25rem;
  font-weight: 800;
}

.lightbox-close {
  position: absolute;
  top: 15px;
  right: 15px;
  background: rgba(0, 0, 0, 0.6);
  border: 0;
  color: #ffffff;
  font-size: 2rem;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  display: grid;
  place-items: center;
  cursor: pointer;
  z-index: 10;
}
</style>

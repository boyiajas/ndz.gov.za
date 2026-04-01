<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Tourism Unit</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">Department</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Tourism</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Main Content Section -->
    <section class="tourism-section py-5">
      <div class="container pb-5">
        <div class="row g-5 align-items-start">
          <!-- Text Column -->
          <div class="col-lg-7">
            <div class="content-card p-4 p-md-5 bg-white shadow-sm rounded">
              <p class="lead text-dark fw-normal mb-4" style="line-height: 1.8;">
                The Tourism Unit of Dr. Nkosazana Dlamini-Zuma Municipality is committed to unlocking the region’s full tourism potential by showcasing its natural beauty, cultural richness, and historical significance.
              </p>

              <h3 class="fw-bold mb-4 text-primary" style="font-size: 1.5rem;">Key Focus Areas</h3>

              <ul class="list-unstyled focus-areas-list">
                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-geo-alt-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Destination Promotion</h5>
                    <p class="text-secondary mb-0">Marketing the municipality as a must-visit destination for eco-tourism, cultural experiences, and heritage exploration.</p>
                  </div>
                </li>
                
                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-briefcase-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Support for Local Tourism Enterprises</h5>
                    <p class="text-secondary mb-0">Empowering local guides, hospitality providers, and tourism SMMEs through training, exposure, and access to resources.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-calendar-event-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Event-Driven Tourism</h5>
                    <p class="text-secondary mb-0">Coordinating cultural festivals, heritage events, and outdoor adventures that attract both domestic and international visitors.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-people-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Partnership Development</h5>
                    <p class="text-secondary mb-0">Collaborating with local communities, private sector partners, and provincial tourism bodies to enhance visitor experiences.</p>
                  </div>
                </li>

                <li class="d-flex mb-4">
                  <div class="icon-box me-3 mt-1 text-primary">
                    <i class="bi bi-signpost-split-fill fs-4"></i>
                  </div>
                  <div>
                    <h5 class="fw-bold text-dark mb-1">Infrastructure & Experience Development</h5>
                    <p class="text-secondary mb-0">Improving tourism facilities, signage, and visitor information to create a safe, welcoming, and memorable environment.</p>
                  </div>
                </li>
              </ul>

              <div class="alert mt-5 border-start border-4 border-warning bg-light p-4 shadow-sm" style="border-radius: 0 4px 4px 0;">
                <p class="mb-0 text-dark fw-medium" style="line-height: 1.7;">
                  With its scenic landscapes, vibrant culture, and warm hospitality, the municipality offers visitors a unique and authentic experience — and the Tourism Unit is the gateway to it all.
                </p>
              </div>
            </div>
          </div>

          <!-- Assets / Image Column -->
          <div class="col-lg-5">
            <div class="row g-3 sticky-top" style="top: 2rem;">
              <div class="col-12">
                <div class="image-box rounded overflow-hidden shadow-sm">
                  <img src="/tourism-1.jpg" alt="Tourism Highlights 1" class="img-fluid w-100" style="object-fit: cover; height: 280px;" @error="handleImageError" />
                </div>
              </div>
              <div class="col-6">
                <div class="image-box rounded overflow-hidden shadow-sm">
                  <img src="/tourism-2.jpg" alt="Tourism Highlights 2" class="img-fluid w-100" style="object-fit: cover; height: 200px;" @error="handleImageError" />
                </div>
              </div>
              <div class="col-6">
                <div class="image-box rounded overflow-hidden shadow-sm">
                  <img src="/tourism-3.jpg" alt="Tourism Highlights 3" class="img-fluid w-100" style="object-fit: cover; height: 200px;" @error="handleImageError" />
                </div>
              </div>
              <div class="col-12 mt-3 text-center">
                 <router-link to="/gallery" class="btn btn-outline-primary px-4 py-2 mt-2 fw-semibold shadow-sm">View Full Gallery</router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'TourismView',
  methods: {
    handleImageError(event) {
      event.target.style.display = 'none';
    }
  }
}
</script>

<style scoped>
.page-wrapper {
  background-color: #f8f9fa;
}

.text-primary {
  color: #00a651 !important; /* NDZ Primary Brand Color */
}

.btn-outline-primary {
  color: #00a651;
  border-color: #00a651;
}

.btn-outline-primary:hover {
  background-color: #00a651;
  color: #fff;
}

.focus-areas-list .icon-box {
  width: 45px;
  height: 45px;
  background-color: rgba(0, 166, 81, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.image-box {
  transition: transform 0.3s ease;
}

.image-box:hover {
  transform: scale(1.02);
}
</style>

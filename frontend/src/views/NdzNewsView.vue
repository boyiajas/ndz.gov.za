<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">NDZ News</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Latest news and updates from the Municipality</p>

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

    <!-- Main Content Section -->
    <section class="py-5">
      <div class="container pb-5">
        <div class="row g-4">
          
          <!-- Article 1 -->
          <div class="col-md-6 col-lg-4 d-flex">
            <div class="card border-0 shadow-sm w-100 news-card overflow-hidden">
              <img src="/img/news/news-fire-engine.jpg" class="card-img-top object-fit-cover" alt="Fire Engine" style="height: 220px;" @error="handleImageError" />
              <div class="card-body p-4 d-flex flex-column h-100 bg-white">
                <div class="mb-2 text-muted small">
                  <i class="bi bi-calendar-event me-1"></i> October 14, 2019 &nbsp;|&nbsp; <i class="bi bi-folder me-1"></i> BLOG, News
                </div>
                <h5 class="card-title fw-bold text-dark lh-sm mb-3">Dr Nkosazana Dlamini Zuma Municipality Acquired A Fire Engine</h5>
                <p class="card-text text-secondary mb-4 flex-grow-1">
                  The procurement of the fire engine is earmarked at ensuring adequate and sufficient equipment to deal with fires within the area of jurisdiction of the municipality. Mostly structural fires that are experienced on regular basis which to a certain extent...
                </p>
                <a href="#" class="btn btn-outline-primary mt-auto align-self-start">Read More <i class="bi bi-arrow-right ms-1"></i></a>
              </div>
            </div>
          </div>

          <!-- Article 2 -->
          <div class="col-md-6 col-lg-4 d-flex">
            <div class="card border-0 shadow-sm w-100 news-card overflow-hidden">
              <img src="/img/news/news-salga.jpg" class="card-img-top object-fit-cover" alt="SALGA KZN" style="height: 220px;" @error="handleImageError" />
              <div class="card-body p-4 d-flex flex-column h-100 bg-white">
                <div class="mb-2 text-muted small">
                  <i class="bi bi-calendar-event me-1"></i> October 3, 2019 &nbsp;|&nbsp; <i class="bi bi-folder me-1"></i> BLOG, News
                </div>
                <h5 class="card-title fw-bold text-dark lh-sm mb-3">Welcoming Address By Mayor, Cllr P N. Mncwabe, On The Salga Kzn Provincial Members Assembly 2019</h5>
                <p class="card-text text-secondary mb-4 flex-grow-1">
                  Programme Director, Hon Cllr W. Mapena<br>
                  Honourable Premier of KwaZulu-Natal, Hon Mr Sihle Zikalala,<br>
                  KwaZulu-Natal MEC for Cooperative Governance and Traditional Affairs, Hon Mr Sipho Hlomuka...
                </p>
                <a href="#" class="btn btn-outline-primary mt-auto align-self-start">Read More <i class="bi bi-arrow-right ms-1"></i></a>
              </div>
            </div>
          </div>

          <!-- Article 3 -->
          <div class="col-md-6 col-lg-4 d-flex">
            <div class="card border-0 shadow-sm w-100 news-card overflow-hidden">
              <img src="/img/news/news-duzi2sani.jpg" class="card-img-top object-fit-cover" alt="Duzi 2 Sani" style="height: 220px;" @error="handleImageError" />
              <div class="card-body p-4 d-flex flex-column h-100 bg-white">
                <div class="mb-2 text-muted small">
                  <i class="bi bi-calendar-event me-1"></i> September 21, 2019 &nbsp;|&nbsp; <i class="bi bi-folder me-1"></i> BLOG, News
                </div>
                <h5 class="card-title fw-bold text-dark lh-sm mb-3">Appreciation To All Stakeholders Who Contributed To The Success Of Our Annual Tourism Month Event Duzi 2 Sani 2019</h5>
                <p class="card-text text-secondary mb-4 flex-grow-1">
                  Dr Nkosazana Dlamini-Zuma Municipality has long grasped the potential of Travel & Tourism to drive economic growth, create jobs and promote social cohesion and it is Mayor Mncwabe’s ambition to...
                </p>
                <a href="#" class="btn btn-outline-primary mt-auto align-self-start">Read More <i class="bi bi-arrow-right ms-1"></i></a>
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
  name: 'NdzNewsView',
  methods: {
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=News+Image&background=f8f9fa&color=333&size=400';
    }
  }
}
</script>

<style scoped>
.page-wrapper {
  background-color: #f8f9fa;
}

.text-primary {
  color: #00a651 !important;
}

.btn-outline-primary {
  color: #00a651;
  border-color: #00a651;
}

.btn-outline-primary:hover {
  background-color: #00a651;
  color: #fff;
}

.news-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 8px;
}

.news-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 1rem 3rem rgba(0,0,0,.15)!important;
}

.card-title {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;  
  overflow: hidden;
}
</style>

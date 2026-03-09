<template>
  <!-- ====================================================
       HERO SECTION with full-screen slider + quick-links
       ==================================================== -->
  <section class="hero-section">
    <!-- Slides -->
    <div
      v-for="(slide, i) in slides"
      :key="i"
      class="hero-slide"
      :class="{ active: currentSlide === i }"
      :style="{ backgroundImage: slide.bg }"
    ></div>
    <div class="hero-overlay"></div>

    <!-- Hero Content -->
    <div class="hero-content">
      <div class="container">
        <h1>{{ slides[currentSlide].title }}</h1>
        <p class="mt-2">{{ slides[currentSlide].sub }}</p>
      </div>
    </div>

    <!-- Slide dots -->
    <div class="hero-dots">
      <button
        v-for="(slide, i) in slides"
        :key="i"
        class="hero-dot"
        :class="{ active: currentSlide === i }"
        @click="currentSlide = i"
      ></button>
    </div>

    <!-- Quick-link tiles -->
    <div class="hero-quicklinks">
      <div class="container">
        <div class="d-flex">
          <router-link class="quicklink-tile" to="/login">
            <div class="ql-icon"><i class="bi bi-people-fill"></i></div>
            Careers
          </router-link>
          <a class="quicklink-tile" href="#">
            <div class="ql-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            Tenders
          </a>
          <a class="quicklink-tile" href="#">
            <div class="ql-icon"><i class="bi bi-receipt-cutoff"></i></div>
            Quotations
          </a>
          <a class="quicklink-tile" href="#">
            <div class="ql-icon"><i class="bi bi-megaphone-fill"></i></div>
            Notices
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       MAYOR'S OFFICE
       ==================================================== -->
  <section class="mayor-section">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-7">
          <div class="section-label">Mayor's Office</div>
          <h2 class="section-title">His Worship the Mayor</h2>
          <div class="section-divider"></div>
          <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;">
            The Mayor of the NDZ Local Municipality has pledged an unwavering commitment to
            serving all residents with dignity, transparency, and excellence. Having grown up
            in the local area, the Mayor is deeply invested in ensuring that all citizens
            have access to quality services, economic opportunity, and a safe environment.
          </p>
          <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
            With a background in Public Administration and municipal governance, the Mayor
            brings a data-driven and people-focused approach to leadership — championing
            infrastructure delivery, fiscal discipline, and inclusive community development.
          </p>
          <a href="#" class="btn-gov mt-4 d-inline-block">Read More</a>
        </div>
        <div class="col-lg-5 text-center">
          <div style="position:relative;display:inline-block;">
            <img
              src="/mayor.jpg"
              alt="NDZ Mayor"
              style="width:100%;max-width:340px;border-radius:8px;border-top:5px solid var(--accent);box-shadow:0 12px 40px rgba(0,59,122,0.25);display:block;margin:0 auto;"
            />
            <div style="position:absolute;bottom:-12px;left:50%;transform:translateX(-50%);background:var(--accent);color:#fff;padding:0.4rem 1.5rem;border-radius:999px;font-size:0.78rem;font-weight:700;white-space:nowrap;">
              Mayor Cllr Sindisiwe P Msomi
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       TOURISM / KEY PRIORITIES / MISSION — Image band
       ==================================================== -->
  <section class="cards-band">
    <div class="row g-0">
      <div class="col-lg-4" v-for="card in bandCards" :key="card.title">
        <div class="band-card">
          <div class="band-card-bg" :style="{ backgroundImage: `url(${card.image})` }"></div>
          <div class="band-card-overlay"></div>
          <div class="band-card-content">
            <h4>{{ card.title }}</h4>
            <p>{{ card.desc }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       OFFICE OF THE MUNICIPAL MANAGER (blue bg)
       ==================================================== -->
  <section class="manager-section">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-5 text-center">
          <div class="manager-photo">
            <img :src="managerImage" alt="Municipal Manager" />
            <div class="manager-photo-badge">Municipal Manager</div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="section-label">Chief Accounting Officer</div>
          <h2 class="section-title">Office of the Municipal Manager</h2>
          <div class="section-divider"></div>
          <p style="color:rgba(255,255,255,0.78);line-height:1.85;font-size:0.95rem;">
            Headed by the Municipal Manager, the Office of the Municipal Manager is at the heart of
            our municipality's operations, ensuring the effective and efficient management of its
            affairs. As the Accounting Officer, the Municipal Manager oversees core functions of our
            municipality, guiding its strategic direction, managing resources, and ensuring that we
            meet our commitments to our community.
          </p>
          <a href="#" class="btn-gov mt-4 d-inline-block" style="background:var(--accent);border-color:var(--accent);color:#fff;">Read More</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       TOURISM FEATURED GALLERY (reference from screenshot)
       ==================================================== -->
  <section class="tourism-feature" :style="{ backgroundImage: `url(${tourismBackground})` }">
    <div class="tourism-overlay"></div>
    <div class="container">
      <div class="tourism-panel">
        <div class="row g-4">
          <div class="col-lg-5">
            <h2 class="tourism-title">uMngeni Tourism</h2>
            <p class="tourism-copy">
              uMngeni Tourism is about celebrating and promoting the KwaZulu-Natal Midlands, and uplifting the local
              community. We aim to unite and empower local businesses and create unique experiences for visitors to
              enjoy.
            </p>
          </div>
          <div class="col-lg-7">
            <div class="tourism-grid">
              <div
                v-for="(item, idx) in tourismGallery"
                :key="idx"
                class="tourism-thumb"
                :style="{ backgroundImage: `url(${item})` }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       POLICIES & DOCUMENTS
       ==================================================== -->
  <section class="docs-section">
    <div class="container">
      <div class="text-center mb-4">
        <div class="section-label" style="text-align:center;">NDZ Local Municipality</div>
        <h2 class="section-title" style="text-align:center;">Policies &amp; Documents</h2>
        <div class="section-divider mx-auto"></div>
      </div>
      <div class="row g-3">
        <div class="col-md-6 col-lg-3" v-for="doc in policies" :key="doc.title">
          <div class="doc-card">
            <div class="doc-icon"><i :class="doc.icon"></i></div>
            <div>
              <h6>{{ doc.title }}</h6>
              <span>View all documents</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       TENDERS / QUOTATIONS / NOTICES / CAREERS
       ==================================================== -->
 <!--  <section class="procurement-section">
    <div class="container">
      <div class="text-center mb-4">
        <div class="section-label" style="text-align:center;">NDZ Local Municipality</div>
        <h2 class="section-title" style="text-align:center;">Business &amp; Procurement</h2>
        <div class="section-divider mx-auto"></div>
      </div>
      <div class="row g-4">
        <div class="col-sm-6 col-lg-3" v-for="item in procurement" :key="item.title">
          <div class="proc-card">
            <div class="proc-icon">{{ item.icon }}</div>
            <h5>{{ item.title }}</h5>
            <p>{{ item.desc }}</p>
          </div>
        </div>
      </div>
    </div>
  </section> -->

  <!-- ====================================================
       RECENT NEWS
       ==================================================== -->
  <section class="news-section">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
        <div>
          <div class="section-label">NDZ News</div>
          <h2 class="section-title">Recent News</h2>
          <div class="section-divider"></div>
        </div>
        <a href="#" class="btn-gov" style="background:var(--accent);border-color:var(--accent);">View All News</a>
      </div>
      <div class="row g-4">
        <div class="col-md-4" v-for="article in news" :key="article.id">
          <div class="news-card">
            <div class="news-card-image" :style="{ backgroundImage: `url(${article.image})` }"></div>
            <div class="news-card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="news-card-badge">{{ article.readTime }} MIN READ</span>
              </div>
              <h5>{{ article.title }}</h5>
              <p class="news-excerpt">{{ article.excerpt }}</p>
              <p class="news-meta">{{ article.date }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       COMMUNITY / WARD REPRESENTATIVES
       ==================================================== -->
  <section class="community-section">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-6">
          <div class="community-video-placeholder">
            <div style="position:absolute;inset:0;background:linear-gradient(135deg,var(--primary),var(--primary-light)); opacity:0.9;"></div>
            <div style="position:relative;z-index:2;text-align:center;color:#fff;">
              <div class="play-btn mx-auto mb-3">▶</div>
              <p style="font-size:0.85rem;opacity:0.8;">NDZ Local Municipality</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="section-label">NDZ Local Municipality</div>
          <h2 class="section-title" style="font-size:2rem;">Empowering Communities through Dedicated Ward Representatives</h2>
          <div class="section-divider"></div>
          <p style="color:var(--text-mid);font-size:0.95rem;line-height:1.85;">
            The NDZ Local Municipality is equipped with dedicated Ward representatives
            who have pledged their unwavering commitment to serving their constituents
            and addressing a wide range of service delivery matters.
          </p>
          <div class="d-flex flex-wrap gap-3 mt-4">
            <a href="mailto:helpdesk@ndz.gov.za" class="btn-gov">Email Us</a>
            <a href="tel:+27110000000" class="contact-pill">📞 +27 (0) 11 000 0000</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       STATS BAND
       ==================================================== -->
  <section class="stats-band">
    <div class="container">
      <div class="stats-card shadow-soft">
        <div class="row g-4 text-center">
          <div class="col-6 col-md-3 stat-item" v-for="(stat, idx) in stats" :key="stat.label">
            <div class="stat-num">{{ stat.value }}</div>
            <div class="stat-lbl">{{ stat.label }}</div>
            <div v-if="idx !== stats.length - 1" class="stat-divider d-none d-md-block"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'HomeView',
  data() {
    return {
      currentSlide: 0,
      sliderInterval: null,
      slides: [
        {
          bg: 'linear-gradient(120deg, rgba(0,59,122,0.55), rgba(0,118,204,0.3)), url("https://picsum.photos/id/1044/1920/600")',
          title: 'Serving Our Communities with Dedication',
          sub: 'NDZ Local Municipality — Quality services for every resident',
        },
        {
          bg: 'linear-gradient(120deg, rgba(20,90,50,0.55), rgba(39,174,96,0.35)), url("https://picsum.photos/id/1040/1920/600")',
          title: 'Building a Greener, Safer Environment',
          sub: 'Infrastructure · Waste · Environment · Sustainable Development',
        },
        {
          bg: 'linear-gradient(120deg, rgba(26,35,50,0.6), rgba(0,91,170,0.35)), url("https://picsum.photos/seed/ndz-hero3/1600/800")',
          title: 'Transparent and Responsive Government',
          sub: 'Open records, accountability, and citizen-first governance',
        },
      ],
      managerImage: '/manager.jpg',
      bandCards: [
        {
          title: 'Tourism',
          desc: 'A series of spectacular waterfalls surround the area. Celebrate and promote our vibrant KwaZulu-Natal region.',
          image: '/band-tourism.jpg',
        },
        {
          title: 'Key Priorities',
          desc: 'Infrastructure · Jobs · Environment · Transparent Government · Safer Communities.',
          image: '/band-priorities.jpg',
        },
        {
          title: 'Mission',
          desc: 'Through people-focused development, we will improve skills capacity to deliver quality services and inclusive socio-economic growth.',
          image: '/band-mission.jpg',
        },
      ],
      policies: [
        { icon: 'bi bi-cash-coin', title: 'Financial Policies' },
        { icon: 'bi bi-people-fill', title: 'Community Policies' },
        { icon: 'bi bi-archive-fill', title: 'Administrative Policies' },
        { icon: 'bi bi-tools', title: 'Infrastructure Policies' },
      ],
      procurement: [
        { icon: '📑', title: 'Tenders', desc: 'View and submit bids for current municipal tenders' },
        { icon: '🧾', title: 'Quotations', desc: 'Request quotation listings for supply and services' },
        { icon: '📣', title: 'Notices', desc: 'Official public notices and announcements' },
        { icon: '👥', title: 'Careers', desc: 'Job opportunities and employment vacancies' },
      ],
      news: [
        {
          id: 1,
          readTime: 2,
          title: 'Mayor tables R674.8 million annual budget for 2025/2026',
          date: 'May 28, 2025',
          excerpt: 'A people-first budget focused on infrastructure delivery, youth jobs, and safer communities.',
          image: 'https://picsum.photos/seed/ndz-news-1/800/500',
        },
        {
          id: 2,
          readTime: 3,
          title: 'His Worship the Mayor to table the 2025/2026 Annual Budget',
          date: 'May 27, 2025',
          excerpt: 'Council prepares for the annual budget address and public engagement sessions.',
          image: 'https://picsum.photos/seed/ndz-news-2/800/500',
        },
        {
          id: 3,
          readTime: 1,
          title: 'PUBLIC NOTICE – Extension Of Operating Hours: Traffic Department',
          date: 'May 26, 2025',
          excerpt: 'Extended operating hours introduced to improve access to licensing and vehicle services.',
          image: 'https://picsum.photos/seed/ndz-news-3/800/500',
        },
      ],
      stats: [
        { value: '101', label: 'New Employees Since Nov 2021' },
        { value: '13', label: 'Municipality Wards' },
        { value: '1,567', label: 'Area Size in Square km' },
        { value: '109K+', label: 'Residents Population' },
      ],
      tourismBackground: 'https://picsum.photos/seed/ndz-hills/1920/900',
      tourismGallery: [
        'https://picsum.photos/seed/ndz-tour-01/600/360',
        'https://picsum.photos/seed/ndz-tour-02/600/360',
        'https://picsum.photos/seed/ndz-tour-03/600/360',
        'https://picsum.photos/seed/ndz-tour-04/600/360',
        'https://picsum.photos/seed/ndz-tour-05/600/360',
        'https://picsum.photos/seed/ndz-tour-06/600/360',
        'https://picsum.photos/seed/ndz-tour-07/600/360',
        'https://picsum.photos/seed/ndz-tour-08/600/360',
        'https://picsum.photos/seed/ndz-tour-09/600/360',
      ],
    }
  },
  mounted() {
    this.sliderInterval = setInterval(() => {
      this.currentSlide = (this.currentSlide + 1) % this.slides.length
    }, 5000)
  },
  beforeUnmount() {
    clearInterval(this.sliderInterval)
  },
}
</script>

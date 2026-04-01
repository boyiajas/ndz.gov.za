<template>
  <div class="home-view-wrapper">
  <!-- ====================================================
       HERO SECTION with full-screen slider + quick-links
       ==================================================== -->
  <section class="hero-section">
    <video autoplay loop muted playsinline class="hero-video" src="/hero-video/hero-video.mp4"></video>
    <div class="hero-overlay"></div>

    <!-- Hero Content -->
    <div class="hero-content">
      <div class="container">
        <h1>Serving Our Communities with Dedication</h1>
        <p class="mt-2">NDZ Local Municipality — Quality services for every resident</p>
      </div>
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
            Tenders & Quotations
          </a>
           <a class="quicklink-tile" href="#">
            <div class="ql-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            Events
          </a>
           <a class="quicklink-tile" href="#">
            <div class="ql-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            Tourism
          </a>
         <!--  <a class="quicklink-tile" href="#">
            <div class="ql-icon"><i class="bi bi-receipt-cutoff"></i></div>
            Quotations
          </a> -->
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
          <h2 class="section-title">Meet The Mayor Cllr. Sindisiwe P. Msomi</h2>
          <div class="section-divider"></div>
          <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;">
            Councillor Sindisiwe P. “MaJobe” Msomi was elected as the Mayor of Dr Nkosazana Dlamini-Zuma Local Municipality on 22 November 2021. She becomes the second democratically elected Mayor of Harry Gwala District’s second-biggest local municipality since the formation of municipalities in August 2016.She did her primary education in Donnybrook and her secondary education at Nokweja High School, This effectively moulded her budding character around the ideals of liberation and the struggle for freedom and equality for all South Africans.
          </p>
          <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
            A teacher by profession having obtained Secondary Teachers Diploma from Eshowe College of Education a Higher Diploma in Education from the University of Natal in 1999. The Mayor holds a Bachelor’s Degree in Public Administration obtained from the Management College of South Africa i​n 2018.Mayor Msomi holds an academic qualification in Local Government, as a former ward councillor she has served her community.​
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
            NKOSIYEZWE CYPRIAN VEZINkosiyezwe Cyprian Vezi joined Ingwe Local Municipality in January 2013. He has an experience of 20 years in local government, starting from the local government transitional period in 1996.He started off in local government as an ANC Councillor in the UGU Regional Council and worked as a member of the Vulamehlo Standing Committee. He was part of the Change Management Committee that established the Sisonke District Municipality in the run up to the 2000 local government elections.He was a PR Councillor representing the ANC in the Ubuhlebezwe Municipality from 2004 to 2006. In 2006 he was elected ward Councillor for Ubuhlebezwe Municipality and subsequently its Deputy Mayor till 2011.In 2011 he was elected Councillor at the then Sisonke District Municipality, now called Harry Gwala District Municipality. He was appointed Whip and member of the Finance Committee.
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
            <h2 class="tourism-title">NDZ Tourism</h2>
            <p class="tourism-copy">
              Ndz Tourism is about celebrating and promoting the KwaZulu-Natal Midlands, and uplifting the local
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
  </div>
</template>

<script>
export default {
  name: 'HomeView',
  data() {
    return {
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
          title: 'Her Worship the Mayor to table the 2025/2026 Annual Budget',
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
      tourismBackground: '/tourism-1.jpg',
      tourismGallery: [
        '/tourism-1.jpg',
        '/tourism-2.jpg',
        '/tourism-3.jpg',
        '/tourism-4.jpg',
        '/tourism-5.jpg',
        '/tourism-1.jpg',
        '/tourism-2.jpg',
        '/tourism-3.jpg',
        '/tourism-4.jpg',
      ],
    }
  },
}
</script>

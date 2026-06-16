<template>
  <div class="home-view-wrapper">
  <!-- ====================================================
       HERO SECTION with full-screen slider + quick-links
       ==================================================== -->
  <section class="hero-section">
    <video autoplay loop muted playsinline class="hero-video" src="/hero-video/hero-video.mp4"></video>
    <!-- <div class="hero-overlay"></div> -->

    <!-- Hero Content -->
    <!-- <div class="hero-content">
      <div class="container">
        <h1>Serving Our Communities with Dedication</h1>
        <p class="mt-2">Dr Nkosazana Dlamini-Zuma Local Municipality — Quality services for every resident</p>
      </div>
    </div> -->

    <!-- Quick-link tiles -->
    <div class="hero-quicklinks">
      <div class="container">
        <div class="d-flex">
          <router-link class="quicklink-tile" to="/login">
            <div class="ql-icon"><i class="bi bi-people-fill"></i></div>
            Careers
          </router-link>
          <router-link class="quicklink-tile" to="/open-tenders">
            <div class="ql-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            Tenders & Quotations
          </router-link>
          <router-link class="quicklink-tile" to="/scm-database-forms">
            <div class="ql-icon"><i class="bi bi-receipt-cutoff"></i></div>
            Scm Database Forms
          </router-link>
           <router-link class="quicklink-tile" to="/gallery">
            <div class="ql-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            Events
          </router-link>
           <router-link class="quicklink-tile" to="/tourism">
            <div class="ql-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            Tourism
          </router-link>
          
          <router-link class="quicklink-tile" to="/news">
            <div class="ql-icon"><i class="bi bi-megaphone-fill"></i></div>
            Notices
          </router-link>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       MAYOR'S OFFICE
       ==================================================== -->
  <section class="mayor-section position-relative overflow-hidden">
    <div class="mayor-bg-overlay"></div>
    <div class="container position-relative z-1">
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
          <!-- <a href="#" class="btn-gov mt-4 d-inline-block">Read More</a> -->
          <router-link class="btn-gov mt-4 d-inline-block" to="/mayors-office">Read More</router-link>
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
          <div class="section-label">Accounting Officer</div>
          <h2 class="section-title">Office of the Municipal Manager</h2>
          <div class="section-divider"></div>
          <p style="color:rgba(255,255,255,0.78);line-height:1.85;font-size:0.95rem;">
            Nkosiyezwe Cyprian Vezi joined Ingwe Local Municipality in January 2013. He has an experience of 20 years in local government, starting from the local government transitional period in 1996.He started off in local government as an ANC Councillor in the UGU Regional Council and worked as a member of the Vulamehlo Standing Committee. He was part of the Change Management Committee that established the Sisonke District Municipality in the run up to the 2000 local government elections.He was a PR Councillor representing the ANC in the Ubuhlebezwe Municipality from 2004 to 2006. In 2006 he was elected ward Councillor for Ubuhlebezwe Municipality and subsequently its Deputy Mayor till 2011.In 2011 he was elected Councillor at the then Sisonke District Municipality, now called Harry Gwala District Municipality. He was appointed Whip and member of the Finance Committee.
          </p>
         
          <router-link class="btn-gov mt-4 d-inline-block" to="/municipal-manager" style="background:var(--accent);border-color:var(--accent);color:#fff;">Read More</router-link>
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
        <div class="row g-4 align-items-center">
          <div class="col-lg-4">
            <div class="section-label text-white-50" style="margin-bottom: 0.5rem;">Discover</div>
            <h2 class="tourism-title">Events & Gallery</h2>
            <div class="section-divider bg-white" style="opacity: 0.5; margin-left: 0;"></div>
            <p class="tourism-copy mt-4">
              Explore the rich cultural heritage and vibrant community events of the Dr Nkosazana Dlamini-Zuma Local Municipality. Click on any image to view it in full screen and experience our beautiful region.
            </p>
          </div>
          <div class="col-lg-8">
            <div class="events-modern-grid">
              <div
                v-for="(item, idx) in tourismGallery"
                :key="idx"
                class="event-grid-item"
                @click="openEventModal(item)"
              >
                <div class="event-grid-bg" :style="{ backgroundImage: `url(${item})` }"></div>
                <div class="event-hover-overlay">
                  <i class="bi bi-arrows-fullscreen event-zoom-icon"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Event Image Modal -->
    <div v-if="isEventModalOpen" class="event-modal-overlay" @click="closeEventModal">
      <div class="event-modal-content" @click.stop>
        <button class="event-modal-close" @click="closeEventModal">&times;</button>
        <img :src="selectedEventImage" class="event-modal-img" alt="Event Preview" />
      </div>
    </div>
  </section>

  <!-- ====================================================
       POLICIES & DOCUMENTS
       ==================================================== -->
  <!-- <section class="docs-section">
    <div class="container">
      <div class="text-center mb-4">
        <div class="section-label" style="text-align:center;">Dr Nkosazana Dlamini-Zuma Local Municipality</div>
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
  </section> -->

  <!-- ====================================================
       TENDERS / QUOTATIONS / NOTICES / CAREERS
       ==================================================== -->
 <!--  <section class="procurement-section">
    <div class="container">
      <div class="text-center mb-4">
        <div class="section-label" style="text-align:center;">Dr Nkosazana Dlamini-Zuma Local Municipality</div>
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
          <!-- <div class="section-label">NDZ News</div> -->
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
       <!--  <div class="col-lg-6">
          <div class="community-video-placeholder">
            <div style="position:absolute;inset:0;background:linear-gradient(135deg,var(--primary),var(--primary-light)); opacity:0.9;"></div>
            <div style="position:relative;z-index:2;text-align:center;color:#fff;">
              <div class="play-btn mx-auto mb-3">▶</div>
              <p style="font-size:0.85rem;opacity:0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>
            </div>
          </div>
        </div> -->
        <div class="col-lg-12">
          <div class="section-label">Dr Nkosazana Dlamini-Zuma Local Municipality</div>
          <h2 class="section-title" style="font-size:2rem;">Empowering Communities through Dedicated Ward Representatives</h2>
          <div class="section-divider"></div>
          <p style="color:var(--text-mid);font-size:0.95rem;line-height:1.85;">
            The Dr Nkosazana Dlamini-Zuma Local Municipality is equipped with dedicated Ward representatives
            who have pledged their unwavering commitment to serving their constituents
            and addressing a wide range of service delivery matters.
          </p>
          <div class="d-flex flex-wrap gap-3 mt-4">
            <a href="mailto:helpdesk@ndz.gov.za" class="btn-gov">Email Us</a>
            <a href="tel:0398331038" class="contact-pill">📞 039 833 1038</a>
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

  <!-- ====================================================
       EMERGENCY NUMBERS
       ==================================================== -->
  <section class="emergency-section">
    <div class="container">
      <div class="text-center mb-4">
        <div class="section-label" style="text-align:center;">Dr Nkosazana Dlamini-Zuma Local Municipality</div>
        <h2 class="section-title" style="text-align:center;">Emergency Numbers</h2>
        <div class="section-divider mx-auto"></div>
      </div>
      <div class="row g-3">
        <div class="col-12 col-md-6 col-lg-4" v-for="item in emergencyNumbers" :key="item.label">
          <div class="emergency-card h-100">
            <div class="emergency-icon" aria-hidden="true">
              <i class="bi bi-telephone-fill"></i>
            </div>
            <a class="emergency-number" :href="`tel:${item.tel}`">{{ item.display }}</a>
            <div class="emergency-label">{{ item.label }}</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       WEATHER SECTION (PREMIUM DESIGN)
       ==================================================== -->
  <section class="weather-section-premium position-relative overflow-hidden pt-5 pb-0">
    <div class="weather-bg-gradient"></div>
    <div class="container position-relative z-1 mb-5">
      <div class="row justify-content-center">
        <div class="col-xl-12">
          <div class="weather-glass-card">
            <div class="row align-items-center">
              
              <!-- Left Side: Location & Time -->
              <div class="col-lg-5 mb-4 mb-lg-0 border-end-lg weather-info-left">
                <div class="d-flex align-items-center mb-3">
                  <div class="location-pin-icon me-3">
                    <i class="bi bi-geo-alt-fill"></i>
                  </div>
                  <div>
                    <h3 class="fw-bold mb-0 text-dark">Creighton</h3>
                    <p class="text-muted mb-0" style="font-size: 0.95rem;">KwaZulu-Natal, South Africa</p>
                  </div>
                </div>
                <p class="text-muted mb-0 mt-4" style="font-size: 0.9rem; line-height: 1.6;">
                  <i class="bi bi-info-circle me-1" style="color: var(--accent);"></i> Live Weather for Dr Nkosazana Dlamini-Zuma Local Municipality
                </p>
              </div>

              <!-- Right Side: Weather Data -->
              <div class="col-lg-7">
                <div v-if="weatherLoading" class="text-center py-4">
                  <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </div>
                <div v-else-if="weatherError" class="text-center text-danger py-4 bg-white rounded bg-opacity-25">
                  <i class="bi bi-exclamation-triangle-fill me-2"></i> Unable to load weather data.
                </div>
                <div v-else class="d-flex flex-column flex-md-row align-items-center justify-content-around">
                  
                  <!-- Main Temp & Icon -->
                  <div class="d-flex align-items-center mb-4 mb-md-0">
                    <div class="weather-icon-pulse me-4">
                      <i :class="weatherIconClass"></i>
                    </div>
                    <div>
                      <div class="weather-temp-main">{{ weatherData.temperature }}<span class="temp-unit">°C</span></div>
                      <div class="weather-desc">{{ weatherData.description }}</div>
                    </div>
                  </div>

                  <!-- Details -->
                  <div class="weather-details d-flex gap-3">
                    <div class="detail-item text-center">
                      <i class="bi bi-thermometer-high detail-icon"></i>
                      <div class="detail-value">{{ weatherData.high !== null ? weatherData.high + '°' : '--' }}</div>
                      <div class="detail-label">High</div>
                    </div>
                    <div class="detail-item text-center">
                      <i class="bi bi-thermometer-low detail-icon"></i>
                      <div class="detail-value">{{ weatherData.low !== null ? weatherData.low + '°' : '--' }}</div>
                      <div class="detail-label">Low</div>
                    </div>
                    <div class="detail-item text-center">
                      <i class="bi bi-wind detail-icon"></i>
                      <div class="detail-value">{{ weatherData.wind !== null ? weatherData.wind : '--' }} <small>km/h</small></div>
                      <div class="detail-label">Wind</div>
                    </div>
                  </div>

                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Weather Marquee -->
    <div class="weather-marquee-container position-relative z-1" v-if="nearbyWeather.length > 0">
      <div class="marquee-content">
        <div class="marquee-item" v-for="(item, idx) in nearbyWeather" :key="idx">
          <span class="marquee-city">{{ item.city }}</span>
          <i :class="item.iconClass" class="marquee-icon"></i>
          <span class="marquee-temp">{{ item.temperature }}°C</span>
        </div>
        <!-- Duplicate for seamless scroll -->
        <div class="marquee-item" v-for="(item, idx) in nearbyWeather" :key="'dup-'+idx">
          <span class="marquee-city">{{ item.city }}</span>
          <i :class="item.iconClass" class="marquee-icon"></i>
          <span class="marquee-temp">{{ item.temperature }}°C</span>
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
      weatherLoading: true,
      weatherError: false,
      weatherData: {
        temperature: null,
        description: '',
        iconCode: 0,
        high: null,
        low: null,
        wind: null
      },
      nearbyWeather: [],
      managerImage: '/manager.jpg',
      bandCards: [
        {
          title: 'Tourism',
          desc: 'A series of spectacular waterfalls surround the area. Celebrate and promote our vibrant Southern Drakensberg.',
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
      emergencyNumbers: [
        { label: 'Police Services', display: '1011', tel: '1011' },
        { label: 'Rural Metro', display: '+27333450080', tel: '+27333450080' },
        { label: 'Disaster Management', display: '+27837082314', tel: '+27837082314' },
        { label: 'Medical Rescue Services', display: '10177', tel: '10177' },
        { label: 'Fire', display: '+27664698893', tel: '+27664698893' },
        { label: 'Anti-Fraud & Corruption', display: '0800701701', tel: '0800701701' },
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
      isEventModalOpen: false,
      selectedEventImage: null,
    }
  },
  computed: {
    weatherIconClass() {
      return this.getWeatherIcon(this.weatherData.iconCode);
    }
  },
  mounted() {
    this.fetchWeather();
    this.fetchNearbyWeather();
  },
  methods: {
    async fetchWeather() {
      try {
        const lat = -30.0272;
        const lon = 29.83955;
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&daily=temperature_2m_max,temperature_2m_min&timezone=Africa%2FJohannesburg`;
        const res = await fetch(url);
        if (!res.ok) throw new Error('Weather API failed');
        const data = await res.json();
        
        this.weatherData.temperature = data.current_weather.temperature;
        this.weatherData.iconCode = data.current_weather.weathercode;
        this.weatherData.wind = data.current_weather.windspeed;
        
        if (data.daily && data.daily.temperature_2m_max && data.daily.temperature_2m_max.length > 0) {
          this.weatherData.high = data.daily.temperature_2m_max[0];
          this.weatherData.low = data.daily.temperature_2m_min[0];
        }
        
        const code = data.current_weather.weathercode;
        let desc = 'Clear sky';
        if (code === 1) desc = 'Mainly clear';
        else if (code === 2) desc = 'Partly cloudy';
        else if (code === 3) desc = 'Overcast';
        else if (code >= 45 && code <= 48) desc = 'Fog';
        else if (code >= 51 && code <= 55) desc = 'Drizzle';
        else if (code >= 61 && code <= 65) desc = 'Rain';
        else if (code >= 71 && code <= 77) desc = 'Snow';
        else if (code >= 80 && code <= 82) desc = 'Rain showers';
        else if (code >= 95 && code <= 99) desc = 'Thunderstorm';
        
        this.weatherData.description = desc;
      } catch (e) {
        console.error(e);
        this.weatherError = true;
      } finally {
        this.weatherLoading = false;
      }
    },
    async fetchNearbyWeather() {
      try {
        const lats = '-29.95,-29.8,-29.7833,-29.75,-30.15';
        const lons = '29.8667,29.7667,29.5,29.5167,30.0667';
        const cities = ['Donnybrook', 'Bulwer', 'Underberg', 'Himeville', 'Ixopo'];
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lats}&longitude=${lons}&current_weather=true&timezone=Africa%2FJohannesburg`;
        const res = await fetch(url);
        if (!res.ok) throw new Error('Nearby Weather API failed');
        const dataList = await res.json();
        
        this.nearbyWeather = dataList.map((data, idx) => {
          return {
            city: cities[idx],
            temperature: data.current_weather.temperature,
            iconClass: this.getWeatherIcon(data.current_weather.weathercode)
          };
        });
      } catch (e) {
        console.error(e);
      }
    },
    openEventModal(imgSrc) {
      this.selectedEventImage = imgSrc;
      this.isEventModalOpen = true;
      document.body.style.overflow = 'hidden';
    },
    closeEventModal() {
      this.isEventModalOpen = false;
      this.selectedEventImage = null;
      document.body.style.overflow = '';
    },
    getWeatherIcon(code) {
      if (code === 0 || code === 1) return 'bi bi-sun';
      if (code === 2) return 'bi bi-cloud-sun';
      if (code === 3) return 'bi bi-clouds';
      if (code >= 45 && code <= 48) return 'bi bi-cloud-fog';
      if (code >= 51 && code <= 55) return 'bi bi-cloud-drizzle';
      if (code >= 61 && code <= 65) return 'bi bi-cloud-rain';
      if (code >= 71 && code <= 77) return 'bi bi-cloud-snow';
      if (code >= 80 && code <= 82) return 'bi bi-cloud-rain-heavy';
      if (code >= 95 && code <= 99) return 'bi bi-cloud-lightning-rain';
      return 'bi bi-cloud';
    }
  }
}
</script>

<style scoped>
/* Weather Section Premium Styles */
.weather-section-premium {
  background-color: #f8f9fa;
  border-top: 1px solid rgba(0,0,0,0.05);
}

.weather-bg-gradient {
  position: absolute;
  top: -50%;
  left: -10%;
  width: 600px;
  height: 600px;
  background: radial-gradient(circle, rgba(0,166,81,0.08) 0%, rgba(255,255,255,0) 70%);
  border-radius: 50%;
  z-index: 0;
}

.weather-glass-card {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 1);
  border-radius: 24px;
  padding: 2.5rem;
  box-shadow: 0 20px 40px rgba(0,59,122,0.06);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.weather-glass-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 30px 60px rgba(0,166,81,0.1);
  border-color: rgba(255, 255, 255, 1);
}

.border-end-lg {
  border-right: 1px solid rgba(0,0,0,0.08);
}
@media (max-width: 991px) {
  .border-end-lg {
    border-right: none;
    border-bottom: 1px solid rgba(0,0,0,0.08);
    padding-bottom: 1.5rem;
  }
}

.location-pin-icon {
  width: 50px;
  height: 50px;
  border-radius: 14px;
  background: linear-gradient(135deg, var(--accent) 0%, #008a42 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: white;
  box-shadow: 0 10px 20px rgba(0,166,81,0.3);
}

.weather-icon-pulse {
  font-size: 4.5rem;
  color: var(--accent);
  line-height: 1;
  text-shadow: 0 0 30px rgba(0,166,81,0.3);
  animation: float 4s ease-in-out infinite;
}

@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
  100% { transform: translateY(0px); }
}

.weather-temp-main {
  font-size: 3.5rem;
  font-weight: 800;
  color: var(--primary);
  line-height: 1;
  letter-spacing: -1px;
}

.temp-unit {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--text-mid);
  vertical-align: top;
  margin-left: 4px;
}

.weather-desc {
  font-size: 1.1rem;
  font-weight: 500;
  color: var(--accent);
  text-transform: capitalize;
  margin-top: 0.5rem;
  letter-spacing: 0.5px;
}

.detail-item {
  background: rgba(0,0,0,0.02);
  border: 1px solid rgba(0,0,0,0.04);
  border-radius: 16px;
  padding: 1rem;
  min-width: 85px;
  transition: background 0.3s ease;
}

.detail-item:hover {
  background: rgba(0,0,0,0.04);
}

.detail-icon {
  font-size: 1.25rem;
  color: var(--primary);
  opacity: 0.7;
  margin-bottom: 0.5rem;
  display: block;
}

.detail-value {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--primary);
  line-height: 1.2;
}

.detail-value small {
  font-size: 0.75rem;
  color: var(--text-mid);
  font-weight: 500;
}

.detail-label {
  font-size: 0.75rem;
  color: var(--text-mid);
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-top: 0.25rem;
}

/* Weather Marquee */
.weather-marquee-container {
  background: rgba(255, 255, 255, 0.85);
  border-top: 1px solid rgba(0, 0, 0, 0.05);
  padding: 0.75rem 0;
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  overflow: hidden;
  position: relative;
  width: 100%;
}

.marquee-content {
  display: inline-flex;
  gap: 4rem;
  padding-left: 4rem;
  animation: scroll-marquee 20s linear infinite;
  white-space: nowrap;
}

.marquee-item {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  font-weight: 600;
  font-size: 1.05rem;
  color: var(--primary);
}

.marquee-city {
  color: var(--text-mid);
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 1px;
}

.marquee-icon {
  color: var(--accent);
  font-size: 1.25rem;
}

@keyframes scroll-marquee {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

/* Events Section */
.tourism-feature {
  position: relative;
  padding: 6rem 0;
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
}
.tourism-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(0, 59, 122, 0.9) 0%, rgba(0, 166, 81, 0.8) 100%);
}
.tourism-panel {
  position: relative;
  z-index: 2;
  background: rgba(255,255,255,0.05);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 24px;
  padding: 3rem;
  box-shadow: 0 30px 60px rgba(0,0,0,0.3);
}
.tourism-title {
  color: #fff;
  font-size: 3rem;
  font-weight: 800;
  margin-bottom: 1.5rem;
}
.tourism-copy {
  color: rgba(255,255,255,0.8);
  font-size: 1.1rem;
  line-height: 1.8;
}

/* Modern Events Grid */
.events-modern-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  grid-auto-rows: 150px;
  gap: 1rem;
}

.event-grid-item {
  position: relative;
  border-radius: 16px;
  overflow: hidden;
  cursor: pointer;
  box-shadow: 0 10px 20px rgba(0,0,0,0.2);
  transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
}

/* Make every 3rd item span 2 rows and columns */
.event-grid-item:nth-child(3n) {
  grid-column: span 2;
  grid-row: span 2;
}

.event-grid-bg {
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  transition: transform 0.6s ease;
}

.event-grid-item:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 30px rgba(0,0,0,0.4);
}

.event-grid-item:hover .event-grid-bg {
  transform: scale(1.1);
}

.event-hover-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,166,81,0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.event-grid-item:hover .event-hover-overlay {
  opacity: 1;
}

.event-zoom-icon {
  color: white;
  font-size: 2rem;
  transform: scale(0.5);
  transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.event-grid-item:hover .event-zoom-icon {
  transform: scale(1);
}

/* Modal */
.event-modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.9);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  animation: fadeIn 0.3s ease;
}
.event-modal-content {
  position: relative;
  max-width: 90vw;
  max-height: 90vh;
}
.event-modal-img {
  max-width: 100%;
  max-height: 90vh;
  border-radius: 12px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.5);
  animation: zoomIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.event-modal-close {
  position: absolute;
  top: -2.5rem;
  right: -2.5rem;
  background: none;
  border: none;
  color: white;
  font-size: 3rem;
  cursor: pointer;
  transition: color 0.3s ease, transform 0.3s ease;
}
.event-modal-close:hover {
  color: var(--accent);
  transform: rotate(90deg);
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes zoomIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}

@media (max-width: 768px) {
  .tourism-panel {
    padding: 1.5rem;
  }
  .events-modern-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .event-grid-item:nth-child(3n) {
    grid-column: span 1;
    grid-row: span 1;
  }
  .event-modal-close {
    top: -3rem;
    right: 0;
  }
}

/* Mayor Section Faded Background Pattern */
.mayor-section {
  padding: 6rem 0;
  background-color: #fff;
}
.mayor-bg-overlay {
  position: absolute;
  inset: 0;
  background-image: url('/tourism-2.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  opacity: 0.06;
  z-index: 0;
}
</style>

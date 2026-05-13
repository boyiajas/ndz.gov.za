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
        <div class="row g-4">
          <div class="col-lg-5">
            <h2 class="tourism-title">Events</h2>
            <p class="tourism-copy">
              <!-- Ndz Tourism is about celebrating and promoting the KwaZulu-Natal Midlands, and uplifting the local
              community. We aim to unite and empower local businesses and create unique experiences for visitors to
              enjoy. -->
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
  <section class="weather-section-premium position-relative overflow-hidden py-5" :style="{ backgroundImage: `url(${tourismBackground})`, backgroundSize: 'cover', backgroundPosition: 'center', backgroundAttachment: 'fixed' }">
    <div class="weather-bg-overlay"></div>
    <div class="container position-relative z-1">
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
    }
  },
  computed: {
    weatherIconClass() {
      const code = this.weatherData.iconCode;
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
  },
  mounted() {
    this.fetchWeather();
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
    }
  }
}
</script>

<style scoped>
/* Weather Section Premium Styles */
.weather-section-premium {
  border-top: 1px solid rgba(0,0,0,0.05);
}

.weather-bg-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.5);
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
</style>

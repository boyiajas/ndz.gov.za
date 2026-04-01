<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Our Councillors</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">About NDZ</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Councillors</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Gallery Section -->
    <section class="gallery-section py-5" style="background-color: #f8f9fa;">
      <div class="container pb-5">
        <!-- 4 columns grid using v-for -->
        <div class="row g-4">
          <div class="col-md-6 col-lg-3" v-for="(councillor, index) in councillors" :key="index">
            <div class="card h-100 councillor-card border-0">
              <div class="card-img-wrapper">
                <img :src="councillor.image" class="card-img-top" :alt="councillor.name + ' - ' + councillor.role" @error="handleImageError" />
              </div>
              <div class="card-body p-4 text-start">
                <h5 class="card-title fw-bold text-dark mb-1">{{ councillor.name }}</h5>
                <p class="card-subtitle text-primary mb-3" style="font-size: 0.85rem; font-weight: 500;">{{ councillor.role }}</p>
                
                <div class="contact-info mt-4">
                  <div class="contact-item">
                    <i class="bi bi-envelope"></i> {{ councillor.email }}
                  </div>
                  <div class="contact-item">
                    <i class="bi bi-telephone"></i> +27 (0) 11 000 0000
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
  name: 'CouncillorsView',
  data() {
    return {
      councillors: [
        { name: 'Cllr S.s Phoswa', role: 'Speaker', email: 'speaker@ndz.gov.za', image: '/img/councillors/image1.jpg' },
        { name: 'Cllr Sindisiwe P Msomi', role: 'Mayor', email: 'mayor@ndz.gov.za', image: '/img/councillors/image2.jpg' },
        { name: 'Cllr Kholeka Hadebe', role: 'Deputy Mayor', email: 'deputymayor@ndz.gov.za', image: '/img/councillors/image3.jpg' },
        { name: 'Cllr M.m Dlamini', role: 'Ward 8', email: 'mm.dlamini@ndz.gov.za', image: '/img/councillors/image4.jpg' },
        { name: 'Cllr V.a.t Mthembu', role: 'Whip of Council', email: 'whip@ndz.gov.za', image: '/img/councillors/image5.jpg' },
        { name: 'Cllr Sh Mlibeni', role: 'Ward 1', email: 'sh.mlibeni@ndz.gov.za', image: '/img/councillors/image6.jpg' },
        { name: 'Cllr S. Mlotshwa', role: 'PR', email: 's.mlotshwa@ndz.gov.za', image: '/img/councillors/image7.jpg' },
        { name: 'Cllr N.g Dlamini', role: 'Ward 6', email: 'ng.dlamini@ndz.gov.za', image: '/img/councillors/image8.jpg' },
        { name: 'Cllr Mw Mtolo', role: 'Ward 15', email: 'mw.mtolo@ndz.gov.za', image: '/img/councillors/image9.jpg' },
        { name: 'Cllr B Memela', role: 'Ward 13', email: 'b.memela@ndz.gov.za', image: '/img/councillors/image10.jpg' },
        { name: 'Cllr B.b Ntshiza', role: 'Ward 2', email: 'bb.ntshiza@ndz.gov.za', image: '/img/councillors/image11.jpg' },
        { name: 'Cllr S.g Mkhize', role: 'Ward 3', email: 'sg.mkhize@ndz.gov.za', image: '/img/councillors/image12.jpg' },
        { name: 'Cllr L.t Shoba', role: 'Ward 4', email: 'lt.shoba@ndz.gov.za', image: '/img/councillors/image13.jpg' },
        { name: 'Cllr P.n Mdlangathi', role: 'Ward 5', email: 'pn.mdlangathi@ndz.gov.za', image: '/img/councillors/image14.jpg' },
        { name: 'Cllr B.b Khathi', role: 'Ward 9', email: 'bb.khathi@ndz.gov.za', image: '/img/councillors/image15.jpg' },
        { name: 'Cllr M.t.c Bhengu', role: 'Ward 10', email: 'mtc.bhengu@ndz.gov.za', image: '/img/councillors/image16.jpg' },
        { name: 'Cllr X. Zamisa', role: 'Ward 11', email: 'x.zamisa@ndz.gov.za', image: '/img/councillors/image17.jpg' },
        { name: 'Cllr T.e Mdladla', role: 'Ward 14', email: 'te.mdladla@ndz.gov.za', image: '/img/councillors/image18.jpg' },
        { name: 'Cllr N.c Dlamini', role: 'PR', email: 'nc.dlamini@ndz.gov.za', image: '/img/councillors/image19.jpg' },
        { name: 'Cllr S.j Phakathi', role: 'PR', email: 'sj.phakathi@ndz.gov.za', image: '/img/councillors/image20.jpg' },
        { name: 'Cllr N.p Zulu', role: 'PR', email: 'np.zulu@ndz.gov.za', image: '/img/councillors/image21.jpg' },
        { name: 'Cllr D.r Ngcamu', role: 'PR', email: 'dr.ngcamu@ndz.gov.za', image: '/img/councillors/image22.jpg' },
        { name: 'Cllr Aldous-trollope', role: 'PR', email: 'aldous.trollope@ndz.gov.za', image: '/img/councillors/image23.jpg' },
        { name: 'Cllr D. Adams', role: 'PR', email: 'd.adams@ndz.gov.za', image: '/img/councillors/image24.jpg' },
        { name: 'Cllr N. Dlamini', role: 'PR', email: 'n.dlamini@ndz.gov.za', image: '/img/councillors/image25.jpg' },
        { name: 'Cllr P. Mayeza', role: 'PR', email: 'p.mayeza@ndz.gov.za', image: '/img/councillors/image26.jpg' },
        { name: 'Cllr Z. Ndlovu', role: 'PR', email: 'z.ndlovu@ndz.gov.za', image: '/img/councillors/image27.jpg' },
        { name: 'Cllr M.p Mbanjwa', role: 'PR', email: 'mp.mbanjwa@ndz.gov.za', image: '/img/councillors/image28.jpg' },
        { name: 'Cllr P.k Memela', role: 'PR', email: 'pk.memela@ndz.gov.za', image: '/img/councillors/image29.jpg' }
      ]
    }
  },
  methods: {
    handleImageError(event) {
      // Fallback placeholder if image is missing
      event.target.src = 'https://ui-avatars.com/api/?name=C&background=00a651&color=fff&size=200';
    }
  }
}
</script>

<style scoped>
.page-wrapper {
  background-color: #f8f9fa;
}

.councillor-card {
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  border-radius: 4px;
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.councillor-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.card-img-wrapper {
  width: 100%;
  height: 300px;
  overflow: hidden;
  background-color: #ffffff; /* Use white to blend with card if image doesn't fill */
}

.card-img-wrapper .card-img-top {
  width: 100%;
  height: 100%;
  object-fit: contain; /* Prevents image from being cut off */
  object-position: top center; /* Anchors the portrait to the top */
  border-radius: 4px 4px 0 0;
  transition: transform 0.3s ease;
}

.councillor-card:hover .card-img-wrapper .card-img-top {
  transform: scale(1.02); /* More subtle zoom */
}

.card-title {
  font-size: 1.15rem;
  letter-spacing: -0.01em;
  color: #1a2b4c !important;
}

.text-primary {
  color: #0d6efd !important;
}

.contact-item {
  display: flex;
  align-items: center;
  font-size: 0.8rem;
  color: #6c757d;
  margin-bottom: 0.5rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.contact-item:last-child {
  margin-bottom: 0;
}

.contact-item i {
  font-size: 0.9rem;
  margin-right: 0.5rem;
  color: #adb5bd;
}
</style>

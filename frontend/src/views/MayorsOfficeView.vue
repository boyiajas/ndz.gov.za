<template>
  <div class="page-wrapper">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Mayor's Office</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">About Us</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Mayor's Office</li>
          </ol>
        </nav>
      </div>
    </div>
    

    <!-- Mayor Content -->
    <section class="mayor-section" style="padding: 5rem 0;">
      <div class="container">
        <div class="row align-items-start g-5">
          <div class="col-12">
            
            <!-- Floated Image Container -->
            <div class="float-lg-start me-lg-5 mb-4" style="width: 100%; max-width: 340px;">
              <div class="card border-0 shadow-sm text-center manager-card overflow-hidden">
                <div class="card-img-wrapper bg-light">
                  <img :src="profileImageSrc" alt="Mayor Cllr. Sindisiwe P. Msomi" class="card-img-top w-100" style="object-fit: cover; height: 320px; object-position: top center;" @error="handleImageError" />
                </div>
                <div class="card-body p-4 bg-white">
                  <h4 class="card-title fw-bold text-dark mb-1">Cllr. S.P. Msomi</h4>
                  <p class="text-primary fw-medium small mb-0 text-uppercase">Mayor</p>
                </div>
              </div>
            </div>

            <!-- Text Content -->
            <div class="section-label" style="color: var(--accent, #ffb300); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin-bottom: 0.5rem;">Mayor's Office</div>
            <h2 class="section-title" style="font-weight: 800; color: var(--text-dark, #1f2937); margin-bottom: 1.5rem;">Meet The Mayor Cllr. Sindisiwe P. Msomi</h2>
            <div class="section-divider" style="width: 50px; height: 3px; background: var(--accent, #ffb300); margin-bottom: 1.5rem;"></div>
            
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;">
              Councillor Sindisiwe P. “MaJobe” Msomi was elected as the Mayor of Dr Nkosazana Dlamini-Zuma Local Municipality on 22 November 2021. She becomes the second democratically elected Mayor of Harry Gwala District’s second-biggest local municipality since the formation of municipalities in August 2016. She did her primary education in Donnybrook and her secondary education at Nokweja High School. This effectively moulded her budding character around the ideals of liberation and the struggle for freedom and equality for all South Africans.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              A teacher by profession having obtained Secondary Teachers Diploma from Eshowe College of Education and a Higher Diploma in Education from the University of Natal in 1999. The Mayor holds a Bachelor’s Degree in Public Administration obtained from the Management College of South Africa in 2018. Mayor Msomi holds an academic qualification in Local Government; as a former ward councillor she has served her community.
            </p>

            <div class="clearfix"></div>

            <div style="background: #fff; padding: 2rem; border-radius: 8px; border-left: 4px solid var(--primary, #00a651); box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); margin-top: 2rem;">
              <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-dark, #1f2937); margin-bottom: 1rem;">Responsibilities of the Mayor</h3>
              <ul style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem; margin-bottom: 0; padding-left: 1.2rem;">
                <li style="margin-bottom: 0.5rem;">Presides at meetings of the executive committee.</li>
                <li style="margin-bottom: 0.5rem;">Performs the duties, including any ceremonial functions, and exercises the powers delegated to the mayor by the municipal council or the executive committee.</li>
                <li style="margin-bottom: 0.5rem;">Identifies the needs of the municipality, reviews and evaluates those needs in order of priority.</li>
                <li>Recommends to the municipal council strategies, programmes and services to address priority needs through the integrated development plan, and the estimates of revenue and expenditure.</li>
              </ul>
            </div>
            
          </div>
        </div>

      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'MayorsOfficeView',
  data() {
    return {
      profileImageSrc: '/mayor.jpg',
    }
  },
  methods: {
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=S+P+Msomi&background=00a651&color=fff&size=300';
    }
  }
}
</script>

<style scoped>
.text-primary {
  color: #00a651 !important;
}

.manager-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.manager-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 1rem 3rem rgba(0,0,0,.15)!important;
}
</style>

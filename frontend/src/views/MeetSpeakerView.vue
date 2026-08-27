<template>
  <div class="page-wrapper">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Meet The Speaker</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">About Us</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Meet The Speaker</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Speaker Content -->
    <section class="mayor-section" style="padding: 5rem 0;">
      <div class="container">
        <div class="row align-items-start g-5">
          <div class="col-12">
            
            <!-- Floated Image Container -->
            <div class="float-lg-start me-lg-5 mb-4" style="max-width: 340px;">
              <div class="card border-0 shadow-sm text-center manager-card overflow-hidden">
                <div class="card-img-wrapper bg-light">
                  <img :src="profileImageSrc" alt="Speaker Cllr. Sfiso Phoswa" class="card-img-top w-100" style="object-fit: cover; height: 320px; object-position: top center;" @error="handleImageError" />
                </div>
                <div class="card-body p-4 bg-white">
                  <h4 class="card-title fw-bold text-dark mb-1">Cllr. Sfiso Phoswa</h4>
                  <p class="text-primary fw-medium small mb-0 text-uppercase">The Speaker</p>
                </div>
              </div>
            </div>

            <!-- Text Content -->
            <div class="section-label" style="color: var(--accent, #ffb300); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin-bottom: 0.5rem;">Office of the Speaker</div>
            <h2 class="section-title" style="font-weight: 800; color: var(--text-dark, #1f2937); margin-bottom: 1.5rem;">Meet The Speaker – Cllr. Sfiso Phoswa</h2>
            <div class="section-divider" style="width: 50px; height: 3px; background: var(--accent, #ffb300); margin-bottom: 1.5rem;"></div>
            
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;">
              The Constitution and Municipal Structures Act requires that every Council elects a Chairperson, who is called The Speaker.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              The aim of the Office of the Speaker is to structure the two functions of a Municipal Council, i.e. its legislative function and its executive function. The Speaker presides at meetings of the Council and performs the duties and exercises the powers delegated to the Speaker.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              A resident of Gqumeni in ward 7 of Dr Nkosazana Dlamini-Zuma Municipality since birth, Cllr Sifiso Sydney Phoswa believes in using his position to influence positive social change by improving the livelihoods of the people of this municipality. His involvement in politics and youth development.
            </p>

            <div class="clearfix"></div>

            <div style="background: #fff; padding: 2rem; border-radius: 8px; border-left: 4px solid var(--primary, #00a651); box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); margin-top: 2rem;">
              <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-dark, #1f2937); margin-bottom: 1rem;">These duties include:</h3>
              <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem; margin-bottom: 0.5rem;">Ensuring that Council meets at least quarterly:</p>
              <ul style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem; margin-bottom: 0; padding-left: 1.2rem;">
                <li style="margin-bottom: 0.5rem;">Maintaining order during meetings</li>
                <li style="margin-bottom: 0.5rem;">Ensuring compliance in the Council and Council committees with the Code of Conduct of Councillors</li>
                <li>Ensuring that Council meetings are conducted in accordance with the rules and orders of the Council.</li>
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
  name: 'MeetSpeakerView',
  data() {
    return {
      profileImageSrc: '/speaker.jpg',
    }
  },
  methods: {
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=S+Phoswa&background=00a651&color=fff&size=300';
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

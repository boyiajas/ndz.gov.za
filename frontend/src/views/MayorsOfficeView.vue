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
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">About NDZ</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Mayor's Office</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Mayor Content -->
    <section class="mayor-section" style="padding: 5rem 0;">
      <div class="container">
        <div class="row align-items-center g-5">
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
          <div class="col-lg-7">
            <div class="section-label" style="color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin-bottom: 0.5rem;">Mayor's Office</div>
            <h2 class="section-title" style="font-weight: 800; color: var(--text-dark); margin-bottom: 1.5rem;">Meet The Mayor Cllr. Sindisiwe P. Msomi</h2>
            <div class="section-divider" style="width: 50px; height: 3px; background: var(--accent); margin-bottom: 1.5rem;"></div>
            <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;">
              Councillor Sindisiwe P. “MaJobe” Msomi was elected as the Mayor of Dr Nkosazana Dlamini-Zuma Local Municipality on 22 November 2021. She becomes the second democratically elected Mayor of Harry Gwala District’s second-biggest local municipality since the formation of municipalities in August 2016. She did her primary education in Donnybrook and her secondary education at Nokweja High School. This effectively moulded her budding character around the ideals of liberation and the struggle for freedom and equality for all South Africans.
            </p>
            <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              A teacher by profession having obtained Secondary Teachers Diploma from Eshowe College of Education and a Higher Diploma in Education from the University of Natal in 1999. The Mayor holds a Bachelor’s Degree in Public Administration obtained from the Management College of South Africa in 2018. Mayor Msomi holds an academic qualification in Local Government; as a former ward councillor she has served her community.
            </p>
          </div>
        </div>

        <div class="row" style="margin-top: 5rem;">
          <div class="col-12">
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 8px; border-left: 4px solid var(--accent);">
              <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-dark); margin-bottom: 1rem;">Responsibilities of the Mayor</h3>
              <ul style="color:var(--text-mid);line-height:1.85;font-size:0.95rem; margin-bottom: 0; padding-left: 1.2rem;">
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
  name: 'MayorsOfficeView'
}
</script>

<style scoped>
/* Scoped styles */
</style>

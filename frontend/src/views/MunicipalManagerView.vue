<template>
  <div class="page-wrapper">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Municipal Manager</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">About NDZ</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Municipal Manager</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Manager Content -->
    <section class="mayor-section" style="padding: 5rem 0;">
      <div class="container">
        <div class="row align-items-center g-5">
          <div class="col-lg-5 text-center">
            <div style="position:relative;display:inline-block;">
              <img
                src="/manager.jpg"
                alt="Municipal Manager"
                style="width:100%;max-width:340px;border-radius:8px;border-top:5px solid var(--accent);box-shadow:0 12px 40px rgba(0,59,122,0.25);display:block;margin:0 auto;"
              />
              <div style="position:absolute;bottom:-12px;left:50%;transform:translateX(-50%);background:var(--accent);color:#fff;padding:0.4rem 1.5rem;border-radius:999px;font-size:0.78rem;font-weight:700;white-space:nowrap;">
                Mr. Nkosiyezwe C. Vezi
              </div>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="section-label" style="color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin-bottom: 0.5rem;">Chief Accounting Officer</div>
            <h2 class="section-title" style="font-weight: 800; color: var(--text-dark); margin-bottom: 1.5rem;">Office of the Municipal Manager</h2>
            <div class="section-divider" style="width: 50px; height: 3px; background: var(--accent); margin-bottom: 1.5rem;"></div>
            <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;">
              <strong>Nkosiyezwe Cyprian Vezi</strong> joined Ingwe Local Municipality in January 2013. He has an experience of 20 years in local government, starting from the local government transitional period in 1996. He started off in local government as an ANC Councillor in the UGU Regional Council and worked as a member of the Vulamehlo Standing Committee.
            </p>
            <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              He was part of the Change Management Committee that established the Sisonke District Municipality in the run up to the 2000 local government elections. He was a PR Councillor representing the ANC in the Ubuhlebezwe Municipality from 2004 to 2006. In 2006 he was elected ward Councillor for Ubuhlebezwe Municipality and subsequently its Deputy Mayor till 2011.
            </p>
             <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              In 2011 he was elected Councillor at the then Sisonke District Municipality, now called Harry Gwala District Municipality. He was appointed Whip and member of the Finance Committee.
            </p>
          </div>
        </div>

        <div class="row" style="margin-top: 5rem;">
          <div class="col-12">
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 8px; border-left: 4px solid var(--accent);">
              <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-dark); margin-bottom: 1rem;">Responsibilities of the Municipal Manager</h3>
              <ul style="color:var(--text-mid);line-height:1.85;font-size:0.95rem; margin-bottom: 0; padding-left: 1.2rem;">
                <li style="margin-bottom: 0.5rem;">The formation and development of an economical, effective, efficient and accountable administration.</li>
                <li style="margin-bottom: 0.5rem;">Ensures that the municipality's administration is responsive to the needs of the local community.</li>
                <li style="margin-bottom: 0.5rem;">Manages the provision of services to the local community in a sustainable and equitable manner.</li>
                <li>Provides guidance and advice on compliance with applicable legislation to political structures, political office bearers, and officials of the municipality.</li>
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
  name: 'MunicipalManagerView'
}
</script>

<style scoped>
/* Scoped styles */
</style>

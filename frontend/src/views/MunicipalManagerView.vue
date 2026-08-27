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
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">About Us</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Municipal Manager</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Manager Content -->
    <section class="mayor-section" style="padding: 5rem 0;">
      <div class="container">
        <div class="row align-items-start g-5">
          <div class="col-12">
            <!-- Floated Image Container -->
            <div class="float-lg-start me-lg-5 mb-4" style="max-width: 340px;">
              <div class="card border-0 shadow-sm text-center manager-card overflow-hidden">
                <div class="card-img-wrapper bg-light">
                  <img :src="profileImageSrc" alt="Mr Nkosiyezwe C. Vezi" class="card-img-top w-100" style="object-fit: cover; height: 320px; object-position: top center;" @error="handleImageError" />
                </div>
                <div class="card-body p-4 bg-white">
                  <h4 class="card-title fw-bold text-dark mb-1">Mr N.C. Vezi</h4>
                  <p class="text-primary fw-medium small mb-0 text-uppercase">Municipal Manager</p>
                </div>
              </div>
            </div>

            <!-- Text Content -->
            <div class="section-label" style="color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin-bottom: 0.5rem;">Accounting Officer</div>
            <h2 class="section-title" style="font-weight: 800; color: var(--text-dark); margin-bottom: 1.5rem;">Office of the Municipal Manager</h2>
            <div class="section-divider" style="width: 50px; height: 3px; background: var(--accent); margin-bottom: 1.5rem;"></div>
            
            <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;">
              <strong>Mr Nkosiyezwe Cyprian Vezi</strong> is an accomplished local government practitioner and senior municipal executive with more than 20 years of experience in local government and over 15 years in senior leadership and management positions. He has served as Municipal Manager of Dr Nkosazana Dlamini Zuma Local Municipality since 2016, following his tenure as Municipal Manager of Ingwe Local Municipality from 2013 to 2016. His extensive public-sector career also includes leadership and governance roles as Deputy Mayor and Councillor at Ubuhlebezwe Local Municipality, Councillor at Harry Gwala District Municipality, and Deputy Chief Education Specialist within the Sisonke District. His career reflects extensive experience in municipal administration, strategic leadership, governance, financial management, service delivery, institutional development and cooperative governance.
            </p>
            <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              Mr Vezi holds a strong academic and professional background in governance, public administration and local government. His qualifications include a master’s degree in governance and political Transformation, a master’s degree in public administration, a Postgraduate Diploma in Governance and Political Transformation, the Municipal Finance Management Programme, and qualifications in Local Government Law and Municipal Administration and Specialist Local Government Management. He is currently pursuing a PhD in Government and Political Transformation at the University of the Free State. His leadership is underpinned by a strong commitment to ethical governance, sound financial management, strategic planning, service delivery, institutional performance and professional local government administration. During his tenure in municipal leadership, the municipalities under his management have achieved a number of unqualified audit opinions, reflecting his continued focus on governance, accountability and financial management.
            </p>
             <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              The Office of the Municipal Manager serves as the administrative head of the Dr Nkosazana Dlamini Zuma Local Municipality and is responsible for the overall coordination and strategic management of the municipality. The Municipal Manager provides leadership to ensure that municipal operations are aligned with legislative requirements, policy directives, and service delivery commitments outlined in the Integrated Development Plan (IDP).
            </p>
            <p style="color:var(--text-mid);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              In his current role, Mr Vezi oversees and provides strategic direction to five distinct operational units, each headed by a manager who reports directly to him:
            </p>
            <ul style="color:var(--text-mid);line-height:1.85;font-size:0.95rem; margin-top:0.5rem; padding-left: 1.2rem; margin-bottom: 2rem;">
              <li>Strategic Support Services (IDP &amp; PMS)</li>
              <li>Communications</li>
              <li>Public Participation</li>
              <li>Internal Audit &amp; Risk Management</li>
              <li>Intergovernmental Relations</li>
            </ul>
            
            <!-- Clear floats after the text block just in case it doesn't clear the image naturally -->
            <div class="clearfix"></div>
          </div>
        </div>

        <div class="row" style="margin-top: 5rem;">
          <div class="col-12">
            <div style="background: #f8f9fa; padding: 2rem; border-radius: 8px; border-left: 4px solid var(--accent);">
              <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-dark); margin-bottom: 1rem;">Core Functions of the Role</h3>
              <ul style="color:var(--text-mid);line-height:1.85;font-size:0.95rem; margin-bottom: 0; padding-left: 1.2rem;">
                <li style="margin-bottom: 0.5rem;"><strong>Strategic Support Services (IDP &amp; PMS):</strong> Oversees the development, implementation, and monitoring of the IDP and Performance Management System to ensure integrated planning and performance accountability across departments.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Communications:</strong> Manages the municipality’s internal and external communication strategies, public relations, media liaison, and the dissemination of accurate and timely information to the public.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Public Participation:</strong> Facilitates community involvement in municipal decision-making processes through stakeholder engagements, ward-based planning, and public consultation forums.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Internal Audit &amp; Risk Management:</strong> Ensures that internal controls are in place and effective, identifies risks, and monitors compliance and governance practices to promote accountability and institutional integrity.</li>
                <li><strong>Intergovernmental Relations (IGR):</strong> Coordinates collaborative efforts with other spheres of government, public entities, and stakeholders to enhance service delivery and alignment with national and provincial programmes.</li>
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
  name: 'MunicipalManagerView',
  data() {
    return {
      profileImageSrc: '/manager.jpg',
    }
  },
  methods: {
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=N+C+Vezi&background=00a651&color=fff&size=300';
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

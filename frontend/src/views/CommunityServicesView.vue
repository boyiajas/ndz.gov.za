<template>
  <div class="page-wrapper bg-light">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Community Services</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">Department</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Community Services</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Main Content Section -->
    <section class="department-section py-5">
      <div class="container pb-5">
        <div class="row align-items-start g-5">
          <div class="col-12">
            
            <!-- Floated Image Container -->
            <div class="float-lg-start me-lg-5 mb-4" style="max-width: 340px;">
              <div class="card border-0 shadow-sm text-center manager-card overflow-hidden">
                <div class="card-img-wrapper bg-light">
                  <img :src="profileImageSrc" alt="Mrs. Kefuwe Gloria Mtolo" class="card-img-top w-100" style="object-fit: cover; height: 320px; object-position: top center;" @error="handleImageError" />
                </div>
                <div class="card-body p-4 bg-white">
                  <h4 class="card-title fw-bold text-dark mb-1">Mrs. Kefuwe Gloria Mtolo</h4>
                  <p class="text-primary fw-medium small mb-0 text-uppercase">Senior Manager: Community and Social Services</p>
                </div>
              </div>
            </div>

            <!-- Text Content -->
            <div class="section-label" style="color: var(--accent, #ffb300); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin-bottom: 0.5rem;">Senior Management</div>
            <h2 class="section-title" style="font-weight: 800; color: var(--text-dark, #1f2937); margin-bottom: 1.5rem;">Community and Social Services Department</h2>
            <div class="section-divider" style="width: 50px; height: 3px; background: var(--accent, #ffb300); margin-bottom: 1.5rem;"></div>
            
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;">
              <strong>Mrs. Mtolo</strong> is a Social Science graduate of the University of KwaZulu-Natal. She is a dedicated, results-driven professional with more than 20 years of experience in local government, specialising in community development and strategic planning.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              Her areas of expertise include HIV/AIDS counselling, social facilitation, municipal Integrated Development Planning (IDP), performance management, policy development and public administration. She also holds a Diploma in Project Management from Varsity College and has completed studies in Monitoring and Evaluation through the University of the Witwatersrand to strengthen her performance-management capabilities.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              Throughout her career, Mrs. Mtolo has worked with various stakeholders across all three spheres of government to improve service delivery. She has contributed to the development of practical solutions and initiatives that respond to community needs and promote sustainable development. Mrs. Mtolo has also completed Ethics for Local Government training through the National School of Government. This training has strengthened her commitment to delivering high-quality services in a reliable, accountable and ethical manner.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              To enhance her ability to translate the municipality’s strategic objectives, as outlined in the IDP, into effective departmental policies and procedures, Mrs. Mtolo completed studies in Policy Formulation and Implementation. This has strengthened her ability to identify service-delivery gaps and develop policies that respond to community needs while improving governance, accountability and risk management.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              The Community and Social Services Department of Dr Nkosazana Dlamini Zuma Local Municipality is committed to promoting social well-being, preserving cultural heritage and improving the quality of life of all residents through accessible, inclusive and sustainable community-based services. The Department plays an important role in advancing social cohesion, community participation and empowerment through its various operational units.
            </p>
            <p style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem;margin-top:1rem;">
              Mrs. Mtolo provides strategic leadership, oversight and direction to eight distinct operational units, each headed by a manager who reports directly to her:
            </p>
            <ul style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem; margin-top:0.5rem; padding-left: 1.2rem; margin-bottom: 2rem;">
              <li>Libraries Services Unit</li>
              <li>Community Halls and Facilities Management Unit</li>
              <li>Cemeteries and Burial Services Unit</li>
              <li>Arts, Culture, and Heritage Unit</li>
              <li>Youth Development Services Unit</li>
              <li>Gender, Elderly, and Disability Services Unit</li>
              <li>Sports and Recreation Unit</li>
              <li>Social Development and Welfare Coordination Unit</li>
            </ul>

            <div class="clearfix"></div>

            <div style="background: #fff; padding: 2rem; border-radius: 8px; border-left: 4px solid var(--primary, #00a651); box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); margin-top: 2rem;">
              <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-dark, #1f2937); margin-bottom: 1rem;">Core Functions of the Role</h3>
              <ul style="color:var(--text-mid, #4b5563);line-height:1.85;font-size:0.95rem; margin-bottom: 0; padding-left: 1.2rem;">
                <li style="margin-bottom: 0.5rem;"><strong>Libraries Services Unit:</strong> Managing municipal libraries and promoting literacy and lifelong learning. Providing access to books, digital resources, newspapers, and educational materials. Facilitating reading programmes, book clubs, and school outreach. Supporting school learners and students with study materials and quiet study spaces. Maintaining library facilities and updating inventory.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Community Halls and Facilities Management Unit:</strong> Overseeing the maintenance and bookings of community halls and municipal venues. Facilitating access to community infrastructure for social, cultural, and educational events. Ensuring compliance with safety, cleanliness, and usage regulations. Supporting community events and public meetings through logistical coordination.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Cemeteries and Burial Services Unit:</strong> Managing and maintaining municipal cemeteries in a dignified and environmentally sustainable manner. Allocating burial plots and keeping updated burial registers and cemetery records. Supporting families in coordinating municipal funeral services. Ensuring compliance with health, environmental, and burial regulations.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Arts, Culture, and Heritage Unit:</strong> Promoting cultural development, artistic expression, and heritage preservation. Supporting local artists, crafters, and cultural groups through training and event participation. Coordinating cultural festivals, heritage month programmes, and arts exhibitions. Identifying, preserving, and promoting local heritage sites and historical landmarks.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Youth Development Services Unit:</strong> Designing and implementing youth empowerment programmes. Facilitating access to skills development, education, and employment opportunities. Coordinating youth dialogues, awareness campaigns, and leadership forums. Working in partnership with provincial departments and stakeholders to support youth advancement.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Gender, Elderly, and Disability Services Unit:</strong> Promoting the inclusion and protection of vulnerable groups including women, older persons, and people living with disabilities. Coordinating awareness campaigns and support programmes that address issues such as gender-based violence, elder abuse, and disability rights. Partnering with NGOs and relevant government departments for social relief and advocacy. Supporting the establishment and functioning of representative forums.</li>
                <li style="margin-bottom: 0.5rem;"><strong>Sports and Recreation Unit:</strong> Promoting healthy lifestyles through the provision and management of sports and recreational facilities. Coordinating municipal sports tournaments and community wellness campaigns. Supporting the development of local sports talent through youth and school partnerships. Maintaining sports grounds, parks, and recreational spaces.</li>
                <li><strong>Social Development and Welfare Coordination Unit:</strong> Liaising with social welfare agencies and non-profit organisations to coordinate social relief and development efforts. Supporting indigent households through registration and referral to appropriate government services. Facilitating poverty alleviation and food security initiatives. Monitoring social challenges and promoting interventions in collaboration with provincial departments.</li>
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
  name: 'CommunityServicesView',
  data() {
    return {
      profileImageSrc: '/img/departments/Photo.jpeg',
    }
  },
  methods: {
    handleImageError(event) {
      event.target.src = 'https://ui-avatars.com/api/?name=K+G+Mtolo&background=00a651&color=fff&size=300';
    }
  }
}
</script>

<style scoped>
.page-wrapper {
  background-color: #f8f9fa;
}

.text-primary {
  color: #00a651 !important; /* NDZ Primary Brand Color */
}

.border-primary {
  border-color: #00a651 !important;
}

.focus-areas-list .icon-box {
  width: 50px;
  height: 50px;
  background-color: rgba(0, 166, 81, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.manager-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.manager-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 1rem 3rem rgba(0,0,0,.15)!important;
}
</style>

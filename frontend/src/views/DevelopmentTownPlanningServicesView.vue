<template>
  <div class="page-wrapper bg-light development-town-planning-view">
    <div class="page-header department-hero">
      <div class="container">
        <p class="hero-kicker">Department</p>
        <h1>Development and Town Planning Services</h1>
        <p>Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <nav aria-label="breadcrumb" class="department-breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><router-link to="/">Home</router-link></li>
            <li class="breadcrumb-item"><span>Departments</span></li>
            <li class="breadcrumb-item active" aria-current="page">Development and Town Planning Services</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="department-ribbon" aria-hidden="true"></div>

    <section class="department-section py-5">
      <div class="container pb-5">
        <div class="row g-4 mb-4">
          <div class="col-lg-8">
            <article class="content-panel h-100">
              <span class="section-label">Overview</span>
              <h2>Development and Town Planning Services Department</h2>
              <p>
                The Development and Town Planning Services Department is responsible for coordinating and facilitating sustainable spatial, infrastructure, economic and tourism development within the Dr Nkosazana Dlamini-Zuma Local Municipality.
              </p>
              <p>
                The Department consists of five key units: Town Planning, Building Control, Business Licensing, Local Economic Development, and Tourism. Through these units, the Department supports orderly land development, safe and compliant buildings, regulated business activities, local economic growth, job creation, and the development and promotion of tourism.
              </p>
              <p>
                The Department operates within the framework of applicable national legislation, municipal by-laws, policies, strategies and planning instruments.
              </p>
            </article>
          </div>

          <div class="col-lg-4">
            <aside class="quick-panel h-100">
              <span class="quick-kicker">Key Units</span>
              <h3>Department Structure</h3>
              <ul>
                <li v-for="unit in units" :key="unit.title">
                  <span>{{ unit.icon }}</span>
                  {{ unit.title }}
                </li>
              </ul>
            </aside>
          </div>
        </div>

        <article class="content-panel mb-4">
          <span class="section-label">Legislative Framework</span>
          <h2>Guiding legislation and policies</h2>
          <div class="legislation-grid">
            <div v-for="item in legislativeFramework" :key="item" class="legislation-card">
              <i class="bi bi-check2-circle"></i>
              <span>{{ item }}</span>
            </div>
          </div>
        </article>

        <div class="section-heading">
          <span class="section-label">Core Functions</span>
          <h2>Service units and responsibilities</h2>
          <p>
            These units translate municipal development priorities into practical spatial, economic, regulatory and tourism interventions.
          </p>
        </div>

        <div class="unit-grid">
          <article v-for="unit in units" :key="unit.title" class="unit-card">
            <div class="unit-card-head">
              <span>{{ unit.icon }}</span>
              <h3>{{ unit.title }}</h3>
            </div>
            <p v-for="paragraph in unit.paragraphs" :key="paragraph">{{ paragraph }}</p>
            <ul>
              <li v-for="item in unit.functions" :key="item" v-html="item"></li>
            </ul>
          </article>
        </div>

        <article class="closing-panel mt-4">
          <h2>Department Commitment</h2>
          <p>
            The Development and Town Planning Services Department is committed to creating an environment that supports sustainable spatial development, safe communities, compliant businesses, local economic growth and a thriving tourism sector.
          </p>
          <p>
            Through integrated planning, effective regulation, stakeholder collaboration and responsive municipal services, the Department contributes to the Municipality's broader vision of building inclusive, resilient and economically active communities.
          </p>
        </article>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'DevelopmentTownPlanningServicesView',
  data() {
    return {
      legislativeFramework: [
        'Spatial Planning and Land Use Management Act 16 of 2013 (SPLUMA)',
        'National Building Regulations and Building Standards Act 103 of 1977',
        'Businesses Act 71 of 1991',
        'National Tourism Act 3 of 2014',
        'Relevant municipal by-laws, policies and planning frameworks',
      ],
      units: [
        {
          title: 'Town Planning',
          icon: '01',
          paragraphs: [
            'The Town Planning Unit is responsible for promoting orderly, sustainable and integrated spatial development across the municipal area.',
            "The Unit plays an important role in translating the strategic objectives of the Municipality's Integrated Development Plan and spatial development frameworks into practical spatial development interventions.",
          ],
          functions: [
            'Preparation and review of the Municipal Spatial Development Framework (MSDF).',
            'Preparation and review of lower-order spatial plans, including Local Area Plans and Precinct Plans.',
            'Preparation, review and maintenance of the municipal Land Use Scheme.',
            'Providing professional spatial planning advice on land development and land management.',
            'Identifying land suitable for future development.',
            'Processing and assessing Land Use Development Applications.',
            'Responding to general land-use and development enquiries.',
            'Enforcement of applicable SPLUMA requirements and municipal by-laws.',
            'Providing and maintaining Geographic Information Systems (GIS) to support planning, decision-making and spatial analysis.',
          ],
        },
        {
          title: 'Building Control',
          icon: '02',
          paragraphs: [
            'The Building Control Unit promotes safe, compliant and sustainable building development throughout the municipal area.',
          ],
          functions: [
            'Responding to general building plan enquiries.',
            'Receiving and scrutinising building plans and related documentation.',
            'Assessing building plans in accordance with the National Building Regulations and Building Standards Act and applicable municipal planning requirements.',
            'Conducting property inspections.',
            'Investigating complaints relating to building and plumbing-related problems.',
            'Processing and maintaining building plan records.',
            'Archiving new and historical building plans through the building plans management system.',
            'Making recommendations regarding the approval of building plans, specifications and supporting documentation.',
            'Enforcing applicable building regulations and standards.',
            'Preparing contravention reports for referral to law enforcement authorities or municipal legal representatives where necessary.',
            'Facilitating the prosecution of contraventions in accordance with applicable legislation.',
            'Issuing Occupancy Certificates in accordance with the National Building Regulations.',
          ],
        },
        {
          title: 'Local Economic Development',
          icon: '03',
          paragraphs: [
            'The Local Economic Development (LED) Unit is focused on creating an enabling environment for inclusive local economic growth, entrepreneurship, investment and employment within the municipal area.',
            "The LED Unit is guided by national and provincial economic development policies and programmes, as well as the Municipality's IDP and Economic Development Strategy.",
            'The Municipality recognises the important role played by small businesses in strengthening local economies. The LED Unit therefore works to facilitate access to government support initiatives while contributing to the creation of an enabling environment and infrastructure in which local businesses and SMMEs can grow and thrive.',
          ],
          functions: [
            'Business development and investment promotion.',
            'Small, Medium and Micro Enterprise (SMME) development.',
            'Development and maintenance of an SMME database.',
            'Development and implementation of poverty alleviation initiatives.',
            'Promotion of local economic development.',
            'Supporting initiatives aimed at job creation and economic transformation.',
            'Facilitating economic interventions that respond to local community needs.',
            'Supporting local businesses and entrepreneurs to access opportunities, resources and relevant government programmes.',
            'Facilitating partnerships between government, the private sector, communities and other stakeholders.',
          ],
        },
        {
          title: 'Business Licensing',
          icon: '04',
          paragraphs: [
            'The Business Licensing Unit is responsible for regulating applicable business activities and ensuring compliance with relevant legislation and municipal requirements.',
            'The Unit contributes to a regulated and compliant business environment that protects consumers while supporting legitimate business activity within the Municipality.',
          ],
          functions: [
            'Providing business licence application forms and information.',
            'Receiving and processing business licence applications.',
            'Circulating applications to relevant municipal departments and stakeholders for assessment and approval.',
            'Conducting inspections of business premises.',
            'Monitoring compliance with applicable business regulations.',
            'Issuing contravention notices where businesses are found to be operating contrary to applicable requirements.',
            'Participating in joint compliance and enforcement operations.',
            'Conducting raids and enforcement operations where required.',
            'Facilitating the removal and disposal of expired or prohibited goods in accordance with applicable procedures and legislation.',
          ],
        },
        {
          title: 'Tourism',
          icon: '05',
          paragraphs: [
            'The Tourism Unit is committed to unlocking and promoting the tourism potential of the Dr Nkosazana Dlamini-Zuma Municipal area by showcasing its natural beauty, cultural heritage, historical significance and unique local experiences.',
            'The Unit works with communities, tourism businesses, government institutions and other stakeholders to position the Municipality as an attractive destination for visitors.',
            'Through its tourism programmes and partnerships, the Municipality seeks to ensure that tourism contributes meaningfully to local economic development, employment creation, SMME growth and community participation.',
          ],
          functions: [
            '<strong>Destination Promotion:</strong> Promoting the Municipality as a destination for eco-tourism, cultural experiences, heritage exploration, outdoor recreation and authentic rural tourism.',
            '<strong>Support for Local Tourism Enterprise:</strong> Supporting local tourism SMMEs, accommodation establishments, tour operators, guides and hospitality businesses through capacity building, exposure, networking and access to available opportunities and resources.',
            '<strong>Event-Driven Tourism:</strong> Supporting and coordinating tourism-related events, cultural festivals, heritage activities, outdoor adventures and other initiatives capable of attracting visitors and stimulating local economic activity.',
            '<strong>Partnership Development:</strong> Building partnerships with local communities, tourism businesses, private-sector stakeholders, provincial tourism structures and other strategic partners to strengthen the local tourism value chain.',
            '<strong>Tourism Infrastructure and Experience Development:</strong> Supporting initiatives aimed at improving tourism facilities, signage, visitor information and tourism experiences to create a safe, welcoming and memorable environment for visitors.',
          ],
        },
      ],
    }
  },
}
</script>

<style scoped>
.page-wrapper {
  background-color: #f8f9fa;
}

.department-hero {
  background:
    linear-gradient(135deg, rgba(15, 107, 59, 0.94), rgba(31, 156, 88, 0.9)),
    url('/img/band-tourism.jpg') center/cover;
  color: #ffffff;
  padding: 4rem 0;
  text-align: left;
}

.hero-kicker {
  margin: 0 0 0.5rem;
  color: rgba(255, 255, 255, 0.72);
  font-size: 0.85rem;
  font-weight: 800;
  letter-spacing: 0.14em;
  text-transform: uppercase;
}

.department-hero h1 {
  margin: 0;
  max-width: 850px;
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 800;
  line-height: 1.1;
}

.department-hero p {
  margin-top: 0.7rem;
  font-size: 1.05rem;
  opacity: 0.9;
}

.department-breadcrumb {
  margin-top: 1.5rem;
  display: flex;
}

.department-breadcrumb .breadcrumb {
  margin: 0;
  font-size: 0.95rem;
  background: rgba(255, 255, 255, 0.1);
  padding: 0.5rem 1rem;
  border-radius: 50px;
}

.department-breadcrumb a,
.department-breadcrumb span,
.department-breadcrumb .active {
  color: #ffffff;
}

.department-ribbon {
  height: 18px;
  background: repeating-linear-gradient(
    135deg,
    var(--accent) 0 10px,
    #ffffff 10px 20px,
    var(--primary) 20px 30px,
    #ffffff 30px 40px
  );
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.content-panel,
.quick-panel,
.closing-panel,
.unit-card {
  background: #ffffff;
  border: 1px solid #e6eee9;
  border-radius: 22px;
  box-shadow: 0 18px 45px rgba(26, 35, 50, 0.06);
}

.content-panel,
.closing-panel {
  padding: 1.6rem;
}

.section-label,
.quick-kicker {
  display: inline-block;
  color: var(--primary);
  font-size: 0.78rem;
  font-weight: 800;
  letter-spacing: 0.13em;
  text-transform: uppercase;
  margin-bottom: 0.45rem;
}

.content-panel h2,
.closing-panel h2,
.section-heading h2 {
  color: var(--text-dark);
  font-size: 1.45rem;
  font-weight: 800;
  margin-bottom: 1rem;
}

.content-panel p,
.closing-panel p,
.section-heading p,
.unit-card p,
.unit-card li {
  color: var(--text-mid);
  font-size: 0.95rem;
  line-height: 1.85;
}

.content-panel p:last-child,
.closing-panel p:last-child {
  margin-bottom: 0;
}

.quick-panel {
  padding: 1.4rem;
  background:
    radial-gradient(circle at top right, rgba(252, 191, 27, 0.18), transparent 35%),
    #ffffff;
}

.quick-panel h3 {
  color: var(--text-dark);
  font-size: 1.2rem;
  font-weight: 800;
  margin-bottom: 1rem;
}

.quick-panel ul,
.unit-card ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.quick-panel li {
  display: flex;
  align-items: center;
  gap: 0.7rem;
  color: var(--text-dark);
  font-weight: 700;
  padding: 0.65rem 0;
  border-bottom: 1px solid #edf2ee;
}

.quick-panel li:last-child {
  border-bottom: 0;
}

.quick-panel li span,
.unit-card-head span {
  width: 38px;
  height: 38px;
  border-radius: 14px;
  display: grid;
  place-items: center;
  background: rgba(31, 156, 88, 0.12);
  color: var(--primary);
  font-size: 0.8rem;
  font-weight: 800;
  flex: 0 0 auto;
}

.legislation-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 0.8rem;
}

.legislation-card {
  display: flex;
  align-items: flex-start;
  gap: 0.65rem;
  background: #f7faf8;
  border: 1px solid #e6eee9;
  border-radius: 16px;
  padding: 0.9rem;
  color: var(--text-mid);
  font-weight: 700;
}

.legislation-card i {
  color: var(--primary);
}

.section-heading {
  max-width: 850px;
  margin: 2rem 0 1.2rem;
}

.unit-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1.2rem;
}

.unit-card {
  padding: 1.35rem;
}

.unit-card-head {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  margin-bottom: 1rem;
}

.unit-card-head h3 {
  margin: 0;
  color: var(--text-dark);
  font-size: 1.15rem;
  font-weight: 800;
}

.unit-card li {
  position: relative;
  padding-left: 1.25rem;
  margin-bottom: 0.55rem;
}

.unit-card li::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0.8rem;
  width: 7px;
  height: 7px;
  border-radius: 999px;
  background: var(--primary);
}

.unit-card :deep(strong) {
  color: var(--text-dark);
  font-weight: 800;
}

.closing-panel {
  border-left: 5px solid var(--accent);
}

@media (max-width: 992px) {
  .legislation-grid,
  .unit-grid {
    grid-template-columns: 1fr;
  }
}
</style>

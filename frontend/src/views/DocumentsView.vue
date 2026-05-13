<template>
  <div class="page-wrapper bg-light documents-view">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Documents</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">NDZ Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Documents</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="document-ribbon" aria-hidden="true"></div>

    <!-- Documents Grid -->
    <section class="py-5">
      <div class="container pb-5">
        <div class="row g-4">
          <div class="col-md-6 col-lg-4" v-for="category in documentCategories" :key="category.id">
            <div class="doc-card h-100">
              <div class="doc-card-top">
                <span class="doc-card-index">{{ category.id }}</span>
                <h5 class="doc-card-title">{{ category.title }}</h5>
              </div>
              <ul class="doc-card-list">
                <li v-for="(item, idx) in category.items" :key="idx">
                  <a v-if="item.link" class="doc-card-link" :href="item.link">{{ item.label }}</a>
                  <span v-else class="doc-card-text">{{ item.label }}</span>
                </li>
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
  name: 'DocumentsView',
  data() {
    return {
      documentCategories: [
        {
          id: '01',
          title: 'Municipal Documents',
          items: [
            { label: 'Executive Office', link: '#' },
            { label: 'Financial Services', link: '#' },
            { label: 'Economic Development', link: '#' },
            { label: 'Infrastructure', link: '#' },
            { label: 'Community Services', link: '#' },
            { label: 'Council Reports', link: '#' },
          ],
        },
        {
          id: '02',
          title: 'Policies',
          items: [
            { label: 'Financial Policies', link: '#' },
            { label: 'Administrative Policies', link: '#' },
            { label: 'Community Policies', link: '#' },
            { label: 'Infrastructure Policies', link: '#' },
            { label: 'Municipal Policies', link: '#' },
            { label: 'Economic Development and Planning Policies', link: '#' },
            { label: 'Incentives Policies', link: '#' },
            { label: 'By-Laws', link: '#' },
          ],
        },
        {
          id: '03',
          title: 'LED',
          items: [
            { label: 'LED Strategy', link: '#' },
            { label: 'Incentives Policy', link: '#' },
          ],
        },
        {
          id: '04',
          title: 'Economic Development Planning & Human Settlement',
          items: [
            { label: 'Town Planning', link: '#' },
          ],
        },
        {
          id: '05',
          title: 'Performance Management',
          items: [
            { label: 'Performance Report', link: '#' },
          ],
        },
        {
          id: '06',
          title: 'Public Notices',
          items: [
            { label: 'Notices', link: '#' },
          ],
        },
        {
          id: '07',
          title: 'Tariffs',
          items: [
            { label: '2024-2025', link: '#' },
          ],
        },
        {
          id: '09',
          title: 'IDP',
          items: [
            { label: '2026-2027', link: '#' },
            { label: '2025-2026', link: '#' },
            { label: '2024-2025', link: '#' },
            { label: '2023-2024', link: '#' },
            { label: '2022-2023', link: '#' },
            { label: '2021-2022', link: '#' },
          ],
        },
        {
          id: '10',
          title: 'Budget',
          items: [
            { label: '2026-2027', link: '#' },
            { label: '2025-2026', link: '#' },
            { label: '2024-2025', link: '#' },
            { label: '2023-2024', link: '#' },
            { label: '2022-2023', link: '#' },
            { label: '2021-2022', link: '#' },
          ],
        },
        {
          id: '11',
          title: 'Annual Report',
          items: [
            { label: '2025-2026', link: '#' },
            { label: '2024-2025', link: '#' },
            { label: '2023-2024', link: '#' },
            { label: '2022-2023', link: '#' },
            { label: '2021-2022', link: '#' },
          ],
        },
        {
          id: '12',
          title: 'Newsletter',
          items: [
            { label: 'Latest editions', link: '#' },
          ],
        },
        {
          id: '13',
          title: 'Building Control',
          items: [
            { label: 'Building Control Forms', link: '#' },
          ],
        },
        {
          id: '14',
          title: 'All Service Agreements',
          items: [
            { label: 'Service Level Agreements', link: '#' },
          ],
        },
        {
          id: '15',
          title: 'Long-Term Borrowings Contracts',
          items: [
            { label: 'Borrowings Contracts', link: '#' },
          ],
        },
        {
          id: '16',
          title: 'EDP & HS',
          items: [
            { label: 'Environmental Management', link: '#' },
          ],
        },
        {
          id: '17',
          title: 'Economic Development and Planning',
          items: [
            { label: 'Spatial Development Framework', link: '#' },
          ],
        },
      ],
      reportContacts: [
        {
          label: 'Municipality Contact Number',
          number: '033 239 9200',
          icon: 'bi bi-telephone-fill',
        },
        {
          label: 'Control Room',
          number: '033 239 9276',
          icon: 'bi bi-headset',
        },
        {
          label: 'WhatsApp Only',
          number: '079 513 6406',
          icon: 'bi bi-whatsapp',
        },
        {
          label: 'Toll-Free',
          number: '080 000 0124',
          icon: 'bi bi-telephone-plus',
        },
      ],
      reportIssues: [
        'Electricity outages',
        'Non-functional streetlights',
        'Non-collection of refuse',
        'Vehicle accidents',
        'Disaster caused by floods, fires, snow or fallen trees',
      ],
      reportNotes: [
        'If electricity in your area is the responsibility of Eskom, contact them on 086 0037 566.',
        'If electricity in your area is the responsibility of the Municipality, contact us on 033 239 5209 / 080 000 1868.',
        'Water and sanitation are the responsibility of uMngeni Local Municipality. Contact them on 0800 864 911 or 033 897 6766.',
      ],
    }
  },
}
</script>

<style scoped>
.page-wrapper {
  background-color: #f8f9fa;
}

.document-ribbon {
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

.doc-card {
  background: #ffffff;
  border-radius: 6px;
  padding: 1.4rem 1.5rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
  border: 1px solid #eef1f5;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.doc-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 14px 35px rgba(0, 0, 0, 0.09);
}

.doc-card-top {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  margin-bottom: 0.75rem;
}

.doc-card-index {
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--primary);
  background: rgba(31, 156, 88, 0.12);
  border-radius: 999px;
  padding: 0.2rem 0.6rem;
  line-height: 1.4;
}

.doc-card-title {
  margin: 0;
  font-size: 1rem;
  font-weight: 700;
  color: var(--text-dark);
}

.doc-card-list {
  list-style: none;
  margin: 0;
  padding: 0;
}

.doc-card-list li {
  margin: 0.35rem 0;
}

.doc-card-link,
.doc-card-text {
  font-size: 0.85rem;
  color: var(--text-mid);
}

.doc-card-link:hover {
  color: var(--primary);
  text-decoration: underline;
}

.report-section {
  background: #ffffff;
  border-top: 1px solid #eef1f5;
}

.report-card {
  background: var(--primary);
  color: #ffffff;
  border-radius: 6px;
  padding: 1rem 1.2rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
}

.report-card-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
}

.report-card-number {
  font-weight: 700;
  font-size: 1rem;
}

.report-card-label {
  font-size: 0.7rem;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  opacity: 0.85;
}

.report-panel {
  background: var(--off-white);
  border-radius: 6px;
  padding: 1.2rem;
  border: 1px solid #eef1f5;
  height: 100%;
}

.report-panel-title {
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 0.8rem;
}

.report-list {
  padding-left: 1.1rem;
  margin: 0;
  color: var(--text-mid);
  font-size: 0.9rem;
}
</style>

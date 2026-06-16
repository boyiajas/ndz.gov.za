<template>
  <div class="page-wrapper bg-light contract-reporting-view">
    <div class="page-header reporting-header">
      <div class="container">
        <h1 class="reporting-title">Contract Reporting</h1>
        <p class="reporting-subtitle">Supply Chain Management</p>

        <nav aria-label="breadcrumb" class="reporting-breadcrumb-wrap">
          <ol class="breadcrumb reporting-breadcrumb">
            <li class="breadcrumb-item">
              <router-link to="/" class="crumb-link">Home</router-link>
            </li>
            <li class="breadcrumb-item">
              <span class="crumb-muted">SCM</span>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Contract Reporting</li>
          </ol>
        </nav>
      </div>
    </div>

    <section class="py-5">
      <div class="container pb-5">
        <div class="row g-5 align-items-start">
          <div class="col-lg-3">
            <div class="quick-links-sidebar bg-primary text-white rounded overflow-hidden shadow-sm">
              <h4 class="p-4 mb-0 fw-bold border-bottom border-light border-opacity-25">Quick Links</h4>
              <ul class="list-unstyled mb-0">
                <li><router-link to="/open-tenders" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">Open Tenders <i class="bi bi-chevron-right float-end"></i></router-link></li>
                <li><router-link to="/closed-tenders" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">Closed Tenders <i class="bi bi-chevron-right float-end"></i></router-link></li>
                <li><router-link to="/open-quotes" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">Open Quotes <i class="bi bi-chevron-right float-end"></i></router-link></li>
                <li><router-link to="/closed-quotes" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">Closed Quotes <i class="bi bi-chevron-right float-end"></i></router-link></li>
                <li><a href="#" class="d-block p-3 text-white text-decoration-none border-bottom border-light border-opacity-10">Intention to Award <i class="bi bi-chevron-right float-end"></i></a></li>
                <li><router-link to="/contract-reporting" class="d-block p-3 text-white text-decoration-none active">Contract Reporting <i class="bi bi-chevron-right float-end"></i></router-link></li>
              </ul>
            </div>
          </div>

          <div class="col-lg-9">
            <div class="content-card bg-white shadow-sm rounded-4 overflow-hidden">
              <div class="p-4 p-lg-5">
                <div class="reporting-reference-strip">
                  <div class="reference-pill">Structured from SCM notes</div>
                  <div class="reference-copy">
                    Formal Quotes and Tenders are grouped by financial year, split into Quarter 1 to
                    Quarter 4, with an Older archive folder for historical records.
                  </div>
                </div>

                <div class="reporting-type-tabs">
                  <button
                    v-for="type in reportingTypes"
                    :key="type.key"
                    type="button"
                    class="reporting-type-tab"
                    :class="{ active: activeType === type.key }"
                    @click="activeType = type.key"
                  >
                    {{ type.label }}
                  </button>
                </div>

                <div class="reporting-year-tabs">
                  <button
                    v-for="year in activeYears"
                    :key="year.key"
                    type="button"
                    class="reporting-year-tab"
                    :class="{ active: activeYearKey === year.key }"
                    @click="activeYearKey = year.key"
                  >
                    {{ year.label }}
                  </button>
                </div>

                <div class="reporting-intro">
                  <div>
                    <span class="reporting-badge">{{ activeTypeLabel }}</span>
                    <h3>{{ activeYear.label }}</h3>
                  </div>
                  <p>{{ activeYear.description }}</p>
                </div>

                <div class="quarter-stack">
                  <section
                    v-for="quarter in activeYear.quarters"
                    :key="quarter.title"
                    class="quarter-block"
                  >
                    <div class="quarter-heading">
                      <span>{{ quarter.title }}</span>
                    </div>

                    <div class="quarter-note" v-if="quarter.note">
                      <i class="bi bi-info-circle-fill"></i>
                      <span>{{ quarter.note }}</span>
                    </div>

                    <div class="quarter-rows">
                      <div
                        v-for="item in quarter.items"
                        :key="item.label"
                        class="report-row"
                      >
                        <div class="report-row-copy">
                          <div class="report-row-title">{{ item.label }}</div>
                          <div class="report-row-meta" v-if="item.meta">{{ item.meta }}</div>
                        </div>
                        <a :href="item.link || '#'" class="btn-download">
                          Download
                        </a>
                      </div>
                    </div>
                  </section>
                </div>

                <div class="archive-note">
                  <div class="archive-note-icon">
                    <i class="bi bi-archive-fill"></i>
                  </div>
                  <div>
                    <h4>Older reporting folder</h4>
                    <p>
                      Historical contract reporting can be grouped under an <strong>Older</strong>
                      archive, as indicated in the source notes, to keep the current financial-year
                      view clean while preserving access to past SCM records.
                    </p>
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
  name: 'ContractReportingView',
  data() {
    return {
      activeType: 'formal-quotes',
      activeYearKey: '2025-2026',
      reportingTypes: [
        { key: 'formal-quotes', label: 'Formal Quotes' },
        { key: 'tenders', label: 'Tenders' },
      ],
      reportingData: {
        'formal-quotes': [
          {
            key: '2025-2026',
            label: '2025/2026',
            description: 'Current financial year reporting for formal quotations, arranged by quarter.',
            quarters: [
              {
                title: 'Quarter 1 (2025/2026)',
                items: [
                  { label: 'Quarter 1 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 2 (2025/2026)',
                items: [
                  { label: 'Quarter 2 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 3 (2025/2026)',
                items: [
                  { label: 'Quarter 3 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 4 (2025/2026)',
                items: [
                  { label: 'Quarter 4 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
            ],
          },
          {
            key: '2024-2025',
            label: '2024/2025',
            description: 'Archived quarterly reporting for formal quotations from the previous financial year.',
            quarters: [
              {
                title: 'Quarter 1 (2024/2025)',
                items: [
                  { label: 'Quarter 1 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 2 (2024/2025)',
                items: [
                  { label: 'Quarter 2 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 3 (2024/2025)',
                items: [
                  { label: 'Quarter 3 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 4 (2024/2025)',
                items: [
                  { label: 'Quarter 4 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
            ],
          },
          {
            key: '2023-2024',
            label: '2023/2024',
            description: 'Archived quarterly reporting for formal quotations from the 2023/2024 financial year.',
            quarters: [
              {
                title: 'Quarter 1 (2023/2024)',
                items: [
                  { label: 'Quarter 1 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 2 (2023/2024)',
                items: [
                  { label: 'Quarter 2 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 3 (2023/2024)',
                items: [
                  { label: 'Quarter 3 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 4 (2023/2024)',
                items: [
                  { label: 'Quarter 4 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
            ],
          },
          {
            key: '2022-2023',
            label: '2022/2023',
            description: 'Archived quarterly reporting for formal quotations from the 2022/2023 financial year.',
            quarters: [
              {
                title: 'Quarter 1 (2022/2023)',
                items: [
                  { label: 'Quarter 1 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 2 (2022/2023)',
                items: [
                  { label: 'Quarter 2 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 3 (2022/2023)',
                items: [
                  { label: 'Quarter 3 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
              {
                title: 'Quarter 4 (2022/2023)',
                items: [
                  { label: 'Quarter 4 report pack', meta: 'Formal quote reporting bundle', link: '#' },
                ],
              },
            ],
          },
          {
            key: 'older',
            label: 'Older',
            description: 'Older formal quotation reporting folders can be kept here for historical reference.',
            quarters: [
              {
                title: 'Archived reporting folders',
                note: 'Create the older folder for historical formal quote records.',
                items: [
                  { label: 'Older formal quote archive', meta: 'Historical records', link: '#' },
                ],
              },
            ],
          },
        ],
        tenders: [
          {
            key: '2025-2026',
            label: '2025/2026',
            description: 'Tender contract reporting for the current financial year.',
            quarters: [
              {
                title: 'Quarter 1 (2025/2026)',
                items: [
                  { label: 'Quarter 1 tender reporting', meta: 'Current financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 2 (2025/2026)',
                note: 'The source notes indicate 2025/2026 should be aligned under the current financial year.',
                items: [
                  { label: 'Quarter 2 tender reporting', meta: 'Current financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 3 (2025/2026)',
                items: [
                  { label: 'Quarter 3 tender reporting', meta: 'Current financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 4 (2025/2026)',
                items: [
                  { label: 'Quarter 4 tender reporting', meta: 'Current financial year', link: '#' },
                ],
              },
            ],
          },
          {
            key: '2024-2025',
            label: '2024/2025',
            description: 'Previous-year tender reporting kept available for SCM reference.',
            quarters: [
              {
                title: 'Quarter 1 (2024/2025)',
                items: [
                  { label: 'Quarter 1 tender reporting', meta: 'Previous financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 2 (2024/2025)',
                items: [
                  { label: 'Quarter 2 tender reporting', meta: 'Previous financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 3 (2024/2025)',
                items: [
                  { label: 'Quarter 3 tender reporting', meta: 'Previous financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 4 (2024/2025)',
                items: [
                  { label: 'Quarter 4 tender reporting', meta: 'Previous financial year', link: '#' },
                ],
              },
            ],
          },
          {
            key: '2023-2024',
            label: '2023/2024',
            description: 'Tender reporting kept for the 2023/2024 financial year.',
            quarters: [
              {
                title: 'Quarter 1 (2023/2024)',
                items: [
                  { label: 'Quarter 1 tender reporting', meta: 'Archived financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 2 (2023/2024)',
                items: [
                  { label: 'Quarter 2 tender reporting', meta: 'Archived financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 3 (2023/2024)',
                items: [
                  { label: 'Quarter 3 tender reporting', meta: 'Archived financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 4 (2023/2024)',
                items: [
                  { label: 'Quarter 4 tender reporting', meta: 'Archived financial year', link: '#' },
                ],
              },
            ],
          },
          {
            key: '2022-2023',
            label: '2022/2023',
            description: 'Tender reporting kept for the 2022/2023 financial year.',
            quarters: [
              {
                title: 'Quarter 1 (2022/2023)',
                items: [
                  { label: 'Quarter 1 tender reporting', meta: 'Archived financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 2 (2022/2023)',
                items: [
                  { label: 'Quarter 2 tender reporting', meta: 'Archived financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 3 (2022/2023)',
                items: [
                  { label: 'Quarter 3 tender reporting', meta: 'Archived financial year', link: '#' },
                ],
              },
              {
                title: 'Quarter 4 (2022/2023)',
                items: [
                  { label: 'Quarter 4 tender reporting', meta: 'Archived financial year', link: '#' },
                ],
              },
            ],
          },
          {
            key: 'older',
            label: 'Older',
            description: 'Older tender reporting folders for archive access.',
            quarters: [
              {
                title: 'Archived reporting folders',
                note: 'Create the older folder for historical tender reporting records.',
                items: [
                  { label: 'Older tender archive', meta: 'Historical records', link: '#' },
                ],
              },
            ],
          },
        ],
      },
    }
  },
  computed: {
    activeYears() {
      return this.reportingData[this.activeType] || []
    },
    activeYear() {
      return this.activeYears.find((year) => year.key === this.activeYearKey) || this.activeYears[0]
    },
    activeTypeLabel() {
      return this.reportingTypes.find((type) => type.key === this.activeType)?.label || 'Reporting'
    },
  },
  watch: {
    activeType() {
      this.activeYearKey = this.activeYears[0]?.key || ''
    },
  },
}
</script>

<style scoped>
.page-wrapper {
  background-color: #f8f9fa;
}

.reporting-header {
  background:
    linear-gradient(135deg, rgba(31, 156, 88, 0.96), rgba(24, 130, 73, 0.94)),
    radial-gradient(circle at top right, rgba(252, 191, 27, 0.2), transparent 40%);
  color: #fff;
  padding: 4rem 0;
}

.reporting-title {
  margin: 0;
  font-size: 2.5rem;
  font-weight: 800;
}

.reporting-subtitle {
  margin-top: 0.5rem;
  font-size: 1.05rem;
  opacity: 0.88;
}

.reporting-breadcrumb-wrap {
  margin-top: 1.5rem;
  display: flex;
  justify-content: flex-start;
}

.reporting-breadcrumb {
  display: inline-flex;
  width: auto;
  margin: 0;
  font-size: 0.95rem;
  background: rgba(255, 255, 255, 0.12);
  padding: 0.55rem 1rem;
  border-radius: 999px;
}

.crumb-link {
  color: rgba(255, 255, 255, 0.92);
  text-decoration: none;
}

.crumb-muted {
  color: rgba(255, 255, 255, 0.72);
}

.reporting-type-tabs {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 0.75rem;
  margin-bottom: 2rem;
}

.reporting-reference-strip {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
  padding: 1rem 1.1rem;
  border-radius: 1rem;
  background: linear-gradient(135deg, rgba(31, 156, 88, 0.06), rgba(252, 191, 27, 0.12));
  border: 1px solid rgba(31, 156, 88, 0.1);
}

.reference-pill {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.45rem 0.8rem;
  border-radius: 999px;
  background: #fff;
  color: var(--primary);
  font-size: 0.76rem;
  font-weight: 800;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  white-space: nowrap;
}

.reference-copy {
  color: var(--text-mid);
  font-size: 0.92rem;
}

.reporting-type-tab,
.reporting-year-tab {
  border: 0;
  transition: all 0.2s ease;
}

.reporting-type-tab {
  padding: 1rem 1.2rem;
  border-radius: 1rem;
  font-size: 1rem;
  font-weight: 800;
  background: linear-gradient(135deg, #edf8f1, #fff8e5);
  color: var(--text-dark);
  box-shadow: inset 0 0 0 1px rgba(31, 156, 88, 0.12);
}

.reporting-type-tab.active {
  background: linear-gradient(135deg, var(--primary), var(--primary-mid));
  color: #fff;
  box-shadow: 0 14px 28px rgba(31, 156, 88, 0.18);
}

.reporting-year-tabs {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}

.reporting-year-tab {
  padding: 0.8rem 1.2rem;
  border-radius: 999px;
  background: #f4f6f9;
  color: #7b8794;
  font-size: 0.9rem;
  font-weight: 700;
}

.reporting-year-tab.active {
  background: #fff;
  color: var(--primary);
  box-shadow: inset 0 0 0 2px rgba(31, 156, 88, 0.9), 0 10px 20px rgba(31, 156, 88, 0.08);
}

.reporting-intro {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
  align-items: flex-end;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #eef1f5;
}

.reporting-intro h3 {
  margin: 0.6rem 0 0;
  color: var(--text-dark);
  font-size: 1.4rem;
  font-weight: 800;
}

.reporting-intro p {
  margin: 0;
  max-width: 42ch;
  color: var(--text-mid);
}

.reporting-badge {
  display: inline-flex;
  padding: 0.35rem 0.8rem;
  border-radius: 999px;
  background: rgba(252, 191, 27, 0.18);
  color: #8a6500;
  font-size: 0.78rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}

.quarter-stack {
  display: flex;
  flex-direction: column;
  gap: 1.6rem;
}

.quarter-block {
  background: #fff;
  border: 1px solid #edf1f5;
  border-radius: 1.2rem;
  overflow: hidden;
  box-shadow: 0 14px 34px rgba(26, 35, 50, 0.05);
}

.quarter-heading {
  padding: 0.95rem 1.2rem;
  background: linear-gradient(90deg, rgba(31, 156, 88, 0.12), rgba(252, 191, 27, 0.08));
  border-left: 6px solid var(--primary);
  font-weight: 800;
  color: var(--text-dark);
  text-transform: uppercase;
}

.quarter-note {
  display: flex;
  gap: 0.7rem;
  align-items: flex-start;
  padding: 1rem 1.2rem 0;
  color: var(--text-mid);
  font-size: 0.92rem;
}

.quarter-note i {
  color: var(--accent-dark);
  margin-top: 0.1rem;
}

.quarter-rows {
  padding: 1rem 1.2rem 1.25rem;
}

.report-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 1rem 0;
  border-bottom: 1px solid #f0f3f7;
}

.report-row:last-child {
  border-bottom: 0;
}

.report-row-title {
  color: var(--text-dark);
  font-weight: 700;
  font-size: 1rem;
}

.report-row-meta {
  color: var(--text-light);
  font-size: 0.86rem;
  margin-top: 0.2rem;
}

.btn-download {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 140px;
  padding: 0.8rem 1.15rem;
  border-radius: 999px;
  background: linear-gradient(135deg, var(--primary), var(--primary-light));
  color: #fff;
  font-weight: 800;
  text-decoration: none;
  box-shadow: 0 10px 20px rgba(31, 156, 88, 0.16);
}

.btn-download:hover {
  color: #fff;
  transform: translateY(-1px);
}

.archive-note {
  display: flex;
  gap: 1rem;
  margin-top: 2rem;
  padding: 1.2rem 1.25rem;
  border-radius: 1rem;
  background: linear-gradient(135deg, rgba(31, 156, 88, 0.08), rgba(252, 191, 27, 0.1));
  border: 1px solid rgba(31, 156, 88, 0.12);
}

.archive-note h4 {
  margin: 0 0 0.35rem;
  color: var(--text-dark);
  font-size: 1rem;
  font-weight: 800;
}

.archive-note p {
  margin: 0;
  color: var(--text-mid);
}

.archive-note-icon {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  background: #fff;
  color: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.bg-primary {
  background-color: var(--primary, #1f9c58) !important;
}

.quick-links-sidebar ul li a {
  transition: all 0.3s ease;
}

.quick-links-sidebar ul li a:hover {
  background-color: rgba(255, 255, 255, 0.1);
  padding-left: 1.5rem !important;
}

.quick-links-sidebar ul li a.active {
  background-color: rgba(255, 255, 255, 0.15);
  font-weight: 700;
  border-left: 4px solid var(--accent, #fcbf1b);
}

@media (max-width: 991.98px) {
  .reporting-intro {
    flex-direction: column;
    align-items: flex-start;
  }
}

@media (max-width: 767.98px) {
  .reporting-title {
    font-size: 2rem;
  }

  .reporting-type-tabs {
    grid-template-columns: 1fr;
  }

  .reporting-reference-strip {
    flex-direction: column;
    align-items: flex-start;
  }

  .report-row {
    flex-direction: column;
    align-items: flex-start;
  }

  .btn-download {
    width: 100%;
  }

  .archive-note {
    flex-direction: column;
  }
}
</style>

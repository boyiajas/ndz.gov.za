<template>
  <div class="page-wrapper bg-light">
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">{{ currentSection.title }}</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Supply Chain Management</p>

        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">SCM</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">{{ currentSection.title }}</li>
          </ol>
        </nav>
      </div>
    </div>

    <section class="open-tenders-section">
      <div class="container">
        <div class="tenders-shell">
          <nav class="tenders-tabs" aria-label="SCM tabs">
            <router-link
              v-for="tab in tabs"
              :key="tab.id"
              :to="tab.to"
              class="tenders-tab"
              :class="{ 'is-active': tab.id === currentSection.id }"
            >
              {{ tab.label }}
            </router-link>
          </nav>

          <div class="tenders-panel">
            <div class="tenders-grid">
              <button
                v-for="year in currentSection.years"
                :key="year.id"
                class="tender-year-card"
                :class="{ 'is-active': activeYear && year.id === activeYear.id }"
                type="button"
                @click="setActiveYear(year.id)"
              >
                {{ year.label }}
              </button>
            </div>

            <div class="year-results" v-if="activeYear">
              <div class="year-results-header">
                <div>
                  <p class="year-results-kicker">{{ currentSection.title }}</p>
                  <h2 class="year-results-title">{{ activeYear.label }} Listings</h2>
                </div>
                <span class="year-results-count">{{ activeYear.items.length }} item<span v-if="activeYear.items.length !== 1">s</span></span>
              </div>

              <div v-if="activeYear.items.length" class="year-results-list">
                <article
                  v-for="item in activeYear.items"
                  :key="item.id"
                  class="year-result-card"
                >
                  <div class="year-result-copy">
                    <p class="year-result-reference">{{ item.reference }}</p>
                    <h3 class="year-result-name">{{ item.title }}</h3>
                    <div class="year-result-meta">
                      <span
                        v-for="detail in item.details"
                        :key="detail"
                        class="year-result-detail"
                      >
                        {{ detail }}
                      </span>
                    </div>
                  </div>

                  <a
                    class="year-result-action"
                    :href="item.href || '#'"
                    @click.prevent
                  >
                    {{ item.actionLabel || 'Open' }}
                  </a>
                </article>
              </div>

              <div v-else class="year-results-empty">
                No records are currently available for {{ activeYear.label }}.
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
  name: 'OpenTendersView',
  data() {
    const openTenderYears = [
      {
        id: 'year-2025-2026',
        label: '2025-2026',
        items: [
          {
            id: 'ot-2526-1',
            reference: 'UMN18/2025-26',
            title: 'Appointment of a service provider to provide internet and VOIP service for the municipality for 36 months',
            details: ['Published: 16 Mar 2026', 'Closing: 16 Apr 2026', '2 files'],
            actionLabel: 'View Notice',
          },
          {
            id: 'ot-2526-2',
            reference: 'UMN20/2025-26',
            title: 'Appointment of a panel of town planners for the municipality for 36 months',
            details: ['Published: 05 Jan 2026', 'Closing: 04 Mar 2026', '1 file'],
            actionLabel: 'View Notice',
          },
          {
            id: 'ot-2526-3',
            reference: 'UMN21/2025-26',
            title: 'Appointment of a service provider for servicing fire extinguishers, fire hoses, and installation of new extinguishers',
            details: ['Published: 05 Jan 2026', 'Closing: 05 Mar 2026', '2 files'],
            actionLabel: 'View Notice',
          },
        ],
      },
      {
        id: 'year-2024-2025',
        label: '2024-2025',
        items: [
          {
            id: 'ot-2425-1',
            reference: 'UMN08/2024-25',
            title: 'Appointment of a service provider to act as transaction advisor for the public-private partnership energy project',
            details: ['Published: 14 Feb 2025', 'Closing: 28 Mar 2025', '1 file'],
            actionLabel: 'View Tender',
          },
          {
            id: 'ot-2425-2',
            reference: 'UMN05/2024-25',
            title: 'Appointment of a panel of occupational medical practitioners for a period of 36 months',
            details: ['Published: 23 Jan 2025', 'Closing: 20 Feb 2025', '1 file'],
            actionLabel: 'View Tender',
          },
          {
            id: 'ot-2425-3',
            reference: 'UMN03/2024-25',
            title: 'Appointment of a panel of service providers to render tree pruning and felling services',
            details: ['Published: 11 Nov 2024', 'Closing: 13 Dec 2024', '1 file'],
            actionLabel: 'View Tender',
          },
        ],
      },
      {
        id: 'year-2023-2024-a',
        label: '2023-2024',
        items: [
          {
            id: 'ot-2324-1',
            reference: 'UMN01/2023-24',
            title: 'Appointment of a service provider to provide banking and cash collection services for five years',
            details: ['Published: 09 Oct 2023', 'Closing: 17 Nov 2023', '1 file'],
            actionLabel: 'View Tender',
          },
          {
            id: 'ot-2324-2',
            reference: 'UMN04/2023-24',
            title: 'Appointment of a service provider to provide insurance services for a period of 36 months',
            details: ['Published: 06 Dec 2023', 'Closing: 26 Jan 2024', '1 file'],
            actionLabel: 'View Tender',
          },
        ],
      },
      {
        id: 'year-2023-2024-b',
        label: '2023-2024',
        items: [
          {
            id: 'ot-2324-3',
            reference: 'UMN05/2023-24',
            title: 'Appointment of a panel of occupational medical practitioners for a period of 36 months',
            details: ['Published: 15 Jan 2024', 'Closing: 23 Feb 2024', '1 file'],
            actionLabel: 'View Tender',
          },
        ],
      },
      {
        id: 'year-2022-2023',
        label: '2022-2023',
        items: [
          {
            id: 'ot-2223-1',
            reference: 'UMN12/2022-23',
            title: 'Supply and installation of street lighting infrastructure upgrades',
            details: ['Published: 08 Feb 2023', 'Closing: 17 Mar 2023', '2 files'],
            actionLabel: 'View Tender',
          },
          {
            id: 'ot-2223-2',
            reference: 'UMN09/2022-23',
            title: 'Appointment of a service provider for fleet maintenance and repairs',
            details: ['Published: 19 Sep 2022', 'Closing: 28 Oct 2022', '1 file'],
            actionLabel: 'View Tender',
          },
        ],
      },
      {
        id: 'year-2021-2022',
        label: '2021-2022',
        items: [
          {
            id: 'ot-2122-1',
            reference: 'UMN07/2021-22',
            title: 'Construction of community sanitation facilities in identified wards',
            details: ['Published: 03 Nov 2021', 'Closing: 15 Dec 2021', '3 files'],
            actionLabel: 'View Tender',
          },
        ],
      },
    ]

    const openQuoteYears = [
      {
        id: 'year-2025-2026',
        label: '2025-2026',
        items: [
          {
            id: 'oq-2526-1',
            reference: 'Q-01/2026',
            title: 'Supply and delivery of office stationery for the municipality',
            details: ['Published: 01 Apr 2026', 'Closing: 15 Apr 2026', '1 file'],
            actionLabel: 'View RFQ',
          },
          {
            id: 'oq-2526-2',
            reference: 'Q-02/2026',
            title: 'Provision of catering services for the upcoming mayoral imbizo',
            details: ['Published: 28 Mar 2026', 'Closing: 10 Apr 2026', '1 file'],
            actionLabel: 'View RFQ',
          },
        ],
      },
      {
        id: 'year-2024-2025',
        label: '2024-2025',
        items: [
          {
            id: 'oq-2425-1',
            reference: 'Q-18/2025',
            title: 'Supply of personal protective equipment for field teams',
            details: ['Published: 18 Feb 2025', 'Closing: 01 Mar 2025', '1 file'],
            actionLabel: 'View RFQ',
          },
          {
            id: 'oq-2425-2',
            reference: 'Q-11/2024',
            title: 'Repair and servicing of municipal printers and copiers',
            details: ['Published: 04 Nov 2024', 'Closing: 15 Nov 2024', '1 file'],
            actionLabel: 'View RFQ',
          },
        ],
      },
      {
        id: 'year-2023-2024-a',
        label: '2023-2024',
        items: [
          {
            id: 'oq-2324-1',
            reference: 'Q-07/2024',
            title: 'Supply and delivery of cleaning material for municipal offices',
            details: ['Published: 06 May 2024', 'Closing: 17 May 2024', '1 file'],
            actionLabel: 'View RFQ',
          },
        ],
      },
      {
        id: 'year-2023-2024-b',
        label: '2023-2024',
        items: [
          {
            id: 'oq-2324-2',
            reference: 'Q-03/2024',
            title: 'Appointment of a service provider for event staging and sound hire',
            details: ['Published: 09 Jan 2024', 'Closing: 19 Jan 2024', '1 file'],
            actionLabel: 'View RFQ',
          },
        ],
      },
      {
        id: 'year-2022-2023',
        label: '2022-2023',
        items: [
          {
            id: 'oq-2223-1',
            reference: 'Q-26/2023',
            title: 'Supply of road signs and traffic cones for public works',
            details: ['Published: 03 Mar 2023', 'Closing: 10 Mar 2023', '1 file'],
            actionLabel: 'View RFQ',
          },
        ],
      },
      {
        id: 'year-2021-2022',
        label: '2021-2022',
        items: [
          {
            id: 'oq-2122-1',
            reference: 'Q-14/2022',
            title: 'Maintenance of council chamber audio visual equipment',
            details: ['Published: 22 Feb 2022', 'Closing: 04 Mar 2022', '1 file'],
            actionLabel: 'View RFQ',
          },
        ],
      },
    ]

    const bidDocumentYears = [
      {
        id: 'year-2025-2026',
        label: '2025-2026',
        items: [
          {
            id: 'bd-2526-1',
            reference: 'BD-2026-01',
            title: 'Bid returnables and declaration forms pack',
            details: ['Updated: 12 Jul 2026', 'PDF and Word pack', '4 files'],
            actionLabel: 'Open Pack',
          },
          {
            id: 'bd-2526-2',
            reference: 'BD-2026-02',
            title: 'General conditions of contract and tender compliance checklist',
            details: ['Updated: 04 Jun 2026', 'PDF pack', '2 files'],
            actionLabel: 'Open Pack',
          },
        ],
      },
      {
        id: 'year-2024-2025',
        label: '2024-2025',
        items: [
          {
            id: 'bd-2425-1',
            reference: 'BD-2025-03',
            title: 'CIDB evaluation schedules and compulsory briefing forms',
            details: ['Updated: 13 Feb 2025', 'Spreadsheet and PDF', '3 files'],
            actionLabel: 'Open Pack',
          },
        ],
      },
      {
        id: 'year-2023-2024-a',
        label: '2023-2024',
        items: [
          {
            id: 'bd-2324-1',
            reference: 'BD-2024-01',
            title: 'Supplier declaration, tax clearance, and functionality schedule',
            details: ['Updated: 10 Apr 2024', 'PDF pack', '3 files'],
            actionLabel: 'Open Pack',
          },
        ],
      },
      {
        id: 'year-2023-2024-b',
        label: '2023-2024',
        items: [],
      },
      {
        id: 'year-2022-2023',
        label: '2022-2023',
        items: [
          {
            id: 'bd-2223-1',
            reference: 'BD-2023-05',
            title: 'Historic bid forms and supplementary returnables',
            details: ['Updated: 01 Aug 2022', 'Archive pack', '2 files'],
            actionLabel: 'Open Archive',
          },
        ],
      },
      {
        id: 'year-2021-2022',
        label: '2021-2022',
        items: [],
      },
    ]

    const quoteDocumentYears = [
      {
        id: 'year-2025-2026',
        label: '2025-2026',
        items: [
          {
            id: 'qd-2526-1',
            reference: 'QD-2026-01',
            title: 'Standard quotation request template and vendor response form',
            details: ['Updated: 09 Jul 2026', 'Word and PDF', '2 files'],
            actionLabel: 'Open Pack',
          },
          {
            id: 'qd-2526-2',
            reference: 'QD-2026-02',
            title: 'Local content declaration and pricing schedule template',
            details: ['Updated: 18 May 2026', 'Spreadsheet pack', '2 files'],
            actionLabel: 'Open Pack',
          },
        ],
      },
      {
        id: 'year-2024-2025',
        label: '2024-2025',
        items: [
          {
            id: 'qd-2425-1',
            reference: 'QD-2025-04',
            title: 'Archived quotation templates for supply and service requests',
            details: ['Updated: 14 Jan 2025', 'Archive pack', '3 files'],
            actionLabel: 'Open Archive',
          },
        ],
      },
      {
        id: 'year-2023-2024-a',
        label: '2023-2024',
        items: [
          {
            id: 'qd-2324-1',
            reference: 'QD-2024-02',
            title: 'Quotation compliance forms and vendor declaration pack',
            details: ['Updated: 22 Mar 2024', 'PDF pack', '2 files'],
            actionLabel: 'Open Pack',
          },
        ],
      },
      {
        id: 'year-2023-2024-b',
        label: '2023-2024',
        items: [],
      },
      {
        id: 'year-2022-2023',
        label: '2022-2023',
        items: [
          {
            id: 'qd-2223-1',
            reference: 'QD-2023-03',
            title: 'Historical RFQ evaluation and adjudication templates',
            details: ['Updated: 02 Nov 2022', 'Archive pack', '2 files'],
            actionLabel: 'Open Archive',
          },
        ],
      },
      {
        id: 'year-2021-2022',
        label: '2021-2022',
        items: [],
      },
    ]

    return {
      selectedYearId: 'year-2025-2026',
      tabs: [
        { id: 'open-tenders', label: 'Open Tenders', to: '/open-tenders' },
        { id: 'open-quotes', label: 'Open Quotations', to: '/open-quotes' },
        { id: 'bid-documents', label: 'Bid Documents', to: '/bid-documents' },
        { id: 'quote-documents', label: 'Quote Documents', to: '/quote-documents' },
      ],
      sections: {
        'open-tenders': {
          id: 'open-tenders',
          title: 'Open Tenders',
          years: openTenderYears,
        },
        'open-quotes': {
          id: 'open-quotes',
          title: 'Open Quotations',
          years: openQuoteYears,
        },
        'bid-documents': {
          id: 'bid-documents',
          title: 'Bid Documents',
          years: bidDocumentYears,
        },
        'quote-documents': {
          id: 'quote-documents',
          title: 'Quote Documents',
          years: quoteDocumentYears,
        },
      },
    }
  },
  computed: {
    currentSection() {
      return this.sections[this.$route.name] || this.sections['open-tenders']
    },
    activeYear() {
      return this.currentSection.years.find((year) => year.id === this.selectedYearId) || this.currentSection.years[0] || null
    },
  },
  watch: {
    '$route.name'() {
      this.selectedYearId = this.currentSection.years[0]?.id || null
    },
  },
  methods: {
    setActiveYear(yearId) {
      this.selectedYearId = yearId
    },
  },
}
</script>

<style scoped>
.page-wrapper {
  min-height: 100vh;
  background-color: #f3f3f3;
}

.open-tenders-section {
  padding: 2rem 0 5rem;
}

.page-header :deep(.breadcrumb-item + .breadcrumb-item::before) {
  color: rgba(255, 255, 255, 0.7);
}

.page-header :deep(.breadcrumb-item.active) {
  color: #ffffff;
}

.page-header :deep(.breadcrumb-item a:hover) {
  color: #ffffff !important;
}

.tenders-shell {
  width: 100%;
}

.tenders-tabs {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  align-items: stretch;
  gap: 0;
}

.tenders-tab {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 3.75rem;
  padding: 1rem 1.25rem;
  background: #ffffff;
  color: #1f2f4a;
  font-size: 1.05rem;
  font-weight: 700;
  text-align: center;
  text-decoration: none;
  border: 1px solid #ececec;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.tenders-tab:hover {
  color: #0d9d49;
}

.tenders-tab.is-active {
  background: #0d9d49;
  color: #ffffff;
  border-color: #0d9d49;
}

.tenders-panel {
  padding: 1.35rem;
  background: #ffffff;
}

.tenders-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 1.15rem;
  margin-bottom: 1.5rem;
}

.tender-year-card {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 13.25rem;
  padding: 1.5rem;
  width: 100%;
  background: #ffffff;
  color: #1f2f4a;
  font-size: clamp(1.85rem, 2vw, 2.2rem);
  font-weight: 700;
  text-decoration: none;
  font-family: inherit;
  border: 2px solid #0d9d49;
  appearance: none;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.tender-year-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 24px rgba(13, 157, 73, 0.12);
}

.tender-year-card.is-active {
  background: #0d9d49;
  color: #ffffff;
  box-shadow: 0 14px 30px rgba(13, 157, 73, 0.2);
}

.year-results {
  border-top: 1px solid #e5e7eb;
  padding-top: 1.5rem;
}

.year-results-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 1rem;
}

.year-results-kicker {
  margin: 0 0 0.35rem;
  color: #0d9d49;
  font-size: 0.85rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.year-results-title {
  margin: 0;
  color: #1f2f4a;
  font-size: clamp(1.4rem, 2vw, 1.8rem);
  font-weight: 800;
}

.year-results-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.45rem 0.85rem;
  background: #eef8f2;
  color: #0d9d49;
  font-size: 0.9rem;
  font-weight: 700;
  border-radius: 999px;
}

.year-results-list {
  display: grid;
  gap: 1rem;
}

.year-result-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 1.2rem 1.25rem;
  background: #f8fbf9;
  border: 1px solid #dcebdd;
  border-left: 4px solid #0d9d49;
  border-radius: 0.75rem;
}

.year-result-copy {
  min-width: 0;
}

.year-result-reference {
  margin: 0 0 0.35rem;
  color: #0d9d49;
  font-size: 0.82rem;
  font-weight: 700;
  letter-spacing: 0.04em;
  text-transform: uppercase;
}

.year-result-name {
  margin: 0 0 0.65rem;
  color: #1f2f4a;
  font-size: 1.05rem;
  font-weight: 700;
  line-height: 1.45;
}

.year-result-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.65rem;
}

.year-result-detail {
  color: #5a6678;
  font-size: 0.92rem;
}

.year-result-action {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.8rem 1.15rem;
  background: #0d9d49;
  color: #ffffff;
  font-size: 0.92rem;
  font-weight: 700;
  text-decoration: none;
  border-radius: 0.65rem;
  white-space: nowrap;
}

.year-result-action:hover {
  background: #0a853d;
  color: #ffffff;
}

.year-results-empty {
  padding: 1.25rem;
  color: #5a6678;
  font-size: 0.98rem;
  text-align: center;
  background: #f8fbf9;
  border: 1px dashed #c7d8c8;
  border-radius: 0.75rem;
}

@media (max-width: 991.98px) {
  .tenders-tabs {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .tenders-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .year-results-header,
  .year-result-card {
    align-items: flex-start;
    flex-direction: column;
  }
}

@media (max-width: 575.98px) {
  .open-tenders-section {
    padding: 1.5rem 0 4rem;
  }

  .tenders-tabs,
  .tenders-grid {
    grid-template-columns: 1fr;
  }

  .tenders-tab {
    min-height: 3.35rem;
    font-size: 1rem;
  }

  .tender-year-card {
    min-height: 10rem;
    font-size: 1.7rem;
  }
}
</style>

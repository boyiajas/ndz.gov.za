<template>
  <footer class="app-footer">
    <div class="container">
      <div class="row g-4">
        <!-- Col 1: Address -->
        <div class="col-lg-3 col-md-6">
          <div class="footer-logo">
            <img src="/logo.png" alt="NDZ Logo" />
          </div>
          <p class="footer-address">
            123 Government Avenue<br>
            Pretoria, Gauteng<br>
            South Africa
          </p>
          <p class="footer-address mt-2">
            ✉ helpdesk@ndz.gov.za<br>
            📞 +27 (0) 11 000 0000
          </p>
          <div class="footer-social mt-3">
            <a href="#" title="Facebook">f</a>
            <a href="#" title="Twitter">t</a>
            <a href="#" title="Instagram">in</a>
          </div>
        </div>

        <!-- Col 2: Quick Links -->
        <div class="col-lg-3 col-md-6">
          <div class="footer-col-title">Services</div>
          <a class="footer-link" href="mailto:helpdesk@ndz.gov.za">helpdesk@ndz.gov.za</a>
          <a class="footer-link" href="mailto:complaints@ndz.gov.za">complaints@ndz.gov.za</a>
          <a class="footer-link" href="mailto:electricity@ndz.gov.za">electricity@ndz.gov.za</a>
          <a class="footer-link" href="mailto:control@ndz.gov.za">control@ndz.gov.za</a>
          <a class="footer-link" href="#">Water & Sanitation</a>
          <a class="footer-link" href="#">Waste Management</a>
        </div>

        <!-- Col 3: Documents -->
        <div class="col-lg-3 col-md-6">
          <div class="footer-col-title">Documents</div>
          <a class="footer-link" href="#">Tenders</a>
          <a class="footer-link" href="#">Quotations</a>
          <a class="footer-link" href="#">Public Notices</a>
          <a class="footer-link" href="#">Financial Policies</a>
          <a class="footer-link" href="#">Community Policies</a>
          <a class="footer-link" href="#">By-Laws</a>
          <a class="footer-link" href="#">IDP Documents</a>
        </div>

        <!-- Col 4: Recent Posts -->
        <div class="col-lg-3 col-md-6">
          <div class="footer-col-title">Recent Posts</div>
          <div v-for="post in recentPosts" :key="post.id" class="footer-post">
            <div class="footer-post-thumb">📰</div>
            <div>
              <div class="footer-post-title">{{ post.title }}</div>
              <div class="footer-post-date">{{ post.date }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <span>&copy; {{ year }} NDZ Portal — South African Government. All rights reserved.</span>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter',
  computed: {
    year() { return new Date().getFullYear() },
  },
  data() {
    return {
      recentPosts: [
        { id: 1, title: 'Mayor tables R674.8 million budget', date: 'May 28, 2025' },
        { id: 2, title: '2025/2026 Annual Budget tabled', date: 'May 27, 2025' },
        { id: 3, title: 'PUBLIC NOTICE – Traffic Dept Extended Hours', date: 'May 26, 2025' },
        { id: 4, title: 'Electricity Maintenance: Planned Outages', date: 'May 23, 2025' },
        { id: 5, title: 'Employee Wellness Programme a success', date: 'May 23, 2025' },
      ],
    }
  },
}
</script>

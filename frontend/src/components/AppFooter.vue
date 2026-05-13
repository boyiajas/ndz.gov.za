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
            Main Street, Creighton, 3263<br>
            South Africa
          </p>
          <p class="footer-address mt-2">
            ✉ helpdesk@ndz.gov.za<br>
            📞 039 833 1038
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

        <!-- Col 4: Newsletter -->
        <div class="col-lg-3 col-md-6">
          <div class="footer-col-title">Subscribe to our Newsletters</div>
          <p class="footer-newsletter-text">
            Get municipal updates, notices, and community news in your inbox.
          </p>
          <form class="footer-newsletter-form" @submit.prevent>
            <label class="footer-newsletter-label" for="footer-email">Email address</label>
            <div class="footer-newsletter-row">
              <input
                id="footer-email"
                class="footer-newsletter-input"
                type="email"
                placeholder="name@example.com"
                required
              />
              <button class="footer-newsletter-btn" type="submit">Subscribe</button>
            </div>
          </form>
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
}
</script>

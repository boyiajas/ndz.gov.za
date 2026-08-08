<template>
  <footer class="app-footer">
    <div class="container">
      <div class="row g-4">
        <!-- Col 1: Address -->
        <div class="col-lg-6 col-md-6">
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

        <!-- Col 2: Newsletter -->
        <div class="col-lg-6 col-md-6">
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

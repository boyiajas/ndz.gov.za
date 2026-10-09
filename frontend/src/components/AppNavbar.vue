<template>
  <!-- Top Info Bar -->
  <div class="top-bar">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center gap-3">
        <span>📞 039 833 1038</span>
        <span class="separator">|</span>
        <span>🕐 Mon – Fri: 08:00 – 16:00</span>
        <span class="separator">|</span>
        <a href="mailto:Mailbox@ndz.gov.za">✉ Mailbox@ndz.gov.za</a>
        <span class="separator">|</span>
        <span>📍 Main Street, Creighton, 3263</span>
        <div class="ms-auto top-bar-social">
          <a
            href="https://facebook.com/ndzmunicipality"
            target="_blank"
            rel="noopener noreferrer"
            title="Facebook"
            aria-label="Follow us on Facebook"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
              <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
            </svg>
          </a>
          <a
            href="https://x.com/ndzmunicipality"
            target="_blank"
            rel="noopener noreferrer"
            title="X (Twitter)"
            aria-label="Follow us on X (Twitter)"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
              <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.147H2.865l8.875 11.631Z"/>
            </svg>
          </a>
          <a
            href="https://www.instagram.com/ndzmunicipality/"
            target="_blank"
            rel="noopener noreferrer"
            title="Instagram"
            aria-label="Follow us on Instagram"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
              <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
            </svg>
          </a>
          <a
            href="https://youtube.com/@ndzmunicipality"
            target="_blank"
            rel="noopener noreferrer"
            title="YouTube"
            aria-label="Follow us on YouTube"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
              <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.021.26-.01.104c-.048.519-.119 1.023-.22 1.402a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.565.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.003-.045A34 34 0 0 1 0 8.003l.002-.413.003-.045c.01-.157.026-.432.05-.758l.015-.195.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c1.16-.312 5.565-.334 6.18-.335zM6.4 5.209v5.582l4.875-2.791L6.4 5.209z"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Navbar -->
  <nav class="main-navbar navbar navbar-expand-lg">
    <div class="container">
      <router-link class="navbar-brand d-flex align-items-center gap-1" to="/">
        <img src="/logo.png" alt="NDZ Logo" class="brand-logo" />
       
      </router-link>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <router-link class="nav-link" to="/">Home</router-link>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">About Us</a>
            <ul class="dropdown-menu">
              <li><router-link class="dropdown-item" to="/mayors-office">Meet The Mayor</router-link></li>
              <li><router-link class="dropdown-item" to="/deputy-mayor">Meet The Deputy Mayor</router-link></li>
              <li><router-link class="dropdown-item" to="/meet-speaker">Meet The Speaker</router-link></li>
              <li><router-link class="dropdown-item" to="/councillors">Councillors</router-link></li>
              <!-- <li><a class="dropdown-item" href="#">Organogram</a></li>
              <li><a class="dropdown-item" href="#">5 Key Priorities</a></li> -->
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Departments</a>
            <ul class="dropdown-menu">
              <li><router-link class="dropdown-item" to="/municipal-manager">Office of the Municipal Manager</router-link></li>
              <li><router-link class="dropdown-item" to="/budget-treasury">Budget & Treasury Office</router-link></li>
              <li><router-link class="dropdown-item" to="/public-works">Public Works & Basic Services</router-link></li>
              <li><router-link class="dropdown-item" to="/development-town-planning-services">Development and Town Planning Services</router-link></li>
              <li><router-link class="dropdown-item" to="/community-services">Community and Social Services</router-link></li>
               <li><router-link class="dropdown-item" to="/corporate-services">Corporate Support Services</router-link></li>
              <!-- <li><a class="dropdown-item" href="#">Communications</a></li>
              <li><a class="dropdown-item" href="#">Supply Chain</a></li>
              <li><a class="dropdown-item" href="#">Financial Services</a></li>
              <li><router-link class="dropdown-item" to="/tourism">Tourism</router-link></li> -->
              
              
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">SCM</a>
            <ul class="dropdown-menu">
              <li class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="#">TENDERS</a>
                <ul class="dropdown-menu">
                  <li><router-link class="dropdown-item" to="/open-tenders">Open Tenders</router-link></li>
                  <li><router-link class="dropdown-item" to="/bid-documents">Bid Documents</router-link></li>
                  <li><router-link class="dropdown-item" to="/briefing-link">Briefing Link</router-link></li>
                  <li><router-link class="dropdown-item" to="/bid-documents">Erratum and Addendums</router-link></li>
                  <li><router-link class="dropdown-item" to="/bid-documents">Validity Extension</router-link></li>
                  <li><router-link class="dropdown-item" to="/closed-register">Closed Register</router-link></li>
                  <li><router-link class="dropdown-item" to="/closed-tenders">Closed Tenders</router-link></li>
                  <!-- <li><a class="dropdown-item" href="#">Tenders 2025-2026</a></li>
                  <li><a class="dropdown-item" href="#">Tenders 2024-2025</a></li>
                  <li><a class="dropdown-item" href="#">Tenders 2023-2024</a></li> -->
                </ul>
              </li>
              <li class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="#">FORMAL QUOTE</a>
                <ul class="dropdown-menu">
                  <li><router-link class="dropdown-item" to="/open-quotes">Open Quotes</router-link></li>
                  <li><router-link class="dropdown-item" to="/open-documents">Open Documents</router-link></li>
                   <li><router-link class="dropdown-item" to="/briefing-link">Briefing Link</router-link></li>
                    <li><router-link class="dropdown-item" to="/bid-documents">Erratum and Addendums</router-link></li>
                  <li><router-link class="dropdown-item" to="/bid-documents">Validity Extension</router-link></li>
                   <li><router-link class="dropdown-item" to="/closed-quotes">Closed Quotes</router-link></li>
                 <!--  <li><a class="dropdown-item" href="#">Quotes 2025-2026</a></li>
                  <li><a class="dropdown-item" href="#">Quotes 2024-2025</a></li>
                  <li><a class="dropdown-item" href="#">Quotes 2023-2024</a></li> -->
                </ul>
              </li>
              
              <li><a class="dropdown-item" href="#">INTENTION TO AWARD</a></li>
              <li><router-link class="dropdown-item" to="/contract-reporting">CONTRACT REPORTING</router-link></li>
              <li>
                <a
                  class="dropdown-item"
                  href="https://forms.cloud.microsoft/r/Rvv4zeUt9Y"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  SCM DATABASE FORMS <i class="bi bi-box-arrow-up-right ms-1 text-muted" style="font-size: 0.75rem;"></i>
                </a>
              </li>
              <!-- <li class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="#">OPENING/CLOSING REGISTER</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Opening-closing Register 2025-2026</a></li>
                  <li><a class="dropdown-item" href="#">Opening-closing Register 2024-2025</a></li>
                  <li><a class="dropdown-item" href="#">Opening-closing Register 2023-2024</a></li>
                </ul>
              </li> -->
              <!-- <li class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="#">OTHER SCM DOCUMENTS</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Led Database</a></li>
                  <li><a class="dropdown-item" href="#">Annexures & Mbd</a></li>
                  <li><a class="dropdown-item" href="#">Briefing Link</a></li>
                  <li><a class="dropdown-item" href="#">Erratum and Addendum</a></li> --
                  <li><a class="dropdown-item" href="#">Contracts</a></li>
                  <li><a class="dropdown-item" href="#">Scm Database Forms</a></li>
                </ul>
              </li> -->
            </ul>
          </li>

          <li class="nav-item">
            <router-link class="nav-link" to="/documents">Documents</router-link>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Media</a>
            <ul class="dropdown-menu">
              <li><router-link class="dropdown-item" to="/news">NDZ News</router-link></li>
              <li><router-link class="dropdown-item" to="/gallery">Event Gallery</router-link></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Career Centre</a>
            <ul class="dropdown-menu">
              <li><router-link class="dropdown-item" to="/open-vacancies">Open Vacancies</router-link></li>
              <li><router-link class="dropdown-item" to="/closed-vacancies">Closed Vacancies</router-link></li>
            </ul>
          </li>

          <li class="nav-item">
            <router-link class="nav-link" to="/contact">Contact Us</router-link>
          </li>

          <template v-if="!auth.isAuthenticated">
            <li class="nav-item ms-2">
              <router-link class="btn-gov" to="/login" style="margin-top:0.85rem;display:inline-block;">Login</router-link>
            </li>
          </template>
          <template v-else>
            <li class="nav-item ms-1">
              <router-link class="nav-link" to="/dashboard">My Portal</router-link>
            </li>
            <li class="nav-item ms-1">
              <button class="btn-gov-outline" style="margin-top:0.85rem;" @click="handleLogout">Logout</button>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

export default {
  name: 'AppNavbar',
  setup() {
    const auth    = useAuthStore()
    const router  = useRouter()
    async function handleLogout() {
      await auth.logout()
      router.push('/')
    }
    return { auth, handleLogout }
  },
}
</script>

<style scoped>
.dropdown-submenu {
  position: relative;
}
.dropdown-submenu > .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -1px;
  display: none;
}
.dropdown-submenu:hover > .dropdown-menu {
  display: block;
}
@media (max-width: 991.98px) {
  .dropdown-submenu > .dropdown-menu {
    position: static;
    display: block;
    margin-left: 1rem;
    border: none;
    box-shadow: none;
    background: transparent;
  }
}
</style>

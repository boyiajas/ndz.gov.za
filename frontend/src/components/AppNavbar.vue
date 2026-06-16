<template>
  <!-- Top Info Bar -->
  <div class="top-bar">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center gap-3">
        <span>📞 039 833 1038</span>
        <span class="separator">|</span>
        <span>🕐 Mon – Fri: 08:00 – 16:00</span>
        <span class="separator">|</span>
        <a href="mailto:helpdesk@ndz.gov.za">✉ helpdesk@ndz.gov.za</a>
        <span class="separator">|</span>
        <span>📍 Main Street, Creighton, 3263</span>
        <div class="ms-auto d-flex gap-2">
          <a href="#" title="Facebook">FB</a>
          <a href="#" title="Twitter">TW</a>
          <a href="#" title="Instagram">IG</a>
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
              <li><router-link class="dropdown-item" to="/mayors-office">Mayor's Office</router-link></li>
              <li><router-link class="dropdown-item" to="/municipal-manager">Municipal Manager</router-link></li>
              <li><router-link class="dropdown-item" to="/meet-speaker">Meet The Speaker</router-link></li>
              <li><router-link class="dropdown-item" to="/councillors">Councillors</router-link></li>
              <li><a class="dropdown-item" href="#">Organogram</a></li>
              <li><a class="dropdown-item" href="#">5 Key Priorities</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Departments</a>
            <ul class="dropdown-menu">
              <li><router-link class="dropdown-item" to="/corporate-services">Corporate Services</router-link></li>
              <li><router-link class="dropdown-item" to="/community-services">Community Services</router-link></li>
              <!-- <li><a class="dropdown-item" href="#">Communications</a></li>
              <li><a class="dropdown-item" href="#">Supply Chain</a></li>
              <li><a class="dropdown-item" href="#">Financial Services</a></li>
              <li><router-link class="dropdown-item" to="/tourism">Tourism</router-link></li> -->
              <li><router-link class="dropdown-item" to="/budget-treasury">Budget & Treasury</router-link></li>
              <li><router-link class="dropdown-item" to="/public-works">Public Works & Basic Services</router-link></li>
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
              
              <!-- <li><a class="dropdown-item" href="#">QUOTE DOCUMENTS</a></li> -->
              <li><a class="dropdown-item" href="#">INTENTION TO AWARD</a></li>
              <li><router-link class="dropdown-item" to="/contract-reporting">CONTRACT REPORTING</router-link></li>
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
              <li><router-link class="dropdown-item" to="#">Internships</router-link></li>
              <li><router-link class="dropdown-item" to="#">Vacancies</router-link></li>
              <li><router-link class="dropdown-item" to="#">Youth Opportunities</router-link></li>
            </ul>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="#Q">Our Partners</router-link>
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
</style>

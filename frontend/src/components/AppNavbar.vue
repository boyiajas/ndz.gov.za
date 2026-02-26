<template>
  <!-- Top Info Bar -->
  <div class="top-bar">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center gap-3">
        <span>📞 +27 (0) 11 000 0000</span>
        <span class="separator">|</span>
        <span>🕐 Mon – Fri: 08:00 – 16:00</span>
        <span class="separator">|</span>
        <a href="mailto:helpdesk@ndz.gov.za">✉ helpdesk@ndz.gov.za</a>
        <span class="separator">|</span>
        <span>📍 123 Government Ave, Pretoria</span>
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
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">About NDZ</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Mayor's Office</a></li>
              <li><a class="dropdown-item" href="#">Municipal Manager</a></li>
              <li><a class="dropdown-item" href="#">Councillors</a></li>
              <li><a class="dropdown-item" href="#">Organogram</a></li>
              <li><a class="dropdown-item" href="#">5 Key Priorities</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Council Services</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Financial Services</a></li>
              <li><a class="dropdown-item" href="#">Infrastructure & Public Works</a></li>
              <li><a class="dropdown-item" href="#">Community Services</a></li>
              <li><a class="dropdown-item" href="#">Human Resources</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">Documents</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">Media</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">Contact Us</a>
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

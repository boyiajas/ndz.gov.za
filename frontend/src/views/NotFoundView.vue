<template>
  <div style="background:var(--primary);padding:5rem 0;text-align:center;color:var(--white);min-height:50vh;display:flex;align-items:center;">
    <div class="container">
      <div style="font-size:4rem;margin-bottom:1rem;">📄</div>
      <h1 style="font-size:2.5rem;font-weight:800;margin-bottom:1rem;">404 — Page Not Found</h1>
      <p style="color:rgba(255,255,255,0.7);font-size:1rem;margin-bottom:2rem;">
        The page you are looking for doesn't exist or has been moved.
      </p>
      <router-link to="/" class="btn-gov" style="background:var(--accent);border-color:var(--accent);">← Back to Home</router-link>
    </div>
  </div>
</template>
<script>
export default { name: 'NotFoundView' }
</script>

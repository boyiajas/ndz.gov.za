<template>
  <div class="page-wrapper">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Event Gallery</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item"><span style="color: rgba(255,255,255,0.7);">Media</span></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Event Gallery</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Gallery Content -->
    <section class="gallery-section" style="padding: 5rem 0;">
      <div class="container">
        <div class="row g-4 mb-4">
          <!-- Gallery Item 1 -->
          <div class="col-md-6 col-lg-4">
            <div class="gallery-card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #fff;">
              <div class="img-wrapper" style="width: 100%; height: 350px; overflow: hidden;">
                <img src="/img/gallery/gallery_1.jpg" alt="Event Image 1" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
              </div>
            </div>
          </div>
          <!-- Gallery Item 2 -->
          <div class="col-md-6 col-lg-4">
            <div class="gallery-card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #fff;">
              <div class="img-wrapper" style="width: 100%; height: 350px; overflow: hidden;">
                <img src="/img/gallery/gallery_2.jpg" alt="Event Image 2" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
              </div>
            </div>
          </div>
          <!-- Gallery Item 3 -->
          <div class="col-md-6 col-lg-4">
            <div class="gallery-card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #fff;">
              <div class="img-wrapper" style="width: 100%; height: 350px; overflow: hidden;">
                <img src="/img/gallery/gallery_3.jpg" alt="Event Image 3" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
              </div>
            </div>
          </div>
          <!-- Gallery Item 4 -->
          <div class="col-md-6 col-lg-4">
            <div class="gallery-card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #fff;">
              <div class="img-wrapper" style="width: 100%; height: 350px; overflow: hidden;">
                <img src="/img/gallery/gallery_4.jpg" alt="Event Image 4" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
              </div>
            </div>
          </div>
          <!-- Gallery Item 5 -->
          <div class="col-md-6 col-lg-4">
            <div class="gallery-card" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #fff;">
              <div class="img-wrapper" style="width: 100%; height: 350px; overflow: hidden;">
                <img src="/img/gallery/gallery_5.jpg" alt="Event Image 5" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
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
  name: 'EventGalleryView'
}
</script>

<style scoped>
.img-wrapper:hover img {
  transform: scale(1.05);
}
</style>

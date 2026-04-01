<template>
  <div class="page-wrapper">
    <!-- Page Header -->
    <div class="page-header" style="background: var(--primary, #004d40); color: #fff; padding: 4rem 0; text-align: left;">
      <div class="container">
        <h1 style="margin: 0; font-size: 2.5rem; font-weight: 700;">Contact Us</h1>
        <p style="margin-top: 0.5rem; font-size: 1.1rem; opacity: 0.8;">Dr Nkosazana Dlamini-Zuma Local Municipality</p>

        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" style="margin-top: 1.5rem; display: flex; justify-content: flex-start;">
          <ol class="breadcrumb" style="margin: 0; font-size: 0.95rem; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 50px;">
            <li class="breadcrumb-item"><router-link to="/" style="color: rgba(255,255,255,0.9); text-decoration: none;">Home</router-link></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;">Contact Us</li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Contact Info Cards -->
    <section class="contact-info-section" style="padding: 5rem 0;">
      <div class="container">
        <div class="row align-items-center mb-5 pb-5 border-bottom">
          <div class="col-lg-3">
            <h2 style="font-weight: 700; color: var(--primary, #2c3e50); font-size: 1.8rem; line-height: 1.2;">Departmental<br/>Contact Info</h2>
          </div>
          <div class="col-lg-9 mt-4 mt-lg-0">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="dept-card" style="background: #fff; padding: 2rem; border-radius: 4px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                  <div class="dept-badge" style="background: var(--primary); color: #fff; display: inline-block; padding: 0.4rem 1rem; font-size: 0.85rem; font-weight: 600; margin-bottom: 1.5rem; border-radius: 2px;">Corporate Services</div>
                  <div class="dept-detail" style="margin-bottom: 0.8rem; color: var(--text-mid); font-size: 0.95rem;">
                    <i class="bi bi-envelope" style="margin-right: 0.5rem;"></i> corporate@ndz.gov.za
                  </div>
                  <div class="dept-detail" style="color: var(--text-mid); font-size: 0.95rem;">
                    <i class="bi bi-telephone" style="margin-right: 0.5rem;"></i> +27 (0) 11 000 0000
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="dept-card" style="background: #fff; padding: 2rem; border-radius: 4px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                  <div class="dept-badge" style="background: var(--primary); color: #fff; display: inline-block; padding: 0.4rem 1rem; font-size: 0.85rem; font-weight: 600; margin-bottom: 1.5rem; border-radius: 2px;">Finance</div>
                  <div class="dept-detail" style="margin-bottom: 0.8rem; color: var(--text-mid); font-size: 0.95rem;">
                    <i class="bi bi-envelope" style="margin-right: 0.5rem;"></i> finance@ndz.gov.za
                  </div>
                  <div class="dept-detail" style="color: var(--text-mid); font-size: 0.95rem;">
                    <i class="bi bi-telephone" style="margin-right: 0.5rem;"></i> +27 (0) 11 000 0000
                  </div>
                </div>
              </div>
            </div>
            <!-- dots indicator placeholder -->
            <div class="text-center mt-4">
              <span style="display:inline-block; width:6px; height:6px; background:#ccc; border-radius:50%; margin:0 3px;"></span>
              <span style="display:inline-block; width:6px; height:6px; background:#ccc; border-radius:50%; margin:0 3px;"></span>
              <span style="display:inline-block; width:15px; height:6px; background:var(--primary); border-radius:3px; margin:0 3px;"></span>
            </div>
          </div>
        </div>

        <div class="row pt-2">
          <!-- Left Col -->
          <div class="col-lg-5 pe-lg-5 mb-5 mb-lg-0">
            <h2 style="font-weight: 700; color: var(--primary, #2c3e50); margin-bottom: 1.5rem;">Get In Touch</h2>
            <p style="color: var(--text-mid); margin-bottom: 2rem; font-size: 0.95rem; line-height: 1.8;">
              We really appreciate you taking the time to get in touch. Please fill in the form below.
            </p>
            
            <div class="contact-methods" style="margin-bottom: 3rem;">
              <div style="margin-bottom: 1.2rem; display: flex; align-items:flex-start;">
                <i class="bi bi-geo-alt-fill" style="color: var(--primary); font-size: 1.2rem; margin-right: 1rem; margin-top: 0.1rem;"></i>
                <div>
                  <strong style="color: var(--text-dark);">Address:</strong>
                  <span style="color: var(--text-mid); display: block; margin-top: 0.2rem; font-size: 0.95rem;">123 Government Ave, Pretoria, South Africa.</span>
                </div>
              </div>
              <div style="margin-bottom: 1.2rem; display: flex; align-items:flex-start;">
                <i class="bi bi-telephone-fill" style="color: var(--primary); font-size: 1.2rem; margin-right: 1rem; margin-top: 0.1rem;"></i>
                <div>
                  <strong style="color: var(--text-dark);">Call:</strong>
                  <span style="color: var(--text-mid); display: block; margin-top: 0.2rem; font-size: 0.95rem;">+27 (0) 11 000 0000 / +27 (0) 79 000 0000 <span style="opacity:0.7;">(WhatsApp Only)</span></span>
                </div>
              </div>
              <div style="margin-bottom: 1.2rem; display: flex; align-items:flex-start;">
                <i class="bi bi-envelope-fill" style="color: var(--primary); font-size: 1.2rem; margin-right: 1rem; margin-top: 0.1rem;"></i>
                <div>
                  <strong style="color: var(--text-dark);">Mail:</strong>
                  <span style="color: var(--text-mid); display: block; margin-top: 0.2rem; font-size: 0.95rem;">helpdesk@ndz.gov.za</span>
                </div>
              </div>
            </div>

            <div>
              <h4 style="font-weight: 700; color: var(--primary); margin-bottom: 0.8rem; font-size: 1.1rem;">Opening Hours</h4>
              <p style="color: var(--text-mid); font-size: 0.95rem;">Open Daily 08h00 to 16h00</p>
            </div>
          </div>

          <!-- Right Col -->
          <div class="col-lg-7">
            <div class="contact-form-card" style="background: #fff; padding: 3rem; border-radius: 4px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
              <h3 style="font-weight: 700; color: var(--primary); margin-bottom: 2rem;">Leave your message</h3>
              <form>
                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Name" style="padding: 0.8rem; border: 1px solid #dee2e6; border-radius: 4px; font-size: 0.95rem;">
                  </div>
                  <div class="col-md-6">
                    <input type="email" class="form-control" placeholder="Email" style="padding: 0.8rem; border: 1px solid #dee2e6; border-radius: 4px; font-size: 0.95rem;">
                  </div>
                </div>
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Subject" style="padding: 0.8rem; border: 1px solid #dee2e6; border-radius: 4px; font-size: 0.95rem;">
                </div>
                <div class="mb-3">
                  <select class="form-select" style="padding: 0.8rem; border: 1px solid #dee2e6; border-radius: 4px; font-size: 0.95rem; color: #6c757d;">
                    <option selected disabled>Select Department</option>
                    <option value="corporate">Corporate Services</option>
                    <option value="finance">Finance</option>
                    <option value="general">General Inquiry</option>
                  </select>
                </div>
                <div class="mb-4">
                  <textarea class="form-control" rows="5" placeholder="Write your message..." style="padding: 0.8rem; border: 1px solid #dee2e6; border-radius: 4px; font-size: 0.95rem;"></textarea>
                </div>
                <!-- ReCaptcha Fake -->
                <div class="mb-4">
                  <div style="border: 1px solid #d3d3d3; background: #fafafa; border-radius: 3px; padding: 10px; display: inline-flex; align-items: center; width: 304px; justify-content: space-between;">
                    <div style="display: flex; align-items: center;">
                      <input type="checkbox" id="recaptcha-dummy" style="width: 28px; height: 28px; margin-right: 12px; border: 2px solid #c1c1c1; border-radius: 2px; cursor: pointer;">
                      <label for="recaptcha-dummy" style="font-family: Roboto, helvetica, arial, sans-serif; font-size: 14px; font-weight: 400; color: #222; margin: 0; cursor: pointer;">I'm not a robot</label>
                    </div>
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                      <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" width="32" height="32" alt="reCAPTCHA logo">
                      <div style="font-family: Roboto, helvetica, arial, sans-serif; font-size: 10px; color: #555; margin-top: 4px;">reCAPTCHA</div>
                      <div style="font-family: Roboto, helvetica, arial, sans-serif; font-size: 8px; color: #555; display: flex; gap: 4px;">
                        <a href="#" style="color: #555; text-decoration: none;">Privacy</a> - <a href="#" style="color: #555; text-decoration: none;">Terms</a>
                      </div>
                    </div>
                  </div>
                </div>
                <button type="button" class="btn-gov" style="background: var(--primary); color: #fff; padding: 0.8rem 2rem; border-radius: 4px; font-weight: 600; border: none;">Send Message</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Map Section -->
    <div class="map-container" style="line-height: 0;">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d113066.08271701386!2d29.6749721!3d-29.8058204!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1ef3bb6d6d2b38fb%3A0x6b1cc8c8d8b6da2b!2sDr%20Nkosazana%20Dlamini%20Zuma%20Local%20Municipality!5e0!3m2!1sen!2sza!4v1700000000000!5m2!1sen!2sza" 
        width="100%" 
        height="450" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ContactView'
}
</script>

<style scoped>
.form-control:focus, .form-select:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 0.25rem rgba(0, 77, 64, 0.25);
}
</style>

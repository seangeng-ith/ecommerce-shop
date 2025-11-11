
<?php use App\Core\Helpers; if(!function_exists('base_url')){ function base_url($p=''){ return Helpers::base_url($p);} } ?>
<?php require_once 'partials/header.php'; ?>

<section class="contact-section">
  <div class="contact-wrapper">
    <div class="contact-container">
      <h1 class="contact-title">Contact us</h1>
      <div class="contact-divider"></div>
      
      <div class="contact-info">
        <div class="contact-item">
          <p class="contact-label">Phone number:</p>
          <p class="contact-value">123 456 789</p>
        </div>
        
        <div class="contact-item">
          <p class="contact-label">Email address:</p>
          <p class="contact-value"><a href="mailto:info@email.com">info@email.com</a></p>
        </div>
        
        <div class="contact-item">
          <p class="contact-message">We work 24/7 to answer your questions</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'partials/footer.php'; ?>
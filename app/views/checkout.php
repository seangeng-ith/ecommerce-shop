
<?php use App\Core\Helpers; if(!function_exists('base_url')){ function base_url($p=''){ return Helpers::base_url($p);} } ?>
<?php require_once 'partials/header.php'; ?>

<section class="checkout-section">
  <div class="checkout-wrapper">
    <div class="checkout-container">
      <h1 class="checkout-title">Check Out</h1>
      <div class="checkout-divider"></div>
      
      <form method="POST" class="checkout-form">
        <div class="form-row">
          <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" class="form-input" placeholder="Name" required>
          </div>
          
          <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-input" placeholder="Email" required>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="phone" class="form-label">Phone</label>
            <input type="tel" id="phone" name="phone" class="form-input" placeholder="Phone" required>
          </div>
          
          <div class="form-group">
            <label for="city" class="form-label">City</label>
            <input type="text" id="city" name="city" class="form-input" placeholder="City" required>
          </div>
        </div>
        
        <div class="form-group full-width">
          <label for="address" class="form-label">Address</label>
          <input type="text" id="address" name="address" class="form-input" placeholder="Address" required>
        </div>
        
        <div class="checkout-actions">
          <button type="submit" class="checkout-btn">Checkout</button>
        </div>
      </form>
    </div>
  </div>
</section>

<?php require_once 'partials/footer.php'; ?>
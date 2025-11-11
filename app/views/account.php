
<?php use App\Core\Helpers; if(!function_exists('base_url')){ function base_url($p=''){ return Helpers::base_url($p);} } ?>
<?php require_once 'partials/header.php'; ?>

<section class="account-section">
  <div class="account-wrapper">
    <div class="account-container">
      
      <!-- Account Info Tab -->
      <div class="account-tab account-info-tab active">
        <div class="account-info-container">
          <h2 class="tab-title">Account Info</h2>
          <div class="account-divider"></div>
          
          <div class="account-details">
            <div class="detail-item">
              <p class="detail-label">Name</p>
              <p class="detail-value">John</p>
            </div>
            
            <div class="detail-item">
              <p class="detail-label">Email</p>
              <p class="detail-value">john@email.com</p>
            </div>
            
            <div class="account-links">
              <a href="#" class="account-link">Your orders</a>
              <a href="<?= base_url('index.php?page=logout') ?>" class="account-link logout-link">Logout</a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Change Password Tab -->
      <div class="change-password-container">
        <h2 class="tab-title">Change Password</h2>
        <div class="account-divider"></div>
        
        <form method="POST" class="password-form">
          <div class="form-group">
            <label for="current_password" class="form-label">Password</label>
            <input type="password" id="current_password" name="current_password" class="form-input" placeholder="Password" required>
          </div>
          
          <div class="form-group">
            <label for="new_password" class="form-label">Confirm Password</label>
            <input type="password" id="new_password" name="new_password" class="form-input" placeholder="Password" required>
          </div>
          
          <button type="submit" class="change-password-btn">Change Password</button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Your Orders Section Full Width -->
<section class="your-orders-section-full">
  <div class="orders-container">
    <h2 class="orders-main-title">Your Orders</h2>
    <div class="orders-main-divider"></div>
    
    <div class="orders-table-wrapper">
      <table class="orders-table">
        <thead>
          <tr class="orders-header">
            <th>Product</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr class="order-row">
            <td class="product-cell">
              <div class="product-info">
                <img src="<?= base_url('img/shoe.png') ?>" alt="White Shoes" class="product-img">
                <span class="product-name">White Shoes</span>
              </div>
            </td>
            <td class="date-cell">2036-5-8</td>
          </tr>
          <tr class="order-row">
            <td class="product-cell">
              <div class="product-info">
                <img src="<?= base_url('img/shoe2.png') ?>" alt="Black Shoes" class="product-img">
                <span class="product-name">Black Shoes</span>
              </div>
            </td>
            <td class="date-cell">2036-4-15</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>



<?php use App\Core\Helpers; if(!function_exists('base_url')){ function base_url($p=''){ return Helpers::base_url($p);} } ?>
<?php require_once 'partials/header.php'; ?>

<section class="contact-section">
  <div class="contact-wrapper">
    <div class="contact-container">
      <h1 class="contact-title">Contact us</h1>
      <div class="contact-divider"></div>

      <?php $sent = $sent ?? false; $errors = $errors ?? []; $data = $data ?? []; ?>
      <?php if ($sent): ?>
        <div class="contact-success">
          <p>Thank you <?= htmlspecialchars($data['name'] ?? '') ?>.</p>
          <p>We received your message<?= ($data['subject'] ?? '') !== '' ? ' about ' . htmlspecialchars($data['subject']) : '' ?>.</p>
          <?php if (($data['profession'] ?? '') !== ''): ?>
            <p>Your profession: <?= htmlspecialchars($data['profession']) ?></p>
          <?php endif; ?>
          <a class="contact-btn" href="<?= base_url('index.php') ?>">Go to Home</a>
        </div>
      <?php else: ?>
        <?php if ($errors): ?>
          <div class="contact-errors">
            <?php foreach ($errors as $e): ?><p><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="contact-grid">
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
            <p class="contact-message">We respond within 24 hours</p>
          </div>
        </div>

          <form method="post" class="contact-form">
            <div class="form-group">
              <label class="form-label" for="name">Full name</label>
              <input class="form-input" type="text" id="name" name="name" placeholder="Your name" value="<?= htmlspecialchars($data['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="email">Email</label>
              <input class="form-input" type="email" id="email" name="email" placeholder="you@email.com" value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="profession">Profession</label>
              <select class="form-input" id="profession" name="profession">
                <?php $opts = ['','Student','Engineer','Designer','Developer','Business Owner','Other']; $cur = (string)($data['profession'] ?? ''); foreach($opts as $o): ?>
                  <option value="<?= htmlspecialchars($o) ?>" <?= $cur === $o ? 'selected' : '' ?>><?= $o === '' ? 'Select profession' : htmlspecialchars($o) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label" for="subject">Subject</label>
              <input class="form-input" type="text" id="subject" name="subject" placeholder="Subject" value="<?= htmlspecialchars($data['subject'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label" for="message">Message</label>
              <textarea class="form-textarea" id="message" name="message" rows="5" placeholder="Write your message" required><?= htmlspecialchars($data['message'] ?? '') ?></textarea>
            </div>
            <button class="contact-btn" type="submit">Send message</button>
          </form>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

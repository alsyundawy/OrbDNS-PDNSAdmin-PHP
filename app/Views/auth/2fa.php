<?php

use App\Core\Helper;

/** @var string $qr */
$qr = $qr ?? '';
?>
<div class="container" style="max-width:480px;">
  <div class="card bg-black border-secondary p-4 shadow-lg">
    <h1 class="h4 mb-3">Verifikasi 2FA</h1>
    <?php if ($qr !== '') : ?>
      <div class="text-center mb-3">
        <img src="<?= Helper::e($qr) ?>" alt="TOTP QR" class="img-fluid rounded bg-white p-2">
      </div>
    <?php endif; ?>
    <form method="POST" action="/2fa"><?= Helper::csrfField() ?>
      <div class="mb-3">
        <label for="otp_code" class="form-label">Kode OTP</label>
        <input
          id="otp_code"
          class="form-control"
          type="text"
          name="otp_code"
          inputmode="numeric"
          maxlength="6"
          required
        >
      </div>
      <button class="btn btn-primary w-100" type="submit">Verifikasi</button>
    </form>
  </div>
</div>

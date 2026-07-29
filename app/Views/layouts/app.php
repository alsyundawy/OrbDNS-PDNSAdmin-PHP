<?php use App\Core\Helper; $flash = Helper::flashGet(); ?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= Helper::e($_SESSION['csrf_token'] ?? '') ?>">
  <title><?= Helper::e($title ?? 'PDNS Admin') ?></title>
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="container py-4">
  <?php if ($flash): ?><div class="alert alert-<?= Helper::e($flash['type']) ?>"><?= Helper::e($flash['message']) ?></div><?php endif; ?>
  <?php require $viewFile; ?>
</div>
<script nonce="<?= Helper::e($_SESSION['csp_nonce'] ?? '') ?>" src="/assets/js/jquery.min.js"></script>
<script nonce="<?= Helper::e($_SESSION['csp_nonce'] ?? '') ?>" src="/assets/js/app.js"></script>
</body>
</html>

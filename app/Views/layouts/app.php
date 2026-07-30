<?php
use App\Core\Auth;
use App\Core\Helper;

/**
 * @var string $title
 * @var string $viewFile
 */
$title = $title ?? 'PDNS Admin';
$viewFile = $viewFile ?? '';
$flash = Helper::flashGet();
$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= Helper::e($_SESSION['csrf_token'] ?? '') ?>">
  <title><?= Helper::e($title) ?></title>
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<?php if ($currentUser !== null): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-secondary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="/">OrbDNS Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="/zones">Zones</a></li>
        <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="/users">Users</a></li>
          <li class="nav-item"><a class="nav-link" href="/logs">Logs</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-3">
        <span class="text-secondary small">Logged in as <strong class="text-light"><?= Helper::e($currentUser['username']) ?></strong></span>
        <a href="/logout" class="btn btn-sm btn-outline-danger">Logout</a>
      </div>
    </div>
  </div>
</nav>
<?php endif; ?>

<div class="container py-4">
  <?php if ($flash): ?>
    <div class="alert alert-<?= Helper::e($flash['type']) ?> alert-dismissible fade show" role="alert">
      <?= Helper::e($flash['message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php if ($viewFile !== '' && file_exists($viewFile)) { require_once $viewFile; } ?>
</div>

<script nonce="<?= Helper::e($_SESSION['csp_nonce'] ?? '') ?>" src="/assets/js/bootstrap.bundle.min.js"></script>
<script nonce="<?= Helper::e($_SESSION['csp_nonce'] ?? '') ?>" src="/assets/js/jquery.min.js"></script>
<script nonce="<?= Helper::e($_SESSION['csp_nonce'] ?? '') ?>" src="/assets/js/app.js"></script>
</body>
</html>

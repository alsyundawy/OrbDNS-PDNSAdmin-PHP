<?php

use App\Core\Helper;

/** @var string $title */
$title = $title ?? 'Login';
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= Helper::e($title) ?></title>
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-dark text-light d-flex align-items-center" style="min-height:100vh;">
<div class="container" style="max-width:420px;">
  <div class="card bg-black border-secondary p-4 shadow-lg">
    <h1 class="h4 mb-3">PDNS Admin Login</h1>
    <form method="POST" action="/login"><?= Helper::csrfField() ?>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input id="username" class="form-control" type="text" name="username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" class="form-control" type="password" name="password" required>
      </div>
      <button class="btn btn-primary w-100" type="submit">Masuk</button>
    </form>
  </div>
</div>
</body>
</html>

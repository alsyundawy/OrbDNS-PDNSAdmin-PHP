<?php

use App\Core\Helper;

/** @var array $users */
$users = $users ?? [];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">User Management</h1>
  <a href="/users/create" class="btn btn-primary">+ Tambah User</a>
</div>
<div class="card p-3">
  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>2FA</th>
          <th>Last Login</th>
          <th>Created At</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u) : ?>
          <tr>
            <td><?= Helper::e($u['id'] ?? '') ?></td>
            <td><strong><?= Helper::e($u['username'] ?? '') ?></strong></td>
            <td><?= Helper::e($u['email'] ?? '') ?></td>
            <td>
              <span class="badge <?= ($u['role'] ?? '') === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                <?= Helper::e($u['role'] ?? 'user') ?>
              </span>
            </td>
            <td>
              <span class="badge <?= (int) ($u['is_active'] ?? 0) === 1 ? 'bg-success' : 'bg-warning' ?>">
                <?= (int) ($u['is_active'] ?? 0) === 1 ? 'Aktif' : 'Non-aktif' ?>
              </span>
            </td>
            <td>
              <span class="badge <?= (int) ($u['totp_enabled'] ?? 0) === 1 ? 'bg-info' : 'bg-dark' ?>">
                <?= (int) ($u['totp_enabled'] ?? 0) === 1 ? 'Enabled' : 'Disabled' ?>
              </span>
            </td>
            <td><?= Helper::e($u['last_login_at'] ?? '—') ?></td>
            <td><?= Helper::e($u['created_at'] ?? '—') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php use App\Core\Helper; ?>
<div class="container" style="max-width:540px;">
  <div class="card p-4 shadow-lg">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4 mb-0">Tambah User Baru</h1>
      <a href="/users" class="btn btn-sm btn-outline-secondary">Kembali</a>
    </div>
    <form method="POST" action="/users"><?= Helper::csrfField() ?>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input id="username" class="form-control" type="text" name="username" required autocomplete="off">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" class="form-control" type="email" name="email" required autocomplete="off">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password (Min. 8 Karakter)</label>
        <input id="password" class="form-control" type="password" name="password" minlength="8" required>
      </div>
      <div class="mb-3">
        <label for="role" class="form-label">Role Akses</label>
        <select id="role" class="form-select" name="role">
          <option value="user">User (Regular)</option>
          <option value="admin">Administrator (Superuser)</option>
        </select>
      </div>
      <button class="btn btn-primary w-100" type="submit">Simpan User</button>
    </form>
  </div>
</div>

<?php

use App\Core\Helper;

/** @var array $logs */
$logs = $logs ?? [];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Activity Logs</h1>
  <a href="/logs/export" class="btn btn-outline-light">Export CSV</a>
</div>
<div class="card p-3">
  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Action</th>
          <th>Target</th>
          <th>IP</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $log) : ?>
          <tr>
            <td><?= Helper::e($log['id'] ?? '') ?></td>
            <td><?= Helper::e($log['username'] ?? '') ?></td>
            <td><?= Helper::e($log['action'] ?? '') ?></td>
            <td><?= Helper::e($log['target'] ?? '') ?></td>
            <td><?= Helper::e($log['ip_address'] ?? '') ?></td>
            <td><?= Helper::e($log['created_at'] ?? '') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

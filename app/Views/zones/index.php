<?php

use App\Core\Helper;

?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">DNS Zones</h1>
  <div class="d-flex gap-2">
    <span id="pdns-status" class="badge text-bg-secondary">Checking PDNS...</span>
    <a href="/zones/create" class="btn btn-primary">Tambah Zone</a>
  </div>
</div>
<div class="card p-3">
  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead>
        <tr>
          <th>Name</th>
          <th>Kind</th>
          <th>Serial</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="zones-table-body">
        <tr>
          <td colspan="4">Loading...</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<script nonce="<?= Helper::e($_SESSION['csp_nonce'] ?? '') ?>" src="/assets/js/zones.js"></script>

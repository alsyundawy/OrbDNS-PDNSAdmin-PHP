<?php

/**
 * @var array $zones
 * @var array $statsMap
 * @var array $recentLogs
 */
?>
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card p-3">
      <h2 class="h6">Total Zones</h2>
      <div class="display-6"><?= count($zones) ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3">
      <h2 class="h6">UDP Queries</h2>
      <div class="display-6"><?= (int) ($statsMap['udp-queries'] ?? 0) ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3">
      <h2 class="h6">Recent Logs</h2>
      <div class="display-6"><?= count($recentLogs) ?></div>
    </div>
  </div>
</div>

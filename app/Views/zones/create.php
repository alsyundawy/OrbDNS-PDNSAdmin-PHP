<?php use App\Core\Helper; ?>
<div class="card p-4">
  <h1 class="h4 mb-3">Create Zone</h1>
  <form method="POST" action="/zones"><?= Helper::csrfField() ?>
    <div class="mb-3"><label class="form-label">Zone Name</label><input class="form-control" type="text" name="name" required></div>
    <div class="mb-3"><label class="form-label">Kind</label><select class="form-select" name="kind"><option value="Native">Native</option><option value="Master">Master</option><option value="Slave">Slave</option></select></div>
    <div class="mb-3"><label class="form-label">Masters</label><input class="form-control" type="text" name="masters"></div>
    <div class="mb-3"><label class="form-label">Nameservers</label><input class="form-control" type="text" name="nameservers"></div>
    <button class="btn btn-primary" type="submit">Save</button>
  </form>
</div>

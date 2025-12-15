<div class="container py-4">
  <h1 class="mb-3">Procurement Dashboard</h1>
  <p>Welcome, <?= esc($user['name'] ?? 'User'); ?> (<?= esc($user['role'] ?? ''); ?>)</p>
  <hr>
  <div class="row">
    <div class="col-md-6">
      <h5>Requisitions</h5>
      <pre><?= esc(json_encode($reqStats ?? [], JSON_PRETTY_PRINT)); ?></pre>
      <h6>Pending Approval</h6>
      <pre><?= esc(json_encode($pendingApproval ?? [], JSON_PRETTY_PRINT)); ?></pre>
    </div>
    <div class="col-md-6">
      <h5>Purchase Orders</h5>
      <pre><?= esc(json_encode($poStats ?? [], JSON_PRETTY_PRINT)); ?></pre>
      <h6>Pending Delivery</h6>
      <pre><?= esc(json_encode($pendingDelivery ?? [], JSON_PRETTY_PRINT)); ?></pre>
      <h6>Overdue POs</h6>
      <pre><?= esc(json_encode($overduePOs ?? [], JSON_PRETTY_PRINT)); ?></pre>
    </div>
  </div>
</div>
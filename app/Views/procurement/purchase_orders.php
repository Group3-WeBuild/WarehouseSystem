<div class="container py-4">
  <h1 class="mb-3">Purchase Orders</h1>
  <p>User: <?= esc($user['name'] ?? ''); ?> (<?= esc($user['role'] ?? ''); ?>)</p>
  <hr>
  <h5>All Purchase Orders</h5>
  <pre><?= esc(json_encode($purchaseOrders ?? [], JSON_PRETTY_PRINT)); ?></pre>
</div>
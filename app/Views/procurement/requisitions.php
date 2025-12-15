<div class="container py-4">
  <h1 class="mb-3">Purchase Requisitions</h1>
  <p>User: <?= esc($user['name'] ?? ''); ?> (<?= esc($user['role'] ?? ''); ?>)</p>
  <hr>
  <h5>All Requisitions</h5>
  <pre><?= esc(json_encode($requisitions ?? [], JSON_PRETTY_PRINT)); ?></pre>
</div>
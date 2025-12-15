<div class="container py-4">
  <h1>Batch Tracking</h1>
  <p>User: <?= esc($user['name'] ?? ''); ?> (<?= esc($user['role'] ?? ''); ?>)</p>
  <hr>
  <h5>Active Batches</h5>
  <pre><?= esc(json_encode($activeBatches ?? [], JSON_PRETTY_PRINT)); ?></pre>
  <h5>Expiring Soon</h5>
  <pre><?= esc(json_encode($expiringBatches ?? [], JSON_PRETTY_PRINT)); ?></pre>
  <h5>Quarantined</h5>
  <pre><?= esc(json_encode($quarantined ?? [], JSON_PRETTY_PRINT)); ?></pre>
</div>
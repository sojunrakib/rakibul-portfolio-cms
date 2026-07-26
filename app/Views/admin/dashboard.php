<section class="admin-section">
  <div class="admin-heading">
    <p>Overview</p>
    <h1>Dashboard</h1>
  </div>
  <div class="stats-row">
    <article><span>Projects</span><strong><?= e($stats['projects']) ?></strong></article>
    <article><span>Skills</span><strong><?= e($stats['skills']) ?></strong></article>
    <article><span>Messages</span><strong><?= e($stats['messages']) ?></strong></article>
    <article><span>Unread</span><strong><?= e($stats['unread']) ?></strong></article>
  </div>
  <div class="module-grid">
    <?php foreach ($modules as $key => $module): ?>
      <a href="/admin/<?= e($key) ?>">
        <strong><?= e($module['label']) ?></strong>
        <span><?= e($module['table']) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<?php $user = \App\Core\Auth::user(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Admin') ?> · Rakibul CMS</title>
  <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body>
  <div class="admin-shell">
    <aside class="admin-sidebar">
      <a class="admin-brand" href="/admin">RH CMS</a>
      <nav>
        <a href="/admin">Dashboard</a>
        <?php foreach ($modules as $key => $moduleConfig): ?>
          <a href="/admin/<?= e($key) ?>"><?= e($moduleConfig['label']) ?></a>
        <?php endforeach; ?>
      </nav>
    </aside>
    <main class="admin-main">
      <header class="admin-topbar">
        <div><p>Signed in as</p><strong><?= e($user['name'] ?? 'Admin') ?></strong></div>
        <div class="topbar-actions">
          <a class="admin-button muted" href="<?= e(url('/')) ?>">View Portfolio</a>
          <form method="post" action="/admin/logout"><?= csrf_field() ?><button type="submit">Logout</button></form>
        </div>
      </header>
      <?php foreach (flash() as $type => $message): ?><div class="admin-flash <?= e($type) ?>"><?= e($message) ?></div><?php endforeach; ?>
      <?= $content ?>
    </main>
  </div>
  <script src="<?= e(asset('js/admin.js')) ?>" defer></script>
</body>
</html>

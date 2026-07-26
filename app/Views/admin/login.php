<form class="login-card" method="post" action="/admin/login">
  <?= csrf_field() ?>
  <span class="admin-mark">RH</span>
  <h1>Portfolio Admin</h1>
  <p>Manage Rakibul Hasan's public portfolio content securely.</p>
  <?php foreach (flash() as $type => $message): ?><div class="admin-flash <?= e($type) ?>"><?= e($message) ?></div><?php endforeach; ?>
  <label>Email<input type="email" name="email" required autofocus value="admin@example.com"></label>
  <label>Password<input type="password" name="password" required placeholder="ChangeMe123!"></label>
  <button type="submit">Sign in</button>
</form>

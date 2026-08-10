<?php
$post = $post ?? [];
?>
<header class="site-header" data-header>
  <nav class="nav-shell" aria-label="Primary navigation">
    <a class="brand" href="<?= e(url('/')) ?>" aria-label="Rakibul Hasan home"><span><?= e(setting($settings, 'logo_text', 'RH')) ?></span><?= e(setting($settings, 'site_name', 'Rakibul Hasan')) ?></a>
    <button class="icon-button nav-toggle" type="button" data-nav-toggle aria-label="Open navigation"><span></span><span></span></button>
    <div class="nav-links" data-nav-menu>
      <?php foreach (($navSections ?? []) as $id => $label): ?>
        <?php if (in_array($id, ['testimonials', 'faq', 'contact'], true)) { continue; } ?>
        <a href="<?= $id === 'blog' ? e(url('/blog')) : e(url('/#' . $id)) ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
      <button class="icon-button" type="button" data-theme-toggle aria-label="Toggle dark mode">◐</button>
    </div>
  </nav>
</header>

<main id="main">
  <article class="section-band blog-post reveal">
    <div class="blog-post-head">
      <p class="eyebrow">
        <a class="blog-post-back" href="<?= e(url('/blog')) ?>">&larr; Blog</a>
        <?php if (!empty($post['category_name'])): ?><span class="blog-post-chip"><?= e($post['category_name']) ?></span><?php endif; ?>
      </p>
      <h1><?= e($post['title'] ?? '') ?></h1>
      <?php if (!empty($post['published_at'])): ?><time class="blog-post-date" datetime="<?= e($post['published_at']) ?>"><?= e(date('F j, Y', strtotime($post['published_at']))) ?></time><?php endif; ?>
    </div>

    <?php if (!empty($post['featured_image'])): ?><img class="blog-post-image" src="<?= e(storage_url($post['featured_image'])) ?>" alt="<?= e($post['title'] ?? '') ?>"><?php endif; ?>

    <?php if (!empty($post['excerpt'])): ?><p class="blog-post-excerpt"><?= e($post['excerpt']) ?></p><?php endif; ?>

    <?php if (!empty($post['content'])): ?><div class="blog-post-body"><?= nl2br(e($post['content'])) ?></div><?php endif; ?>

    <div class="blog-post-foot">
      <a class="text-link" href="<?= e(url('/blog')) ?>">&larr; Back to all posts</a>
    </div>
  </article>
</main>

<footer class="site-footer">
  <div class="footer-links">
    <a href="<?= e(url('/#portfolio')) ?>">Projects</a>
    <a href="<?= e(url('/#testimonials')) ?>">Testimonials</a>
    <a href="<?= e(url('/#faq')) ?>">FAQ</a>
    <a href="<?= e(url('/#contact')) ?>">Contact</a>
  </div>
  <span>© <?= date('Y') ?> <?= e(setting($settings, 'site_name', 'Rakibul Hasan')) ?></span>
</footer>
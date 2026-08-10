<?php
$activeCategory = $activeCategory ?? null;
$activeSlug = ($activeCategory['slug'] ?? '') !== '' ? $activeCategory['slug'] : trim((string) ($categorySlug ?? ''));
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
  <section class="section-band blog-band reveal">
    <div class="section-head">
      <p class="eyebrow">Blog</p>
      <h2><?= $activeCategory ? e($activeCategory['name'] . ' posts.') : 'All blog posts.' ?></h2>
    </div>

    <div class="blog-categories" aria-label="Blog categories">
      <a class="stack-tab<?= $activeSlug === '' ? ' active' : '' ?>" href="<?= e(url('/blog')) ?>">All</a>
      <?php foreach ($categories as $category): ?>
        <a class="stack-tab<?= $activeSlug === $category['slug'] ? ' active' : '' ?>" href="<?= e(url('/blog?category=' . rawurlencode($category['slug']))) ?>"><?= e($category['name']) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if ($posts): ?>
      <div class="content-grid">
        <?php foreach ($posts as $post): ?>
          <article class="content-card reveal-child">
            <a class="content-card__link" href="<?= e(url('/blog/' . rawurlencode($post['slug']))) ?>">
              <?php if (!empty($post['featured_image'])): ?><img src="<?= e(storage_url($post['featured_image'])) ?>" alt="<?= e($post['title']) ?>" loading="lazy"><?php endif; ?>
              <h3><?= e($post['title']) ?></h3>
              <?php if (!empty($post['excerpt'])): ?><p><?= e($post['excerpt']) ?></p><?php endif; ?>
              <?php if (!empty($post['published_at'])): ?><small><?= e(date('M d, Y', strtotime($post['published_at']))) ?></small><?php endif; ?>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="blog-empty">
        <strong><?= $activeCategory ? 'No posts in this category yet.' : 'No blog posts yet.' ?></strong>
        <span><?= $activeCategory ? 'Posts published under &ldquo;' . e($activeCategory['name']) . '&rdquo; from the admin panel will appear here.' : 'Check back soon, or browse the categories above.' ?></span>
      </div>
    <?php endif; ?>
  </section>
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
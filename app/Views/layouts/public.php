<?php
$seo = $seo ?? [];
$settings = $settings ?? [];
$metaTitle = $seo['title'] ?? ($title ?? 'Rakibul Hasan');
$metaDescription = $seo['description'] ?? 'Portfolio of Rakibul Hasan, Full-Stack Developer and AI/ML Enthusiast.';
?>
<!doctype html>
<html lang="en" class="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="<?= e(setting($settings, 'theme_color', '#4ee1a0')) ?>">
  <title><?= e($metaTitle) ?></title>
  <meta name="description" content="<?= e($metaDescription) ?>">
  <meta property="og:title" content="<?= e($metaTitle) ?>">
  <meta property="og:description" content="<?= e($metaDescription) ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= e(url('/')) ?>">
  <?php if (!empty($seo['og_image'])): ?><meta property="og:image" content="<?= e(storage_url($seo['og_image'])) ?>"><?php endif; ?>
  <meta name="twitter:card" content="<?= e($seo['twitter_card'] ?? 'summary_large_image') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
  <style>
    #spiderWebCanvas{position:fixed;inset:0;width:100%;height:100%;z-index:0;pointer-events:none}
  </style>
</head>
<body>
  <canvas id="spiderWebCanvas" aria-hidden="true"></canvas>
  <a class="skip-link" href="#main">Skip to content</a>
  <?= $content ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
  <script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>

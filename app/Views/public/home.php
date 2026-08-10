<?php
$designations = array_values(array_filter(array_map('trim', array_filter((array) ($designations ?? []), static fn ($d) => is_string($d)))));
if ($designations === []) {
    $designations = ['Full-Stack Developer'];
}
$skillGroups = [];
foreach ($skills as $skill) {
    $skillGroups[$skill['category']][] = $skill;
}
$stats = json_decode($about['stats_json'] ?? '[]', true) ?: [];
$profileImages = array_values(array_filter((array) ($profileImages ?? []), static fn ($i) => !empty($i['image_path'])));
foreach ($profileImages as &$profileImageRow) {
    $imagePath = $profileImageRow['image_path'];
    $profileImageRow['src'] = str_starts_with($imagePath, 'assets/')
        ? asset(substr($imagePath, strlen('assets/')))
        : storage_url($imagePath);
    $profileImageRow['alt'] = trim((string) ($profileImageRow['alt_text'] ?? ''));
    if ($profileImageRow['alt'] === '') {
        $profileImageRow['alt'] = 'Rakibul Hasan — Full Stack Developer';
    }
}
unset($profileImageRow);
$heroImageFallback = asset('img/rakibul-hero.png');
$secondaryCtaLabel = trim($hero['secondary_cta_label'] ?? '');
if ($secondaryCtaLabel === '' || strcasecmp($secondaryCtaLabel, 'Download Resume') === 0) {
    $secondaryCtaLabel = 'Download CV';
}
?>
<header class="site-header" data-header>
  <nav class="nav-shell" aria-label="Primary navigation">
    <a class="brand" href="#top" aria-label="Rakibul Hasan home"><span><?= e(setting($settings, 'logo_text', 'RH')) ?></span><?= e(setting($settings, 'site_name', 'Rakibul Hasan')) ?></a>
    <button class="icon-button nav-toggle" type="button" data-nav-toggle aria-label="Open navigation"><span></span><span></span></button>
    <div class="nav-links" data-nav-menu>
      <?php foreach (($navSections ?? []) as $id => $label): ?>
        <?php if (in_array($id, ['testimonials', 'faq', 'contact'], true)) { continue; } ?>
        <a href="<?= $id === 'blog' ? e(url('/blog')) : e('#' . $id) ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
      <button class="icon-button" type="button" data-theme-toggle aria-label="Toggle dark mode">◐</button>
    </div>
  </nav>
</header>

<main id="main">
  <section id="top" class="hero-section section-band">
    <div class="hero-grid">
      <div class="hero-copy reveal">
    
        <h1><?= e($hero['name'] ?? 'Rakibul Hasan') ?></h1>
        <p class="role-line">I am a <span data-rotator data-words='<?= e(json_encode($designations, JSON_THROW_ON_ERROR)) ?>'><?= e($designations[0] ?? 'Full-Stack Developer') ?></span></p>
        <p class="hero-intro"><?= e($hero['intro'] ?? '') ?></p>
        <div class="hero-actions">
          <a class="btn btn-primary" href="<?= e($hero['primary_cta_target'] ?? '#portfolio') ?>"><?= e($hero['primary_cta_label'] ?? 'View Projects') ?></a>
          <a class="btn btn-ghost" href="<?= e($hero['secondary_cta_target'] ?? '/resume') ?>"><?= e($secondaryCtaLabel) ?></a>
        </div>
        <div class="social-row" aria-label="Social links">
          <?php foreach ($socials as $social):
            $rawUrl = trim($social['url'] ?? '');
            $label = trim($social['label'] ?: $social['platform']);
            $platform = strtolower(trim($social['platform'] ?? ''));
            if ($rawUrl === '') {
              continue;
            }
            if (stripos($rawUrl, 'mailto:') === 0) {
              $href = $rawUrl;
              $extra = '';
            } elseif (filter_var($rawUrl, FILTER_VALIDATE_EMAIL) || strcasecmp($social['platform'], 'Email') === 0) {
              $href = 'mailto:' . preg_replace('/^mailto:/i', '', $rawUrl);
              $extra = '';
            } else {
              if (preg_match('#^https?://#i', $rawUrl)) {
                $href = $rawUrl;
              } elseif (strpos($rawUrl, '//') === 0) {
                $href = 'https:' . $rawUrl;
              } else {
                $href = 'https://' . $rawUrl;
              }
              $extra = ' target="_blank" rel="noopener noreferrer"';
            }

            $icon = '';
            if ($platform === 'linkedin') {
              $icon = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1 4.98 2.12 4.98 3.5zM0 24V7h5v17H0zm7-17h4.8v2.35h.07c.67-1.26 2.31-2.59 4.75-2.59 5.08 0 6.02 3.35 6.02 7.7V24h-5.01v-7.6c0-1.81-.03-4.14-2.52-4.14-2.52 0-2.9 1.96-2.9 3.99V24H7V7z"/></svg>';
            } elseif ($platform === 'github') {
              $icon = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.302 3.438 9.8 8.205 11.387.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.757-1.333-1.757-1.09-.745.084-.73.084-.73 1.205.085 1.84 1.236 1.84 1.236 1.07 1.835 2.807 1.305 3.492.998.108-.776.418-1.305.76-1.605-2.665-.3-5.467-1.332-5.467-5.93 0-1.31.47-2.381 1.236-3.221-.124-.302-.536-1.52.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.98-.399 3-.405 1.02.006 2.043.139 3 .405 2.291-1.552 3.297-1.23 3.297-1.23.655 1.656.243 2.874.12 3.176.77.84 1.235 1.911 1.235 3.221 0 4.61-2.807 5.625-5.48 5.92.43.372.823 1.102.823 2.222 0 1.606-.014 2.898-.014 3.293 0 .32.216.694.825.576C20.565 22.092 24 17.592 24 12.297 24 5.67 18.627.297 12 .297z"/></svg>';
            } elseif ($platform === 'email' || $platform === 'mail') {
              $icon = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 5.5A2.5 2.5 0 014.5 3h15A2.5 2.5 0 0122 5.5v13a2.5 2.5 0 01-2.5 2.5h-15A2.5 2.5 0 012 18.5v-13zm2.5-.5a.5.5 0 00-.5.5v.34l7.5 4.8 7.5-4.8V5.5a.5.5 0 00-.5-.5h-15zm15 2.46l-7.37 4.71a1 1 0 01-1.26 0L4.5 7.46V18.5a.5.5 0 00.5.5h15a.5.5 0 00.5-.5V7.46z"/></svg>';
            } elseif ($platform === 'scholar' || $platform === 'google scholar' || $platform === 'google-scholar') {
              $icon = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2 2 7l10 5 10-5-10-5zm0 1.8 7.9 3.95L12 11.7 4.1 7.75 12 3.8z" fill="currentColor"/><path d="M4 9.5v2.5c0 4 3.4 7.5 8 7.5s8-3.5 8-7.5V9.5l-8 4-8-4z" fill="currentColor"/><path d="M12 13.5a1.5 1.5 0 0 1 1.5 1.5v3h-3v-3A1.5 1.5 0 0 1 12 13.5z" fill="currentColor"/></svg>';
            }
          ?>
            <a href="<?= e($href) ?>" class="social-link" aria-label="<?= e($label) ?>"<?= $extra ?>>
              <?= $icon ?: '<span class="social-label">' . e($social['platform']) . '</span>' ?>
              <span class="sr-only"><?= e($social['platform']) ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="hero-visual reveal" data-hero-card>
        <div class="hero-visual__effects" aria-hidden="true">
          <span class="blob blob-1"></span>
          <span class="blob blob-2"></span>
          <span class="blob blob-3"></span>
          <span class="grid-overlay"></span>
        </div>
        <div class="orbit-wrapper">
          <div class="orbit-ring" aria-hidden="true">
            <span class="orbit-dot orbit-dot--primary"></span>
          </div>
          <div class="orbit-ring orbit-ring--inner" aria-hidden="true">
            <span class="orbit-dot orbit-dot--accent"></span>
          </div>
          <div class="profile-center" data-profile>
            <div class="profile-tilt" data-tilt>
              <?php if (count($profileImages) <= 1): ?>
                <img src="<?= e($profileImages[0]['src'] ?? $heroImageFallback) ?>" alt="<?= e($profileImages[0]['alt'] ?? 'Rakibul Hasan — Full Stack Developer') ?>" width="1154" height="1408" loading="eager">
              <?php else: ?>
                <?php foreach ($profileImages as $profileIndex => $profileImageRow): ?>
                  <img class="profile-slide<?= $profileIndex === 0 ? ' is-active' : '' ?>" src="<?= e($profileImageRow['src']) ?>" alt="<?= e($profileImageRow['alt']) ?>" width="1154" height="1408" <?= $profileIndex === 0 ? 'loading="eager"' : 'loading="lazy"' ?> data-profile-slide>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="orbit-track">
            <span class="orbit-icon" style="--gc:61,109,190" aria-label="PHP">
              <svg viewBox="0 0 48 48"><rect x="6" y="12" width="36" height="24" rx="7" fill="#777BB4"/><text x="24" y="30" text-anchor="middle" fill="#fff" font-size="13" font-weight="800">PHP</text></svg>
            </span>
            <span class="orbit-icon" style="--gc:194,42,75" aria-label="Laravel">
              <svg viewBox="0 0 48 48"><rect x="6" y="12" width="36" height="24" rx="7" fill="#ff2d20"/><text x="24" y="30" text-anchor="middle" fill="#fff" font-size="14" font-weight="800">Lar</text></svg>
            </span>
            <span class="orbit-icon" style="--gc:0,97,185" aria-label="MySQL">
              <svg viewBox="0 0 48 48"><rect x="6" y="12" width="36" height="24" rx="7" fill="#00758f"/><text x="24" y="30" text-anchor="middle" fill="#fff" font-size="13" font-weight="800">SQL</text></svg>
            </span>
            <span class="orbit-icon" style="--gc:247,223,30;--tc:#071014" aria-label="JavaScript">
              <svg viewBox="0 0 48 48"><rect x="5" y="5" width="38" height="38" rx="6" fill="#f7df1e"/><text x="24" y="32" text-anchor="middle" fill="#071014" font-size="13" font-weight="800">JS</text></svg>
            </span>
            <span class="orbit-icon" style="--gc:227,76,38" aria-label="HTML5">
              <svg viewBox="0 0 48 48"><path d="M8 4l3.2 36.4L24 44l12.8-3.6L40 4H8z" fill="#e34f26"/><path d="M33.5 13.6H17.2l.5 5.4h15.3l-.8 8.4-8 2.6-8-2.6-.5-6h5.4l.2 3.3 2.9.9 2.9-.9.4-4.1H14.6l-1-11H35l-.5 5.3z" fill="#fff"/></svg>
            </span>
            <span class="orbit-icon" style="--gc:56,115,222" aria-label="CSS3">
              <svg viewBox="0 0 48 48"><path d="M8 4l3.2 36.4L24 44l12.8-3.6L40 4H8z" fill="#1572b6"/><path d="M33.7 13.6H16.8l.4 5.4h16.1l-.9 9.1-8.4 2.6-8.4-2.6-.5-6h5.4l.2 3.3 3.3.9 3.3-.9.4-4.3H14.1l-1.1-11H35l-.4 5.3z" fill="#fff"/></svg>
            </span>
            <span class="orbit-icon" style="--gc:228,90,40" aria-label="Git">
              <svg viewBox="0 0 48 48"><path d="M28 14c-2.2 0-4 1.8-4 4 0 .5.1 1 .3 1.4l-5.6 3.3c-.8-.5-1.7-.8-2.7-.8-2.5 0-4.5 2-4.5 4.5s2 4.5 4.5 4.5c1 0 1.9-.3 2.7-.8l5.6 3.3c-.2.4-.3.9-.3 1.4 0 2.2 1.8 4 4 4s4-1.8 4-4-1.8-4-4-4c-.9 0-1.8.3-2.4.9L24 24.5l2.6-1.5c.6.6 1.5.9 2.4.9 2.2 0 4-1.8 4-4s-1.8-4-4-4z" fill="#f05032"/></svg>
            </span>
            <span class="orbit-icon" style="--gc:124,92,255" aria-label="AI / Machine Learning">
              <svg viewBox="0 0 48 48"><circle cx="24" cy="14" r="4" fill="rgba(124,92,255,.85)"/><circle cx="14" cy="34" r="3.5" fill="rgba(124,92,255,.65)"/><circle cx="34" cy="34" r="3.5" fill="rgba(124,92,255,.65)"/><line x1="20.5" y1="17" x2="16" y2="31" stroke="rgba(124,92,255,.35)" stroke-width="1.5"/><line x1="27.5" y1="17" x2="32" y2="31" stroke="rgba(124,92,255,.35)" stroke-width="1.5"/><line x1="17.5" y1="34" x2="30.5" y2="34" stroke="rgba(124,92,255,.2)" stroke-width="1"/></svg>
            </span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="about" class="section-band about-band reveal">
    <div class="section-head">
      <p class="eyebrow">About</p>
      <h2></h2>
    </div>
    <div class="about-grid">
      <p class="lead-copy"><?= e($about['summary'] ?? '') ?></p>
      <div class="stat-grid">
        <?php foreach ($stats as $stat): ?>
          <div class="stat-card"><strong><?= e($stat['value'] ?? '') ?></strong><span><?= e($stat['label'] ?? '') ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="skills" class="section-band skills-band reveal" data-skills>
    <div class="skills-bg" aria-hidden="true">
      <span class="skills-bg__orb skills-bg__orb--1"></span>
      <span class="skills-bg__orb skills-bg__orb--2"></span>
      <span class="skills-bg__orb skills-bg__orb--3"></span>
      <span class="skills-bg__grid"></span>
    </div>
    <div class="section-head">
      <p class="eyebrow">Skills</p>
      <h2></h2>
    </div>
    <div class="skill-grid">
      <?php foreach ($skillGroups as $category => $items): ?>
        <?php
          $catKey = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $category));
          $catLower = strtolower($category);
          if (str_contains($catLower, 'ai') || str_contains($catLower, 'data')) {
            $catIcon = '&#9883;';
          } elseif (str_contains($catLower, 'language')) {
            $catIcon = '&#9001;';
          } else {
            $catIcon = '&#9881;';
          }
        ?>
        <article class="skill-card skill-card--<?= e($catKey) ?> reveal-child">
          <div class="skill-card__head">
            <span class="skill-card__icon"><?= $catIcon ?></span>
            <div class="skill-card__title">
              <h3><?= e($category) ?></h3>
              <span class="skill-card__count"><?= count($items) ?> skills</span>
            </div>
          </div>
          <div class="skill-card__list">
            <?php foreach ($items as $item): ?>
              <?php
                $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $item['name']));
              ?>
              <div class="skill-item" data-skill="<?= e($slug) ?>">
                <div class="skill-item__head">
                  <span class="skill-item__icon skill-item__icon--<?= e($slug) ?>"><?= strtoupper(mb_substr($item['name'], 0, 2)) ?></span>
                  <span class="skill-item__name"><?= e($item['name']) ?></span>
                  <span class="skill-item__pct" data-pct="<?= e($item['proficiency']) ?>"><?= e($item['proficiency']) ?>%</span>
                </div>
                <div class="skill-bar">
                  <span class="skill-bar__track">
                    <span class="skill-bar__fill" style="--target: <?= e($item['proficiency']) ?>%"></span>
                  </span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="skill-card__foot">
            <span class="skill-card__avg">Avg <?= e(round(array_sum(array_column($items, 'proficiency')) / count($items))) ?>%</span>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="experience" class="section-band timeline-band reveal">
    <div class="section-head">
      <p class="eyebrow">Experience</p>
      <h2></h2>
    </div>
    <div class="timeline">
      <?php foreach ($experience as $item): ?>
        <article class="timeline-item reveal-child">
          <time><?= e($item['period']) ?></time>
          <h3><?= e($item['title']) ?></h3>
          <p><?= e($item['company']) ?><?= $item['location'] ? ' · ' . e($item['location']) : '' ?></p>
          <ul>
            <?php foreach (array_filter(array_map('trim', preg_split('/\R/', $item['description'] ?? '') ?: [])) as $bullet): ?>
              <li><?= e($bullet) ?></li>
            <?php endforeach; ?>
          </ul>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="education" class="section-band reveal">
    <div class="section-head compact">
      <p class="eyebrow">Education</p>
      <h2></h2>
    </div>
    <div class="education-grid">
      <?php foreach ($education as $item): ?>
        <article class="mini-card reveal-child">
          <h3><?= e($item['degree']) ?></h3>
          <p><?= e($item['institution']) ?><?= $item['location'] ? ' · ' . e($item['location']) : '' ?></p>
          <strong><?= e($item['result']) ?></strong><span><?= e($item['period']) ?></span>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="portfolio" class="section-band portfolio-band reveal">
    <div class="section-head">
      <p class="eyebrow">Projects</p>
      <h2></h2>
    </div>
    <div class="project-grid">
      <?php foreach ($projects as $project): ?>
        <article class="project-card reveal-child">
          <?php if (!empty($project['featured_image'])): ?>
            <figure class="project-visual">
              <img src="<?= e(storage_url($project['featured_image'])) ?>" alt="<?= e($project['title']) ?> image" loading="lazy">
            </figure>
          <?php endif; ?>
          <div class="project-top"><span><?= $project['is_featured'] ? 'Featured' : 'Project' ?></span><small><?= e($project['role']) ?></small></div>
          <h3><?= e($project['title']) ?></h3>
          <p><?= e($project['description']) ?></p>
          <?php $features = array_filter(array_map('trim', preg_split('/\R/', $project['features'] ?? '') ?: [])); ?>
          <?php if ($features): ?>
            <ul>
              <?php foreach ($features as $feature): ?>
                <li><?= e($feature) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <div class="tag-row">
            <?php foreach ($project['technologies'] as $tech): ?><span><?= e($tech['technology']) ?></span><?php endforeach; ?>
          </div>
          <?php if (!empty($project['live_url'])): ?>
            <a class="text-link" href="<?= e($project['live_url']) ?>" target="_blank" rel="noopener">Live Demo</a>
          <?php endif; ?>
          <?php if (!empty($project['github_url'])): ?>
            <a class="text-link" href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener">GitHub</a>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="stack" class="section-band reveal">
    <div class="section-head compact">
      <p class="eyebrow">Technology Stack</p>
      <h2></h2>
    </div>
    <div class="stack-tabs" role="tablist" aria-label="Filter technologies">
      <button class="stack-tab active" data-filter="all" role="tab" aria-selected="true">All</button>
      <button class="stack-tab" data-filter="languages" role="tab" aria-selected="false">Languages</button>
      <button class="stack-tab" data-filter="backend" role="tab" aria-selected="false">Backend</button>
      <button class="stack-tab" data-filter="database" role="tab" aria-selected="false">Database</button>
      <button class="stack-tab" data-filter="aiml" role="tab" aria-selected="false">AI/ML</button>
      <button class="stack-tab" data-filter="devops" role="tab" aria-selected="false">DevOps</button>
    </div>
    <div class="stack-grid-wrap">
      <canvas id="stackNetCanvas" aria-hidden="true"></canvas>
      <div class="stack-grid" id="stackGrid">
        <?php
        $techs = [
          ['name' => 'Python',      'cat' => 'languages', 'hue' => 207, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3c-3 0-4.5 1.5-4.5 4v3h5v1H3.5C1.5 11 0 13 0 16.5S1.5 22 3.5 22h2v-2.5c0-2 1.5-3.5 3.5-3.5h5c2 0 3.5-1.5 3.5-3.5V7c0-2.5-2-4-4.5-4H9z"/><path d="M15 21c3 0 4.5-1.5 4.5-4v-3h-5v-1h6c2 0 3.5-2 3.5-5.5S22.5 2 20.5 2h-2v2.5c0 2-1.5 3.5-3.5 3.5h-5c-2 0-3.5 1.5-3.5 3.5V17c0 2.5 2 4 4.5 4h5z"/></svg>'],
          ['name' => 'JavaScript',   'cat' => 'languages', 'hue' => 54, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="3"/><path d="M14 9c0-1.1-.9-2-2-2h-1a2 2 0 00-2 2v3c0 1.1.9 2 2 2h1a2 2 0 002-2m-2 5v3m4-6c0-1.1-.9-2-2-2h-1a2 2 0 00-2 2"/></svg>'],
          ['name' => 'PHP',         'cat' => 'languages', 'hue' => 250, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M7 9v6m3-6v6m0-3h2.5a1.5 1.5 0 001.5-1.5v-1A1.5 1.5 0 0012.5 8H10m4 4l2 4"/></svg>'],
          ['name' => 'Laravel',     'cat' => 'backend',   'hue' => 4, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/><path d="M12 12l5-2.5"/></svg>'],
          ['name' => 'MySQL',       'cat' => 'database',  'hue' => 207, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v7c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12v7c0 1.66 4 3 9 3s9-1.34 9-3v-7"/></svg>'],
          ['name' => 'Bootstrap',   'cat' => 'backend',   'hue' => 270, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 7v5l10 5 10-5V7"/><path d="M8 12h8m-4-3v6"/></svg>'],
          ['name' => 'TensorFlow',  'cat' => 'aiml',      'hue' => 24, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M12 12l8-4"/><path d="M12 12l-8-4"/><path d="M12 12l8 4"/><path d="M12 12l-8 4"/><path d="M12 6v12"/></svg>'],
          ['name' => 'PyTorch',     'cat' => 'aiml',      'hue' => 10, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l-6 6a8.5 8.5 0 1012 0L12 3z"/><circle cx="12" cy="3" r="1.5"/></svg>'],
          ['name' => 'Scikit-learn','cat' => 'aiml',      'hue' => 28, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 4"/><circle cx="12" cy="12" r="2"/></svg>'],
          ['name' => 'OpenAI',      'cat' => 'aiml',      'hue' => 265, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/><path d="M12 6c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6z"/><path d="M12 9c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3z"/></svg>'],
          ['name' => 'Docker',      'cat' => 'devops',    'hue' => 207, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="8" width="4" height="4"/><rect x="9" y="8" width="4" height="4"/><rect x="14" y="8" width="4" height="4"/><rect x="9" y="13" width="4" height="4"/><rect x="14" y="13" width="4" height="4"/><path d="M4 13h4v4H4z"/><path d="M1 12h4"/></svg>'],
          ['name' => 'Git',         'cat' => 'devops',    'hue' => 10, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"/><circle cx="18" cy="18" r="3"/><circle cx="18" cy="6" r="3"/><path d="M12 3v13a5 5 0 005 5h1"/><path d="M8 9l4.5 4.5"/></svg>'],
          ['name' => 'Linux',       'cat' => 'devops',    'hue' => 48, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/><path d="M8 14c1.5-2 4-2 4-2s2.5 0 4 2m-6 3c.67.5 2 .5 3 0"/><path d="M9 9c.5-1 1.5-1 2 0m2 0c.5-1 1.5-1 2 0"/></svg>'],
          ['name' => 'VS Code',     'cat' => 'devops',    'hue' => 207, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3L5 8l-3 4 3 4 11 5V3z"/><path d="M16 12h6"/><path d="M10 9l-3 3 3 3"/></svg>'],
          ['name' => 'Jupyter',     'cat' => 'devops',    'hue' => 24, 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/><circle cx="12" cy="12" r="2"/><path d="M8 5c-.5 2-1 3-2 4"/><path d="M16 5c.5 2 1 3 2 4"/></svg>'],
        ];
        foreach ($techs as $t):
        ?>
        <div class="tech-card reveal-child" data-cat="<?= $t['cat'] ?>">
          <div class="tech-card__glow"></div>
          <div class="tech-card__shine"></div>
          <div class="tech-card__icon" style="--th:<?= $t['hue'] ?>">
            <?= $t['icon'] ?>
          </div>
          <span class="tech-card__name"><?= $t['name'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php if ($certificates): ?>
    <section id="certificates" class="section-band reveal">
      <div class="section-head compact">
        <p class="eyebrow">Certificates</p>
        <h2></h2>
      </div>
      <div class="content-grid">
        <?php foreach ($certificates as $certificate): ?>
          <article class="content-card reveal-child">
            <?php if (!empty($certificate['image_path'])): ?>
              <?php $certificateUrl = storage_url($certificate['image_path']); ?>
              <?php if (str_ends_with(strtolower($certificate['image_path']), '.pdf')): ?>
                <a class="file-preview" href="<?= e($certificateUrl) ?>" target="_blank" rel="noopener">View PDF</a>
              <?php else: ?>
                <img src="<?= e($certificateUrl) ?>" alt="<?= e($certificate['title']) ?>" loading="lazy">
              <?php endif; ?>
            <?php endif; ?>
            <h3><?= e($certificate['title']) ?></h3>
            <?php if (!empty($certificate['issuer'])): ?><p><?= e($certificate['issuer']) ?></p><?php endif; ?>
            <?php if (!empty($certificate['issued_at'])): ?><small><?= e(date('M Y', strtotime($certificate['issued_at']))) ?></small><?php endif; ?>
            <?php if (!empty($certificate['credential_url'])): ?><a class="text-link" href="<?= e($certificate['credential_url']) ?>" target="_blank" rel="noopener">View credential</a><?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

    <section id="research" class="section-band reveal">
      <div class="section-head compact">
        <p class="eyebrow">Research</p>
        <h2></h2>
      </div>
      <div class="content-grid">
        <?php foreach ($research as $paper): ?>
          <article class="content-card reveal-child">
            <h3><?= e($paper['title']) ?></h3>
            <?php if (!empty($paper['summary'])): ?><p><?= e($paper['summary']) ?></p><?php endif; ?>
            <?php if (!empty($paper['published_at'])): ?><small><?= e(date('M d, Y', strtotime($paper['published_at']))) ?></small><?php endif; ?>
            <?php if (!empty($paper['url'])): ?><a class="text-link" href="<?= e($paper['url']) ?>" target="_blank" rel="noopener">Read publication</a><?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

  <?php if ($testimonials): ?>
    <section id="testimonials" class="section-band reveal">
      <div class="section-head compact"><p class="eyebrow">Testimonials</p><h2></h2></div>
      <div class="testimonial-strip">
        <?php foreach ($testimonials as $testimonial): ?>
          <article class="quote-card">
            <?php if ($testimonial['is_placeholder']): ?><span class="placeholder-badge">Placeholder</span><?php endif; ?>
            <p>“<?= e($testimonial['message']) ?>”</p><strong><?= e($testimonial['name']) ?></strong><small><?= e($testimonial['role']) ?></small>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <section id="faq" class="section-band reveal">
    <div class="section-head compact"><p class="eyebrow">FAQ</p><h2></h2></div>
    <div class="faq-list">
      <?php foreach ($faqs as $faq): ?>
        <details><summary><?= e($faq['question']) ?></summary><p><?= e($faq['answer']) ?></p></details>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="contact" class="section-band contact-band reveal">
    <div class="contact-grid">
      <div>
        <p class="eyebrow">Contact</p>
        <h2></h2>
        <div class="contact-lines">
          <span><?= e(setting($settings, 'phone', '+8801789834538')) ?></span>
          <span><?= e(setting($settings, 'location', '106/7A, Moniporipara, Dhaka, Bangladesh')) ?></span>
          <span><?= e(setting($settings, 'email', '[ADD EMAIL ADDRESS]')) ?></span>
        </div>
      </div>
      <form class="contact-form" method="post" action="/contact">
        <?= csrf_field() ?>
        <?php foreach (flash() as $type => $message): ?><div class="flash <?= e($type) ?>"><?= e($message) ?></div><?php endforeach; ?>
        <label>Name<input name="name" value="<?= e(old('name')) ?>" required></label>
        <label>Email<input type="email" name="email" value="<?= e(old('email')) ?>" required></label>
        <label>Subject<input name="subject" value="<?= e(old('subject')) ?>" required></label>
        <label>Message<textarea name="message" rows="5" required><?= e(old('message')) ?></textarea></label>
        <button class="btn btn-primary" type="submit">Send Message</button>
      </form>
    </div>
  </section>
</main>

<footer class="site-footer">
  <div class="footer-links">
    <a href="#portfolio">Projects</a>
    <a href="#testimonials">Testimonials</a>
    <a href="#faq">FAQ</a>
    <a href="#contact">Contact</a>
  </div>
  <span>© <?= date('Y') ?> <?= e(setting($settings, 'site_name', 'Rakibul Hasan')) ?></span>
</footer>

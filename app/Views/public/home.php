<?php
$designations = array_values(array_filter(array_map('trim', preg_split('/\R|→/', $hero['designations'] ?? 'Full-Stack Developer') ?: [])));
$skillGroups = [];
foreach ($skills as $skill) {
    $skillGroups[$skill['category']][] = $skill;
}
$stats = json_decode($about['stats_json'] ?? '[]', true) ?: [];
$heroImage = asset('img/rakibul-hero.png');
?>
<header class="site-header" data-header>
  <nav class="nav-shell" aria-label="Primary navigation">
    <a class="brand" href="#top" aria-label="Rakibul Hasan home"><span><?= e(setting($settings, 'logo_text', 'RH')) ?></span><?= e(setting($settings, 'site_name', 'Rakibul Hasan')) ?></a>
    <button class="icon-button nav-toggle" type="button" data-nav-toggle aria-label="Open navigation"><span></span><span></span></button>
    <div class="nav-links" data-nav-menu>
      <?php foreach (($navSections ?? []) as $id => $label): ?>
        <?php if (in_array($id, ['portfolio', 'testimonials', 'faq', 'contact'], true)) { continue; } ?>
        <a href="#<?= e($id) ?>"><?= e($label) ?></a>
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
        <p class="role-line">I build as a <span data-rotator data-words='<?= e(json_encode($designations, JSON_THROW_ON_ERROR)) ?>'><?= e($designations[0] ?? 'Full-Stack Developer') ?></span></p>
        <p class="hero-intro"><?= e($hero['intro'] ?? '') ?></p>
        <div class="hero-actions">
          <a class="btn btn-primary" href="<?= e($hero['primary_cta_target'] ?? '#portfolio') ?>"><?= e($hero['primary_cta_label'] ?? 'View Projects') ?></a>
          <a class="btn btn-ghost" href="<?= e($hero['secondary_cta_target'] ?? '/resume') ?>"><?= e($hero['secondary_cta_label'] ?? 'Download Resume') ?></a>
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
            <div class="profile-float">
              <div class="profile-tilt" data-tilt>
                <div class="profile-center__glow" aria-hidden="true"></div>
                <div class="profile-center__border" aria-hidden="true"></div>
                <div class="profile-center__glass">
                  <img src="<?= e($heroImage) ?>" alt="Rakibul Hasan — Full Stack Developer" width="1154" height="1408" loading="eager">
                  <div class="profile-center__cyber" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="orbit-track">
            <span class="orbit-icon" style="--gc:61,109,190" aria-label="PHP">PHP</span>
            <span class="orbit-icon" style="--gc:194,42,75" aria-label="Laravel">Lar</span>
            <span class="orbit-icon" style="--gc:0,97,185" aria-label="MySQL">SQL</span>
            <span class="orbit-icon" style="--gc:247,223,30;--tc:#071014" aria-label="JavaScript">JS</span>
            <span class="orbit-icon" style="--gc:227,76,38" aria-label="HTML5">H5</span>
            <span class="orbit-icon" style="--gc:56,115,222" aria-label="CSS3">C3</span>
            <span class="orbit-icon" style="--gc:228,90,40" aria-label="Git">Git</span>
            <span class="orbit-icon" style="--gc:124,92,255" aria-label="AI / Machine Learning">AI</span>
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
          <?php if (!empty($project['github_url'])): ?>
            <a class="text-link" href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener"><?= e($project['github_url']) ?></a>
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
    <div class="logo-grid">
      <?php foreach ($techStack as $tech): ?><span><?= e($tech['name']) ?></span><?php endforeach; ?>
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

  <?php if ($research): ?>
    <section id="research" class="section-band reveal">
      <div class="section-head compact">
        <p class="eyebrow">Research</p>
        <h2>Research and publications.</h2>
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
  <?php endif; ?>

  <?php if (!empty($blogPosts)): ?>
    <section id="blog" class="section-band reveal">
      <div class="section-head compact">
        <p class="eyebrow">Blog</p>
        <h2>Notes from the build process.</h2>
      </div>
      <div class="content-grid">
        <?php foreach ($blogPosts as $post): ?>
          <article class="content-card reveal-child">
            <?php if (!empty($post['featured_image'])): ?><img src="<?= e(storage_url($post['featured_image'])) ?>" alt="<?= e($post['title']) ?>" loading="lazy"><?php endif; ?>
            <h3><?= e($post['title']) ?></h3>
            <?php if (!empty($post['excerpt'])): ?><p><?= e($post['excerpt']) ?></p><?php endif; ?>
            <?php if (!empty($post['published_at'])): ?><small><?= e(date('M d, Y', strtotime($post['published_at']))) ?></small><?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

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
  <a href="/admin">Admin</a>
</footer>

<?php

declare(strict_types=1);

namespace App\Models;

final class PortfolioModel extends BaseModel
{
    public function siteData(): array
    {
        $db = $this->db();
        $settingsRows = $db->select('SELECT setting_key, setting_value FROM settings WHERE is_public = 1');
        $settings = [];
        foreach ($settingsRows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        $designations = array_values(array_filter(array_map('trim', array_column($db->select('SELECT title FROM hero_designations WHERE is_visible = 1 ORDER BY display_order ASC'), 'title'))));

        $profileImages = $db->select('SELECT * FROM profile_images WHERE is_visible = 1 ORDER BY display_order ASC');

        $projects = $db->select('SELECT * FROM projects ORDER BY is_featured DESC, display_order ASC');
        foreach ($projects as &$project) {
            $project['technologies'] = $db->select('SELECT technology FROM project_technologies WHERE project_id = ? ORDER BY id ASC', [$project['id']]);
        }

        return [
            'settings' => $settings,
            'seo' => $db->first('SELECT * FROM seo_meta WHERE page = ?', ['home']) ?? [],
            'hero' => $db->first('SELECT * FROM hero_content ORDER BY id ASC LIMIT 1') ?? [],
            'designations' => $designations,
            'profileImages' => $profileImages,
            'about' => $db->first('SELECT * FROM about_content ORDER BY id ASC LIMIT 1') ?? [],
            'skills' => $db->select('SELECT * FROM skills WHERE is_visible = 1 ORDER BY category ASC, display_order ASC'),
            'experience' => $db->select('SELECT * FROM experience ORDER BY display_order ASC'),
            'education' => $db->select('SELECT * FROM education ORDER BY display_order ASC'),
            'projects' => $projects,
            'techStack' => $db->select('SELECT * FROM tech_stack WHERE is_visible = 1 ORDER BY display_order ASC'),
            'certificates' => $db->select('SELECT * FROM certificates ORDER BY display_order ASC'),
            'research' => $db->select('SELECT * FROM research ORDER BY display_order ASC'),
            'testimonials' => $db->select('SELECT * FROM testimonials ORDER BY display_order ASC'),
            'faqs' => $db->select('SELECT * FROM faqs WHERE is_visible = 1 ORDER BY display_order ASC'),
            'socials' => $db->select('SELECT * FROM social_links WHERE is_visible = 1 ORDER BY display_order ASC'),
            'blogPosts' => $db->select("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY COALESCE(published_at, created_at) DESC LIMIT 3"),
            'blogCategories' => $db->select('SELECT * FROM blog_categories WHERE is_visible = 1 ORDER BY display_order ASC, name ASC'),
        ];
    }

    public function blogCategories(): array
    {
        return $this->db()->select('SELECT * FROM blog_categories WHERE is_visible = 1 ORDER BY display_order ASC, name ASC');
    }

    public function blogCategoryBySlug(string $slug): ?array
    {
        return $this->db()->first('SELECT * FROM blog_categories WHERE slug = ? AND is_visible = 1', [$slug]);
    }

    public function blogPostBySlug(string $slug): ?array
    {
        return $this->db()->first(
            "SELECT bp.*, bc.name AS category_name, bc.slug AS category_slug
             FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bc.id = bp.category_id
             WHERE bp.slug = ? AND bp.status = 'published'",
            [$slug]
        );
    }

    public function blogPostsByCategory(?string $categorySlug = null): array
    {
        $base = "SELECT bp.*, bc.name AS category_name, bc.slug AS category_slug
                 FROM blog_posts bp
                 LEFT JOIN blog_categories bc ON bc.id = bp.category_id
                 WHERE bp.status = 'published'";
        $params = [];
        if ($categorySlug !== null && $categorySlug !== '') {
            $base .= ' AND bc.slug = ?';
            $params[] = $categorySlug;
        }
        $base .= ' ORDER BY COALESCE(bp.published_at, bp.created_at) DESC';

        return $this->db()->select($base, $params);
    }

    public function stats(): array
    {
        $db = $this->db();
        $viewRow = $db->first('SELECT view_count FROM portfolio_views ORDER BY id ASC LIMIT 1');
        return [
            'views' => (int) ($viewRow['view_count'] ?? 0),
            'messages' => (int) $db->first('SELECT COUNT(*) AS total FROM contact_messages')['total'],
            'unread' => (int) $db->first('SELECT COUNT(*) AS total FROM contact_messages WHERE is_read = 0')['total'],
            'projects' => (int) $db->first('SELECT COUNT(*) AS total FROM projects')['total'],
            'skills' => (int) $db->first('SELECT COUNT(*) AS total FROM skills')['total'],
        ];
    }
}

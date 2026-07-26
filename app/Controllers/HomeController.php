<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Request;
use App\Core\Response;
use App\Models\PortfolioModel;

final class HomeController
{
    public function index(Request $request): string
    {
        $data = (new PortfolioModel())->siteData();
        $maintenance = ($data['settings']['maintenance_mode'] ?? '0') === '1';
        if ($maintenance && !\App\Core\Auth::check()) {
            http_response_code(503);
            return view('public/maintenance', ['title' => 'Maintenance', 'settings' => $data['settings']]);
        }

        $navSections = [
            'about' => 'About',
            'skills' => 'Skills',
            'experience' => 'Experience',
            'education' => 'Education',
            'portfolio' => 'Projects',
            'stack' => 'Tech Stack',
            'certificates' => 'Certificates',
            'research' => 'Research',
            'blog' => 'Blog',
            'testimonials' => 'Testimonials',
            'faq' => 'FAQ',
            'contact' => 'Contact',
        ];

        return view('public/home', $data + ['title' => $data['seo']['title'] ?? 'Rakibul Hasan', 'navSections' => $navSections]);
    }

    public function resume(Request $request): never
    {
        $setting = App::get('db')->first('SELECT setting_value FROM settings WHERE setting_key = ?', ['resume_pdf']);
        $relative = $setting['setting_value'] ?? '';
        $path = dirname(__DIR__, 2) . '/public/uploads/' . ltrim($relative, '/');
        if (!$relative || !is_file($path)) {
            flash('error', 'Resume PDF has not been uploaded yet.');
            Response::redirect('/#contact');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Rakibul-Hasan-Resume.pdf"');
        readfile($path);
        exit;
    }

    public function sitemap(Request $request): never
    {
        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>' . e(url('/')) . '</loc><priority>1.0</priority></url></urlset>';
        exit;
    }

    public function robots(Request $request): never
    {
        header('Content-Type: text/plain; charset=utf-8');
        echo "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml') . "\n";
        exit;
    }
}

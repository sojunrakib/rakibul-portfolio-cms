<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Core\View;
use App\Models\CmsModel;
use App\Services\SlugService;
use App\Services\UploadService;
use RuntimeException;

final class ModuleController
{
    private array $modules;
    private CmsModel $model;

    public function __construct()
    {
        $this->modules = require dirname(__DIR__, 3) . '/config/modules.php';
        $this->model = new CmsModel();
    }

    public function index(Request $request, string $module): string
    {
        Auth::require();
        $config = $this->module($module);
        $query = trim((string) $request->input('q', ''));
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 15;

        return View::render('admin/modules/index', [
            'title' => $config['label'],
            'module' => $module,
            'config' => $config,
            'rows' => $this->model->rows($config['table'], $config['order'] ?? 'id', $query, $perPage, ($page - 1) * $perPage),
            'total' => $this->model->count($config['table'], $query),
            'page' => $page,
            'perPage' => $perPage,
            'query' => $query,
            'modules' => $this->modules,
        ], 'layouts/admin');
    }

    public function create(Request $request, string $module): string
    {
        Auth::require();
        $config = $this->module($module);
        if (($config['readonly_create'] ?? false) === true) {
            Response::redirect('/admin/' . $module);
        }

        return View::render('admin/modules/form', [
            'title' => 'Create ' . $config['label'],
            'module' => $module,
            'config' => $config,
            'row' => [],
            'modules' => $this->modules,
        ], 'layouts/admin');
    }

    public function store(Request $request, string $module): never
    {
        Auth::require();
        $config = $this->module($module);
        $this->guardCsrf($request);
        try {
            $data = $this->payload($request, $config);
        } catch (RuntimeException $exception) {
            flash('error', $exception->getMessage());
            Response::redirect('/admin/' . $module . '/create');
        }
        $errors = $this->validatePayload($data, $config);

        if ($errors) {
            flash('error', implode(' ', $errors));
            Response::redirect('/admin/' . $module . '/create');
        }

        $this->model->insert($config['table'], $data);
        flash('success', $config['label'] . ' created.');
        Response::redirect('/admin/' . $module);
    }

    public function edit(Request $request, string $module, string $id): string
    {
        Auth::require();
        $config = $this->module($module);
        $row = $this->model->find($config['table'], (int) $id);
        if (!$row) {
            Response::redirect('/admin/' . $module);
        }

        if ($module === 'messages') {
            $this->model->update($config['table'], (int) $id, ['is_read' => 1]);
            $row['is_read'] = 1;
        }

        return View::render('admin/modules/form', [
            'title' => 'Edit ' . $config['label'],
            'module' => $module,
            'config' => $config,
            'row' => $row,
            'modules' => $this->modules,
        ], 'layouts/admin');
    }

    public function update(Request $request, string $module, string $id): never
    {
        Auth::require();
        $config = $this->module($module);
        $this->guardCsrf($request);
        try {
            $data = $this->payload($request, $config, $this->model->find($config['table'], (int) $id) ?? []);
        } catch (RuntimeException $exception) {
            flash('error', $exception->getMessage());
            Response::redirect('/admin/' . $module . '/' . (int) $id . '/edit');
        }
        $errors = $this->validatePayload($data, $config);

        if ($errors) {
            flash('error', implode(' ', $errors));
            Response::redirect('/admin/' . $module . '/' . (int) $id . '/edit');
        }

        $this->model->update($config['table'], (int) $id, $data);
        flash('success', $config['label'] . ' updated.');
        Response::redirect('/admin/' . $module);
    }

    public function delete(Request $request, string $module, string $id): never
    {
        Auth::require();
        $config = $this->module($module);
        $this->guardCsrf($request);
        $this->model->delete($config['table'], (int) $id);
        flash('success', $config['label'] . ' deleted.');
        Response::redirect('/admin/' . $module);
    }

    public function reorder(Request $request, string $module): never
    {
        Auth::require();
        $config = $this->module($module);
        $this->guardCsrf($request);
        $ids = array_filter(array_map('intval', explode(',', (string) $request->input('ordered_ids'))));
        if (($config['order'] ?? '') === 'display_order') {
            $this->model->reorder($config['table'], $ids);
        }
        Response::json(['ok' => true]);
    }

    private function module(string $module): array
    {
        if (!isset($this->modules[$module])) {
            Response::redirect('/admin');
        }

        return $this->modules[$module];
    }

    private function guardCsrf(Request $request): void
    {
        if (!Csrf::verify((string) $request->input('_csrf'))) {
            flash('error', 'Security token expired. Please try again.');
            Response::redirect('/admin');
        }
    }

    private function payload(Request $request, array $config, array $existing = []): array
    {
        $data = [];
        $uploader = new UploadService();
        foreach ($config['fields'] as $name => $field) {
            $type = $field['type'] ?? 'text';
            if (($config['table'] ?? '') === 'settings' && $name === 'setting_value') {
                $settingType = (string) $request->input('setting_type', $existing['setting_type'] ?? '');
                if (in_array($settingType, ['file', 'image'], true)) {
                    $uploaded = $uploader->store($request->file($name), $config['table']);
                    $data[$name] = $uploaded ?? ($existing[$name] ?? '');
                    continue;
                }
            }

            if (in_array($type, ['image', 'file'], true)) {
                $uploaded = $uploader->store($request->file($name), $config['table']);
                $data[$name] = $uploaded ?? ($existing[$name] ?? '');
                continue;
            }

            if ($type === 'checkbox') {
                $data[$name] = $request->input($name) ? 1 : 0;
                continue;
            }

            $data[$name] = trim((string) $request->input($name, $existing[$name] ?? ''));
            if ($type === 'datetime-local') {
                $data[$name] = str_replace('T', ' ', $data[$name]);
            }
        }

        if (($config['table'] ?? '') === 'blog_posts' && empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = SlugService::make($data['title']);
        }

        if (($config['table'] ?? '') === 'projects' && empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = SlugService::make($data['title']);
        }

        if (($config['table'] ?? '') === 'media_library') {
            $data['file_name'] = $data['file_name'] ?: basename((string) ($data['file_path'] ?? ''));
            $data['file_type'] = $data['file_type'] ?: pathinfo((string) ($data['file_path'] ?? ''), PATHINFO_EXTENSION);
        }

        return $data;
    }

    private function validatePayload(array $data, array $config): array
    {
        $required = [];
        foreach ($config['fields'] as $name => $field) {
            if (($field['required'] ?? false) === true) {
                $required[] = $name;
            }
        }

        $errors = Validator::required($data, $required);
        foreach ($data as $key => $value) {
            if (str_contains($key, 'email') && $value !== '' && !Validator::email((string) $value)) {
                $errors[$key] = 'Enter a valid email address.';
            }
        }

        return $errors;
    }
}

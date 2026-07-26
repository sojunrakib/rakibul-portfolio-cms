<?php
$isEdit = !empty($row['id']);
$action = $isEdit ? "/admin/{$module}/{$row['id']}/update" : "/admin/{$module}/store";
?>
<section class="admin-section">
  <div class="admin-heading row">
    <div><p><?= e($config['label']) ?></p><h1><?= e($isEdit ? 'Edit Record' : 'Create Record') ?></h1></div>
    <a class="admin-button muted" href="/admin/<?= e($module) ?>">Back</a>
  </div>
  <form class="admin-form" method="post" action="<?= e($action) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?php foreach ($config['fields'] as $name => $field): ?>
      <?php
        $type = $field['type'] ?? 'text';
        if ($module === 'settings' && $name === 'setting_value' && in_array(($row['setting_type'] ?? ''), ['file', 'image'], true)) {
            $type = 'file';
        }
        $value = $row[$name] ?? '';
      ?>
      <label class="<?= $type === 'checkbox' ? 'check-label' : '' ?>">
        <span><?= e($field['label']) ?></span>
        <?php if ($type === 'textarea'): ?>
          <textarea name="<?= e($name) ?>" rows="7"><?= e($value) ?></textarea>
        <?php elseif ($type === 'select'): ?>
          <select name="<?= e($name) ?>"><?php foreach (($field['options'] ?? []) as $key => $label): ?><option value="<?= e($key) ?>" <?= (string) $value === (string) $key ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select>
        <?php elseif ($type === 'checkbox'): ?>
          <input type="checkbox" name="<?= e($name) ?>" value="1" <?= (int) $value === 1 ? 'checked' : '' ?>>
        <?php elseif (in_array($type, ['image', 'file'], true)): ?>
          <?php if ($value): ?><small>Current: <?= e($value) ?></small><?php endif; ?>
          <input type="file" name="<?= e($name) ?>" accept="<?= $type === 'image' ? 'image/*' : 'image/*,application/pdf' ?>">
        <?php else: ?>
          <input type="<?= e($type) ?>" name="<?= e($name) ?>" value="<?= e($value) ?>" <?= ($field['required'] ?? false) ? 'required' : '' ?>>
        <?php endif; ?>
        <?php if (!empty($field['hint'])): ?><small><?= e($field['hint']) ?></small><?php endif; ?>
      </label>
    <?php endforeach; ?>
    <button class="admin-button" type="submit"><?= e($isEdit ? 'Save Changes' : 'Create') ?></button>
  </form>
</section>

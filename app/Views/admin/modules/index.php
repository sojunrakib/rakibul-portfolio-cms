<section class="admin-section">
  <div class="admin-heading row">
    <div><p><?= e($config['table']) ?></p><h1><?= e($config['label']) ?></h1></div>
    <?php if (($config['readonly_create'] ?? false) !== true): ?><a class="admin-button" href="/admin/<?= e($module) ?>/create">Create</a><?php endif; ?>
  </div>

  <form class="search-bar" method="get">
    <input name="q" value="<?= e($query) ?>" placeholder="Search <?= e($config['label']) ?>">
    <button type="submit">Search</button>
  </form>

  <?php if (($config['order'] ?? '') === 'display_order'): ?>
    <form class="reorder-form" method="post" action="/admin/<?= e($module) ?>/reorder" data-reorder-form>
      <?= csrf_field() ?><input type="hidden" name="ordered_ids" data-ordered-ids>
      <button type="submit">Save Drag Order</button>
    </form>
  <?php endif; ?>

  <div class="table-wrap">
    <table>
      <thead><tr><th>ID</th><?php foreach (array_slice(array_keys($config['fields']), 0, 4) as $field): ?><th><?= e($config['fields'][$field]['label']) ?></th><?php endforeach; ?><th>Actions</th></tr></thead>
      <tbody data-sortable>
      <?php foreach ($rows as $row): ?>
        <tr draggable="<?= (($config['order'] ?? '') === 'display_order') ? 'true' : 'false' ?>" data-id="<?= e($row['id']) ?>">
          <td><?= e($row['id']) ?></td>
          <?php foreach (array_slice(array_keys($config['fields']), 0, 4) as $field): ?>
            <?php $cell = (string) ($row[$field] ?? ''); $cell = function_exists('mb_strimwidth') ? mb_strimwidth($cell, 0, 90, '...') : substr($cell, 0, 90); ?>
            <td><?= e($cell) ?></td>
          <?php endforeach; ?>
          <td class="actions">
            <a href="/admin/<?= e($module) ?>/<?= e($row['id']) ?>/edit">Edit</a>
            <button type="button" data-delete-open data-action="/admin/<?= e($module) ?>/<?= e($row['id']) ?>/delete">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <?php $pages = max(1, (int) ceil($total / $perPage)); ?>
    <span><?= e($total) ?> records</span>
    <?php for ($i = 1; $i <= $pages; $i++): ?><a class="<?= $i === $page ? 'active' : '' ?>" href="?q=<?= e($query) ?>&page=<?= $i ?>"><?= $i ?></a><?php endfor; ?>
  </div>
</section>

<dialog class="delete-modal" data-delete-modal>
  <form method="post" data-delete-form>
    <?= csrf_field() ?>
    <h2>Delete record?</h2>
    <p>This removes the item from the CMS. This action cannot be undone.</p>
    <div><button type="button" data-delete-close>Cancel</button><button class="danger" type="submit">Delete</button></div>
  </form>
</dialog>

(() => {
  const modal = document.querySelector('[data-delete-modal]');
  const form = document.querySelector('[data-delete-form]');
  document.querySelectorAll('[data-delete-open]').forEach((button) => {
    button.addEventListener('click', () => {
      form.action = button.dataset.action;
      if (modal.showModal) modal.showModal();
    });
  });
  document.querySelector('[data-delete-close]')?.addEventListener('click', () => modal.close());

  const tbody = document.querySelector('[data-sortable]');
  let dragging = null;
  if (tbody) {
    tbody.addEventListener('dragstart', (event) => {
      dragging = event.target.closest('tr');
      dragging?.classList.add('dragging');
    });
    tbody.addEventListener('dragend', () => {
      dragging?.classList.remove('dragging');
      dragging = null;
    });
    tbody.addEventListener('dragover', (event) => {
      event.preventDefault();
      const after = [...tbody.querySelectorAll('tr:not(.dragging)')].find((row) => event.clientY <= row.getBoundingClientRect().top + row.offsetHeight / 2);
      if (!dragging) return;
      if (after) tbody.insertBefore(dragging, after);
      else tbody.appendChild(dragging);
    });
  }

  document.querySelector('[data-reorder-form]')?.addEventListener('submit', (event) => {
    const ids = [...document.querySelectorAll('[data-sortable] tr')].map((row) => row.dataset.id).join(',');
    document.querySelector('[data-ordered-ids]').value = ids;
  });
})();

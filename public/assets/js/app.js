/**
 * BNGRC — Suivi des collectes et distributions
 * Main JavaScript
 */
document.addEventListener('DOMContentLoaded', () => {

  // ============ Sidebar Toggle (mobile) ============
  const sidebar    = document.getElementById('sidebar');
  const toggler    = document.querySelector('.navbar-toggler');

  // Create overlay element for mobile
  const overlay = document.createElement('div');
  overlay.className = 'sidebar-overlay';
  document.body.appendChild(overlay);

  if (toggler && sidebar) {
    toggler.addEventListener('click', (e) => {
      // On mobile, toggle sidebar
      if (window.innerWidth < 992) {
        e.preventDefault();
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
      }
    });

    overlay.addEventListener('click', () => {
      sidebar.classList.remove('show');
      overlay.classList.remove('show');
    });
  }

  // ============ Besoins: Add/Remove Lines ============
  const btnAddLine = document.getElementById('btnAddLine');
  const lignesContainer = document.getElementById('lignesBesoins');

  if (btnAddLine && lignesContainer) {
    btnAddLine.addEventListener('click', () => {
      const firstLine = lignesContainer.querySelector('.ligne-besoin');
      if (firstLine) {
        const clone = firstLine.cloneNode(true);
        // Reset inputs
        clone.querySelectorAll('input').forEach(inp => inp.value = '');
        clone.querySelectorAll('select').forEach(sel => sel.selectedIndex = 0);
        lignesContainer.appendChild(clone);
        attachRemoveHandlers(lignesContainer, '.ligne-besoin');
      }
    });
    attachRemoveHandlers(lignesContainer, '.ligne-besoin');
  }

  // ============ Dons: Add/Remove Lines ============
  const btnAddLineDon = document.getElementById('btnAddLineDon');
  const lignesDonContainer = document.getElementById('lignesDons');

  if (btnAddLineDon && lignesDonContainer) {
    btnAddLineDon.addEventListener('click', () => {
      const firstLine = lignesDonContainer.querySelector('.ligne-don');
      if (firstLine) {
        const clone = firstLine.cloneNode(true);
        clone.querySelectorAll('input').forEach(inp => inp.value = '');
        clone.querySelectorAll('select').forEach(sel => sel.selectedIndex = 0);
        lignesDonContainer.appendChild(clone);
        attachRemoveHandlers(lignesDonContainer, '.ligne-don');
      }
    });
    attachRemoveHandlers(lignesDonContainer, '.ligne-don');
  }

  function attachRemoveHandlers(container, lineClass) {
    container.querySelectorAll('.btn-remove-line').forEach(btn => {
      btn.onclick = function() {
        const lines = container.querySelectorAll(lineClass);
        if (lines.length > 1) {
          this.closest(lineClass).remove();
        }
      };
    });
  }

  // ============ Dashboard: Search filter ============
  const searchVille = document.getElementById('searchVille');
  if (searchVille) {
    searchVille.addEventListener('input', function() {
      const val = this.value.toLowerCase();
      const rows = document.querySelectorAll('table tbody tr');
      rows.forEach(row => {
        const ville = row.querySelector('td')?.textContent.toLowerCase() || '';
        row.style.display = ville.includes(val) ? '' : 'none';
      });
    });
  }

  // ============ Bootstrap Tooltips ============
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
  tooltipTriggerList.forEach(el => {
    if (el.title && typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
      new bootstrap.Tooltip(el, { trigger: 'hover' });
    }
  });

  // ============ Active link highlight ============
  const currentPath = window.location.pathname;
  document.querySelectorAll('.sidebar-link').forEach(link => {
    if (link.getAttribute('href') === currentPath) {
      link.classList.add('active');
    }
  });

});

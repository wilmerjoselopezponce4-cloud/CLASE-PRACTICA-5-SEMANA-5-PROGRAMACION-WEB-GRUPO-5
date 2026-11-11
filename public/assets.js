(() => {
  'use strict';
  const $ = (selector, scope = document) => scope.querySelector(selector);
  const $$ = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));

  const sidebar = $('#sidebar');
  const scrim = $('#scrim');
  const toggle = $('#menuToggle');
  const setSidebar = open => {
    sidebar?.classList.toggle('open', open);
    scrim?.classList.toggle('visible', open);
    document.body.classList.toggle('menu-open', open);
  };
  toggle?.addEventListener('click', () => setSidebar(!sidebar.classList.contains('open')));
  scrim?.addEventListener('click', () => setSidebar(false));

  let lastFocus = null;
  const showModal = id => {
    const modal = document.getElementById(id);
    if (!modal) return;
    lastFocus = document.activeElement;
    modal.hidden = false;
    document.body.classList.add('modal-visible');
    setTimeout(() => $('input:not([type="hidden"]), select, button[data-close-modal]', modal)?.focus(), 30);
  };
  const closeModal = modal => {
    if (!modal) return;
    modal.hidden = true;
    document.body.classList.remove('modal-visible');
    lastFocus?.focus?.();
  };

  document.addEventListener('click', event => {
    const opener = event.target.closest('[data-open-modal]');
    if (opener) showModal(opener.getAttribute('data-open-modal'));
    if (event.target.closest('[data-close-modal]')) closeModal(event.target.closest('.modal-shell'));
    const dismiss = event.target.closest('[data-dismiss-flash]');
    if (dismiss) dismiss.closest('.toast-flash')?.remove();
    const togglePwd = event.target.closest('[data-toggle-password]');
    if (togglePwd) {
      const input = document.getElementById(togglePwd.dataset.togglePassword);
      if (input) {
        input.type = input.type === 'password' ? 'text' : 'password';
        togglePwd.textContent = input.type === 'password' ? 'Ver' : 'Ocultar';
      }
    }
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      const openModal = $('.modal-shell:not([hidden])');
      if (openModal) closeModal(openModal);
      else setSidebar(false);
    }
  });
  document.addEventListener('submit', event => {
    const message = event.target.dataset.confirm;
    if (message && !window.confirm(message)) event.preventDefault();
  });

  const taskForm = $('#taskForm');
  if (taskForm) {
    let members = {};
    try { members = JSON.parse($('#taskMembersJSON')?.textContent || '{}'); } catch (_) { members = {}; }
    const projectSelect = $('#taskProject');
    const assigneeSelect = $('#taskMember');
    const membersForProject = (projectId, selected = '') => {
      const choices = members[String(projectId)] || [];
      assigneeSelect.replaceChildren(new Option('Sin asignar', ''));
      choices.forEach(u => assigneeSelect.add(new Option(u.nombre, String(u.id))));
      assigneeSelect.value = String(selected || '');
    };
    membersForProject(projectSelect?.value || '');
    projectSelect?.addEventListener('change', () => membersForProject(projectSelect.value));
    const taskAction = $('input[name="action"]', taskForm);
    const formTitle = $('#taskModalTitle');
    const submitButton = $('#taskSubmit');
    const deleteForm = $('#taskDeleteForm');
    const projectFixed = $('#fixedTaskProject');
    const newTask = () => {
      taskForm.reset();
      taskAction.value = 'task_create';
      if (deleteForm) deleteForm.hidden = true;
      $('input[name="id"]', taskForm).value = '';
      if (projectFixed) projectSelect.value = projectFixed.value;
      membersForProject(projectSelect.value);
      if (formTitle) formTitle.textContent = 'Nueva tarea';
      if (submitButton) submitButton.innerHTML = 'Crear tarea <span aria-hidden="true">→</span>';
    };
    $$('[data-new-task]').forEach(btn => btn.addEventListener('click', newTask));
    $$('[data-edit-task]').forEach(btn => btn.addEventListener('click', () => {
      let task;
      try { task = JSON.parse(btn.getAttribute('data-edit-task') || '{}'); } catch (_) { return; }
      newTask();
      taskAction.value = 'task_update';
      if (deleteForm) { deleteForm.hidden = false; $('input[name="id"]', deleteForm).value = task.id || ''; }
      $('input[name="id"]', taskForm).value = task.id || '';
      $('input[name="titulo"]', taskForm).value = task.titulo || '';
      $('textarea[name="descripcion"]', taskForm).value = task.descripcion || '';
      projectSelect.value = String(task.proyecto_id || '');
      membersForProject(task.proyecto_id, task.usuario_id);
      $('select[name="prioridad"]', taskForm).value = task.prioridad || 'Media';
      $('select[name="estado"]', taskForm).value = task.estado || 'Pendiente';
      $('input[name="fecha_vencimiento"]', taskForm).value = task.fecha_vencimiento || '';
      if (formTitle) formTitle.textContent = 'Editar tarea';
      if (submitButton) submitButton.textContent = 'Guardar cambios';
      showModal('taskModal');
    }));
  }

  const userForm = $('#userEditForm');
  if (userForm) {
    $$('[data-edit-user]').forEach(btn => btn.addEventListener('click', () => {
      let user;
      try { user = JSON.parse(btn.getAttribute('data-edit-user') || '{}'); } catch (_) { return; }
      $('input[name="id"]', userForm).value = user.id;
      $('input[name="nombre"]', userForm).value = user.nombre;
      $('input[name="email"]', userForm).value = user.email;
      $('select[name="rol_id"]', userForm).value = String(user.rol_id);
      $('input[name="password"]', userForm).value = '';
      showModal('userEditModal');
    }));
  }
})();

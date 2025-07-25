let projects = JSON.parse(localStorage.getItem('projects')) || [];

function saveProjects() {
  localStorage.setItem('projects', JSON.stringify(projects));
}

function switchTab(tab) {
  document.querySelectorAll('.tab').forEach(t => (t.style.display = 'none'));
  document.getElementById(tab).style.display = 'block';

  if (tab === 'dashboard') {
    renderDashboard();
  } else if (tab === 'projects') {
    renderProjects('project-list-full', projects);
  }
}

function openModal() {
  document.getElementById('modal').style.display = 'block';
}

function closeModal() {
  document.getElementById('modal').style.display = 'none';
  document.getElementById('projectName').value = '';
  document.getElementById('projectStatus').value = 'To Do';
}

function addProject() {
  const name = document.getElementById('projectName').value.trim();
  const status = document.getElementById('projectStatus').value;

  if (name) {
    projects.push({ name, status });
    saveProjects();
    renderDashboard();
    closeModal();
  }
}

function renderDashboard() {
  document.getElementById('project-count').textContent = projects.length;
  renderProjects('project-list', projects);
}

function renderProjects(listId, listData) {
  const list = document.getElementById(listId);
  list.innerHTML = '';

  if (listData.length === 0) {
    list.innerHTML = '<li class="list-group-item text-muted">No projects found.</li>';
    return;
  }

  listData.forEach((project, index) => {
    const listItem = document.createElement('li');
    listItem.className = 'list-group-item d-flex justify-content-between align-items-center project-card';
    listItem.setAttribute('draggable', true);
    listItem.setAttribute('data-index', index);

    const badgeColor = getStatusColor(project.status);

    listItem.innerHTML = `
      <div>
        <strong>${project.name}</strong>
        <span class="badge bg-${badgeColor} ms-2">${project.status}</span>
      </div>
      <div>
        <button class="btn btn-sm btn-outline-secondary me-2" onclick="editProject(${index})">Edit</button>
        <button class="btn btn-sm btn-outline-danger" onclick="deleteProject(${index})">Delete</button>
      </div>
    `;

    addDragEvents(listItem);
    list.appendChild(listItem);
  });
}

function getStatusColor(status) {
  switch (status) {
    case 'To Do':
      return 'primary'; // Blue badge
    case 'In Progress':
      return 'warning'; // Yellow badge
    case 'Done':
      return 'success'; // Green badge
    default:
      return 'light';
  }
}

function deleteProject(index) {
  if (confirm('Are you sure you want to delete this project?')) {
    projects.splice(index, 1);
    saveProjects();
    renderDashboard();
    renderProjects('project-list-full', projects);
  }
}

function editProject(index) {
  const project = projects[index];
  const newName = prompt('Edit project name:', project.name);
  const newStatus = prompt('Edit status (To Do, In Progress, Done):', project.status);

  if (newName && newStatus) {
    projects[index] = { name: newName.trim(), status: newStatus.trim() };
    saveProjects();
    renderDashboard();
    renderProjects('project-list-full', projects);
  }
}

// Filter handler for dashboard only
document.querySelectorAll('.filter-option').forEach(item => {
  item.addEventListener('click', e => {
    e.preventDefault();
    const status = item.getAttribute('data-status');

    let filtered = projects;
    if (status !== 'all') {
      filtered = projects.filter(project => project.status === status);
    }

    renderProjects('project-list', filtered);
  });
});

// Drag and Drop Support
let dragSrcIndex = null;

function addDragEvents(item) {
  item.addEventListener('dragstart', (e) => {
    dragSrcIndex = parseInt(e.target.getAttribute('data-index'));
    e.dataTransfer.effectAllowed = 'move';
    e.target.style.opacity = '0.4';
  });

  item.addEventListener('dragover', (e) => {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
  });

  item.addEventListener('drop', (e) => {
    e.stopPropagation();
    const targetIndex = parseInt(e.target.closest('li').getAttribute('data-index'));

    if (dragSrcIndex !== null && dragSrcIndex !== targetIndex) {
      const temp = projects[dragSrcIndex];
      projects.splice(dragSrcIndex, 1);
      projects.splice(targetIndex, 0, temp);
      saveProjects();
      renderDashboard();
      renderProjects('project-list-full', projects);
    }
  });

  item.addEventListener('dragend', (e) => {
    e.target.style.opacity = '1';
  });
}

// On page load
renderDashboard();

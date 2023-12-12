function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}

function filterTasks() {
  const priority = document.getElementById('priorityFilter').value;
  const tasks = document.querySelectorAll('.task-card');

  tasks.forEach(task => {
    const taskPriority = task.dataset.priority;
    const priorityMatch = taskPriority === priority || priority === 'all';

    if (priorityMatch) {
      task.style.display = 'block';
    } else {
      task.style.display = 'none';
    }
  });
}




document.getElementById('priorityFilter').addEventListener('change', filterTasks);

// Adicionando filtro de busca




// Initial call to filterTasks to display tasks based on the initial filter values






document.getElementById('projectFilter').addEventListener('change', function() {
    var filter = this.value;

    if (filter === 'favorites') {
        document.getElementById('favoriteProjects').style.display = 'block';
        document.getElementById('notFavoriteProjects').style.display = 'none';
    } else if (filter === 'notFavorites') {
        document.getElementById('favoriteProjects').style.display = 'none';
        document.getElementById('notFavoriteProjects').style.display = 'block';
    } else {
        document.getElementById('favoriteProjects').style.display = 'none';
        document.getElementById('notFavoriteProjects').style.display = 'none';
    }
});


function reloadPage() {
  location.reload();
}




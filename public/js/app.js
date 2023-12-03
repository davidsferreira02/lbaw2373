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
  const searchValue = document.getElementById('searchInput').value.toLowerCase();
  const priority = document.getElementById('priorityFilter').value;


  const tasks = document.querySelectorAll('.task-card');

  tasks.forEach(task => {
      const title = task.querySelector('h3').innerText.toLowerCase();
      const taskPriority = task.dataset.priority;
    

      const titleMatch = title.includes(searchValue) || searchValue === '';
      const priorityMatch = taskPriority === priority || priority === 'all';

    //na database
    

      if (titleMatch && priorityMatch ) {
          task.style.display = 'block';
      } else {
          task.style.display = 'none';
      }
  });
}

document.getElementById('priorityFilter').addEventListener('change', filterTasks);

document.getElementById('searchInput').addEventListener('input', filterTasks);





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






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



document.addEventListener('DOMContentLoaded', function() {
    var forms = document.querySelectorAll('.complete-form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(form);
            var url = form.getAttribute('action');
            var taskCard = form.closest('.task-card');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                var button = form.querySelector('button');
                var paragraphs = taskCard.querySelectorAll('p');

                paragraphs.forEach(function(paragraph) {
                    if (paragraph.textContent.includes('isCompleted:')) {
                        if (data.iscompleted) {
                            button.textContent = 'Uncomplete';
                            button.classList.remove('btn-success');
                            button.classList.add('btn-warning');
                            paragraph.innerHTML = '<strong>isCompleted:</strong> <span>true</span>';
                        } else {
                            button.textContent = 'Mark as completed';
                            button.classList.remove('btn-warning');
                            button.classList.add('btn-success');
                            paragraph.innerHTML = '<strong>isCompleted:</strong> <span>false</span>';
                        }
                    }
                });
            })
            .catch(function(error) {
                console.error('Erro ao completar a tarefa:', error);
            });
        });
    });
});



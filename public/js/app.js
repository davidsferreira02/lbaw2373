
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
  
  function filterTasks(priority) {
    const taskCards = document.querySelectorAll('.task-card');
    
    taskCards.forEach(card => {
        if (card.dataset.priority === priority || priority === 'all') {
            card.style.display = 'block';
        } else {
            card.style.display = 'none'; 
        }
    });
}


document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const priority = this.dataset.priority;
        filterTasks(priority); 
    });
});


$(document).ready(function() {
  $('.complete-task-btn').click(function(e) {
      e.preventDefault();
      var taskId = $(this).data('task-id');
      var projectTitle = $(this).data('project-title');
      $.ajax({
          type: 'PATCH',
          url: '/project/' + projectTitle + '/task/' + taskId + '/complete',
          success: function(response) {
              location.reload();
          },
          error: function(err) {
              console.error('Erro ao completar a tarefa:', err);
          }
      });
  });
});

  

  

  

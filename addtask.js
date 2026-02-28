
const addTaskBtn = document.querySelector('.nav-block.purple'); 
const addTaskOverlay = document.getElementById('addTaskOverlay');
const cancelAddTask = document.getElementById('cancelAddTask');
const addTaskForm = document.getElementById('addTaskForm');
const todosToday = document.getElementById('todosToday');

addTaskBtn.addEventListener('click', () => {
    addTaskOverlay.classList.remove('hidden');
});

cancelAddTask.addEventListener('click', () => {
    addTaskOverlay.classList.add('hidden');
});

addTaskForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(addTaskForm);

    const res = await fetch('add_task.php', {
        method: 'POST',
        body: formData
    });

    if (res.ok) {
        const data = await res.json();
        
        addTaskOverlay.classList.add('hidden');

        const todoDiv = document.createElement('div');
        todoDiv.classList.add('todo');
        todoDiv.classList.add(data.priority ?? 'medium');
        todoDiv.innerHTML = `
            <div>
                <h3>${data.title}</h3>
                <span class="due">Due ${data.due_date} • ${data.due_time}</span>
            </div>
            <div class="actions">
                <button class="btn done">Done</button>
                <button class="btn giveup">Give Up</button>
            </div>
        `;

        todosToday.prepend(todoDiv);

        addTaskForm.reset();

    } else {
        alert('Error adding task');
    }
});
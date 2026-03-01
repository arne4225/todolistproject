document.addEventListener('DOMContentLoaded', () => {

    const addTaskBtn = document.querySelector('.sidebar:not(.right) .nav-block.purple');
    const addTaskOverlay = document.getElementById('addTaskOverlay');
    const cancelAddTask = document.getElementById('cancelAddTask');
    const addTaskForm = document.getElementById('addTaskForm');
    const todosToday = document.getElementById('todosToday');

    console.log('Add task button found:', !!addTaskBtn);
    console.log('Add task overlay found:', !!addTaskOverlay);
    console.log('Form found:', !!addTaskForm);

    if (addTaskBtn) {
        addTaskBtn.addEventListener('click', () => {
            addTaskOverlay.classList.remove('hidden');
        });
    }

    if (cancelAddTask) {
        cancelAddTask.addEventListener('click', () => {
            addTaskOverlay.classList.add('hidden');
        });
    }

    if (addTaskForm) {
        addTaskForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(addTaskForm);

            try {
                const res = await fetch('add_task.php', {
                    method: 'POST',
                    body: formData
                });

                if (!res.ok) {
                    try {
                        const err = await res.json();
                        console.error('Server error:', err);
                        alert('Failed to add task: ' + (err.error || 'Unknown error'));
                    } catch {
                        const errText = await res.text();
                        console.error('Server error:', errText);
                        alert('Failed to add task: Server error (check console for details)');
                    }
                    return;
                }

                const data = await res.json();

                addTaskOverlay.classList.add('hidden');

                const todoDiv = document.createElement('div');
                todoDiv.classList.add('todo', data.priority ?? 'medium');

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
            } catch (error) {
                console.error('Error adding task:', error);
                alert('Error adding task: ' + error.message);
            }
        });
    }

    const priority = document.querySelector('select[name="priority"]');

    if (priority) {
        priority.addEventListener('change', () => {
            priority.dataset.priority = priority.value;
        });

        // init
        priority.dataset.priority = priority.value;
    }

});
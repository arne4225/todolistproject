// todo_actions.js
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.todo .done, .todo .giveup').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const todoDiv = e.target.closest('.todo');
            const todoId = todoDiv.dataset.id;
            const isGiveUp = e.target.classList.contains('giveup');

            if (isGiveUp && !confirm('Are you sure you want to give up this task?')) {
                return;
            }

            const status = isGiveUp ? 'giveup' : 'done';

            const formData = new FormData();
            formData.append('todo_id', todoId);
            formData.append('status', status);

            try {
                const res = await fetch('update_status.php', {
                    method: 'POST',
                    body: formData
                });

                if (!res.ok) {
                    alert('Server error');
                    return;
                }

                // 🔥 ALTIJD verwijderen bij succes
                todoDiv.style.opacity = '0';
                todoDiv.style.transition = 'opacity 0.2s ease';

                setTimeout(() => {
                    todoDiv.remove();

                    const remaining = document.querySelectorAll('#todosToday .todo');
                    if (remaining.length === 0) {
                        document
                            .getElementById('todosToday')
                            .insertAdjacentHTML('beforeend', '<p class="muted">No todos yet 🎉</p>');
                    }
                }, 200);

            } catch (err) {
                alert('Network error');
            }
        });
    });
});
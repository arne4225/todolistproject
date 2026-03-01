
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.todo .done, .todo .giveup').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const todoDiv = e.target.closest('.todo');
            const todoId = todoDiv.dataset.id;
            const status = e.target.classList.contains('done') ? 'done' : 'giveup';

            const formData = new FormData();
            formData.append('todo_id', todoId);
            formData.append('status', status);

            try {
                const res = await fetch('update_status.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();
                if (data.success) {
                    todoDiv.style.opacity = '0.5';
                    todoDiv.querySelectorAll('button').forEach(b => b.disabled = true);
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (err) {
                alert('Network error: ' + err.message);
            }
        });
    });
});
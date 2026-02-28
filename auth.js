document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('authOverlay');
    const loginForm = document.getElementById('loginForm');

    if (!overlay || !loginForm) return;

    window.hideAuthOverlay = () => {
        overlay.classList.add('hidden');

        setTimeout(() => {
            location.reload();
        }, 500);
    };
    window.hideAuthOverlay = hideAuthOverlay;

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const res = await fetch('login.php', {
            method: 'POST',
            body: new FormData(loginForm)
        });

        if (res.ok) {
            hideAuthOverlay();
        } else {
            alert('Invalid username or password');
        }
    });

    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const res = await fetch('register.php', {
                method: 'POST',
                body: new FormData(registerForm)
            });

            if (res.ok) {
                hideAuthOverlay();
            } else {
                alert('Registration failed');
            }
        });
    }
    const logoutBtn = document.getElementById('logoutBtn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', async () => {
            const overlay = document.getElementById('authOverlay');

            await fetch('logout.php');

            overlay.classList.remove('hidden');

            
        });
    }
});
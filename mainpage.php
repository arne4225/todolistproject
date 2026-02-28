<?php
session_start();
require 'db.php';

$isLoggedIn = isset($_SESSION['user_id']);
$user = null;

if ($isLoggedIn) {
    // Ingelogde user ophalen
    $stmtUser = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmtUser->execute([$_SESSION['user_id']]);
    $user = $stmtUser->fetch();
}

// Todos ophalen van ingelogde user
$stmt = $pdo->prepare("
    SELECT *
    FROM todos
    WHERE user_id = ?
    ORDER BY due_date, due_time
");
$stmt->execute([$_SESSION['user_id'] ?? 0]);
$todos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Todo Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="auth.js" defer></script>
    <script src="addtask.js" defer></script>
</head>

<body>

    <!-- AUTH OVERLAY -->
    <div class="auth-overlay <?= $isLoggedIn ? 'hidden' : '' ?>" id="authOverlay">
        <div class="auth-modal">
            <h2>Welcome back</h2>

            <!-- LOGIN -->
            <form id="loginForm" method="post" action="login.php">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>

            <div class="divider">or</div>

            <!-- REGISTER -->
            <form id="registerForm" method="post" action="register.php">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="secondary">Register</button>
            </form>
        </div>
    </div>

    <!-- ADD TASK OVERLAY -->
    <div class="auth-overlay hidden" id="addTaskOverlay">
        <div class="auth-modal">
            <h2>Add New Task</h2>
            <form id="addTaskForm">
                <input type="text" name="title" placeholder="Task title" required>
                <input type="date" name="due_date" required>
                <input type="time" name="due_time" required>
                <button type="submit">Add Task</button>
            </form>
            <div class="divider">or</div>
            <button type="button" class="btn giveup" id="cancelAddTask">Cancel</button>
        </div>
    </div>

    <div class="layout">

        <!-- LEFT SIDEBAR -->
        <aside class="sidebar">
            <div class="profile-card">
                <h3>Date</h3>
                <p class="muted"><?= date('d-m-Y') ?></p>
            </div>

            <div class="nav-block purple">Add task</div>
            <div class="nav-block red">Delete tasks</div>
            <div class="nav-block green">Stats</div>
            <div class="nav-block orange">Focus mode</div>
        </aside>

        <!-- MAIN APP -->
        <main class="app">
            <div class="content">

                <section class="summary">
                    <p>All tasks</p>
                </section>
                <section class="todos" id="todosToday">
                    <section class="todos">

                        <?php if (empty($todos)): ?>
                            <p class="muted">No todos yet 🎉</p>
                        <?php endif; ?>

                        <?php foreach ($todos as $todo): ?>
                            <div class="todo medium">
                                <div>
                                    <h3><?= htmlspecialchars($todo['title']) ?></h3>
                                    <span class="due">
                                        Due <?= date('d-m-Y', strtotime($todo['due_date'])) ?>
                                        <?php if (!empty($todo['due_time'])): ?>
                                            • <?= substr($todo['due_time'], 0, 5) ?>
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <div class="actions">
                                    <button class="btn done">Done</button>
                                    <button class="btn giveup">Give up</button>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </section>
                </section>
            </div>
        </main>

        <!-- RIGHT SIDEBAR -->
        <aside class="sidebar right">
            <div class="profile-card">
                <h3><?= htmlspecialchars($user['username'] ?? 'Guest') ?></h3>
                <p class="muted"><?= $isLoggedIn ? 'Logged in' : 'Not logged in' ?></p>
            </div>

            <div class="nav-block blue">Account settings</div>
            <div class="nav-block cyan">Profile</div>
            <div class="nav-block orange">Privacy</div>
            <div class="nav-block purple">Theme</div>
            <div class="nav-block red" id="logoutBtn">Logout</div>
        </aside>

    </div>

</body>

</html>
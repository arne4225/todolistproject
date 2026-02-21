<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Todo Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="app">

    <header class="topbar">
        <h1>todo<span>.</span></h1>
        <button class="add-btn">+ Add task</button>
    </header>

    <section class="summary">
        <p>Today</p>
        <h2>3h 40m total</h2>
    </section>

    <section class="todos">

        <div class="todo high">
            <div>
                <h3>Study PHP</h3>
                <span>90 min</span>
            </div>
            <input type="checkbox">
        </div>

        <div class="todo medium">
            <div>
                <h3>Go to the gym</h3>
                <span>60 min</span>
            </div>
            <input type="checkbox">
        </div>

        <div class="todo low">
            <div>
                <h3>Clean room</h3>
                <span>30 min</span>
            </div>
            <input type="checkbox">
        </div>

    </section>

</div>

</body>
</html>
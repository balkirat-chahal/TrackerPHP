<?php
// Pages: 'home' for the next todo, 'all' for full list

// Sample data for now for working on the design
$todos = [
    [
        'title' => 'Buy groceries',
        'description' => 'Milk, Bread, Eggs, Butter',
        'due' => '2025-06-06 18:00',
        'duration' => '30 minutes',
        'priority' => 'High'
    ],
    [
        'title' => 'Finish homework',
        'description' => 'Math and science exercises',
        'due' => '2025-06-07 12:00',
        'duration' => '2 hours',
        'priority' => 'Medium'
    ],
    [
        'title' => 'Call mom',
        'description' => 'Check in and catch up',
        'due' => '2025-06-06 20:00',
        'duration' => '15 minutes',
        'priority' => 'Low'
    ]
];

// Determine which page to show
$page = $_GET['page'] ?? 'home';        // If a page was provided then we use that otherwise we go to home
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Todo App</title>
  <style>
    /* Styling */

    /* Making the dark theme */
    body {
      margin: 0;
      font-family: sans-serif;
      background: #1e1e1e;
      color: #f3f3f3;
      display: flex;
      flex-direction: row;
      height: 100vh;
    }

    /* Navbar */
    nav {
      flex: 0 0 25%; /* 3/12 width on a large screen */
      background: #252526;
      display: flex;
      flex-direction: column;
      padding: 1rem;
    }
    nav a {
      color: #9cdcfe;
      margin: 1rem 0;
      text-decoration: none;
      font-weight: bold;
    }
    nav a:hover {
      color: #4fc1ff;
    }

    /* Main section of the website where todos are displayed */
    main {
      flex: 1;
      padding: 2rem;
      overflow-y: auto;
    }

    /* Todo card */
    .todo {
      border: 1px solid #333;
      border-left: 5px solid #4fc1ff;
      padding: 1rem;
      margin-bottom: 1rem;
      background: #2d2d2d;
      border-radius: 6px;
    }
    /* Different priority todo cards different colors */
    .todo.high { border-left-color: #f44747; }
    .todo.medium { border-left-color: #ffaf00; }
    .todo.low { border-left-color: #619955; }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }
      /* In a small screen, the navbar will move to bottom of page and become horizontal */
      nav {
        flex-direction: row;
        justify-content: space-around;
        flex: 0 0 auto;
        width: 100%;
        position: fixed;
        bottom: 0;
        left: 0;
        z-index: 10;
        padding: 0.5rem 0;
      }

      main {
        padding-bottom: 4rem; /* prevent content under nav */
      }

      nav a {
        margin: 0;
        padding: 0.5rem;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>
  <!-- Navigation Bar  -->
  <nav>
<!-- Directs to the page we want to go to -->
    <a href="?page=home">Home</a>
    <a href="?page=all">All Todos</a>
    <a href="#">Login</a>
  </nav>

  <!-- Main Content Area -->
  <main>
    <?php if ($page === 'home'): ?> <!-- On home page show only the next todo -->
      <h1>Next Todo</h1>
      <?php $todo = $todos[0]; ?>
      <div class="todo <?= strtolower($todo['priority']) ?>">
        <h2><?= $todo['title'] ?></h2>
        <p><?= $todo['description'] ?></p>
        <p><strong>Due:</strong> <?= $todo['due'] ?></p>
        <p><strong>Duration:</strong> <?= $todo['duration'] ?></p>
        <p><strong>Priority:</strong> <?= $todo['priority'] ?></p>
      </div>
    <?php elseif ($page === 'all'): ?>
      <h1>All Todos</h1>
      <?php foreach ($todos as $todo): ?> <!-- On All Todos page, show all todos -->
        <div class="todo <?= strtolower($todo['priority']) ?>">
          <h2><?= $todo['title'] ?></h2>
          <p><?= $todo['description'] ?></p>
          <p><strong>Due:</strong> <?= $todo['due'] ?></p>
          <p><strong>Duration:</strong> <?= $todo['duration'] ?></p>
          <p><strong>Priority:</strong> <?= $todo['priority'] ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>
</body>
</html>

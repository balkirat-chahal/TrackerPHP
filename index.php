<?php
include 'components.php';
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
  <link rel="stylesheet" type= "text/css" href="styles.css"/>
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
<!-- Here I am practicing using php and html syntax not using echo -->
  <main>
    <?php if ($page === 'home'): ?> <!-- On home page show only the next todo -->
      <h1>Next Todo</h1>
      <?php $todo = $todos[0]; 
        todoCard($todo);
      ?>
    <?php elseif ($page === 'all'): ?>
      <h1>All Todos</h1>
      <?php foreach ($todos as $todo):  // On All Todos page, show all todos
        todoCard($todo);
        ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>
</body>
</html>

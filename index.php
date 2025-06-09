<?php
include 'components.php';
// Pages: 'home' for the next todo, 'all' for full list, 'add' for task form

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

// For now just adding the task in the array, but later I will add it put it in a database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $todos[] = [
        'title' => $_POST['title'] ?? 'Untitled',
        'description' => $_POST['description'] ?? '',
        'due' => $_POST['due'] ?? '',
        'duration' => $_POST['duration'] ?? '',
        'priority' => $_POST['priority'] ?? 'Low'
    ];
}

// Determine which page to show
$page = $_GET['page'] ?? 'home';        // If a page was provided then we use that otherwise we go to home
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Todo App</title>
  <link rel="stylesheet" type="text/css" href="styles.css" />
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
    <!-------  HOME PAGE ------->
    <?php if ($page === 'home'): ?> <!-- On home page show only the next todo -->
      <h1>Next Todo</h1>
      <?php $todo = $todos[0]; 
        todoCard($todo);
      ?>
    
    <!------- ALL TODOS PAGE ------->
    <?php elseif ($page === 'all'): ?>
      <h1>All Todos</h1>
      <?php foreach ($todos as $todo):  // On All Todos page, show all todos
        todoCard($todo);
        ?>
      <?php endforeach; ?>


      <!-------- ADD TODO PAGE ----------->
      <?php elseif ($page === 'add'): ?>
      <h1>Add New Task</h1>
      <!-- Simple form to add a new task -->
      <form method="POST" class="task-form">
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" id="title" name="title" placeholder="e.g., Buy groceries" required>
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="description" placeholder="e.g., Milk, Eggs, Bread..." required></textarea>
        </div>

        <div class="form-group">
          <label for="due">Due Date & Time</label>
          <input type="datetime-local" id="due" name="due" required>
        </div>

        <div class="form-group">
          <label for="duration">Estimated Duration (in minutes)</label>
          <input type="number" id="duration" name="duration" placeholder="e.g., 30" required>
        </div>

        <div class="form-group">
          <label for="priority">Priority</label>
          <select id="priority" name="priority">
            <option value="High">High</option>
            <option value="Medium" selected>Medium</option>
            <option value="Low">Low</option>
          </select>
        </div>

        <button type="submit" class="submit-btn">+ Add Task</button>
      </form>
    <?php endif; ?>

  </main>

  <!-- Add task button -->
  <a href="?page=add" class="add-task-button">+ Add Task</a>
</body>
</html>

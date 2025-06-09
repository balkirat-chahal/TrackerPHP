<?php
include 'components.php';
include 'utils.php';
// Pages: 'home' for the next todo, 'all' for full list, 'add' for task form, 'login', 'signup'

// ######### Connecting to the Database #############

// Setting the credentials for the database
loadenv(__DIR__ . "/.env");
$host = 'localhost';
$user = getenv("DB_USER");
$password = getenv("DB_PASS");
$dbname = 'trackerphp';

// connecting to the mysql server
$conn = mysqli_connect($host, $user, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists and select it
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (!mysqli_query($conn, $sql)) {
    die("Error creating database: " . mysqli_error($conn));
}

mysqli_select_db($conn, $dbname);

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


// ######### Controllers ############

// For now just adding the task to the database, not using users yet so user_id is NULL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['page'] ?? '') === 'add') {
    $title = $_POST['title'] ?? 'Untitled';
    $description = $_POST['description'] ?? '';
    $due = $_POST['due'] ?? '';
    $duration = ($_POST['duration'] ?? '') . ' minutes';
    $priority = $_POST['priority'] ?? 'Low';

    // Use a prepared statement to safely insert the data
    $stmt = mysqli_prepare($conn, "INSERT INTO todos (user_id, title, description, due, duration, priority) VALUES (NULL, ?, ?, ?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssss", $title, $description, $due, $duration, $priority);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        die("Failed to prepare statement: " . mysqli_error($conn));
    }

    header("Location: ?page=all");
    exit();
}


// ######### UI #############

$page = $_GET['page'] ?? 'home';
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
    <a href="?page=home">Home</a>
    <a href="?page=all">All Todos</a>
    <a href="?page=login">Login</a>
  </nav>

  <!-- Main Content Area -->
  <main>

    <!-------  HOME PAGE ------->
    <?php if ($page === 'home'): ?>
      <h1>Next Todo</h1>
      <?php $todo = $todos[0];
        todoCard($todo);
      ?>

    <!------- ALL TODOS PAGE ------->
    <?php elseif ($page === 'all'): ?>
      <h1>All Todos</h1>
      <?php foreach ($todos as $todo):
        todoCard($todo);
      endforeach; ?>

    <!-------- ADD TODO PAGE ----------->
    <?php elseif ($page === 'add'): ?>
      <h1>Add New Task</h1>
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

    <!-------- LOGIN PAGE --------->
    <?php elseif ($page === 'login'): ?>
      <h1>Login</h1>
      <form class="auth-form">
        <div class="form-group">
          <label for="login-username">Username</label>
          <input type="text" id="login-username" name="username" placeholder="Enter your username" required>
        </div>

        <div class="form-group">
          <label for="login-password">Password</label>
          <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="submit-btn">Login</button>
        <p class="form-link">Don't have an account? <a href="?page=signup">Create one</a></p>
      </form>

    <!-------- SIGNUP PAGE --------->
    <?php elseif ($page === 'signup'): ?>
      <h1>Create Account</h1>
      <form class="auth-form">
        <div class="form-group">
          <label for="signup-username">Username</label>
          <input type="text" id="signup-username" name="username" placeholder="Choose a username" required>
        </div>

        <div class="form-group">
          <label for="signup-password">Password</label>
          <input type="password" id="signup-password" name="password" placeholder="Choose a password" required>
        </div>

        <button type="submit" class="submit-btn">Sign Up</button>
        <p class="form-link">Already have an account? <a href="?page=login">Login here</a></p>
      </form>
    <?php endif; ?>

  </main>

  <!-- Add task button -->
  <a href="?page=add" class="add-task-button">+ Add Task</a>
</body>
</html>

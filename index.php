<?php
session_start();

include 'components.php';
include 'utils.php';

// ######### Connecting to the Database #############

loadenv(__DIR__ . "/.env");
$host = 'localhost';
$user = getenv("DB_USER");
$password = getenv("DB_PASS");
$dbname = 'trackerphp';

$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// ######### Controllers ############

// Redirect if user not logged in and trying to access protected pages
$publicPages = ['login', 'signup'];
$page = $_GET['page'] ?? 'home';
if (!in_array($page, $publicPages) && !isset($_SESSION['user_id'])) {
    header("Location: ?page=login");
    exit();
}

// Handle signup form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'signup') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $email && $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: ?page=login");
        exit();
    }
}

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, "SELECT id, password FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $hashed);

    if (mysqli_stmt_fetch($stmt) && password_verify($password, $hashed)) {
        $_SESSION['user_id'] = $id;
        header("Location: ?page=home");
        exit();
    }
    mysqli_stmt_close($stmt);
}

// Handle logout
if ($page === 'logout') {
    session_destroy();
    header("Location: ?page=login");
    exit();
}

// Handle adding new todo (if logged in)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'add') {
    $title = $_POST['title'] ?? 'Untitled';
    $description = $_POST['description'] ?? '';
    $due = $_POST['due'] ?? '';
    $duration = ($_POST['duration'] ?? '') . ' minutes';
    $priority = $_POST['priority'] ?? 'Low';

    $stmt = mysqli_prepare($conn, "INSERT INTO todos (user_id, title, description, due, duration, priority) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isssss", $_SESSION['user_id'], $title, $description, $due, $duration, $priority);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: ?page=all");
    exit();
}

// ######### UI #############

$page = $_GET['page'] ?? 'home';

// Fetch user todos if logged in
$todos = [];
if (isset($_SESSION['user_id'])) {
    $stmt = mysqli_prepare($conn, "SELECT title, description, due, duration, priority FROM todos WHERE user_id = ? ORDER BY due ASC, priority ASC, duration ASC");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $title, $description, $due, $duration, $priority);
    while (mysqli_stmt_fetch($stmt)) {
        $todos[] = compact('title', 'description', 'due', 'duration', 'priority');
    }
    mysqli_stmt_close($stmt);
}
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
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="?page=home">Home</a>
      <a href="?page=all">All Todos</a>
      <a href="?page=logout">Logout</a>
    <?php else: ?>
      <a href="?page=login">Login</a>
    <?php endif; ?>
  </nav>

  <!-- Main Content Area -->
  <main>
    <h1 class="app-title"> Task Tracker </h1>
    <br/>
    <?php if ($page === 'home' && $todos): ?>
      <h1>Next Todo</h1>
      <?php todoCard($todos[0]); ?>

    <?php elseif ($page === 'all'): ?>
      <h1>All Todos</h1>
      <?php foreach ($todos as $todo) todoCard($todo); ?>

    <?php elseif ($page === 'add'): ?>
      <h1>Add New Task</h1>
      <form method="POST" class="task-form">
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
          <label for="due">Due Date & Time</label>
          <input type="datetime-local" id="due" name="due" required>
        </div>
        <div class="form-group">
          <label for="duration">Estimated Duration (in minutes)</label>
          <input type="number" id="duration" name="duration" required>
        </div>
        <div class="form-group">
          <label for="priority">Priority</label>
          <select id="priority" name="priority">
            <option value="High">High</option>
            <option value="Medium">Medium</option>
            <option value="Low" selected>Low</option>
          </select>
        </div>
        <button type="submit" class="submit-btn">+ Add Task</button>
      </form>

    <?php elseif ($page === 'login'): ?>
      <h1>Login</h1>
      <form method="POST" class="auth-form">
        <div class="form-group">
          <label for="login-username">Username</label>
          <input type="text" id="login-username" name="username" required>
        </div>
        <div class="form-group">
          <label for="login-password">Password</label>
          <input type="password" id="login-password" name="password" required>
        </div>
        <button type="submit" class="submit-btn">Login</button>
        <p class="form-link">Don't have an account? <a href="?page=signup">Create one</a></p>
      </form>

    <?php elseif ($page === 'signup'): ?>
      <h1>Create Account</h1>
      <form method="POST" class="auth-form">
        <div class="form-group">
          <label for="signup-username">Username</label>
          <input type="text" id="signup-username" name="username" required>
        </div>
        <div class="form-group">
          <label for="signup-email">Email</label>
          <input type="email" id="signup-email" name="email" required>
        </div>
        <div class="form-group">
          <label for="signup-password">Password</label>
          <input type="password" id="signup-password" name="password" required>
        </div>
        <button type="submit" class="submit-btn">Sign Up</button>
        <p class="form-link">Already have an account? <a href="?page=login">Login here</a></p>
      </form>
    <?php endif; ?>
  </main>

  <?php if (isset($_SESSION['user_id'])): ?>
    <a href="?page=add" class="add-task-button">+ Add Task</a>
  <?php endif; ?>
</body>
</html>

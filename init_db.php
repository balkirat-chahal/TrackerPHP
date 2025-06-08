<?php
// This file initializes the MySQL Database and creates the user and todos table.
include 'utils.php';
// Setting the credentials for the database
loadenv(__DIR__ . "/.env");
$host = 'localhost';
$user = getenv("DB_USER");
$password = getenv("DB_PASS");
$dbname = 'trackerphp';

// connecting to the mysql server
$conn = mysqli_connect($host, $user, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error()); // if failed to connect then give error
}

// Create database if not exists and select it
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (!mysqli_query($conn, $sql)) {
    die("Error creating database: " . mysqli_error($conn));
}

mysqli_select_db($conn, $dbname);

// Creating the users table
$sql_users = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!mysqli_query($conn, $sql_users)) {
    die("Error creating users table: " . mysqli_error($conn));
}

// Creating the todos table
$sql_todos = "
CREATE TABLE IF NOT EXISTS todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due DATETIME,
    duration VARCHAR(100),
    priority ENUM('High', 'Medium', 'Low') DEFAULT 'Low',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)";
if (!mysqli_query($conn, $sql_todos)) {
    die("Error creating todos table: " . mysqli_error($conn));
}

echo "Database and tables created successfully.";

mysqli_close($conn);
?>

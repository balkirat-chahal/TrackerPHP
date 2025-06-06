<!-- This files contains all of the components -->
<?php 
    function todoCard($todo){
        // This function takes a todo associative array and dislays it's properties in a card
        // I am practicing using the echo format here
        echo '<div class="todo ' . strtolower($todo['priority']) . '">';
        echo '<h2>' . htmlspecialchars($todo['title']) . '</h2>';
        echo '<p>' . htmlspecialchars($todo['description']) . '</p>';
        echo '<p><strong>Due:</strong> ' . htmlspecialchars($todo['due']) . '</p>';
        echo '<p><strong>Duration:</strong> ' . htmlspecialchars($todo['duration']) . '</p>';
        echo '<p><strong>Priority:</strong> ' . htmlspecialchars($todo['priority']) . '</p>';
        echo '</div>';
    }
?>
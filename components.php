<!-- This files contains all of the components -->
<?php 
    function todoCard($todo) {
        echo '<div class="todo ' . strtolower($todo['priority']) . '">';
        echo '<h2>' . htmlspecialchars($todo['title']) . '</h2>';
        echo '<p>' . htmlspecialchars($todo['description']) . '</p>';
        echo '<p><strong>Due:</strong> ' . htmlspecialchars($todo['due']) . '</p>';
        echo '<p><strong>Duration:</strong> ' . htmlspecialchars($todo['duration']) . '</p>';
        echo '<p><strong>Priority:</strong> ' . htmlspecialchars($todo['priority']) . '</p>';
        echo '<form method="POST" style="margin-top: 10px;">';
        echo '<input type="hidden" name="done_id" value="' . $todo['id'] . '">';
        echo '<button type="submit" class="done-btn">✓ Mark as Done</button>';
        echo '</form>';
        echo '</div>';
    }   
    function completedCard($todo) {
        echo '<div class="todo ' . strtolower($todo['priority']) . '">';
        echo '<h2>' . htmlspecialchars($todo['title']) . '</h2>';
        echo '<p>' . htmlspecialchars($todo['description']) . '</p>';
        echo '<p><strong>Due:</strong> ' . htmlspecialchars($todo['due']) . '</p>';
        echo '<p><strong>Duration:</strong> ' . htmlspecialchars($todo['duration']) . '</p>';
        echo '<p><strong>Priority:</strong> ' . htmlspecialchars($todo['priority']) . '</p>';
        echo '<h2 style="color:green">Completed</h2>';
        echo '</div>';
    } 
?>
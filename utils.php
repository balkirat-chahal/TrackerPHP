<?php 
function loadEnv($path) {
    /*
    This function loads the environment variables from a specified path.
    */
    if (!file_exists($path)) return;    // file not found
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // reading file but not taking newlines or empty lines
    foreach ($lines as $line) {
        list($name, $value) = explode('=', $line, 2); // extracting the name and value
        putenv(trim($name) . '=' . trim($value)); // loading these into the environment variables
    }
}
?>
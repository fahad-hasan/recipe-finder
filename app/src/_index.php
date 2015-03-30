<?php
function __autoload($class_name) {
    include strtolower($class_name) . '.class.php';
}
?>
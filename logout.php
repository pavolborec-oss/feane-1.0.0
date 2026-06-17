<?php
session_start();

// Vymazanie všetkých session údajov
session_unset();
session_destroy();

// Presmerovanie na úvodnú stránku
header("Location: index.php");
exit();
?>
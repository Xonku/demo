<?php
require_once 'controllers/config.php';
unset($_SESSION['user']);
header('Location: index.php');
exit;
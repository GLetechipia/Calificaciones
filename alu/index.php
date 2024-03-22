<?php
session_start();
if (!isset($_SESSION['numero_control'])) header("Location:./index.php");
header("Location:menu.php");
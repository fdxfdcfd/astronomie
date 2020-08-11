<?php
ob_start();
session_start();
session_unset();
$url = include 'config.php';
header("Location: " . $url . "login.php"); die;
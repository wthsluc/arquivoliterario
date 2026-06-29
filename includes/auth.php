<?php

session_start();

if (empty($_SESSION['Idusuario'])) {
    header('Location: ../pages/login.php');
    exit;
}

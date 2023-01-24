<?php

require 'includes/class-auth.php';
require 'includes/class-csrf.php';
require 'includes/class-user.php';
require
require
require
require


$paths = trim($_SERVER['REQUEST_URI'], '/');

$paths = parse_url($paths, PHP_URL_PATH);

switch($paths)
{
    case 'home':
        require 'pages/home.php';
        break;
    case 'login':
        require 'pages/login.php';
        break;
    case 'signup':
        require 'pages/signup.php';
        break;
    case 'cart':
        require 'pages/cart.php';
        break;
    case 'logout':
        require 'pages/logout.php';
        break;
    case '':

        break;
    case '':

        break;
}
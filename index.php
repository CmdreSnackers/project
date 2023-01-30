<?php
// session_set_cookie_params((60*60*24*31), '/', '.cloudwaysapps.com');
session_start();

require 'config.php';
require 'includes/class-db.php';
require 'includes/class-auth.php';
require 'includes/class-valid.php';
require 'includes/class-csrf.php';
require 'includes/class-user.php';
require 'includes/class-product.php';
require 'includes/class-message.php';


$paths = trim($_SERVER['REQUEST_URI'], '/');

$paths = parse_url($paths, PHP_URL_PATH);

switch($paths)
{
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
    case 'messages':
        require 'pages/messages.php';
        break;
    case 'dashboard':
        require 'pages/dashboard.php';
        break;
    case 'orders':
        require 'pages/orders.php';
        break;
    case 'product':
        require 'pages/product.php';
        break;
    case 'payment-verify':
        require "pages/payment-verification.php";
        break;
    case 'checkout':
        require "pages/checkout.php";
        break;
    case 'manage-product':
        require 'pages/manage-product.php';
        break;
    case 'manage-product-add':
        require 'pages/manage-product-add.php';
        break;
    case 'manage-product-edit':
        require 'pages/manage-product-edit.php';
        break;
    case 'admin':
        require 'pages/admin.php';
        break;
    case 'admin-add':
        require 'pages/admin-add.php';
        break;
    case 'admin-edit':
        require 'pages/admin-edit.php';
        break;
    default:
        require "pages/home.php";
        break;
}
<?php
if(Authentication::isLoggedIn()) {
    Authentication::logout();
}

header('Location: /login');
exit;


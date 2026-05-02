<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();
session_destroy();
redirect('/admin/login.php');

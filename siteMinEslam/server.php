<?php
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
if ($uri !== '/' && file_exists(__DIR__.'/assets'.$uri)) {
    return false;
}
require_once __DIR__.'/assets/index.php';
// include_path='C:\xampp\php\PEAR'
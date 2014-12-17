<?php
session_start();

//Load cofiguration
include 'config.php';
include inc . 'page.php';
openDB();
require inc . 'functions.php';
require inc . 'Category.php';

if(!isset($_GET['page'])){
    $page = 'index';
}else{
    $page = $_GET['page'];
}

loadPage($page);

closeDB();
<?php

$base_url = 'http://localhost/project/';

$db_host ='localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'project';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ('connection failed');

define('WP', 'myproject2024');


 
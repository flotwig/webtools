<?php
if (!IN_WEBTOOLS) { header('Location: ../index.php'); die(); }
header('Status: 404 Not Found');
header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
$page_title = '404 Not Found';
webtools_trigger_user_error('Sorry, the page you were looking for could not be found. Click <a href="' . $_SERVER['PHP_SELF'] . '">here</a> to return to the home page.');

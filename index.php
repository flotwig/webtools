<?php
// This is the code used to display individual pages within the site. For page code, please see the
// files in the /pages/ directory. If you'd like to edit the layout of the site, you should see
// template.html - there's very little that's relevant to the site layout within this file.
// ------------------------------------------------------------------------------------------------
set_error_handler('webtools_error_handler');
$default_tz = @date_default_timezone_get(); // get PHP's default timezone...
date_default_timezone_set($default_tz); // ...and reset it so we don't get silly errors when we use time functions
define('IN_WEBTOOLS',TRUE); // check this constant in your pages to see if the user is visiting via WebTools or via other less regulated means
define('START_TIME',microtime(TRUE)); // enables us to get loadtime stats almost down to the millisecond
ini_set('error_reporting',E_ALL|E_STRICT);
$config = array();
$page_title = '';
require('./config.php');
// Load up the template
$template = @file_get_contents($config['template']); // @ it so no ugly errors are seen if the script fails
if (!$template) {
	trigger_error('Unable to load the template. Please refer to the documentation for more details.',E_USER_ERROR);
	die();
}
if (empty($_GET['page'])) {
	$page = $config['homepage'];
} else {
	$page = explode('.',$_GET['page']);
	$page = end($page);
	$page = basename($page);
}
$self_link = $_SERVER['PHP_SELF'] . '?page=' . $page;
if (file_exists('./pages/' . $page . '.php')) {
	ob_start(); // begin output buffering so we can put the output of a page into a variable for templating
	require_once('./pages/' . $page . '.php');
	$content = ob_get_contents(); // put the output into a string variable
	ob_end_clean(); // kill the output buffer so we can output stuff, this time for real
	$navli = '';
	foreach ($config['enabled_modules'] as $mod_mach=>$mod_pret) {
		$navli .= '<li><a href="index.php?page=' . $mod_mach . '">' . $mod_pret . '</a></li>' . PHP_EOL;
	}
	$template = str_replace(array('%content%','%generationtime%','%year%','%pagetitle%','%page%','%basehref%','%pagehref%','%nav_li%'), // array of strings to replace
		array($content,round(microtime(TRUE)-START_TIME,6),date('Y'),$page_title,$page,$_SERVER['PHP_SELF'],$self_link,$navli), // stuff to replace the strings with
		$template);
	echo $template;
} else {
	header('Location: ' . $_SERVER['PHP_SELF'] . '?page=' . $config['404page']); 
}
function webtools_error_handler($errno,$errstr,$errfile,$errline,$errcontext) {
	global $config;
	if (error_reporting()!==0) { // if they were using @, let's not show an error
		if ($config['showerrors']) {
			webtools_trigger_user_error('Error (' . $errno . '): ' . $errstr . ' (' . basename($errfile) . ', line ' . $errline . ')');
		}
		if ($config['dieonerror']) {
			die();
		}
	}
	return TRUE;
}
function webtools_trigger_user_error($text,$level='error') { // $level is one of 'error','warning','info' - ranging from most serious to least
	echo '<div class="alert alert-' . $level . '">' . $text . '</div>';
}

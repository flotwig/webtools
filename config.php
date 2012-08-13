<?php
// WebTools configuration file
// General WebTools configuration
	$config['ratelimit'] = 5; // The amount of time, in seconds, before an IP can make another request.
	$config['showerrors'] = TRUE; // Should the script output errors to the browser if they occur? Set to FALSE for production environments, TRUE for development environments.
	$config['dieonerror'] = FALSE; // Should the script die() if ANY error or warning occurs?
	$config['limitfolder'] = './ratelimit/'; // The folder in which rate limit information is stored.
	$config['template'] = './template.html'; // The location of the page template.
	$config['homepage'] = 'home'; // The name of the module used to handle requests that don't specify a page.
	$config['404page'] = '404'; // The name of the page used to handle requests to non-existing pages.
	$config['enabled_modules'] => array(
		'whois'=>'Whois Database Lookup',
		'reverseip'=>'Reverse IP Lookup',
		'getips'=>'Domain to IP Tool',
		'headers'=>'HTTP View Headers',
		'dns'=>'DNS Record Lookup',
		'check-up'=>'Down for everyone or just me?',
		'bigurl'=>'Short URL Expander',
	);
// "whois" page configuration
	$config['serverlist'] = './whois-servers.txt'; // The location of the whois-servers.txt file, relative to index.php.
	$config['maxredirs'] = 1; // The maximum number of WHOIS servers to bounce through. Recommended value is 1.

<?php
if (!IN_WEBTOOLS) { header('Location: ../index.php'); die(); }
$page_title = "Whois Database Lookup Tool";
// Let's load up whois-servers.txt and parse it into a handy-dandy key=>value array.
$raw_servers = @file_get_contents($config['serverlist']);
if (!$raw_servers) {
	die(webtools_trigger_user_error('Unable to read the whois server listing. Please refer to the documentation for details.'));
}
$raw_servers = explode("\n",$raw_servers);
$servers = array();
$lookup = FALSE;
foreach ($raw_servers as $raw_server) {
	$raw_server = explode(' ',$raw_server);
	if (count($raw_server)==2) { // this way we don't have any invalid entries throwing a wrench in the works
		$servers[trim($raw_server[0])]=trim($raw_server[1]);
	}
}
unset($raw_servers);
if (isset($_GET['domain'])) {
	// we have this link coming in from another doohickey, let's extract the relevant values
	$dom = explode('.',$_GET['domain']);
	$tld = end($dom);
	if ($tld=='uk'||$tld=='au') {
		$tld .= '.' . prev($dom);
	}
	$dom = prev($dom);
	$_POST['domain'] = $dom;
	$_POST['tld'] = $tld;
	$_GET['lookup'] = TRUE;
}
if (isset($_GET['lookup'])) {
	/*if (file_exists($config['limitfolder'] . $_SERVER['REMOTE_ADDR'])) {
		if ((filemtime($config['limitfolder'] . $_SERVER['REMOTE_ADDR'])-$config['ratelimit'])<time()) {
			file_put_contents($config['limitfolder'] . $_SERVER['REMOTE_ADDR'],'');
		} else {
			header('Location: ' . $self_link . '&ratelimit'); die(); // they've hit the rate limit
		}
	} else {
		file_put_contents($config['limitfolder'] . $_SERVER['REMOTE_ADDR'],'');
	}*/
	if (count($_POST)!==2||!isset($servers[$_POST['tld']])) {
		header('Location: ' . $self_link); die(); // attempted exploit - don't tell the attacker how we detected it
	} elseif (empty($_POST['domain'])||empty($_POST['tld'])) {
		header('Location: ' . $self_link . '&nodomain'); die(); // they neglected to enter a domain name
	} else {
		$domain = explode('.',$_POST['domain']);
		if(!preg_match('/^[a-z\d][a-z\d-]{0,62}$/i',end($domain))||preg_match('/-$/',end($domain))||strlen(end($domain)>255)) {
			header('Location: ' . $self_link . '&invaliddomain'); die(); // bad domain :/
		} else {
			$lookup = TRUE;
			$domain = strtolower(end($domain));
			$tld = strtolower($_POST['tld']);
			$server = $servers[$tld];
		}
	}
}
if (isset($_GET['servers'])) {
	echo '<strong>List of servers used by this script</strong>';
	echo '<table>
		<tr><th>TLD</th><th>Whois Server</th></tr>';
	foreach ($servers as $tld=>$server) {
		echo '<tr><td>.' . $tld . '</td><td>' . $server . '</td></tr>';
	}
	echo '</table>';
} elseif ($lookup) {
	echo '<h4 style="float:left;">WHOIS information about ' . $domain . '.' . $tld . '</h4> <a style="float:right;" class="label label-info" href="' . $self_link . '&lookup&domain=' . $domain . '.' . $tld . '">Permalink to these results</a>
	<br>';
	$lookupagain = TRUE;
	$iterations = 0;
	while ($lookupagain) {
		$iterations++;
		$server = trim($server);
		if (strpos(strtolower($server),'verisign')) {
			$query = '=' . $domain; // verisign whois servers only return the results we want if we use the = search operator because they can afford to be different
		} else {
			$query = $domain;
		}
		$query .= '.' . $tld;
		$whois = @fsockopen($server,43,$errno,$errstr,5);
		if (!$whois) {
			break;
		} else {
			$output = array();
			stream_set_blocking($whois,1); // prevents 100% CPU usage if server is laggy *cough* verisign
			fwrite($whois,$query . "\r\n");
			while (!feof($whois)) {
				$buffer = fgets($whois);
				$output[] = $buffer;
				$trimmedbuffer = trim($buffer);
				if (substr($trimmedbuffer,0,13)=='Whois Server:') { // thin-server support
					$bufferparts = explode(' ',$trimmedbuffer);
					$server = end($bufferparts);
				}
			}
			@fclose($whois);
		}
		if ($server!==$servers[$tld]&&$iterations<=$config['maxredirs']) {
			$lookupagain=TRUE;
			$servers[$tld]=$server;
		} else {
			$lookupagain=FALSE;
		}
	}
	echo '<pre class="well">';
	foreach ($output as $line) {
		echo htmlentities($line);
	}
	echo '</pre>';
} else {
?>
Look up information about any domain on the Internet, straight from the source.<br>
<?php 
if (isset($_GET['nodomain'])) { 
	webtools_trigger_user_error('You need to enter a domain name to look up!');
} elseif (isset($_GET['invaliddomain'])) {
	webtools_trigger_user_error('That\'s not a valid domain.');
} elseif (isset($_GET['ratelimit'])) {
	webtools_trigger_user_error('You can only make 1 lookup in every ' . number_format($config['ratelimit']) . ' seconds.','warning');
}
?>
<form action="<?php echo $self_link; ?>&lookup" method="POST" id="lookupform">
www.
<input type="text" name="domain">.
<select name="tld">
	<?php foreach ($servers as $tld=>$server) { echo '<option value="' . $tld . '">' . $tld . '</option>'; } ?>
</select><br>
<input type="submit" value="Look it up!">
</form>
<?php
}

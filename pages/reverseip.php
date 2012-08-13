<?php
if (!IN_WEBTOOLS) { header('Location: ../index.php'); die(); }
$page_title = 'Reverse IP Lookup';
if (isset($_GET['ip'])) {
	if (filter_var(trim($_GET['ip']),FILTER_VALIDATE_IP)) {
		$host = gethostbyaddr($_GET['ip']);
		if ($host==$_GET['ip']) {
			webtools_trigger_user_error('There\'s not a domain name associated with that IP address.');
		} else {
			echo '<strong>IP Lookup performed successfully.</strong><pre>
IP Address:	' . $_GET['ip'] . '
Host name:	' . $host . '
<a href="' . $_SERVER['PHP_SELF'] . '?page=getips&amp;domain=' . $host . '">View other IP addresses this domain resolves to</a>
<a href="' . $_SERVER['PHP_SELF'] . '?page=whois&amp;domain=' . $host . '">Perform a WHOIS lookup on this domain</a>
</pre>';
		}
	} else {
		webtools_trigger_user_error('That\'s not a valid IPv4 or IPv6 address.');
	}
}
?>
This tool uses the domain name system to see what domain name is associated with an IPv4 or IPv6 address. <br>
<form action="<?php echo $self_link; ?>" method="GET">
	<input name="page" type="hidden" value="<?php echo $page; ?>">
	IP Address: <input name="ip"> <input type="submit" value="Look it up!">
</form>

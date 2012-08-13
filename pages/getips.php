<?php
if (!IN_WEBTOOLS) { header('Location: ../index.php'); die(); }
$page_title = 'IP Address Lookup Tool';
if (isset($_GET['domain'])) {
	$domain = trim($_GET['domain']);
	$hosts = gethostbynamel($domain);
	if (is_array($hosts)) {
	     echo '<pre>Host ' . $domain . ' resolves to:';
	     foreach ($hosts as $ip) {
		  echo "\n" . '	IP: ' . $ip . ' <a href="' . $_SERVER['PHP_SELF'] . '?page=reverseip&amp;ip=' . $ip . '">[reverse IP lookup]</a>';
	     }
	     echo '</pre>';
	} else {
	     webtools_trigger_user_error('We were unable to find that domain name in the domain name system.');
	}
} else {
	$domain = 'example.com';
}
?>
This tool uses the domain name system to see what domain name is associated with an IPv4 or IPv6 address. <br>
<form action="<?php echo $self_link; ?>" method="GET">
	<input name="page" type="hidden" value="<?php echo $page; ?>">
	Domain name: <input name="domain" value="<?php echo $domain; ?>"> <input type="submit" name="submit" value="Look it up!">
</form>

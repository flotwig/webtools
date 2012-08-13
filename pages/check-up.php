<?php
if (!IN_WEBTOOLS) { header('Location: ../index.php'); die(); }
$page_title = 'Is it up or not?';
if (count($_GET)==2) {
	$domain = htmlentities($_GET['domain']);
	$domain = trim($domain,'.');
	$domain = trim($domain);
	$domain = strtolower($domain);
	if (parse_url($domain,PHP_URL_HOST)!==NULL) {
		$domain = parse_url($domain,PHP_URL_HOST);
	}
	if (!preg_match('/^[a-z\d][a-z\d-\.]{0,62}$/i',$domain)||preg_match('/-$/',$domain)||strlen($domain)>255||!strstr($domain,'.')) {
		webtools_trigger_user_error('That doesn\'t appear to be a valid domain there, cowboy.');
	} elseif (!@fsockopen($domain,80,$erra,$errb,2)) {
		webtools_trigger_user_error('It\'s not just you! <strong>' .  $domain . '</strong> looks down from here, too!','warning');
	} else {
		webtools_trigger_user_error('It\'s just you, <strong>' . $domain . '</strong> is up.','info');
	}
}
?>
<form action="<?php echo $self_link; ?>" method="GET">
	<input name="page" type="hidden" value="<?php echo $page; ?>">
	Is <input id="domain-field" name="domain"> down for everyone <a href="javascript:document.forms[0].submit();">or just me?</a><br>
	<input type="submit" value="Check!">	
</form>

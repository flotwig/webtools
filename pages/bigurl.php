<?php
if (!IN_WEBTOOLS) { header('Location: ../index.php'); die(); }
$page_title = 'URL Expander';
if (count($_GET)==2&&isset($_GET['short'])) {
	$short = urldecode($_GET['short']);
	if (filter_var($short,FILTER_VALIDATE_URL)&&(substr($short,0,7)=='http://'||substr($short,0,8)=='https://')) {
		stream_context_get_default(array('http'=>array('max_redirects'=>0,'method'=>'HEAD','ignore_errors'=>1)));
		$headers = @get_headers($short,TRUE);
		if (filter_var($headers['Location'],FILTER_VALIDATE_URL)) {
			echo '<strong>Expanded URL</strong><br>';
			echo '<a href="' . htmlentities($short) . '" rel="nofollow">' . htmlentities($short) . '</a> leads to: <br>';
			echo '<strong><a href="' . htmlentities($headers['Location']) . '" rel="nofollow">' . htmlentities($headers['Location']) . '</a></strong><br><br>';
		} else {
			webtools_trigger_user_error('We couldn\'t find that address on the Internet. Sorry.');
		}
	} else {
		webtools_trigger_user_error('That\'s not a valid HTTP URL. A valid URL looks like http://bit.ly/wf54f');
	}
}
?>
Short vanity URLs are nice and all, but sometimes you might want to see what that short link could
be sending you to <strong>before</strong> you click it. That's what this tool is for.
<form action="<?php echo $self_link; ?>" method="GET">
	<input name="page" type="hidden" value="<?php echo $page; ?>">
	Short link to expand: <input name="short">
	<input type="submit" value="Expand that link!">
</form>

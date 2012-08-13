<?php
if (!IN_WEBTOOLS) { header('Location: ../index.php'); die(); }
$page_title = 'HTTP Header Lookup Tool';
if (isset($_POST['url'])) {
	if (filter_var($_POST['url'],FILTER_VALIDATE_URL)&&(substr($_POST['url'],0,7)=='http://'||substr($_POST['url'],0,8)=='https://')) {
		stream_context_get_default(array('http'=>array('max_redirects'=>0,'method'=>'HEAD','ignore_errors'=>1)));
		$headers = @get_headers($_POST['url'],TRUE);
		if ($headers) {
			echo '<strong>HTTP Headers for ' . substr(htmlentities($_POST['url']),0,100) . '</strong><pre>';
			foreach ($headers as $id=>$header) {
				if (is_numeric($id)) {
					echo htmlentities(str_repeat(' ',20) . $header . "\n");
				} else {
					if (is_array($header)) {
						foreach ($header as $xhead) {
							echo htmlentities(str_pad($id . ': ',20) . $xhead . "\n");
						}
					} else {
						echo htmlentities(str_pad($id . ': ',20) . $header . "\n");
					}
				}
			}
			echo '</pre>';
		} else {
			webtools_trigger_user_error('We couldn\'t find that address on the Internet. Sorry.');
		}
	} else {
		webtools_trigger_user_error('That\'s not a valid HTTP URL. A valid URL looks like http://example.com/index.html');
	}
}
?>
Using this tool, you can view the HTTP headers of any page on the Internet.<br>
<form action="<?php echo $self_link; ?>" method="POST">
	URL (including http): <input name="url" value=""> <input type="submit" name="submit" value="Get Headers">
</form>

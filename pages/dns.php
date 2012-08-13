<?php
if (!IN_WEBTOOLS) { header('Location: ../index.php'); die(); }
$page_title = 'DNS Record Lookup Utility';
$record_types = array('ANY', 'A', 'CNAME', 'HINFO', 'MX', 'NS', 'PTR', 'SOA', 'TXT', 'AAAA', 'SRV', 'NAPTR', 'A6');
if (isset($_POST['domain'])&&isset($_POST['types'])&&defined('DNS_' . $record_types[$_POST['types']])) { // do a bit of fraud detection here
	$records = dns_get_record(trim($_POST['domain']),constant('DNS_' . $record_types[$_POST['types']]));
	if ($records) {
		echo '<table style="text-align:left;vertical-align:top;width:100%;" class="table table-bordered table-striped table-condensed">
		<thead><tr><th>Host name</th><th>Class</th><th>Type</th><th>TTL</th><td>&nbsp;</td></tr></thead>
		<tbody>';
		foreach ($records as $record) {
			if (!empty($record['type'])) { // to account for some DNS bugs
				echo '<tr><th>' . $record['host'] . '</th><td>' . $record['class'] . '</td><td>' . $record['type'] . '</td><td>' . $record['ttl'] . '</td><td>';
				foreach ($record as $id=>$attr) {
					if ($id!=='host'&&$id!=='ttl'&&$id!=='type'&&$id!=='class') {
						if (!is_array($attr)) {
							$attr = array($attr);
						}
						foreach ($attr as $att) {
							echo '<em>' . $id . '</em>: ' . htmlentities($att) . '<br>';
						}
					}
				}
				echo '</td></tr>';
			}
		}
		echo '</tbody></table>';
	} else {
		webtools_trigger_user_error('We were unable to look up the domain you specified. Are you sure it is valid?');
	}
}
?>
You can use this utility to see the domain name system records attached to a specific domain name.
<form action="<?php echo $self_link; ?>" method="POST">
	Domain name: <input name="domain"> Record type(s) to return: <select name="types"><?php 
	foreach ($record_types as $id=>$type) {
		echo '<option value="' . $id . '">' . $type . '</option>';
	}
	?></select><br>
	<input type="submit" value="Perform DNS Lookup">
</form>

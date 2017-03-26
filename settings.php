<?php

$db = get_db();

if (count($postvars) > 0) {
	$editsetting = $db->prepare('UPDATE settings SET value=? WHERE key=?');
	foreach ($postvars as $k => $v) {
		$editsetting->execute(array($v, $k));
	}
}

$settings = load_settings($db);
$path = get_path($db, $inode);

?>
<!doctype HTML>
<html>
<head>
	<title>txt</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body{max-width:50em;margin:auto;font-family:<?php echo $settings->fontfamily; ?>,sans-serif;font-size:<?php echo $settings->fontsize; ?>}
		th{text-align:left}
		td{font-family:monospace;white-space:pre}
		a,a:visited{color:#00f}<?php echo $settings->customcss; ?>
	</style>
</head>
<body>
	<?php printmessages(); ?>
	<h3><?php show_path($path); ?></h3>
	Settings:<br/>
	<form action="<?php make_url('settings', $inode); ?>" method="POST">
		<table border="0">
		<?php
			$sets = get_object_vars($settings);
			foreach ($sets as $k => $v) {
				if (!isset($postvars[$k])) {
					$postvars[$k] = $v;
				}
				echo '<tr><td>';
				echo $k;
				echo '</td><td>';
				input('type:text,name:' . $k);
				echo '</td></tr>';
			}
			?>
		</table>
		<input type="submit" value="save" />
	</form>
</body>
</html>

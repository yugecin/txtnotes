<?php

$db = get_db();
$settings = load_settings($db);
$path = get_path($db, $inode);

$content = '';
$file = simple_select($db, 'SELECT inode,name FROM files WHERE inode=?', array($inode));

if (count($file) != 1) {
	$file = null;
	$messages[] = 'File does not exist.';
} else {
	if (isset($postvars['to'])) {
		simple_execute($db, 'UPDATE files SET name=? WHERE inode=?', array($postvars['to'], $inode));
		header('Location: ' . $URL . $user . '/browse/' . get_parentinode($path) . '/');
		die();
	} else {
		$postvars['to'] = $file[0]->name;
	}
}

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
		input{width:100%}
	</style>
</head>
<body>
	<?php printmessages(); ?>
	<h3><?php show_path($path); ?></h3>
	<?php if ($file != null): ?>
	New name:<br/>
	<form action="<?php make_url('rename', $inode); ?>" method="POST">
		<?php input('type:text,name:to'); ?><br/>
		<input type="submit" value="move" />
	</form>
	<?php endif; ?>
</body>
</html>

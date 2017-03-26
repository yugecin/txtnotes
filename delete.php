<?php

$db = get_db();
$settings = load_settings($db);
$path = get_path($db, $inode);

$content = '';
$file = simple_select($db, 'SELECT parent, isdir FROM files WHERE inode=?', array($inode));

$isempty = true;
if (count($file) != 1) {
	$file = null;
	$messages[] = 'File does not exist.';
} else {
	if (isset($postvars['yes'])) {
		simple_execute($db, 'UPDATE files SET parent=? WHERE parent=?', array($file[0]->parent, $inode));
		simple_execute($db, 'DELETE FROM files WHERE inode=?', array($inode));
		header('Location: ' . $URL . $user . '/browse/' . $file[0]->parent . '/');
		die();
	}
	if ($file[0]->isdir) {
		$r = simple_select($db, 'SELECT inode FROM files WHERE parent=?', array($inode));
		if (count($r) > 0) {
			$isempty = false;
		}
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
	</style>
</head>
<body>
	<?php printmessages(); ?>
	<h3><?php show_path($path); ?></h3>
	<?php if ($file != null): ?>
	<?php if (!$isempty): ?>
	<p>Selected folder is not empty! Everything inside will be placed in the parent folder.</p>
	<?php endif; ?>
	<form action="<?php make_url('delete', $inode); ?>" method="POST">
		<?php input('type:hidden,name:yes,value:hi'); ?>
		<input type="submit" value="Delete" />
	</form>
	<?php endif; ?>
</body>
</html>

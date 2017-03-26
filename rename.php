<?php

$db = get_db();
$path = get_path($db, $inode);

$content = '';
$file = simple_select($db, 'SELECT inode FROM files WHERE inode=?', array($inode));

if (count($file) != 1) {
	$file = null;
	$messages[] = 'File does not exist.';
} else {
	if (isset($postvars['to'])) {
		simple_execute($db, 'UPDATE files SET name=? WHERE inode=?', array($postvars['to'], $inode));
		header('Location: ' . $URL . $user . '/browse/' . get_parentinode($path) . '/');
		die();
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
		body{max-width:50em;margin:auto;font-family:Tahoma,sans-serif;font-size:100%}
		th{text-align:left}
		td{font-family:monospace;white-space:pre}
		a,a:visited{color:#00f}
		textarea{width:100%}
	</style>
</head>
<body>
	<?php printmessages(); ?>
	<h3><?php show_path($path); ?></h3>
	<?php if ($file != null): ?>
	New name:<br/>
	<form action="<?php make_url('rename', $inode); ?>" method="POST">
		<?php input('type:text,name:to'); ?>
		<input type="submit" value="move" />
	</form>
	<?php endif; ?>
</body>
</html>

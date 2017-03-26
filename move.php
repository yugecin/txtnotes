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
		$newparent = $postvars['to'];
		$target = simple_select($db, 'SELECT isdir, inode FROM files WHERE inode=?', array($newparent));
		if ($newparent != 0 && (count($target) != 1 || $target[0]->isdir != 1)) {
			$messages[] = 'Invalid target.';
			goto nomove;
		}
		$newpath = get_path($db, $newparent);
		foreach ($newpath as $p) {
			if ($p->inode == $inode) {
				$messages[] = 'Invalid target. A directory cannot be placed into itself or subdirectories.';
				goto nomove;
			}
		}
		simple_execute($db, 'UPDATE files SET parent=? WHERE inode=?', array($newparent, $inode));
		header('Location: ' . $URL . $user . '/browse/' . $newparent . '/');
		die();
	} nomove:
	$folders = simple_select($db, 'SELECT inode, parent, name FROM files WHERE isdir=1', array());
	$resolvedfolders = array();

	$forbiddenfolders = array();
	$resolvedfolders[0] = '/root/';
	while (count($folders) > 0) {
		$tounset = array();
		foreach ($folders as $k => $f) {
			if ($f->inode == $inode || in_array($f->parent, $forbiddenfolders)) {
				$forbiddenfolders[] = $f->inode;
				$tounset[] = $k;
			} else if (isset($resolvedfolders[$f->parent])) {
				$resolvedfolders[$f->inode] = $resolvedfolders[$f->parent] . htmlentities($f->name) . '/';
				$tounset[] = $k;
			}
		}
		foreach($tounset as $t) {
			unset($folders[$t]);
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
	Destination:<br/>
	<form action="<?php make_url('move', $inode); ?>" method="POST">
		<select name="to">
			<?php foreach ($resolvedfolders as $k => $f) {
				echo '<option value="' . $k . '">' . $f . '</option>';
			} ?>
		</select>
		<input type="submit" value="move" />
	</form>
	<?php endif; ?>
</body>
</html>

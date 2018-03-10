<?php

$db = get_db();
$settings = load_settings($db);
$path = get_path($db, $inode);

$newfile = null;
$isdir = 0;
if (!empty($postvars['newfile'])) {
	$newfile = $postvars['newfile'];
	unsetpost('newfile');
}
if (!empty($postvars['newfolder'])) {
	$isdir = 1;
	$newfile = $postvars['newfolder'];
	unsetpost('newfolder');
}
if ($newfile != null) {
	if (count(simple_select($db, 'SELECT inode FROM files WHERE parent=? AND name=?', array($inode, $newfile))) > 0) {
		$messages[] = 'File/folder with this name already exists.';
	} else {
		simple_execute($db, 'INSERT INTO files(parent,isdir,name) VALUES (?,?,?)', array($inode, $isdir, $newfile));
	}
}

$files = simple_select($db, 'SELECT isdir, name, inode, LENGTH(content) AS size FROM files WHERE parent=? ORDER BY isdir DESC, name ASC', array($inode));

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
	<hr/>
	<table border="0" width="100%">
		<thead>
			<tr><th width="1%">EDIT</th><th width="1%">SIZE</th><th width="auto">NAME</th></tr>
		</thead>
		<tbody>
			<?php if($inode != 0): ?>
			<tr><td></td><td>&lt;DIR&gt;</td><td><a href="<?php make_url('browse', get_parentinode($path)); ?>">..</a></td></tr>
			<?php endif; ?>
			<?php foreach($files as $f) {
				echo '<tr><td>';
				make_link('move', $f->inode, 'M');
				echo ' ';
				make_link('rename', $f->inode, 'R');
				echo ' ';
				make_link('delete', $f->inode, 'D');
				echo ' ';
				echo '</td><td>';
				if ($f->isdir) {
					echo '&lt;DIR&gt;';
					echo '</td><td>';
					make_link('browse', $f->inode, htmlentities($f->name));
				} else {
					if ($f->size > 0) {
						echo str_repeat(' ', 5 - strlen($f->size)) . $f->size;
					}
					echo '</td><td>';
					make_link('edit', $f->inode, htmlentities($f->name));
				}
				echo '</td></tr>';
			} ?>
		</tbody>
	</table>
	<hr/>
	<form action="<?php make_url('browse', $inode); ?>" method="POST">
		<table border="0">
			<tr>
				<td>New folder:</td>
				<td><?php input('type:text,name:newfolder'); ?> <input type="submit" value="Create" /></td>
			</tr>
			<tr>
				<td>New file:</td>
				<td><?php input('type:text,name:newfile'); ?> <input type="submit" value="Create" /></td>
			</tr>
		</table>
	</form>
	<hr/>
	<?php make_link('settings', 0, 'Settings'); ?><br/>
	<?php make_link('passwd', 0, 'Change password'); ?><br/>
	<?php make_link('logout', 0, 'Logout'); ?>
</body>
</html>

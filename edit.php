<?php

$db = get_db();
$path = get_path($db, $inode);

$content = '';
$file = simple_select($db, 'SELECT parent, content FROM files WHERE inode=? AND isdir=0', array($inode));

if (count($file) == 1) {
	$file = $file[0];
	$content = $file->content;
} else {
	$file = null;
	$messages[] = 'File does not exist, or is directory.';
}

if (isset($postvars['content'])) {
	$content = $postvars['content'];
	if ($file != null) {
		simple_execute($db, 'UPDATE files SET content=? WHERE inode=?', array($content, $inode));
	}
}

$content = htmlentities($content);

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
	<form action="<?php make_url('edit', $inode); ?>" method="POST">
		<textarea rows="25" name="content"><?php echo $content; ?></textarea><br/>
		<input type="submit" value="save" />
	</form>
</body>
</html>

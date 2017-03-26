<!doctype HTML>
<html>
<head>
	<title>txt</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body{max-width:50em;margin:auto;font-family:Tahoma,sans-serif;font-size:100%}
		input{width:100%;}
		a,a:visited{color:#00f}
	</style>
</head>
<body>
	<?php printmessages(); ?>
	<form action="./" method="POST">
	<p>
		Password:<br/>
		<input type="password" name="loginpass" />
	</p>
	<p>
		<input type="submit" value="Login" />
	</p>
	<?php
	foreach ($postvars as $k => $v) {
		echo '<input type="hidden" name="';
		echo htmlentities($k);
		echo '" value="';
		echo htmlentities($v);
		echo '"/>';
	}
	?>
	</form>
	<p><a href="<?php echo $URL; ?>">Home</a></p>
</body>
</html>

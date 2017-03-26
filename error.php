<?php
$db = get_db();
$settings = load_settings($db);
?>
<!doctype HTML>
<html>
<head>
	<title>txt</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body{max-width:50em;margin:auto;font-family:<?php echo $settings->fontfamily; ?>,sans-serif;font-size:<?php echo $settings->fontsize; ?>}
		input{width:100%;}
		a,a:visited{color:#00f}<?php echo $settings->customcss; ?>
	</style>
</head>
<body>
	<?php printmessages(); ?>
	<?php if (isset($extrahtml)) echo $extrahtml; ?>
	<p><a href="<?php echo $URL; ?>">Home</a></p>
</body>
</html>

<!doctype HTML>
<html>
<head>
	<title>txt</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body{max-width:50em;margin:auto;font-family:Tahoma,sans-serif;font-size:100%;}
		input{width:100%;}
	</style>
</head>
<body>
	<?php printmessages(); ?>
	<?php if (isset($extrahtml)) echo $extrahtml; ?>
	<p><a href="<?php echo $URL; ?>">Home</a></p>
</body>
</html>

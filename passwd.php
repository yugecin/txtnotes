<?php

$db = get_db();
$settings = load_settings($db);
$path = get_path($db, $inode);

if (!isset($postvars['oldpass']) || !isset($postvars['pass']) || !isset($postvars['passc'])) {
	goto nochange;
}
if ($postvars['pass'] != $postvars['passc']) {
	$messages[] = 'Given passwords do not match! You need to enter your desired password twice to make sure you haven\'t made any typing mistakes.';
	goto nochange;
}
if (file_exists($user . '.pw')) {
	if (!password_verify($postvars['oldpass'], file_get_contents($user . '.pw'))) {
		$messages[] = 'Old password is incorrect';
		goto nochange;
	}
	if (empty($postvars['pass'])) {
		if (unlink($user . '.pw') === false) {
			$messages[] = 'Could not change password (internal error).';
			goto nochange;
		}
		session_destroy();
	}
}

if (!empty($postvars['pass'])) {
	$res = file_put_contents($user . '.pw', password_hash($postvars['pass'], PASSWORD_BCRYPT));
	if ($res === false) {
		$messages[] = 'Error creating password file.';
		goto nochange;
	}
	if (session_id() == '') {
		session_start();
	}
	$_SESSION['user'] = $user;
}

$messages[] = 'Password changed!';

nochange:

unsetpost('oldpass');
unsetpost('pass');
unsetpost('passc');

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
		input{width:100%;}
	</style>
</head>
<body>
	<?php printmessages(); ?>
	<h3><?php show_path($path); ?></h3>
	<form action="<?php make_url('passwd', $inode); ?>" method="POST">
		Old password (leave empty if you didn't have one): <br/>
		<?php input('type:password,name:oldpass'); ?><br/>
		Password (leave empty if you don't want one): <br/>
		<?php input('type:password,name:pass'); ?><br/>
		Confirm password:
		<?php input('type:password,name:passc'); ?><br/>
		<em><small>If you do not supply a password, everyone can visit and edit your files when they know your username.<br/>
		If you use a password, a cookie will be used to determine whether you're logged in.<br/>
		There is no possibility to recover your password.</small></em>
		<input type="submit" value="save" />
	</form>
</body>
</html>

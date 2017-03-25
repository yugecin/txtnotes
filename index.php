<?php
{
	if (!isset($postvars['user']) || !isset($postvars['pass']) || !isset($postvars['passc'])) {
		goto noregister; // bite me
	}
	if ($postvars['pass'] != $postvars['passc']) {
		$messages[] = 'Given passwords do not match! You need to enter your desired password twice to make sure you haven\'t made any typing mistakes.';
		goto noregister;
	}
	$user = $postvars['user'];
	if (!verify_username()) {
		$messages[] = 'Your username does not comply, please check the instructions at the username field.';
		goto noregister;
	}
	if (file_exists($user . '.db')) {
		$messages[] = 'This username is not available.';
		goto noregister;
	}
	try {
		$db = get_db();
		$db->query('CREATE TABLE files(inode INTEGER PRIMARY KEY, parent INTEGER, isdir INTEGER, name TEXT, content TEXT)');

	} catch (PDOException $e) {
		$messages[] = 'Error creating database';
		goto noregister;
	}
	if (!empty($postvars['pass'])) {
		$res = file_put_contents($user . '.pw', password_hash($postvars['pass'], PASSWORD_BCRYPT));
		if ($res === false) {
			$messages[] = 'Error creating password file.';
			$res = unlink($user . '.db');
			if ($res === false) {
				$messages[] = 'Unable to delete database after failure (this is bad).';
			}
			goto noregister;
		}
		session_start();
		$_SESSION['user'] = $user;
	}
	header('Location: ' . $URL . $user . '/');
	die();

} noregister:

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
		body{max-width:50em;margin:auto;font-family:Tahoma,sans-serif;font-size:100%;}
		input{width:100%;}
		a,a:visited{color:#00f}
	</style>
</head>
<body>
	<?php printmessages(); ?>
	<h1>plaintext notes</h1>
	<hr/>
	<h2>Register</h2>
	<form action="./" method="post">
		<p>
			Username: <br/>
			<?php input('type:text,name:user'); ?><br/>
			<small><em>Usernames are case sensitive!<br/>
			Your username determines your realm, you can visit your realm by going to <strong><?php echo $URL; ?>your username</strong><br/>
			Your username can only contain ABCDEFGHIJKLMNOPQRSTUVWXYZ<wbr/>abcdefghijklmnopqrstuvwxyz<wbr/>0123456789<wbr/>_~</em></small>
		</p>
		<p>
			Password (Optional): <br/>
			<?php input('type:password,name:pass'); ?><br/>
			Confirm password:
			<?php input('type:password,name:passc'); ?><br/>
			<em><small>If you do not supply a password, everyone can visit and edit your files when they know your username.<br/>
			If you use a password, a cookie will be used to determine whether you're logged in.<br/>
			There is no possibility to recover your password.</small></em>
		</p>
		<p>
			<input type="submit" value="Register" />
		</p>
	</form>
	<hr/>
	<h2>Already have an account?</h2>
	<p>Visit your realm by going to <strong><?php echo $URL; ?>your username</strong><br/>
	Remember, usernames are case sensitive!</p>
	<hr/>
	<h2>What is this?</h2>
	<p>A place where you can store notes (for example to make todo lists).</p>
</body>
</html>

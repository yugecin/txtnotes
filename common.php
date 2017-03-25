<?php
if (!function_exists('password_hash')) {
	include('password.php'); // see https://github.com/ircmaxell/password_compat/blob/master/lib/password.php
}

$PATH = '/txt/';
$URL = 'http://localhost:8080' . $PATH;

$messages = array();

$postvars = array();

foreach ($_POST as $name => $val) {
	$postvars[$name] = $val;
}

function unsetpost($name) {
	global $postvars;
	unset($postvars[$name]);
}

function input($attributes) {
	global $postvars;
	$attributes = explode(',', $attributes);
	$name = null;
	echo '<input';
	foreach ($attributes as $a) {
		$a = explode(':', $a);
		echo ' ';
		echo $a[0];
		echo '="';
		echo $a[1];
		echo '"';
		if ($a[0] == 'name') {
			$name = $a[1];
		}
	}
	if ($name != null && isset($postvars[$name])) {
		echo ' value="';
		echo htmlentities($postvars[$name]);
		echo '"';
	}
	echo '/>';
}

function printmessages() {
	global $messages;
	if (count($messages) == 0) {
		return;
	}
	echo '<div style="padding:1em;color:#eee;background:#333">';
	foreach($messages as $m) {
		echo '* ' . $m . '<br/>';
	}
	echo '</div>';
}

function verify_username($name) {
	static $phpisnotc = '__________xxxxxxx__________________________xxxxxx__________________________xxx_';
	for ($i = strlen($name); $i > 0;) {
		$pos = ord($name[--$i]) - 48;
		if ($pos < 0 || $pos > 77 || $phpisnotc[$pos] == 'x') {
			return false;
		}
	}
	return true;
}


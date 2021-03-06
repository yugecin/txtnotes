<?php

include 'common.php';

$url = $_SERVER[ 'REQUEST_URI' ];
if (substr($url, -1, 1) !== '/') {
	header('Location: ' . $url . '/');
	die();
}

// Keep in mind that if the user is using proxy server (like PAC),
//   REQUEST_URI will include the full request URL like http://example.com/path/
// so we need to filter that out
if(strlen($url) > 4 && substr($url, 0, 4 ) === 'http') {
	$offset = strpos($url, '/', 2);
	if($offset !== false) {
		$url = substr($url, 0, $offset);
	}
}

while(strpos($url, '../') !== false) $url = str_replace('../', '', $url);

$url = explode('?', $url, 2);
$url = $url[0];
if (strlen($url) >= strlen($PATH)) {
	$url = substr($url, strlen($PATH));
}
if (strlen($url) == 0) {
	include 'index.php';
	die();
}

$url = explode('/', $url);
$user = $url[0];

if (!verify_username() || !file_exists($user . '.db')) {
	$messages[] = 'This user does not exist.';
	include 'index.php';
	die();
}

if (file_exists($user . '.pw')) {
	session_start();
	if (!isset($_SESSION['user']) || $_SESSION['user'] !== $user) {
		session_destroy();
		if (isset($postvars['loginpass']) && password_verify($postvars['loginpass'], file_get_contents($user . '.pw'))) {
			session_start();
			unsetpost('loginpass');
			$_SESSION['user'] = $user;
			goto authenticated;
		}
		unsetpost('loginpass');
		include 'unauthorized.php';
		die();
	}

} authenticated:

$method = 'browse';
if (count($url) > 1 && in_array($url[1], array('logout', 'delete', 'edit', 'move', 'rename', 'settings', 'passwd'))) {
	$method = $url[1];
}

if ($method === 'logout') {
	session_destroy();
	header('Location: ' . $URL);
	die();
}

$inode = 0;

if (count($url) > 2 && is_numeric($url[2])) {
	$inode = $url[2];
	$inode++;
	$inode--; // oh so safe
}

include $method . '.php';


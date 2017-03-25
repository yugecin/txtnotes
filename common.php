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

function verify_username() {
	static $phpisnotc = '__________xxxxxxx__________________________xxxxxx__________________________xxx_';
	global $user;
	for ($i = strlen($user); $i > 0;) {
		$pos = ord($user[--$i]) - 48;
		if ($pos < 0 || $pos > 77 || $phpisnotc[$pos] == 'x') {
			return false;
		}
	}
	return true;
}

function make_url($method, $inode) {
	global $URL, $user;
	echo $URL . $user . '/' . $method . '/' . $inode . '/';
}

function make_link($method, $inode, $text) {
	echo '<a href="';
	make_url($method, $inode);
	echo '">';
	echo $text;
	echo '</a>';
}

function get_db() {
	global $user;
	return new PDO('sqlite:' . $user. '.db');
}

function simple_select($db, $select, $bindings) {
	$s = $db->prepare($select);
	if ($s === false) {
		die(print_r($db->errorinfo(), true));
	}
	if ($s->execute($bindings) === false) {
		die(print_r($db->errorinfo(), true));
	}
	$d = $s->fetchAll(PDO::FETCH_CLASS);
	$s = null;
	return $d;
}

function simple_execute($db, $execute, $bindings) {
	$s = $db->prepare($execute);
	if ($s === false) {
		die(print_r($db->errorinfo(), true));
	}
	if ($s->execute($bindings) === false) {
		die(print_r($db->errorinfo(), true));
	}
	$s = null;
}

function get_path($db, $inode) {
	$path = array();
	if ($inode != 0) {
		$parentinode = $inode;
		$getnode = $db->prepare('SELECT parent, isdir, name FROM files WHERE inode=?');
		while ($parentinode != 0) {
			$getnode->execute(array($parentinode));
			$node = $getnode->fetchAll(PDO::FETCH_CLASS);
			$node = $node[0];
			$node->inode = $parentinode;
			$node->name = htmlentities($node->name);
			array_unshift($path, $node);
			$parentinode = $node->parent;
		}
	}
	return $path;
}

function show_path($path) {
	make_link('browse', 0, 'root');
	echo '/';
	foreach($path as $p) {
		make_link($p->isdir ? 'browse' : 'edit', $p->inode, $p->name);
		if ($p->isdir) {
			echo '/';
		}
	}
}


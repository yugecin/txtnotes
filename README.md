
txt
---
Webbased plaintext notekeeper. Simple. Minimalistic (as in [motherfuckingwebsite.com](http://www.motherfuckingwebsite.com)). Distraction-free (see [time well spent](https://www.timewellspent.io/)).

Created with php. No frameworks, no bullshit, only code. It might be a little mess, but it ain't no beauty contest. It's all about speed here.

### Notes
* Relies on Apache (see `.htaccess` file)
* Uses [PDO](https://php.net/manual/en/intro.pdo.php) with [SQLite](https://sqlite.org), so you probably want to use PHP 5.0+.
* Saves files in running folder, you probably want to give your webserver write access.
* If your php version doesn't support `password_hash` or `password_verify`, add [this password.php file](https://github.com/ircmaxell/password_compat/blob/master/lib/password.php) in the same directory.

### License
[MIT](/LICENSE)


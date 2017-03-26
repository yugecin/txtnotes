
txtnotes
--------
Webbased plaintext notekeeper. Simple. Minimalistic (as in [motherfuckingwebsite.com](http://www.motherfuckingwebsite.com)). Distraction-free (see [time well spent](https://www.timewellspent.io/)).

Created with php. No frameworks, no bullshit, only code. It might be a little mess, but it ain't no beauty contest. It's all about speed here.

### Notes
* Edit the url and path in `common.php` or links will be broken
* Relies on Apache (see `.htaccess` file)
* Uses [PDO](https://php.net/manual/en/intro.pdo.php) with [SQLite](https://sqlite.org), so you probably want to use PHP 5.0+.
* Saves files in running folder, you probably want to give your webserver write access.
* If your php version doesn't support `password_hash` or `password_verify`, add [this password.php file](https://github.com/ircmaxell/password_compat/blob/master/lib/password.php) in the same directory.

### License
[MIT](/LICENSE)

### Screenshots
[home](https://cloud.githubusercontent.com/assets/12662260/24330735/5bf9b23c-1225-11e7-858d-2571d17e779b.png)
[browser](https://cloud.githubusercontent.com/assets/12662260/24330733/538f7942-1225-11e7-93e7-6b61cbf258a5.png)

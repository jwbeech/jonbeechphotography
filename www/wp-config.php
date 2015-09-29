<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'portfiliowordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('WP_MEMORY_LIMIT', '256M');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ' V|agKFbzBV{^?C_s!3JB#Ve@rN1oy--8)K]cJ`4-:UU;E[xD~XmYmx!-57NK%<3');
define('SECURE_AUTH_KEY',  '{t%?|!Oj$z^:}.>8.|m~x>.:y|Z-?r{`yI*INXX`rKA$f&w$|Wlvo9AmRK&i5${.');
define('LOGGED_IN_KEY',    '4$+Hjr|pY)_f,9YF{f`E-CXt8V9Y7w4#)%zlH0%N&m1}o!Okq<|9)_b|wU/Mce,+');
define('NONCE_KEY',        '@6j|Lvbz9h>CpbE x?{QfgtVofnNH|-^pCYvv6R&Ui_{q{AaIpBYtqu+/G!39niC');
define('AUTH_SALT',        'W|ukCTk1oEAB(|!x(KZa59xQPFU#Jn:Nw7$a*-:rC-Q#,_:q+Ow2>agvN+I0 ]6~');
define('SECURE_AUTH_SALT', '&Q_qLD-[~#IfnxcUfaQQl7-,j%L*NvO0rQLPwy/#IqtvvZ9@MW}z{f/fKrbA+AvW');
define('LOGGED_IN_SALT',   'V^5sYys -IA{AOj|UAR} :?bs$d-|/wb}5Qc1(zh9zqv]_9+_bgp9u2#oB^ =iGv');
define('NONCE_SALT',       'j #8E?[U`y>U/+d;ZQ|#]c^OV0+xw_2X0AIyzkowU+y:_-Z_n|iT_9MbQ{n m+Zu');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'jbp101_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

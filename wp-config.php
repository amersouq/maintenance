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
define('DB_NAME', 'mahmouda_wp976');

/** MySQL database username */
define('DB_USER', 'mahmouda_wp976');

/** MySQL database password */
define('DB_PASSWORD', 'D7S5[)p5F4');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'sise6cal2zoxljvmcagz8s9okk987dfmllgpazypekprtwngt6ytbfrmeheo0h6r');
define('SECURE_AUTH_KEY',  'wnn0zfsnidrw0jdxh6mayupamhe3ynfnhsiqip29s5lwtqeodzoidnmykiepgacc');
define('LOGGED_IN_KEY',    'v15vtnhhjddtlbnneyavor6pdp0ugg8y8wxwiwgpbkdukn3palo8aum3hhxyisck');
define('NONCE_KEY',        '0nkvhfdxukzufjqhfofywu7cyxjgxswnnslzyfhptvv1cxdg64knb9pvtghmp8ec');
define('AUTH_SALT',        'wtb4bmwddteavjoxdzcqmdmj2frvaent3tqssny7bk3bvbcbexiijakwdhm1eqth');
define('SECURE_AUTH_SALT', 'tbvnfojgnffdbgfbsprrwk9ckxsicvggoz0dyjlhkxwxuhu8vphu2vserj9hy7ic');
define('LOGGED_IN_SALT',   'rqrthnjsrp4ljdehcavfqm79crdgru4kpgx0xwrn09idrnvvgg9aryftw5xswbk7');
define('NONCE_SALT',       '8fnshmnfje6rpfy9et5mjqx3ki23q1izc5le2dypvwcodbi1bucqwqykhhiw7zeb');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
//define('WP_DEBUG', false);
ini_set('log_errors','On');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define( 'WP_MEMORY_LIMIT', '128M' );
define( 'WP_AUTO_UPDATE_CORE', false );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

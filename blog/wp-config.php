<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'photoblog');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         '1!(mP R}WAtJ+h ycNoZO@6wo&D$|GL/1,6v9rvYHCitNr^39>{c(,1D~a%~5J4<');
define('SECURE_AUTH_KEY',  'I-Lzg&llV2ck,{wHK_nXU|Cv{Y[.3t0^=#*N+T+<s:{fp~8j^q%l#n:kM`>)<u+R');
define('LOGGED_IN_KEY',    '}T,3@4T>ju{rY|oDYDPVN7ZF|%@j$k3Xr kwq|e+3GUwQUQVfD!%+}0CPS-GV+{%');
define('NONCE_KEY',        'X9K/^3<0E~[Tk|!ia*cTha|P;~uJ[B-/XTLaf>jj5A9|U2-4 WS6|598?!c:aBzQ');
define('AUTH_SALT',        'DDXK fLl?alY.E8CEpLP$M8(a;wS>W+bh%,e1{Mxrw>gyt_Tm1yb[zcmeBs}W|jp');
define('SECURE_AUTH_SALT', ')+9~Z_n8t+2aC+T,v>-}z#NdE4u8gBD+Z&Jp1-xxh/LfU`V]rR,!Xu>y=Dn=i?Y9');
define('LOGGED_IN_SALT',   'R^3P@pSV;^+48m9?VKS-BC/$uU3dQE=0P{~c`>y&`WnvtQe-E~lrUo)+90aUCWUH');
define('NONCE_SALT',       '1S#73>,j&~Z.+!UE]x;e]yxJRMMGh*mW4,S|cLA+{9.N4*@_7W{_G?m|;<<@>^&p');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

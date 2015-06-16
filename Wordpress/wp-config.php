<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'lightning');

/** MySQL hostname */
define('DB_HOST', 'groenestraat.cloudapp.net');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'an^QB%RxMA!ryWk-V]@f+B!vr`pR+^v,*>6TEETG)AM^O^f>Q=#|/68#PAg&~M57');
define('SECURE_AUTH_KEY',  '0M)r<77NK<Gh*UEjj6V4{b(.!IrOH]p(De|l5j~|*:VdA}^;zUw-5+VX}DZte*R3');
define('LOGGED_IN_KEY',    'G|$$j;.1S--Q2j7X83p=r)#S-6u6JLz!CZ}rtoC`bNqZ?!F4H5G,BV*W+Ow|B|22');
define('NONCE_KEY',        '{hq{rV[v~T9)@)-i|Hm.+2FOM#)1n++W4{u:.nJ@6s0nn|^AP1fJm=xZ]>xj>bdJ');
define('AUTH_SALT',        'M}ZjwF!0.g8X-=RSoeH+*YMJlkExJV3,/Xu}bA>Xw0{mc-ol$Qv*Y!scan@a#gNQ');
define('SECURE_AUTH_SALT', 'dQid{q=F5<U&!0<+D|Pv%|<ZElcXOQg.l|wg#V!:`d3SL9)$$7RGeZ._$ s[nX|V');
define('LOGGED_IN_SALT',   '-]Q-Yh|=rL<[wm^A4RBTZ._sjLNJ-6grqNq}X.fRd#7?-(7~b@h|.zjb94g-`S>{');
define('NONCE_SALT',       'k4HgDX4S$*sMHERi+uocRd%ubDdyzyV@B~sT~6BKf77j%@llF~;nU^/2l=2U[LV9');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
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
define('DB_NAME', 'presepolymers');

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
define('AUTH_KEY',         '4G-&+)?nbj0BJTAc&W6|kWR|A:MVy vM8d!+Cd=U`_z8V=#:kmX7tER3/q*[dr1|');
define('SECURE_AUTH_KEY',  '(,+2-=85^si7d|GeFx^.[a+{l;0)fc>:HiBG41#YL<1q*t@!n6$%s|e.UXoLUfc]');
define('LOGGED_IN_KEY',    ';cAIg{j)@F~<]3:J@$]+=:3)N6+4s.Bn4G}FD:;~g,;Ro_>EumE_kwo]%97Lqt(+');
define('NONCE_KEY',        ';{(&^#+[SrcU_xW3S/A^`drWBCh#,&|Mj<%ol5YYJ!]^2Gx-ztTcoMPBE=_60=Ql');
define('AUTH_SALT',        'W.(iJt>N~Z.-h@@<Kf@R+`~`b[`(z0sym6y5br8Y}ns{C*O9Af<+*9yT|JR|&JqC');
define('SECURE_AUTH_SALT', 'k6CzP.L@mi+iQHpK-<+d&Jln]wTI6WrxKX!@iS<|G0[UD03DR+n2p+Hrf^ c_>XQ');
define('LOGGED_IN_SALT',   'uy}/*tl1r&ztOc|hs-LrJQ F{.s-S?AH@1=!klX}}78kQ].T)q1F^9Iphq~cwB/M');
define('NONCE_SALT',       'H#jZwsy^r8=oi3E-}fH#x(0vp]9=`a)?4t%L=?v^6xsIiw;^=bST(/3!e21fYsjv');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

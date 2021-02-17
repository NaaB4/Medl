<?php
define('WP_CACHE', true); // WP-Optimize Cache
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG', false);
define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
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
// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_xq2s5' );
/** MySQL database username */
define( 'DB_USER', 'wp_jg0tg' );
/** MySQL database password */
define( 'DB_PASSWORD', '#Us2bQDv45' );
/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
define( 'WP_CACHE_KEY_SALT', 'medl:' );
define( 'WP_REDIS_PORT', 32771 );
define( 'WP_REDIS_PASSWORD', '99Jr3&md' );
/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'gk|B3031l28W/05zY3gRSJ/3gR-I37m;u[4osPpz[uNC+cwQ_u86uh8tx1v7(PKF');
define('SECURE_AUTH_KEY', '15e34X])y6|ugy&55k[-1(d6J4KuRj41/;r@M89N018uV9))IWD@)@W5/e3@K|]4');
define('LOGGED_IN_KEY', '5x83D0[Tn4Q321[Y!6PS70/weui!SE:gD9[Q|q7kz+fnaH8YG5Rlp][0BUcfcmV4');
define('NONCE_KEY', '217~:g#RbC905;IBYg!5wQ20~BLKZ(_471PLvusz4-GH#_zI-U673rx0;6(|3L|:');
define('AUTH_SALT', 'ECV9z4n!@pygBs1LG(2v5+B/~#8]-DkrDR:l(*|[Hw6E0:9~b:ua8u1T03x005u*');
define('SECURE_AUTH_SALT', 'S6VbY*42!N71l|Vcbv7uCF|QVJUdM&o*8mGSMj-*c5xg5+1-)jY:Z~U6+h6z:386');
define('LOGGED_IN_SALT', '(/Z906eZ8fJ4!]avAKWXK]K~7Wd7LF:5SQY+)5tZ&qx%81bgCe@_uf/#!~Z@K%%S');
define('NONCE_SALT', 'om9vfXKONAOYX[d|G50Jis82p77&)Zc@(7@7m&+A[#V2|(~&R1#6Y6uJ;ARk~RF7');
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'TgB5B_';
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'medl.dev.qmarketing.de');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
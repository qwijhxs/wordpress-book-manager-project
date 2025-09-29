<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'nLxf$@#*29c)J|9 goeWy:SEl<X_c3FE3`@[oiON)^/T$x]|v#BG0Uc=(M>o*rmO' );
define( 'SECURE_AUTH_KEY',   '&oB`(8(<&w_]~xZssmfHJFS=!F |3!MNBojI2m!r<|(+}=inM|uPQuW9Ono>Hk u' );
define( 'LOGGED_IN_KEY',     '&GlK~ T# meBhtQrh&B#XT!z3##`83|UD%]e*~qy~dgUruY`W[`6r2jR_|sQ`9pV' );
define( 'NONCE_KEY',         'x1qH.ne9uht{QD1ko()yr2)}8zYcER5l[>#-/ev}bqOi_ey.#-p[!Uff}9:_#B#r' );
define( 'AUTH_SALT',         'hL_dUHl)*RA$s5+)66k)<oD&l1Mhg7L_JO7&o?0[NkG&@:g_uN`ZbbLBH7z[Jul/' );
define( 'SECURE_AUTH_SALT',  'h)#UgA(?Ys.P2d)6Jy&[&RiMG>4E4wPH7Y_~Jp}H[:-1RbBrpEkmy V5NqpP6#DZ' );
define( 'LOGGED_IN_SALT',    '-%cLGAmt)`JH*L(jt ]cp?M1|NSGT0UYS2,S1h0C8oTl{Ls;+2r)4M<@XAGsnP,`' );
define( 'NONCE_SALT',        '%.RHI1w-5(tVAA`T7&}sOtO;VDK#Tj8*HRs^6XU{0eU[m,,b^Gclf_l^`Ji*b%|m' );
define( 'WP_CACHE_KEY_SALT', 'u6KNP,2+Z@}fh5SyB`mM2HZTe,?}b=ra,<K6-8iC*+:m23gE!Rr}V0$QtTFjbVPD' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

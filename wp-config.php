<?php
define('WP_CACHE', true); // WP-Optimize Cache
/**
 * Grundeinstellungen für WordPress
 *
 * Zu diesen Einstellungen gehören:
 *
 * * MySQL-Zugangsdaten,
 * * Tabellenpräfix,
 * * Sicherheitsschlüssel
 * * und ABSPATH.
 *
 * Mehr Informationen zur wp-config.php gibt es auf der
 * {@link https://codex.wordpress.org/Editing_wp-config.php wp-config.php editieren}
 * Seite im Codex. Die Zugangsdaten für die MySQL-Datenbank
 * bekommst du von deinem Webhoster.
 *
 * Diese Datei wird zur Erstellung der wp-config.php verwendet.
 * Du musst aber dafür nicht das Installationsskript verwenden.
 * Stattdessen kannst du auch diese Datei als wp-config.php mit
 * deinen Zugangsdaten für die Datenbank abspeichern.
 *
 * @package WordPress
 */
// ** MySQL-Einstellungen ** //
/**   Diese Zugangsdaten bekommst du von deinem Webhoster. **/
/**
 * Ersetze datenbankname_hier_einfuegen
 * mit dem Namen der Datenbank, die du verwenden möchtest.
 */
define( 'DB_NAME', 'medl' );
/**
 * Ersetze benutzername_hier_einfuegen
 * mit deinem MySQL-Datenbank-Benutzernamen.
 */
define( 'DB_USER', 'root' );
/**
 * Ersetze passwort_hier_einfuegen mit deinem MySQL-Passwort.
 */
define( 'DB_PASSWORD', '' );
/**
 * Ersetze localhost mit der MySQL-Serveradresse.
 */
define( 'DB_HOST', 'localhost' );
/**
 * Der Datenbankzeichensatz, der beim Erstellen der
 * Datenbanktabellen verwendet werden soll
 */
define( 'DB_CHARSET', 'utf8mb4' );
/**
 * Der Collate-Type sollte nicht geändert werden.
 */
define('DB_COLLATE', '');
/**#@+
 * Sicherheitsschlüssel
 *
 * Ändere jeden untenstehenden Platzhaltertext in eine beliebige,
 * möglichst einmalig genutzte Zeichenkette.
 * Auf der Seite {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * kannst du dir alle Schlüssel generieren lassen.
 * Du kannst die Schlüssel jederzeit wieder ändern, alle angemeldeten
 * Benutzer müssen sich danach erneut anmelden.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '5xIs(=<.cXaoDI|6!Fx2nRp=.I*u&jxb)-MU7DL_#oxz8_A1?2ijovO]=L3A=^h`' );
define( 'SECURE_AUTH_KEY',  'Z:#K>[hqU88zk&I5$;jGC*+6oq:U~W@ 7ExAVc<]JyKGe@2FjD*^AN#s gK~72Cz' );
define( 'LOGGED_IN_KEY',    '#EO!m!+qeHZ+ejxcX=0-g_h{4xICbnu<3GK!b/M%1:;3w#I!mco*=<*I<%_+},},' );
define( 'NONCE_KEY',        '7E-eEsUjgMrg27&*q+2Bhjk!<}2iT>V|k5L+Vi~Ipi5 `Mg2mb9G&u&T.xJ$^`o=' );
define( 'AUTH_SALT',        '^j0v[u6qhhAda^Nrg)Q+Vi}5<+wOiq!qWKgEdm0G*O(,*k=r/Lhr~=s2IB&}14NV' );
define( 'SECURE_AUTH_SALT', 'Zb.c_An%<ru#?Y8[j=l5&sl8*mZx4Bo`1Fa`!7*U,T}*y+<5T~2h#BS-N3Z>5S]/' );
define( 'LOGGED_IN_SALT',   'i4p$%Gt~n5)C3pu:ltd~J8R;6Gy@B!jE,tG2 #u-b]x&[:nKk*L_!:sW+HC31U:;' );
define( 'NONCE_SALT',       '9fi)!g+?k;?I5My.NNO+MT31!iIzwcZjm;9kt9OMLz8 8,RZChR#}ix>;*s=lvo7' );
/**#@-*/
/**
 * WordPress Datenbanktabellen-Präfix
 *
 * Wenn du verschiedene Präfixe benutzt, kannst du innerhalb einer Datenbank
 * verschiedene WordPress-Installationen betreiben.
 * Bitte verwende nur Zahlen, Buchstaben und Unterstriche!
 */
$table_prefix = 'wp_';
/**
 * Für Entwickler: Der WordPress-Debug-Modus.
 *
 * Setze den Wert auf „true“, um bei der Entwicklung Warnungen und Fehler-Meldungen angezeigt zu bekommen.
 * Plugin- und Theme-Entwicklern wird nachdrücklich empfohlen, WP_DEBUG
 * in ihrer Entwicklungsumgebung zu verwenden.
 *
 * Besuche den Codex, um mehr Informationen über andere Konstanten zu finden,
 * die zum Debuggen genutzt werden können.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_ALLOW_MULTISITE', true );
/* Das war’s, Schluss mit dem Bearbeiten! Viel Spaß. */
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'medl.de');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
define( 'WP_REDIS_HOST', '35.157.122.173' );
define( 'WP_REDIS_PASSWORD', '9YWtOoe2q4EI' );
/* That's all, stop editing! Happy publishing. */
/** Der absolute Pfad zum WordPress-Verzeichnis. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Definiert WordPress-Variablen und fügt Dateien ein.  */
require_once( ABSPATH . 'wp-settings.php' );
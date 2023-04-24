<?php
/**
 * Baskonfiguration för WordPress.
 *
 * Denna fil används av wp-config.php-genereringsskript under installationen.
 * Du behöver inte använda webbplatsens installationsrutin, utan kan kopiera
 * denna fil direkt till "wp-config.php" och fylla i alla värden.
 *
 * Denna fil innehåller följande konfigurationer:
 *
 * * Inställningar för databas
 * * Säkerhetsnycklar
 * * Tabellprefix för databas
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Databasinställningar - åtkomstuppgifter för databasen får du från ditt webbhotell ** //
/** Namnet på databasen du vill använda för WordPress */
define( 'DB_NAME', 'grupprojekt' );

/** Databasens användarnamn */
define( 'DB_USER', 'grupprojekt' );

/** Databasens lösenord */
define( 'DB_PASSWORD', 'grupprojekt' );

/** Databasserver */
define( 'DB_HOST', 'localhost' );

/** Teckenkodning för tabellerna i databasen. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kollationeringstyp för databasen. Ändra inte om du är osäker. */
define('DB_COLLATE', '');

/**#@+
 * Unika autentiseringsnycklar och salter.
 *
 * Ändra dessa till unika fraser!
 * Du kan generera nycklar med {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Du kan när som helst ändra dessa nycklar för att göra aktiva cookies obrukbara, vilket tvingar alla användare att logga in på nytt.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'nr]uC_mKAGcGq*oI1(OXG/-;D,H<=%l BhlFzZZS-`bdP$aPRMJ||`Ec%+@+3ncd' );
define( 'SECURE_AUTH_KEY',  'Q)rJstQJxi]LoxjFf,W!kx~J<2/T?k+KFCsZp3X8!nOS=[R4&S]1Q 9UN<w?{va)' );
define( 'LOGGED_IN_KEY',    '+AgT<1%3myoMOFUkR>t.~)Okq&8P{;T`5?#CN&qwz+l=s2G+@9VJDjZ.NUh)C zL' );
define( 'NONCE_KEY',        '+s;OoQ?v5PET5mdarZDWZ_r&X;^v>Rygl##,G.*app&+5kPU+^N5|)=I8V]sc9e!' );
define( 'AUTH_SALT',        'a$JKMW!1^8TWi}h)dei#cgjD#me(.eyZq=+ZA4_i=Vfr2N`z;$K;dB.YaOI)7q%h' );
define( 'SECURE_AUTH_SALT', 'yTPd0>*FI{.a5ir%H^n.%U2MBRThfyf{VK_)zuWfga1qNwY9#6bw:n9r*@%%Q8jX' );
define( 'LOGGED_IN_SALT',   '?Z;#3!Cj.4oHZ,OVqt2?+~/pp^-BY4l}wCn:[lK~79dQy-vm^e= >22@;3bq%LI(' );
define( 'NONCE_SALT',       't]:1rzB|prgYHmiUZv!kVCv/6Y7n6EJ!mZgEsSWGDuE:X5@vv4;Q~@&zY0TdN}-O' );

/**#@-*/

/**
 * Tabellprefix för WordPress-databasen.
 *
 * Du kan ha flera installationer i samma databas om du ger varje installation ett unikt
 * prefix. Använd endast siffror, bokstäver och understreck!
 */
$table_prefix = 'wp_';

/** 
 * För utvecklare: WordPress felsökningsläge. 
 * 
 * Ändra detta till true för att aktivera meddelanden under utveckling. 
 * Det rekommenderas att man som tilläggsskapare och temaskapare använder WP_DEBUG 
 * i sin utvecklingsmiljö. 
 *
 * För information om andra konstanter som kan användas för felsökning, 
 * se dokumentationen. 
 * 
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */ 
define('WP_DEBUG', false);
/* Lägg in eventuella anpassade värden mellan denna rad och raden med "sluta redigera här". */




/* Det var allt, sluta redigera här och börja publicera! */

/** Absolut sökväg till WordPress-katalogen. */
if ( !defined('ABSPATH') )
	define('ABSPATH', __DIR__ . '/');

/** Anger WordPress-värden och inkluderade filer. */
require_once(ABSPATH . 'wp-settings.php');
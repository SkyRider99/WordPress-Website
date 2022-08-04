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
* * Inställningar för MySQL
* * Säkerhetsnycklar
* * Tabellprefix för databas
* * ABSPATH
*
* @link https://wordpress.org/support/article/editing-wp-config-php/
*
* @package WordPress
*/
// ** MySQL-inställningar - MySQL-uppgifter får du från ditt webbhotell ** //
/** Namnet på databasen du vill använda för WordPress */
define( 'DB_NAME', 'hael0008' );
/** MySQL-databasens användarnamn */
define( 'DB_USER', 'hael0008' );
/** MySQL-databasens lösenord */
define( 'DB_PASSWORD', 'Blackfox21!' );
/** MySQL-server */
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
define( 'AUTH_KEY',         'f&v&32O{yV_574J}l!DDxr%m2p!ttl|kOmRSGm]!GY5Q?+BoBmybo?jCV4WvSXcI' );
define( 'SECURE_AUTH_KEY',  'btd**P?MGIpsjx;D7|>/NNZ9)bxKal3P4krVii{Z[pt=!&vU0fISNMa2Txf`KX.4' );
define( 'LOGGED_IN_KEY',    'UuoEPgw-|_^Et1,S%R/|hT+0F>-z=/ V==!Y[hb9>tS0_+/h]z*8B<QZ%2,}F||2' );
define( 'NONCE_KEY',        '_/X(Rv7_]Ye6J+}l>/3r$d:i~|+&xGDW>f6lCi|m4#?I^K~SR?alF6xGN(dW:r$L' );
define( 'AUTH_SALT',        'x/V^jb3Y=#Wr[Uj*+YChNWLxhQL~Dlmnog+i_7yC9{en MiI}CS+N.8L+O~NNO^Z' );
define( 'SECURE_AUTH_SALT', 'C3.9qG*h9m#]y;r_o!y!m,h>IjKlj!xr9nti<Evo6P;j3}Hlk=Fj>OdG)<7ZKLIZ' );
define( 'LOGGED_IN_SALT',   '6GMi4EN~()^z?; 0M%7^dz1S1!KDqkUhkm=psx+EI)]F3 +aE[y*d!I)&&Lc;|Oy' );
define( 'NONCE_SALT',       '~aC_]$&YY[V(V6?PC}@$}]!!&Uy.4VDBR>%3qkEE.EfBFH2VcPXz_8!d/Ywu]xYv' );
/**#@-*/
/**
* Tabellprefix för WordPress-databasen.
*
* Du kan ha flera installationer i samma databas om du ger varje installation ett unikt
* prefix. Använd endast siffror, bokstäver och understreck!
*/
$table_prefix = 'hassan_';
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
* @link https://codex.wordpress.org/Debugging_in_WordPress
*/ 
define('WP_DEBUG', false);
/* Lägg in eventuella anpassade värden mellan denna rad och raden med "sluta redigera här". */
/* Det var allt, sluta redigera här och börja publicera! */
/** Absolut sökväg till WordPress-katalogen. */
if ( !defined('ABSPATH') )
define('ABSPATH', __DIR__ . '/');
/** Anger WordPress-värden och inkluderade filer. */
require_once(ABSPATH . 'wp-settings.php');
<?php
/* Plugin Name: 1-Hassan Insert Footnote
 * Plugin URI: http://example.com/plugin/1_hassan_insert_footnote
 * Description: Infogar en liten textsnutt i slutet på alla poster
 * Version: 1.0
 * Author: Hassan El Alaoui
 * Author URI: http://example.com/
 * License: GNU GPL v2 or later
*/

//Lägger till filter som filtrerar content med funktionen 
add_filter ('the_content', 'hassan_insert_footnote');

function hassan_insert_footnote($content){
    if (!is_feed() && !is_home()) {
        $content .= "<div class='hassan_insert_footnote'> \n";
        $content .= "<h4>Gillade du artikeln?</h4> \n";
        $content .= "<p>Prenumerera på <a href='http://example.com/rss'>min RSS</p> \n";
        $content .= "</div> \n\n";  
    }
    return $content; 
}
?>
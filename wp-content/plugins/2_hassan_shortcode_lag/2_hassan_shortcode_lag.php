<?php
    /*
        * Plugin Name: 2-Hassan Shortcode Lag
        * Plugin URI: http//example.com/plugins/2_hassan_shortcode_lag
        * Description: Infogar automatiskt en länk till ett bra lag genom att skriva [lag]
        * Version: 1.0
        * Author: Hassan El Alaoui
        * Author URI: http//example.com
        * License: GNU GPL v2 or later
    */

    add_shortcode('lag', 'hassan_shortcode_lag');

    function hassan_shortcode_lag(){
        return '<a href="http://www.hv71.se">Heja på rätt lag</a>';
    }
?>
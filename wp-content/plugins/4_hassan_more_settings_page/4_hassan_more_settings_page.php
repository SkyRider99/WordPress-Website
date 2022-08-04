<?php
    /*
        * Plugin Name: 4-Hassan More Settings Page
        * Plugin URI: http//example.com/plugins/4_hassan_more_settings_page
        * Description: Lägger till et nytt menyalternativ i Dashboard
        * Version: 1.0
        * Author: Hassan El Alaoui
        * Author URI: http//example.com
        * License: GNU GPL v2 or later
    */

    add_action('admin_menu', 'hassan_more_settings');

    function hassan_more_settings(){
        //Skapar en ny undersida under settings
        add_options_page('Hassa more settings page', 'Hassan more settings', 'manage_options', 'hassan_more_settings','hassan_more_settings_page');
    }

    function hassan_more_settings_page(){ ?>
        <h1>Hassan More Settings Page</h1>
        <p>Här kan du sätta en massa olika options exempelvis till tema som har skapats</p>
   <?php } ?>




<?php
    /*
        * Plugin Name: 3-Hassan Dashboard Menu
        * Plugin URI: http//example.com/plugins/3_hassan_dashboard_menu
        * Description: Lägger till et nytt menyalternativ i Dashboard
        * Version: 1.0
        * Author: Hassan El Alaoui
        * Author URI: http//example.com
        * License: GNU GPL v2 or later
    */

    //Skapa en meny i WordPress
    add_action('admin_menu', 'hassan_dashboard_menu');

    function hassan_dashboard_menu() {
        //Skapa en top-level-meny
        add_menu_page('Mina Inställningar', 'Mitt Plugin', 'manage_options', 'hassan_dashboard_menu', 'hassan_dashboard_menu_settings_page');

        //Skapa undermenyer till denna
        add_submenu_page('hassan_dashboard_menu', 'Om mitt plugin', 'Om', 'manage_options', 'hassan_dashboard_menu_about','hassan_dashboard_menu_about_page');

    }


    function hassan_dashboard_menu_about_page(){
        echo "HEJ the ART of War is intressting to read!";
    }

?>
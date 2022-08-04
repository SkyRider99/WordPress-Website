<?php
/*
 * Define me some sidebars
 * ***************************************************** */

 if(function_exists('register_sidebar')){
    register_sidebar (array('name' => 'Left Sidebar', 
    'id' => 'sidebar-left',
    'description' => 'Left sidebar for use on content pages',
    'before_widget' => '<section class="widget-container">',
    'after_widget' => '</section> <!-- /widget-container -->',
    'before_title' => '<div class="widget-title"><h3>',
    'after_title' => '</h3> </div> <!-- /widget-title -->'
    ));
    register_sidebar (array('name' => 'Right Sidebar', 
        'id' => 'sidebar-right',
        'description' => 'Right sidebar for use on content pages',
        'before_widget' => '<section class="widget-container">',
        'after_widget' => '</section> <!-- /widget-container -->',
        'before_title' => '<div class="widget-title"><h3>',
        'after_title' => '</h3> </div> <!-- /widget-title -->'
    ));
    register_sidebar (array('name' => 'Footer Left', 
        'id' => 'footer-left',
        'description' => 'Footer widget to the left',
        'before_widget' => '<section class="footer-container">',
        'after_widget' => '</section> <!-- /footer-container -->',
        'before_title' => '<div class="footer-title"><h3>',
        'after_title' => '</h3> </div> <!-- /footer-title -->'
    ));
    register_sidebar (array('name' => 'Footer Middle', 
        'id' => 'footer-middle',
        'description' => 'Footer widget in the Middle',
        'before_widget' => '<section class="footer-container">',
        'after_widget' => '</section> <!-- /footer-container -->',
        'before_title' => '<div class="footer-title"><h3>',
        'after_title' => '</h3> </div> <!-- /footer-title -->'
    ));
    register_sidebar (array('name' => 'Footer Right', 
        'id' => 'footer-right',
        'description' => 'Footer widget to the right',
        'before_widget' => '<section class="footer-container">',
        'after_widget' => '</section> <!-- /footer-container -->',
        'before_title' => '<div class="footer-title"><h3>',
        'after_title' => '</h3> </div> <!-- /footer-title -->'
    ));



 }


?>
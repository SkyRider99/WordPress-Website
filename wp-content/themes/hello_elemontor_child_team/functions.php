<?php
function child_theme_styles() {
    /* Länka först in förälder-temats stilmall */
    wp_enqueue_style( 'hello-elementor', get_template_directory_uri() . '/style.css' );

    /* Och sedan länka in child-temats stilmall */
    wp_enqueue_style( 'hello_elemontor_child_team', get_stylesheet_directory_uri() . '/style.css');

    /* Länka in en font */
    /*wp_enqueue_style('Ubuntu Mono', 'https://fonts.googleapis.com/css2?family=Ubuntu+Mono&display=swap', array(), '1.0');*/
}
/* Kör funktionen för att hämta in stilarna */
add_action( 'wp_enqueue_scripts', 'child_theme_styles' );


function child_theme_fonts() {
    wp_enqueue_style('Ubuntu Mono', 'https://fonts.googleapis.com/css2?family=Ubuntu+Mono&display=swap', array(), '1.0');
}
add_action( 'wp_enqueue_scripts', 'child_theme_fonts' );


if (function_exists('register_sidebar')) {
    register_sidebar(array('name' => 'Footer Left',
    'id' => 'footer-left',
    'description' => 'Footer widget to the left',
    'before_widget' => '<section class="footer-container">',
    'after_widget' => '</section> <!-- /footer-container -->',
    'before_title' => '<div class="footer-title"><h3>',
    'after_title' => '</h3></div> <!-- /footer-title -->'
    ));

    register_sidebar(array('name' => 'Footer Middle',
    'id' => 'footer-middle',
    'description' => 'Footer widget to the middle',
    'before_widget' => '<section class="footer-container">',
    'after_widget' => '</section> <!-- /footer-container -->',
    'before_title' => '<div class="footer-title"><h3>',
    'after_title' => '</h3></div> <!-- /footer-title -->'
    ));

    register_sidebar(array('name' => 'Footer Right',
    'id' => 'footer-right',
    'description' => 'Footer widget to the right',
    'before_widget' => '<section class="footer-container">',
    'after_widget' => '</section> <!-- /footer-container -->',
    'before_title' => '<div class="footer-title"><h3>',
    'after_title' => '</h3></div> <!-- /footer-title -->'
    ));
}

?>
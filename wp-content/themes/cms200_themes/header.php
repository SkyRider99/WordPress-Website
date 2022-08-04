<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset') ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
    <link href="https://fonts.googleapis.com/css?family=Lora|Lato&display=swap" rel="stylesheet" />
    <title>
        <?php
            echo get_bloginfo('name');
            if(!is_front_page() || !is_home()){
                echo wp_title('>');
            }
        ?>
    </title>
    <?php wp_head(); ?>
</head>
<body>
    <div class="container">
        <header class="main">
            <div class="logo">
                <a href="index.html" rel="home">
                    <img src="<?php echo get_template_directory_uri() . '/images/logo.png' ?>" alt="Logo" />
                </a>
            </div> <!-- /logo -->
            <nav class="main">
                <?php wp_nav_menu(); ?>
            </nav> <!-- /navigation -->
        </header> <!-- /header.main -->
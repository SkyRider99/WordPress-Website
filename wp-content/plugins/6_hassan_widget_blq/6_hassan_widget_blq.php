<?php
    /*
        * Plugin Name: 6-Hassan Widget Black List quotes
        * Plugin URI: http//example.com/plugins/6_hassan_widget_blq
        * Description: Skriver ut ett random quote från Black List serien
        * Version: 1.0
        * Author: Hassan El Alaoui
        * Author URI: http//example.com
        * License: GNU GPL v2 or later
    */

    add_action('widget_init', function(){register_widget('Black_List_Quotes'); });

    class Black_List_Quotes extends WP_Widget {
        //Konstruktorn
        public function __construct() {
            parent::WP_Widget(false,'Hassan Black List Quotes', 'description=Picks a random quote from Black List Universe');
        }

        //Uppdatera widgeten
        function update($new_instance, $old_instance) {
            return $new_instance;
        }

        //Hanterar hur dashboard-formuläret ser ut
        function form($instance) {
            $title = esc_attr($instance['title']);
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">
                    Title: <input class="widefat"
                    id="<?php echo $this->get_field_id('title'); ?>"
                    name="<?php echo $this->get_field_name('title'); ?>"
                    type="text"
                    value="<?php echo $title; ?>" />
                </label>
            </p>
            <?php 
        }

        //Hantera själva widgt på skärm
        function widget($args, $instance){
            extract($args);
            $HBLQ_IMG_URL = plugins_url("images/", __FILE__);
            $HBLQ_QUOTES_FILE = plugin_dir_path(__FILE__) . "quotes.txt";

            //Öppna filen med citat
            $quotesFile = fopen($HBLQ_QUOTES_FILE, "r") or die("Kunde inte öppna filen");
            while(!feof($quotesFile)){
                $quotes .= fread($quotesFile, 1024);
            }

            //splitta strängen till en array. Varje rad blir en ny cell
            $quotes = explode("\n", $quotes);

            //Slumpar en rad ur arrayen med citat
            $chosen = wptexturize($quotes[mt_rand(0, count($quotes)-1)]);

            // Splitta raden på :
            list($name, $quote) = explode(":", $chosen);
            $name = stratolower($name);

            echo $before_widget;
            echo $before_title . $instance['title'] . $after_title;
            echo "<div class='hblq-img'> <img src='$HBLQ_IMG_URL$name.jpg'>";
            echo "<div class='hblq-quote'> $quote </div>";
            echo $after_widget;
        }
    }

?>
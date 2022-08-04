<?php 
/*
 * Plugin Name: Weather Widget 
 * Plugin URI: http://example.com/weather_widget/ 
 * Description: En widget som visar som anropar vädret från Weatherstack API
 * Version: 1.0
 * Author: Hassan El Alaoui
 * Author URI: http://example.com
 * License: GPLv2
 */

add_action( 'widgets_init', function() {
    register_widget( 'Weather_Widget' ); 
});

class Weather_Widget extends WP_Widget {
    public function __construct() {
        parent::WP_Widget(false,'Weather Widget','description=Anropar vädret från Watherstack API.');
        wp_enqueue_style( 'Widget_Base_CSS', plugins_url( '/widget-base-style.css', __FILE__ ) );
    }

    public function widget($args, $instance) {
        // Hämta in alla argument
		extract ($args);
        
        $chosenCity = $instance['chosenCity'];
        $url = $url = "http://api.weatherstack.com/current?access_key=f77e43a89bf634e88c875bf03348764c&query=$chosenCity";
        $contents = file_get_contents($url);
        $contents = json_decode($contents, true);
        
        // Kollar om staden man har skrivit inte finns
        if (!$contents) {
            echo $before_widget;
            echo $before_title . $instance['title'] . $after_title;
            echo '<div class="hassan-weather-widget">' .
                '<br>Error. Staden hittades inte!' .
                '</div>';
            echo $after_widget;
        }
        else {
            // Hämtar in namnet på staden
            $stad = $contents['location']['name'];

            //Övergripande väder
            $weather_desc = ucfirst($contents['current']['weather_descriptions'][0]);

            //Fixar till tempraturen
            $tempraturen = floatval($contents['current']['temperature']);
            $tempraturen = number_format($tempraturen, 1);

            //Fixar till fuktighet 
            $fuktighet = floatval($contents['current']['humidity']);
            $fuktighet = number_format($fuktighet, 1);

            //Lokal tid
            $lokal = intval($contents['location']['localtime_epoch']);
            $lokal = gmdate("H:i Y-m-d", $lokal);

            //Bild ikon
            $ikonBild = $contents['current']['weather_icons'][0];
            $ikonBild = "https://assets.weatherstack.com/images/wsymbols01_png_64/wsymbol_0008_clear_sky_night.png";

            ?>
            <div class="hassan-weather-widget">
                <div class="title"><?php echo $stad; ?></div>
                <img class="bild" src=<?php echo $ikonBild; ?> />
                <div class="desc"><?php echo $weather_desc; ?></div>
                <div class="grader"><?php echo $tempraturen; ?>&#176;</div>
                <div class ="ovrigt">
                    <p>Fuktigheten: <?php echo $fuktighet; ?></p>
                    <p>Lokala Tiden: <?php echo $lokal; ?></p>
                </div>
            </div>
            <?php
        }
    }

    public function update ($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
        $instance['chosenCity'] = isset( $new_instance['chosenCity'] ) ? wp_strip_all_tags( $new_instance['chosenCity'] ) : '';
        return $instance;
    }

    public function form ($instance) {
        // Hämtar ut de delar vi vill visa upp i widget options
		$title = esc_attr ($instance['title']);
        $chosenCity = esc_attr ($instance['chosenCity']);
		?>
		<p>
            <label 
                for="<?php echo $this->get_field_id('chosenCity'); ?>">
                Staden du vill ha vädret för: <input class="widefat" 
                id="<?php echo $this->get_field_id('chosenCity'); ?>" 
                name="<?php echo $this->get_field_name('chosenCity'); ?>" 
                type="text" 
                value="<?php echo $chosenCity; ?>" />
            </label>
		</p>
        <p>
            <label 
                for="<?php echo $this->get_field_id('title'); ?>">
                Titeln du vill ha: <input class="widefat" 
                id="<?php echo $this->get_field_id('title'); ?>" 
                name="<?php echo $this->get_field_name('title'); ?>" 
                type="text" 
                value="<?php echo $title; ?>" />
            </label>
		</p>
		<?php
    }
}
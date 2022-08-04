<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
	if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
		get_template_part( 'template-parts/dynamic-footer' );
	} else {
		get_template_part( 'template-parts/footer' );
	}
}
?>

<footer class="main">
	<div class="foot-left"><?php dynamic_sidebar('footer-left') ?></div>

	<div class="foot-middle"><?php dynamic_sidebar('footer-middle') ?></div>

	<div class="foot-right"><?php dynamic_sidebar('footer-right') ?></div>
</footer>


<?php wp_footer(); ?>


</body>
</html> 

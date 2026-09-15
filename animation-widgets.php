<?php
/**
 * Plugin Name: Animation Widgets
 * Plugin URI:  https://github.com/javihorus/animation-widgets
 * Description: Widgets de animación para Elementor: scroll, marquesina, ruleta y carruseles configurables.
 * Version: 1.15.2
 * Author: Javi Horus
 * Text Domain: animation-widgets
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Requires Plugins: elementor
 * Elementor tested up to: 3.24
 * GitHub Plugin URI: https://github.com/javihorus/animation-widgets
 * Primary Branch:    main
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ANIMATION_WIDGETS_VERSION', '1.15.2' );
define( 'ANIMATION_WIDGETS_FILE', __FILE__ );
define( 'ANIMATION_WIDGETS_PATH', plugin_dir_path( __FILE__ ) );
define( 'ANIMATION_WIDGETS_URL', plugin_dir_url( __FILE__ ) );

define( 'AW_VERSION', ANIMATION_WIDGETS_VERSION );
define( 'AW_DIR', ANIMATION_WIDGETS_PATH );
define( 'AW_URL', ANIMATION_WIDGETS_URL );

function animation_widgets_bootstrap() {
	load_plugin_textdomain( 'animation-widgets', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'animation_widgets_missing_elementor_notice' );
		return;
	}

	if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.20.0', '<' ) ) {
		add_action( 'admin_notices', 'animation_widgets_old_elementor_notice' );
		return;
	}

	add_action( 'elementor/elements/categories_registered', 'animation_widgets_register_category' );
	add_action( 'elementor/widgets/register', 'animation_widgets_register_widgets' );
	add_action( 'wp_enqueue_scripts', 'animation_widgets_register_assets' );

	// Scroll Fill extension for native Elementor Heading/Text Editor widgets.
	require_once ANIMATION_WIDGETS_PATH . 'includes/class-scroll-fill-extension.php';
	\Animation_Widgets\Scroll_Fill_Extension::register();
}
add_action( 'plugins_loaded', 'animation_widgets_bootstrap' );

function animation_widgets_register_category( $elements_manager ) {
	$elements_manager->add_category(
		'animation-widgets',
		array(
			'title' => esc_html__( 'Animation Widgets', 'animation-widgets' ),
			'icon'  => 'eicon-animation',
		)
	);
}

function animation_widgets_register_assets() {
	wp_register_style(
		'animation-widgets-frontend',
		ANIMATION_WIDGETS_URL . 'assets/css/animation-widgets.css',
		array(),
		ANIMATION_WIDGETS_VERSION
	);

	wp_register_script(
		'animation-widgets-frontend',
		ANIMATION_WIDGETS_URL . 'assets/js/animation-widgets.js',
		array( 'elementor-frontend' ),
		ANIMATION_WIDGETS_VERSION,
		true
	);
}

function animation_widgets_register_widgets( $widgets_manager ) {
	require_once ANIMATION_WIDGETS_PATH . 'widgets/widget-sticky-scroll.php';
	require_once ANIMATION_WIDGETS_PATH . 'widgets/widget-marquee-hero.php';
	require_once ANIMATION_WIDGETS_PATH . 'includes/widgets/class-interactive-wheel.php';
	require_once ANIMATION_WIDGETS_PATH . 'includes/widgets/class-scroll-gallery.php';
	require_once ANIMATION_WIDGETS_PATH . 'includes/widgets/class-scroll-fill.php';
	require_once ANIMATION_WIDGETS_PATH . 'includes/widgets/class-testimonial-carousel.php';
	require_once ANIMATION_WIDGETS_PATH . 'includes/widgets/class-programs-carousel.php';

	$widgets_manager->register( new \AW_Widget_Sticky_Scroll() );
	$widgets_manager->register( new \AW_Widget_Marquee_Hero() );
	$widgets_manager->register( new \Animation_Widgets\Widgets\Interactive_Wheel() );
	$widgets_manager->register( new \Animation_Widgets\Widgets\Scroll_Gallery() );
	$widgets_manager->register( new \Animation_Widgets\Widgets\Scroll_Fill() );
	$widgets_manager->register( new \Animation_Widgets\Widgets\Testimonial_Carousel() );
	$widgets_manager->register( new \Animation_Widgets\Widgets\Programs_Carousel() );
}

function animation_widgets_missing_elementor_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	?>
	<div class="notice notice-warning is-dismissible">
		<p><?php echo esc_html__( 'Animation Widgets necesita que Elementor esté instalado y activo.', 'animation-widgets' ); ?></p>
	</div>
	<?php
}

function animation_widgets_old_elementor_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	?>
	<div class="notice notice-warning is-dismissible">
		<p><?php echo esc_html__( 'Animation Widgets necesita Elementor 3.20 o una versión posterior.', 'animation-widgets' ); ?></p>
	</div>
	<?php
}

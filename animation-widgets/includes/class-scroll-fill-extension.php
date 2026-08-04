<?php
/**
 * Scroll Fill controls for Elementor native text widgets.
 *
 * @package Animation_Widgets
 */

namespace Animation_Widgets;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Scroll_Fill_Extension {

	/** Register Elementor hooks. */
	public static function register() {
		// Register after each widget's Style controls so Elementor keeps its
		// familiar Content → Style → Advanced tab order.
		add_action( 'elementor/element/heading/section_title_style/after_section_end', array( __CLASS__, 'add_controls' ) );
		add_action( 'elementor/element/text-editor/section_style/after_section_end', array( __CLASS__, 'add_controls' ) );
		add_action( 'elementor/frontend/widget/before_render', array( __CLASS__, 'before_render' ) );
	}

	/**
	 * Add the effect controls to Heading and Text Editor.
	 *
	 * @param Element_Base $element Elementor element.
	 */
	public static function add_controls( $element ) {
		$element->start_controls_section(
			'aw_scroll_fill_section',
			array(
				'label' => esc_html__( 'Animation Widgets - Scroll Fill', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'aw_scroll_fill_enabled',
			array(
				'label'        => esc_html__( 'Activar Scroll Fill', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Sí', 'animation-widgets' ),
				'label_off'    => esc_html__( 'No', 'animation-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$condition = array( 'aw_scroll_fill_enabled' => 'yes' );

		$element->add_control(
			'aw_scroll_fill_color',
			array(
				'label'     => esc_html__( 'Color relleno', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'condition' => $condition,
				'selectors' => array( '{{WRAPPER}}' => '--aw-scroll-fill-color: {{VALUE}};' ),
			)
		);

		$element->add_control(
			'aw_scroll_fill_muted',
			array(
				'label'     => esc_html__( 'Color inicial', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5B5B5B',
				'condition' => $condition,
				'selectors' => array( '{{WRAPPER}}' => '--aw-scroll-fill-muted: {{VALUE}};' ),
			)
		);

		$element->add_control(
			'aw_scroll_fill_start',
			array(
				'label'       => esc_html__( 'Comenzar al entrar en (%)', 'animation-widgets' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range'       => array( '%' => array( 'min' => 0, 'max' => 100, 'step' => 1 ) ),
				'default'     => array( 'unit' => '%', 'size' => 80 ),
				'condition'   => $condition,
			)
		);

		$element->add_control(
			'aw_scroll_fill_end',
			array(
				'label'       => esc_html__( 'Terminar al salir en (%)', 'animation-widgets' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range'       => array( '%' => array( 'min' => 0, 'max' => 100, 'step' => 1 ) ),
				'default'     => array( 'unit' => '%', 'size' => 20 ),
				'condition'   => $condition,
			)
		);

		$element->add_control(
			'aw_scroll_fill_soften',
			array(
				'label'      => esc_html__( 'Suavidad entre caracteres', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'x' ),
				'range'      => array( 'x' => array( 'min' => 0, 'max' => 12, 'step' => 0.5 ) ),
				'default'    => array( 'unit' => 'x', 'size' => 4 ),
				'condition'  => $condition,
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Mark enabled native widgets and enqueue the shared frontend assets.
	 *
	 * @param Element_Base $widget Elementor widget.
	 */
	public static function before_render( $widget ) {
		$name = $widget->get_name();
		if ( ! in_array( $name, array( 'heading', 'text-editor' ), true ) ) {
			return;
		}

		$settings = $widget->get_settings_for_display();
		if ( 'yes' !== ( $settings['aw_scroll_fill_enabled'] ?? '' ) ) {
			return;
		}

		$start  = isset( $settings['aw_scroll_fill_start']['size'] ) ? max( 0, min( 100, (float) $settings['aw_scroll_fill_start']['size'] ) ) : 80;
		$end    = isset( $settings['aw_scroll_fill_end']['size'] ) ? max( 0, min( 100, (float) $settings['aw_scroll_fill_end']['size'] ) ) : 20;
		$soften = isset( $settings['aw_scroll_fill_soften']['size'] ) ? max( 0, min( 12, (float) $settings['aw_scroll_fill_soften']['size'] ) ) : 4;

		$widget->add_render_attribute(
			'_wrapper',
			array(
				'class'               => 'aw-scroll-fill-native',
				'data-aw-scroll-fill' => '',
				'data-aw-fill-target' => $name,
				'data-start'          => (string) $start,
				'data-end'            => (string) $end,
				'data-soften'         => (string) $soften,
			)
		);

		wp_enqueue_style( 'animation-widgets-frontend' );
		wp_enqueue_script( 'animation-widgets-frontend' );
	}
}

<?php
/**
 * Scroll-linked text fill widget.
 *
 * @package Animation_Widgets
 */

namespace Animation_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Scroll_Fill extends Widget_Base {

	public function get_name() {
		return 'animation-widgets-scroll-fill';
	}

	public function get_title() {
		return esc_html__( 'Texto Scroll Fill', 'animation-widgets' );
	}

	public function get_icon() {
		return 'eicon-animated-headline';
	}

	public function get_categories() {
		return array( 'animation-widgets' );
	}

	public function get_keywords() {
		return array( 'texto', 'scroll', 'fill', 'reveal', 'relleno', 'lectura' );
	}

	public function get_script_depends() {
		return array( 'animation-widgets-frontend' );
	}

	public function get_style_depends() {
		return array( 'animation-widgets-frontend' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Contenido', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Texto', 'animation-widgets' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => esc_html__( 'Este texto se llena de color a medida que avanzas por la página.', 'animation-widgets' ),
				'placeholder' => esc_html__( 'Escribe el texto que quieres revelar.', 'animation-widgets' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'html_tag',
			array(
				'label'   => esc_html__( 'Etiqueta HTML', 'animation-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'div',
				'options' => array(
					'h1'  => 'H1',
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'div' => 'DIV',
					'p'   => 'P',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'behavior_section',
			array(
				'label' => esc_html__( 'Recorrido del scroll', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'start_point',
			array(
				'label'       => esc_html__( 'Comenzar al entrar en (%)', 'animation-widgets' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range'       => array( '%' => array( 'min' => 0, 'max' => 100, 'step' => 1 ) ),
				'default'     => array( 'unit' => '%', 'size' => 80 ),
				'description' => esc_html__( 'Altura de la ventana donde empieza el relleno. 80% equivale a cerca de la parte inferior.', 'animation-widgets' ),
			)
		);

		$this->add_control(
			'end_point',
			array(
				'label'       => esc_html__( 'Terminar al salir en (%)', 'animation-widgets' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range'       => array( '%' => array( 'min' => 0, 'max' => 100, 'step' => 1 ) ),
				'default'     => array( 'unit' => '%', 'size' => 20 ),
				'description' => esc_html__( 'Altura de la ventana donde el último carácter queda totalmente relleno.', 'animation-widgets' ),
			)
		);

		$this->add_control(
			'soften',
			array(
				'label'       => esc_html__( 'Suavidad entre caracteres', 'animation-widgets' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'x' ),
				'range'       => array( 'x' => array( 'min' => 0, 'max' => 12, 'step' => 0.5 ) ),
				'default'     => array( 'unit' => 'x', 'size' => 4 ),
				'description' => esc_html__( 'Aumenta el número de caracteres que cambian de color simultáneamente.', 'animation-widgets' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => esc_html__( 'Texto', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .aw-scroll-fill__text',
			)
		);

		$this->add_control(
			'filled_color',
			array(
				'label'     => esc_html__( 'Color relleno', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .aw-scroll-fill' => '--aw-scroll-fill-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'muted_color',
			array(
				'label'     => esc_html__( 'Color inicial', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5B5B5B',
				'selectors' => array( '{{WRAPPER}} .aw-scroll-fill' => '--aw-scroll-fill-muted: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'text_align',
			array(
				'label'     => esc_html__( 'Alineación', 'animation-widgets' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array( 'title' => esc_html__( 'Izquierda', 'animation-widgets' ), 'icon' => 'eicon-text-align-left' ),
					'center' => array( 'title' => esc_html__( 'Centro', 'animation-widgets' ), 'icon' => 'eicon-text-align-center' ),
					'right'  => array( 'title' => esc_html__( 'Derecha', 'animation-widgets' ), 'icon' => 'eicon-text-align-right' ),
				),
				'default'   => 'left',
				'selectors' => array( '{{WRAPPER}} .aw-scroll-fill__text' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'max_width',
			array(
				'label'      => esc_html__( 'Ancho máximo', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array( 'min' => 200, 'max' => 1800 ),
					'%'  => array( 'min' => 10, 'max' => 100 ),
					'vw' => array( 'min' => 10, 'max' => 100 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 1000 ),
				'selectors'  => array( '{{WRAPPER}} .aw-scroll-fill__text' => 'max-width: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$text     = isset( $settings['text'] ) ? trim( $settings['text'] ) : '';
		$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p' );
		$tag = in_array( $settings['html_tag'], $allowed_tags, true ) ? $settings['html_tag'] : 'div';

		if ( '' === $text ) {
			return;
		}

		$start  = isset( $settings['start_point']['size'] ) ? max( 0, min( 100, (float) $settings['start_point']['size'] ) ) : 80;
		$end    = isset( $settings['end_point']['size'] ) ? max( 0, min( 100, (float) $settings['end_point']['size'] ) ) : 20;
		$soften = isset( $settings['soften']['size'] ) ? max( 0, min( 12, (float) $settings['soften']['size'] ) ) : 4;

		$this->add_render_attribute(
			'root',
			array(
				'class'          => 'aw-scroll-fill',
				'data-aw-scroll-fill' => '',
				'data-start'     => (string) $start,
				'data-end'       => (string) $end,
				'data-soften'    => (string) $soften,
			)
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'root' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<<?php echo esc_html( $tag ); ?> class="aw-scroll-fill__text"><?php echo wp_kses_post( $text ); ?></<?php echo esc_html( $tag ); ?>>
		</div>
		<?php
	}
}

<?php
/**
 * Fading testimonial carousel widget.
 *
 * @package Animation_Widgets
 */

namespace Animation_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Testimonial_Carousel extends Widget_Base {

	public function get_name() {
		return 'animation-widgets-testimonial-carousel';
	}

	public function get_title() {
		return esc_html__( 'Carrusel de testimonios', 'animation-widgets' );
	}

	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	public function get_categories() {
		return array( 'animation-widgets' );
	}

	public function get_keywords() {
		return array( 'testimonios', 'reseñas', 'carrusel', 'slider', 'fade' );
	}

	public function get_script_depends() {
		return array( 'animation-widgets-frontend' );
	}

	public function get_style_depends() {
		return array( 'animation-widgets-frontend' );
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_behavior_controls();
		$this->register_box_style_controls();
		$this->register_text_style_controls();
		$this->register_navigation_style_controls();
	}

	private function register_content_controls() {
		$this->start_controls_section(
			'testimonials_content',
			array(
				'label' => esc_html__( 'Testimonios', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'quote',
			array(
				'label'       => esc_html__( 'Testimonio', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 6,
				'label_block' => true,
			)
		);
		$repeater->add_control(
			'name',
			array(
				'label'       => esc_html__( 'Nombre', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);
		$repeater->add_control(
			'source',
			array(
				'label'       => esc_html__( 'Procedencia (opcional)', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Reseña de Google', 'animation-widgets' ),
			)
		);

		$this->add_control(
			'testimonials',
			array(
				'label'         => esc_html__( 'Elementos', 'animation-widgets' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => true,
				'default'       => array(
					array(
						'quote'  => esc_html__( 'Un espacio fantástico para cuidarte y disfrutar de cada clase.', 'animation-widgets' ),
						'name'   => esc_html__( 'María', 'animation-widgets' ),
						'source' => esc_html__( 'Reseña de Google', 'animation-widgets' ),
					),
					array(
						'quote'  => esc_html__( 'Clases dinámicas, variadas y muy bien explicadas.', 'animation-widgets' ),
						'name'   => esc_html__( 'Ana', 'animation-widgets' ),
						'source' => esc_html__( 'Reseña de Google', 'animation-widgets' ),
					),
				),
				'title_field'   => '{{{ name || "Testimonio" }}}',
			)
		);

		$this->end_controls_section();
	}

	private function register_behavior_controls() {
		$this->start_controls_section(
			'testimonials_behavior',
			array(
				'label' => esc_html__( 'Movimiento y navegación', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Reproducción automática', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Sí', 'animation-widgets' ),
				'label_off'    => esc_html__( 'No', 'animation-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'interval',
			array(
				'label'     => esc_html__( 'Tiempo entre testimonios (ms)', 'animation-widgets' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 4500,
				'min'       => 1500,
				'max'       => 20000,
				'step'      => 100,
				'condition' => array( 'autoplay' => 'yes' ),
			)
		);
		$this->add_control(
			'transition_duration',
			array(
				'label'   => esc_html__( 'Duración del fundido (ms)', 'animation-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 700,
				'min'     => 100,
				'max'     => 3000,
				'step'    => 50,
			)
		);
		$this->add_control(
			'resume_delay',
			array(
				'label'       => esc_html__( 'Reanudar tras interactuar (ms)', 'animation-widgets' ),
				'description' => esc_html__( 'Tiempo de espera antes de volver a avanzar automáticamente.', 'animation-widgets' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 3500,
				'min'         => 500,
				'max'         => 20000,
				'step'        => 100,
				'condition'   => array( 'autoplay' => 'yes' ),
			)
		);
		$this->add_control(
			'pause_hover',
			array(
				'label'        => esc_html__( 'Pausar al pasar el ratón', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array( 'autoplay' => 'yes' ),
			)
		);
		$this->add_control(
			'show_arrows',
			array(
				'label'        => esc_html__( 'Mostrar flechas', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'show_dots',
			array(
				'label'        => esc_html__( 'Mostrar puntos', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
	}

	private function register_box_style_controls() {
		$this->start_controls_section(
			'testimonials_box_style',
			array(
				'label' => esc_html__( 'Caja', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array( 'name' => 'box_background', 'types' => array( 'classic', 'gradient' ), 'selector' => '{{WRAPPER}} .aw-testimonials' )
		);
		$this->add_responsive_control(
			'content_width',
			array(
				'label'      => esc_html__( 'Ancho máximo', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array( 'px' => array( 'min' => 280, 'max' => 1600 ), '%' => array( 'min' => 20, 'max' => 100 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 1000 ),
				'selectors'  => array( '{{WRAPPER}} .aw-testimonials__inner' => 'max-width: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'padding',
			array(
				'label'      => esc_html__( 'Espaciado interior', 'animation-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array( 'top' => 70, 'right' => 48, 'bottom' => 70, 'left' => 48, 'unit' => 'px', 'isLinked' => false ),
				'selectors'  => array( '{{WRAPPER}} .aw-testimonials' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => esc_html__( 'Radio', 'animation-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .aw-testimonials' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array( 'name' => 'box_border', 'selector' => '{{WRAPPER}} .aw-testimonials' )
		);
		$this->end_controls_section();
	}

	private function register_text_style_controls() {
		$this->start_controls_section(
			'testimonials_text_style',
			array(
				'label' => esc_html__( 'Textos', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alineación', 'animation-widgets' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array( 'title' => esc_html__( 'Izquierda', 'animation-widgets' ), 'icon' => 'eicon-text-align-left' ),
					'center' => array( 'title' => esc_html__( 'Centro', 'animation-widgets' ), 'icon' => 'eicon-text-align-center' ),
					'right'  => array( 'title' => esc_html__( 'Derecha', 'animation-widgets' ), 'icon' => 'eicon-text-align-right' ),
				),
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .aw-testimonials' => 'text-align: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'quote_typography', 'label' => esc_html__( 'Tipografía del testimonio', 'animation-widgets' ), 'selector' => '{{WRAPPER}} .aw-testimonials__quote' )
		);
		$this->add_control(
			'quote_color',
			array( 'label' => esc_html__( 'Color del testimonio', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .aw-testimonials__quote' => 'color: {{VALUE}};' ) )
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'meta_typography', 'label' => esc_html__( 'Tipografía del nombre', 'animation-widgets' ), 'selector' => '{{WRAPPER}} .aw-testimonials__meta' )
		);
		$this->add_control(
			'meta_color',
			array( 'label' => esc_html__( 'Color del nombre', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#e6c993', 'selectors' => array( '{{WRAPPER}} .aw-testimonials__meta' => 'color: {{VALUE}};' ) )
		);
		$this->add_control(
			'source_color',
			array( 'label' => esc_html__( 'Color de la procedencia', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#e6c993', 'selectors' => array( '{{WRAPPER}} .aw-testimonials__source' => 'color: {{VALUE}};' ) )
		);
		$this->add_responsive_control(
			'meta_spacing',
			array(
				'label'      => esc_html__( 'Separación del nombre', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 28 ),
				'selectors'  => array( '{{WRAPPER}} .aw-testimonials__meta' => 'margin-top: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();
	}

	private function register_navigation_style_controls() {
		$this->start_controls_section(
			'testimonials_navigation_style',
			array(
				'label' => esc_html__( 'Navegación', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'arrow_color',
			array( 'label' => esc_html__( 'Color de flechas', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .aw-carousel-arrow' => 'color: {{VALUE}}; border-color: {{VALUE}};' ) )
		);
		$this->add_responsive_control(
			'arrow_size',
			array( 'label' => esc_html__( 'Tamaño de flechas', 'animation-widgets' ), 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 28, 'max' => 90 ) ), 'default' => array( 'unit' => 'px', 'size' => 48 ), 'selectors' => array( '{{WRAPPER}} .aw-carousel-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ) )
		);
		$this->add_responsive_control(
			'dot_size',
			array(
				'label'      => esc_html__( 'Tamaño de puntos', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 4, 'max' => 30 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 8 ),
				'selectors'  => array( '{{WRAPPER}} .aw-carousel-dots button.aw-carousel-dot' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; max-width: {{SIZE}}{{UNIT}} !important; max-height: {{SIZE}}{{UNIT}} !important;' ),
			)
		);
		$this->add_responsive_control(
			'dot_gap',
			array(
				'label'      => esc_html__( 'Separación entre puntos', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 50 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 9 ),
				'selectors'  => array( '{{WRAPPER}} .aw-carousel-dots' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_control(
			'dot_color',
			array( 'label' => esc_html__( 'Color de puntos', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => 'rgba(255,255,255,.28)', 'selectors' => array( '{{WRAPPER}} .aw-carousel-dot' => 'background-color: {{VALUE}} !important;' ) )
		);
		$this->add_control(
			'dot_active_color',
			array( 'label' => esc_html__( 'Color del punto activo', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#d0af72', 'selectors' => array( '{{WRAPPER}} .aw-carousel-dot.is-active' => 'background-color: {{VALUE}} !important;' ) )
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = array_values(
			array_filter(
				(array) $settings['testimonials'],
				static function ( $item ) {
					return ! empty( trim( (string) ( $item['quote'] ?? '' ) ) );
				}
			)
		);

		if ( empty( $items ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="aw-widget-placeholder">' . esc_html__( 'Añade al menos un testimonio.', 'animation-widgets' ) . '</div>';
			}
			return;
		}

		$show_arrows = 'yes' === ( $settings['show_arrows'] ?? 'yes' ) && count( $items ) > 1;
		$show_dots   = 'yes' === ( $settings['show_dots'] ?? 'yes' ) && count( $items ) > 1;
		$this->add_render_attribute(
			'carousel',
			array(
				'class'               => 'aw-testimonials',
				'data-aw-testimonials' => '',
				'data-autoplay'        => 'yes' === ( $settings['autoplay'] ?? 'yes' ) ? 'true' : 'false',
				'data-interval'        => (string) max( 1500, min( 20000, (int) ( $settings['interval'] ?? 4500 ) ) ),
				'data-duration'        => (string) max( 100, min( 3000, (int) ( $settings['transition_duration'] ?? 700 ) ) ),
				'data-resume-delay'    => (string) max( 500, min( 20000, (int) ( $settings['resume_delay'] ?? 3500 ) ) ),
				'data-pause-hover'     => 'yes' === ( $settings['pause_hover'] ?? 'yes' ) ? 'true' : 'false',
			)
		);
		?>
		<section <?php echo $this->get_render_attribute_string( 'carousel' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-roledescription="carousel">
			<div class="aw-testimonials__inner">
				<div class="aw-testimonials__slides" aria-live="polite">
					<?php foreach ( $items as $index => $item ) : ?>
						<article class="aw-testimonials__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-aw-testimonial-slide aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
							<blockquote class="aw-testimonials__quote"><?php echo nl2br( esc_html( trim( (string) $item['quote'] ) ) ); ?></blockquote>
							<?php $name = trim( sanitize_text_field( (string) ( $item['name'] ?? '' ) ) ); ?>
							<?php $source = trim( sanitize_text_field( (string) ( $item['source'] ?? '' ) ) ); ?>
							<?php if ( '' !== $name || '' !== $source ) : ?>
								<div class="aw-testimonials__meta">
									<?php if ( '' !== $name ) : ?><span class="aw-testimonials__name"><?php echo esc_html( $name ); ?></span><?php endif; ?>
									<?php if ( '' !== $name && '' !== $source ) : ?><span aria-hidden="true"> · </span><?php endif; ?>
									<?php if ( '' !== $source ) : ?><span class="aw-testimonials__source"><?php echo esc_html( $source ); ?></span><?php endif; ?>
								</div>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
				<?php if ( $show_arrows ) : ?>
					<button class="aw-carousel-arrow aw-carousel-arrow--prev" type="button" data-aw-prev aria-label="<?php echo esc_attr__( 'Testimonio anterior', 'animation-widgets' ); ?>">&#8592;</button>
					<button class="aw-carousel-arrow aw-carousel-arrow--next" type="button" data-aw-next aria-label="<?php echo esc_attr__( 'Testimonio siguiente', 'animation-widgets' ); ?>">&#8594;</button>
				<?php endif; ?>
				<?php if ( $show_dots ) : ?>
					<div class="aw-carousel-dots" data-aw-dots>
						<?php foreach ( $items as $index => $item ) : // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable ?>
							<button class="aw-carousel-dot<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" data-aw-dot="<?php echo esc_attr( (string) $index ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Mostrar testimonio %d', 'animation-widgets' ), $index + 1 ) ); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>"></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

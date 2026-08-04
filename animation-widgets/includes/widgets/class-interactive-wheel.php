<?php
/**
 * Interactive wheel widget.
 *
 * @package Animation_Widgets
 */

namespace Animation_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Interactive_Wheel extends Widget_Base {

	public function get_name() {
		return 'animation-widgets-interactive-wheel';
	}

	public function get_title() {
		return esc_html__( 'Ruleta interactiva', 'animation-widgets' );
	}

	public function get_icon() {
		return 'eicon-sync';
	}

	public function get_categories() {
		return array( 'animation-widgets' );
	}

	public function get_keywords() {
		return array( 'ruleta', 'wheel', 'azar', 'animación', 'interactivo' );
	}

	public function get_script_depends() {
		return array( 'animation-widgets-frontend' );
	}

	public function get_style_depends() {
		return array( 'animation-widgets-frontend' );
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_wheel_style_controls();
		$this->register_button_style_controls();
		$this->register_result_style_controls();
	}

	private function register_content_controls() {
		$this->start_controls_section(
			'wheel_content',
			array(
				'label' => esc_html__( 'Ruleta', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'wheel_image',
			array(
				'label'   => esc_html__( 'Imagen completa de la ruleta', 'animation-widgets' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'wheel_image_size',
				'default'   => 'full',
				'separator' => 'none',
			)
		);

		$this->add_control(
			'pointer_image',
			array(
				'label'   => esc_html__( 'Imagen del selector', 'animation-widgets' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'pointer_position',
			array(
				'label'   => esc_html__( 'Posición del selector', 'animation-widgets' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'top'    => array( 'title' => esc_html__( 'Arriba', 'animation-widgets' ), 'icon' => 'eicon-v-align-top' ),
					'right'  => array( 'title' => esc_html__( 'Derecha', 'animation-widgets' ), 'icon' => 'eicon-h-align-right' ),
					'bottom' => array( 'title' => esc_html__( 'Abajo', 'animation-widgets' ), 'icon' => 'eicon-v-align-bottom' ),
					'left'   => array( 'title' => esc_html__( 'Izquierda', 'animation-widgets' ), 'icon' => 'eicon-h-align-left' ),
				),
				'default' => 'bottom',
				'toggle'  => false,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'angle',
			array(
				'label'       => esc_html__( 'Ángulo de parada', 'animation-widgets' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => -3600,
				'max'         => 3600,
				'step'        => 0.1,
				'description' => esc_html__( 'Rotación final de la imagen, en grados.', 'animation-widgets' ),
			)
		);
		$repeater->add_control(
			'result_text',
			array(
				'label'       => esc_html__( 'Texto del resultado', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'label_block' => true,
			)
		);

		$this->add_control(
			'results',
			array(
				'label'         => esc_html__( 'Casillas y resultados', 'animation-widgets' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => true,
				'default'       => array(
					array( 'angle' => 0, 'result_text' => esc_html__( 'Hoy necesitas Juego: soltar reglas heredadas y probar respuestas nuevas.', 'animation-widgets' ) ),
					array( 'angle' => 60, 'result_text' => esc_html__( 'Hoy necesitas Reconfiguración: revisar cómo estás comunicando y sosteniendo tu impacto.', 'animation-widgets' ) ),
					array( 'angle' => 120, 'result_text' => esc_html__( 'Hoy necesitas Origen: volver al lugar interno desde el que quieres liderar.', 'animation-widgets' ) ),
					array( 'angle' => 180, 'result_text' => esc_html__( 'Hoy necesitas Foco: ordenar prioridades y recuperar dirección.', 'animation-widgets' ) ),
					array( 'angle' => 300, 'result_text' => esc_html__( 'Hoy necesitas Autoridad: encarnar una presencia más clara, propia y coherente.', 'animation-widgets' ) ),
				),
				'title_field'   => '{{{ angle }}}° — {{{ result_text }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wheel_behavior',
			array(
				'label' => esc_html__( 'Comportamiento', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'button_initial_text',
			array(
				'label'       => esc_html__( 'Texto inicial del botón', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Tira de la ruleta y descubre lo que necesitas', 'animation-widgets' ),
				'label_block' => true,
			)
		);
		$this->add_control(
			'button_spinning_text',
			array(
				'label'       => esc_html__( 'Texto durante el giro', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'La ruleta está girando…', 'animation-widgets' ),
				'label_block' => true,
			)
		);
		$this->add_control(
			'button_repeat_text',
			array(
				'label'       => esc_html__( 'Texto para volver a tirar', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Tira de nuevo la ruleta', 'animation-widgets' ),
				'label_block' => true,
			)
		);
		$this->add_control(
			'spin_duration',
			array(
				'label'   => esc_html__( 'Duración del giro (segundos)', 'animation-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5.2,
				'min'     => 0.2,
				'max'     => 30,
				'step'    => 0.1,
			)
		);
		$this->add_control(
			'min_turns',
			array(
				'label'   => esc_html__( 'Vueltas mínimas', 'animation-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5,
				'min'     => 0,
				'max'     => 50,
				'step'    => 1,
			)
		);
		$this->add_control(
			'max_turns',
			array(
				'label'       => esc_html__( 'Vueltas máximas', 'animation-widgets' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 7,
				'min'         => 0,
				'max'         => 50,
				'step'        => 1,
				'description' => esc_html__( 'Usa el mismo valor en mínimo y máximo para un número fijo.', 'animation-widgets' ),
			)
		);
		$this->add_control(
			'result_animation',
			array(
				'label'   => esc_html__( 'Animación del resultado', 'animation-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slide-up',
				'options' => array(
					'fade'     => esc_html__( 'Fundido', 'animation-widgets' ),
					'slide-up' => esc_html__( 'Desplazamiento hacia arriba', 'animation-widgets' ),
					'zoom'     => esc_html__( 'Zoom suave', 'animation-widgets' ),
					'none'     => esc_html__( 'Sin animación', 'animation-widgets' ),
				),
			)
		);
		$this->add_control(
			'result_duration',
			array(
				'label'   => esc_html__( 'Duración del resultado (ms)', 'animation-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
				'min'     => 0,
				'max'     => 5000,
				'step'    => 50,
			)
		);

		$this->end_controls_section();
	}

	private function register_wheel_style_controls() {
		$this->start_controls_section(
			'wheel_style',
			array(
				'label' => esc_html__( 'Ruleta y selector', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'wheel_width',
			array(
				'label'      => esc_html__( 'Ancho de la ruleta', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array( 'min' => 100, 'max' => 1400 ),
					'%'  => array( 'min' => 10, 'max' => 100 ),
					'vw' => array( 'min' => 10, 'max' => 100 ),
				),
				'default'    => array( 'unit' => '%', 'size' => 76 ),
				'selectors'  => array( '{{WRAPPER}} .aw-wheel__stage' => 'width: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'pointer_size',
			array(
				'label'      => esc_html__( 'Tamaño del selector', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 5, 'max' => 300 ),
					'%'  => array( 'min' => 1, 'max' => 50 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 60 ),
				'selectors'  => array( '{{WRAPPER}} .aw-wheel__pointer' => 'width: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'pointer_offset',
			array(
				'label'      => esc_html__( 'Distancia al borde', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => -150, 'max' => 150 ) ),
				'default'    => array( 'unit' => 'px', 'size' => -12 ),
				'selectors'  => array( '{{WRAPPER}} .aw-wheel__stage' => '--aw-pointer-offset: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'pointer_shift',
			array(
				'label'      => esc_html__( 'Desplazamiento lateral', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => -300, 'max' => 300 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 0 ),
				'selectors'  => array( '{{WRAPPER}} .aw-wheel__stage' => '--aw-pointer-shift: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();
	}

	private function register_button_style_controls() {
		$this->start_controls_section(
			'button_style',
			array(
				'label' => esc_html__( 'Botón', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'   => esc_html__( 'Alineación', 'animation-widgets' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array( 'title' => esc_html__( 'Izquierda', 'animation-widgets' ), 'icon' => 'eicon-text-align-left' ),
					'center'     => array( 'title' => esc_html__( 'Centro', 'animation-widgets' ), 'icon' => 'eicon-text-align-center' ),
					'flex-end'   => array( 'title' => esc_html__( 'Derecha', 'animation-widgets' ), 'icon' => 'eicon-text-align-right' ),
				),
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .aw-wheel__control' => 'align-items: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'button_typography', 'selector' => '{{WRAPPER}} .aw-wheel__button' )
		);
		$this->start_controls_tabs( 'button_states' );
		$this->start_controls_tab( 'button_normal', array( 'label' => esc_html__( 'Normal', 'animation-widgets' ) ) );
		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Color del texto', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#242619',
				'selectors' => array( '{{WRAPPER}} .aw-wheel__button' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array( 'name' => 'button_background', 'types' => array( 'classic', 'gradient' ), 'selector' => '{{WRAPPER}} .aw-wheel__button' )
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'button_hover', array( 'label' => esc_html__( 'Hover', 'animation-widgets' ) ) );
		$this->add_control(
			'button_hover_text_color',
			array(
				'label'     => esc_html__( 'Color del texto', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .aw-wheel__button:hover, {{WRAPPER}} .aw-wheel__button:focus-visible' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array( 'name' => 'button_hover_background', 'types' => array( 'classic', 'gradient' ), 'selector' => '{{WRAPPER}} .aw-wheel__button:hover, {{WRAPPER}} .aw-wheel__button:focus-visible' )
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array( 'name' => 'button_border', 'selector' => '{{WRAPPER}} .aw-wheel__button', 'separator' => 'before' )
		);
		$this->add_responsive_control(
			'button_radius',
			array(
				'label'      => esc_html__( 'Radio del borde', 'animation-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .aw-wheel__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array( 'name' => 'button_shadow', 'selector' => '{{WRAPPER}} .aw-wheel__button' )
		);
		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Espaciado interior', 'animation-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array( 'top' => 14, 'right' => 26, 'bottom' => 14, 'left' => 26, 'unit' => 'px', 'isLinked' => false ),
				'selectors'  => array( '{{WRAPPER}} .aw-wheel__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'button_margin_top',
			array(
				'label'      => esc_html__( 'Separación de la ruleta', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 250 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 48 ),
				'selectors'  => array( '{{WRAPPER}} .aw-wheel__control' => 'margin-top: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();
	}

	private function register_result_style_controls() {
		$this->start_controls_section(
			'result_style',
			array(
				'label' => esc_html__( 'Resultado', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'result_typography', 'selector' => '{{WRAPPER}} .aw-wheel__result' )
		);
		$this->add_control(
			'result_color',
			array(
				'label'     => esc_html__( 'Color', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5b603e',
				'selectors' => array( '{{WRAPPER}} .aw-wheel__result' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_responsive_control(
			'result_alignment',
			array(
				'label'   => esc_html__( 'Alineación', 'animation-widgets' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'   => array( 'title' => esc_html__( 'Izquierda', 'animation-widgets' ), 'icon' => 'eicon-text-align-left' ),
					'center' => array( 'title' => esc_html__( 'Centro', 'animation-widgets' ), 'icon' => 'eicon-text-align-center' ),
					'right'  => array( 'title' => esc_html__( 'Derecha', 'animation-widgets' ), 'icon' => 'eicon-text-align-right' ),
				),
				'default'   => 'center',
				'selectors' => array( '{{WRAPPER}} .aw-wheel__result' => 'text-align: {{VALUE}};' ),
			)
		);
		$this->add_responsive_control(
			'result_spacing',
			array(
				'label'      => esc_html__( 'Separación', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 150 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 24 ),
				'selectors'  => array( '{{WRAPPER}} .aw-wheel__result' => 'margin-top: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'result_max_width',
			array(
				'label'      => esc_html__( 'Ancho máximo', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array( 'px' => array( 'min' => 100, 'max' => 1400 ), '%' => array( 'min' => 10, 'max' => 100 ), 'vw' => array( 'min' => 10, 'max' => 100 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 720 ),
				'selectors'  => array( '{{WRAPPER}} .aw-wheel__result' => 'max-width: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$results  = array();

		foreach ( (array) $settings['results'] as $item ) {
			$text = isset( $item['result_text'] ) ? sanitize_textarea_field( $item['result_text'] ) : '';
			if ( '' === trim( $text ) ) {
				continue;
			}
			$results[] = array(
				'angle' => isset( $item['angle'] ) ? (float) $item['angle'] : 0,
				'text'  => $text,
			);
		}

		if ( empty( $settings['wheel_image']['url'] ) || empty( $results ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="aw-widget-placeholder">' . esc_html__( 'Selecciona una imagen de ruleta y añade al menos un resultado.', 'animation-widgets' ) . '</div>';
			}
			return;
		}

		$widget_id = 'aw-wheel-' . $this->get_id();
		$min_turns = max( 0, min( 50, (int) $settings['min_turns'] ) );
		$max_turns = max( $min_turns, min( 50, (int) $settings['max_turns'] ) );
		$duration  = max( 200, min( 30000, (float) $settings['spin_duration'] * 1000 ) );
		$result_duration = max( 0, min( 5000, (int) $settings['result_duration'] ) );

		$this->add_render_attribute(
			'widget',
			array(
				'id'                     => $widget_id,
				'class'                  => 'aw-wheel',
				'data-aw-wheel'          => '',
				'data-results'           => wp_json_encode( $results ),
				'data-duration'          => (string) $duration,
				'data-min-turns'         => (string) $min_turns,
				'data-max-turns'         => (string) $max_turns,
				'data-spinning-text'     => $settings['button_spinning_text'],
				'data-repeat-text'       => $settings['button_repeat_text'],
				'data-result-animation'  => $settings['result_animation'],
				'data-result-duration'   => (string) $result_duration,
				'data-pointer-position'  => $settings['pointer_position'],
			)
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'widget' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> >
			<div class="aw-wheel__stage">
				<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'wheel_image_size', 'wheel_image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( ! empty( $settings['pointer_image']['url'] ) ) : ?>
					<img class="aw-wheel__pointer" src="<?php echo esc_url( $settings['pointer_image']['url'] ); ?>" alt="" aria-hidden="true">
				<?php endif; ?>
			</div>
			<div class="aw-wheel__control">
				<button class="aw-wheel__button" type="button" aria-describedby="<?php echo esc_attr( $widget_id . '-result' ); ?>">
					<span class="aw-wheel__button-label"><?php echo esc_html( $settings['button_initial_text'] ); ?></span>
				</button>
				<p id="<?php echo esc_attr( $widget_id . '-result' ); ?>" class="aw-wheel__result" aria-live="polite" aria-atomic="true"></p>
			</div>
		</div>
		<?php
	}
}


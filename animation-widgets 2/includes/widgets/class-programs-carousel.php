<?php
/**
 * Infinite programs marquee carousel widget.
 *
 * @package Animation_Widgets
 */

namespace Animation_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Programs_Carousel extends Widget_Base {

	public function get_name() {
		return 'animation-widgets-programs-carousel';
	}

	public function get_title() {
		return esc_html__( 'Carrusel de programas', 'animation-widgets' );
	}

	public function get_icon() {
		return 'eicon-media-carousel';
	}

	public function get_categories() {
		return array( 'animation-widgets' );
	}

	public function get_keywords() {
		return array( 'programas', 'carrusel', 'marquee', 'infinito', 'tarjetas' );
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
		$this->register_card_style_controls();
		$this->register_initial_style_controls();
		$this->register_overlay_style_controls();
		$this->register_button_style_controls();
		$this->register_navigation_style_controls();
	}

	private function register_content_controls() {
		$this->start_controls_section(
			'programs_content',
			array(
				'label' => esc_html__( 'Programas', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Imagen', 'animation-widgets' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array( 'active' => true ),
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
			'eyebrow',
			array(
				'label'       => esc_html__( 'Título superior / numeración', 'animation-widgets' ),
				'description' => esc_html__( 'Si lo dejas vacío se genera automáticamente, por ejemplo: 01 — Fit Pilates.', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => '01 — Fit Pilates',
			)
		);
		$repeater->add_control(
			'description',
			array(
				'label'       => esc_html__( 'Descripción', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 6,
				'label_block' => true,
			)
		);
		$repeater->add_control(
			'button_text',
			array(
				'label'       => esc_html__( 'Texto del botón', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Más información', 'animation-widgets' ),
				'label_block' => true,
			)
		);
		$repeater->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Enlace', 'animation-widgets' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
				'options'     => array( 'url', 'is_external', 'nofollow' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'programs',
			array(
				'label'         => esc_html__( 'Elementos', 'animation-widgets' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => true,
				'default'       => array(
					array( 'name' => esc_html__( 'Programa uno', 'animation-widgets' ), 'description' => esc_html__( 'Describe aquí el primer programa.', 'animation-widgets' ), 'button_text' => esc_html__( 'Más información', 'animation-widgets' ) ),
					array( 'name' => esc_html__( 'Programa dos', 'animation-widgets' ), 'description' => esc_html__( 'Describe aquí el segundo programa.', 'animation-widgets' ), 'button_text' => esc_html__( 'Más información', 'animation-widgets' ) ),
					array( 'name' => esc_html__( 'Programa tres', 'animation-widgets' ), 'description' => esc_html__( 'Describe aquí el tercer programa.', 'animation-widgets' ), 'button_text' => esc_html__( 'Más información', 'animation-widgets' ) ),
				),
				'title_field'   => '{{{ name || "Programa" }}}',
			)
		);
		$this->add_control(
			'image_size',
			array(
				'label'   => esc_html__( 'Resolución de imagen', 'animation-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'large',
				'options' => array(
					'thumbnail'    => esc_html__( 'Miniatura', 'animation-widgets' ),
					'medium'       => esc_html__( 'Mediana', 'animation-widgets' ),
					'medium_large' => esc_html__( 'Mediana grande', 'animation-widgets' ),
					'large'        => esc_html__( 'Grande', 'animation-widgets' ),
					'full'         => esc_html__( 'Completa', 'animation-widgets' ),
				),
			)
		);

		$this->end_controls_section();
	}

	private function register_behavior_controls() {
		$this->start_controls_section(
			'programs_behavior',
			array(
				'label' => esc_html__( 'Movimiento y navegación', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Movimiento automático', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Sí', 'animation-widgets' ),
				'label_off'    => esc_html__( 'No', 'animation-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'speed',
			array(
				'label'       => esc_html__( 'Velocidad continua (px/s)', 'animation-widgets' ),
				'description' => esc_html__( 'Píxeles que avanza por segundo.', 'animation-widgets' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 25,
				'min'         => 5,
				'max'         => 200,
				'step'        => 1,
				'condition'   => array( 'autoplay' => 'yes' ),
			)
		);
		$this->add_control(
			'direction',
			array(
				'label'   => esc_html__( 'Dirección', 'animation-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Hacia la izquierda', 'animation-widgets' ),
					'right' => esc_html__( 'Hacia la derecha', 'animation-widgets' ),
				),
			)
		);
		$this->add_control(
			'pause_hover',
			array(
				'label'        => esc_html__( 'Pausar al pasar el ratón', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'resume_delay',
			array(
				'label'   => esc_html__( 'Reanudar tras interactuar (ms)', 'animation-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3000,
				'min'     => 500,
				'max'     => 20000,
				'step'    => 100,
			)
		);
		$this->add_control(
			'show_arrows',
			array(
				'label'        => esc_html__( 'Mostrar flechas', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);
		$this->add_control(
			'show_dots',
			array(
				'label'        => esc_html__( 'Mostrar puntos', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();
	}

	private function register_card_style_controls() {
		$this->start_controls_section(
			'programs_card_style',
			array(
				'label' => esc_html__( 'Tarjetas', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'card_width',
			array(
				'label'      => esc_html__( 'Ancho', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array( 'px' => array( 'min' => 160, 'max' => 900 ), 'vw' => array( 'min' => 30, 'max' => 95 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 420 ),
				'tablet_default' => array( 'unit' => 'vw', 'size' => 54 ),
				'mobile_default' => array( 'unit' => 'vw', 'size' => 82 ),
				'selectors'  => array( '{{WRAPPER}} .aw-programs__card' => 'width: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'card_height',
			array(
				'label'      => esc_html__( 'Altura', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array( 'px' => array( 'min' => 220, 'max' => 1000 ), 'vh' => array( 'min' => 30, 'max' => 100 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 600 ),
				'tablet_default' => array( 'unit' => 'px', 'size' => 520 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 480 ),
				'selectors'  => array( '{{WRAPPER}} .aw-programs__card' => 'height: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Separación', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 100 ), 'vw' => array( 'min' => 0, 'max' => 10 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 0 ),
				'selectors'  => array( '{{WRAPPER}} .aw-programs' => '--aw-program-gap: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_control(
			'image_fit',
			array(
				'label'     => esc_html__( 'Ajuste de imagen', 'animation-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array( 'cover' => esc_html__( 'Cubrir', 'animation-widgets' ), 'contain' => esc_html__( 'Contener', 'animation-widgets' ), 'fill' => esc_html__( 'Estirar', 'animation-widgets' ) ),
				'selectors' => array( '{{WRAPPER}} .aw-programs__image' => 'object-fit: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array( 'name' => 'card_background', 'types' => array( 'classic', 'gradient' ), 'selector' => '{{WRAPPER}} .aw-programs__card' )
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array( 'name' => 'card_border', 'selector' => '{{WRAPPER}} .aw-programs__card' )
		);
		$this->add_responsive_control(
			'card_radius',
			array( 'label' => esc_html__( 'Radio', 'animation-widgets' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'selectors' => array( '{{WRAPPER}} .aw-programs__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) )
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array( 'name' => 'card_shadow', 'selector' => '{{WRAPPER}} .aw-programs__card' )
		);
		$this->end_controls_section();
	}

	private function register_initial_style_controls() {
		$this->start_controls_section(
			'programs_initial_style',
			array( 'label' => esc_html__( 'Contenido antes del hover', 'animation-widgets' ), 'tab' => Controls_Manager::TAB_STYLE )
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'eyebrow_typography', 'label' => esc_html__( 'Tipografía del título superior', 'animation-widgets' ), 'selector' => '{{WRAPPER}} .aw-programs__code, {{WRAPPER}} .aw-programs__overlay-code' )
		);
		$this->add_control(
			'initial_eyebrow_color',
			array( 'label' => esc_html__( 'Color del título superior', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .aw-programs__code' => 'color: {{VALUE}};' ) )
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'initial_button_typography', 'label' => esc_html__( 'Tipografía del botón inicial', 'animation-widgets' ), 'selector' => '{{WRAPPER}} .aw-programs__summary-button' )
		);
		$this->start_controls_tabs( 'initial_button_states' );
		$this->start_controls_tab( 'initial_button_normal', array( 'label' => esc_html__( 'Normal', 'animation-widgets' ) ) );
		$this->add_control( 'initial_button_color', array( 'label' => esc_html__( 'Texto', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .aw-programs__summary-button' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'initial_button_background', array( 'label' => esc_html__( 'Fondo', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => 'rgba(255,255,255,.18)', 'selectors' => array( '{{WRAPPER}} .aw-programs__summary-button' => 'background-color: {{VALUE}};' ) ) );
		$this->end_controls_tab();
		$this->start_controls_tab( 'initial_button_hover', array( 'label' => esc_html__( 'Hover', 'animation-widgets' ) ) );
		$this->add_control( 'initial_button_hover_color', array( 'label' => esc_html__( 'Texto', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .aw-programs__summary-button:hover, {{WRAPPER}} .aw-programs__summary-button:focus-visible' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'initial_button_hover_background', array( 'label' => esc_html__( 'Fondo', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .aw-programs__summary-button:hover, {{WRAPPER}} .aw-programs__summary-button:focus-visible' => 'background-color: {{VALUE}};' ) ) );
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'initial_button_padding',
			array( 'label' => esc_html__( 'Espaciado del botón inicial', 'animation-widgets' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ), 'default' => array( 'top' => 12, 'right' => 24, 'bottom' => 12, 'left' => 24, 'unit' => 'px', 'isLinked' => false ), 'selectors' => array( '{{WRAPPER}} .aw-programs__summary-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) )
		);
		$this->add_responsive_control(
			'initial_button_radius',
			array( 'label' => esc_html__( 'Radio del botón inicial', 'animation-widgets' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'default' => array( 'top' => 8, 'right' => 8, 'bottom' => 8, 'left' => 8, 'unit' => 'px', 'isLinked' => true ), 'selectors' => array( '{{WRAPPER}} .aw-programs__summary-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) )
		);
		$this->end_controls_section();
	}

	private function register_overlay_style_controls() {
		$this->start_controls_section(
			'programs_overlay_style',
			array( 'label' => esc_html__( 'Información superpuesta', 'animation-widgets' ), 'tab' => Controls_Manager::TAB_STYLE )
		);
		$this->add_control(
			'overlay_background',
			array( 'label' => esc_html__( 'Color de la capa', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => 'rgba(88,76,77,.78)', 'selectors' => array( '{{WRAPPER}} .aw-programs__overlay' => 'background-color: {{VALUE}};' ) )
		);
		$this->add_control(
			'overlay_eyebrow_color',
			array( 'label' => esc_html__( 'Color del título superior', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#e6c993', 'selectors' => array( '{{WRAPPER}} .aw-programs__overlay-code' => 'color: {{VALUE}};' ) )
		);
		$this->add_responsive_control(
			'overlay_padding',
			array( 'label' => esc_html__( 'Espaciado interior', 'animation-widgets' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em', '%' ), 'default' => array( 'top' => 36, 'right' => 34, 'bottom' => 36, 'left' => 34, 'unit' => 'px', 'isLinked' => false ), 'selectors' => array( '{{WRAPPER}} .aw-programs__overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) )
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'title_typography', 'label' => esc_html__( 'Tipografía del nombre', 'animation-widgets' ), 'selector' => '{{WRAPPER}} .aw-programs__title, {{WRAPPER}} .aw-programs__label' )
		);
		$this->add_control(
			'title_color',
			array( 'label' => esc_html__( 'Color del nombre', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .aw-programs__title, {{WRAPPER}} .aw-programs__label' => 'color: {{VALUE}};' ) )
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'description_typography', 'label' => esc_html__( 'Tipografía de la descripción', 'animation-widgets' ), 'selector' => '{{WRAPPER}} .aw-programs__description' )
		);
		$this->add_control(
			'description_color',
			array( 'label' => esc_html__( 'Color de la descripción', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => 'rgba(255,255,255,.92)', 'selectors' => array( '{{WRAPPER}} .aw-programs__description' => 'color: {{VALUE}};' ) )
		);
		$this->end_controls_section();
	}

	private function register_button_style_controls() {
		$this->start_controls_section(
			'programs_button_style',
			array( 'label' => esc_html__( 'Botón', 'animation-widgets' ), 'tab' => Controls_Manager::TAB_STYLE )
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'button_typography', 'selector' => '{{WRAPPER}} .aw-programs__button' )
		);
		$this->start_controls_tabs( 'button_states' );
		$this->start_controls_tab( 'button_normal', array( 'label' => esc_html__( 'Normal', 'animation-widgets' ) ) );
		$this->add_control( 'button_color', array( 'label' => esc_html__( 'Texto', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#2e2829', 'selectors' => array( '{{WRAPPER}} .aw-programs__button' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'button_background', array( 'label' => esc_html__( 'Fondo', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#d0af72', 'selectors' => array( '{{WRAPPER}} .aw-programs__button' => 'background-color: {{VALUE}};' ) ) );
		$this->end_controls_tab();
		$this->start_controls_tab( 'button_hover', array( 'label' => esc_html__( 'Hover', 'animation-widgets' ) ) );
		$this->add_control( 'button_hover_color', array( 'label' => esc_html__( 'Texto', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .aw-programs__button:hover, {{WRAPPER}} .aw-programs__button:focus-visible' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'button_hover_background', array( 'label' => esc_html__( 'Fondo', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .aw-programs__button:hover, {{WRAPPER}} .aw-programs__button:focus-visible' => 'background-color: {{VALUE}};' ) ) );
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'button_padding',
			array( 'label' => esc_html__( 'Espaciado', 'animation-widgets' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ), 'default' => array( 'top' => 12, 'right' => 24, 'bottom' => 12, 'left' => 24, 'unit' => 'px', 'isLinked' => false ), 'selectors' => array( '{{WRAPPER}} .aw-programs__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) )
		);
		$this->add_responsive_control(
			'button_radius',
			array( 'label' => esc_html__( 'Radio', 'animation-widgets' ), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'default' => array( 'top' => 8, 'right' => 8, 'bottom' => 8, 'left' => 8, 'unit' => 'px', 'isLinked' => true ), 'selectors' => array( '{{WRAPPER}} .aw-programs__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) )
		);
		$this->end_controls_section();
	}

	private function register_navigation_style_controls() {
		$this->start_controls_section(
			'programs_navigation_style',
			array( 'label' => esc_html__( 'Navegación', 'animation-widgets' ), 'tab' => Controls_Manager::TAB_STYLE )
		);
		$this->add_control( 'arrow_color', array( 'label' => esc_html__( 'Color de flechas', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#584c4d', 'selectors' => array( '{{WRAPPER}} .aw-carousel-arrow' => 'color: {{VALUE}}; border-color: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'arrow_size', array( 'label' => esc_html__( 'Tamaño de flechas', 'animation-widgets' ), 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 28, 'max' => 90 ) ), 'default' => array( 'unit' => 'px', 'size' => 48 ), 'selectors' => array( '{{WRAPPER}} .aw-carousel-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'dot_size', array( 'label' => esc_html__( 'Tamaño de puntos', 'animation-widgets' ), 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 4, 'max' => 30 ) ), 'default' => array( 'unit' => 'px', 'size' => 8 ), 'selectors' => array( '{{WRAPPER}} .aw-carousel-dots button.aw-carousel-dot' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; max-width: {{SIZE}}{{UNIT}} !important; max-height: {{SIZE}}{{UNIT}} !important;' ) ) );
		$this->add_responsive_control( 'dot_gap', array( 'label' => esc_html__( 'Separación entre puntos', 'animation-widgets' ), 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 50 ) ), 'default' => array( 'unit' => 'px', 'size' => 9 ), 'selectors' => array( '{{WRAPPER}} .aw-carousel-dots' => 'gap: {{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'dot_color', array( 'label' => esc_html__( 'Color de puntos', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => 'rgba(88,76,77,.28)', 'selectors' => array( '{{WRAPPER}} .aw-carousel-dot' => 'background-color: {{VALUE}} !important;' ) ) );
		$this->add_control( 'dot_active_color', array( 'label' => esc_html__( 'Color del punto activo', 'animation-widgets' ), 'type' => Controls_Manager::COLOR, 'default' => '#584c4d', 'selectors' => array( '{{WRAPPER}} .aw-carousel-dot.is-active' => 'background-color: {{VALUE}} !important;' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = array_values(
			array_filter(
				(array) $settings['programs'],
				static function ( $item ) {
					return ! empty( $item['image']['url'] ) || ! empty( trim( (string) ( $item['name'] ?? '' ) ) );
				}
			)
		);

		if ( empty( $items ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="aw-widget-placeholder">' . esc_html__( 'Añade al menos un programa.', 'animation-widgets' ) . '</div>';
			}
			return;
		}

		$show_arrows = 'yes' === ( $settings['show_arrows'] ?? '' );
		$show_dots   = 'yes' === ( $settings['show_dots'] ?? '' );
		$speed       = isset( $settings['speed'] ) ? (float) $settings['speed'] : 25;
		$this->add_render_attribute(
			'carousel',
			array(
				'class'            => 'aw-programs',
				'data-aw-programs' => '',
				'data-autoplay'    => 'yes' === ( $settings['autoplay'] ?? 'yes' ) ? 'true' : 'false',
				'data-speed'       => (string) max( 5, min( 200, $speed ) ),
				'data-direction'   => 'right' === ( $settings['direction'] ?? 'left' ) ? 'right' : 'left',
				'data-pause-hover' => 'yes' === ( $settings['pause_hover'] ?? 'yes' ) ? 'true' : 'false',
				'data-resume-delay' => (string) max( 500, min( 20000, (int) ( $settings['resume_delay'] ?? 3000 ) ) ),
			)
		);
		?>
		<section <?php echo $this->get_render_attribute_string( 'carousel' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-roledescription="carousel">
			<div class="aw-programs__viewport" data-aw-programs-viewport>
				<div class="aw-programs__track" data-aw-programs-track>
					<div class="aw-programs__group" data-aw-programs-group>
						<?php foreach ( $items as $index => $item ) : ?>
							<?php $this->render_program_card( $item, $settings['image_size'], $index ); ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php if ( $show_arrows ) : ?>
				<button class="aw-carousel-arrow aw-carousel-arrow--prev" type="button" data-aw-prev aria-label="<?php echo esc_attr__( 'Programa anterior', 'animation-widgets' ); ?>">&#8592;</button>
				<button class="aw-carousel-arrow aw-carousel-arrow--next" type="button" data-aw-next aria-label="<?php echo esc_attr__( 'Programa siguiente', 'animation-widgets' ); ?>">&#8594;</button>
			<?php endif; ?>
			<?php if ( $show_dots ) : ?>
				<div class="aw-carousel-dots" data-aw-dots>
					<?php foreach ( $items as $index => $item ) : // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable ?>
						<button class="aw-carousel-dot<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" data-aw-dot="<?php echo esc_attr( (string) $index ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Mostrar programa %d', 'animation-widgets' ), $index + 1 ) ); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>"></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>
		<?php
	}

	private function render_program_card( $item, $image_size, $index ) {
		$name        = trim( sanitize_text_field( (string) ( $item['name'] ?? '' ) ) );
		$eyebrow     = trim( sanitize_text_field( (string) ( $item['eyebrow'] ?? '' ) ) );
		$description = trim( sanitize_textarea_field( (string) ( $item['description'] ?? '' ) ) );
		$button_text = trim( sanitize_text_field( (string) ( $item['button_text'] ?? '' ) ) );
		$summary_link_key = 'program-summary-link-' . $index;
		$overlay_link_key = 'program-overlay-link-' . $index;
		$has_button  = '' !== $button_text;
		$has_link    = ! empty( $item['link']['url'] );
		if ( '' === $eyebrow ) {
			$eyebrow = sprintf( '%02d', $index + 1 ) . ( '' !== $name ? ' — ' . $name : '' );
		}
		if ( $has_link ) {
			$this->add_link_attributes( $summary_link_key, $item['link'] );
			$this->add_render_attribute( $summary_link_key, 'class', 'aw-programs__summary-button' );
			$this->add_link_attributes( $overlay_link_key, $item['link'] );
			$this->add_render_attribute( $overlay_link_key, 'class', 'aw-programs__button' );
		}
		?>
		<article class="aw-programs__card" data-aw-program-card tabindex="0">
			<?php echo $this->render_program_image( $item['image'] ?? array(), $image_size, $name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="aw-programs__shade"></div>
			<span class="aw-programs__code"><?php echo esc_html( $eyebrow ); ?></span>
			<div class="aw-programs__summary">
				<?php if ( '' !== $name ) : ?><h3 class="aw-programs__label"><?php echo esc_html( $name ); ?></h3><?php endif; ?>
				<?php if ( $has_button ) : ?>
					<?php if ( $has_link ) : ?>
						<a <?php echo $this->get_render_attribute_string( $summary_link_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $button_text ); ?></a>
					<?php else : ?>
						<span class="aw-programs__summary-button"><?php echo esc_html( $button_text ); ?></span>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="aw-programs__overlay">
				<span class="aw-programs__overlay-code"><?php echo esc_html( $eyebrow ); ?></span>
				<?php if ( '' !== $name ) : ?><h3 class="aw-programs__title"><?php echo esc_html( $name ); ?></h3><?php endif; ?>
				<?php if ( '' !== $description ) : ?><p class="aw-programs__description"><?php echo nl2br( esc_html( $description ) ); ?></p><?php endif; ?>
				<?php if ( $has_button ) : ?>
					<?php if ( $has_link ) : ?>
						<a <?php echo $this->get_render_attribute_string( $overlay_link_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $button_text ); ?></a>
					<?php else : ?>
						<span class="aw-programs__button"><?php echo esc_html( $button_text ); ?></span>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</article>
		<?php
	}

	private function render_program_image( $image, $image_size, $alt ) {
		$attachment_id = ! empty( $image['id'] ) ? (int) $image['id'] : 0;
		if ( $attachment_id ) {
			return wp_get_attachment_image( $attachment_id, $image_size, false, array( 'class' => 'aw-programs__image', 'loading' => 'lazy', 'alt' => $alt ) );
		}
		if ( empty( $image['url'] ) ) {
			return '<span class="aw-programs__image-placeholder" aria-hidden="true"></span>';
		}
		return sprintf( '<img class="aw-programs__image" src="%1$s" alt="%2$s" loading="lazy">', esc_url( $image['url'] ), esc_attr( $alt ) );
	}
}

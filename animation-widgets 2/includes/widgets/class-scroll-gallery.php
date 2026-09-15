<?php
/**
 * Scroll-linked horizontal gallery widget.
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

class Scroll_Gallery extends Widget_Base {

	public function get_name() {
		return 'animation-widgets-scroll-gallery';
	}

	public function get_title() {
		return esc_html__( 'Galería horizontal con scroll', 'animation-widgets' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return array( 'animation-widgets' );
	}

	public function get_keywords() {
		return array( 'galería', 'scroll', 'horizontal', 'sticky', 'carrusel' );
	}

	public function get_script_depends() {
		return array( 'animation-widgets-frontend' );
	}

	public function get_style_depends() {
		return array( 'animation-widgets-frontend' );
	}

	protected function register_controls() {
		$this->register_scene_content_controls();
		$this->register_content_controls();
		$this->register_layout_controls();
		$this->register_card_style_controls();
		$this->register_text_style_controls();
		$this->register_scene_style_controls();
	}

	private function register_scene_content_controls() {
		$this->start_controls_section(
			'scene_content',
			array(
				'label' => esc_html__( 'Título y texto fijos', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'scene_title',
			array(
				'label'       => esc_html__( 'Título superior (opcional)', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'label_block' => true,
				'placeholder' => esc_html__( 'Escribe el título que aparecerá encima de la galería.', 'animation-widgets' ),
			)
		);

		$this->add_control(
			'scene_footer',
			array(
				'label'       => esc_html__( 'Texto inferior (opcional)', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'label_block' => true,
				'placeholder' => esc_html__( 'Escribe el texto que aparecerá debajo de la galería.', 'animation-widgets' ),
			)
		);

		$this->end_controls_section();
	}

	private function register_content_controls() {
		$this->start_controls_section(
			'gallery_content',
			array(
				'label' => esc_html__( 'Imágenes', 'animation-widgets' ),
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
			'text',
			array(
				'label'       => esc_html__( 'Texto (opcional)', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'label_block' => true,
			)
		);
		$repeater->add_control(
			'caption',
			array(
				'label'       => esc_html__( 'Pie de foto (opcional)', 'animation-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);
		$repeater->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Enlace (opcional)', 'animation-widgets' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
				'options'     => array( 'url', 'is_external', 'nofollow' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'         => esc_html__( 'Elementos de la galería', 'animation-widgets' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'prevent_empty' => true,
				'default'       => array(
					array( 'text' => esc_html__( 'Primera imagen', 'animation-widgets' ) ),
					array( 'text' => esc_html__( 'Segunda imagen', 'animation-widgets' ) ),
					array( 'text' => esc_html__( 'Tercera imagen', 'animation-widgets' ) ),
				),
				'title_field'   => '{{{ caption || text || "Imagen" }}}',
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

	private function register_layout_controls() {
		$this->start_controls_section(
			'gallery_behavior',
			array(
				'label' => esc_html__( 'Recorrido y comportamiento', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'full_width',
			array(
				'label'        => esc_html__( 'Ocupar todo el ancho de pantalla', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Sí', 'animation-widgets' ),
				'label_off'    => esc_html__( 'No', 'animation-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'scene_full_height',
			array(
				'label'        => esc_html__( 'Escena completa con título y texto', 'animation-widgets' ),
				'description'  => esc_html__( 'Mantiene una pantalla fijada y reparte su altura entre el título, la galería y el texto inferior.', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Sí', 'animation-widgets' ),
				'label_off'    => esc_html__( 'No', 'animation-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'direction',
			array(
				'label'   => esc_html__( 'Dirección', 'animation-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right-to-left',
				'options' => array(
					'right-to-left' => esc_html__( 'De derecha a izquierda', 'animation-widgets' ),
					'left-to-right' => esc_html__( 'De izquierda a derecha', 'animation-widgets' ),
				),
			)
		);
		$this->add_control(
			'scroll_factor',
			array(
				'label'       => esc_html__( 'Longitud del recorrido', 'animation-widgets' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'x' ),
				'range'       => array( 'x' => array( 'min' => 0.5, 'max' => 3, 'step' => 0.1 ) ),
				'default'     => array( 'unit' => 'x', 'size' => 1 ),
				'description' => esc_html__( 'Un valor mayor crea un tramo vertical más largo y un movimiento más lento.', 'animation-widgets' ),
			)
		);
		$this->add_control(
			'mobile_breakpoint',
			array(
				'label'       => esc_html__( 'Cambiar a deslizamiento táctil hasta (px)', 'animation-widgets' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 767,
				'min'         => 320,
				'max'         => 1200,
				'step'        => 1,
				'description' => esc_html__( 'En pantallas iguales o menores se desactiva el efecto fijado y se puede deslizar con el dedo.', 'animation-widgets' ),
			)
		);

		$this->end_controls_section();
	}

	private function register_card_style_controls() {
		$this->start_controls_section(
			'gallery_style',
			array(
				'label' => esc_html__( 'Imágenes y tarjetas', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'section_background',
			array(
				'label'     => esc_html__( 'Fondo de la sección', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#242619',
				'selectors' => array( '{{WRAPPER}} .aw-gallery, {{WRAPPER}} .aw-gallery__sticky' => 'background-color: {{VALUE}};' ),
			)
		);
		$this->add_responsive_control(
			'card_width',
			array(
				'label'      => esc_html__( 'Ancho de cada tarjeta', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array( 'px' => array( 'min' => 100, 'max' => 1000 ), 'vw' => array( 'min' => 10, 'max' => 95 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 430 ),
				'tablet_default' => array( 'unit' => 'vw', 'size' => 58 ),
				'mobile_default' => array( 'unit' => 'vw', 'size' => 82 ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery__card' => 'width: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Separación entre tarjetas', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 200 ), 'vw' => array( 'min' => 0, 'max' => 20 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 26 ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery__track' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'edge_padding',
			array(
				'label'      => esc_html__( 'Margen en los extremos', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 300 ), 'vw' => array( 'min' => 0, 'max' => 30 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 56 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 20 ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery__track' => 'padding-inline: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_control(
			'auto_viewport_height',
			array(
				'label'        => esc_html__( 'Ajustar automáticamente al alto de las imágenes', 'animation-widgets' ),
				'description'  => esc_html__( 'Úsalo si desactivas la escena completa y quieres que el bloque adopte la altura real de las imágenes.', 'animation-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Sí', 'animation-widgets' ),
				'label_off'    => esc_html__( 'No', 'animation-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
				'condition'    => array( 'scene_full_height!' => 'yes' ),
			)
		);
		$this->add_control(
			'image_ratio',
			array(
				'label'   => esc_html__( 'Proporción de imagen', 'animation-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => esc_html__( 'Original', 'animation-widgets' ),
					'1 / 1'  => '1:1',
					'4 / 3'  => '4:3',
					'3 / 2'  => '3:2',
					'16 / 9' => '16:9',
					'custom' => esc_html__( 'Altura personalizada', 'animation-widgets' ),
				),
				'condition' => array( 'auto_viewport_height!' => 'yes' ),
			)
		);
		$this->add_responsive_control(
			'image_height',
			array(
				'label'      => esc_html__( 'Altura de imagen', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array( 'px' => array( 'min' => 80, 'max' => 1200 ), 'vh' => array( 'min' => 10, 'max' => 100 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 520 ),
				'condition'  => array( 'auto_viewport_height!' => 'yes', 'image_ratio' => 'custom' ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery--custom-height .aw-gallery__image' => 'height: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_control(
			'image_fit',
			array(
				'label'   => esc_html__( 'Ajuste de imagen', 'animation-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => array(
					'cover'   => esc_html__( 'Cubrir', 'animation-widgets' ),
					'contain' => esc_html__( 'Contener', 'animation-widgets' ),
					'fill'    => esc_html__( 'Estirar', 'animation-widgets' ),
				),
				'selectors' => array( '{{WRAPPER}} .aw-gallery__image' => 'object-fit: {{VALUE}};' ),
				'condition' => array( 'auto_viewport_height!' => 'yes' ),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array( 'name' => 'card_background', 'types' => array( 'classic', 'gradient' ), 'selector' => '{{WRAPPER}} .aw-gallery__card' )
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array( 'name' => 'card_border', 'selector' => '{{WRAPPER}} .aw-gallery__card' )
		);
		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => esc_html__( 'Radio', 'animation-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array( 'name' => 'card_shadow', 'selector' => '{{WRAPPER}} .aw-gallery__card' )
		);
		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Espaciado interior', 'animation-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
				'condition'  => array( 'auto_viewport_height!' => 'yes' ),
			)
		);
		$this->add_responsive_control(
			'vertical_padding',
			array(
				'label'      => esc_html__( 'Margen vertical de la galería', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 300 ), 'vh' => array( 'min' => 0, 'max' => 30 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 0 ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery__sticky' => 'padding-block: {{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'auto_viewport_height!' => 'yes' ),
			)
		);
		$this->end_controls_section();
	}

	private function register_text_style_controls() {
		$this->start_controls_section(
			'text_style',
			array(
				'label' => esc_html__( 'Textos opcionales', 'animation-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'text_typography', 'selector' => '{{WRAPPER}} .aw-gallery__text' )
		);
		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Color del texto', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fffced',
				'selectors' => array( '{{WRAPPER}} .aw-gallery__text' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'caption_typography', 'label' => esc_html__( 'Tipografía del pie', 'animation-widgets' ), 'selector' => '{{WRAPPER}} .aw-gallery__caption' )
		);
		$this->add_control(
			'caption_color',
			array(
				'label'     => esc_html__( 'Color del pie', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#9ca973',
				'selectors' => array( '{{WRAPPER}} .aw-gallery__caption' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_responsive_control(
			'text_spacing',
			array(
				'label'      => esc_html__( 'Separación desde la imagen', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 14 ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery__copy' => 'margin-top: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'copy_gap',
			array(
				'label'      => esc_html__( 'Separación entre texto y pie', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 8 ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery__copy' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();
	}

	private function register_scene_style_controls() {
		$this->start_controls_section(
			'scene_text_style',
			array(
				'label'     => esc_html__( 'Título y texto fijos', 'animation-widgets' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'scene_full_height' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'scene_title_typography', 'label' => esc_html__( 'Tipografía del título', 'animation-widgets' ), 'selector' => '{{WRAPPER}} .aw-gallery__scene-title' )
		);
		$this->add_control(
			'scene_title_color',
			array(
				'label'     => esc_html__( 'Color del título', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fffced',
				'selectors' => array( '{{WRAPPER}} .aw-gallery__scene-title' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array( 'name' => 'scene_footer_typography', 'label' => esc_html__( 'Tipografía del texto inferior', 'animation-widgets' ), 'selector' => '{{WRAPPER}} .aw-gallery__scene-footer' )
		);
		$this->add_control(
			'scene_footer_color',
			array(
				'label'     => esc_html__( 'Color del texto inferior', 'animation-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fffced',
				'selectors' => array( '{{WRAPPER}} .aw-gallery__scene-footer' => 'color: {{VALUE}};' ),
			)
		);
		$this->add_responsive_control(
			'scene_text_width',
			array(
				'label'      => esc_html__( 'Ancho máximo de los textos', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array( 'px' => array( 'min' => 200, 'max' => 1600 ), '%' => array( 'min' => 20, 'max' => 100 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 900 ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery__fixed-content' => 'max-width: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'scene_horizontal_padding',
			array(
				'label'      => esc_html__( 'Margen lateral de los textos', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 300 ), 'vw' => array( 'min' => 0, 'max' => 20 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 56 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 20 ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery__header, {{WRAPPER}} .aw-gallery__footer' => 'padding-inline: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'scene_vertical_padding',
			array(
				'label'      => esc_html__( 'Margen superior e inferior', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 200 ), 'vh' => array( 'min' => 0, 'max' => 20 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 48 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 24 ),
				'selectors'  => array( '{{WRAPPER}} .aw-gallery--scene .aw-gallery__scene' => 'padding-block: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'scene_gap',
			array(
				'label'      => esc_html__( 'Separación respecto a la galería', 'animation-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 120 ), 'vh' => array( 'min' => 0, 'max' => 15 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 28 ),
				'selectors'  => array(
					'{{WRAPPER}} .aw-gallery--scene .aw-gallery__header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .aw-gallery--scene .aw-gallery__footer' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = array_filter(
			(array) $settings['items'],
			static function ( $item ) {
				return ! empty( $item['image']['url'] );
			}
		);

		if ( empty( $items ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="aw-widget-placeholder">' . esc_html__( 'Añade al menos una imagen a la galería.', 'animation-widgets' ) . '</div>';
			}
			return;
		}

		$ratio = in_array( $settings['image_ratio'], array( 'auto', '1 / 1', '4 / 3', '3 / 2', '16 / 9', 'custom' ), true ) ? $settings['image_ratio'] : 'auto';
		$classes = array( 'aw-gallery' );
		$auto_viewport_height = 'yes' === ( $settings['auto_viewport_height'] ?? 'yes' );
		$scene_full_height = 'yes' === ( $settings['scene_full_height'] ?? 'yes' );
		if ( 'yes' === $settings['full_width'] ) {
			$classes[] = 'aw-gallery--full-width';
		}
		if ( $scene_full_height ) {
			$classes[] = 'aw-gallery--scene';
		} elseif ( $auto_viewport_height ) {
			$classes[] = 'aw-gallery--fit-content';
		} elseif ( 'custom' === $ratio ) {
			$classes[] = 'aw-gallery--custom-height';
		}

		$this->add_render_attribute(
			'gallery',
			array(
				'class'                  => $classes,
				'data-aw-gallery'        => '',
				'data-direction'         => $settings['direction'],
				'data-scroll-factor'     => (string) max( 0.5, min( 3, (float) $settings['scroll_factor']['size'] ) ),
				'data-mobile-breakpoint' => (string) max( 320, min( 1200, (int) $settings['mobile_breakpoint'] ) ),
				'style'                  => '--aw-image-ratio:' . ( 'custom' === $ratio ? 'auto' : $ratio ) . ';',
			)
		);
		?>
		<section <?php echo $this->get_render_attribute_string( 'gallery' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> >
			<div class="aw-gallery__sticky">
				<div class="aw-gallery__scene">
					<?php $scene_title = isset( $settings['scene_title'] ) ? trim( sanitize_textarea_field( $settings['scene_title'] ) ) : ''; ?>
					<?php $scene_footer = isset( $settings['scene_footer'] ) ? trim( sanitize_textarea_field( $settings['scene_footer'] ) ) : ''; ?>
					<?php if ( '' !== $scene_title ) : ?>
						<header class="aw-gallery__header"><h2 class="aw-gallery__scene-title aw-gallery__fixed-content"><?php echo nl2br( esc_html( $scene_title ) ); ?></h2></header>
					<?php endif; ?>
					<div class="aw-gallery__viewport">
						<div class="aw-gallery__track">
							<?php foreach ( $items as $index => $item ) : ?>
							<?php
							$text     = isset( $item['text'] ) ? trim( sanitize_textarea_field( $item['text'] ) ) : '';
							$caption  = isset( $item['caption'] ) ? trim( sanitize_text_field( $item['caption'] ) ) : '';
							$has_copy = '' !== $text || '' !== $caption;
							$link_key = 'gallery-link-' . $index;
							$has_link = ! empty( $item['link']['url'] );
							if ( $has_link ) {
								$this->add_link_attributes( $link_key, $item['link'] );
								$this->add_render_attribute( $link_key, 'class', 'aw-gallery__card' );
							}
							?>
							<?php if ( $has_link ) : ?>
								<a <?php echo $this->get_render_attribute_string( $link_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php else : ?>
								<article class="aw-gallery__card">
							<?php endif; ?>
								<?php echo $this->render_item_image( $item['image'], $settings['image_size'], $caption ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php if ( $has_copy ) : ?>
									<div class="aw-gallery__copy">
										<?php if ( '' !== $text ) : ?><p class="aw-gallery__text"><?php echo nl2br( esc_html( $text ) ); ?></p><?php endif; ?>
										<?php if ( '' !== $caption ) : ?><p class="aw-gallery__caption"><?php echo esc_html( $caption ); ?></p><?php endif; ?>
									</div>
								<?php endif; ?>
							<?php echo $has_link ? '</a>' : '</article>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php endforeach; ?>
						</div>
					</div>
					<?php if ( '' !== $scene_footer ) : ?>
						<footer class="aw-gallery__footer"><p class="aw-gallery__scene-footer aw-gallery__fixed-content"><?php echo nl2br( esc_html( $scene_footer ) ); ?></p></footer>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}

	/**
	 * Render an attachment responsively, with a safe URL fallback.
	 *
	 * @param array  $image      Elementor media value.
	 * @param string $image_size WordPress image size.
	 * @param string $caption    Optional alternative text fallback.
	 * @return string
	 */
	private function render_item_image( $image, $image_size, $caption ) {
		$attachment_id = ! empty( $image['id'] ) ? (int) $image['id'] : 0;
		$attributes    = array(
			'class'   => 'aw-gallery__image',
			'loading' => 'lazy',
		);

		if ( $attachment_id ) {
			return wp_get_attachment_image( $attachment_id, $image_size, false, $attributes );
		}

		if ( empty( $image['url'] ) ) {
			return '';
		}

		return sprintf(
			'<img class="aw-gallery__image" src="%1$s" alt="%2$s" loading="lazy">',
			esc_url( $image['url'] ),
			esc_attr( $caption )
		);
	}
}

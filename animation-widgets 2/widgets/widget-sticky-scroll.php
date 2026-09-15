<?php
/**
 * Widget: Sticky Scroll
 * Descripción: Columna de textos a la izquierda + imagen sticky a la derecha
 *              que cambia con el scroll. Réplica del efecto ProEffects.
 *
 * Para añadir un nuevo ítem en el editor de Elementor:
 *   Panel izquierdo → sección "Ítems" → botón "+ Añadir ítem"
 *
 * @package GLEX_Widgets
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class AW_Widget_Sticky_Scroll extends \Elementor\Widget_Base {

    public function get_name()        { return 'glex-sticky-scroll'; }
    public function get_title()       { return 'Sticky Scroll'; }
    public function get_icon()        { return 'eicon-scroll'; }
    public function get_categories()  { return [ 'animation-widgets' ]; }
    public function get_keywords()    { return [ 'sticky', 'scroll', 'glex', 'servicios', 'features' ]; }

    // ──────────────────────────────────────────────────────────────────
    //  CONTROLES DEL PANEL
    // ──────────────────────────────────────────────────────────────────
    protected function register_controls() {

        /* ── ÍTEMS ──────────────────────────────────────────────── */
        $this->start_controls_section( 'section_items', [
            'label' => 'Ítems',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $rep = new \Elementor\Repeater();

        $rep->add_control( 'eyebrow', [
            'label'   => 'Eyebrow (// 01)',
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '// 01',
        ] );

        $rep->add_control( 'title', [
            'label'       => 'Título',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => 'Nombre del servicio',
            'label_block' => true,
        ] );

        $rep->add_control( 'title_tag', [
            'label'   => 'Etiqueta del título',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'h2',
            'options' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                'h4' => 'H4', 'p'  => 'Párrafo',
            ],
        ] );

        $rep->add_control( 'description', [
            'label'   => 'Descripción',
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Descripción del servicio.',
            'rows'    => 4,
        ] );

        $rep->add_control( 'btn_text', [
            'label'   => 'Texto del botón',
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'Conocer el servicio',
        ] );

        $rep->add_control( 'btn_icon', [
            'label'   => 'Icono del botón (opcional)',
            'type'    => \Elementor\Controls_Manager::ICONS,
            'default' => [ 'value' => '', 'library' => '' ],
        ] );

        $rep->add_control( 'btn_icon_position', [
            'label'     => 'Posición del icono',
            'type'      => \Elementor\Controls_Manager::SELECT,
            'default'   => 'after',
            'options'   => [ 'before' => 'Antes del texto', 'after' => 'Después del texto' ],
            'condition' => [ 'btn_icon[value]!' => '' ],
        ] );

        $rep->add_control( 'btn_url', [
            'label'         => 'URL del botón',
            'type'          => \Elementor\Controls_Manager::URL,
            'placeholder'   => 'https://...',
            'show_external' => true,
            'default'       => [ 'url' => '#' ],
        ] );

        $rep->add_control( 'image', [
            'label'   => 'Imagen',
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ] );

        $this->add_control( 'items', [
            'label'       => 'Ítems',
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $rep->get_controls(),
            'default'     => [
                [
                    'eyebrow'     => '// 01',
                    'title'       => 'Título del servicio',
                    'title_tag'   => 'h2',
                    'description' => 'Descripción del servicio o característica.',
                    'btn_text'    => 'Saber más',
                    'btn_url'     => [ 'url' => '#' ],
                ],
                [
                    'eyebrow'     => '// 02',
                    'title'       => 'Segundo servicio',
                    'title_tag'   => 'h2',
                    'description' => 'Descripción del segundo servicio o característica.',
                    'btn_text'    => 'Saber más',
                    'btn_url'     => [ 'url' => '#' ],
                ],
                [
                    'eyebrow'     => '// 03',
                    'title'       => 'Tercer servicio',
                    'title_tag'   => 'h2',
                    'description' => 'Descripción del tercer servicio o característica.',
                    'btn_text'    => 'Saber más',
                    'btn_url'     => [ 'url' => '#' ],
                ],
            ],
            'title_field' => '{{{ title }}}',
        ] );

        $this->end_controls_section();

        /* ── ANIMACIÓN ──────────────────────────────────────────── */
        $this->start_controls_section( 'section_anim', [
            'label' => 'Animación',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'transition', [
            'label'   => 'Tipo de transición',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'reveal-down',
            'options' => [
                'reveal-down' => 'Reveal Down — entra de arriba a abajo',
                'reveal-up'   => 'Reveal Up — entra de abajo a arriba',
                'crossfade'   => 'Crossfade — fundido cruzado',
            ],
        ] );

        $this->add_control( 'trigger', [
            'label'       => 'Punto de disparo (% del viewport)',
            'description' => 'Porcentaje del viewport donde comienza la transición. 20 = cuando el ítem llega al 20% superior.',
            'type'        => \Elementor\Controls_Manager::SLIDER,
            'default'     => [ 'size' => 20 ],
            'range'       => [ 'px' => [ 'min' => 5, 'max' => 70 ] ],
        ] );

        $this->add_control( 'dim_inactive', [
            'label'        => 'Atenuar texto inactivo',
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => 'Sí',
            'label_off'    => 'No',
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->add_control( 'inactive_opacity', [
            'label'     => 'Opacidad texto inactivo (%)',
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'default'   => [ 'size' => 30 ],
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
            'condition' => [ 'dim_inactive' => 'yes' ],
        ] );

        $this->end_controls_section();

        /* ── LAYOUT ─────────────────────────────────────────────── */
        $this->start_controls_section( 'section_layout', [
            'label' => 'Layout',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'mobile_mode', [
            'label'       => 'Comportamiento en móvil',
            'description' => 'Elige entre conservar el efecto sticky o mostrar cada imagen seguida de su texto sin animaciones.',
            'type'        => \Elementor\Controls_Manager::SELECT,
            'default'     => 'sticky',
            'options'     => [
                'sticky'  => 'Sticky animado',
                'stacked' => 'Imagen + texto, sin animación',
            ],
        ] );

        $this->add_control( 'col_split', [
            'label'   => 'Ancho columna de texto (%)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 48 ],
            'range'   => [ 'px' => [ 'min' => 25, 'max' => 70 ] ],
        ] );

        $this->add_control( 'col_gap', [
            'label'   => 'Gap entre columnas (px)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 48 ],
            'range'   => [ 'px' => [ 'min' => 0, 'max' => 120 ] ],
        ] );

        $this->add_control( 'pad_x', [
            'label'       => 'Padding horizontal extra (px)',
            'description' => 'Normalmente déjalo en 0 y controla el ancho desde el contenedor de Elementor.',
            'type'        => \Elementor\Controls_Manager::SLIDER,
            'default'     => [ 'size' => 0 ],
            'range'       => [ 'px' => [ 'min' => 0, 'max' => 200 ] ],
        ] );

        $this->add_responsive_control( 'item_height', [
            'label'          => 'Altura mínima del texto',
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'vh', 'px' ],
            'default'        => [ 'size' => 80, 'unit' => 'vh' ],
            'tablet_default' => [ 'size' => 75, 'unit' => 'vh' ],
            'mobile_default' => [ 'size' => 70, 'unit' => 'vh' ],
            'range'          => [
                'vh' => [ 'min' => 0, 'max' => 120 ],
                'px' => [ 'min' => 0, 'max' => 1200 ],
            ],
        ] );

        $this->add_responsive_control( 'image_height', [
            'label'          => 'Altura de la imagen',
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'vh', 'vw', 'px' ],
            'default'        => [ 'size' => 82, 'unit' => 'vh' ],
            'tablet_default' => [ 'size' => 70, 'unit' => 'vh' ],
            'mobile_default' => [ 'size' => 60, 'unit' => 'vw' ],
            'range'          => [
                'vh' => [ 'min' => 20, 'max' => 100 ],
                'vw' => [ 'min' => 20, 'max' => 140 ],
                'px' => [ 'min' => 100, 'max' => 1200 ],
            ],
        ] );

        $this->add_responsive_control( 'content_gap', [
            'label'          => 'Separación entre los textos',
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'px' ],
            'default'        => [ 'size' => 20, 'unit' => 'px' ],
            'tablet_default' => [ 'size' => 16, 'unit' => 'px' ],
            'mobile_default' => [ 'size' => 12, 'unit' => 'px' ],
            'range'          => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
        ] );

        $this->add_responsive_control( 'image_text_gap', [
            'label'          => 'Separación imagen → texto',
            'description'    => 'En el diseño móvil apilado, controla el espacio bajo cada imagen.',
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'px', 'vh' ],
            'default'        => [ 'size' => 24, 'unit' => 'px' ],
            'tablet_default' => [ 'size' => 20, 'unit' => 'px' ],
            'mobile_default' => [ 'size' => 16, 'unit' => 'px' ],
            'range'          => [
                'px' => [ 'min' => 0, 'max' => 200 ],
                'vh' => [ 'min' => 0, 'max' => 30 ],
            ],
        ] );

        $this->add_responsive_control( 'after_text_gap', [
            'label'          => 'Separación texto → imagen siguiente',
            'description'    => 'En el diseño móvil apilado, controla el espacio después de cada bloque de texto.',
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'px', 'vh' ],
            'default'        => [ 'size' => 40, 'unit' => 'px' ],
            'tablet_default' => [ 'size' => 36, 'unit' => 'px' ],
            'mobile_default' => [ 'size' => 28, 'unit' => 'px' ],
            'range'          => [
                'px' => [ 'min' => 0, 'max' => 240 ],
                'vh' => [ 'min' => 0, 'max' => 30 ],
            ],
        ] );

        $this->add_responsive_control( 'last_item_gap', [
            'label'          => 'Espacio final del último ítem',
            'description'    => 'Controla únicamente el espacio que queda después del último texto y su botón.',
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'px', 'vh' ],
            'default'        => [ 'size' => 24, 'unit' => 'px' ],
            'tablet_default' => [ 'size' => 24, 'unit' => 'px' ],
            'mobile_default' => [ 'size' => 20, 'unit' => 'px' ],
            'range'          => [
                'px' => [ 'min' => 0, 'max' => 240 ],
                'vh' => [ 'min' => 0, 'max' => 30 ],
            ],
        ] );

        $this->add_responsive_control( 'sticky_top', [
            'label'          => 'Separación desde arriba (sticky)',
            'description'    => 'Mueve hacia abajo la posición en la que queda fijada la imagen. Úsalo también para dejar espacio a una cabecera fija.',
            'type'           => \Elementor\Controls_Manager::SLIDER,
            'size_units'     => [ 'px', 'vh' ],
            'default'        => [ 'size' => 0, 'unit' => 'px' ],
            'tablet_default' => [ 'size' => 0, 'unit' => 'px' ],
            'mobile_default' => [ 'size' => 0, 'unit' => 'px' ],
            'range'          => [
                'px' => [ 'min' => 0, 'max' => 300 ],
                'vh' => [ 'min' => 0, 'max' => 30 ],
            ],
        ] );

        $this->add_control( 'image_radius', [
            'label'   => 'Border radius de la imagen (px)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 12 ],
            'range'   => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
        ] );

        $this->end_controls_section();

        /* ── ESTILO: EYEBROW ────────────────────────────────────── */
        $this->start_controls_section( 'style_eyebrow', [
            'label' => 'Eyebrow',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'eyebrow_color', [
            'label'     => 'Color',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .gss-eyebrow' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'eyebrow_typo',
            'selector' => '{{WRAPPER}} .gss-eyebrow',
        ] );
        $this->end_controls_section();

        /* ── ESTILO: TÍTULO ─────────────────────────────────────── */
        $this->start_controls_section( 'style_title', [
            'label' => 'Título',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'title_color', [
            'label'     => 'Color',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .gss-title' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'title_typo',
            'selector' => '{{WRAPPER}} .gss-title',
        ] );
        $this->end_controls_section();

        /* ── ESTILO: DESCRIPCIÓN ────────────────────────────────── */
        $this->start_controls_section( 'style_desc', [
            'label' => 'Descripción',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'desc_color', [
            'label'     => 'Color',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .gss-desc' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'desc_typo',
            'selector' => '{{WRAPPER}} .gss-desc',
        ] );
        $this->end_controls_section();

        /* ── ESTILO: BOTÓN ──────────────────────────────────────── */
        $this->start_controls_section( 'style_btn', [
            'label' => 'Botón',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        // Alineación — igual que el widget nativo de Elementor
        $this->add_responsive_control( 'btn_align', [
            'label'     => 'Posición',
            'type'      => \Elementor\Controls_Manager::CHOOSE,
            'options'   => [
                'left'    => [ 'title' => 'Izquierda', 'icon' => 'eicon-text-align-left'    ],
                'center'  => [ 'title' => 'Centro',    'icon' => 'eicon-text-align-center'  ],
                'right'   => [ 'title' => 'Derecha',   'icon' => 'eicon-text-align-right'   ],
                'justify' => [ 'title' => 'Completo',  'icon' => 'eicon-text-align-justify' ],
            ],
            'default'   => 'left',
            'selectors' => [ '{{WRAPPER}} .gss-btn-wrap' => 'text-align: {{VALUE}};' ],
        ] );

        // Ancho completo cuando alineación = justify
        $this->add_control( 'btn_full_width', [
            'label'        => 'Ancho del botón',
            'type'         => \Elementor\Controls_Manager::SELECT,
            'default'      => 'auto',
            'options'      => [ 'auto' => 'Auto', '100%' => 'Completo (100%)' ],
            'selectors'    => [ '{{WRAPPER}} .gss-btn' => 'width: {{VALUE}};' ],
            'condition'    => [ 'btn_align' => 'justify' ],
        ] );

        // Tipografía (fuera de tabs, como en Elementor nativo)
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'btn_typo',
            'selector' => '{{WRAPPER}} .gss-btn',
        ] );

        $this->add_group_control( \Elementor\Group_Control_Text_Shadow::get_type(), [
            'name'     => 'btn_text_shadow',
            'selector' => '{{WRAPPER}} .gss-btn',
        ] );

        // Tabs Normal / Hover
        $this->start_controls_tabs( 'btn_tabs' );

        /* Tab Normal */
        $this->start_controls_tab( 'btn_tab_normal', [ 'label' => 'Normal' ] );

        $this->add_control( 'btn_color', [
            'label'     => 'Color de texto',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .gss-btn' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Background::get_type(), [
            'name'     => 'btn_bg',
            'label'    => 'Fondo',
            'types'    => [ 'classic', 'gradient' ],
            'selector' => '{{WRAPPER}} .gss-btn',
        ] );
        $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'btn_shadow',
            'selector' => '{{WRAPPER}} .gss-btn',
        ] );

        $this->end_controls_tab();

        /* Tab Hover */
        $this->start_controls_tab( 'btn_tab_hover', [ 'label' => 'Al pasar el cursor' ] );

        $this->add_control( 'btn_color_hover', [
            'label'     => 'Color de texto',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .gss-btn:hover' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Background::get_type(), [
            'name'     => 'btn_bg_hover',
            'label'    => 'Fondo',
            'types'    => [ 'classic', 'gradient' ],
            'selector' => '{{WRAPPER}} .gss-btn:hover',
        ] );
        $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'btn_shadow_hover',
            'selector' => '{{WRAPPER}} .gss-btn:hover',
        ] );
        $this->add_control( 'btn_hover_transition', [
            'label'     => 'Duración transición (ms)',
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'default'   => [ 'size' => 300 ],
            'range'     => [ 'px' => [ 'min' => 0, 'max' => 3000 ] ],
            'selectors' => [ '{{WRAPPER}} .gss-btn' => 'transition: all {{SIZE}}ms ease;' ],
        ] );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control( 'btn_border_divider', [ 'type' => \Elementor\Controls_Manager::DIVIDER ] );

        // Borde
        $this->add_group_control( \Elementor\Group_Control_Border::get_type(), [
            'name'     => 'btn_border',
            'selector' => '{{WRAPPER}} .gss-btn',
        ] );

        // Border radius (4 esquinas independientes)
        $this->add_control( 'btn_radius', [
            'label'      => 'Radio del borde',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors'  => [ '{{WRAPPER}} .gss-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        // Padding
        $this->add_control( 'btn_padding', [
            'label'      => 'Relleno (padding)',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [ '{{WRAPPER}} .gss-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();
    }

    // ──────────────────────────────────────────────────────────────────
    //  RENDER (frontend + preview de Elementor)
    // ──────────────────────────────────────────────────────────────────
    protected function render() {
        $s     = $this->get_settings_for_display();
        $items = $s['items'] ?? [];
        $N     = count( $items );
        if ( $N < 1 ) return;

        // Leer ajustes con defaults
        $transition = $s['transition']                    ?? 'reveal-down';
        $trigger    = ( $s['trigger']['size']             ?? 20 ) / 100;
        $dim        = ( $s['dim_inactive']                ?? 'yes' ) === 'yes';
        $mobile_mode = ( $s['mobile_mode'] ?? 'sticky' ) === 'stacked' ? 'stacked' : 'sticky';
        $iop        = ( $s['inactive_opacity']['size']    ?? 30  ) / 100;
        $split      = ( $s['col_split']['size']           ?? 48  ) . '%';
        $gap        = ( $s['col_gap']['size']             ?? 48  ) . 'px';
        $pad_x      = ( $s['pad_x']['size']               ?? 80  ) . 'px';
        $radius     = ( $s['image_radius']['size']        ?? 12  ) . 'px';

        // Los valores responsive se imprimen en el CSS del propio widget. Esto
        // mantiene compatibles los widgets guardados antes de que estos
        // controles fueran responsive y actualiza la vista previa al instante.
        $responsive_size = static function( $value, $default_size, $default_unit, $allowed_units ) {
            $size = isset( $value['size'] ) && is_numeric( $value['size'] ) ? $value['size'] : $default_size;
            $unit = isset( $value['unit'] ) && in_array( $value['unit'], $allowed_units, true )
                ? $value['unit']
                : $default_unit;
            return $size . $unit;
        };

        $item_h         = $responsive_size( $s['item_height']          ?? [], 80, 'vh', [ 'vh', 'px' ] );
        $item_h_tablet  = $responsive_size( $s['item_height_tablet']   ?? [], 75, 'vh', [ 'vh', 'px' ] );
        $mobile_item_setting = $s['item_height_mobile'] ?? [];
        if ( isset( $mobile_item_setting['size'] ) && (float) $mobile_item_setting['size'] <= 0 ) {
            $mobile_item_setting = [];
        }
        $item_h_mobile  = $responsive_size( $mobile_item_setting, 70, 'vh', [ 'vh', 'px' ] );
        $img_h          = $responsive_size( $s['image_height']         ?? [], 82, 'vh', [ 'vh', 'vw', 'px' ] );
        $img_h_tablet   = $responsive_size( $s['image_height_tablet']  ?? [], 70, 'vh', [ 'vh', 'vw', 'px' ] );
        $img_h_mobile   = $responsive_size( $s['image_height_mobile']  ?? [], 60, 'vw', [ 'vh', 'vw', 'px' ] );
        $content_gap    = $responsive_size( $s['content_gap']          ?? [], 20, 'px', [ 'px' ] );
        $content_gap_t  = $responsive_size( $s['content_gap_tablet']   ?? [], 16, 'px', [ 'px' ] );
        $content_gap_m  = $responsive_size( $s['content_gap_mobile']   ?? [], 12, 'px', [ 'px' ] );
        $image_text_m   = $responsive_size( $s['image_text_gap_mobile'] ?? [], 16, 'px', [ 'px', 'vh' ] );
        $after_text_m   = $responsive_size( $s['after_text_gap_mobile'] ?? [], 28, 'px', [ 'px', 'vh' ] );
        $last_item_m    = $responsive_size( $s['last_item_gap_mobile']  ?? [], 20, 'px', [ 'px', 'vh' ] );
        $sticky_top     = $responsive_size( $s['sticky_top']          ?? [], 0, 'px', [ 'px', 'vh' ] );
        $sticky_top_t   = $responsive_size( $s['sticky_top_tablet']   ?? [], 0, 'px', [ 'px', 'vh' ] );
        $sticky_top_m   = $responsive_size( $s['sticky_top_mobile']   ?? [], 0, 'px', [ 'px', 'vh' ] );

        // ID único por instancia de widget (permite múltiples en la misma página)
        $uid        = 'gss-' . $this->get_id();
        $data_pe    = esc_attr( wp_json_encode( [
            'transition'      => $transition,
            'trigger'         => $trigger,
            'dimInactive'     => $dim,
            'inactiveOpacity' => $iop,
        ] ) );

        $margin        = "max(0px,calc((100vh - {$item_h}) / 2))";
        $initial_clip  = $transition === 'reveal-up' ? 'inset(0 0 100% 0)' : 'inset(100% 0 0 0)';

        /* ── CSS (scoped al UID para no afectar otros widgets) ─── */
        ?>
<style>
/* Forzar overflow visible en los wrappers de Elementor para que sticky funcione */
.elementor-widget-glex-sticky-scroll,
.elementor-widget-glex-sticky-scroll > .elementor-widget-container {
  overflow:visible!important;
}
.gss-sticky-context{overflow:visible!important;}
#<?php echo $uid ?>{
  display:grid;
  grid-template-columns:<?php echo $split ?> 1fr;
  grid-template-rows:auto;
  column-gap:<?php echo $gap ?>;
  <?php if ( (int)$s['pad_x']['size'] > 0 ) echo "padding:0 {$pad_x};"; ?>
  width:100%;
  box-sizing:border-box;
}
#<?php echo $uid ?> .gss-card{display:contents}
#<?php echo $uid ?> .gss-content{grid-column:1;grid-row:1;min-width:0;}
#<?php echo $uid ?> .gss-item{
  min-height:<?php echo $item_h ?>;
  display:flex;flex-direction:column;justify-content:center;
  padding:24px 0;
  transition:opacity .35s ease;
}
#<?php echo $uid ?> .gss-copy{display:flex;flex-direction:column;gap:<?php echo $content_gap ?>;}
#<?php echo $uid ?> .gss-stack-image{display:none;}
#<?php echo $uid ?> .gss-media{
  grid-column:2;
  grid-row:1;
  position:sticky;top:<?php echo $sticky_top ?>;height:100vh;
  align-self:start;
  overflow:hidden;
  will-change:transform;
}
#<?php echo $uid ?> .gss-image{
  position:absolute;inset:0;
  overflow:hidden;
  will-change:clip-path,opacity;
}
#<?php echo $uid ?> .gss-image-inner{
  position:absolute;
  top:calc((100vh - <?php echo $img_h ?>) / 2);
  bottom:calc((100vh - <?php echo $img_h ?>) / 2);
  left:0;right:0;
  background-size:cover;background-position:center;background-repeat:no-repeat;
  border-radius:<?php echo $radius ?>;
}
/* Botón: inline por defecto, no estira al 100% */
#<?php echo $uid ?> .gss-btn-wrap{ display:block; }
#<?php echo $uid ?> .gss-btn{
  display:inline-flex;align-items:center;gap:8px;
  text-decoration:none;cursor:pointer;
  box-sizing:border-box;
}
/* Estado inicial antes de que JS arranque */
#<?php echo $uid ?>:not(.gss-ready) .gss-image:not([data-index="0"]){
  clip-path:inset(100% 0 0 0);
}
/* ── Mobile: apila imagen + texto en vertical ──────── */
@media(max-width:1024px) and (min-width:768px){
  #<?php echo $uid ?> .gss-item{
    min-height:<?php echo $item_h_tablet ?>;
  }
  #<?php echo $uid ?> .gss-copy{gap:<?php echo $content_gap_t ?>;}
  #<?php echo $uid ?> .gss-image-inner{
    top:calc((100vh - <?php echo $img_h_tablet ?>) / 2);
    bottom:calc((100vh - <?php echo $img_h_tablet ?>) / 2);
  }
  #<?php echo $uid ?> .gss-media{top:<?php echo $sticky_top_t ?>;}
}
@media(max-width:767px){
  #<?php echo $uid ?>{
    display:block;
    padding:0;
  }
  #<?php echo $uid ?> .gss-card{display:flex;flex-direction:column;}
  #<?php echo $uid ?> .gss-media{
    order:0;grid-column:auto;grid-row:auto;
    position:sticky;top:<?php echo $sticky_top_m ?>;
    height:<?php echo $img_h_mobile ?>;
    align-self:stretch;z-index:50;
  }
  #<?php echo $uid ?> .gss-content{order:1;grid-column:auto;grid-row:auto;}
  #<?php echo $uid ?> .gss-image{
    position:absolute;inset:0;height:100%;
  }
  #<?php echo $uid ?> .gss-image-inner{
    position:absolute;top:0;bottom:0;left:0;right:0;
  }
  #<?php echo $uid ?> .gss-item{
    min-height:<?php echo $item_h_mobile ?>;
    padding:<?php echo $image_text_m ?> 0 <?php echo $after_text_m ?>;
    margin-top:0!important;margin-bottom:0!important;
  }
  #<?php echo $uid ?> .gss-copy{gap:<?php echo $content_gap_m ?>;}
  #<?php echo $uid ?> .gss-item:last-child{
    min-height:auto;
    padding-bottom:<?php echo $last_item_m ?>;
  }
  #<?php echo $uid ?>.gss-mobile-stacked .gss-media{display:none;}
  #<?php echo $uid ?>.gss-mobile-stacked .gss-item{
    min-height:auto;
    justify-content:flex-start;
    padding:0 0 <?php echo $after_text_m ?>;
    opacity:1!important;
  }
  #<?php echo $uid ?>.gss-mobile-stacked .gss-stack-image{
    display:block;width:100%;height:<?php echo $img_h_mobile ?>;
    flex:0 0 auto;margin-bottom:<?php echo $image_text_m ?>;
    background-size:cover;background-position:center;background-repeat:no-repeat;
    border-radius:<?php echo $radius ?>;
  }
  #<?php echo $uid ?>.gss-mobile-stacked .gss-item:last-child{
    padding-bottom:<?php echo $last_item_m ?>;
  }
}
</style>

        <?php /* ── HTML ───────────────────────────────────────────────── */ ?>
<section id="<?php echo $uid ?>" class="gss-root<?php echo $mobile_mode === 'stacked' ? ' gss-mobile-stacked' : '' ?>" data-pe="<?php echo $data_pe ?>">
  <div class="gss-card">
    <div class="gss-media" aria-hidden="true">
<?php foreach ( $items as $i => $item ) :
    $img_url  = $item['image']['url'] ?? '';
    $clip     = $i === 0 ? 'inset(0)' : esc_attr( $initial_clip );
    $z        = 10 + $i;
?>
      <div class="gss-image" data-index="<?php echo $i ?>"
           style="z-index:<?php echo $z ?>;opacity:1;clip-path:<?php echo $clip ?>;">
        <?php if ( $img_url ) : ?>
        <div class="gss-image-inner"
             style="background-image:url('<?php echo esc_url( $img_url ) ?>')"></div>
        <?php endif ?>
      </div>
<?php endforeach ?>
    </div>

    <div class="gss-content">
<?php foreach ( $items as $i => $item ) :
    $img_url  = $item['image']['url'] ?? '';
    $btn_url  = $item['btn_url']['url'] ?? '#';
    $btn_ext  = ! empty( $item['btn_url']['is_external'] ) ? ' target="_blank" rel="noopener"' : '';
    $btn_nf   = ! empty( $item['btn_url']['nofollow']    ) ? ' rel="nofollow"' : '';
    $tag      = in_array( $item['title_tag'] ?? '', ['h1','h2','h3','h4','p'] )
                ? $item['title_tag'] : 'h2';
    $item_op  = ( ! $dim || $i === 0 ) ? '1' : esc_attr( (string) $iop );
    $mt       = $i === 0      ? "margin-top:{$margin};"    : '';
    $mb       = $i === $N - 1 ? "margin-bottom:{$margin};" : '';
?>
    <div class="gss-item" data-index="<?php echo $i ?>"
         style="opacity:<?php echo $item_op ?>;<?php echo $mt . $mb ?>">
      <?php if ( $img_url ) : ?>
      <div class="gss-stack-image" aria-hidden="true"
           style="background-image:url('<?php echo esc_url( $img_url ) ?>')"></div>
      <?php endif ?>

      <div class="gss-copy">
      <?php if ( ! empty( $item['eyebrow'] ) ) : ?>
      <span class="gss-eyebrow"><?php echo esc_html( $item['eyebrow'] ) ?></span>
      <?php endif ?>

      <?php if ( ! empty( $item['title'] ) ) : ?>
      <<?php echo $tag ?> class="gss-title">
        <?php echo esc_html( $item['title'] ) ?>
      </<?php echo $tag ?>>
      <?php endif ?>

      <?php if ( ! empty( $item['description'] ) ) : ?>
      <p class="gss-desc"><?php echo wp_kses_post( $item['description'] ) ?></p>
      <?php endif ?>

      <?php
      $has_btn_text = ! empty( $item['btn_text'] );
      $has_btn_icon = ! empty( $item['btn_icon']['value'] );
      $icon_pos     = $item['btn_icon_position'] ?? 'after';
      if ( $has_btn_text || $has_btn_icon ) : ?>
      <div class="gss-btn-wrap">
        <a class="gss-btn" href="<?php echo esc_url( $btn_url ) ?>"<?php echo $btn_ext . $btn_nf ?>>
          <?php if ( $has_btn_icon && $icon_pos === 'before' ) : ?>
          <span class="gss-btn-icon"><?php \Elementor\Icons_Manager::render_icon( $item['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
          <?php endif ?>
          <?php if ( $has_btn_text ) : ?>
          <span class="gss-btn-text"><?php echo esc_html( $item['btn_text'] ) ?></span>
          <?php endif ?>
          <?php if ( $has_btn_icon && $icon_pos !== 'before' ) : ?>
          <span class="gss-btn-icon"><?php \Elementor\Icons_Manager::render_icon( $item['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
          <?php endif ?>
        </a>
      </div>
      <?php endif ?>
      </div>
    </div>
<?php endforeach ?>
    </div>
  </div>
</section>

        <?php /* ── JS (sin dependencias externas) ─────────────────────── */ ?>
<script>
(function(){
  'use strict';

  function clamp01(v){ return v<0?0:v>1?1:v; }
  function ease(t){ return t<0.5?4*t*t*t:1-Math.pow(-2*t+2,3)/2; }

  var root   = document.getElementById('<?php echo esc_js($uid) ?>');
  if(!root) return;
  var items  = Array.from(root.querySelectorAll('.gss-item'));
  var images = Array.from(root.querySelectorAll('.gss-image'));
  var media  = root.querySelector('.gss-media');
  var N = items.length;

  var cfg={};
  try{ cfg=JSON.parse(root.dataset.pe||'{}'); }catch(e){}
  var tr   = cfg.transition      || 'reveal-down';
  var trig = typeof cfg.trigger==='number' ? cfg.trigger : 0.2;
  var dim  = !!cfg.dimInactive;
  var iop  = typeof cfg.inactiveOpacity==='number' ? cfg.inactiveOpacity : 0.3;

  // El editor no siempre desplaza el mismo documento que el iframe. Mostramos
  // todos los textos con su color real y la primera imagen como vista previa.
  if(typeof elementorFrontend!=='undefined'
     && elementorFrontend.isEditMode
     && elementorFrontend.isEditMode()){
    items.forEach(function(item){ item.style.opacity='1'; });
    images.forEach(function(im,index){
      im.style.opacity=index===0?'1':'0';
      im.style.clipPath=index===0?'inset(0)':'inset(100% 0 0 0)';
    });
    root.classList.add('gss-ready');
    return;
  }

  if(N<2){
    images.forEach(function(im){ im.style.clipPath='inset(0)'; im.style.opacity='1'; });
    items.forEach(function(item){ item.style.opacity='1'; });
    root.classList.add('gss-ready');
    return;
  }

  // Algunos wrappers intermedios de Elementor aplican overflow y desactivan
  // position:sticky. Sólo corregimos esos wrappers: html y body deben conservar
  // el scroll de la página para no interferir con footers fijos o revelados.
  var stickyContextsPrepared=false;
  function prepareStickyAncestors(){
    var ancestor=root.parentElement;
    while(ancestor){
      var style=window.getComputedStyle(ancestor);
      var overflow=[style.overflow,style.overflowX,style.overflowY].join(' ');
      var isPageRoot=ancestor===document.body||ancestor===document.documentElement;
      if(!isPageRoot&&/(auto|scroll|hidden|clip|overlay)/.test(overflow)){
        ancestor.classList.add('gss-sticky-context');
      }
      if(ancestor===document.documentElement) break;
      ancestor=ancestor.parentElement;
    }
    stickyContextsPrepared=true;
  }

  // Estado inicial
  images[0].style.clipPath='inset(0)'; images[0].style.opacity='1';
  for(var i=1;i<N;i++){
    if(tr==='crossfade'){
      images[i].style.opacity='0'; images[i].style.clipPath='inset(0)';
    } else {
      images[i].style.opacity='1';
      images[i].style.clipPath = tr==='reveal-up' ? 'inset(0 0 100% 0)' : 'inset(100% 0 0 0)';
    }
  }
  items.forEach(function(el,i){ el.style.opacity=!dim||i===0?'1':String(iop); });
  root.classList.add('gss-ready');

  // Loop de scroll (rAF throttle)
  var pending=false;
  function tick(){
    pending=false;
    var vh=window.innerHeight, trigY=vh*trig;
    var mobileMode=window.matchMedia('(max-width:767px)').matches;
    if(mobileMode && root.classList.contains('gss-mobile-stacked')){
      if(media) media.style.transform='';
      items.forEach(function(item){ item.style.opacity='1'; });
      return;
    }
    if(!stickyContextsPrepared) prepareStickyAncestors();
    if(mobileMode && media){
      // Medimos sin el desplazamiento aplicado en el frame anterior para que
      // la liberación final avance de forma estable, sin saltos ni rebotes.
      media.style.transform='';
      var mediaRect=media.getBoundingClientRect();
      var visibleBottom=Math.max(0,Math.min(vh,mediaRect.bottom));
      trigY=Math.max(trigY,visibleBottom+(vh-visibleBottom)*trig);

      // Al entrar el último texto bajo la imagen, ambos suben juntos. Así la
      // imagen deja de estar visualmente sticky antes de que el texto termine.
      var stickyTop=parseFloat(window.getComputedStyle(media).top)||0;
      var lastRect=items[N-1].getBoundingClientRect();
      var releaseOffset=0;
      if(mediaRect.top<=stickyTop+1){
        releaseOffset=Math.max(0,Math.min(media.offsetHeight+stickyTop,mediaRect.bottom-lastRect.top));
      }
      media.style.transform='translate3d(0,'+(-releaseOffset).toFixed(2)+'px,0)';
    }else if(media){
      media.style.transform='';
    }
    var rev=new Array(N+1); rev[0]=1; rev[N]=0;
    for(var i=1;i<N;i++){
      var r=items[i].getBoundingClientRect();
      rev[i]=ease(clamp01((trigY+r.height-r.top)/r.height));
    }
    // Imagen 0 siempre visible
    images[0].style.clipPath='inset(0)'; images[0].style.opacity='1';
    // Imágenes 1..N-1
    for(var j=1;j<N;j++){
      if(tr==='crossfade'){
        images[j].style.opacity=rev[j].toFixed(3);
        images[j].style.clipPath='inset(0)';
      } else if(tr==='reveal-up'){
        images[j].style.clipPath='inset(0 0 '+((1-rev[j])*100).toFixed(2)+'% 0)';
        images[j].style.opacity='1';
      } else { // reveal-down (default)
        images[j].style.clipPath='inset('+((1-rev[j])*100).toFixed(2)+'% 0 0 0)';
        images[j].style.opacity='1';
      }
    }
    // Atenuar texto inactivo
    if(dim){
      var act=0;
      for(var k=N-1;k>=1;k--){ if(rev[k]>0.5){ act=k; break; } }
      items.forEach(function(el,idx){ el.style.opacity=idx===act?'1':String(iop); });
    }
  }
  var onScroll=function(){ if(!pending){ pending=true; requestAnimationFrame(tick); } };
  // Escuchar en window, document Y body — cubre scroll nativo, body scroll y Lenis/GSAP
  window.addEventListener('scroll', onScroll, {passive:true});
  window.addEventListener('resize', onScroll, {passive:true});
  document.addEventListener('scroll', onScroll, {passive:true, capture:true});
  document.body.addEventListener('scroll', onScroll, {passive:true});
  tick(); // estado inicial
})();
</script>
<?php
    } // end render()
} // end class

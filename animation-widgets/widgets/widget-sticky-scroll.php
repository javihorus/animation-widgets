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
    public function get_categories()  { return [ 'general' ]; }
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

        $this->add_control( 'item_height', [
            'label'   => 'Altura mínima por ítem (vh)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 80 ],
            'range'   => [ 'px' => [ 'min' => 40, 'max' => 120 ] ],
        ] );

        $this->add_control( 'image_height', [
            'label'   => 'Altura de la imagen (vh)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 82 ],
            'range'   => [ 'px' => [ 'min' => 40, 'max' => 100 ] ],
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
        $iop        = ( $s['inactive_opacity']['size']    ?? 30  ) / 100;
        $split      = ( $s['col_split']['size']           ?? 48  ) . '%';
        $gap        = ( $s['col_gap']['size']             ?? 48  ) . 'px';
        $pad_x      = ( $s['pad_x']['size']               ?? 80  ) . 'px';
        $item_h     = ( $s['item_height']['size']         ?? 80  ) . 'vh';
        $img_h      = ( $s['image_height']['size']        ?? 82  ) . 'vh';
        $radius     = ( $s['image_radius']['size']        ?? 12  ) . 'px';

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
#<?php echo $uid ?>{
  display:grid;
  grid-template-columns:<?php echo $split ?> 1fr;
  grid-template-rows:repeat(<?php echo $N ?>,auto);
  column-gap:<?php echo $gap ?>;
  <?php if ( (int)$s['pad_x']['size'] > 0 ) echo "padding:0 {$pad_x};"; ?>
  width:100%;
  box-sizing:border-box;
}
#<?php echo $uid ?> .gss-card{display:contents}
#<?php echo $uid ?> .gss-item{
  grid-column:1;
  min-height:<?php echo $item_h ?>;
  display:flex;flex-direction:column;justify-content:center;gap:20px;
  padding:24px 0;
  transition:opacity .35s ease;
}
#<?php echo $uid ?> .gss-image{
  grid-column:2;
  grid-row:1/-1;
  position:sticky;top:0;height:100vh;
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
@media(max-width:767px){
  #<?php echo $uid ?>{
    grid-template-columns:1fr;
    grid-template-rows:none;
    padding:0;
  }
  #<?php echo $uid ?> .gss-image{
    grid-column:1;grid-row:auto;
    position:relative;height:60vw;
    clip-path:none!important;opacity:1!important;z-index:auto!important;
  }
  #<?php echo $uid ?> .gss-image-inner{
    position:absolute;top:0;bottom:0;left:0;right:0;
  }
  #<?php echo $uid ?> .gss-item{min-height:auto;padding:40px 0;}
}
</style>

        <?php /* ── HTML ───────────────────────────────────────────────── */ ?>
<section id="<?php echo $uid ?>" data-pe="<?php echo $data_pe ?>">
  <div class="gss-card">
<?php foreach ( $items as $i => $item ) :
    $img_url  = $item['image']['url'] ?? '';
    $btn_url  = $item['btn_url']['url'] ?? '#';
    $btn_ext  = ! empty( $item['btn_url']['is_external'] ) ? ' target="_blank" rel="noopener"' : '';
    $btn_nf   = ! empty( $item['btn_url']['nofollow']    ) ? ' rel="nofollow"' : '';
    $tag      = in_array( $item['title_tag'] ?? '', ['h1','h2','h3','h4','p'] )
                ? $item['title_tag'] : 'h2';
    $clip     = $i === 0 ? 'inset(0)' : esc_attr( $initial_clip );
    $z        = 10 + $i;
    $item_op  = $i === 0 ? '1' : esc_attr( (string) $iop );
    $mt       = $i === 0      ? "margin-top:{$margin};"    : '';
    $mb       = $i === $N - 1 ? "margin-bottom:{$margin};" : '';
?>
    <div class="gss-image" data-index="<?php echo $i ?>"
         style="z-index:<?php echo $z ?>;opacity:1;clip-path:<?php echo $clip ?>;">
      <?php if ( $img_url ) : ?>
      <div class="gss-image-inner"
           style="background-image:url('<?php echo esc_url( $img_url ) ?>')"></div>
      <?php endif ?>
    </div>

    <div class="gss-item" data-index="<?php echo $i ?>"
         style="opacity:<?php echo $item_op ?>;<?php echo $mt . $mb ?>">
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
<?php endforeach ?>
  </div>
</section>

        <?php /* ── JS (sin dependencias externas) ─────────────────────── */ ?>
<script>
(function(){
  'use strict';

  // En el editor de Elementor el scroll es del iframe, no del window.
  // El efecto sticky no funciona ahí, pero el widget es visible y editable.
  if(typeof elementorFrontend!=='undefined'
     && elementorFrontend.isEditMode
     && elementorFrontend.isEditMode()) return;

  function clamp01(v){ return v<0?0:v>1?1:v; }
  function ease(t){ return t<0.5?4*t*t*t:1-Math.pow(-2*t+2,3)/2; }

  var root   = document.getElementById('<?php echo esc_js($uid) ?>');
  if(!root) return;
  var items  = Array.from(root.querySelectorAll('.gss-item'));
  var images = Array.from(root.querySelectorAll('.gss-image'));
  var N = items.length;

  var cfg={};
  try{ cfg=JSON.parse(root.dataset.pe||'{}'); }catch(e){}
  var tr   = cfg.transition      || 'reveal-down';
  var trig = typeof cfg.trigger==='number' ? cfg.trigger : 0.2;
  var dim  = !!cfg.dimInactive;
  var iop  = typeof cfg.inactiveOpacity==='number' ? cfg.inactiveOpacity : 0.3;

  // En mobile dejamos el layout apilado sin animación
  if(window.matchMedia('(max-width:767px)').matches || N<2){
    images.forEach(function(im){ im.style.clipPath='inset(0)'; im.style.opacity='1'; });
    root.classList.add('gss-ready');
    return;
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
  if(dim) items.forEach(function(el,i){ el.style.opacity=i===0?'1':String(iop); });
  root.classList.add('gss-ready');

  // Loop de scroll (rAF throttle)
  var pending=false;
  function tick(){
    pending=false;
    var vh=window.innerHeight, trigY=vh*trig;
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
  document.addEventListener('scroll', onScroll, {passive:true, capture:true});
  document.body.addEventListener('scroll', onScroll, {passive:true});
  tick(); // estado inicial
})();
</script>
<?php
    } // end render()
} // end class

<?php
/**
 * Widget: Marquee Hero
 * Descripción: Hero a pantalla completa con carrusel infinito de imágenes en
 *              columnas de distinta altura + texto centrado con efecto reveal.
 *
 * @package Animation_Widgets
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class AW_Widget_Marquee_Hero extends \Elementor\Widget_Base {

    public function get_name()        { return 'aw-marquee-hero'; }
    public function get_title()       { return 'Marquee Hero'; }
    public function get_icon()        { return 'eicon-slider-full-screen'; }
    public function get_categories()  { return [ 'general' ]; }
    public function get_keywords()    { return [ 'marquee', 'hero', 'carrusel', 'banner', 'parallax', 'aw' ]; }

    // ─────────────────────────────────────────────────────────────────
    //  CONTROLES
    // ─────────────────────────────────────────────────────────────────
    protected function register_controls() {

        /* ── IMÁGENES ───────────────────────────────────────────── */
        $this->start_controls_section( 'section_images', [
            'label' => 'Imágenes',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $rep = new \Elementor\Repeater();

        $rep->add_control( 'image', [
            'label'   => 'Imagen',
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ] );

        $rep->add_control( 'size', [
            'label'   => 'Tamaño',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'small',
            'options' => [ 'small' => 'Pequeña', 'large' => 'Grande' ],
        ] );

        $rep->add_control( 'v_pos', [
            'label'   => 'Posición vertical (vh)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 42 ],
            'range'   => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
        ] );

        $this->add_control( 'images', [
            'label'       => 'Imágenes del carrusel',
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $rep->get_controls(),
            'default'     => [
                [ 'size' => 'small', 'v_pos' => [ 'size' => 42 ] ],
                [ 'size' => 'large', 'v_pos' => [ 'size' => 16 ] ],
                [ 'size' => 'small', 'v_pos' => [ 'size' => 50 ] ],
                [ 'size' => 'large', 'v_pos' => [ 'size' => 10 ] ],
            ],
            'title_field' => '{{ size === "large" ? "Grande" : "Pequeña" }} — {{ v_pos.size }}vh',
        ] );

        $this->end_controls_section();

        /* ── TÍTULO ─────────────────────────────────────────────── */
        $this->start_controls_section( 'section_heading', [
            'label' => 'Título',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'heading', [
            'label'       => 'Texto del título',
            'type'        => \Elementor\Controls_Manager::TEXTAREA,
            'default'     => 'Consultoría inmobiliaria y urbanística',
            'label_block' => true,
        ] );

        $this->add_control( 'heading_tag', [
            'label'   => 'Etiqueta',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'h1',
            'options' => [ 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'p' => 'Párrafo' ],
        ] );

        $this->add_control( 'heading_align', [
            'label'     => 'Alineación',
            'type'      => \Elementor\Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [ 'title' => 'Izquierda', 'icon' => 'eicon-text-align-left'   ],
                'center' => [ 'title' => 'Centro',    'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => 'Derecha',   'icon' => 'eicon-text-align-right'  ],
            ],
            'default'   => 'center',
            'selectors' => [ '{{WRAPPER}} .aw-mh-text' => 'justify-content: {{VALUE}}; text-align: {{VALUE}};' ],
        ] );

        $this->add_control( 'top_label_heading', [
            'label'     => 'Texto superior',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'top_label', [
            'label'       => 'Etiqueta superior',
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => '',
            'placeholder' => 'Ej: DESDE 1994',
            'label_block' => true,
        ] );

        $this->add_control( 'top_label_align', [
            'label'     => 'Alineación',
            'type'      => \Elementor\Controls_Manager::CHOOSE,
            'options'   => [
                'flex-start' => [ 'title' => 'Izquierda', 'icon' => 'eicon-text-align-left'   ],
                'center'     => [ 'title' => 'Centro',    'icon' => 'eicon-text-align-center' ],
                'flex-end'   => [ 'title' => 'Derecha',   'icon' => 'eicon-text-align-right'  ],
            ],
            'default'   => 'center',
            'condition' => [ 'top_label!' => '' ],
            'selectors' => [ '{{WRAPPER}} .aw-mh-top' => 'justify-content: {{VALUE}};' ],
        ] );

        $this->add_control( 'bottom_text_heading', [
            'label'     => 'Texto inferior',
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'bottom_text', [
            'label'       => 'Texto inferior',
            'type'        => \Elementor\Controls_Manager::TEXTAREA,
            'default'     => '',
            'placeholder' => 'Ej: Asesoramiento jurídico especializado...',
            'label_block' => true,
        ] );

        $this->add_control( 'bottom_text_align', [
            'label'     => 'Alineación',
            'type'      => \Elementor\Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [ 'title' => 'Izquierda', 'icon' => 'eicon-text-align-left'   ],
                'center' => [ 'title' => 'Centro',    'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => 'Derecha',   'icon' => 'eicon-text-align-right'  ],
            ],
            'default'   => 'center',
            'condition' => [ 'bottom_text!' => '' ],
            'selectors' => [ '{{WRAPPER}} .aw-mh-bottom' => 'text-align: {{VALUE}};' ],
        ] );

        $this->end_controls_section();

        /* ── AJUSTES DEL MARQUEE ────────────────────────────────── */
        $this->start_controls_section( 'section_marquee', [
            'label' => 'Marquee',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'speed', [
            'label'   => 'Velocidad (segundos por ciclo)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 45 ],
            'range'   => [ 'px' => [ 'min' => 5, 'max' => 120 ] ],
        ] );

        $this->add_control( 'separator', [
            'label'   => 'Separación entre imágenes (px)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 170 ],
            'range'   => [ 'px' => [ 'min' => 0, 'max' => 400 ] ],
        ] );

        $this->add_control( 'direction', [
            'label'   => 'Dirección',
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'left',
            'options' => [ 'left' => '← Izquierda', 'right' => '→ Derecha' ],
        ] );

        $this->add_control( 'pause_hover', [
            'label'        => 'Pausar al pasar el cursor',
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => 'Sí',
            'label_off'    => 'No',
            'return_value' => 'yes',
            'default'      => '',
        ] );

        $this->end_controls_section();

        /* ── TAMAÑOS DE IMAGEN ──────────────────────────────────── */
        $this->start_controls_section( 'section_sizes', [
            'label' => 'Tamaños de imagen',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'small_w', [
            'label'   => 'Ancho imagen pequeña (px)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 232 ],
            'range'   => [ 'px' => [ 'min' => 100, 'max' => 600 ] ],
        ] );
        $this->add_control( 'small_h', [
            'label'   => 'Alto imagen pequeña (px)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 330 ],
            'range'   => [ 'px' => [ 'min' => 100, 'max' => 800 ] ],
        ] );
        $this->add_control( 'large_w', [
            'label'   => 'Ancho imagen grande (px)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 400 ],
            'range'   => [ 'px' => [ 'min' => 100, 'max' => 800 ] ],
        ] );
        $this->add_control( 'large_h', [
            'label'   => 'Alto imagen grande (px)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 500 ],
            'range'   => [ 'px' => [ 'min' => 100, 'max' => 1000 ] ],
        ] );
        $this->add_control( 'img_brightness', [
            'label'   => 'Brillo de las imágenes (%)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 80 ],
            'range'   => [ 'px' => [ 'min' => 10, 'max' => 100 ] ],
        ] );
        $this->add_control( 'img_radius', [
            'label'   => 'Border radius imágenes (px)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 4 ],
            'range'   => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
        ] );

        $this->end_controls_section();

        /* ── ESTILO: FONDO ──────────────────────────────────────── */
        $this->start_controls_section( 'style_bg', [
            'label' => 'Fondo',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'hero_height', [
            'label'   => 'Altura del hero (vh)',
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'default' => [ 'size' => 100 ],
            'range'   => [ 'px' => [ 'min' => 30, 'max' => 100 ] ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Background::get_type(), [
            'name'     => 'hero_bg',
            'types'    => [ 'classic', 'gradient' ],
            'selector' => '{{WRAPPER}} .aw-mh-wrap',
        ] );
        $this->end_controls_section();

        /* ── ESTILO: TÍTULO ─────────────────────────────────────── */
        $this->start_controls_section( 'style_heading', [
            'label' => 'Título',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'heading_color', [
            'label'     => 'Color',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#fafafa',
            'selectors' => [ '{{WRAPPER}} .aw-mh-heading' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'heading_typo',
            'selector' => '{{WRAPPER}} .aw-mh-heading',
        ] );
        $this->add_control( 'blend_mode', [
            'label'     => 'Mix blend mode',
            'type'      => \Elementor\Controls_Manager::SELECT,
            'default'   => 'difference',
            'options'   => [
                'normal'     => 'Normal',
                'difference' => 'Difference (invierte sobre imágenes)',
                'overlay'    => 'Overlay',
                'multiply'   => 'Multiply',
                'screen'     => 'Screen',
                'exclusion'  => 'Exclusion',
            ],
            'selectors' => [ '{{WRAPPER}} .aw-mh-text' => 'mix-blend-mode: {{VALUE}};' ],
        ] );
        $this->add_control( 'heading_offset', [
            'label'       => 'Desplazamiento vertical del título',
            'description' => 'Valores negativos suben el título, positivos lo bajan.',
            'type'        => \Elementor\Controls_Manager::SLIDER,
            'size_units'  => [ 'px', 'vh' ],
            'default'     => [ 'size' => 0, 'unit' => 'px' ],
            'range'       => [ 'px' => [ 'min' => -300, 'max' => 300 ], 'vh' => [ 'min' => -30, 'max' => 30 ] ],
            'selectors'   => [ '{{WRAPPER}} .aw-mh-heading' => 'transform: translateY({{SIZE}}{{UNIT}});' ],
        ] );
        $this->add_control( 'heading_padding', [
            'label'      => 'Padding lateral del título',
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'vw' ],
            'default'    => [ 'top' => '0', 'right' => '40', 'bottom' => '0', 'left' => '40', 'unit' => 'px', 'isLinked' => false ],
            'selectors'  => [ '{{WRAPPER}} .aw-mh-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();

        /* ── ESTILO: ETIQUETA SUPERIOR ──────────────────────────── */
        $this->start_controls_section( 'style_top', [
            'label'     => 'Etiqueta superior',
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [ 'top_label!' => '' ],
        ] );
        $this->add_control( 'top_label_color', [
            'label'     => 'Color',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#fafafa',
            'selectors' => [ '{{WRAPPER}} .aw-mh-top' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'top_label_typo',
            'selector' => '{{WRAPPER}} .aw-mh-top',
        ] );
        $this->add_control( 'top_distance', [
            'label'       => 'Distancia desde arriba',
            'description' => 'Mueve la etiqueta hacia abajo (más cerca del título) o hacia arriba.',
            'type'        => \Elementor\Controls_Manager::SLIDER,
            'size_units'  => [ 'px', 'vh' ],
            'default'     => [ 'size' => 24, 'unit' => 'px' ],
            'range'       => [ 'px' => [ 'min' => 0, 'max' => 500 ], 'vh' => [ 'min' => 0, 'max' => 60 ] ],
            'selectors'   => [ '{{WRAPPER}} .aw-mh-top' => 'top: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_control( 'top_h_padding', [
            'label'      => 'Padding horizontal',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vw' ],
            'default'    => [ 'size' => 40, 'unit' => 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 200 ] ],
            'selectors'  => [ '{{WRAPPER}} .aw-mh-top' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();

        /* ── ESTILO: TEXTO INFERIOR ─────────────────────────────── */
        $this->start_controls_section( 'style_bottom', [
            'label'     => 'Texto inferior',
            'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [ 'bottom_text!' => '' ],
        ] );
        $this->add_control( 'bottom_text_color', [
            'label'     => 'Color',
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#fafafa',
            'selectors' => [ '{{WRAPPER}} .aw-mh-bottom' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'bottom_text_typo',
            'selector' => '{{WRAPPER}} .aw-mh-bottom',
        ] );
        $this->add_control( 'bottom_max_width', [
            'label'      => 'Ancho máximo del texto',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'default'    => [ 'size' => 560, 'unit' => 'px' ],
            'range'      => [ 'px' => [ 'min' => 200, 'max' => 1200 ], '%' => [ 'min' => 20, 'max' => 100 ] ],
            'selectors'  => [ '{{WRAPPER}} .aw-mh-bottom p' => 'max-width: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_control( 'bottom_distance', [
            'label'       => 'Distancia desde abajo',
            'description' => 'Mueve el texto hacia arriba (más cerca del título) o hacia abajo.',
            'type'        => \Elementor\Controls_Manager::SLIDER,
            'size_units'  => [ 'px', 'vh' ],
            'default'     => [ 'size' => 40, 'unit' => 'px' ],
            'range'       => [ 'px' => [ 'min' => 0, 'max' => 500 ], 'vh' => [ 'min' => 0, 'max' => 60 ] ],
            'selectors'   => [ '{{WRAPPER}} .aw-mh-bottom' => 'bottom: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_control( 'bottom_h_padding', [
            'label'      => 'Padding horizontal',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'vw' ],
            'default'    => [ 'size' => 40, 'unit' => 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 200 ] ],
            'selectors'  => [ '{{WRAPPER}} .aw-mh-bottom' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->end_controls_section();
    }

    // ─────────────────────────────────────────────────────────────────
    //  RENDER
    // ─────────────────────────────────────────────────────────────────
    protected function render() {
        $s       = $this->get_settings_for_display();
        $images  = $s['images'] ?? [];
        if ( empty( $images ) ) return;

        $uid      = 'aw-mh-' . $this->get_id();
        $tag      = in_array( $s['heading_tag'] ?? '', ['h1','h2','h3','h4','p'] ) ? $s['heading_tag'] : 'h1';
        $speed    = ( $s['speed']['size']      ?? 45  ) . 's';
        $sep_w    = ( $s['separator']['size']  ?? 170 ) . 'px';
        $small_w  = ( $s['small_w']['size']    ?? 232 ) . 'px';
        $small_h  = ( $s['small_h']['size']    ?? 330 ) . 'px';
        $large_w  = ( $s['large_w']['size']    ?? 400 ) . 'px';
        $large_h  = ( $s['large_h']['size']    ?? 500 ) . 'px';
        $bright   = ( $s['img_brightness']['size'] ?? 80 ) / 100;
        $radius   = ( $s['img_radius']['size'] ?? 4   ) . 'px';
        $height   = ( $s['hero_height']['size'] ?? 100 ) . 'vh';
        $dir        = ( $s['direction'] ?? 'left' ) === 'right' ? 'animation-direction:reverse;' : '';
        $pause      = ( $s['pause_hover'] ?? '' ) === 'yes';
        $kf_name    = 'aw-mh-move-' . $this->get_id();
        $top_label  = $s['top_label']  ?? '';
        $bottom_txt = $s['bottom_text'] ?? '';

        // Construir un grupo de imágenes (se duplica para el loop infinito)
        ob_start();
        foreach ( $images as $item ) :
            $url   = $item['image']['url'] ?? '';
            $sz    = ( $item['size'] ?? 'small' ) === 'large' ? 'large' : 'small';
            $vp    = ( $item['v_pos']['size'] ?? 42 ) . 'vh';
        ?>
<div class="aw-mh-sep"></div>
<div class="aw-mh-img aw-mh-img-<?php echo $sz ?>" style="margin-top:<?php echo $vp ?>">
  <?php if ( $url ) : ?><img src="<?php echo esc_url( $url ) ?>" alt="" loading="lazy"><?php endif ?>
</div>
        <?php endforeach;
        $group = ob_get_clean();

        ?>
<style>
#<?php echo $uid ?>{position:relative;width:100%;height:<?php echo $height ?>;overflow:hidden;}
#<?php echo $uid ?> .aw-mh-marquee{position:absolute;inset:0;overflow:hidden;z-index:1;}
#<?php echo $uid ?> .aw-mh-track{
  display:flex;align-items:flex-start;width:max-content;height:100%;
  animation:<?php echo $kf_name ?> <?php echo $speed ?> linear infinite;
  <?php echo $dir ?>
}
<?php if ( $pause ) : ?>
#<?php echo $uid ?>:hover .aw-mh-track{animation-play-state:paused;}
<?php endif ?>
#<?php echo $uid ?> .aw-mh-sep{width:<?php echo $sep_w ?>;flex-shrink:0;}
#<?php echo $uid ?> .aw-mh-img{
  position:relative;flex-shrink:0;overflow:hidden;
  border-radius:<?php echo $radius ?>;filter:brightness(<?php echo $bright ?>);
}
#<?php echo $uid ?> .aw-mh-img img{width:100%;height:100%;display:block;object-fit:cover;}
#<?php echo $uid ?> .aw-mh-img-small{width:<?php echo $small_w ?>;height:<?php echo $small_h ?>;}
#<?php echo $uid ?> .aw-mh-img-large{width:<?php echo $large_w ?>;height:<?php echo $large_h ?>;}
/* Overlay ─ sólo estructura; color/padding/posición los gestiona Elementor */
#<?php echo $uid ?> .aw-mh-overlay{position:absolute;inset:0;z-index:10;pointer-events:none;}

/* Etiqueta superior: layout estructural — top/padding/color/align → Elementor */
#<?php echo $uid ?> .aw-mh-top{
  position:absolute;left:0;right:0;
  display:flex;align-items:center;
  animation:aw-mh-reveal 1s cubic-bezier(.22,1,.36,1) forwards;
}

/* Título: siempre centrado — translateY/padding/color/align/blend → Elementor */
#<?php echo $uid ?> .aw-mh-text{
  position:absolute;inset:0;
  display:flex;align-items:center;
  animation:aw-mh-reveal 1.4s cubic-bezier(.22,1,.36,1) forwards;
}
#<?php echo $uid ?> .aw-mh-heading{
  max-width:1000px;margin:0;
  font-size:clamp(48px,8vw,120px);line-height:.95;
  letter-spacing:-0.065em;text-wrap:balance;font-weight:400;
}

/* Texto inferior: layout estructural — bottom/padding/color/align → Elementor */
#<?php echo $uid ?> .aw-mh-bottom{
  position:absolute;left:0;right:0;
  display:flex;flex-direction:column;align-items:center;
  animation:aw-mh-reveal 1.8s cubic-bezier(.22,1,.36,1) forwards;
}
#<?php echo $uid ?> .aw-mh-bottom p{margin:0;}
@keyframes <?php echo $kf_name ?>{
  from{transform:translateX(0);}
  to{transform:translateX(-50%);}
}
@keyframes aw-mh-reveal{
  0%{opacity:0;transform:perspective(1200px) translateY(30px) scale(.96);}
  100%{opacity:1;transform:perspective(1200px) translateY(0) scale(1);}
}
@media(max-width:767px){
  #<?php echo $uid ?> .aw-mh-sep{width:80px;}
  #<?php echo $uid ?> .aw-mh-img-small{width:150px;height:220px;}
  #<?php echo $uid ?> .aw-mh-img-large{width:250px;height:320px;}
}
</style>

<div id="<?php echo $uid ?>" class="aw-mh-wrap">

  <div class="aw-mh-marquee">
    <div class="aw-mh-track">
      <?php echo $group /* original */ ?>
      <?php echo $group /* duplicado para loop sin corte */ ?>
    </div>
  </div>

  <div class="aw-mh-overlay">

    <?php if ( $top_label ) : ?>
    <div class="aw-mh-top">
      <span><?php echo esc_html( $top_label ) ?></span>
    </div>
    <?php endif ?>

    <?php if ( ! empty( $s['heading'] ) ) : ?>
    <div class="aw-mh-text">
      <<?php echo $tag ?> class="aw-mh-heading">
        <?php echo wp_kses_post( $s['heading'] ) ?>
      </<?php echo $tag ?>>
    </div>
    <?php endif ?>

    <?php if ( $bottom_txt ) : ?>
    <div class="aw-mh-bottom">
      <p><?php echo wp_kses_post( $bottom_txt ) ?></p>
    </div>
    <?php endif ?>

  </div>

</div>
<?php
    } // end render()
} // end class

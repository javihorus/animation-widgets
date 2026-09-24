=== Animation Widgets ===
Contributors: javihorus
Tags: elementor, animation, roulette, carousel, testimonials, gallery
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.15.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Seis widgets de animación configurables para Elementor Free.

== Description ==

= Widgets existentes conservados =

* Sticky Scroll.
* Marquee Hero.

= Ruleta interactiva =

* Imagen principal y selector personalizados.
* Posición, tamaño y desplazamiento ajustables para el selector.
* Resultados ilimitados con ángulo manual y texto completo.
* Duración y número de vueltas configurables.
* Controles completos para el botón y el texto del resultado.
* Cuatro animaciones de aparición del resultado.

= Galería horizontal con scroll =

* Número ilimitado de imágenes.
* Texto, pie de foto y enlace opcionales por tarjeta.
* Recorrido horizontal en ambos sentidos.
* Longitud del tramo de scroll configurable.
* Opción de ancho completo.
* Escena de pantalla completa con título superior y texto inferior opcionales.
* Tamaño, separación, proporción y estilo responsive.
* Modo táctil automático en móvil.

= Carrusel de testimonios =

* Testimonios ilimitados con nombre y procedencia opcional.
* Fundido automático, bucle infinito y reanudación tras interactuar.
* Navegación táctil, flechas y puntos configurables.
* Tipografías, colores, tamaños y espaciados personalizables.

= Carrusel de programas =

* Programas ilimitados con imagen, nombre, descripción, botón y enlace.
* Movimiento continuo e infinito con velocidad y dirección configurables.
* Información superpuesta en hover y apertura mediante toque en móvil.
* Arrastre táctil, flechas y puntos opcionales, con reanudación automática.

== Installation ==

1. Instala y activa Elementor.
2. Sube el archivo ZIP desde Plugins > Añadir plugin > Subir plugin.
3. Activa Animation Widgets.
4. Busca la categoría "Animation Widgets" en el editor de Elementor.

== Changelog ==

= 1.15.2 =
* Toda la información superpuesta del Carrusel de programas abre ahora el enlace con un solo clic.
* La apertura funciona también en enlaces configurados para una ventana nueva.
* El arrastre horizontal sigue bloqueando clics accidentales sin interferir con los enlaces.

= 1.15.1 =
* Corregidos los botones del Carrusel de programas: un clic normal vuelve a abrir la URL configurada.
* El arrastre horizontal sigue bloqueando clics accidentales sin interferir con los enlaces.

= 1.15 =
* La altura de Marquee Hero se puede ajustar por separado en escritorio, tablet y móvil.
* Sticky Scroll ya no modifica el scroll global de html o body, manteniendo compatibles los footers fijos o con efecto reveal.
* Añadida la opción móvil "Imagen + texto, sin animación" al widget Sticky Scroll.
* El modo alternativo muestra cada fotografía seguida de su contenido y desactiva el atenuado.
* El último ítem de Sticky Scroll se ajusta a su contenido en móvil para eliminar el espacio inferior sobrante.
* Añadido un control responsive para el espacio final del último ítem.
* La imagen de Sticky Scroll se libera automáticamente y sube junto al último texto en móvil.
* Aclarado el control responsive de separación superior de la imagen sticky.
* Sticky Scroll conserva en móvil la imagen fijada y la transición entre imágenes durante el desplazamiento.
* El punto de activación móvil se adapta al espacio visible debajo de la imagen.
* En el editor de Elementor todos los textos muestran su color real, sin atenuado incorrecto.
* Añadido un control responsive para separar la imagen fija de una cabecera superior.

= 1.13.5 =
* Añadidos controles responsive de altura y separación al widget Sticky Scroll.
* Eliminados en móvil los márgenes del modo sticky que alejaban las imágenes de sus textos.

= 1.13.4 =
* Corregido el modo Difference del título de Marquee Hero para que mezcle con las imágenes.
* El control de tipografía del título vuelve a aplicar correctamente el tamaño responsive.

= 1.13.3 =
* Sticky Scroll y Marquee Hero aparecen ahora dentro de la categoría Animation Widgets de Elementor.

= 1.13.2 =
* Eliminado el subrayado impuesto por algunos temas en los botones de programas.
* Aislado el tamaño de los puntos frente a los estilos globales de botones.
* Añadidos controles responsive para tamaño y separación de los puntos.

= 1.13.1 =
* La imagen de cada programa cubre ahora toda la tarjeta de forma robusta.
* Añadida numeración o etiqueta superior configurable y automática.
* Añadido el botón visible antes de interactuar con la tarjeta.
* El botón inicial y el botón superpuesto se muestran incluso sin enlace.

= 1.13.0 =
* Añadido el Carrusel de testimonios configurable con fundido y control táctil.
* Añadido el Carrusel de programas continuo con capa informativa interactiva.
* Incorporadas navegación opcional, pausa y reanudación automática en ambos widgets.

= 1.12.5 =
* Añadida una escena 100vh que reparte el espacio entre título, galería y texto inferior.
* Incorporados campos y controles de estilo para los textos fijos superior e inferior.
* Conservado como alternativa el modo de altura natural introducido en 1.12.4.

= 1.12.4 =
* El bloque visible adopta automáticamente la altura real de las imágenes.
* La distancia de scroll queda separada de la altura visual del contenido.
* Eliminada la obligación de ocupar 100vh en escritorio.

= 1.12.3 =
* Añadido ajuste automático de las imágenes al alto visible de la galería.
* Eliminados los huecos superior e inferior en el modo fijado.
* Los textos opcionales se muestran superpuestos sin aumentar la altura.
* Corregida la aplicación residual de alturas personalizadas ocultas.

= 1.12.2 =
* Corregida la fijación cuando el tema aplica overflow al documento completo.
* Añadida gestión segura para varias galerías en una misma página.

= 1.12.1 =
* Corregida la fijación de la galería dentro de contenedores Elementor con overflow.
* Añadida una altura de respaldo mientras se inicializa el cálculo del recorrido.

= 1.12.0 =
* Conservados Sticky Scroll y Marquee Hero de la versión 1.11.0.
* Añadidos Ruleta interactiva y Galería horizontal con scroll.

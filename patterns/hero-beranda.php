<?php
/**
 * Title: Beranda: Hero
 * Slug: pawpaw/hero-beranda
 * Categories: pawpaw
 * Description: Judul besar, tombol, dan dua foto hewan.
 * Inserter: yes
 *
 * @package PawPaw
 */
?>
<!-- wp:group {"tagName":"section","className":"container hero","layout":{"type":"default"}} -->
<section class="wp-block-group container hero"><!-- wp:group {"className":"hero__text","layout":{"type":"default"}} -->
<div class="wp-block-group hero__text"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">🐾 Petshop &amp; grooming di Bandung</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Kebutuhan <mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color">Si Gemas</mark>, Semua Ada di Sini</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"lead"} -->
<p class="lead">Makanan, camilan, mainan, sampai grooming untuk kucing dan anjing kesayanganmu. Tanya-tanya dan booking cukup lewat WhatsApp.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/produk/' ) ); ?>">Lihat Produk</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-biru"} -->
<div class="wp-block-button is-style-biru"><a class="wp-block-button__link wp-element-button" href="#wa-grooming">Booking Grooming</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"hero__art","layout":{"type":"default"}} -->
<div class="wp-block-group hero__art"><!-- wp:image {"className": "photo photo--cat"} -->
<figure class="wp-block-image photo photo--cat"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/cat.svg" alt="Ilustrasi kucing"/></figure>
<!-- /wp:image -->

<!-- wp:image {"className": "photo photo--dog"} -->
<figure class="wp-block-image photo photo--dog"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/img/dog.svg" alt="Ilustrasi anjing"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

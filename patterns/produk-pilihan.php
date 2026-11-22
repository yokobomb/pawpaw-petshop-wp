<?php
/**
 * Title: Beranda: Produk pilihan
 * Slug: pawpaw/produk-pilihan
 * Categories: pawpaw
 * Description: Panel oranye berisi produk pilihan.
 * Inserter: yes
 *
 * @package PawPaw
 */
?>
<!-- wp:group {"tagName":"section","className":"panel panel--orange has-paw","layout":{"type":"default"}} -->
<section class="wp-block-group panel panel--orange has-paw"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"section-head","layout":{"type":"default"}} -->
<div class="wp-block-group section-head"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Produk pilihan</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Biar Si Gemas Makin Happy</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Beberapa contoh produk di toko. Harga dan ketersediaan bisa ditanyakan lewat WhatsApp.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:pawpaw/produk-pilihan {"jumlah":4} /-->

<!-- wp:buttons {"className":"center","layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons center"><!-- wp:button {"className":"is-style-putih"} -->
<div class="wp-block-button is-style-putih"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/produk/' ) ); ?>">Lihat Semua Produk</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

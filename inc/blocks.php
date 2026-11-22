<?php
/**
 * Blok khusus PawPaw. Isinya diambil otomatis dari Produk, Layanan,
 * Kategori, dan Pengaturan Toko, jadi cukup diedit di satu tempat.
 *
 * @package PawPaw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Daftar blok: nama => [judul, deskripsi, ikon, atribut].
 */
function pawpaw_block_list() {
	return array(
		'logo'           => array( 'Logo PawPaw', 'Logo dan nama toko. Ganti logo lewat Tampilan > Editor > Header, atau Pengaturan > Umum untuk nama.', 'pets', array() ),
		'kategori'       => array( 'Kartu Kategori Produk', 'Kartu semua kategori. Diedit di Produk > Kategori.', 'category', array() ),
		'produk-pilihan' => array( 'Produk Pilihan', 'Produk yang dicentang "Produk pilihan". Diedit di menu Produk.', 'star-filled', array( 'jumlah' => array( 'type' => 'number', 'default' => 4 ) ) ),
		'katalog'        => array( 'Katalog Produk + Filter', 'Semua produk per kategori dengan tombol filter. Diedit di menu Produk.', 'cart', array() ),
		'layanan'        => array( 'Daftar Layanan Grooming', 'Kartu layanan grooming. Diedit di menu Layanan Grooming.', 'heart', array() ),
		'alamat'         => array( 'Alamat Toko', 'Diambil dari Pengaturan Toko.', 'location', array() ),
		'jam-buka'       => array( 'Jam Buka', 'Diambil dari Pengaturan Toko.', 'clock', array() ),
		'nomor-wa'       => array( 'Nomor WhatsApp', 'Diambil dari Pengaturan Toko.', 'phone', array( 'label' => array( 'type' => 'boolean', 'default' => false ) ) ),
		'form-wa'        => array( 'Form Kirim ke WhatsApp', 'Form singkat yang membuka WhatsApp dengan pesan tersusun.', 'email', array() ),
		'peta'           => array( 'Peta Lokasi', 'Peta Google Maps dari Pengaturan Toko.', 'location-alt', array() ),
		'wa-melayang'    => array( 'Tombol WhatsApp Melayang', 'Tombol "Chat WA" di pojok kanan bawah layar.', 'format-chat', array() ),
		'hak-cipta'      => array( 'Teks Hak Cipta', 'Tahun dan nama toko, otomatis.', 'editor-paragraph', array() ),
	);
}

function pawpaw_register_blocks() {
	wp_register_script(
		'pawpaw-editor',
		PAWPAW_URI . '/assets/js/editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
		PAWPAW_VERSION,
		true
	);

	$for_js = array();
	foreach ( pawpaw_block_list() as $slug => $b ) {
		$attrs = $b[3];
		register_block_type( 'pawpaw/' . $slug, array(
			'api_version'     => 3,
			'title'           => $b[0],
			'description'     => $b[1],
			'category'        => 'pawpaw',
			'icon'            => $b[2],
			'attributes'      => $attrs,
			'supports'        => array( 'html' => false ),
			'editor_script'   => 'pawpaw-editor',
			'render_callback' => 'pawpaw_render_' . str_replace( '-', '_', $slug ),
		) );
		$for_js[ $slug ] = array(
			'title'       => $b[0],
			'description' => $b[1],
			'icon'        => $b[2],
			'attributes'  => $attrs,
		);
	}
	wp_add_inline_script( 'pawpaw-editor', 'window.PAWPAW_BLOCKS = ' . wp_json_encode( $for_js ) . ';', 'before' );
}
add_action( 'init', 'pawpaw_register_blocks' );

function pawpaw_block_category( $cats ) {
	array_unshift( $cats, array( 'slug' => 'pawpaw', 'title' => 'PawPaw Petshop', 'icon' => null ) );
	return $cats;
}
add_filter( 'block_categories_all', 'pawpaw_block_category' );

/* ------------------------------------------------------------------
 * Potongan HTML yang dipakai berulang
 * ------------------------------------------------------------------ */

function pawpaw_paw_icon() {
	return '<svg viewBox="0 0 64 64" width="26" height="26" aria-hidden="true"><g fill="currentColor">'
		. '<ellipse cx="32" cy="42" rx="14" ry="11"/><circle cx="14" cy="28" r="6.5"/><circle cx="26" cy="16" r="6.5"/>'
		. '<circle cx="42" cy="16" r="6.5"/><circle cx="54" cy="28" r="6.5"/></g></svg>';
}

function pawpaw_kosong( $pesan ) {
	return '<p class="pawpaw-kosong">' . esc_html( $pesan ) . '</p>';
}

function pawpaw_kartu_produk( $post ) {
	$nama  = get_the_title( $post );
	$hewan = get_post_meta( $post->ID, 'pawpaw_hewan', true );
	$desk  = get_post_meta( $post->ID, 'pawpaw_desk', true );
	$harga = get_post_meta( $post->ID, 'pawpaw_harga', true );
	$emoji = get_post_meta( $post->ID, 'pawpaw_emoji', true );
	$msg   = 'Halo PawPaw Petshop! Aku mau tanya produk: ' . wp_strip_all_tags( $nama ) . '. Apakah ready? 🐾';

	$img = '';
	if ( has_post_thumbnail( $post ) ) {
		$img = get_the_post_thumbnail( $post, 'medium_large', array( 'alt' => esc_attr( wp_strip_all_tags( $nama ) ), 'loading' => 'lazy' ) );
	}

	$html  = '<article class="card product">';
	$html .= '<div class="card__img">' . ( $img ? $img : '<span class="emoji" aria-hidden="true">' . esc_html( $emoji ? $emoji : '🐾' ) . '</span>' ) . '</div>';
	$html .= '<div class="card__body">';
	if ( $hewan ) {
		$html .= '<span class="tag">' . esc_html( $hewan ) . '</span>';
	}
	$html .= '<h3>' . esc_html( $nama ) . '</h3>';
	if ( $desk ) {
		$html .= '<p>' . esc_html( $desk ) . '</p>';
	}
	if ( $harga ) {
		$html .= '<p class="price">' . esc_html( $harga ) . '</p>';
	}
	$html .= '<a class="btn btn--block btn--blue" target="_blank" rel="noopener" href="' . esc_url( pawpaw_wa_url( $msg ) ) . '">Tanya Produk via WhatsApp</a>';
	$html .= '</div></article>';
	return $html;
}

function pawpaw_query_produk( $args = array() ) {
	return get_posts( array_merge( array(
		'post_type'      => 'pawpaw_produk',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
	), $args ) );
}

/* ------------------------------------------------------------------
 * Render tiap blok
 * ------------------------------------------------------------------ */

function pawpaw_render_logo() {
	$name = get_bloginfo( 'name' );
	$html = '<a class="logo" href="' . esc_url( home_url( '/' ) ) . '" aria-label="' . esc_attr( $name . ', ke Beranda' ) . '">';
	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$html .= wp_get_attachment_image( $logo_id, 'medium', false, array( 'class' => 'logo__img', 'alt' => esc_attr( $name ) ) );
	} else {
		// "PawPaw Petshop" jadi "PawPaw" besar + "Petshop" kecil.
		$words = preg_split( '/\s+/', trim( $name ) );
		$big   = array_shift( $words );
		$small = implode( ' ', $words );
		$html .= '<span class="logo__mark">' . pawpaw_paw_icon() . '</span>';
		$html .= '<span class="logo__text">' . esc_html( $big ) . ( $small ? '<small>' . esc_html( $small ) . '</small>' : '' ) . '</span>';
	}
	$html .= '</a>';
	return '<div ' . get_block_wrapper_attributes() . '>' . $html . '</div>';
}

function pawpaw_render_kategori() {
	$terms = pawpaw_kategori();
	if ( ! $terms ) {
		return pawpaw_kosong( 'Belum ada kategori. Tambahkan di Produk > Kategori.' );
	}
	$url  = pawpaw_url_katalog();
	$html = '';
	foreach ( $terms as $t ) {
		$tone  = 'b' === get_term_meta( $t->term_id, 'pawpaw_warna', true ) ? 'b' : 'o';
		$emoji = get_term_meta( $t->term_id, 'pawpaw_emoji', true );
		$html .= '<a class="cat cat--' . $tone . '" href="' . esc_url( $url . '#' . $t->slug ) . '">'
			. '<span class="cat__emoji" aria-hidden="true">' . esc_html( $emoji ? $emoji : '🐾' ) . '</span>'
			. '<span class="cat__label">' . esc_html( $t->name ) . '</span>'
			. ( $t->description ? '<span class="cat__desc">' . esc_html( $t->description ) . '</span>' : '' )
			. '</a>';
	}
	return '<div ' . get_block_wrapper_attributes( array( 'class' => 'cat-grid' ) ) . '>' . $html . '</div>';
}

function pawpaw_render_produk_pilihan( $attrs ) {
	$jumlah = isset( $attrs['jumlah'] ) ? max( 1, (int) $attrs['jumlah'] ) : 4;
	$posts  = pawpaw_query_produk( array(
		'posts_per_page' => $jumlah,
		'meta_key'       => 'pawpaw_pilihan', // phpcs:ignore WordPress.DB.SlowDBQuery
		'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
	) );
	if ( ! $posts ) {
		return pawpaw_kosong( 'Belum ada produk pilihan. Centang "Produk pilihan" di produk yang mau ditampilkan.' );
	}
	return '<div ' . get_block_wrapper_attributes( array( 'class' => 'grid grid--products' ) ) . '>' . implode( '', array_map( 'pawpaw_kartu_produk', $posts ) ) . '</div>';
}

function pawpaw_render_katalog() {
	$terms = pawpaw_kategori();
	$all   = pawpaw_query_produk();
	if ( ! $all ) {
		return pawpaw_kosong( 'Belum ada produk. Tambahkan di menu Produk.' );
	}

	$chips = '<button type="button" class="chip" data-f="semua" aria-pressed="true">🐾 Semua</button>';
	$blocks = '';
	$used   = array();
	foreach ( $terms as $t ) {
		$items = array_filter( $all, function ( $p ) use ( $t ) {
			return has_term( $t->term_id, 'kategori_produk', $p );
		} );
		if ( ! $items ) {
			continue;
		}
		foreach ( $items as $p ) {
			$used[ $p->ID ] = true;
		}
		$emoji   = get_term_meta( $t->term_id, 'pawpaw_emoji', true );
		$chips  .= '<button type="button" class="chip" data-f="' . esc_attr( $t->slug ) . '" aria-pressed="false">' . esc_html( ( $emoji ? $emoji : '🐾' ) . ' ' . $t->name ) . '</button>';
		$blocks .= '<section class="cat-block" id="' . esc_attr( $t->slug ) . '" data-cat="' . esc_attr( $t->slug ) . '">'
			. '<h2><span class="cat-block__emoji" aria-hidden="true">' . esc_html( $emoji ? $emoji : '🐾' ) . '</span>' . esc_html( $t->name ) . '</h2>'
			. '<div class="grid grid--products">' . implode( '', array_map( 'pawpaw_kartu_produk', $items ) ) . '</div></section>';
	}
	$lain = array_filter( $all, function ( $p ) use ( $used ) {
		return empty( $used[ $p->ID ] );
	} );
	if ( $lain ) {
		$chips  .= '<button type="button" class="chip" data-f="lainnya" aria-pressed="false">🐾 Lainnya</button>';
		$blocks .= '<section class="cat-block" id="lainnya" data-cat="lainnya"><h2><span class="cat-block__emoji" aria-hidden="true">🐾</span>Lainnya</h2>'
			. '<div class="grid grid--products">' . implode( '', array_map( 'pawpaw_kartu_produk', $lain ) ) . '</div></section>';
	}

	return '<div ' . get_block_wrapper_attributes( array( 'class' => 'pawpaw-katalog' ) ) . ' data-katalog>'
		. '<div class="chips" role="group" aria-label="Filter kategori produk">' . $chips . '</div>'
		. '<div class="katalog__isi">' . $blocks . '</div></div>';
}

function pawpaw_render_layanan() {
	$posts = get_posts( array(
		'post_type'      => 'pawpaw_layanan',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
	) );
	if ( ! $posts ) {
		return pawpaw_kosong( 'Belum ada layanan. Tambahkan di menu Layanan Grooming.' );
	}
	$base = pawpaw_pesan( 'grooming' );
	$html = '';
	foreach ( $posts as $p ) {
		$nama  = get_the_title( $p );
		$emoji = get_post_meta( $p->ID, 'pawpaw_emoji', true );
		$desk  = get_post_meta( $p->ID, 'pawpaw_desk', true );
		$harga = get_post_meta( $p->ID, 'pawpaw_harga', true );
		$plain = wp_strip_all_tags( $nama );
		$msg   = false !== strpos( $base, 'Layanan yang dipilih:' )
			? str_replace( 'Layanan yang dipilih:', 'Layanan yang dipilih: ' . $plain, $base )
			: $base . "\n\nLayanan: " . $plain;
		$html .= '<article class="card service"><span class="service__emoji" aria-hidden="true">' . esc_html( $emoji ? $emoji : '🛁' ) . '</span>'
			. '<h3>' . esc_html( $nama ) . '</h3>'
			. ( $desk ? '<p>' . esc_html( $desk ) . '</p>' : '' )
			. ( $harga ? '<p class="price">' . esc_html( $harga ) . '</p>' : '' )
			. '<a class="btn btn--block" target="_blank" rel="noopener" href="' . esc_url( pawpaw_wa_url( $msg ) ) . '">Booking Grooming</a></article>';
	}
	return '<div ' . get_block_wrapper_attributes( array( 'class' => 'grid grid--services' ) ) . '>' . $html . '</div>';
}

function pawpaw_render_alamat() {
	return '<p ' . get_block_wrapper_attributes( array( 'class' => 'pawpaw-info' ) ) . '>' . nl2br( esc_html( pawpaw_opsi( 'alamat' ) ) ) . '</p>';
}

function pawpaw_render_jam_buka() {
	$html = '';
	foreach ( pawpaw_jam_buka() as $row ) {
		$html .= '<li><span>' . esc_html( $row[0] ) . '</span><strong>' . esc_html( $row[1] ) . '</strong></li>';
	}
	return '<ul ' . get_block_wrapper_attributes( array( 'class' => 'info-list hours' ) ) . '>' . $html . '</ul>';
}

function pawpaw_render_nomor_wa( $attrs ) {
	$nomor = esc_html( pawpaw_opsi( 'whatsapp_display' ) );
	$link  = '<a href="' . esc_url( pawpaw_wa_url( pawpaw_pesan( 'umum' ) ) ) . '" target="_blank" rel="noopener"><strong>' . $nomor . '</strong></a>';
	$label = ! empty( $attrs['label'] ) ? 'WhatsApp: ' : '';
	return '<p ' . get_block_wrapper_attributes( array( 'class' => 'pawpaw-info' ) ) . '>' . $label . $link . '</p>';
}

function pawpaw_render_form_wa() {
	return '<form ' . get_block_wrapper_attributes( array( 'class' => 'wa-form' ) ) . ' data-wa-form data-wa="' . esc_attr( pawpaw_wa_url() ) . '" data-nama="' . esc_attr( get_bloginfo( 'name' ) ) . '">'
		. '<label>Nama kamu <input type="text" name="nama" autocomplete="name" placeholder="Contoh: Sinta"></label>'
		. '<label>Hewan peliharaan <input type="text" name="hewan" placeholder="Contoh: Kucing, ras domestik"></label>'
		. '<label>Keperluan <select name="keperluan"><option>Tanya produk</option><option>Booking grooming</option><option>Lainnya</option></select></label>'
		. '<label>Pesan <textarea name="pesan" placeholder="Tulis pertanyaan atau jadwal yang kamu mau"></textarea></label>'
		. '<button class="btn btn--block" type="submit">Kirim via WhatsApp</button>'
		. '</form>';
}

function pawpaw_render_peta() {
	$src = pawpaw_opsi( 'peta' );
	if ( $src ) {
		return '<div ' . get_block_wrapper_attributes( array( 'class' => 'map-ph has-map' ) ) . '>'
			. '<iframe src="' . esc_url( $src ) . '" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="' . esc_attr( 'Lokasi ' . get_bloginfo( 'name' ) ) . '"></iframe></div>';
	}
	return '<div ' . get_block_wrapper_attributes( array( 'class' => 'map-ph' ) ) . '><div>'
		. '<p style="font-size:2rem">🗺️</p>'
		. '<p><strong>Peta lokasi toko</strong><br>Tempel link Google Maps di Pengaturan Toko.</p>'
		. '</div></div>';
}

function pawpaw_render_wa_melayang() {
	return '<a ' . get_block_wrapper_attributes( array( 'class' => 'wa-float' ) ) . ' href="' . esc_url( pawpaw_wa_url( pawpaw_pesan( 'umum' ) ) ) . '" target="_blank" rel="noopener" aria-label="Chat lewat WhatsApp">💬 Chat WA</a>';
}

function pawpaw_render_hak_cipta() {
	return '<p ' . get_block_wrapper_attributes() . '>© ' . esc_html( wp_date( 'Y' ) ) . ' ' . esc_html( get_bloginfo( 'name' ) ) . '. Semua hak dilindungi.</p>';
}

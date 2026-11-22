<?php
/**
 * Menu "Produk" dan "Layanan Grooming" di dashboard.
 *
 * Keduanya memakai form sederhana (judul, foto, dan kotak isian),
 * bukan editor blok, supaya gampang diisi.
 *
 * @package PawPaw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pawpaw_register_post_types() {
	register_post_type( 'pawpaw_produk', array(
		'labels'              => array(
			'name'               => 'Produk',
			'singular_name'      => 'Produk',
			'menu_name'          => 'Produk',
			'add_new'            => 'Tambah Produk',
			'add_new_item'       => 'Tambah Produk Baru',
			'edit_item'          => 'Edit Produk',
			'new_item'           => 'Produk Baru',
			'view_item'          => 'Lihat Produk',
			'search_items'       => 'Cari Produk',
			'not_found'          => 'Belum ada produk.',
			'not_found_in_trash' => 'Tidak ada produk di tempat sampah.',
			'all_items'          => 'Semua Produk',
			'featured_image'     => 'Foto produk',
			'set_featured_image' => 'Pilih foto produk',
			'remove_featured_image' => 'Hapus foto produk',
			'use_featured_image' => 'Pakai sebagai foto produk',
		),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => false,
		'menu_position'       => 4,
		'menu_icon'           => 'dashicons-cart',
		'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
		'taxonomies'          => array( 'kategori_produk' ),
	) );

	register_taxonomy( 'kategori_produk', 'pawpaw_produk', array(
		'labels'            => array(
			'name'          => 'Kategori Produk',
			'singular_name' => 'Kategori',
			'menu_name'     => 'Kategori',
			'add_new_item'  => 'Tambah Kategori',
			'edit_item'     => 'Edit Kategori',
			'search_items'  => 'Cari Kategori',
			'all_items'     => 'Semua Kategori',
			'not_found'     => 'Belum ada kategori.',
		),
		'public'            => false,
		'show_ui'           => true,
		'show_in_rest'      => false,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'rewrite'           => false,
	) );

	register_post_type( 'pawpaw_layanan', array(
		'labels'              => array(
			'name'               => 'Layanan Grooming',
			'singular_name'      => 'Layanan',
			'menu_name'          => 'Layanan Grooming',
			'add_new'            => 'Tambah Layanan',
			'add_new_item'       => 'Tambah Layanan Baru',
			'edit_item'          => 'Edit Layanan',
			'all_items'          => 'Semua Layanan',
			'not_found'          => 'Belum ada layanan.',
		),
		'public'              => false,
		'show_ui'             => true,
		'show_in_rest'        => false,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-pets',
		'supports'            => array( 'title', 'page-attributes' ),
	) );
}
add_action( 'init', 'pawpaw_register_post_types' );

/* ------------------------------------------------------------------
 * Kotak isian (meta box)
 * ------------------------------------------------------------------ */

function pawpaw_fields_produk() {
	return array(
		'pawpaw_harga'   => array( 'label' => 'Harga', 'type' => 'text', 'placeholder' => 'Rp 50.000', 'help' => 'Bebas ditulis, misalnya "Rp 50.000" atau "Mulai Rp 25.000".' ),
		'pawpaw_hewan'   => array( 'label' => 'Untuk hewan', 'type' => 'text', 'placeholder' => 'Kucing / Anjing / Kucing & Anjing', 'help' => 'Muncul sebagai label kecil di atas nama produk.' ),
		'pawpaw_desk'    => array( 'label' => 'Deskripsi singkat', 'type' => 'textarea', 'placeholder' => 'Satu atau dua kalimat tentang produk ini.' ),
		'pawpaw_emoji'   => array( 'label' => 'Emoji', 'type' => 'text', 'placeholder' => '🍗', 'help' => 'Tampil kalau produk belum punya foto. Foto produk diatur di kotak "Foto produk" di samping.', 'class' => 'small-text' ),
		'pawpaw_pilihan' => array( 'label' => 'Produk pilihan', 'type' => 'checkbox', 'help' => 'Centang supaya produk ini muncul di bagian "Produk pilihan" di Beranda.' ),
	);
}

function pawpaw_fields_layanan() {
	return array(
		'pawpaw_harga' => array( 'label' => 'Harga', 'type' => 'text', 'placeholder' => 'Mulai Rp 50.000' ),
		'pawpaw_desk'  => array( 'label' => 'Deskripsi singkat', 'type' => 'textarea', 'placeholder' => 'Apa saja yang termasuk di layanan ini.' ),
		'pawpaw_emoji' => array( 'label' => 'Emoji', 'type' => 'text', 'placeholder' => '🛁', 'class' => 'small-text' ),
	);
}

function pawpaw_add_meta_boxes() {
	add_meta_box( 'pawpaw_produk_info', 'Info Produk', 'pawpaw_render_meta_box', 'pawpaw_produk', 'normal', 'high', array( 'fields' => pawpaw_fields_produk() ) );
	add_meta_box( 'pawpaw_layanan_info', 'Info Layanan', 'pawpaw_render_meta_box', 'pawpaw_layanan', 'normal', 'high', array( 'fields' => pawpaw_fields_layanan() ) );
}
add_action( 'add_meta_boxes', 'pawpaw_add_meta_boxes' );

function pawpaw_render_meta_box( $post, $box ) {
	wp_nonce_field( 'pawpaw_meta', 'pawpaw_meta_nonce' );
	echo '<table class="form-table" role="presentation">';
	foreach ( $box['args']['fields'] as $key => $f ) {
		$val = get_post_meta( $post->ID, $key, true );
		$id  = esc_attr( $key );
		echo '<tr><th scope="row"><label for="' . $id . '">' . esc_html( $f['label'] ) . '</label></th><td>';
		if ( 'textarea' === $f['type'] ) {
			echo '<textarea class="large-text" rows="3" id="' . $id . '" name="' . $id . '" placeholder="' . esc_attr( isset( $f['placeholder'] ) ? $f['placeholder'] : '' ) . '">' . esc_textarea( $val ) . '</textarea>';
		} elseif ( 'checkbox' === $f['type'] ) {
			echo '<label><input type="checkbox" id="' . $id . '" name="' . $id . '" value="1"' . checked( $val, '1', false ) . '> Ya</label>';
		} else {
			$class = isset( $f['class'] ) ? $f['class'] : 'regular-text';
			echo '<input type="text" class="' . esc_attr( $class ) . '" id="' . $id . '" name="' . $id . '" value="' . esc_attr( $val ) . '" placeholder="' . esc_attr( isset( $f['placeholder'] ) ? $f['placeholder'] : '' ) . '">';
		}
		if ( ! empty( $f['help'] ) ) {
			echo '<p class="description">' . esc_html( $f['help'] ) . '</p>';
		}
		echo '</td></tr>';
	}
	echo '</table>';
	echo '<p class="description">Urutan tampil diatur lewat kolom <strong>Urutan</strong> di kotak "Atribut" (angka kecil tampil duluan).</p>';
}

function pawpaw_save_meta( $post_id, $post ) {
	if ( ! isset( $_POST['pawpaw_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['pawpaw_meta_nonce'] ), 'pawpaw_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$fields = 'pawpaw_produk' === $post->post_type ? pawpaw_fields_produk() : pawpaw_fields_layanan();
	foreach ( $fields as $key => $f ) {
		if ( 'checkbox' === $f['type'] ) {
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? '1' : '0' );
			continue;
		}
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$val = 'textarea' === $f['type'] ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );
		update_post_meta( $post_id, $key, $val );
	}
}
add_action( 'save_post_pawpaw_produk', 'pawpaw_save_meta', 10, 2 );
add_action( 'save_post_pawpaw_layanan', 'pawpaw_save_meta', 10, 2 );

/* ------------------------------------------------------------------
 * Kolom tambahan di daftar Produk & Layanan
 * ------------------------------------------------------------------ */

function pawpaw_produk_columns( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		if ( 'title' === $k ) {
			$new['pawpaw_foto'] = 'Foto';
		}
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['pawpaw_harga']   = 'Harga';
			$new['pawpaw_pilihan'] = 'Pilihan';
		}
	}
	unset( $new['date'] );
	return $new;
}
add_filter( 'manage_pawpaw_produk_posts_columns', 'pawpaw_produk_columns' );

function pawpaw_layanan_columns( $cols ) {
	$cols['pawpaw_harga'] = 'Harga';
	unset( $cols['date'] );
	return $cols;
}
add_filter( 'manage_pawpaw_layanan_posts_columns', 'pawpaw_layanan_columns' );

function pawpaw_columns_content( $col, $post_id ) {
	if ( 'pawpaw_harga' === $col ) {
		echo esc_html( get_post_meta( $post_id, 'pawpaw_harga', true ) );
	} elseif ( 'pawpaw_pilihan' === $col ) {
		echo '1' === get_post_meta( $post_id, 'pawpaw_pilihan', true ) ? '⭐' : '–';
	} elseif ( 'pawpaw_foto' === $col ) {
		if ( has_post_thumbnail( $post_id ) ) {
			echo get_the_post_thumbnail( $post_id, array( 48, 48 ), array( 'style' => 'border-radius:8px;width:48px;height:48px;object-fit:cover' ) );
		} else {
			echo '<span style="font-size:28px">' . esc_html( get_post_meta( $post_id, 'pawpaw_emoji', true ) ) . '</span>';
		}
	}
}
add_action( 'manage_pawpaw_produk_posts_custom_column', 'pawpaw_columns_content', 10, 2 );
add_action( 'manage_pawpaw_layanan_posts_custom_column', 'pawpaw_columns_content', 10, 2 );

function pawpaw_admin_columns_css() {
	echo '<style>.column-pawpaw_foto{width:64px}.column-pawpaw_pilihan{width:70px;text-align:center}</style>';
}
add_action( 'admin_head-edit.php', 'pawpaw_admin_columns_css' );

/* Daftar di dashboard diurutkan sesuai kolom Urutan. */
function pawpaw_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( in_array( $query->get( 'post_type' ), array( 'pawpaw_produk', 'pawpaw_layanan' ), true ) && ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
	}
}
add_action( 'pre_get_posts', 'pawpaw_admin_order' );

/* ------------------------------------------------------------------
 * Isian tambahan di Kategori: emoji, deskripsi pendek, warna, urutan
 * ------------------------------------------------------------------ */

function pawpaw_term_fields_html( $term = null ) {
	$get   = function ( $k, $d = '' ) use ( $term ) {
		if ( ! $term ) {
			return $d;
		}
		$v = get_term_meta( $term->term_id, $k, true );
		return '' === $v ? $d : $v;
	};
	$emoji  = $get( 'pawpaw_emoji' );
	$urutan = $get( 'pawpaw_urutan', '0' );
	$warna  = $get( 'pawpaw_warna', 'o' );
	$fields = array(
		'emoji'  => '<input name="pawpaw_emoji" id="pawpaw_emoji" type="text" value="' . esc_attr( $emoji ) . '" class="small-text" placeholder="🍗">',
		'warna'  => '<select name="pawpaw_warna" id="pawpaw_warna"><option value="o"' . selected( $warna, 'o', false ) . '>Oranye</option><option value="b"' . selected( $warna, 'b', false ) . '>Biru</option></select>',
		'urutan' => '<input name="pawpaw_urutan" id="pawpaw_urutan" type="number" value="' . esc_attr( $urutan ) . '" class="small-text">',
	);
	$labels = array(
		'emoji'  => array( 'Emoji', 'Tampil di kartu kategori dan judul katalog.' ),
		'warna'  => array( 'Warna lingkaran emoji', '' ),
		'urutan' => array( 'Urutan', 'Angka kecil tampil duluan.' ),
	);
	return array( $fields, $labels );
}

function pawpaw_term_add_fields() {
	list( $fields, $labels ) = pawpaw_term_fields_html();
	wp_nonce_field( 'pawpaw_term', 'pawpaw_term_nonce' );
	foreach ( $fields as $k => $html ) {
		echo '<div class="form-field"><label for="pawpaw_' . esc_attr( $k ) . '">' . esc_html( $labels[ $k ][0] ) . '</label>' . $html . ( $labels[ $k ][1] ? '<p>' . esc_html( $labels[ $k ][1] ) . '</p>' : '' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}
add_action( 'kategori_produk_add_form_fields', 'pawpaw_term_add_fields' );

function pawpaw_term_edit_fields( $term ) {
	list( $fields, $labels ) = pawpaw_term_fields_html( $term );
	wp_nonce_field( 'pawpaw_term', 'pawpaw_term_nonce' );
	foreach ( $fields as $k => $html ) {
		echo '<tr class="form-field"><th scope="row"><label for="pawpaw_' . esc_attr( $k ) . '">' . esc_html( $labels[ $k ][0] ) . '</label></th><td>' . $html . ( $labels[ $k ][1] ? '<p class="description">' . esc_html( $labels[ $k ][1] ) . '</p>' : '' ) . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}
add_action( 'kategori_produk_edit_form_fields', 'pawpaw_term_edit_fields' );

function pawpaw_term_save( $term_id ) {
	if ( ! isset( $_POST['pawpaw_term_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['pawpaw_term_nonce'] ), 'pawpaw_term' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	if ( isset( $_POST['pawpaw_emoji'] ) ) {
		update_term_meta( $term_id, 'pawpaw_emoji', sanitize_text_field( wp_unslash( $_POST['pawpaw_emoji'] ) ) );
	}
	if ( isset( $_POST['pawpaw_warna'] ) ) {
		update_term_meta( $term_id, 'pawpaw_warna', 'b' === $_POST['pawpaw_warna'] ? 'b' : 'o' );
	}
	if ( isset( $_POST['pawpaw_urutan'] ) ) {
		update_term_meta( $term_id, 'pawpaw_urutan', (int) $_POST['pawpaw_urutan'] );
	}
}
add_action( 'created_kategori_produk', 'pawpaw_term_save' );
add_action( 'edited_kategori_produk', 'pawpaw_term_save' );

/* Bantuan kecil di layar kategori: "Deskripsi" bawaan dipakai sebagai teks kecil di kartu. */
function pawpaw_term_desc_note() {
	echo '<p class="description" style="margin-top:12px">Kolom <strong>Deskripsi</strong> dipakai sebagai teks kecil di kartu kategori Beranda (contoh: "Makanan harian si gemas").</p>';
}
add_action( 'after-kategori_produk-table', 'pawpaw_term_desc_note' );

<?php
/**
 * Fungsi bantu PawPaw.
 *
 * @package PawPaw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Nilai bawaan pengaturan toko.
 */
function pawpaw_default_settings() {
	return array(
		'whatsapp'         => '6281234567890',
		'whatsapp_display' => '0812-3456-7890',
		'alamat'           => '[Alamat lengkap toko], Bandung',
		'jam'              => "Senin – Jumat | [00.00 – 00.00]\nSabtu – Minggu | [00.00 – 00.00]",
		'peta'             => '',
		'pesan_umum'       => 'Halo PawPaw Petshop! Aku mau tanya-tanya dulu ya 🐾',
		'pesan_tanya'      => 'Halo PawPaw Petshop! Aku mau tanya soal produk 🐾',
		'pesan_grooming'   => "Halo PawPaw Petshop! Aku mau booking grooming 🐾\n\nNama hewan:\nJenis (kucing/anjing):\nRas & ukuran:\nLayanan yang dipilih:\nTanggal & jam yang diinginkan:",
		'halaman_produk'   => 0,
	);
}

/**
 * Ambil satu pengaturan toko.
 *
 * @param string $key Nama pengaturan.
 * @return mixed
 */
function pawpaw_opsi( $key ) {
	$defaults = pawpaw_default_settings();
	$saved    = get_option( 'pawpaw_settings', array() );
	if ( is_array( $saved ) && isset( $saved[ $key ] ) && '' !== $saved[ $key ] ) {
		return $saved[ $key ];
	}
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}

/**
 * Link WhatsApp dengan pesan yang sudah terisi.
 *
 * @param string $msg Pesan.
 * @return string
 */
function pawpaw_wa_url( $msg = '' ) {
	$nomor = preg_replace( '/\D+/', '', (string) pawpaw_opsi( 'whatsapp' ) );
	if ( 0 === strpos( $nomor, '0' ) ) {
		$nomor = '62' . substr( $nomor, 1 ); // 0812... jadi 62812...
	}
	$url = 'https://wa.me/' . $nomor;
	if ( '' !== $msg ) {
		$url .= '?text=' . rawurlencode( $msg );
	}
	return $url;
}

/**
 * Pesan WhatsApp siap pakai: umum, tanya, grooming.
 *
 * @param string $jenis umum|tanya|grooming.
 * @return string
 */
function pawpaw_pesan( $jenis ) {
	$jenis = in_array( $jenis, array( 'umum', 'tanya', 'grooming' ), true ) ? $jenis : 'umum';
	return (string) pawpaw_opsi( 'pesan_' . $jenis );
}

/**
 * Daftar jam buka: array of [hari, jam].
 */
function pawpaw_jam_buka() {
	$rows  = array();
	$lines = preg_split( '/\r\n|\r|\n/', (string) pawpaw_opsi( 'jam' ) );
	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$parts  = array_map( 'trim', explode( '|', $line, 2 ) );
		$rows[] = array( $parts[0], isset( $parts[1] ) ? $parts[1] : '' );
	}
	return $rows;
}

/**
 * URL halaman katalog produk.
 */
function pawpaw_url_katalog() {
	$id = (int) pawpaw_opsi( 'halaman_produk' );
	if ( $id && 'publish' === get_post_status( $id ) ) {
		return get_permalink( $id );
	}
	$page = get_page_by_path( 'produk' );
	return $page ? get_permalink( $page ) : home_url( '/' );
}

/**
 * Kategori produk, sudah diurutkan sesuai kolom "Urutan".
 *
 * @return WP_Term[]
 */
function pawpaw_kategori() {
	$terms = get_terms( array(
		'taxonomy'   => 'kategori_produk',
		'hide_empty' => false,
	) );
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	usort( $terms, function ( $a, $b ) {
		$ua = (int) get_term_meta( $a->term_id, 'pawpaw_urutan', true );
		$ub = (int) get_term_meta( $b->term_id, 'pawpaw_urutan', true );
		return $ua === $ub ? strcmp( $a->name, $b->name ) : $ua - $ub;
	} );
	return $terms;
}

/**
 * Ubah link "#wa-umum", "#wa-tanya", "#wa-grooming" di tombol/teks mana pun
 * menjadi link WhatsApp asli dengan pesan yang sudah terisi.
 *
 * Jadi di editor cukup isi link tombol dengan #wa-grooming.
 *
 * @param string $html Isi blok.
 * @return string
 */
function pawpaw_ganti_link_wa( $html ) {
	if ( false === strpos( $html, '#wa-' ) ) {
		return $html;
	}
	return preg_replace_callback(
		'/href=(["\'])#wa-(umum|tanya|grooming)\1/',
		function ( $m ) {
			return 'href="' . esc_url( pawpaw_wa_url( pawpaw_pesan( $m[2] ) ) ) . '" target="_blank" rel="noopener"';
		},
		$html
	);
}
add_filter( 'render_block', 'pawpaw_ganti_link_wa', 20 );

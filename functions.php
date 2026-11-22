<?php
/**
 * PawPaw Petshop: tema blok WordPress.
 *
 * Isi tema ini dibagi ke beberapa file di folder inc/:
 * - helpers.php     : fungsi kecil (ambil pengaturan, bikin link WhatsApp)
 * - settings.php    : halaman "Pengaturan Toko" (WA, alamat, jam buka, peta)
 * - post-types.php  : menu "Produk" dan "Layanan Grooming" di dashboard
 * - blocks.php      : blok khusus PawPaw (katalog, kategori, form WA, dll.)
 * - setup.php       : isi contoh yang dibuat otomatis saat tema pertama kali diaktifkan
 *
 * @package PawPaw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PAWPAW_VERSION', '1.0.0' );
define( 'PAWPAW_DIR', get_template_directory() );
define( 'PAWPAW_URI', get_template_directory_uri() );

require PAWPAW_DIR . '/inc/helpers.php';
require PAWPAW_DIR . '/inc/settings.php';
require PAWPAW_DIR . '/inc/post-types.php';
require PAWPAW_DIR . '/inc/blocks.php';
require PAWPAW_DIR . '/inc/setup.php';

/**
 * Dukungan tema.
 */
function pawpaw_theme_setup() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array(
		'height'      => 112,
		'width'       => 112,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'pawpaw_theme_setup' );

/**
 * CSS dan JS untuk pengunjung.
 */
function pawpaw_enqueue_assets() {
	wp_enqueue_style( 'pawpaw-style', get_stylesheet_uri(), array(), PAWPAW_VERSION );
	wp_enqueue_script( 'pawpaw-front', PAWPAW_URI . '/assets/js/front.js', array(), PAWPAW_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
}
add_action( 'wp_enqueue_scripts', 'pawpaw_enqueue_assets' );

/**
 * Gaya tambahan untuk blok Tombol: "Biru" dan "Putih".
 * Muncul di panel kanan editor saat sebuah tombol dipilih.
 */
function pawpaw_block_styles() {
	register_block_style( 'core/button', array( 'name' => 'biru', 'label' => 'Biru' ) );
	register_block_style( 'core/button', array( 'name' => 'putih', 'label' => 'Putih' ) );
}
add_action( 'init', 'pawpaw_block_styles' );

/**
 * Kategori pola blok "PawPaw" di editor.
 */
function pawpaw_pattern_category() {
	register_block_pattern_category( 'pawpaw', array( 'label' => 'PawPaw Petshop' ) );
}
add_action( 'init', 'pawpaw_pattern_category' );

/**
 * Ikon tab browser: emoji jejak kaki (kalau belum ada Ikon Situs di Pengaturan).
 */
function pawpaw_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	echo '<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Ctext y=%22.9em%22 font-size=%2290%22%3E%F0%9F%90%BE%3C/text%3E%3C/svg%3E">' . "\n";
	echo '<meta name="theme-color" content="#FFF6E8">' . "\n";
}
add_action( 'wp_head', 'pawpaw_favicon', 2 );

/**
 * Pakai emoji bawaan perangkat (lebih ringan, tampilannya sama seperti desain asli).
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

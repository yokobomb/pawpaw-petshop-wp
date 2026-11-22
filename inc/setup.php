<?php
/**
 * Isi contoh yang dibuat otomatis saat tema pertama kali diaktifkan:
 * halaman (Beranda, Produk, Grooming, Tentang Kami, Kontak), menu,
 * kategori, produk, dan layanan grooming. Semua bisa diedit atau dihapus.
 *
 * Hanya berjalan sekali. Halaman/produk yang sudah ada tidak ditimpa.
 *
 * @package PawPaw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ambil isi sebuah pola blok dari folder patterns/.
 *
 * @param string $slug Nama file tanpa .php.
 * @return string
 */
function pawpaw_pattern_markup( $slug ) {
	$file = PAWPAW_DIR . '/patterns/' . $slug . '.php';
	if ( ! file_exists( $file ) ) {
		return '';
	}
	ob_start();
	include $file;
	return trim( ob_get_clean() );
}

function pawpaw_page_hero_markup( $eyebrow, $title, $text ) {
	return str_replace(
		array( 'Label kecil', 'Judul Halaman', 'Satu kalimat pengantar tentang isi halaman ini.' ),
		array( esc_html( $eyebrow ), esc_html( $title ), esc_html( $text ) ),
		pawpaw_pattern_markup( 'judul-halaman' )
	);
}

function pawpaw_demo_data() {
	return array(
		'kategori' => array(
			array( 'makanan', 'Makanan', '🍗', 'Makanan harian si gemas', 'o' ),
			array( 'camilan', 'Camilan', '🦴', 'Buat hadiah & latihan', 'b' ),
			array( 'mainan', 'Mainan', '🎾', 'Biar makin aktif & happy', 'o' ),
			array( 'aksesori', 'Aksesori', '🎀', 'Kalung, harness, & lainnya', 'b' ),
			array( 'kebersihan', 'Kebersihan', '🧼', 'Biar bersih & wangi', 'o' ),
		),
		// slug, kategori, hewan, emoji, nama, deskripsi, pilihan
		'produk'   => array(
			array( 'dryfood-kucing', 'makanan', 'Kucing', '🍗', 'Makanan Kering Kucing', 'Makanan harian untuk kucing. Tanya varian & merek yang tersedia.', true ),
			array( 'dryfood-anjing', 'makanan', 'Anjing', '🍖', 'Makanan Kering Anjing', 'Makanan harian untuk anjing. Tanya varian & ukuran kemasan.', false ),
			array( 'wetfood-kucing', 'makanan', 'Kucing', '🐟', 'Makanan Basah Kucing', 'Pilihan makanan basah (pouch/kaleng). Tanya rasa yang ready.', false ),
			array( 'treat-kucing', 'camilan', 'Kucing', '🐠', 'Camilan Kucing', 'Camilan untuk hadiah atau teman main. Tanya pilihan rasanya.', false ),
			array( 'snack-anjing', 'camilan', 'Anjing', '🦴', 'Camilan Kunyah Anjing', 'Camilan kunyah untuk anjing. Tanya ukuran yang cocok.', true ),
			array( 'stick-kucing', 'camilan', 'Kucing', '🥢', 'Cat Stick', 'Camilan bentuk stik untuk kucing. Tanya varian yang tersedia.', false ),
			array( 'bola-karet', 'mainan', 'Anjing', '🎾', 'Bola Karet', 'Mainan lempar-tangkap untuk anjing. Tanya ukuran yang ada.', false ),
			array( 'tongkat-bulu', 'mainan', 'Kucing', '🪶', 'Tongkat Bulu', 'Mainan kejar-kejaran untuk kucing.', true ),
			array( 'tali-kunyah', 'mainan', 'Anjing', '🪢', 'Mainan Tali Kunyah', 'Mainan tarik dan kunyah untuk anjing.', false ),
			array( 'kalung-tag', 'aksesori', 'Kucing & Anjing', '🎀', 'Kalung & Tag Nama', 'Kalung manis untuk si gemas. Tanya warna & ukuran.', false ),
			array( 'tempat-makan', 'aksesori', 'Kucing & Anjing', '🥣', 'Tempat Makan & Minum', 'Mangkuk makan dan minum. Tanya model yang tersedia.', false ),
			array( 'harness-tali', 'aksesori', 'Kucing & Anjing', '🦮', 'Harness & Tali Tuntun', 'Untuk jalan-jalan bareng. Tanya ukuran yang cocok.', false ),
			array( 'shampoo', 'kebersihan', 'Kucing & Anjing', '🧴', 'Shampoo Hewan', 'Untuk mandi di rumah. Tanya pilihan yang tersedia.', true ),
			array( 'pasir-kucing', 'kebersihan', 'Kucing', '🪣', 'Pasir Kucing', 'Untuk litter box. Tanya jenis & ukuran kemasan.', false ),
			array( 'sisir-sikat', 'kebersihan', 'Kucing & Anjing', '🪮', 'Sisir & Sikat Bulu', 'Untuk merawat bulu di rumah.', false ),
		),
		'layanan'  => array(
			array( '🛁', 'Mandi', 'Mandi dan keringkan bulu supaya bersih dan wangi.' ),
			array( '✨', 'Grooming Lengkap', 'Mandi, sisir bulu, potong kuku, dan bersihkan telinga dalam satu paket.' ),
			array( '✂️', 'Potong Kuku & Telinga', 'Perawatan singkat: potong kuku dan bersihkan telinga.' ),
			array( '💈', 'Rapikan Bulu', 'Merapikan atau memotong bulu sesuai kebutuhan si gemas.' ),
		),
	);
}

/**
 * Masukkan foto contoh dari folder tema ke Media.
 */
function pawpaw_import_image( $file, $parent_id ) {
	$path = PAWPAW_DIR . '/assets/demo/' . $file;
	if ( ! file_exists( $path ) ) {
		return 0;
	}
	$upload = wp_upload_bits( $file, null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$id = wp_insert_attachment( array(
		'post_mime_type' => 'image/jpeg',
		'post_title'     => get_the_title( $parent_id ),
		'post_status'    => 'inherit',
	), $upload['file'], $parent_id );
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	return $id;
}

function pawpaw_buat_konten_contoh() {
	if ( get_option( 'pawpaw_demo_dibuat' ) ) {
		return;
	}
	update_option( 'pawpaw_demo_dibuat', 1 );

	// Taksonomi & post type harus sudah terdaftar.
	pawpaw_register_post_types();
	$data = pawpaw_demo_data();

	/* Kategori */
	$urut = 0;
	foreach ( $data['kategori'] as $k ) {
		$urut++;
		$term = term_exists( $k[0], 'kategori_produk' );
		if ( ! $term ) {
			$term = wp_insert_term( $k[1], 'kategori_produk', array( 'slug' => $k[0], 'description' => $k[3] ) );
			if ( is_wp_error( $term ) ) {
				continue;
			}
			update_term_meta( $term['term_id'], 'pawpaw_emoji', $k[2] );
			update_term_meta( $term['term_id'], 'pawpaw_warna', $k[4] );
			update_term_meta( $term['term_id'], 'pawpaw_urutan', $urut * 10 );
		}
	}

	/* Produk */
	$ada_produk = get_posts( array( 'post_type' => 'pawpaw_produk', 'posts_per_page' => 1, 'post_status' => 'any', 'fields' => 'ids' ) );
	if ( ! $ada_produk ) {
		foreach ( $data['produk'] as $i => $p ) {
			$id = wp_insert_post( array(
				'post_type'   => 'pawpaw_produk',
				'post_status' => 'publish',
				'post_title'  => $p[4],
				'post_name'   => $p[0],
				'menu_order'  => ( $i + 1 ) * 10,
			) );
			if ( ! $id || is_wp_error( $id ) ) {
				continue;
			}
			wp_set_object_terms( $id, $p[1], 'kategori_produk' );
			update_post_meta( $id, 'pawpaw_hewan', $p[2] );
			update_post_meta( $id, 'pawpaw_emoji', $p[3] );
			update_post_meta( $id, 'pawpaw_desk', $p[5] );
			update_post_meta( $id, 'pawpaw_harga', 'Rp xx.000' );
			update_post_meta( $id, 'pawpaw_pilihan', $p[6] ? '1' : '0' );
			if ( file_exists( PAWPAW_DIR . '/assets/demo/' . $p[0] . '.jpg' ) ) {
				$img = pawpaw_import_image( $p[0] . '.jpg', $id );
				if ( $img ) {
					set_post_thumbnail( $id, $img );
				}
			}
		}
	}

	/* Layanan */
	$ada_layanan = get_posts( array( 'post_type' => 'pawpaw_layanan', 'posts_per_page' => 1, 'post_status' => 'any', 'fields' => 'ids' ) );
	if ( ! $ada_layanan ) {
		foreach ( $data['layanan'] as $i => $l ) {
			$id = wp_insert_post( array(
				'post_type'   => 'pawpaw_layanan',
				'post_status' => 'publish',
				'post_title'  => $l[1],
				'menu_order'  => ( $i + 1 ) * 10,
			) );
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, 'pawpaw_emoji', $l[0] );
				update_post_meta( $id, 'pawpaw_desk', $l[2] );
				update_post_meta( $id, 'pawpaw_harga', 'Mulai Rp xx.000' );
			}
		}
	}

	/* Pola tersinkron: edit sekali, berubah di semua halaman yang memakainya. */
	$sync = array();
	foreach ( array( 'keunggulan' => 'Keunggulan PawPaw', 'faq' => 'FAQ PawPaw' ) as $slug => $title ) {
		$id = wp_insert_post( array(
			'post_type'    => 'wp_block',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => wp_slash( pawpaw_pattern_markup( $slug ) ),
		) );
		$sync[ $slug ] = ( $id && ! is_wp_error( $id ) )
			? '<!-- wp:block {"ref":' . (int) $id . '} /-->'
			: pawpaw_pattern_markup( $slug );
	}

	/* Halaman */
	$halaman = array(
		'beranda'      => array( 'Beranda', array(
			pawpaw_pattern_markup( 'hero-beranda' ),
			pawpaw_pattern_markup( 'kategori' ),
			pawpaw_pattern_markup( 'produk-pilihan' ),
			pawpaw_pattern_markup( 'grooming-ringkas' ),
			$sync['keunggulan'],
			$sync['faq'],
			pawpaw_pattern_markup( 'kontak-ringkas' ),
		) ),
		'produk'       => array( 'Produk', array(
			pawpaw_page_hero_markup( 'Katalog produk', 'Belanja Kebutuhan Si Gemas', 'Pilih kategori, temukan yang cocok, lalu tanya stok dan harga lewat WhatsApp. Nggak ada keranjang ribet, tinggal chat.' ),
			pawpaw_pattern_markup( 'katalog' ),
			pawpaw_pattern_markup( 'cta-tanya' ),
		) ),
		'grooming'     => array( 'Grooming', array(
			pawpaw_page_hero_markup( 'Grooming kucing & anjing', 'Spa Day untuk Si Gemas 🛁', 'Bulu bersih, rapi, dan wangi. Pilih layanan yang cocok, lalu booking jadwalnya lewat WhatsApp.' ),
			pawpaw_pattern_markup( 'layanan' ),
			pawpaw_pattern_markup( 'cara-booking' ),
			$sync['faq'],
		) ),
		'tentang-kami' => array( 'Tentang Kami', array(
			pawpaw_page_hero_markup( 'Tentang kami', 'Halo, Kami PawPaw! 🐾', 'Toko kebutuhan hewan dan layanan grooming di Bandung untuk kamu, pemilik kucing dan anjing.' ),
			pawpaw_pattern_markup( 'tentang-cerita' ),
			pawpaw_pattern_markup( 'tentang-duo' ),
			$sync['keunggulan'],
		) ),
		'kontak'       => array( 'Kontak', array(
			pawpaw_page_hero_markup( 'Kontak', 'Yuk, Ngobrol Bareng Kami', 'Mau tanya produk atau booking grooming? Kirim pesan lewat WhatsApp, atau mampir langsung ke toko.' ),
			pawpaw_pattern_markup( 'kontak-lengkap' ),
			pawpaw_pattern_markup( 'peta' ),
		) ),
	);

	$ids   = array();
	$order = 0;
	foreach ( $halaman as $slug => $h ) {
		$order++;
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			continue;
		}
		$id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $h[0],
			'post_name'    => $slug,
			'menu_order'   => $order,
			'post_content' => wp_slash( implode( "\n\n", $h[1] ) ),
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			$ids[ $slug ] = $id;
		}
	}

	/* Beranda jadi halaman depan */
	if ( ! empty( $ids['beranda'] ) && 'posts' === get_option( 'show_on_front' ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['beranda'] );
	}

	/* Simpan halaman katalog di pengaturan */
	$opsi = get_option( 'pawpaw_settings', array() );
	$opsi = is_array( $opsi ) ? $opsi : array();
	if ( empty( $opsi['halaman_produk'] ) && ! empty( $ids['produk'] ) ) {
		$opsi['halaman_produk'] = $ids['produk'];
		update_option( 'pawpaw_settings', $opsi );
	}

	/* Menu navigasi */
	$labels = array( 'beranda' => 'Beranda', 'produk' => 'Produk', 'grooming' => 'Grooming', 'tentang-kami' => 'Tentang Kami', 'kontak' => 'Kontak Kami' );
	$links  = array();
	foreach ( $labels as $slug => $label ) {
		if ( empty( $ids[ $slug ] ) ) {
			continue;
		}
		$links[] = '<!-- wp:navigation-link ' . wp_json_encode( array(
			'label' => $label,
			'type'  => 'page',
			'id'    => (int) $ids[ $slug ],
			'url'   => get_permalink( $ids[ $slug ] ),
			'kind'  => 'post-type',
		), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . ' /-->';
	}
	if ( $links ) {
		wp_insert_post( array(
			'post_type'    => 'wp_navigation',
			'post_status'  => 'publish',
			'post_title'   => 'Menu Utama',
			'post_content' => wp_slash( implode( "\n", $links ) ),
		) );
	}

	set_transient( 'pawpaw_baru_aktif', 1, DAY_IN_SECONDS );
}
add_action( 'after_switch_theme', 'pawpaw_buat_konten_contoh' );

/**
 * Pesan sambutan setelah tema diaktifkan.
 */
function pawpaw_notice_aktif() {
	if ( ! get_transient( 'pawpaw_baru_aktif' ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	delete_transient( 'pawpaw_baru_aktif' );
	echo '<div class="notice notice-success is-dismissible"><p><strong>🐾 Tema PawPaw aktif!</strong> Halaman, menu, produk, dan layanan contoh sudah dibuat. Langkah pertama: isi nomor WhatsApp, alamat, dan jam buka di <a href="' . esc_url( admin_url( 'admin.php?page=pawpaw-pengaturan' ) ) . '">Pengaturan Toko</a>.</p></div>';
}
add_action( 'admin_notices', 'pawpaw_notice_aktif' );

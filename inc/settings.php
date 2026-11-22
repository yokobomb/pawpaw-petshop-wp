<?php
/**
 * Halaman "Pengaturan Toko" di dashboard.
 *
 * @package PawPaw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pawpaw_settings_menu() {
	add_menu_page(
		'Pengaturan Toko',
		'Pengaturan Toko',
		'edit_theme_options',
		'pawpaw-pengaturan',
		'pawpaw_settings_page',
		'dashicons-store',
		3
	);
}
add_action( 'admin_menu', 'pawpaw_settings_menu' );

function pawpaw_register_settings() {
	register_setting( 'pawpaw_settings_group', 'pawpaw_settings', array(
		'type'              => 'array',
		'sanitize_callback' => 'pawpaw_sanitize_settings',
		'default'           => array(),
	) );
}
add_action( 'admin_init', 'pawpaw_register_settings' );

function pawpaw_sanitize_settings( $input ) {
	$input = is_array( $input ) ? $input : array();
	$out   = array();

	$out['whatsapp']         = preg_replace( '/\D+/', '', isset( $input['whatsapp'] ) ? $input['whatsapp'] : '' );
	$out['whatsapp_display'] = sanitize_text_field( isset( $input['whatsapp_display'] ) ? $input['whatsapp_display'] : '' );
	$out['alamat']           = sanitize_textarea_field( isset( $input['alamat'] ) ? $input['alamat'] : '' );
	$out['jam']              = sanitize_textarea_field( isset( $input['jam'] ) ? $input['jam'] : '' );
	$out['halaman_produk']   = absint( isset( $input['halaman_produk'] ) ? $input['halaman_produk'] : 0 );

	foreach ( array( 'pesan_umum', 'pesan_tanya', 'pesan_grooming' ) as $k ) {
		$out[ $k ] = sanitize_textarea_field( isset( $input[ $k ] ) ? $input[ $k ] : '' );
	}

	// Peta: boleh tempel link embed atau kode <iframe> lengkap dari Google Maps.
	$peta = isset( $input['peta'] ) ? trim( wp_unslash( $input['peta'] ) ) : '';
	if ( preg_match( '/src=["\']([^"\']+)["\']/i', $peta, $m ) ) {
		$peta = $m[1];
	}
	$out['peta'] = esc_url_raw( $peta, array( 'https' ) );

	if ( 0 === strpos( $out['whatsapp'], '0' ) ) {
		$out['whatsapp'] = '62' . substr( $out['whatsapp'], 1 );
	}
	return $out;
}

function pawpaw_settings_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$o = wp_parse_args( get_option( 'pawpaw_settings', array() ), pawpaw_default_settings() );
	$n = 'pawpaw_settings';
	?>
	<div class="wrap">
		<h1>🐾 Pengaturan Toko</h1>
		<p>Isi sekali di sini, semua tombol WhatsApp, footer, dan halaman Kontak otomatis ikut berubah.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'pawpaw_settings_group' ); ?>

			<h2 class="title">WhatsApp</h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="pp-wa">Nomor WhatsApp</label></th>
					<td>
						<input name="<?php echo esc_attr( $n ); ?>[whatsapp]" id="pp-wa" type="text" class="regular-text" value="<?php echo esc_attr( $o['whatsapp'] ); ?>" inputmode="numeric">
						<p class="description">Contoh: <code>6281234567890</code>. Boleh juga ditulis <code>0812...</code>, nanti otomatis diubah.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="pp-wad">Nomor yang ditampilkan</label></th>
					<td>
						<input name="<?php echo esc_attr( $n ); ?>[whatsapp_display]" id="pp-wad" type="text" class="regular-text" value="<?php echo esc_attr( $o['whatsapp_display'] ); ?>">
						<p class="description">Tulisan nomor yang dilihat pengunjung, contoh <code>0812-3456-7890</code>.</p>
					</td>
				</tr>
			</table>

			<h2 class="title">Alamat, jam buka, dan peta</h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="pp-alamat">Alamat toko</label></th>
					<td><textarea name="<?php echo esc_attr( $n ); ?>[alamat]" id="pp-alamat" rows="2" class="large-text"><?php echo esc_textarea( $o['alamat'] ); ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="pp-jam">Jam buka</label></th>
					<td>
						<textarea name="<?php echo esc_attr( $n ); ?>[jam]" id="pp-jam" rows="4" class="large-text code"><?php echo esc_textarea( $o['jam'] ); ?></textarea>
						<p class="description">Satu baris satu jadwal. Pisahkan hari dan jam dengan garis tegak <code>|</code>. Contoh: <code>Senin – Jumat | 09.00 – 18.00</code></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="pp-peta">Peta Google Maps</label></th>
					<td>
						<textarea name="<?php echo esc_attr( $n ); ?>[peta]" id="pp-peta" rows="3" class="large-text code" placeholder="https://www.google.com/maps/embed?pb=..."><?php echo esc_textarea( $o['peta'] ); ?></textarea>
						<p class="description">Buka Google Maps, cari toko, klik <strong>Bagikan</strong> lalu <strong>Sematkan peta</strong>, salin kodenya, dan tempel di sini. Kosongkan kalau belum ada.</p>
					</td>
				</tr>
			</table>

			<h2 class="title">Halaman katalog</h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="pp-hal">Halaman Produk</label></th>
					<td>
						<?php
						wp_dropdown_pages( array(
							'name'              => $n . '[halaman_produk]',
							'id'                => 'pp-hal',
							'selected'          => (int) $o['halaman_produk'],
							'show_option_none'  => '— Otomatis (halaman "produk") —',
							'option_none_value' => 0,
						) );
						?>
						<p class="description">Kartu kategori di Beranda akan mengarah ke halaman ini.</p>
					</td>
				</tr>
			</table>

			<h2 class="title">Pesan otomatis WhatsApp</h2>
			<p>Pesan yang langsung terisi saat pengunjung menekan tombol.</p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="pp-pu">Tombol umum<br><code>#wa-umum</code></label></th>
					<td><textarea name="<?php echo esc_attr( $n ); ?>[pesan_umum]" id="pp-pu" rows="2" class="large-text"><?php echo esc_textarea( $o['pesan_umum'] ); ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="pp-pt">Tombol tanya produk<br><code>#wa-tanya</code></label></th>
					<td><textarea name="<?php echo esc_attr( $n ); ?>[pesan_tanya]" id="pp-pt" rows="2" class="large-text"><?php echo esc_textarea( $o['pesan_tanya'] ); ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="pp-pg">Tombol booking grooming<br><code>#wa-grooming</code></label></th>
					<td>
						<textarea name="<?php echo esc_attr( $n ); ?>[pesan_grooming]" id="pp-pg" rows="7" class="large-text"><?php echo esc_textarea( $o['pesan_grooming'] ); ?></textarea>
						<p class="description">Biarkan baris <code>Layanan yang dipilih:</code> supaya nama layanan otomatis terisi di tombol tiap layanan.</p>
					</td>
				</tr>
			</table>

			<div class="notice notice-info inline" style="margin:24px 0;padding:12px 16px">
				<p><strong>Tips:</strong> mau bikin tombol WhatsApp sendiri di halaman mana pun? Tambahkan blok <em>Tombol</em>, lalu isi link-nya dengan <code>#wa-umum</code>, <code>#wa-tanya</code>, atau <code>#wa-grooming</code>.</p>
			</div>

			<?php submit_button( 'Simpan Pengaturan' ); ?>
		</form>
	</div>
	<?php
}

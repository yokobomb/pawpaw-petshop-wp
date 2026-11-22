/* Blok PawPaw di editor: pratinjau diambil langsung dari server,
   jadi yang terlihat di editor sama dengan di situs. */
( function ( wp ) {
	var el = wp.element.createElement;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var RangeControl = wp.components.RangeControl;
	var ToggleControl = wp.components.ToggleControl;
	var Disabled = wp.components.Disabled;
	var ServerSideRender = wp.serverSideRender;
	var blocks = window.PAWPAW_BLOCKS || {};

	Object.keys( blocks ).forEach( function ( slug ) {
		var b = blocks[ slug ];
		var name = 'pawpaw/' + slug;

		wp.blocks.registerBlockType( name, {
			apiVersion: 3,
			title: b.title,
			description: b.description,
			icon: b.icon,
			category: 'pawpaw',
			attributes: b.attributes,
			supports: { html: false },
			edit: function ( props ) {
				var controls = null;
				if ( slug === 'produk-pilihan' ) {
					controls = el( InspectorControls, null,
						el( PanelBody, { title: 'Pengaturan' },
							el( RangeControl, {
								label: 'Jumlah produk yang ditampilkan',
								value: props.attributes.jumlah,
								min: 1, max: 12,
								onChange: function ( v ) { props.setAttributes( { jumlah: v } ); }
							} )
						)
					);
				}
				if ( slug === 'nomor-wa' ) {
					controls = el( InspectorControls, null,
						el( PanelBody, { title: 'Pengaturan' },
							el( ToggleControl, {
								label: 'Tampilkan tulisan "WhatsApp:" di depan nomor',
								checked: !! props.attributes.label,
								onChange: function ( v ) { props.setAttributes( { label: v } ); }
							} )
						)
					);
				}
				return el( 'div', useBlockProps(),
					controls,
					el( Disabled, null,
						el( ServerSideRender, { block: name, attributes: props.attributes } )
					)
				);
			},
			save: function () { return null; }
		} );
	} );
} )( window.wp );

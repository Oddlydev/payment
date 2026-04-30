/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, URLInput, useBlockProps, PanelColorSettings } = blockEditor;
	const { PanelBody } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	registerBlockType('sandbox/cta', {
		title: __('CTA', 'sandbox'),
		description: __('A conversion call-to-action section.', 'sandbox'),
		icon: 'megaphone',
		category: 'design',
		supports: {
			align: ['wide', 'full'],
			anchor: true,
			className: true,
		},
		attributes: {
			eyebrow: { type: 'string', default: '' },
			title: { type: 'string', default: '' },
			text: { type: 'string', default: '' },
			buttonText: { type: 'string', default: '' },
			buttonUrl: { type: 'string', default: '' },
			backgroundColor: { type: 'string', default: '' },
			textColor: { type: 'string', default: '' },
		},
		edit: function (props) {
			const { attributes, setAttributes } = props;

			const wrapperStyle = {};
			if (attributes.backgroundColor) { wrapperStyle.backgroundColor = attributes.backgroundColor; }
			if (attributes.textColor) { wrapperStyle.color = attributes.textColor; }

			const blockProps = useBlockProps({ className: 'sandbox-cta-block home-cta', style: wrapperStyle });

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(PanelColorSettings, {
						title: __('Colors', 'sandbox'),
						initialOpen: true,
						colorSettings: [
							{
								value: attributes.backgroundColor,
								onChange: function (v) { setAttributes({ backgroundColor: v || '' }); },
								label: __('Background color', 'sandbox'),
							},
							{
								value: attributes.textColor,
								onChange: function (v) { setAttributes({ textColor: v || '' }); },
								label: __('Text color', 'sandbox'),
							},
						],
					}),
					el(
						PanelBody,
						{ title: __('Button URL', 'sandbox'), initialOpen: false },
						el('p', { style: { marginBottom: '4px', fontWeight: 600, fontSize: '11px', textTransform: 'uppercase' } }, __('Button URL', 'sandbox')),
						el(URLInput, {
							value: attributes.buttonUrl,
							placeholder: __('Enter URL here', 'sandbox'),
							onChange: function (v) { setAttributes({ buttonUrl: v }); },
						})
					)
				),
				el(
					'section',
					blockProps,
					el(
						'div',
						{},
						el(RichText, {
							tagName: 'p',
							className: 'marketing-eyebrow',
							value: attributes.eyebrow,
							placeholder: __('Enter eyebrow text', 'sandbox'),
							allowedFormats: [],
							onChange: function (v) { setAttributes({ eyebrow: v }); },
						}),
						el(RichText, {
							tagName: 'h2',
							value: attributes.title,
							placeholder: __('Enter CTA title', 'sandbox'),
							onChange: function (v) { setAttributes({ title: v }); },
						}),
						el(RichText, {
							tagName: 'p',
							value: attributes.text,
							placeholder: __('Enter supporting text', 'sandbox'),
							onChange: function (v) { setAttributes({ text: v }); },
						})
					),
					el(RichText, {
						tagName: 'span',
						className: 'button button-primary',
						value: attributes.buttonText,
						placeholder: __('Button text', 'sandbox'),
						allowedFormats: [],
						onChange: function (v) { setAttributes({ buttonText: v }); },
					})
				)
			);
		},
		save: function () { return null; },
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, URLInput } = blockEditor;
	const { PanelBody, TextControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	const phText = function () {
		return __('Enter text here', 'sandbox');
	};
	const phUrl = function () {
		return __('Enter URL here', 'sandbox');
	};

	registerBlockType('sandbox/cta', {
		title: __('CTA', 'sandbox'),
		description: __('A conversion call-to-action section.', 'sandbox'),
		icon: 'megaphone',
		category: 'design',
		attributes: {
			eyebrow: { type: 'string', default: '' },
			title: { type: 'string', default: '' },
			text: { type: 'string', default: '' },
			buttonText: { type: 'string', default: '' },
			buttonUrl: { type: 'string', default: '' },
		},
		edit: function (props) {
			const { attributes, setAttributes } = props;

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('CTA Button', 'sandbox'), initialOpen: true },
						el(TextControl, {
							label: __('Button text', 'sandbox'),
							placeholder: phText(),
							value: attributes.buttonText,
							onChange: function (value) {
								setAttributes({ buttonText: value });
							},
						}),
						el(URLInput, {
							label: __('Button URL', 'sandbox'),
							placeholder: phUrl(),
							value: attributes.buttonUrl,
							onChange: function (value) {
								setAttributes({ buttonUrl: value });
							},
						})
					)
				),
				el(
					'section',
					{ className: 'sandbox-cta-block home-cta' },
					el(
						'div',
						{},
						el(RichText, {
							tagName: 'p',
							className: 'marketing-eyebrow',
							value: attributes.eyebrow,
							placeholder: phText(),
							onChange: function (value) {
								setAttributes({ eyebrow: value });
							},
						}),
						el(RichText, {
							tagName: 'h2',
							value: attributes.title,
							placeholder: phText(),
							onChange: function (value) {
								setAttributes({ title: value });
							},
						}),
						el(RichText, {
							tagName: 'p',
							value: attributes.text,
							placeholder: phText(),
							onChange: function (value) {
								setAttributes({ text: value });
							},
						})
					),
					el(
						'span',
						{
							className: 'button button-primary' + (attributes.buttonText ? '' : ' sandbox-block-placeholder'),
						},
						attributes.buttonText || phText()
					)
				)
			);
		},
		save: function () {
			return null;
		},
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

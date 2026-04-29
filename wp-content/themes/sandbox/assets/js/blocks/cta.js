/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, URLInput } = blockEditor;
	const { PanelBody, TextControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	registerBlockType('sandbox/cta', {
		title: __('CTA', 'sandbox'),
		description: __('A conversion call-to-action section.', 'sandbox'),
		icon: 'megaphone',
		category: 'design',
		attributes: {
			eyebrow: { type: 'string', default: __('Ready to launch?', 'sandbox') },
			title: { type: 'string', default: __('Build your marketing website with Sandbox today.', 'sandbox') },
			text: { type: 'string', default: __('Start with this template, then replace each section using custom Gutenberg blocks tailored to your brand.', 'sandbox') },
			buttonText: { type: 'string', default: __('Go to checkout', 'sandbox') },
			buttonUrl: { type: 'string', default: '/checkout/' },
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
							placeholder: __('Enter button text', 'sandbox'),
							value: attributes.buttonText,
							onChange: function (value) { setAttributes({ buttonText: value }); },
						}),
						el(URLInput, {
							label: __('Button URL', 'sandbox'),
							value: attributes.buttonUrl,
							onChange: function (value) { setAttributes({ buttonUrl: value }); },
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
							placeholder: __('CTA eyebrow', 'sandbox'),
							onChange: function (value) { setAttributes({ eyebrow: value }); },
						}),
						el(RichText, {
							tagName: 'h2',
							value: attributes.title,
							placeholder: __('CTA title', 'sandbox'),
							onChange: function (value) { setAttributes({ title: value }); },
						}),
						el(RichText, {
							tagName: 'p',
							value: attributes.text,
							placeholder: __('CTA description', 'sandbox'),
							onChange: function (value) { setAttributes({ text: value }); },
						})
					),
					el('span', { className: 'button button-primary' }, attributes.buttonText)
				)
			);
		},
		save: function () { return null; },
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

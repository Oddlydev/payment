/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, URLInput, useBlockProps, PanelColorSettings } = blockEditor;
	const { PanelBody, RangeControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	registerBlockType('sandbox/hero', {
		title: __('Hero', 'sandbox'),
		description: __('A customizable marketing hero section.', 'sandbox'),
		icon: 'cover-image',
		category: 'design',
		supports: {
			align: ['wide', 'full'],
			anchor: true,
			className: true,
		},
		attributes: {
			eyebrow: { type: 'string', default: '' },
			title: { type: 'string', default: '' },
			intro: { type: 'string', default: '' },
			buttonText: { type: 'string', default: '' },
			buttonUrl: { type: 'string', default: '' },
			secondaryButtonText: { type: 'string', default: '' },
			secondaryButtonUrl: { type: 'string', default: '' },
			statOneNumber: { type: 'string', default: '' },
			statOneLabel: { type: 'string', default: '' },
			statTwoNumber: { type: 'string', default: '' },
			statTwoLabel: { type: 'string', default: '' },
			statThreeNumber: { type: 'string', default: '' },
			statThreeLabel: { type: 'string', default: '' },
			maxWidth: { type: 'number', default: 1120 },
			topPadding: { type: 'number', default: 72 },
			bottomPadding: { type: 'number', default: 72 },
			backgroundColor: { type: 'string', default: '' },
			textColor: { type: 'string', default: '' },
		},
		edit: function (props) {
			const { attributes, setAttributes } = props;

			const wrapperStyle = {
				'--sandbox-hero-max-width': attributes.maxWidth + 'px',
				'--sandbox-hero-padding-top': attributes.topPadding + 'px',
				'--sandbox-hero-padding-bottom': attributes.bottomPadding + 'px',
			};
			if (attributes.backgroundColor) { wrapperStyle.backgroundColor = attributes.backgroundColor; }
			if (attributes.textColor) { wrapperStyle.color = attributes.textColor; }

			const blockProps = useBlockProps({ className: 'sandbox-hero-block', style: wrapperStyle });

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
						{ title: __('Layout', 'sandbox'), initialOpen: false },
						el(RangeControl, {
							label: __('Max width (px)', 'sandbox'),
							min: 720, max: 1440,
							value: attributes.maxWidth,
							onChange: function (v) { setAttributes({ maxWidth: v || 1120 }); },
						}),
						el(RangeControl, {
							label: __('Top padding (px)', 'sandbox'),
							min: 24, max: 160,
							value: attributes.topPadding,
							onChange: function (v) { setAttributes({ topPadding: v || 72 }); },
						}),
						el(RangeControl, {
							label: __('Bottom padding (px)', 'sandbox'),
							min: 24, max: 160,
							value: attributes.bottomPadding,
							onChange: function (v) { setAttributes({ bottomPadding: v || 72 }); },
						})
					),
					el(
						PanelBody,
						{ title: __('Button URLs', 'sandbox'), initialOpen: false },
						el('p', { style: { marginBottom: '4px', fontWeight: 600, fontSize: '11px', textTransform: 'uppercase' } }, __('Primary button URL', 'sandbox')),
						el(URLInput, {
							value: attributes.buttonUrl,
							placeholder: __('Enter URL here', 'sandbox'),
							onChange: function (v) { setAttributes({ buttonUrl: v }); },
						}),
						el('p', { style: { margin: '12px 0 4px', fontWeight: 600, fontSize: '11px', textTransform: 'uppercase' } }, __('Secondary button URL', 'sandbox')),
						el(URLInput, {
							value: attributes.secondaryButtonUrl,
							placeholder: __('Enter URL here', 'sandbox'),
							onChange: function (v) { setAttributes({ secondaryButtonUrl: v }); },
						})
					)
				),
				el(
					'section',
					blockProps,
					el(
						'div',
						{ className: 'sandbox-hero-block__inner' },
						el(
							'div',
							{ className: 'sandbox-hero-block__content' },
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
								className: 'sandbox-hero-block__title',
								value: attributes.title,
								placeholder: __('Enter hero title', 'sandbox'),
								onChange: function (v) { setAttributes({ title: v }); },
							}),
							el(RichText, {
								tagName: 'p',
								className: 'sandbox-hero-block__intro',
								value: attributes.intro,
								placeholder: __('Enter intro paragraph', 'sandbox'),
								onChange: function (v) { setAttributes({ intro: v }); },
							}),
							el(
								'div',
								{ className: 'marketing-actions' },
								el(RichText, {
									tagName: 'span',
									className: 'button button-primary',
									value: attributes.buttonText,
									placeholder: __('Primary button', 'sandbox'),
									allowedFormats: [],
									onChange: function (v) { setAttributes({ buttonText: v }); },
								}),
								el(RichText, {
									tagName: 'span',
									className: 'button button-secondary',
									value: attributes.secondaryButtonText,
									placeholder: __('Secondary button', 'sandbox'),
									allowedFormats: [],
									onChange: function (v) { setAttributes({ secondaryButtonText: v }); },
								})
							)
						),
						el(
							'div',
							{ className: 'sandbox-hero-block__panel' },
							el('div', {},
								el(RichText, { tagName: 'span', value: attributes.statOneNumber, placeholder: __('Stat', 'sandbox'), allowedFormats: [], onChange: function (v) { setAttributes({ statOneNumber: v }); } }),
								el(RichText, { tagName: 'p', value: attributes.statOneLabel, placeholder: __('Label', 'sandbox'), allowedFormats: [], onChange: function (v) { setAttributes({ statOneLabel: v }); } })
							),
							el('div', {},
								el(RichText, { tagName: 'span', value: attributes.statTwoNumber, placeholder: __('Stat', 'sandbox'), allowedFormats: [], onChange: function (v) { setAttributes({ statTwoNumber: v }); } }),
								el(RichText, { tagName: 'p', value: attributes.statTwoLabel, placeholder: __('Label', 'sandbox'), allowedFormats: [], onChange: function (v) { setAttributes({ statTwoLabel: v }); } })
							),
							el('div', {},
								el(RichText, { tagName: 'span', value: attributes.statThreeNumber, placeholder: __('Stat', 'sandbox'), allowedFormats: [], onChange: function (v) { setAttributes({ statThreeNumber: v }); } }),
								el(RichText, { tagName: 'p', value: attributes.statThreeLabel, placeholder: __('Label', 'sandbox'), allowedFormats: [], onChange: function (v) { setAttributes({ statThreeLabel: v }); } })
							)
						)
					)
				)
			);
		},
		save: function () { return null; },
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

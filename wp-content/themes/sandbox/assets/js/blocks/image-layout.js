/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, useBlockProps, PanelColorSettings } = blockEditor;
	const { PanelBody, TextControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	const editorFigure = function (className, url, alt, captionValue, onCaptionChange) {
		return el(
			'figure',
			{ className: className },
			url
				? el('img', { src: url, alt: alt || '' })
				: el('div', { className: 'sandbox-image-layout__editor-placeholder' }, __('Enter URL here', 'sandbox')),
			el(RichText, {
				tagName: 'figcaption',
				value: captionValue,
				placeholder: __('Enter caption here', 'sandbox'),
				allowedFormats: [],
				onChange: onCaptionChange,
			})
		);
	};

	registerBlockType('sandbox/image-layout', {
		title: __('Image Layout', 'sandbox'),
		description: __('A visual image grid with captions.', 'sandbox'),
		icon: 'format-gallery',
		category: 'design',
		supports: {
			align: ['wide', 'full'],
			anchor: true,
			className: true,
		},
		attributes: {
			eyebrow: { type: 'string', default: '' },
			title: { type: 'string', default: '' },
			imageOneUrl: { type: 'string', default: '' },
			imageOneAlt: { type: 'string', default: '' },
			imageOneCaption: { type: 'string', default: '' },
			imageTwoUrl: { type: 'string', default: '' },
			imageTwoAlt: { type: 'string', default: '' },
			imageTwoCaption: { type: 'string', default: '' },
			imageThreeUrl: { type: 'string', default: '' },
			imageThreeAlt: { type: 'string', default: '' },
			imageThreeCaption: { type: 'string', default: '' },
			backgroundColor: { type: 'string', default: '' },
		},
		edit: function (props) {
			const { attributes, setAttributes } = props;

			const wrapperStyle = {};
			if (attributes.backgroundColor) { wrapperStyle.backgroundColor = attributes.backgroundColor; }

			const blockProps = useBlockProps({ className: 'sandbox-image-layout-block home-section', style: wrapperStyle });

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(PanelColorSettings, {
						title: __('Colors', 'sandbox'),
						initialOpen: false,
						colorSettings: [
							{
								value: attributes.backgroundColor,
								onChange: function (v) { setAttributes({ backgroundColor: v || '' }); },
								label: __('Background color', 'sandbox'),
							},
						],
					}),
					el(
						PanelBody,
						{ title: __('Image URLs & Alt text', 'sandbox'), initialOpen: true },
						el(TextControl, {
							label: __('Image 1 URL', 'sandbox'),
							placeholder: __('Enter URL here', 'sandbox'),
							value: attributes.imageOneUrl,
							onChange: function (v) { setAttributes({ imageOneUrl: v }); },
						}),
						el(TextControl, {
							label: __('Image 1 alt text', 'sandbox'),
							placeholder: __('Enter alt text', 'sandbox'),
							value: attributes.imageOneAlt,
							onChange: function (v) { setAttributes({ imageOneAlt: v }); },
						}),
						el(TextControl, {
							label: __('Image 2 URL', 'sandbox'),
							placeholder: __('Enter URL here', 'sandbox'),
							value: attributes.imageTwoUrl,
							onChange: function (v) { setAttributes({ imageTwoUrl: v }); },
						}),
						el(TextControl, {
							label: __('Image 2 alt text', 'sandbox'),
							placeholder: __('Enter alt text', 'sandbox'),
							value: attributes.imageTwoAlt,
							onChange: function (v) { setAttributes({ imageTwoAlt: v }); },
						}),
						el(TextControl, {
							label: __('Image 3 URL', 'sandbox'),
							placeholder: __('Enter URL here', 'sandbox'),
							value: attributes.imageThreeUrl,
							onChange: function (v) { setAttributes({ imageThreeUrl: v }); },
						}),
						el(TextControl, {
							label: __('Image 3 alt text', 'sandbox'),
							placeholder: __('Enter alt text', 'sandbox'),
							value: attributes.imageThreeAlt,
							onChange: function (v) { setAttributes({ imageThreeAlt: v }); },
						})
					)
				),
				el(
					'section',
					blockProps,
					el(
						'div',
						{ className: 'section-heading' },
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
							placeholder: __('Enter section title', 'sandbox'),
							onChange: function (v) { setAttributes({ title: v }); },
						})
					),
					el(
						'div',
						{ className: 'home-image-layout' },
						editorFigure(
							'home-image-layout__item home-image-layout__item--large',
							attributes.imageOneUrl, attributes.imageOneAlt,
							attributes.imageOneCaption,
							function (v) { setAttributes({ imageOneCaption: v }); }
						),
						editorFigure(
							'home-image-layout__item',
							attributes.imageTwoUrl, attributes.imageTwoAlt,
							attributes.imageTwoCaption,
							function (v) { setAttributes({ imageTwoCaption: v }); }
						),
						editorFigure(
							'home-image-layout__item',
							attributes.imageThreeUrl, attributes.imageThreeAlt,
							attributes.imageThreeCaption,
							function (v) { setAttributes({ imageThreeCaption: v }); }
						)
					)
				)
			);
		},
		save: function () { return null; },
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText } = blockEditor;
	const { PanelBody, TextControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	const phText = function () {
		return __('Enter text here', 'sandbox');
	};
	const phUrl = function () {
		return __('Enter URL here', 'sandbox');
	};

	const editorFigure = function (className, url, alt, caption) {
		return el(
			'figure',
			{ className: className },
			url
				? el('img', { src: url, alt: alt || '' })
				: el('div', { className: 'sandbox-image-layout__editor-placeholder' }, phUrl()),
			el(
				'figcaption',
				{ className: caption ? '' : 'sandbox-block-placeholder' },
				caption || phText()
			)
		);
	};

	registerBlockType('sandbox/image-layout', {
		title: __('Image Layout', 'sandbox'),
		description: __('A visual image grid with captions.', 'sandbox'),
		icon: 'format-gallery',
		category: 'design',
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
						{ title: __('Image Settings', 'sandbox'), initialOpen: true },
						el(TextControl, {
							label: __('Image 1 URL', 'sandbox'),
							placeholder: phUrl(),
							value: attributes.imageOneUrl,
							onChange: function (value) {
								setAttributes({ imageOneUrl: value });
							},
						}),
						el(TextControl, {
							label: __('Image 1 alt text', 'sandbox'),
							placeholder: phText(),
							value: attributes.imageOneAlt,
							onChange: function (value) {
								setAttributes({ imageOneAlt: value });
							},
						}),
						el(TextControl, {
							label: __('Image 1 caption', 'sandbox'),
							placeholder: phText(),
							value: attributes.imageOneCaption,
							onChange: function (value) {
								setAttributes({ imageOneCaption: value });
							},
						}),
						el(TextControl, {
							label: __('Image 2 URL', 'sandbox'),
							placeholder: phUrl(),
							value: attributes.imageTwoUrl,
							onChange: function (value) {
								setAttributes({ imageTwoUrl: value });
							},
						}),
						el(TextControl, {
							label: __('Image 2 alt text', 'sandbox'),
							placeholder: phText(),
							value: attributes.imageTwoAlt,
							onChange: function (value) {
								setAttributes({ imageTwoAlt: value });
							},
						}),
						el(TextControl, {
							label: __('Image 2 caption', 'sandbox'),
							placeholder: phText(),
							value: attributes.imageTwoCaption,
							onChange: function (value) {
								setAttributes({ imageTwoCaption: value });
							},
						}),
						el(TextControl, {
							label: __('Image 3 URL', 'sandbox'),
							placeholder: phUrl(),
							value: attributes.imageThreeUrl,
							onChange: function (value) {
								setAttributes({ imageThreeUrl: value });
							},
						}),
						el(TextControl, {
							label: __('Image 3 alt text', 'sandbox'),
							placeholder: phText(),
							value: attributes.imageThreeAlt,
							onChange: function (value) {
								setAttributes({ imageThreeAlt: value });
							},
						}),
						el(TextControl, {
							label: __('Image 3 caption', 'sandbox'),
							placeholder: phText(),
							value: attributes.imageThreeCaption,
							onChange: function (value) {
								setAttributes({ imageThreeCaption: value });
							},
						})
					)
				),
				el(
					'section',
					{ className: 'sandbox-image-layout-block home-section' },
					el(
						'div',
						{ className: 'section-heading' },
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
						})
					),
					el(
						'div',
						{ className: 'home-image-layout' },
						editorFigure(
							'home-image-layout__item home-image-layout__item--large',
							attributes.imageOneUrl,
							attributes.imageOneAlt,
							attributes.imageOneCaption
						),
						editorFigure('home-image-layout__item', attributes.imageTwoUrl, attributes.imageTwoAlt, attributes.imageTwoCaption),
						editorFigure('home-image-layout__item', attributes.imageThreeUrl, attributes.imageThreeAlt, attributes.imageThreeCaption)
					)
				)
			);
		},
		save: function () {
			return null;
		},
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText } = blockEditor;
	const { PanelBody, TextControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	registerBlockType('sandbox/image-layout', {
		title: __('Image Layout', 'sandbox'),
		description: __('A visual image grid with captions.', 'sandbox'),
		icon: 'format-gallery',
		category: 'design',
		attributes: {
			eyebrow: { type: 'string', default: __('Image layout', 'sandbox') },
			title: { type: 'string', default: __('Show your product experience visually.', 'sandbox') },
			imageOneUrl: { type: 'string', default: 'https://picsum.photos/900/620?random=11' },
			imageOneAlt: { type: 'string', default: __('Students watching an online session', 'sandbox') },
			imageOneCaption: { type: 'string', default: __('Live cohort sessions and guided lessons.', 'sandbox') },
			imageTwoUrl: { type: 'string', default: 'https://picsum.photos/620/420?random=12' },
			imageTwoAlt: { type: 'string', default: __('Instructor dashboard', 'sandbox') },
			imageTwoCaption: { type: 'string', default: __('Simple instructor dashboard to manage modules.', 'sandbox') },
			imageThreeUrl: { type: 'string', default: 'https://picsum.photos/620/420?random=13' },
			imageThreeAlt: { type: 'string', default: __('Mobile learning screen', 'sandbox') },
			imageThreeCaption: { type: 'string', default: __('Learner-friendly experience across devices.', 'sandbox') },
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
							placeholder: __('https://example.com/image-1.jpg', 'sandbox'),
							value: attributes.imageOneUrl,
							onChange: function (value) { setAttributes({ imageOneUrl: value }); },
						}),
						el(TextControl, {
							label: __('Image 1 alt text', 'sandbox'),
							placeholder: __('Describe image 1', 'sandbox'),
							value: attributes.imageOneAlt,
							onChange: function (value) { setAttributes({ imageOneAlt: value }); },
						}),
						el(TextControl, {
							label: __('Image 1 caption', 'sandbox'),
							placeholder: __('Caption for image 1', 'sandbox'),
							value: attributes.imageOneCaption,
							onChange: function (value) { setAttributes({ imageOneCaption: value }); },
						}),
						el(TextControl, {
							label: __('Image 2 URL', 'sandbox'),
							placeholder: __('https://example.com/image-2.jpg', 'sandbox'),
							value: attributes.imageTwoUrl,
							onChange: function (value) { setAttributes({ imageTwoUrl: value }); },
						}),
						el(TextControl, {
							label: __('Image 2 alt text', 'sandbox'),
							placeholder: __('Describe image 2', 'sandbox'),
							value: attributes.imageTwoAlt,
							onChange: function (value) { setAttributes({ imageTwoAlt: value }); },
						}),
						el(TextControl, {
							label: __('Image 2 caption', 'sandbox'),
							placeholder: __('Caption for image 2', 'sandbox'),
							value: attributes.imageTwoCaption,
							onChange: function (value) { setAttributes({ imageTwoCaption: value }); },
						}),
						el(TextControl, {
							label: __('Image 3 URL', 'sandbox'),
							placeholder: __('https://example.com/image-3.jpg', 'sandbox'),
							value: attributes.imageThreeUrl,
							onChange: function (value) { setAttributes({ imageThreeUrl: value }); },
						}),
						el(TextControl, {
							label: __('Image 3 alt text', 'sandbox'),
							placeholder: __('Describe image 3', 'sandbox'),
							value: attributes.imageThreeAlt,
							onChange: function (value) { setAttributes({ imageThreeAlt: value }); },
						}),
						el(TextControl, {
							label: __('Image 3 caption', 'sandbox'),
							placeholder: __('Caption for image 3', 'sandbox'),
							value: attributes.imageThreeCaption,
							onChange: function (value) { setAttributes({ imageThreeCaption: value }); },
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
							placeholder: __('Image layout eyebrow', 'sandbox'),
							onChange: function (value) { setAttributes({ eyebrow: value }); },
						}),
						el(RichText, {
							tagName: 'h2',
							value: attributes.title,
							placeholder: __('Image layout title', 'sandbox'),
							onChange: function (value) { setAttributes({ title: value }); },
						})
					),
					el(
						'div',
						{ className: 'home-image-layout' },
						el('figure', { className: 'home-image-layout__item home-image-layout__item--large' }, el('img', { src: attributes.imageOneUrl, alt: attributes.imageOneAlt }), el('figcaption', {}, attributes.imageOneCaption)),
						el('figure', { className: 'home-image-layout__item' }, el('img', { src: attributes.imageTwoUrl, alt: attributes.imageTwoAlt }), el('figcaption', {}, attributes.imageTwoCaption)),
						el('figure', { className: 'home-image-layout__item' }, el('img', { src: attributes.imageThreeUrl, alt: attributes.imageThreeAlt }), el('figcaption', {}, attributes.imageThreeCaption))
					)
				)
			);
		},
		save: function () { return null; },
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

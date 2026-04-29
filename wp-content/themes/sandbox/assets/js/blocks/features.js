/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText } = blockEditor;
	const { PanelBody, TextControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	registerBlockType('sandbox/features', {
		title: __('Features', 'sandbox'),
		description: __('A 3-column feature section for marketing pages.', 'sandbox'),
		icon: 'screenoptions',
		category: 'design',
		attributes: {
			eyebrow: { type: 'string', default: __('Platform features', 'sandbox') },
			title: { type: 'string', default: __('Everything needed to run a strong marketing website.', 'sandbox') },
			featureOneTitle: { type: 'string', default: __('Conversion-focused hero blocks', 'sandbox') },
			featureOneText: { type: 'string', default: __('Use editable hero sections with call-to-action controls built directly in Gutenberg.', 'sandbox') },
			featureTwoTitle: { type: 'string', default: __('Flexible page building', 'sandbox') },
			featureTwoText: { type: 'string', default: __('Combine reusable sections and rich content blocks to design custom campaign pages quickly.', 'sandbox') },
			featureThreeTitle: { type: 'string', default: __('Checkout-ready flow', 'sandbox') },
			featureThreeText: { type: 'string', default: __('Guide visitors from product discovery to payment with clear actions and minimal friction.', 'sandbox') },
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
						{ title: __('Feature Cards', 'sandbox'), initialOpen: true },
						el(TextControl, {
							label: __('Feature 1 title', 'sandbox'),
							placeholder: __('Enter first feature title', 'sandbox'),
							value: attributes.featureOneTitle,
							onChange: function (value) { setAttributes({ featureOneTitle: value }); },
						}),
						el(TextControl, {
							label: __('Feature 1 description', 'sandbox'),
							placeholder: __('Enter first feature description', 'sandbox'),
							value: attributes.featureOneText,
							onChange: function (value) { setAttributes({ featureOneText: value }); },
						}),
						el(TextControl, {
							label: __('Feature 2 title', 'sandbox'),
							placeholder: __('Enter second feature title', 'sandbox'),
							value: attributes.featureTwoTitle,
							onChange: function (value) { setAttributes({ featureTwoTitle: value }); },
						}),
						el(TextControl, {
							label: __('Feature 2 description', 'sandbox'),
							placeholder: __('Enter second feature description', 'sandbox'),
							value: attributes.featureTwoText,
							onChange: function (value) { setAttributes({ featureTwoText: value }); },
						}),
						el(TextControl, {
							label: __('Feature 3 title', 'sandbox'),
							placeholder: __('Enter third feature title', 'sandbox'),
							value: attributes.featureThreeTitle,
							onChange: function (value) { setAttributes({ featureThreeTitle: value }); },
						}),
						el(TextControl, {
							label: __('Feature 3 description', 'sandbox'),
							placeholder: __('Enter third feature description', 'sandbox'),
							value: attributes.featureThreeText,
							onChange: function (value) { setAttributes({ featureThreeText: value }); },
						})
					)
				),
				el(
					'section',
					{ className: 'sandbox-features-block home-section' },
					el(
						'div',
						{ className: 'section-heading' },
						el(RichText, {
							tagName: 'p',
							className: 'marketing-eyebrow',
							value: attributes.eyebrow,
							placeholder: __('Features eyebrow', 'sandbox'),
							onChange: function (value) { setAttributes({ eyebrow: value }); },
						}),
						el(RichText, {
							tagName: 'h2',
							value: attributes.title,
							placeholder: __('Features section title', 'sandbox'),
							onChange: function (value) { setAttributes({ title: value }); },
						})
					),
					el(
						'div',
						{ className: 'home-feature-grid' },
						el('article', { className: 'feature-card' }, el('h3', {}, attributes.featureOneTitle), el('p', {}, attributes.featureOneText)),
						el('article', { className: 'feature-card' }, el('h3', {}, attributes.featureTwoTitle), el('p', {}, attributes.featureTwoText)),
						el('article', { className: 'feature-card' }, el('h3', {}, attributes.featureThreeTitle), el('p', {}, attributes.featureThreeText))
					)
				)
			);
		},
		save: function () { return null; },
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

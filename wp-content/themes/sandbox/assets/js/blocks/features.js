/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, useBlockProps, PanelColorSettings } = blockEditor;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	registerBlockType('sandbox/features', {
		title: __('Features', 'sandbox'),
		description: __('A 3-column feature section for marketing pages.', 'sandbox'),
		icon: 'screenoptions',
		category: 'design',
		supports: {
			align: ['wide', 'full'],
			anchor: true,
			className: true,
		},
		attributes: {
			eyebrow: { type: 'string', default: '' },
			title: { type: 'string', default: '' },
			featureOneTitle: { type: 'string', default: '' },
			featureOneText: { type: 'string', default: '' },
			featureTwoTitle: { type: 'string', default: '' },
			featureTwoText: { type: 'string', default: '' },
			featureThreeTitle: { type: 'string', default: '' },
			featureThreeText: { type: 'string', default: '' },
			backgroundColor: { type: 'string', default: '' },
			textColor: { type: 'string', default: '' },
		},
		edit: function (props) {
			const { attributes, setAttributes } = props;

			const wrapperStyle = {};
			if (attributes.backgroundColor) { wrapperStyle.backgroundColor = attributes.backgroundColor; }
			if (attributes.textColor) { wrapperStyle.color = attributes.textColor; }

			const blockProps = useBlockProps({ className: 'sandbox-features-block home-section', style: wrapperStyle });

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
					})
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
						{ className: 'home-feature-grid' },
						el(
							'article',
							{ className: 'feature-card' },
							el(RichText, {
								tagName: 'h3',
								value: attributes.featureOneTitle,
								placeholder: __('Feature title', 'sandbox'),
								onChange: function (v) { setAttributes({ featureOneTitle: v }); },
							}),
							el(RichText, {
								tagName: 'p',
								value: attributes.featureOneText,
								placeholder: __('Feature description', 'sandbox'),
								onChange: function (v) { setAttributes({ featureOneText: v }); },
							})
						),
						el(
							'article',
							{ className: 'feature-card' },
							el(RichText, {
								tagName: 'h3',
								value: attributes.featureTwoTitle,
								placeholder: __('Feature title', 'sandbox'),
								onChange: function (v) { setAttributes({ featureTwoTitle: v }); },
							}),
							el(RichText, {
								tagName: 'p',
								value: attributes.featureTwoText,
								placeholder: __('Feature description', 'sandbox'),
								onChange: function (v) { setAttributes({ featureTwoText: v }); },
							})
						),
						el(
							'article',
							{ className: 'feature-card' },
							el(RichText, {
								tagName: 'h3',
								value: attributes.featureThreeTitle,
								placeholder: __('Feature title', 'sandbox'),
								onChange: function (v) { setAttributes({ featureThreeTitle: v }); },
							}),
							el(RichText, {
								tagName: 'p',
								value: attributes.featureThreeText,
								placeholder: __('Feature description', 'sandbox'),
								onChange: function (v) { setAttributes({ featureThreeText: v }); },
							})
						)
					)
				)
			);
		},
		save: function () { return null; },
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

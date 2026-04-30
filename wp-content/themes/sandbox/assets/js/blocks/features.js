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

	const previewLine = function (value, tag) {
		const Tag = tag || 'span';
		return el(
			Tag,
			{
				className: value ? '' : 'sandbox-block-placeholder',
			},
			value || phText()
		);
	};

	registerBlockType('sandbox/features', {
		title: __('Features', 'sandbox'),
		description: __('A 3-column feature section for marketing pages.', 'sandbox'),
		icon: 'screenoptions',
		category: 'design',
		attributes: {
			eyebrow: { type: 'string', default: '' },
			title: { type: 'string', default: '' },
			featureOneTitle: { type: 'string', default: '' },
			featureOneText: { type: 'string', default: '' },
			featureTwoTitle: { type: 'string', default: '' },
			featureTwoText: { type: 'string', default: '' },
			featureThreeTitle: { type: 'string', default: '' },
			featureThreeText: { type: 'string', default: '' },
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
							placeholder: phText(),
							value: attributes.featureOneTitle,
							onChange: function (value) {
								setAttributes({ featureOneTitle: value });
							},
						}),
						el(TextControl, {
							label: __('Feature 1 description', 'sandbox'),
							placeholder: phText(),
							value: attributes.featureOneText,
							onChange: function (value) {
								setAttributes({ featureOneText: value });
							},
						}),
						el(TextControl, {
							label: __('Feature 2 title', 'sandbox'),
							placeholder: phText(),
							value: attributes.featureTwoTitle,
							onChange: function (value) {
								setAttributes({ featureTwoTitle: value });
							},
						}),
						el(TextControl, {
							label: __('Feature 2 description', 'sandbox'),
							placeholder: phText(),
							value: attributes.featureTwoText,
							onChange: function (value) {
								setAttributes({ featureTwoText: value });
							},
						}),
						el(TextControl, {
							label: __('Feature 3 title', 'sandbox'),
							placeholder: phText(),
							value: attributes.featureThreeTitle,
							onChange: function (value) {
								setAttributes({ featureThreeTitle: value });
							},
						}),
						el(TextControl, {
							label: __('Feature 3 description', 'sandbox'),
							placeholder: phText(),
							value: attributes.featureThreeText,
							onChange: function (value) {
								setAttributes({ featureThreeText: value });
							},
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
						{ className: 'home-feature-grid' },
						el(
							'article',
							{ className: 'feature-card' },
							previewLine(attributes.featureOneTitle, 'h3'),
							previewLine(attributes.featureOneText, 'p')
						),
						el(
							'article',
							{ className: 'feature-card' },
							previewLine(attributes.featureTwoTitle, 'h3'),
							previewLine(attributes.featureTwoText, 'p')
						),
						el(
							'article',
							{ className: 'feature-card' },
							previewLine(attributes.featureThreeTitle, 'h3'),
							previewLine(attributes.featureThreeText, 'p')
						)
					)
				)
			);
		},
		save: function () {
			return null;
		},
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, URLInput } = blockEditor;
	const { PanelBody, TextControl, RangeControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	const phText = function () {
		return __('Enter text here', 'sandbox');
	};
	const phUrl = function () {
		return __('Enter URL here', 'sandbox');
	};
	const phNumber = function () {
		return __('Enter number here', 'sandbox');
	};
	const phLabel = function () {
		return __('Enter label here', 'sandbox');
	};

	registerBlockType('sandbox/hero', {
		title: __('Hero', 'sandbox'),
		description: __('A customizable marketing hero section.', 'sandbox'),
		icon: 'cover-image',
		category: 'design',
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
		},
		edit: function (props) {
			const { attributes, setAttributes } = props;

			const previewText = function (value) {
				return value || phText();
			};
			const previewStat = function (num, label, phNum, phLab) {
				return el(
					'div',
					{},
					el('span', { className: num ? '' : 'sandbox-block-placeholder' }, num || phNum()),
					el('p', { className: label ? '' : 'sandbox-block-placeholder' }, label || phLab())
				);
			};

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Layout', 'sandbox'), initialOpen: true },
						el(RangeControl, {
							label: __('Max width (px)', 'sandbox'),
							min: 720,
							max: 1440,
							value: attributes.maxWidth,
							onChange: function (value) {
								setAttributes({ maxWidth: value || 1120 });
							},
						}),
						el(RangeControl, {
							label: __('Top padding (px)', 'sandbox'),
							min: 24,
							max: 160,
							value: attributes.topPadding,
							onChange: function (value) {
								setAttributes({ topPadding: value || 72 });
							},
						}),
						el(RangeControl, {
							label: __('Bottom padding (px)', 'sandbox'),
							min: 24,
							max: 160,
							value: attributes.bottomPadding,
							onChange: function (value) {
								setAttributes({ bottomPadding: value || 72 });
							},
						})
					),
					el(
						PanelBody,
						{ title: __('Buttons', 'sandbox'), initialOpen: false },
						el(TextControl, {
							label: __('Primary button text', 'sandbox'),
							placeholder: phText(),
							value: attributes.buttonText,
							onChange: function (value) {
								setAttributes({ buttonText: value });
							},
						}),
						el(URLInput, {
							label: __('Primary button URL', 'sandbox'),
							placeholder: phUrl(),
							value: attributes.buttonUrl,
							onChange: function (value) {
								setAttributes({ buttonUrl: value });
							},
						}),
						el(TextControl, {
							label: __('Secondary button text', 'sandbox'),
							placeholder: phText(),
							value: attributes.secondaryButtonText,
							onChange: function (value) {
								setAttributes({ secondaryButtonText: value });
							},
						}),
						el(URLInput, {
							label: __('Secondary button URL', 'sandbox'),
							placeholder: phUrl(),
							value: attributes.secondaryButtonUrl,
							onChange: function (value) {
								setAttributes({ secondaryButtonUrl: value });
							},
						})
					),
					el(
						PanelBody,
						{ title: __('Stats', 'sandbox'), initialOpen: false },
						el(TextControl, {
							label: __('Stat 1 number', 'sandbox'),
							placeholder: phNumber(),
							value: attributes.statOneNumber,
							onChange: function (value) {
								setAttributes({ statOneNumber: value });
							},
						}),
						el(TextControl, {
							label: __('Stat 1 label', 'sandbox'),
							placeholder: phLabel(),
							value: attributes.statOneLabel,
							onChange: function (value) {
								setAttributes({ statOneLabel: value });
							},
						}),
						el(TextControl, {
							label: __('Stat 2 number', 'sandbox'),
							placeholder: phNumber(),
							value: attributes.statTwoNumber,
							onChange: function (value) {
								setAttributes({ statTwoNumber: value });
							},
						}),
						el(TextControl, {
							label: __('Stat 2 label', 'sandbox'),
							placeholder: phLabel(),
							value: attributes.statTwoLabel,
							onChange: function (value) {
								setAttributes({ statTwoLabel: value });
							},
						}),
						el(TextControl, {
							label: __('Stat 3 number', 'sandbox'),
							placeholder: phNumber(),
							value: attributes.statThreeNumber,
							onChange: function (value) {
								setAttributes({ statThreeNumber: value });
							},
						}),
						el(TextControl, {
							label: __('Stat 3 label', 'sandbox'),
							placeholder: phLabel(),
							value: attributes.statThreeLabel,
							onChange: function (value) {
								setAttributes({ statThreeLabel: value });
							},
						})
					)
				),
				el(
					'section',
					{
						className: 'sandbox-hero-block',
						style: {
							'--sandbox-hero-max-width': attributes.maxWidth + 'px',
							'--sandbox-hero-padding-top': attributes.topPadding + 'px',
							'--sandbox-hero-padding-bottom': attributes.bottomPadding + 'px',
						},
					},
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
								placeholder: phText(),
								onChange: function (value) {
									setAttributes({ eyebrow: value });
								},
							}),
							el(RichText, {
								tagName: 'h2',
								className: 'sandbox-hero-block__title',
								value: attributes.title,
								placeholder: phText(),
								onChange: function (value) {
									setAttributes({ title: value });
								},
							}),
							el(RichText, {
								tagName: 'p',
								className: 'sandbox-hero-block__intro',
								value: attributes.intro,
								placeholder: phText(),
								onChange: function (value) {
									setAttributes({ intro: value });
								},
							}),
							el(
								'div',
								{ className: 'marketing-actions' },
								el(
									'span',
									{
										className: 'button button-primary' + (attributes.buttonText ? '' : ' sandbox-block-placeholder'),
									},
									previewText(attributes.buttonText)
								),
								el(
									'span',
									{
										className: 'button button-secondary' + (attributes.secondaryButtonText ? '' : ' sandbox-block-placeholder'),
									},
									previewText(attributes.secondaryButtonText)
								)
							)
						),
						el(
							'div',
							{ className: 'sandbox-hero-block__panel' },
							previewStat(attributes.statOneNumber, attributes.statOneLabel, phNumber, phLabel),
							previewStat(attributes.statTwoNumber, attributes.statTwoLabel, phNumber, phLabel),
							previewStat(attributes.statThreeNumber, attributes.statThreeLabel, phNumber, phLabel)
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

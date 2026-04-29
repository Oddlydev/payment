/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, URLInput } = blockEditor;
	const { PanelBody, TextControl, RangeControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	registerBlockType('sandbox/hero', {
		title: __('Hero', 'sandbox'),
		description: __('A customizable marketing hero section.', 'sandbox'),
		icon: 'cover-image',
		category: 'design',
		attributes: {
			eyebrow: { type: 'string', default: __('Professional training made simple', 'sandbox') },
			title: { type: 'string', default: __('Build skills faster with focused online training.', 'sandbox') },
			intro: {
				type: 'string',
				default: __('A clean learning experience for teams and individuals who want practical lessons, clear progress, and direct access to paid course enrollment.', 'sandbox'),
			},
			buttonText: { type: 'string', default: __('Enroll now', 'sandbox') },
			buttonUrl: { type: 'string', default: '/checkout/' },
			secondaryButtonText: { type: 'string', default: __('View features', 'sandbox') },
			secondaryButtonUrl: { type: 'string', default: '#' },
			statOneNumber: { type: 'string', default: __('8 weeks', 'sandbox') },
			statOneLabel: { type: 'string', default: __('Structured learning path', 'sandbox') },
			statTwoNumber: { type: 'string', default: __('24/7', 'sandbox') },
			statTwoLabel: { type: 'string', default: __('Access to course materials', 'sandbox') },
			statThreeNumber: { type: 'string', default: __('Certificate', 'sandbox') },
			statThreeLabel: { type: 'string', default: __('Issued after completion', 'sandbox') },
			maxWidth: { type: 'number', default: 1120 },
			topPadding: { type: 'number', default: 72 },
			bottomPadding: { type: 'number', default: 72 },
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
							value: attributes.buttonText,
							onChange: function (value) {
								setAttributes({ buttonText: value });
							},
						}),
						el(URLInput, {
							label: __('Primary button URL', 'sandbox'),
							value: attributes.buttonUrl,
							onChange: function (value) {
								setAttributes({ buttonUrl: value });
							},
						}),
						el(TextControl, {
							label: __('Secondary button text', 'sandbox'),
							value: attributes.secondaryButtonText,
							onChange: function (value) {
								setAttributes({ secondaryButtonText: value });
							},
						}),
						el(URLInput, {
							label: __('Secondary button URL', 'sandbox'),
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
							value: attributes.statOneNumber,
							onChange: function (value) {
								setAttributes({ statOneNumber: value });
							},
						}),
						el(TextControl, {
							label: __('Stat 1 label', 'sandbox'),
							value: attributes.statOneLabel,
							onChange: function (value) {
								setAttributes({ statOneLabel: value });
							},
						}),
						el(TextControl, {
							label: __('Stat 2 number', 'sandbox'),
							value: attributes.statTwoNumber,
							onChange: function (value) {
								setAttributes({ statTwoNumber: value });
							},
						}),
						el(TextControl, {
							label: __('Stat 2 label', 'sandbox'),
							value: attributes.statTwoLabel,
							onChange: function (value) {
								setAttributes({ statTwoLabel: value });
							},
						}),
						el(TextControl, {
							label: __('Stat 3 number', 'sandbox'),
							value: attributes.statThreeNumber,
							onChange: function (value) {
								setAttributes({ statThreeNumber: value });
							},
						}),
						el(TextControl, {
							label: __('Stat 3 label', 'sandbox'),
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
								placeholder: __('Eyebrow text', 'sandbox'),
								onChange: function (value) {
									setAttributes({ eyebrow: value });
								},
							}),
							el(RichText, {
								tagName: 'h2',
								className: 'sandbox-hero-block__title',
								value: attributes.title,
								placeholder: __('Hero title', 'sandbox'),
								onChange: function (value) {
									setAttributes({ title: value });
								},
							}),
							el(RichText, {
								tagName: 'p',
								className: 'sandbox-hero-block__intro',
								value: attributes.intro,
								placeholder: __('Intro text', 'sandbox'),
								onChange: function (value) {
									setAttributes({ intro: value });
								},
							}),
							el(
								'div',
								{ className: 'marketing-actions' },
								el('span', { className: 'button button-primary' }, attributes.buttonText),
								el('span', { className: 'button button-secondary' }, attributes.secondaryButtonText)
							)
						),
						el(
							'div',
							{ className: 'sandbox-hero-block__panel' },
							el('div', {}, el('span', {}, attributes.statOneNumber), el('p', {}, attributes.statOneLabel)),
							el('div', {}, el('span', {}, attributes.statTwoNumber), el('p', {}, attributes.statTwoLabel)),
							el('div', {}, el('span', {}, attributes.statThreeNumber), el('p', {}, attributes.statThreeLabel))
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

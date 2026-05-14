/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, useBlockProps, PanelColorSettings } = blockEditor;
	const { PanelBody, RangeControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	const DEFAULT_STATS = [
		{ value: '98%', label: __('Customer satisfaction', 'sandbox') },
		{ value: '42k+', label: __('Learners enrolled', 'sandbox') },
		{ value: '120+', label: __('Reusable page sections', 'sandbox') },
		{ value: '24/7', label: __('Access to resources', 'sandbox') },
	];

	const getStats = function (stats) {
		return DEFAULT_STATS.map(function (defaultStat, index) {
			const savedStat = Array.isArray(stats) && stats[index] ? stats[index] : {};
			return {
				value: savedStat.value !== undefined ? savedStat.value : defaultStat.value,
				label: savedStat.label !== undefined ? savedStat.label : defaultStat.label,
			};
		});
	};

	registerBlockType('sandbox/stats', {
		title: __('Stats', 'sandbox'),
		description: __('A metrics section for numbers, outcomes, and proof points.', 'sandbox'),
		icon: 'chart-bar',
		category: 'design',
		supports: {
			align: ['wide', 'full'],
			anchor: true,
			className: true,
		},
		attributes: {
			eyebrow: { type: 'string', default: __('Results', 'sandbox') },
			title: { type: 'string', default: __('Numbers that show the impact', 'sandbox') },
			statCount: { type: 'number', default: 3 },
			stats: { type: 'array', default: [] },
			backgroundColor: { type: 'string', default: '' },
			textColor: { type: 'string', default: '' },
		},
		edit: function (props) {
			const { attributes, setAttributes } = props;
			const statCount = Math.min(Math.max(attributes.statCount || 3, 2), 4);
			const stats = getStats(attributes.stats);
			const wrapperStyle = {};
			if (attributes.backgroundColor) { wrapperStyle.backgroundColor = attributes.backgroundColor; }
			if (attributes.textColor) { wrapperStyle.color = attributes.textColor; }
			const blockProps = useBlockProps({ className: 'sandbox-stats-block home-section', style: wrapperStyle });

			const setStat = function (index, field, value) {
				const nextStats = getStats(attributes.stats);
				nextStats[index] = Object.assign({}, nextStats[index], { [field]: value });
				setAttributes({ stats: nextStats });
			};

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Stats settings', 'sandbox'), initialOpen: true },
						el(RangeControl, {
							label: __('Number of stats', 'sandbox'),
							min: 2,
							max: 4,
							step: 1,
							value: statCount,
							onChange: function (value) {
								setAttributes({
									statCount: Math.min(Math.max(value || 3, 2), 4),
									stats: getStats(attributes.stats),
								});
							},
						})
					),
					el(PanelColorSettings, {
						title: __('Colors', 'sandbox'),
						initialOpen: false,
						colorSettings: [
							{
								value: attributes.backgroundColor,
								onChange: function (value) { setAttributes({ backgroundColor: value || '' }); },
								label: __('Background color', 'sandbox'),
							},
							{
								value: attributes.textColor,
								onChange: function (value) { setAttributes({ textColor: value || '' }); },
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
							onChange: function (value) { setAttributes({ eyebrow: value }); },
						}),
						el(RichText, {
							tagName: 'h2',
							value: attributes.title,
							placeholder: __('Enter section title', 'sandbox'),
							onChange: function (value) { setAttributes({ title: value }); },
						})
					),
					el(
						'div',
						{ className: 'sandbox-stats-grid sandbox-stats-grid--count-' + statCount },
						stats.slice(0, statCount).map(function (stat, index) {
							return el(
								'div',
								{ className: 'sandbox-stat-card', key: index },
								el(RichText, {
									tagName: 'strong',
									value: stat.value,
									placeholder: __('Value', 'sandbox'),
									allowedFormats: [],
									onChange: function (value) { setStat(index, 'value', value); },
								}),
								el(RichText, {
									tagName: 'span',
									value: stat.label,
									placeholder: __('Label', 'sandbox'),
									allowedFormats: [],
									onChange: function (value) { setStat(index, 'label', value); },
								})
							);
						})
					)
				)
			);
		},
		save: function () { return null; },
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

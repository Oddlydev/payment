/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, useBlockProps, PanelColorSettings } = blockEditor;
	const { PanelBody, RangeControl, SelectControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	const DEFAULT_CARDS = [
		{
			title: __('Course-ready pages', 'sandbox'),
			text: __('Present learning outcomes, schedules, pricing, and enrollment details in a simple marketing flow', 'sandbox'),
		},
		{
			title: __('Course-ready pages 2', 'sandbox'),
			text: __('Present learning outcomes, schedules, pricing, and enrollment details in a simple marketing flow', 'sandbox'),
		},
		{
			title: __('Course-ready pages 3', 'sandbox'),
			text: __('Present learning outcomes, schedules, pricing, and enrollment details in a simple marketing flow', 'sandbox'),
		},
		{
			title: __('Course-ready pages 4', 'sandbox'),
			text: __('Present learning outcomes, schedules, pricing, and enrollment details in a simple marketing flow', 'sandbox'),
		},
	];

	const getCards = function (cards) {
		return DEFAULT_CARDS.map(function (defaultCard, index) {
			const savedCard = Array.isArray(cards) && cards[index] ? cards[index] : {};
			return {
				title: savedCard.title !== undefined ? savedCard.title : defaultCard.title,
				text: savedCard.text !== undefined ? savedCard.text : defaultCard.text,
			};
		});
	};

	const getLayoutOptions = function (cardCount) {
		const options = [
			{ label: __('Equal columns', 'sandbox'), value: 'equal' },
			{ label: __('Feature first card', 'sandbox'), value: 'featured-left' },
			{ label: __('Feature last card', 'sandbox'), value: 'featured-right' },
		];

		if (cardCount === 4) {
			options.push({ label: __('Two by two grid', 'sandbox'), value: 'two-by-two' });
		}

		return options;
	};

	registerBlockType('sandbox/card-layout', {
		title: __('Card Layout', 'sandbox'),
		description: __('A configurable 2-4 card section with selectable layouts.', 'sandbox'),
		icon: 'grid-view',
		category: 'design',
		supports: {
			align: ['wide', 'full'],
			anchor: true,
			className: true,
		},
		attributes: {
			eyebrow: { type: 'string', default: __('Featured', 'sandbox') },
			title: { type: 'string', default: __('Created Using Custom Gutenberg Blocks', 'sandbox') },
			cardCount: { type: 'number', default: 3 },
			layout: { type: 'string', default: 'equal' },
			cards: { type: 'array', default: [] },
			backgroundColor: { type: 'string', default: '' },
			textColor: { type: 'string', default: '' },
		},
		edit: function (props) {
			const { attributes, setAttributes } = props;
			const cardCount = Math.min(Math.max(attributes.cardCount || 3, 2), 4);
			const layout = attributes.layout === 'two-by-two' && cardCount !== 4 ? 'equal' : (attributes.layout || 'equal');
			const cards = getCards(attributes.cards);

			const wrapperStyle = {};
			if (attributes.backgroundColor) { wrapperStyle.backgroundColor = attributes.backgroundColor; }
			if (attributes.textColor) { wrapperStyle.color = attributes.textColor; }

			const blockProps = useBlockProps({ className: 'sandbox-card-layout-block home-section', style: wrapperStyle });

			const setCard = function (index, field, value) {
				const nextCards = getCards(attributes.cards);
				nextCards[index] = Object.assign({}, nextCards[index], { [field]: value });
				setAttributes({ cards: nextCards });
			};

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Card layout', 'sandbox'), initialOpen: true },
						el(RangeControl, {
							label: __('Number of cards', 'sandbox'),
							min: 2,
							max: 4,
							step: 1,
							value: cardCount,
							onChange: function (value) {
								const nextCount = Math.min(Math.max(value || 3, 2), 4);
								setAttributes({
									cardCount: nextCount,
									layout: attributes.layout === 'two-by-two' && nextCount !== 4 ? 'equal' : attributes.layout,
									cards: getCards(attributes.cards),
								});
							},
						}),
						el(SelectControl, {
							label: __('Layout', 'sandbox'),
							value: layout,
							options: getLayoutOptions(cardCount),
							onChange: function (value) { setAttributes({ layout: value }); },
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
						{ className: 'sandbox-card-layout sandbox-card-layout--' + layout + ' sandbox-card-layout--count-' + cardCount },
						cards.slice(0, cardCount).map(function (card, index) {
							return el(
								'article',
								{ className: 'sandbox-card-layout__card', key: index },
								el(RichText, {
									tagName: 'h3',
									value: card.title,
									placeholder: __('Card title', 'sandbox'),
									onChange: function (value) { setCard(index, 'title', value); },
								}),
								el(RichText, {
									tagName: 'p',
									value: card.text,
									placeholder: __('Card description', 'sandbox'),
									onChange: function (value) { setCard(index, 'text', value); },
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

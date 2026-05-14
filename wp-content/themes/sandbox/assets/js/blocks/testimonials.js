/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, useBlockProps, PanelColorSettings } = blockEditor;
	const { PanelBody, RangeControl, SelectControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	const DEFAULT_ITEMS = [
		{ quote: __('The page sections were easy to edit and gave us a polished launch page quickly.', 'sandbox'), name: __('Amaya Perera', 'sandbox'), role: __('Program Lead', 'sandbox') },
		{ quote: __('We could change the message, layout, and calls to action without touching code.', 'sandbox'), name: __('Nuwan Silva', 'sandbox'), role: __('Marketing Manager', 'sandbox') },
		{ quote: __('The blocks made every page feel consistent while still giving each section flexibility.', 'sandbox'), name: __('Kavindi Fernando', 'sandbox'), role: __('Course Creator', 'sandbox') },
	];

	const getItems = function (items) {
		return DEFAULT_ITEMS.map(function (defaultItem, index) {
			const savedItem = Array.isArray(items) && items[index] ? items[index] : {};
			return {
				quote: savedItem.quote !== undefined ? savedItem.quote : defaultItem.quote,
				name: savedItem.name !== undefined ? savedItem.name : defaultItem.name,
				role: savedItem.role !== undefined ? savedItem.role : defaultItem.role,
			};
		});
	};

	registerBlockType('sandbox/testimonials', {
		title: __('Testimonials', 'sandbox'),
		description: __('A social proof section with editable customer quotes.', 'sandbox'),
		icon: 'format-quote',
		category: 'design',
		supports: {
			align: ['wide', 'full'],
			anchor: true,
			className: true,
		},
		attributes: {
			eyebrow: { type: 'string', default: __('Testimonials', 'sandbox') },
			title: { type: 'string', default: __('What customers are saying', 'sandbox') },
			testimonialCount: { type: 'number', default: 3 },
			layout: { type: 'string', default: 'grid' },
			testimonials: { type: 'array', default: [] },
			backgroundColor: { type: 'string', default: '' },
			textColor: { type: 'string', default: '' },
		},
		edit: function (props) {
			const { attributes, setAttributes } = props;
			const testimonialCount = Math.min(Math.max(attributes.testimonialCount || 3, 1), 3);
			const layout = attributes.layout || 'grid';
			const testimonials = getItems(attributes.testimonials);
			const wrapperStyle = {};
			if (attributes.backgroundColor) { wrapperStyle.backgroundColor = attributes.backgroundColor; }
			if (attributes.textColor) { wrapperStyle.color = attributes.textColor; }
			const blockProps = useBlockProps({ className: 'sandbox-testimonials-block home-section', style: wrapperStyle });

			const setItem = function (index, field, value) {
				const nextItems = getItems(attributes.testimonials);
				nextItems[index] = Object.assign({}, nextItems[index], { [field]: value });
				setAttributes({ testimonials: nextItems });
			};

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Testimonials settings', 'sandbox'), initialOpen: true },
						el(RangeControl, {
							label: __('Number of testimonials', 'sandbox'),
							min: 1,
							max: 3,
							step: 1,
							value: testimonialCount,
							onChange: function (value) {
								setAttributes({
									testimonialCount: Math.min(Math.max(value || 3, 1), 3),
									testimonials: getItems(attributes.testimonials),
								});
							},
						}),
						el(SelectControl, {
							label: __('Layout', 'sandbox'),
							value: layout,
							options: [
								{ label: __('Grid', 'sandbox'), value: 'grid' },
								{ label: __('Featured quote', 'sandbox'), value: 'featured' },
							],
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
						{ className: 'sandbox-testimonials sandbox-testimonials--' + layout + ' sandbox-testimonials--count-' + testimonialCount },
						testimonials.slice(0, testimonialCount).map(function (item, index) {
							return el(
								'figure',
								{ className: 'sandbox-testimonial-card', key: index },
								el(RichText, {
									tagName: 'blockquote',
									value: item.quote,
									placeholder: __('Customer quote', 'sandbox'),
									onChange: function (value) { setItem(index, 'quote', value); },
								}),
								el(
									'figcaption',
									{},
									el(RichText, {
										tagName: 'strong',
										value: item.name,
										placeholder: __('Name', 'sandbox'),
										allowedFormats: [],
										onChange: function (value) { setItem(index, 'name', value); },
									}),
									el(RichText, {
										tagName: 'span',
										value: item.role,
										placeholder: __('Role or company', 'sandbox'),
										allowedFormats: [],
										onChange: function (value) { setItem(index, 'role', value); },
									})
								)
							);
						})
					)
				)
			);
		},
		save: function () { return null; },
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

/* global wp */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, RichText, useBlockProps, PanelColorSettings } = blockEditor;
	const { PanelBody, RangeControl, SelectControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	const DEFAULT_ITEMS = [
		{ question: __('Can I edit the section content?', 'sandbox'), answer: __('Yes. Each question and answer is editable directly in the block preview.', 'sandbox') },
		{ question: __('Can I change how many FAQs are shown?', 'sandbox'), answer: __('Yes. The block supports two to six FAQ items from the sidebar settings.', 'sandbox') },
		{ question: __('Will this render on the frontend?', 'sandbox'), answer: __('Yes. The saved page is rendered by PHP using the same attributes from the editor.', 'sandbox') },
		{ question: __('Can I use it on full-width pages?', 'sandbox'), answer: __('Yes. The block supports wide and full alignment like the other custom blocks.', 'sandbox') },
		{ question: __('Can I change colors?', 'sandbox'), answer: __('Yes. Background and text colors are available in the block inspector.', 'sandbox') },
		{ question: __('Does it need extra JavaScript?', 'sandbox'), answer: __('No. The frontend output uses native details and summary elements.', 'sandbox') },
	];

	const getItems = function (items) {
		return DEFAULT_ITEMS.map(function (defaultItem, index) {
			const savedItem = Array.isArray(items) && items[index] ? items[index] : {};
			return {
				question: savedItem.question !== undefined ? savedItem.question : defaultItem.question,
				answer: savedItem.answer !== undefined ? savedItem.answer : defaultItem.answer,
			};
		});
	};

	registerBlockType('sandbox/faq', {
		title: __('FAQ', 'sandbox'),
		description: __('An editable FAQ section using native accordion items on the frontend.', 'sandbox'),
		icon: 'editor-help',
		category: 'design',
		supports: {
			align: ['wide', 'full'],
			anchor: true,
			className: true,
		},
		attributes: {
			eyebrow: { type: 'string', default: __('FAQ', 'sandbox') },
			title: { type: 'string', default: __('Frequently asked questions', 'sandbox') },
			faqCount: { type: 'number', default: 4 },
			layout: { type: 'string', default: 'single' },
			items: { type: 'array', default: [] },
			backgroundColor: { type: 'string', default: '' },
			textColor: { type: 'string', default: '' },
		},
		edit: function (props) {
			const { attributes, setAttributes } = props;
			const faqCount = Math.min(Math.max(attributes.faqCount || 4, 2), 6);
			const layout = attributes.layout || 'single';
			const items = getItems(attributes.items);
			const wrapperStyle = {};
			if (attributes.backgroundColor) { wrapperStyle.backgroundColor = attributes.backgroundColor; }
			if (attributes.textColor) { wrapperStyle.color = attributes.textColor; }
			const blockProps = useBlockProps({ className: 'sandbox-faq-block home-section', style: wrapperStyle });

			const setItem = function (index, field, value) {
				const nextItems = getItems(attributes.items);
				nextItems[index] = Object.assign({}, nextItems[index], { [field]: value });
				setAttributes({ items: nextItems });
			};

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('FAQ settings', 'sandbox'), initialOpen: true },
						el(RangeControl, {
							label: __('Number of questions', 'sandbox'),
							min: 2,
							max: 6,
							step: 1,
							value: faqCount,
							onChange: function (value) {
								setAttributes({
									faqCount: Math.min(Math.max(value || 4, 2), 6),
									items: getItems(attributes.items),
								});
							},
						}),
						el(SelectControl, {
							label: __('Layout', 'sandbox'),
							value: layout,
							options: [
								{ label: __('Single column', 'sandbox'), value: 'single' },
								{ label: __('Two columns', 'sandbox'), value: 'two-column' },
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
						{ className: 'sandbox-faq-list sandbox-faq-list--' + layout },
						items.slice(0, faqCount).map(function (item, index) {
							return el(
								'div',
								{ className: 'sandbox-faq-item', key: index },
								el(RichText, {
									tagName: 'h3',
									value: item.question,
									placeholder: __('Question', 'sandbox'),
									onChange: function (value) { setItem(index, 'question', value); },
								}),
								el(RichText, {
									tagName: 'p',
									value: item.answer,
									placeholder: __('Answer', 'sandbox'),
									onChange: function (value) { setItem(index, 'answer', value); },
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

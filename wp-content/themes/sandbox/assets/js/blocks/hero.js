/* global wp */
/**
 * Sandbox Hero block — container with InnerBlocks.
 *
 * The Hero is now a layout container. The author composes the section using
 * any core blocks they want (heading, paragraph, buttons, columns, group,
 * image, etc.). Every inner block keeps its OWN toolbar — so alignment,
 * text color, background color, typography, etc. are changed per element
 * exactly the way Gutenberg does it natively.
 *
 * The Hero block itself only owns the section-level concerns:
 *   - max width of the inner column
 *   - full / wide alignment
 *   - background color, text color, padding (via core block supports)
 */
(function (blocks, blockEditor, components, element, i18n) {
	const { registerBlockType } = blocks;
	const { InspectorControls, InnerBlocks, useBlockProps } = blockEditor;
	const { PanelBody, RangeControl } = components;
	const { createElement: el, Fragment } = element;
	const { __ } = i18n;

	/**
	 * Default starter content shown when the block is first inserted.
	 * The user can change, remove, or add blocks freely after that.
	 */
	const TEMPLATE = [
		['core/paragraph', {
			placeholder: __('Eyebrow text', 'sandbox'),
			className: 'marketing-eyebrow',
		}],
		['core/heading', {
			level: 2,
			placeholder: __('Hero title', 'sandbox'),
			className: 'sandbox-hero-block__title',
		}],
		['core/paragraph', {
			placeholder: __('Intro paragraph — describe the offer in one or two sentences.', 'sandbox'),
			className: 'sandbox-hero-block__intro',
		}],
		['core/buttons', {}, [
			['core/button', { text: __('Primary action', 'sandbox') }],
			['core/button', { text: __('Secondary action', 'sandbox'), className: 'is-style-outline' }],
		]],
	];

	registerBlockType('sandbox/hero', {
		title: __('Hero', 'sandbox'),
		description: __('A flexible hero section. Drop any blocks inside — each child has its own alignment, color, and typography controls.', 'sandbox'),
		icon: 'cover-image',
		category: 'design',
		supports: {
			align: ['wide', 'full'],
			anchor: true,
			className: true,
			color: { background: true, text: true, gradients: true, link: true },
			spacing: { padding: true, margin: ['top', 'bottom'], blockGap: true },
			typography: { fontSize: true, lineHeight: true },
		},
		attributes: {
			maxWidth: { type: 'number', default: 1120 },
		},
		edit: function (props) {
			const { attributes, setAttributes } = props;

			const blockProps = useBlockProps({
				className: 'sandbox-hero-block',
				style: { '--sandbox-hero-max-width': attributes.maxWidth + 'px' },
			});

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Hero layout', 'sandbox'), initialOpen: true },
						el(RangeControl, {
							label: __('Inner max width (px)', 'sandbox'),
							help: __('Width of the centered content column. Use full-width alignment in the toolbar to make the background fill the viewport.', 'sandbox'),
							min: 720, max: 1600, step: 10,
							value: attributes.maxWidth,
							onChange: function (v) { setAttributes({ maxWidth: v || 1120 }); },
						})
					)
				),
				el(
					'section',
					blockProps,
					el(
						'div',
						{ className: 'sandbox-hero-block__inner' },
						el(InnerBlocks, {
							template: TEMPLATE,
							templateLock: false,
							renderAppender: InnerBlocks.ButtonBlockAppender,
						})
					)
				)
			);
		},
		save: function () {
			// Dynamic block — render handled by PHP, but inner blocks must be
			// saved so their content lives in post_content.
			return el(InnerBlocks.Content);
		},
	});
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);

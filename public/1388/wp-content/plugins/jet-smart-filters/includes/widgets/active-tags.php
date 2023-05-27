<?php

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Smart_Filters_Active_Tags_Widget extends Widget_Base {

	public function get_name() {
		return 'jet-smart-filters-active-tags';
	}

	public function get_title() {
		return __( 'Active Tags', 'jet-smart-filters' );
	}

	public function get_icon() {
		return 'jet-smart-filters-icon-active-filters';
	}

	public function get_help_url() {
		return jet_smart_filters()->widgets->prepare_help_url(
			'https://crocoblock.com/knowledge-base/articles/jetsmartfilters-how-to-enable-visitors-to-disable-active-filters/',
			$this->get_name()
		);
	}

	public function get_categories() {
		return array( jet_smart_filters()->widgets->get_category() );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-smart-filters/widgets/active-tags/css-scheme',
			array(
				'tags'       => '.jet-smart-filters-active-tags',
				'tags-list'  => '.jet-active-tags__list',
				'tags-title' => '.jet-active-tags__title',
				'tag'        => '.jet-active-tag',
				'tag-label'  => '.jet-active-tag__label',
				'tag-value'  => '.jet-active-tag__val',
				'tag-remove' => '.jet-active-tag__remove',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'Content', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'content_provider',
			array(
				'label'   => __( 'Show active tags for:', 'jet-smart-filters' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => jet_smart_filters()->data->content_providers(),
			)
		);

		$this->add_control(
			'additional_providers_enabled',
			array(
				'label'        => esc_html__( 'Additional Providers Enabled', 'jet-smart-filters' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => '',
				'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
				'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'additional_providers',
			array(
				'label'       => __( 'Additional Providers List', 'jet-smart-filters' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'default'     => '',
				'options'     => jet_smart_filters()->data->content_providers(),
				'condition'   => array(
					'additional_providers_enabled' => 'yes',
				),
			)
		);

		$this->add_control(
			'apply_type',
			array(
				'label'   => __( 'Apply type', 'jet-smart-filters' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ajax',
				'options' => array(
					'ajax'   => __( 'AJAX', 'jet-smart-filters' ),
					'reload' => __( 'Page reload', 'jet-smart-filters' ),
				),
			)
		);

		$this->add_control(
			'tags_label',
			array(
				'label'   => esc_html__( 'Label', 'jet-smart-filters' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Active tags:', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'clear_item',
			array(
				'label'        => esc_html__( 'Clear Item', 'jet-smart-filters' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
				'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
				'return_value' => 'yes',
				'default'      => 'yes'
			)
		);

		$this->add_control(
			'clear_item_label',
			array(
				'label'     => esc_html__( 'Clear Item Label', 'jet-smart-filters' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Clear', 'jet-smart-filters' ),
				'condition' => array(
					'clear_item' => 'yes'
				)
			)
		);

		$this->add_control(
			'query_id',
			array(
				'label'       => esc_html__( 'Query ID', 'jet-smart-filters' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Set unique query ID if you use multiple widgets of same provider on the page. Same ID you need to set for filtered widget.', 'jet-smart-filters' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tags_title_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tags_title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['tags-title'] ,
			)
		);

		$this->add_control(
			'tags_title_color',
			array(
				'label' => esc_html__( 'Color', 'jet-smart-filters' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tags-title'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'tags_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tags-title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tags_styles',
			array(
				'label'      => __( 'Tags List', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'tags_position',
			array(
				'label'       => esc_html__( 'Tags Position', 'jet-smart-filters' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle'      => false,
				'default'     => 'row',
				'options'     => array(
					'row'    => array(
						'title' => esc_html__( 'Line', 'jet-smart-filters' ),
						'icon'  => 'fa fa-ellipsis-h',
					),
					'column' => array(
						'title' => esc_html__( 'Column', 'jet-smart-filters' ),
						'icon'  => 'fa fa-bars',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['tags'] => 'flex-direction: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['tags-list'] => 'flex-direction: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'tags_space_between_horizontal',
			array(
				'label'      => esc_html__( 'Horizontal Offset', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 5,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'tags_position' => 'row'
				)
			)
		);

		$this->add_responsive_control(
			'tags_space_between_vertical',
			array(
				'label'      => esc_html__( 'Vertical Offset', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 5,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tags_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tags'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tags_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['tags'],
			)
		);

		$this->add_control(
			'tags_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tags'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tags_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['tags'],
			)
		);

		$this->add_responsive_control(
			'tags_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tags'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tags_alignment_column',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-smart-filters' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tags-list'] => 'align-items: {{VALUE}};',
				),
				'condition' => array(
					'tags_position' => 'column'
				)
			)
		);

		$this->add_responsive_control(
			'tags_alignment_line',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-smart-filters' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'jet-smart-filters' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tags-list'] => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'tags_position' => 'row'
				)
			)
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_tag_styles',
			array(
				'label'      => __( 'Tag Item', 'jet-smart-filters' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'tag_min_width',
			array(
				'label'      => esc_html__( 'Minimal Width', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 500,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tag_value_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['tag-value'],
			)
		);

		$this->start_controls_tabs( 'tag_style_tabs' );

		$this->start_controls_tab(
			'tag_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'tag_value_normal_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag-value'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tag_normal_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tag_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'tag_value_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] . ':hover ' . $css_scheme['tag-value'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tag_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tag_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] . ':hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tag_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['tag'],
			)
		);

		$this->add_control(
			'tag_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tag_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['tag'],
			)
		);

		$this->add_responsive_control(
			'tag_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tags_clear_item_heading',
			array(
				'label'     => esc_html__( 'Clear Item', 'jet-smart-filters' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tag_clear_style_tabs' );

		$this->start_controls_tab(
			'tag_clear_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'tag_clear_normal_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] . '--clear ' . $css_scheme['tag-value'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tag_clear_normal_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] . '--clear ' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tag_clear_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'tag_clear_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] . '--clear' . ':hover ' . $css_scheme['tag-value'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tag_clear_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] . '--clear' . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tag_clear_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] . '--clear' . ':hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'tag_remove_heading',
			array(
				'label'     => esc_html__( 'Remove', 'jet-smart-filters' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'tag_remove_size',
			array(
				'label'      => esc_html__( 'Size', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 12,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tag-remove'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tag_remove_offset_top',
			array(
				'label'      => esc_html__( 'Offset Top', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tag-remove'] => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tag_remove_offset_right',
			array(
				'label'      => esc_html__( 'Offset Right', 'jet-smart-filters' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tag-remove'] => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tag_remove_style_tabs' );

		$this->start_controls_tab(
			'tag_remove_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'tag_remove_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag-remove'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tag_remove_normal_bg_color',
			array(
				'label' => esc_html__( 'Background', 'jet-smart-filters' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag-remove'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tag_remove_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'tag_remove_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] . ':hover ' . $css_scheme['tag-remove'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tag_remove_hover_bg_color',
			array(
				'label' => esc_html__( 'Background', 'jet-smart-filters' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] . ':hover ' . $css_scheme['tag-remove'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'tag_remove_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-smart-filters' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['tag'] . ':hover ' . $css_scheme['tag-remove'] => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tag_remove_border',
				'label'       => esc_html__( 'Border', 'jet-smart-filters' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['tag-remove'],
			)
		);

		$this->add_control(
			'tag_remove_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tag-remove'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tag_remove_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-smart-filters' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['tag-remove'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Returns CSS selector for nested element
	 *
	 * @param  [type] $el [description]
	 *
	 * @return [type]     [description]
	 */
	public function css_selector( $el = null ) {
		return sprintf( '{{WRAPPER}} .%1$s%2$s', $this->get_name(), $el );
	}

	/**
	 * Render tags smaple to style them in editor
	 *
	 * @return void
	 */
	public function render_tags_sample() {

		$plugin = Plugin::instance();

		if ( ! $plugin->editor->is_edit_mode() ) {
			return;
		}

		$active_tags = array(
			array(
				'title' => __( 'Categories', 'jet-smart-filters' ),
				'value' => __( 'Active Category', 'jet-smart-filters' ),
			),
			array(
				'title' => __( 'Tags', 'jet-smart-filters' ),
				'value' => __( 'Active Tag', 'jet-smart-filters' ),
			),
		);

		$settings    = $this->get_settings();
		$title       = isset( $settings['tags_label'] ) ? $settings['tags_label'] : '';
		$clear_item  = isset( $settings['clear_item'] ) ? filter_var( $settings['clear_item'], FILTER_VALIDATE_BOOLEAN ) : false;
		$clear_label = ! empty( $settings['clear_item_label'] ) ? $settings['clear_item_label'] : false;

		$active_tag_html = jet_smart_filters()->template_parse( 'for-js/active-tag.php' );

		echo '<div class="jet-active-tags__list">';
		echo '<div class="jet-active-tags__title">' . $title . '</div>';

		if ( $clear_item && $clear_label ) {
			echo '<div class="jet-active-tag jet-active-tag--clear">';
			$value = $clear_label;

			eval( '?>'.$active_tag_html.'<?php ' );
			echo '</div>';
		}

		foreach ( $active_tags as $_tag ) {
			echo '<div class="jet-active-tag">';
			$value = $_tag['value'];

			eval( '?>'.$active_tag_html.'<?php ' );
			echo '</div>';
		}

		echo '<div>';

	}

	protected function render() {

		$base_class           = $this->get_name();
		$settings             = $this->get_settings();
		$provider             = ! empty( $settings['content_provider'] ) ? $settings['content_provider'] : '';
		$additional_providers = ! empty( $settings['additional_providers'] ) && $settings['additional_providers_enabled'] === 'yes' ?  htmlspecialchars( json_encode( $settings['additional_providers'] ) ) : '';
		$query_id             = ! empty( $settings['query_id'] ) ? $settings['query_id'] : 'default';
		$clear_label          = ! empty( $settings['clear_item_label'] ) ? $settings['clear_item_label'] : false;
		$clear_item           = isset( $settings['clear_item'] ) ? filter_var( $settings['clear_item'], FILTER_VALIDATE_BOOLEAN ) : false;

		printf(
			'<div class="%1$s jet-active-tags" data-label="%6$s" data-clear-item-label="%7$s" data-content-provider="%2$s" data-additional-providers="%3$s" data-apply-type="%4$s" data-query-id="%5$s">',
			$base_class,
			$provider,
			$additional_providers,
			$settings['apply_type'],
			$query_id,
			$settings['tags_label'],
			$clear_item && $clear_label ? $settings['clear_item_label'] : false
		);

		$this->render_tags_sample();

		echo '</div>';

	}

}

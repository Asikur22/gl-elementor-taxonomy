<?php

use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;

/**
 * Elementor Taxonomy Widget.
 *
 * Elementor widget that inserts terms of a taxonomy into the page.
 *
 * @since 1.0.0
 */
class Elementor_GL_Taxonomy_Widget extends Widget_Base {
	
	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'gl-ele-taxonomy';
	}
	
	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Taxonomy', GL_ELE_TEXTDOMAIN );
	}
	
	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'fa fa-list';
	}
	
	public function get_style_depends() {
		return [ 'gl-ele-taxonomy' ];
	}
	
	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'basic' ];
	}
	
	protected function get_taxonomy() {
		$taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
		
		$tax = array();
		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				$tax[ $taxonomy->name ] = $taxonomy->label . ' (' . $taxonomy->name . ')';
			}
		}
		
		return $tax;
	}
	
	/**
	 * Register Taxonomy widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Query', GL_ELE_TEXTDOMAIN ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'taxonomy',
			[
				'label'   => __( 'Taxonomy', GL_ELE_TEXTDOMAIN ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_taxonomy(),
				'default' => 'category',
			]
		);
		
		$this->add_control(
			'hide_empty',
			[
				'label'        => __( 'Hide Empty', GL_ELE_TEXTDOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', GL_ELE_TEXTDOMAIN ),
				'label_off'    => __( 'No', GL_ELE_TEXTDOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		
		$this->add_control(
			'show_counts',
			[
				'label'        => __( 'Show Counts', GL_ELE_TEXTDOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', GL_ELE_TEXTDOMAIN ),
				'label_off'    => __( 'No', GL_ELE_TEXTDOMAIN ),
				'return_value' => 'true',
				'default'      => '',
			]
		);
		
		$this->add_control(
			'orderby',
			[
				'label'   => __( 'Order By', GL_ELE_TEXTDOMAIN ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'id'         => __( 'ID', GL_ELE_TEXTDOMAIN ),
					'name'       => __( 'Name', GL_ELE_TEXTDOMAIN ),
					'slug'       => __( 'Slug', GL_ELE_TEXTDOMAIN ),
					'term_order' => __( 'Term Order', GL_ELE_TEXTDOMAIN ),
					'count'      => __( 'Count', GL_ELE_TEXTDOMAIN ),
					'none'       => __( 'None', GL_ELE_TEXTDOMAIN ),
				],
				'default' => 'name',
			]
		);
		
		$this->add_control(
			'show_all_btn',
			[
				'label'        => __( 'Show All Item', GL_ELE_TEXTDOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', GL_ELE_TEXTDOMAIN ),
				'label_off'    => __( 'No', GL_ELE_TEXTDOMAIN ),
				'return_value' => 'true',
				'default'      => '',
			]
		);
		
		$this->add_control(
			'align_items',
			[
				'label'    => __( 'Align Items', GL_ELE_TEXTDOMAIN ),
				'type'     => Controls_Manager::SELECT,
				'options'  => [
					'id'         => __( 'ID', GL_ELE_TEXTDOMAIN ),
					'name'       => __( 'Name', GL_ELE_TEXTDOMAIN ),
					'slug'       => __( 'Slug', GL_ELE_TEXTDOMAIN ),
					'term_order' => __( 'Term Order', GL_ELE_TEXTDOMAIN ),
					'count'      => __( 'Count', GL_ELE_TEXTDOMAIN ),
					'none'       => __( 'None', GL_ELE_TEXTDOMAIN ),
				],
				'default'  => 'name',
				'selector' => '{{WRAPPER}} .wp-term-list',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Item', GL_ELE_TEXTDOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .wp-term-list a',
			]
		);
		
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .wp-term-list a',
			]
		);
		
		$this->start_controls_tabs( 'tabs_tax_style' );
		
		$this->start_controls_tab(
			'tab_tax_normal',
			[
				'label' => __( 'Normal', GL_ELE_TEXTDOMAIN ),
			]
		);
		
		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', GL_ELE_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wp-term-list a' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'background_color',
			[
				'label'     => __( 'Background Color', GL_ELE_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wp-term-list a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_tax_hover',
			[
				'label' => __( 'Hover', GL_ELE_TEXTDOMAIN ),
			]
		);
		
		$this->add_control(
			'hover_color',
			[
				'label'     => __( 'Text Color', GL_ELE_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wp-term-list a:hover, {{WRAPPER}} .wp-term-list a:focus'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .wp-term-list a:hover svg, {{WRAPPER}} .wp-term-list a:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'background_hover_color',
			[
				'label'     => __( 'Background Color', GL_ELE_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wp-term-list a:hover, {{WRAPPER}} .wp-term-list a:focus' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'hover_border_color',
			[
				'label'     => __( 'Border Color', GL_ELE_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wp-term-list a:hover, {{WRAPPER}} .wp-term-list a:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', GL_ELE_TEXTDOMAIN ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_tax_active',
			[
				'label' => __( 'Active', GL_ELE_TEXTDOMAIN ),
			]
		);
		
		$this->add_control(
			'active_color',
			[
				'label'     => __( 'Text Color', GL_ELE_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wp-term-list li.active a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'background_active_color',
			[
				'label'     => __( 'Background Color', GL_ELE_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wp-term-list li.active a' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'active_border_color',
			[
				'label'     => __( 'Border Color', GL_ELE_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wp-term-list li.active a, {{WRAPPER}} .wp-term-list li.active a' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'border',
				'selector'  => '{{WRAPPER}} .wp-term-list a',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label'      => __( 'Border Radius', GL_ELE_TEXTDOMAIN ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wp-term-list a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .wp-term-list a',
			]
		);
		
		$this->add_responsive_control(
			'text_padding',
			[
				'label'      => __( 'Padding', GL_ELE_TEXTDOMAIN ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wp-term-list a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'empty_style',
			[
				'label' => __( 'Not Found Text', GL_ELE_TEXTDOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'empty_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .wp-term-list-empty',
			]
		);
		
		$this->add_control(
			'empty_text_color',
			[
				'label'     => __( 'Text Color', GL_ELE_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wp-term-list-empty' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', GL_ELE_TEXTDOMAIN ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => __( 'Left', GL_ELE_TEXTDOMAIN ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', GL_ELE_TEXTDOMAIN ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => __( 'Right', GL_ELE_TEXTDOMAIN ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', GL_ELE_TEXTDOMAIN ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wp-term-list-empty' => 'text-align: {{VALUE}};',
				],
				'default'   => '',
			]
		);
		
		$this->add_responsive_control(
			'empty_text_padding',
			[
				'label'      => __( 'Padding', GL_ELE_TEXTDOMAIN ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wp-term-list-empty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);
		
		$this->end_controls_section();
		
	}
	
	/**
	 * Render Taxonomy widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$args = array(
			'hide_empty' => $settings['hide_empty'],
			'orderby'    => $settings['orderby'],
			'taxonomy'   => $settings['taxonomy']
		);
		
		$terms = get_terms( $args );
		
		$html = '';
		if ( ! empty( $terms ) ) {
			
			$html .= '<ul class="wp-term-list wp-' . $args['taxonomy'] . '-terms">';
			
			if ( ! empty( $settings['show_all_btn'] ) ) {
				$taxObject = get_taxonomy( $args['taxonomy'] );
				$postType  = $taxObject->object_type;
				$isActive  = is_post_type_archive( $postType[0] ) ? ' class="active"' : '';
				$html      .= '<li' . $isActive . '><a href="' . esc_url( get_post_type_archive_link( $postType[0] ) ) . '">' . __( 'Show All', GL_ELE_TEXTDOMAIN ) . '</a></li>';
			}
			
			foreach ( $terms as $term ) {
				$isActive = get_queried_object_id() == $term->term_id ? ' class="active"' : '';
				$count    = empty( $settings['show_counts'] ) ? '' : ' (' . $term->count . ')';
				$html     .= '<li' . $isActive . '><a href="' . esc_url( get_term_link( $term ) ) . '">' . $term->name . $count . '</a></li>';
			}
			
			$html .= '</ul>';
			
		} else {
			
			$html .= '<div class="wp-term-list-empty">No Term Found!</div>';
			
		}
		
		echo $html;
	}
	
}

// Register widget
Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_GL_Taxonomy_Widget() );
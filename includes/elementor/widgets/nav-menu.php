<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use GlossyMM\Utils;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;

class Glossymm_Nav_Menu extends Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->add_script_depends( 'glossymm-nav-menu' );
        $this->add_style_depends( 'glossymm-nav-menu' );
        $this->add_style_depends( 'glossymm-responsive-nav-menu' );
    }

    public function get_name() {
        return 'glossymm_nav_menu';
    }

    public function get_title() {
        return esc_html__( "GlossyMM Nav Menu", 'glossy-mega-menu' );
    }

    public function get_icon() {
        return "eicon-nav-menu";
    }

    public function get_categories() {
        return "basic";
    }

    public function get_keywords() {
        return ['glossymm', 'menu', 'nav-menu', 'nav', 'navigation', 'navigation-menu', 'mega', 'megamenu', 'mega-menu'];
    }

    public function get_help_url() {
        return 'https://glossymm.com/doc/nav-menu/';
    }

    public function get_menus() {
        $list = [];
        $menus = wp_get_nav_menus();
        foreach ( $menus as $menu ) {
            $list[$menu->slug] = $menu->name;
        }
        return $list;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'glossymm_content_tab',
            [
                'label' => esc_html__( 'Menu Settings', 'glossy-mega-menu' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'glossymm_nav_menu',
            [
                'label'   => esc_html__( 'Select menu', 'glossy-mega-menu' ),
                'type'    => Controls_Manager::SELECT,
                'options' => $this->get_menus(),
            ]
        );
        $this->add_responsive_control(
            'glsymm_menu_justify_content',
            [
                'label'     => __( 'Justify Content', 'glossy-mega-menu' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'flex-start',
                'options'   => [
                    'flex-start'    => __( 'Flex Start', 'glossy-mega-menu' ),
                    'flex-end'      => __( 'Flex End', 'glossy-mega-menu' ),
                    'center'        => __( 'Center', 'glossy-mega-menu' ),
                    'space-between' => __( 'Space Between', 'glossy-mega-menu' ),
                    'space-around'  => __( 'Space Around', 'glossy-mega-menu' ),
                    'space-evenly'  => __( 'Space Evenly', 'glossy-mega-menu' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .glossymm-navbar-nav' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glsymm_menu_align_items',
            [
                'label'     => __( 'Align Items', 'glossy-mega-menu' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'stretch',
                'options'   => [
                    'stretch'    => __( 'Stretch', 'glossy-mega-menu' ),
                    'flex-start' => __( 'Flex Start', 'glossy-mega-menu' ),
                    'flex-end'   => __( 'Flex End', 'glossy-mega-menu' ),
                    'center'     => __( 'Center', 'glossy-mega-menu' ),
                    'baseline'   => __( 'Baseline', 'glossy-mega-menu' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .glossymm-navbar-nav' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glsymm_menu_flex_direction',
            [
                'label'     => __( 'Flex Direction:', 'glossy-mega-menu' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'row',
                'options'   => [
                    'row'    => __( 'Row', 'glossy-mega-menu' ),
                    'column' => __( 'Column', 'glossy-mega-menu' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .glossymm-navbar-nav' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'glossymm_nav_dropdown_as',
            [
                'label'   => esc_html__( 'Dropdown open as', 'glossy-mega-menu' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'glossymm-nav-dropdown-hover',
                'options' => [
                    'glossymm-nav-dropdown-hover' => esc_html__( 'Hover', 'glossy-mega-menu' ),
                    'glossymm-nav-dropdown-click' => esc_html__( 'Click', 'glossy-mega-menu' ),
                ],
            ]
        );

        $this->add_control(
            'glossymm_submenu_indicator_icon',
            [
                'label'                  => esc_html__( 'Dropdown Indicator Icon', 'glossy-mega-menu' ),
                'type'                   => Controls_Manager::ICONS,
                'skin'                   => 'inline',
                'exclude_inline_options' => ['svg'],
                'skin_settings'          => [
                    'inline' => [
                        'none' => [
                            'label' => esc_html__( 'Default', 'glossy-mega-menu' ),
                            'icon'  => 'fas fa-chevron-down',
                        ],
                        'icon' => [
                            'label' => esc_html__( 'Icon Library', 'glossy-mega-menu' ),
                            'icon'  => 'fas fa-external-link-alt',
                        ],
                    ],
                ],
                'recommended'            => [
                    'fa-solid' => [
                        'plus',
                        'external-link-alt',
                        'link',
                        'angle-down',
                        "chevron-down",
                    ],
                ],
                'label_block'            => false,
            ]
        );

        $this->add_control(
            'glossymm_responsive_breakpoint',
            [
                'label'       => __( 'Responsive Breakpoint', 'glossy-mega-menu' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'default'     => esc_html__( '1024', 'glossy-mega-menu' ),
                'placeholder' => esc_html__( '1024', 'glossy-mega-menu' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'glossymm_mobile_menu',
            [
                'label' => esc_html__( 'Mobile Menu Settings', 'glossy-mega-menu' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'glossymm_nav_menu_logo',
            [
                'label'   => esc_html__( 'Mobile Menu Logo', 'glossy-mega-menu' ),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'glossymm_nav_menu_logo_link_to',
            [
                'label'   => esc_html__( 'Menu link', 'glossy-mega-menu' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'home',
                'options' => [
                    'home'   => esc_html__( 'Default(Home)', 'glossy-mega-menu' ),
                    'custom' => esc_html__( 'Custom URL', 'glossy-mega-menu' ),
                ],
            ]
        );

        $this->add_control(
            'glossymm_nav_menu_logo_link',
            [
                'label'       => esc_html__( ' Custom Link', 'glossy-mega-menu' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => 'https://glossyit.com',
                'condition'   => [
                    'glossymm_nav_menu_logo_link_to' => 'custom',
                ],
                'show_label'  => false,

            ]
        );

        $this->add_control(
            'glossymm_hamburger_icon',
            [
                'label'     => __( 'Hamburger Icon (Optional)', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::ICONS,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'submenu_click_area',
            [
                'label'        => esc_html__( 'Submenu Click Area', 'glossy-mega-menu' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Icon', 'glossy-mega-menu' ),
                'label_off'    => esc_html__( 'Text', 'glossy-mega-menu' ),
                'return_value' => 'icon',
                'default'      => 'icon',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'glossymm_menu_style_tab',
            [
                'label' => esc_html__( 'Menu Wrapper', 'glossy-mega-menu' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'glossymm_mobile_menu_panel_width',
            [
                'label'          => esc_html__( 'Width', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::SLIDER,
                'size_units'     => ['px', '%'],
                'devices'        => ['desktop', 'tablet', 'mobile'],
                'range'          => [
                    'px' => [
                        'min'  => 350,
                        'max'  => 700,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'mobile_default' => [
                    'size' => 350,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 350,
                    'unit' => 'px',
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-menu-container' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_menubar_height',
            [
                'label'           => esc_html__( 'Menu Height', 'glossy-mega-menu' ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => ['px', '%'],
                'range'           => [
                    'px' => [
                        'min'  => 30,
                        'max'  => 300,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'devices'         => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'tablet_default'  => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'mobile_default'  => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors'       => [
                    '{{WRAPPER}} .glossymm-menu-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator'       => 'after',
            ]
        );

        $this->add_control(
            'glossymm_menu_wrap_h',
            [
                'label' => esc_html__( 'Menu wrapper background', 'glossy-mega-menu' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_menubar_background',
                'label'    => esc_html__( 'Menu Panel Background', 'glossy-mega-menu' ),
                'types'    => ['classic', 'gradient'],
                'devices'  => ['desktop'],
                'selector' => '{{WRAPPER}} .glossymm-menu-container',
            ]
        );

        $this->add_responsive_control(
            'wrapper_color_mobile',
            [
                'label'     => esc_html__( 'Mobile Wrapper Background', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::COLOR,
                'devices'   => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .glossymm-menu-container.glossymm-megamenu-offcanvas' => 'background-color: {{VALUE}};',
                ],
            ]        );

        $this->add_responsive_control(
            'glossymm_mobile_menu_panel_spacing',
            [
                'label'          => esc_html__( 'Padding', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::DIMENSIONS,
                'size_units'     => ['px', '%', 'em'],
                'tablet_default' => [
                    'top'    => '10',
                    'right'  => '0',
                    'bottom' => '10',
                    'left'   => '0',
                    'unit'   => 'px',
                ],
                'devices'        => ['desktop', 'tablet'],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-nav-identity-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        

        $this->add_responsive_control(
            'glossymm_border_radius',
            [
                'label'           => esc_html__( 'Menu border radius', 'glossy-mega-menu' ),
                'type'            => Controls_Manager::DIMENSIONS,
                'size_units'      => ['px', 'em', 'rem'],
                'separator'       => ['before'],
                'desktop_default' => [
                    'top'    => 0,
                    'right'  => 0,
                    'bottom' => 0,
                    'left'   => 0,
                    'unit'   => 'px',
                ],
                'tablet_default'  => [
                    'top'    => 0,
                    'right'  => 0,
                    'bottom' => 0,
                    'left'   => 0,
                    'unit'   => 'px',
                ],
                'selectors'       => [
                    '{{WRAPPER}} .glossymm-menu-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'glossymm_menu_item_icon_spacing',
            [
                'label'       => esc_html__( 'Menu Icon Spacing', 'glossy-mega-menu' ),
                'description' => esc_html__( 'This is only work with Mega menu icon option', 'glossy-mega-menu' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => ['px', '%', 'em'],
                'selectors'   => [
                    '{{WRAPPER}} .glossymm-navbar-nav li a .glossymm-menu-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'glossymm_style_tab_menuitem',
            [
                'label' => esc_html__( 'Menu item style', 'glossy-mega-menu' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'glossymm_content_typography',
                'label'    => esc_html__( 'Typography', 'glossy-mega-menu' ),
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav > li > a',
            ]
        );

        $this->add_control(
            'glossymm_menu_item_h',
            [
                'label'     => esc_html__( 'Menu Item Style', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs(
            'glossymm_nav_menu_tabs'
        );
        // Normal
        $this->start_controls_tab(
            'glossymm_nav_menu_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'glossy-mega-menu' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_item_background',
                'label'    => esc_html__( 'Item background', 'glossy-mega-menu' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav > li > a',
            ]
        );

        $this->add_responsive_control(
            'glossymm_menu_text_color',
            [
                'label'           => esc_html__( 'Item text color', 'glossy-mega-menu' ),
                'type'            => Controls_Manager::COLOR,
                'desktop_default' => '#000000',
                'tablet_default'  => '#000000',
                'selectors'       => [
                    '{{WRAPPER}} .glossymm-navbar-nav > li > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'       => 'glossymm_menu_text_border',
                'selector'   => '{{WRAPPER}} .glossymm-navbar-nav > li > a',
                'size_units' => ['px'],
            ]
        );

        $this->add_control(
            'glossymm_menu_text_border_radius',
            [
                'label'      => esc_html__( 'Border Radius (px)', 'glossy-mega-menu' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .glossymm-navbar-nav > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'glossymm_nav_menu_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'glossy-mega-menu' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_item_background_hover',
                'label'    => esc_html__( 'Item background', 'glossy-mega-menu' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav > li > a:hover, {{WRAPPER}} .glossymm-navbar-nav > li > a:focus, {{WRAPPER}} .glossymm-navbar-nav > li > a:active, {{WRAPPER}} .glossymm-navbar-nav > li:hover > a',
            ]
        );

        $this->add_responsive_control(
            'glossymm_item_color_hover',
            [
                'label'     => esc_html__( 'Item text color', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#707070',
                'selectors' => [
                    '{{WRAPPER}} .glossymm-navbar-nav > li > a:hover'                              => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav > li > a:focus'                              => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav > li > a:active'                             => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav > li:hover > a'                              => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav > li:hover > a .glossymm-submenu-indicator'  => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav > li > a:hover .glossymm-submenu-indicator'  => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav > li > a:focus .glossymm-submenu-indicator'  => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav > li > a:active .glossymm-submenu-indicator' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'       => 'glossymm_menu_text_border_hover',
                'selector'   => '{{WRAPPER}} .glossymm-navbar-nav > li:hover > a',
                'size_units' => ['px'],
            ]
        );

        $this->add_control(
            'glossymm_menu_text_border_radius_hover',
            [
                'label'      => esc_html__( 'Border Radius (px)', 'glossy-mega-menu' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .glossymm-navbar-nav > li:hover > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // active
        $this->start_controls_tab(
            'glossymm_nav_menu_active_tab',
            [
                'label' => esc_html__( 'Active', 'glossy-mega-menu' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_nav_menu_active_bg_color',
                'label'    => esc_html__( 'Item background', 'glossy-mega-menu' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav > li.current-menu-item > a,{{WRAPPER}} .glossymm-navbar-nav > li.current-menu-ancestor > a',
            ]
        );

        $this->add_responsive_control(
            'glossymm_nav_menu_active_text_color',
            [
                'label'     => esc_html__( 'Item text color (Active)', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#707070',
                'selectors' => [
                    '{{WRAPPER}} .glossymm-navbar-nav > li.current-menu-item > a'                                 => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav > li.current-menu-ancestor > a'                             => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav > li.current-menu-ancestor > a .glossymm-submenu-indicator' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'       => 'glossymm_menu_text_border_active',
                'selector'   => '{{WRAPPER}} .glossymm-navbar-nav > li.current-menu-item > a',
                'size_units' => ['px'],
            ]
        );

        $this->add_control(
            'glossymm_menu_text_border_radius_active',
            [
                'label'      => esc_html__( 'Border Radius (px)', 'glossy-mega-menu' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .glossymm-navbar-nav > li.current-menu-item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'glossymms_menu_item_spacing',
            [
                'label'           => esc_html__( 'Item Spacing', 'glossy-mega-menu' ),
                'type'            => Controls_Manager::DIMENSIONS,
                'separator'       => ['before'],
                'devices'         => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'top'    => 15,
                    'right'  => 15,
                    'bottom' => 15,
                    'left'   => 15,
                    'unit'   => 'px',
                ],
                'size_units'      => ['px'],
                'selectors'       => [
                    '{{WRAPPER}} .glossymm-navbar-nav > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_menu_item_margin',
            [
                'label'      => esc_html__( 'Item Margin', 'glossy-mega-menu' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .glossymm-navbar-nav > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'glossymm_style_tab_submenu_indicator',
            [
                'label' => esc_html__( 'Submenu indicator style', 'glossy-mega-menu' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'glossymm_submenu_indicator_font_size',
            [
                'label'      => esc_html__( 'Font Size', 'glossy-mega-menu' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 5,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .glossymm-navbar-nav > li > a .glossymm-submenu-indicator'      => 'width: {{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .glossymm-navbar-nav > li > a .glossymm-submenu-indicator-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_style_tab_submenu_indicator_color',
            [
                'label'     => esc_html__( 'Indicator color', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#101010',
                'alpha'     => false,
                'selectors' => [
                    '{{WRAPPER}} .glossymm-navbar-nav > li  a .glossymm-submenu-indicator'      => 'color: {{VALUE}}!important; fill: {{VALUE}}!important;',
                    '{{WRAPPER}} .glossymm-navbar-nav > li  a .glossymm-submenu-indicator-icon' => 'color: {{VALUE}}!important; fill: {{VALUE}}!important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'glossymm_submenu_indicator_spacing',
            [
                'label'           => esc_html__( 'Indicator Margin (px)', 'glossy-mega-menu' ),
                'type'            => Controls_Manager::DIMENSIONS,
                'size_units'      => ['px'],
                'desktop_default' => [
                    'top'      => 0,
                    'right'    => 0,
                    'bottom'   => -3,
                    'left'     => 3,
                    'unit'     => 'px',
                    'isLinked' => false,
                ],
                'selectors'       => [
                    '{{WRAPPER}} .glossymm-navbar-nav-default .glossymm-dropdown-has>a .glossymm-submenu-indicator'      => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .glossymm-navbar-nav-default .glossymm-dropdown-has>a .glossymm-submenu-indicator-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'glossymm_style_tab_submenu_item',
            [
                'label' => esc_html__( 'Submenu item style', 'glossy-mega-menu' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'glossymm_menu_item_typography',
                'label'    => esc_html__( 'Typography', 'glossy-mega-menu' ),
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li > a',
            ]
        );

        $this->add_responsive_control(
            'glossymm_submenu_item_spacing',
            [
                'label'           => esc_html__( 'Spacing(px)', 'glossy-mega-menu' ),
                'type'            => Controls_Manager::DIMENSIONS,
                'devices'         => ['desktop', 'tablet'],
                'desktop_default' => [
                    'top'    => 15,
                    'right'  => 15,
                    'bottom' => 15,
                    'left'   => 15,
                    'unit'   => 'px',
                ],
                'tablet_default'  => [
                    'top'    => 15,
                    'right'  => 15,
                    'bottom' => 15,
                    'left'   => 15,
                    'unit'   => 'px',
                ],
                'size_units'      => ['px'],
                'selectors'       => [
                    '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'glossymm_submenu_active_hover_tabs'
        );
        $this->start_controls_tab(
            'glossymm_submenu_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'glossy-mega-menu' ),
            ]
        );

        $this->add_responsive_control(
            'glossymm_submenu_item_color',
            [
                'label'     => esc_html__( 'Item text color', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li > a' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_menu_item_background',
                'label'    => esc_html__( 'Item background', 'glossy-mega-menu' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li > a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'glossymm_submenu_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'glossy-mega-menu' ),
            ]
        );

        $this->add_responsive_control(
            'glossymm_item_text_color_hover',
            [
                'label'     => esc_html__( 'Item text color (Hover)', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#707070',
                'selectors' => [
                    '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li > a:hover'  => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li > a:focus'  => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li > a:active' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li:hover > a'  => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_menu_item_background_hover',
                'label'    => esc_html__( 'Item background (hover)', 'glossy-mega-menu' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '
					{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li:hover a,
					{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li:focus a,
					{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li:active a,
					{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li:hover a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'glossymm_submenu_active_tab',
            [
                'label' => esc_html__( 'Active', 'glossy-mega-menu' ),
            ]
        );

        $this->add_responsive_control(
            'glossymm_nav_sub_menu_active_text_color',
            [
                'label'     => esc_html__( 'Item text color (Active)', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#707070',
                'selectors' => [
                    '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li.current-menu-item > a' => 'color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_nav_sub_menu_active_bg_color',
                'label'    => esc_html__( 'Item background (Active)', 'glossy-mega-menu' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li.current-menu-item > a',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'glossymm_menu_item_border_heading',
            [
                'label'     => esc_html__( 'Sub Menu Items Border', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'glossymm_menu_item_border',
                'label'    => esc_html__( 'Border', 'glossy-mega-menu' ),
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li > a',
            ]
        );

        $this->add_control(
            'glossymm_menu_item_border_last_child_heading',
            [
                'label'     => esc_html__( 'Border Last Child', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'glossymm_menu_item_border_last_child',
                'label'    => esc_html__( 'Border last Child', 'glossy-mega-menu' ),
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li:last-child > a',
            ]
        );

        $this->add_control(
            'glossymm_menu_item_border_first_child_heading',
            [
                'label'     => esc_html__( 'Border First Child', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'glossymm_menu_item_border_first_child',
                'label'    => esc_html__( 'Border First Child', 'glossy-mega-menu' ),
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel > li:first-child > a',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'glossymm_style_tab_submenu_panel',
            [
                'label' => esc_html__( 'Submenu panel style', 'glossy-mega-menu' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'sub_panel_padding',
            [
                'label'     => esc_html__( 'Padding (px)', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'default'   => [
                    'top'      => '0',
                    'bottom'   => '0',
                    'left'     => '0',
                    'right'    => '0',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .glossymm-submenu-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'glossymm_panel_submenu_border',
                'label'    => esc_html__( 'Panel Menu Border', 'glossy-mega-menu' ),
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_submenu_container_background',
                'label'    => esc_html__( 'Container background', 'glossy-mega-menu' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel,{{WRAPPER}} .glossymm-navbar-nav .glossymm-megamenu-panel',
            ]
        );

        $this->add_responsive_control(
            'glossymm_submenu_panel_border_radius',
            [
                'label'           => esc_html__( 'Border Radius', 'glossy-mega-menu' ),
                'type'            => Controls_Manager::DIMENSIONS,
                'desktop_default' => [
                    'top'    => 0,
                    'right'  => 0,
                    'bottom' => 0,
                    'left'   => 0,
                    'unit'   => 'px',
                ],
                'tablet_default'  => [
                    'top'    => 0,
                    'right'  => 0,
                    'bottom' => 0,
                    'left'   => 0,
                    'unit'   => 'px',
                ],
                'size_units'      => ['px'],
                'selectors'       => [
                    '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_submenu_container_width',
            [
                'label'           => esc_html__( 'Conatiner width', 'glossy-mega-menu' ),
                'type'            => Controls_Manager::TEXT,
                'devices'         => ['desktop'],
                'desktop_default' => '220px',
                'tablet_default'  => '200px',
                'selectors'       => [
                    '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel' => 'min-width: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'glossymm_panel_box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'glossy-mega-menu' ),
                'selector' => '{{WRAPPER}} .glossymm-navbar-nav .glossymm-submenu-panel',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'glossymm_menu_toggle_style_tab',
            [
                'label' => esc_html__( 'Hamburger Style', 'glossy-mega-menu' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'glossymm_menu_toggle_style_title',
            [
                'label'     => esc_html__( 'Hamburger Toggle', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'glossymm_menu_toggle_icon_position',
            [
                'label'       => esc_html__( 'Position', 'glossy-mega-menu' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'     => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'glossy-mega-menu' ),
                        'icon'  => 'fa fa-angle-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'glossy-mega-menu' ),
                        'icon'  => 'fa fa-angle-right',
                    ],
                ],
                'default'     => 'right',
                'selectors'   => [
                    '{{WRAPPER}} .glossymm-menu-hamburger' => 'float: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_menu_toggle_spacing',
            [
                'label'          => esc_html__( 'Padding', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::DIMENSIONS,
                'size_units'     => ['px', 'em', 'rem'],
                'devices'        => ['desktop', 'tablet'],
                'tablet_default' => [
                    'top'    => '10',
                    'right'  => '10',
                    'bottom' => '10',
                    'left'   => '10',
                    'unit'   => 'px',
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-menu-hamburger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_menu_toggle_width',
            [
                'label'          => esc_html__( 'Width', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::SLIDER,
                'size_units'     => ['px', 'em', 'rem'],
                'range'          => [
                    'px' => [
                        'min'  => 45,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'devices'        => ['desktop', 'tablet'],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 45,
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-menu-hamburger' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_menu_toggle_border_radius',
            [
                'label'          => esc_html__( 'Border Radius', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::SLIDER,
                'size_units'     => ['px', '%'],
                'range'          => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'devices'        => ['desktop', 'tablet'],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-menu-hamburger' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_menu_open_typography',
            [
                'label'      => esc_html__( 'Icon Size', 'glossy-mega-menu' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px' => [
                        'min'  => 15,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .glossymm-menu-hamburger > .glossymm-menu-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'glossymm_hamburger_icon[value]!' => '',
                ],
            ]
        );

        $this->start_controls_tabs(
            'glossymm_menu_toggle_normal_and_hover_tabs'
        );

        $this->start_controls_tab(
            'glossymm_menu_toggle_normal',
            [
                'label' => esc_html__( 'Normal', 'glossy-mega-menu' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_menu_toggle_background',
                'label'    => esc_html__( 'Background', 'glossy-mega-menu' ),
                'types'    => ['classic'],
                'selector' => '{{WRAPPER}} .glossymm-menu-hamburger',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'glossymm_menu_toggle_border',
                'label'     => esc_html__( 'Border', 'glossy-mega-menu' ),
                'separator' => 'before',
                'selector'  => '{{WRAPPER}} .glossymm-menu-hamburger',
            ]
        );

        $this->add_control(
            'glossymm_menu_toggle_icon_color',
            [
                'label'     => esc_html__( 'Hamburger Icon Color', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .glossymm-menu-hamburger .glossymm-menu-hamburger-icon' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-menu-hamburger > .glossymm-menu-icon'         => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'glossymm_menu_toggle_hover',
            [
                'label' => esc_html__( 'Hover', 'glossy-mega-menu' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_menu_toggle_background_hover',
                'label'    => esc_html__( 'Background', 'glossy-mega-menu' ),
                'types'    => ['classic'],
                'selector' => '{{WRAPPER}} .glossymm-menu-hamburger:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'glossymm_menu_toggle_border_hover',
                'label'     => esc_html__( 'Border', 'glossy-mega-menu' ),
                'separator' => 'before',
                'selector'  => '{{WRAPPER}} .glossymm-menu-hamburger:hover',
            ]
        );

        $this->add_control(
            'glossymm_menu_toggle_icon_color_hover',
            [
                'label'     => esc_html__( 'Hamburger Icon Color', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0, 0, 0, 0.5)',
                'selectors' => [
                    '{{WRAPPER}} .glossymm-menu-hamburger:hover .glossymm-menu-hamburger-icon' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .glossymm-menu-hamburger:hover > .glossymm-menu-icon'         => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'glossymm_menu_close_style_title',
            [
                'label'     => esc_html__( 'Close Toggle', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'glossymm_menu_close_typography',
                'label'    => esc_html__( 'Typography', 'glossy-mega-menu' ),
                'selector' => '{{WRAPPER}} .glossymm-menu-close',
            ]
        );

        $this->add_responsive_control(
            'glossymm_menu_close_spacing',
            [
                'label'          => esc_html__( 'Padding', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::DIMENSIONS,
                'size_units'     => ['px'],
                'devices'        => ['desktop', 'tablet'],
                'tablet_default' => [
                    'top'    => '8',
                    'right'  => '8',
                    'bottom' => '8',
                    'left'   => '8',
                    'unit'   => 'px',
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-menu-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_menu_close_margin',
            [
                'label'          => esc_html__( 'Margin', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::DIMENSIONS,
                'size_units'     => ['px', 'em', 'rem'],
                'devices'        => ['desktop', 'tablet'],
                'tablet_default' => [
                    'top'    => '12',
                    'right'  => '12',
                    'bottom' => '12',
                    'left'   => '12',
                    'unit'   => 'px',
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-menu-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_menu_close_width',
            [
                'label'          => esc_html__( 'Width', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::SLIDER,
                'size_units'     => ['px', 'em', 'rem', '%'],
                'range'          => [
                    'px' => [
                        'min'  => 45,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'devices'        => ['desktop', 'tablet'],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 45,
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-menu-close' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_menu_close_border_radius',
            [
                'label'          => esc_html__( 'Border Radius', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::SLIDER,
                'size_units'     => ['px', 'em', '%'],
                'range'          => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'devices'        => ['desktop', 'tablet'],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-menu-close' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'glossymm_menu_close_normal_and_hover_tabs'
        );

        $this->start_controls_tab(
            'glossymm_menu_close_normal',
            [
                'label' => esc_html__( 'Normal', 'glossy-mega-menu' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_menu_close_background',
                'label'    => esc_html__( 'Background', 'glossy-mega-menu' ),
                'types'    => ['classic'],
                'selector' => '{{WRAPPER}} .glossymm-menu-close',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'glossymm_menu_close_border',
                'label'     => esc_html__( 'Border', 'glossy-mega-menu' ),
                'separator' => 'before',
                'selector'  => '{{WRAPPER}} .glossymm-menu-close',
            ]
        );

        $this->add_control(
            'glossymm_menu_close_icon_color',
            [
                'label'     => esc_html__( 'Hamburger Icon Color', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .glossymm-menu-close' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'glossymm_menu_close_hover',
            [
                'label' => esc_html__( 'Hover', 'glossy-mega-menu' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'glossymm_menu_close_background_hover',
                'label'    => esc_html__( 'Background', 'glossy-mega-menu' ),
                'types'    => ['classic'],
                'selector' => '{{WRAPPER}} .glossymm-menu-close:hover',
                'default'  => [
                    'background' => '#231111',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'glossymm_menu_close_border_hover',
                'label'     => esc_html__( 'Border', 'glossy-mega-menu' ),
                'separator' => 'before',
                'selector'  => '{{WRAPPER}} .glossymm-menu-close:hover',
            ]
        );

        $this->add_control(
            'glossymm_menu_close_icon_color_hover',

            [
                'label'     => esc_html__( 'Hamburger Icon Color', 'glossy-mega-menu' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .glossymm-menu-close:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'glossymm_mobile_menu_logo_style_tab',
            [
                'label' => esc_html__( 'Mobile Menu Logo', 'glossy-mega-menu' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'glossymm_mobile_menu_logo_width',
            [
                'label'          => esc_html__( 'Width', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::SLIDER,
                'size_units'     => ['px', 'em', 'rem', '%'],
                'range'          => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 5,
                    ],
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 160,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 120,
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-nav-logo > img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_mobile_menu_logo_height',
            [
                'label'          => esc_html__( 'Height', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::SLIDER,
                'size_units'     => ['px', 'em', 'rem', '%'],
                'range'          => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 200,
                        'step' => 1,
                    ],
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-nav-logo > img' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_mobile_menu_logo_margin',
            [
                'label'          => esc_html__( 'Margin', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::DIMENSIONS,
                'size_units'     => ['px', '%', 'em'],
                'tablet_default' => [
                    'top'      => '5',
                    'right'    => '0',
                    'bottom'   => '5',
                    'left'     => '0',
                    'unit'     => 'px',
                    'isLinked' => 'false',
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-nav-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'glossymm_mobile_menu_logo_padding',
            [
                'label'          => esc_html__( 'Padding', 'glossy-mega-menu' ),
                'type'           => Controls_Manager::DIMENSIONS,
                'size_units'     => ['px', '%', 'em'],
                'tablet_default' => [
                    'top'      => '5',
                    'right'    => '5',
                    'bottom'   => '5',
                    'left'     => '5',
                    'unit'     => 'px',
                    'isLinked' => 'true',
                ],
                'selectors'      => [
                    '{{WRAPPER}} .glossymm-nav-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        extract( $this->get_settings_for_display() );
        // Return if menu not selected
        if ( empty( $glossymm_nav_menu ) ) {
            return;
        }
        $hamburger_icon_value = '';
        $hamburger_icon_type = '';
        if ( $glossymm_hamburger_icon != '' && $glossymm_hamburger_icon ) {
            if ( $glossymm_hamburger_icon['library'] !== 'svg' ) {
                $hamburger_icon_value = esc_attr( $glossymm_hamburger_icon['value'] );
                $hamburger_icon_type = esc_attr( 'icon' );
            } else {
                $hamburger_icon_value = esc_url( $glossymm_hamburger_icon['value']['url'] );
                $hamburger_icon_type = esc_attr( 'url' );
            }
        }



        $style = "<style id='glossymm-responsive-navmenu'>";

        $style .= "@media (max-width:{$glossymm_responsive_breakpoint}px){
                    .glossymm-menu-container ul {
                             flex-direction: column;
                             text-align: left;
                         }
                         .glossymm-nav-identity-panel{
                            display: flex !important;
                         }
                        .glossymm-menu-hamburger{
                                display: block !important;
                        }
                        .glossymm-menu-container ul li.glossymm-menu-item {
                            display: block;
                            width: 100%;
                        }

                        .glossymm-menu-container a {
                            line-height: 50px;
                            font-size: 16px;
                            padding: 10px 15px;
                        }
                        .glossymm-menu-container ul li.glossymm-menu-item ul {
                            position: static;
                            display: none;
                        }  
            }"; 

        $style .= "</style>";

        echo wp_kses($style,[
            'style' => [
                'id' => array(),
            ]
        ]);     
        //wp_add_inline_style('glossymm-responsive-nav-menu', $custom_css);
        
        echo '<div class="glossymm-menu-wrapper " data-hamburger-icon="' . esc_attr( $hamburger_icon_value ) . '" data-hamburger-icon-type="' . esc_attr( $hamburger_icon_type ) . '" data-responsive-breakpoint="' . esc_attr( $glossymm_responsive_breakpoint ) . '">';
        $this->render_raw();
        echo '</div>';
    }

    protected function render_raw() {
        extract( $this->get_settings_for_display() );
        if ( $glossymm_nav_menu != '' && wp_get_nav_menu_items( $glossymm_nav_menu ) !== false && count( wp_get_nav_menu_items( $glossymm_nav_menu ) ) > 0 ) {
            /**
             * Hamburger Toggler Button
             */
            ?>
            <button class="glossymm-menu-hamburger glossymm-menu-toggler"  type="button" aria-label="hamburger-icon">
                <?php
                /**
                 * Show Default Icon
                 */
                if ( $glossymm_hamburger_icon['value'] === '' ):
                ?>
                    <span class="glossymm-menu-hamburger-icon"></span><span class="glossymm-menu-hamburger-icon"></span><span class="glossymm-menu-hamburger-icon"></span>
                <?php
                endif;
                /**
                 * Show Icon or, SVG
                 */
                \Elementor\Icons_Manager::render_icon( $glossymm_hamburger_icon, ['aria-hidden' => 'true', 'class' => 'glossymm-menu-icon'] );
                ?>
            </button>
            <?php

            /**
             * Main Menu Container
             */
            $link = $target = $nofollow = '';

            if ( isset( $glossymm_nav_menu_logo_link_to ) && $glossymm_nav_menu_logo_link_to == 'home' ) {
                $link = get_home_url();
            } elseif ( isset( $glossymm_nav_menu_logo_link ) ) {
                $link = $glossymm_nav_menu_logo_link['url'];
                $target = ( $glossymm_nav_menu_logo_link['is_external'] != "on" ? "" : "_blank" );
                $nofollow = ( $glossymm_nav_menu_logo_link['nofollow'] != "on" ? "" : "nofollow" );
            }

            $metadata = Utils::img_meta( esc_attr( $glossymm_nav_menu_logo['id'] ) );
            $markup = '<div class="glossymm-nav-identity-panel">';
            // Use an if statement to conditionally display the site logo
            if ( !empty( $glossymm_nav_menu_logo['id'] ) ):
                $markup .= '
                            <div class="glossymm-site-title">
                                <a class="glossymm-nav-logo" href="' . esc_url( $link ) . '" target="' . ( !empty( $target ) ? esc_attr( $target ) : '_self' ) . '" rel="' . esc_attr( $nofollow ) . '">
                                    ' . \GlossyMM\Utils::get_attachment_image_html( $this->get_settings_for_display(), 'glossymm_nav_menu_logo', 'full' ) . '
                                </a>
                            </div>';
            endif;
            $markup .= '<button class="glossymm-menu-close glossymm-menu-toggler" type="button">X</button></div>';

            $container_classes = [
                'glossymm-menu-container glossymm-menu-offcanvas-elements glossymm-navbar-nav-default',
                'glossymm-nav-menu-one-page-' . !empty( $glossymm_nav_dropdown_as ) ? $glossymm_nav_dropdown_as : 'glossymm-nav-dropdown-hover',
            ];

            $args = [
                'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>' . $markup,
                'container'       => 'div',
                'container_id'    => 'glossymm-megamenu-' . $glossymm_nav_menu,
                'container_class' => join( ' ', $container_classes ),
                'menu'            => $glossymm_nav_menu,
                'menu_class'      => 'glossymm-navbar-nav submenu-click-on-' . $submenu_click_area,
                'depth'           => 4,
                'echo'            => true,
                'fallback_cb'     => 'wp_page_menu',
                'walker'          => new GlossyMM\Glossymm_Menu_Walker(),
            ];

            // set submenu indicator icon
            $args['submenu_indicator_icon'] = $this->get_indicator_icon( $glossymm_submenu_indicator_icon );

            // WP 6.1 submenu issue
            if ( version_compare( get_bloginfo( 'version' ), '6.1', '>=' ) ) {
                unset( $args['depth'] );
            }

            wp_nav_menu( $args );

            /**
             * Mobile Menu Overlay
             */
            ?>
			<div class="glossymm-menu-overlay glossymm-menu-offcanvas-elements glossymm-menu-toggler glossymm-nav-menu--overlay"></div>

            <?php
            /**
             * Editor: Widget Empty Fallback on Responsive View
             */
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ): ?>
				<span class="glossymm-nav-menu--empty-fallback">&nbsp;</span>
			<?php endif;
        }
    }

    protected function get_indicator_icon( $glossymm_submenu_indicator_icon ) {
        $indicator_class = 'glossymm-submenu-indicator';
        if ( empty( $glossymm_submenu_indicator_icon['value'] ) ) {
            $glossymm_submenu_indicator_icon['value'] = "fas fa-chevron-down";
            $glossymm_submenu_indicator_icon['library'] = "fa-solid";
        }
        return \Elementor\Icons_Manager::try_get_icon_html( $glossymm_submenu_indicator_icon, ['class' => $indicator_class, 'aria-hidden' => 'true'] );

    }
}

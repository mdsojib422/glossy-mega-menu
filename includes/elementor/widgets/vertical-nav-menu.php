<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Widget_Base;

if ( !class_exists( "Glossymm_Vertical_Nav_menu" ) ) {

    class Glossymm_Vertical_Nav_menu extends Widget_Base {

        public function __construct( $data = [], $args = null ) {
            parent::__construct( $data, $args );
            $this->add_script_depends( 'glossymm-vertical-nav-menu' );
            $this->add_style_depends( 'glossymm-vertical-nav-menu' );
        }

        public function get_name() {
            return 'glossymm_vertical_nav_menu';
        }

        public function get_title() {
            return esc_html__( "Vertical Nav Menu", 'glossy-mega-menu' );
        }

        public function get_icon() {
            return "eicon-nav-menu";
        }

        public function get_categories() {
            return "basic";
        }

        public function get_keywords() {
            return ['glossymm', 'vertical-menu', 'menu', 'nav-menu', 'nav', 'navigation', 'navigation-menu', 'mega', 'megamenu', 'mega-menu'];
        }

        public function get_help_url() {
            return 'https://glossyit.com/doc/nav-menu/';
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
                'glossymm_vertical_nav_menu_select',
                [
                    'label' => esc_html__( 'Menu Settings', 'glossy-mega-menu' ),
                    'tab'   => Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'glossymm_vertical_nav_menu',
                [
                    'label'   => esc_html__( 'Select menu', 'glossy-mega-menu' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => $this->get_menus(),
                    'default' => 'primary-menu',
                ]
            );

            $this->end_controls_section();

            $this->start_controls_section(
                'glossymm_vertical_menu_opener',
                [
                    'label' => esc_html__( 'Vertical Menu Opener', 'glossy-mega-menu' ),
                    'tab'   => Controls_Manager::TAB_CONTENT,
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

            $this->end_controls_section();

            /* style Tab */

            $this->start_controls_section(
                'glossymm_menu_style_tab',
                [
                    'label' => esc_html__( 'Menu Wrapper', 'glossy-mega-menu' ),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'glossymm_vt_menu_opener_iconbg',
                    'label'    => esc_html__( 'Opener Icon BG', 'glossy-mega-menu' ),
                    'types'    => ['classic', 'gradient'],
                    'devices'  => ['desktop'],
                    'selector' => '{{WRAPPER}} .glossymm-menu-hamburger',
                ]
            );
            $this->add_control(
                'glossymm_vt_menu_opener_color',
                [
                    'label'     => esc_html__( 'Opener Color', 'textdomain' ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .glossymm-menu-hamburger svg' => 'fill: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'glossymm_vt_menu_opener_width',
                [
                    'label'      => esc_html__( 'Size', 'glossy-mega-menu' ),
                    'type'       => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 5,
                        ],
                    ],
                    'default'    => [
                        'unit' => 'px',
                        'size' => 25,
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .glossymm-menu-hamburger svg' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();

        }

        protected function render() {
            extract( $this->get_settings_for_display() );
            // Return if menu not selected
            if ( empty( $glossymm_vertical_nav_menu ) ) {
                return;
            }

            echo '<div class="glossymm-menu-wrapper">';
            $this->render_raw();
            echo '</div>';
        }

        protected function render_raw() {
            extract( $this->get_settings_for_display() );
            
            ?>
            <button id="glossymm_vertical_menu_open" class="glossymm-menu-hamburger glossymm-menu-toggler"  type="button" aria-label="hamburger-icon">
                <?php
            /**
             * Show Default Icon
             */
            if ( $glossymm_hamburger_icon['value'] === '' ):
            ?>
                    <span class="glossymm-menu-hamburger-icon"></span>
                    <span class="glossymm-menu-hamburger-icon"></span>
                    <span class="glossymm-menu-hamburger-icon"></span>
                <?php
            endif;
            /**
             * Show Icon or, SVG
             */
            \Elementor\Icons_Manager::render_icon( $glossymm_hamburger_icon, ['aria-hidden' => 'true', 'class' => 'glossymm-menu-icon'] );
            ?>
            </button>


            <?php
            $header_id = glossymm_get_header_id();
            $enabled_vertical_header = get_post_meta( $header_id, '_glossymm_vertical_header', true );
            if ( !empty( $enabled_vertical_header ) ) {
                add_action( "glossymm_vertical_menu", [$this,'display_vertical_header_menu'] );
            }

        }


        public function display_vertical_header_menu(){
            $settings = $this->get_settings_for_display();                
            $container_classes = ['glossymm-vertical-menu-wrapper'];
            $args = [
                'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'container'       => 'div',
                'menu'            => $settings['glossymm_vertical_nav_menu'],
                'container_id'    => 'glossymm-megamenu-vertical',
                'container_class' => join( ' ', $container_classes ),
                'menu_class'      => 'glossymm-vertical-navbar-nav',
                'depth'           => 0,
                'walker'          => new GlossyMM\GlossyMM_Vertical_Walker(),
                'echo'            => true,
                'fallback_cb'     => 'wp_page_menu',
            ];
            wp_nav_menu( $args );
        }
    }

}
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

        protected function register_controls() {
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
            if ( empty( $glossymm_vertical_nav_menu ) || !is_vertical_menu_enabled() ) {
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
        }
    }

}
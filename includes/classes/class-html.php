<?php
namespace GlossyMM {
    if ( !defined( 'ABSPATH' ) ) {
        exit;
    }
    // Exit if accessed directly

    class Html {

        public static $url = null;

        public function __construct( $file ) {
            self::$url = $file . "views/";
        }

        public static function partials( $view, $data = null ) {

            include self::$url . $view . ".php";

        }

    }

}
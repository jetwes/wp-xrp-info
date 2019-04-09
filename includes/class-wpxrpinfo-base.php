<?php

if(!class_exists('WP_XRP_Info')) {
    /**
     * WP XRP Info main class
     * (c) Jens Twesmann
     * @since 1.0.0
     */
    class WP_XRP_Info
    {
        /**
         * Single instance of the class
         *
         * @var WP_XRP_Info
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Returns single instance of the class
         *
         * @return WP_XRP_Info
         * @since 1.0.0
         */
        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Constructor
         *
         * @return WP_XRP_Info
         * @since 1.0.0
         */
        public function __construct()
        {
            include_once('class-wpxrpinfo-ledger.php');
            $use_proxy = get_option('plugin_options')['use_proxy'];
            if ($use_proxy != 'yes')
                $this->ledger = new WPXRPINFO_Ledger('https://s2.ripple.com:51234',
                    'no');
            else $this->ledger = new WPXRPINFO_Ledger('https://s2.ripple.com:51234',
                'yes');
            return $this;
        }

    }
}

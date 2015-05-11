<?php
/**
 * MgEvents
 *
 * @package Mindgruve Events
 * @author kchevalier@mindgruve.com
 * @version 1.0
 */
/*
  Plugin Name: MG Events
  Plugin URI: http://mindgruve.com/
  Description: Manage events.
  Author: kchevalier@mindgruve.com
  Version: 1.0
  Author URI: http://mindgruve.com/
 */

if (!class_exists('MgEvents')) {

    class MgEvents
    {

        /**
        *
        * PROPERTIES
        *
        */

        public static $pluginName = "MG Events";


        /**
         *
         * METHODS
         *
         */

        /**
         * Init
         *
         * @return null
         */
        public static function init()
        {
            require('MgEventsRequirements.php');
            if (MgEventsRequirements::checkRequirements()) {
                add_action('registered_post_type', array('MgEvents', 'load'));
                add_action('widgets_init', array('MgEvents', 'registerWidgets'));
            }
        }

        public static function load()
        {
            self::registerModels();
            self::registerValidators();
        }

        /**
         * Register Models
         *
         * @return null
         */
        public static function registerModels()
        {
            if(!class_exists('MgEventModel')){
                include('models/MgEventModel.php');
                add_action('init', array('MgEventModel', 'init'));
            }
            do_action('register_wpml_sync_post_type', MgEventModel::$postType, MgEventModel::$multilanguageSyncFields, MgEventModel::$taxonomy);
        }

        /**
         * Register Validators
         */
        public static function registerValidators()
        {
            $validatorClasses = array();
            $path = __DIR__ . '/validators/';
            if (realpath($path) && is_readable(realpath($path))) {
                $files = scandir($path);
                if ($files) {
                    foreach ($files as $file) {
                        preg_match('/.+\.php$/', $file, $match);
                        if (!empty($match)) {
                            $validatorClasses[] = basename($match[0], '.php');
                        }
                    }
                }
            }
            foreach ($validatorClasses as $validatorClass) {
                if (!class_exists($validatorClass) && $validatorClass != '.svn') {
                    include("validators/$validatorClass.php");
                }
            }
        }

        /**
         * Register Widgets
         *
         * @return null
         */
        public static function registerWidgets()
        {
            include('widgets/MgListEventsWidget.php');
            register_widget('MgListEventsWidget');
        }
    }

    MgEvents::init();
}

<?php
/**
 * MgEventsRequirements
 *
 * Technical requirements for the MgEvents plugin
 *
 * @package MG Events
 * @author kchevalier@mindgruve.com
 * @version 1.0
 */

class MgEventsRequirements
{

    /**
     *
     * PROPERTIES
     *
     */

    private static $minimumPhpVersion = '5.2';

    private static $classes = array(
        'MgEventModel',
    );

    private static $classDepenencies = array(

    );

    private static $functionDepenencies = array(
        'add_action'         => 1.2,
        'is_admin'           => 1.5,
        'plugin_basename'    => 1.5,
        'update_post_meta'   => 1.5,
        'get_post_meta'      => 1.5,
        'wp_nonce_field'     => 2.0,
        'current_user_can'   => 2.0,
        'wp_register_script' => 2.6,
        'wp_enqueue_script'  => 2.6,
        'register_post_type' => 2.9,
        'add_editor_style'   => 3.0, // standin for 'add_meta_box' at v3.0
    );

    private static $actionDependencies = array(
        'init'               => 2.1,
        'save_post'          => 2.1,
    );


    /**
     *
     * METHODS
     *
     */

    /**
     * Check Requirements
     *
     * @return boolean
     */
    public static function checkRequirements()
    {
        if (function_exists('add_action')) {

            // check minimum PHP requirements
            if (version_compare(phpversion(), self::$minimumPhpVersion) < 0) {
                add_action('admin_notices', array('MgEventsRequirements', 'adminErrorNoticePhp'));
                return false;
            }

            // check class name conflicts
            if (count(self::$classes)) {
                foreach (self::$classes as $class) {
                    if (class_exists($class)) {
                        add_action('admin_notices', array('MgEventsRequirements', 'adminErrorNoticeClassConflict'));
                        return false;
                    }
                }
            }

            // check class dependencies
            if (count(self::$classDepenencies)) {
                foreach (self::$classDepenencies as $class => $version) {
                    if (!class_exists($class)) {
                        add_action('admin_notices', array('MgEventsRequirements', 'adminErrorNoticeUpgradeWordpress'));
                        return false;
                    }
                }
            }

            // check function dependencies
            if (count(self::$functionDepenencies)) {
                foreach (self::$functionDepenencies as $function => $version) {
                    if (!function_exists($function)) {
                        add_action('admin_notices', array('MgEventsRequirements', 'adminErrorNoticeUpgradeWordpress'));
                        return false;
                    }
                }
            }

            // check action dependencies
            if (function_exists('has_action') && count(self::$actionDependencies)) {
                foreach (self::$actionDependencies as $action => $version) {
                    if (!has_action($action)) {
                        add_action('admin_notices', array('MgEventsRequirements', 'adminErrorNoticeUpgradeWordpress'));
                        return false;
                    }
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /* ERROR MESSAGES */

    /**
     * Admin Error Notice PHP
     *
     * @return null
     */
    public static function adminErrorNoticePhp()
    {
        echo "<div class='error'><p>" . __("The '" . MgEvents::$pluginName . "' plugin requires at least version "
            . self::$minimumPhpVersion . " of PHP. Your version is " . phpversion() . ". Please update PHP and try again.")
            . "</p></div>\n";
    }

    /**
     * Admin Error Notice Class Conflict
     *
     * @return null
     */
    public static function adminErrorNoticeClassConflict()
    {
        echo "<div class='error'><p>" . __("The '" . MgEvents::$pluginName . "' plugin has found a naming conflict. "
            . "Try disabling other plugins to see if the conflict resolves.")
            . "</p></div>\n";
    }

    /**
     * Admin Error Notice Upgrade Wordpress
     *
     * @return null
     */
    public static function adminErrorNoticeUpgradeWordpress()
    {
        $versions = array_merge(
            self::$classes,
            self::$classDepenencies,
            self::$functionDepenencies,
            self::$actionDependencies
        );
        arsort($versions);
        echo "<div class='error'><p>" . __("The '" . MgEvents::$pluginName . "' plugin requires at least version "
            . sprintf("%01.1f", reset($versions)) . " of Wordpress. Your version is " . get_bloginfo('version') . ". "
            . "Please update WordPress and try again")
            . "</p></div>\n";
    }
}

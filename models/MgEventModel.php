<?php

/**
 * MgEventModel
 *
 * A WordPress custom post type definition
 *
 * @package MG Events
 * @author kchevalier@mindgruve.com
 * @version 1.0
 */
class MgEventModel
{

    /**
     *
     * PROPERTIES
     *
     */

    public static $postType = 'mg_event';

    // Multilanguage Sync
    public static $multilanguageSyncFields = array(
        'venue',
        'address1',
        'address2',
        'city',
        'region',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'donate_url',
        'email_recipient',
        'featured',
    );

    private static $postTypeName = 'Events';
    private static $postTypeSingularName = 'Event';
    private static $postTypeSlug = 'event';
    private static $postTypeMenuIcon = 'dashicons-calendar';

    private static $locationMetaBoxTitle = 'Location';
    private static $datesMetaBoxTitle = 'Date Range';
    private static $featuredMetaBoxTitle = 'Featured?';
    private static $timesMetaBoxTitle = 'Display Times';
    private static $donateMetaBoxTitle = 'Extra Information';
    private static $typeMetaBoxTitle = 'Type of Event';

    public static $taxonomy = 'mg_event_type';
    private static $taxonomyName = 'Event Types';
    private static $taxonomySingularName = 'Event Type';
    private static $taxonomySlug = 'event-type';

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
        self::registerPostType();
        self::registerTaxonomy();
        add_action('current_screen', array('MgEventModel', 'registerAssets'));
        add_action('add_meta_boxes_' . self::$postType, array('MgEventModel', 'addMetaBoxes'));
        add_action('admin_notices', array('MgEventModel', 'handleErrorsDisplay'));
        add_action('save_post', array('MgEventModel', 'handleMetaBoxSubmit'));
        add_action('admin_menu', array('MgEventModel', 'addOptionsPage'));
        add_action(self::$taxonomy . '_add_form_fields', array('MgEventModel', 'addTaxonomyMetaFields'), 10, 2);
        add_action(self::$taxonomy . '_edit_form_fields', array('MgEventModel', 'editTaxonomyMetaFields'), 10, 2);
        add_action('edited_' . self::$taxonomy, array('MgEventModel', 'saveTaxonomyMetaFields'), 10, 2);
        add_action('create_' . self::$taxonomy, array('MgEventModel', 'saveTaxonomyMetaFields'), 10, 2);
        add_filter('manage_mg_event_posts_columns', array('MgEventModel', 'addAdminColumns'));
        add_filter('manage_mg_event_posts_custom_column', array('MgEventModel', 'getAdminColumn'), 10, 2);
    }

    /* DEFINITION */

    /**
     * Register Post Type
     *
     * @return null
     */
    public static function registerPostType()
    {
        // industry post type
        register_post_type(
            self::$postType,
            array(
                'labels'          => array(
                    'name'          => __(self::$postTypeName),
                    'singular_name' => __(self::$postTypeSingularName)
                ),
                'menu_icon'       => self::$postTypeMenuIcon,
                'public'          => true,
                'has_archive'     => true,
                'capability_type' => 'page',
                'supports'        => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'revisions',
                ),
                'rewrite'         => array(
                    'slug' => self::$postTypeSlug,
                ),
            )
        );
    }

    /**
     * Register Taxonomy
     *
     * @return null
     */
    public static function registerTaxonomy()
    {
        // service category taxonomy
        register_taxonomy(
            self::$taxonomy,
            self::$postType,
            array(
                'labels'       => array(
                    'name'              => _x(self::$taxonomyName, 'taxonomy general name'),
                    'singular_name'     => _x(self::$taxonomySingularName, 'taxonomy singular name'),
                    'search_items'      => __('Search ' . self::$taxonomyName),
                    'all_items'         => __('All ' . self::$taxonomyName),
                    'parent_item'       => __('Parent ' . self::$taxonomySingularName),
                    'parent_item_colon' => __('Parent ' . self::$taxonomySingularName . ':'),
                    'edit_item'         => __('Edit ' . self::$taxonomySingularName),
                    'update_item'       => __('Update ' . self::$taxonomySingularName),
                    'add_new_item'      => __('Add New ' . self::$taxonomySingularName),
                    'new_item_name'     => __('New ' . self::$taxonomySingularName),
                    'menu_name'         => __(self::$taxonomyName),
                ),
                'hierarchical' => true,
                'rewrite'      => array(
                    'slug' => self::$taxonomySlug,
                ),
            )
        );
    }

    public static function addOptionsPage()
    {
        wp_nonce_field(plugin_basename(__FILE__), 'location_box_content_nonce');
        add_submenu_page(
            'edit.php?post_type=' . self::$postType,
            'Event Settings',
            'Settings',
            'edit_posts',
            basename(__FILE__),
            array('MgEventModel', 'showOptionsPage')
        );
    }

    public static function addTaxonomyMetaFields()
    {
        echo '<div class="form-field">
            <label for="icon_html">Icon HTML</label>
            <input type="text" name="icon_html" id="icon_html" value="">
            <p class="description">Enter icon HTML for this event type</p>
        </div>';
    }

    public static function editTaxonomyMetaFields($term)
    {
        // put the term ID into a variable
        $t_id = $term->term_id;

        // retrieve the existing value(s) for this meta field. This returns an array
        $icon_html = get_option('taxonomy_' . $t_id . '_icon_html');

        echo '<tr class="form-field">
            <th scope="row" valign="top"><label for="icon_html">Icon HTML</label></th>
                <td>
                    <textarea name="icon_html" id="icon_html">' . $icon_html . '</textarea>
                    <p class="description">Enter icon HTML for this event type</p>
                </td>
            </tr>';
    }

    public static function saveTaxonomyMetaFields($term_id)
    {
        if (isset($_POST['icon_html'])) {
            update_option('taxonomy_' . $term_id . '_icon_html', stripslashes($_POST['icon_html']));
        }
    }

    public static function showOptionsPage()
    {
        $nonceName  = 'mg_event_options_box';
        $optionName = 'mg_event_policy_pdf';

        if (isset($_POST[$nonceName]) && isset($_POST[$optionName]) && wp_verify_nonce(
                $_POST[$nonceName],
                plugin_basename(__FILE__)
            )
        ) {
            update_option($optionName, $_POST[$optionName]);
        }

        // display form
        $eventsPolicyPdf = get_option($optionName, '');
        $nonceField      = wp_nonce_field(plugin_basename(__FILE__), $nonceName);
        include(__DIR__ . '/../views/admin-options-box.php');
    }


    /* META DATA UI */

    /**
     * Add Meta Boxes
     *
     * @return null
     */
    public static function addMetaBoxes()
    {

        // add location box
        add_meta_box(
            'location_box',
            __(self::$locationMetaBoxTitle),
            array('MgEventModel', 'locationBoxContent'),
            self::$postType,
            'normal',
            'high'
        );

        // add featured box
        add_meta_box(
            'dates_box',
            __(self::$featuredMetaBoxTitle),
            array('MgEventModel', 'featuredBoxContent'),
            self::$postType,
            'side',
            'core'
        );

        // add dates box
        add_meta_box(
            'featured_box',
            __(self::$datesMetaBoxTitle),
            array('MgEventModel', 'datesBoxContent'),
            self::$postType,
            'side',
            'core'
        );

        // add times box
        add_meta_box(
            'times_box',
            __(self::$timesMetaBoxTitle),
            array('MgEventModel', 'timesBoxContent'),
            self::$postType,
            'normal',
            'high'
        );

        // add donate url box
        add_meta_box(
            'donate_url_box',
            __(self::$donateMetaBoxTitle),
            array('MgEventModel', 'extraInformationBoxContent'),
            self::$postType,
            'normal',
            'high'
        );

        // add type box
        add_meta_box(
            'type_box',
            __(self::$typeMetaBoxTitle),
            array('MgEventModel', 'typeBoxContent'),
            self::$postType,
            'side',
            'default'
        );
        //remove_meta_box('tagsdiv-mg_event_type', 'mg_event', 'side');
        remove_meta_box('mg_event_typediv', 'mg_event', 'side');
    }

    /**
     * Location Box Content
     *
     * @param WP_Post $post
     * @return null
     */
    public static function locationBoxContent($post)
    {

        // get meta values
        $venue       = isset($_SESSION[self::$postType]['post_data']['venue']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['venue']
        ) : get_post_meta(
            $post->ID,
            'venue',
            true
        );
        $address1    = isset($_SESSION[self::$postType]['post_data']['address1']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['address1']
        ) : get_post_meta(
            $post->ID,
            'address1',
            true
        );
        $address2    = isset($_SESSION[self::$postType]['post_data']['address2']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['address2']
        ) : get_post_meta(
            $post->ID,
            'address2',
            true
        );
        $city        = isset($_SESSION[self::$postType]['post_data']['city']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['city']
        ) : get_post_meta(
            $post->ID,
            'city',
            true
        );
        $region      = isset($_SESSION[self::$postType]['post_data']['region']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['region']
        ) : get_post_meta(
            $post->ID,
            'region',
            true
        );
        $country     = isset($_SESSION[self::$postType]['post_data']['country']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['country']
        ) : get_post_meta(
            $post->ID,
            'country',
            true
        );
        $postal_code = isset($_SESSION[self::$postType]['post_data']['postal_code']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['postal_code']
        ) : get_post_meta(
            $post->ID,
            'postal_code',
            true
        );
        $latitude    = isset($_SESSION[self::$postType]['post_data']['latitude']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['latitude']
        ) : get_post_meta(
            $post->ID,
            'latitude',
            true
        );
        $longitude   = isset($_SESSION[self::$postType]['post_data']['longitude']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['longitude']
        ) : get_post_meta(
            $post->ID,
            'longitude',
            true
        );

        unset($_SESSION[self::$postType]['post_data']['venue']);
        unset($_SESSION[self::$postType]['post_data']['address1']);
        unset($_SESSION[self::$postType]['post_data']['address2']);
        unset($_SESSION[self::$postType]['post_data']['city']);
        unset($_SESSION[self::$postType]['post_data']['region']);
        unset($_SESSION[self::$postType]['post_data']['country']);
        unset($_SESSION[self::$postType]['post_data']['postal_code']);
        unset($_SESSION[self::$postType]['post_data']['latitude']);
        unset($_SESSION[self::$postType]['post_data']['longitude']);

        // display form
        wp_nonce_field(plugin_basename(__FILE__), 'location_box_content_nonce');
        include(__DIR__ . '/../views/admin-location-meta-box.php');
    }

    public static function featuredBoxContent($post)
    {
        $event_featured = get_post_meta($post->ID, 'event_featured', true);
        wp_nonce_field(plugin_basename(__FILE__), 'featured_box_content_nonce');
        include(__DIR__ . '/../views/admin-featured-meta-box.php');
    }


    /**
     * Dates Box Content
     *
     * @param WP_Post $post
     * @return null
     */
    public static function datesBoxContent($post)
    {

        // get meta values
        $startDateUnix = isset($_SESSION[self::$postType]['post_data']['start_date']) ? strtotime(
            trim($_SESSION[self::$postType]['post_data']['start_date'])
        ) : get_post_meta($post->ID, 'start_date', true);
        $endDateUnix   = isset($_SESSION[self::$postType]['post_data']['end_date']) ? strtotime(
            trim($_SESSION[self::$postType]['post_data']['end_date'])
        ) : get_post_meta($post->ID, 'end_date', true);
        $startDate     = $startDateUnix ? date('m/d/Y', intval($startDateUnix)) : '';
        $endDate       = $endDateUnix ? date('m/d/Y', intval($endDateUnix)) : '';
        $startTime     = get_post_meta($post->ID, 'start_time', true);
        $endTime       = get_post_meta($post->ID, 'end_time', true);

        unset($_SESSION[self::$postType]['post_data']['start_date']);
        unset($_SESSION[self::$postType]['post_data']['end_date']);

        // break times up into discrete parts
        $startTimeHour   = null;
        $startTimeMinute = null;
        $startTimeAmpm   = 'am';
        $endTimeHour     = null;
        $endTimeMinute   = null;
        $endTimeAmpm     = 'am';

        if (preg_match('/^(\d{1,2}):(\d{1,2})$/', $startTime)) {
            $startTimeHour   = date("g", strtotime($startTime));
            $startTimeMinute = date("i", strtotime($startTime));
            $startTimeAmpm   = date("a", strtotime($startTime));
        }

        if (preg_match('/^(\d{1,2}):(\d{1,2})$/', $endTime)) {
            $endTimeHour   = date("g", strtotime($endTime));
            $endTimeMinute = date("i", strtotime($endTime));
            $endTimeAmpm   = date("a", strtotime($endTime));
        }

        if ($startDate == $endDate) {
            $endDate = '';
        }

        // display form
        wp_nonce_field(plugin_basename(__FILE__), 'dates_box_content_nonce');
        include(__DIR__ . '/../views/admin-dates-meta-box.php');
    }

    /**
     * Times Box Content
     *
     * @param WP_Post $post
     * @return null
     */
    public static function timesBoxContent($post)
    {

        // get meta values
        $textDate = isset($_SESSION[self::$postType]['post_data']['text_date']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['text_date']
        ) : get_post_meta(
            $post->ID,
            'text_date',
            true
        );
        $textTime = isset($_SESSION[self::$postType]['post_data']['text_time']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['text_time']
        ) : get_post_meta(
            $post->ID,
            'text_time',
            true
        );

        unset($_SESSION[self::$postType]['post_data']['text_date']);
        unset($_SESSION[self::$postType]['post_data']['text_time']);

        // display form
        wp_nonce_field(plugin_basename(__FILE__), 'times_box_content_nonce');
        include(__DIR__ . '/../views/admin-times-meta-box.php');
    }

    /**
     * Dontate URL Box Content
     *
     * @param WP_Post $post
     * @return null
     */
    public static function extraInformationBoxContent($post)
    {

        // get meta values
        $donateLabel    = isset($_SESSION[self::$postType]['post_data']['donate_label']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['donate_label']
        ) : get_post_meta(
            $post->ID,
            'donate_label',
            true
        );
        $donateUrl      = isset($_SESSION[self::$postType]['post_data']['donate_url']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['donate_url']
        ) : get_post_meta(
            $post->ID,
            'donate_url',
            true
        );
        $emailRecipient = isset($_SESSION[self::$postType]['post_data']['email_recipient']) ? stripslashes(
            $_SESSION[self::$postType]['post_data']['email_recipient']
        ) : get_post_meta(
            $post->ID,
            'email_recipient',
            true
        );

        unset($_SESSION[self::$postType]['post_data']['donate_label']);
        unset($_SESSION[self::$postType]['post_data']['donate_url']);
        unset($_SESSION[self::$postType]['post_data']['email_recipient']);

        // display form
        wp_nonce_field(plugin_basename(__FILE__), 'extra_information_box_content_nonce');
        include(__DIR__ . '/../views/admin-extra-information-meta-box.php');
    }

    /**
     * Type Box Content
     *
     * @param WP_Post $post
     * @return null
     */
    public static function typeBoxContent($post)
    {

        // get data
        $eventTypes = get_terms('mg_event_type', 'hide_empty=0&orderby=name&order=DESC');
        $types      = wp_get_object_terms($post->ID, 'mg_event_type');

        // display form
        wp_nonce_field(plugin_basename(__FILE__), 'type_box_content_nonce');
        include(__DIR__ . '/../views/admin-type-meta-box.php');
    }

    /* META DATA SAVE */

    /**
     * Handle Meta Box Submit
     *
     * @param integer $postId
     * @return null
     */
    public static function handleMetaBoxSubmit($postId)
    {
        $_SESSION[self::$postType]['post_data'] = $_POST;
        self::locationBoxSave($postId, $_POST);
        self::featuredBoxSave($postId, $_POST);
        self::datesBoxSave($postId, $_POST);
        self::timesBoxSave($postId, $_POST);
        self::extraInformationBoxSave($postId, $_POST);
        self::typeBoxSave($postId, $_POST);
    }

    public static function handleErrorsDisplay()
    {
        $validatorClasses = array();
        $path             = __DIR__ . '/../validators/';
        if (realpath($path) && is_readable(realpath($path))) {
            $files = scandir($path);
            if ($files) {
                foreach ($files as $file) {
                    preg_match('/^.*\.[A-Za-z]+$/', $file, $match);
                    if (!empty($match)) {
                        $validatorClasses[] = basename($match[0], '.php');
                    }
                }
            }
        }
        if (!empty($validatorClasses)) {
            foreach ($validatorClasses as $validatorClass) {
                if (isset($_SESSION[$validatorClass]['errorSchema']) && $_SESSION[$validatorClass]['errorSchema']) {
                    $error = "<div class='error'><p>";
                    foreach ($_SESSION[$validatorClass]['errorSchema'] as $field => $messages) {
                        if (count($messages)) {
                            foreach ($messages as $message) {
                                $error .= '<strong>' . ucwords(
                                        str_replace('_', ' ', $field)
                                    ) . ": " . '</strong>' . $message . '<br>';
                            }
                        }
                    }
                    $error .= "</p></div>";
                    echo $error;
                    unset($_SESSION[$validatorClass]['errorSchema']);
                }
            }
        }
    }

    /**
     * Type Box Save
     *
     * @param int $postId
     * @param array $values = array()
     * @return null
     */
    private static function featuredBoxSave($postId, $values = array())
    {
        if (self::metaSaveVerifications('featured_box_content_nonce', $postId)) {

            // save values
            if (isset($values['event_featured'])) {
                $featured = trim($values['event_featured']);
                update_post_meta($postId, 'event_featured', true);
            }else {
                update_post_meta($postId, 'event_featured', false);
            }
        }
    }

    /**
     * Location Box Save
     *
     * @param int $postId
     * @param array $values = array()
     * @return null
     */
    private static function locationBoxSave($postId, $values = array())
    {
        if (self::metaSaveVerifications('location_box_content_nonce', $postId)) {

            $locationValidator = new MgEventLocationValidator(
                array(
                    'venue'     => array('required' => false, 'max_length' => 100),
                    'address1'  => array('required' => false, 'max_length' => 100),
                    'address2'  => array('required' => false, 'max_length' => 100),
                    'latitude'  => array('required' => false, 'max_length' => 20),
                    'longitude' => array('required' => false, 'max_length' => 20),
                )
            );
            $locationValidator->bind($values);

            if ($locationValidator->isValid()) {

                // save values
                if (isset($values['venue'])) {
                    $venue = trim($values['venue']);
                    update_post_meta($postId, 'venue', $venue);
                }
                if (isset($values['address1'])) {
                    $address1 = trim($values['address1']);
                    update_post_meta($postId, 'address1', $address1);
                }
                if (isset($values['address2'])) {
                    $address2 = trim($values['address2']);
                    update_post_meta($postId, 'address2', $address2);
                }
                if (isset($values['city'])) {
                    $city = trim($values['city']);
                    update_post_meta($postId, 'city', $city);
                }
                if (isset($values['region'])) {
                    $region = trim($values['region']);
                    update_post_meta($postId, 'region', $region);
                }
                if (isset($values['country'])) {
                    $country = trim($values['country']);
                    update_post_meta($postId, 'country', $country);
                }
                if (isset($values['postal_code'])) {
                    $postal_code = trim($values['postal_code']);
                    update_post_meta($postId, 'postal_code', $postal_code);
                }
                if (isset($values['latitude'])) {
                    $latitude = trim($values['latitude']);
                    update_post_meta($postId, 'latitude', $latitude);
                }
                if (isset($values['longitude'])) {
                    $longitude = trim($values['longitude']);
                    update_post_meta($postId, 'longitude', $longitude);
                }
            }
        }
    }

    /**
     * Dates Box Save
     *
     * @param int $postId
     * @param array $values = array()
     * @return null
     */
    private static function datesBoxSave($postId, $values = array())
    {
        if (self::metaSaveVerifications('dates_box_content_nonce', $postId)) {

            $datesValidator = new MgEventDatesValidator(
                array(
                    'start_date' => array(
                        'required'    => true,
                        'max_length'  => 10,
                        'date_format' => '/[0-9]{2,4}\/[0-9]{2}\/[0-9]{2,4}/'
                    ),
                    'end_date'   => array(
                        'required'    => false,
                        'max_length'  => 10,
                        'date_format' => '/[0-9]{2,4}\/[0-9]{2}\/[0-9]{2,4}/'
                    )
                )
            );
            $datesValidator->bind($values);

            if ($datesValidator->isValid()) {

                // save values
                if (isset($values['start_date'])) {
                    $startDate = strtotime(trim($values['start_date']));
                    update_post_meta($postId, 'start_date', $startDate);
                }
                if (isset($values['end_date'])) {
                    $endDate = strtotime(trim($values['end_date']));
                    if (!$endDate) {
                        $endDate = $startDate;
                    }
                    update_post_meta($postId, 'end_date', $endDate);
                }
                if (isset($values['start_time_hour']) && isset($values['start_time_minute']) && isset($values['start_time_ampm'])) {
                    $startTimeHour   = intval(trim($values['start_time_hour']));
                    $startTimeMinute = trim($values['start_time_minute']);
                    $startTimeAmpm   = trim($values['start_time_ampm']);

                    $startTimeHour = date(
                        "H",
                        strtotime(
                            sprintf('%02d', $startTimeHour)
                            . ':'
                            . sprintf('%02d', $startTimeMinute)
                            . ' '
                            . $startTimeAmpm
                        )
                    );

                    update_post_meta(
                        $postId,
                        'start_time',
                        sprintf('%02d', $startTimeHour) . ':' . sprintf('%02d', $startTimeMinute)
                    );
                }
                if (isset($values['end_time_hour']) && isset($values['end_time_minute']) && isset($values['end_time_ampm'])) {
                    $endTimeHour   = intval(trim($values['end_time_hour']));
                    $endTimeMinute = trim($values['end_time_minute']);
                    $endTimeAmpm   = trim($values['end_time_ampm']);

                    $endTimeHour = date(
                        "H",
                        strtotime(
                            sprintf('%02d', $endTimeHour)
                            . ':'
                            . sprintf('%02d', $endTimeMinute)
                            . ' '
                            . $endTimeAmpm
                        )
                    );

                    update_post_meta(
                        $postId,
                        'end_time',
                        sprintf('%02d', $endTimeHour) . ':' . sprintf('%02d', $endTimeMinute)
                    );
                }
            }
        }
    }

    /**
     * Times Box Save
     *
     * @param int $postId
     * @param array $values = array()
     * @return null
     */
    private static function timesBoxSave($postId, $values = array())
    {
        if (self::metaSaveVerifications('times_box_content_nonce', $postId)) {
            $timesValidator = new MgEventExtraInformationValidator(
                array(
                    'text_date' => array('required' => false, 'max_length' => 500),
                    'text_time' => array('required' => false, 'max_length' => 500),
                )
            );
            $timesValidator->bind($values);

            if ($timesValidator->isValid()) {

                // save values
                if (isset($values['text_date'])) {
                    $textDate = trim($values['text_date']);
                    update_post_meta($postId, 'text_date', $textDate);
                }

                // save values
                if (isset($values['text_time'])) {
                    $textTime = trim($values['text_time']);
                    update_post_meta($postId, 'text_time', $textTime);
                }
            }
        }
    }

    /**
     * Donate Box Save
     *
     * @param int $postId
     * @param array $values = array()
     * @return null
     */
    private static function extraInformationBoxSave($postId, $values = array())
    {
        if (self::metaSaveVerifications('extra_information_box_content_nonce', $postId)) {
            $extraInformationValidator = new MgEventExtraInformationValidator(
                array(
                    'donate_label'    => array('required' => false, 'max_length' => 100),
                    'donate_url'      => array('url' => true),
                    'email_recipient' => array('email' => true)
                )
            );
            $extraInformationValidator->bind($values);

            if ($extraInformationValidator->isValid()) {

                // save values
                if (isset($values['donate_label'])) {
                    $donateLabel = trim($values['donate_label']);
                    update_post_meta($postId, 'donate_label', $donateLabel);
                }

                // save values
                if (isset($values['donate_url'])) {
                    $donateUrl = trim($values['donate_url']);
                    update_post_meta($postId, 'donate_url', $donateUrl);
                }

                // save values
                if (isset($values['email_recipient'])) {
                    $donateUrl = trim($values['email_recipient']);
                    update_post_meta($postId, 'email_recipient', $donateUrl);
                }
            }
        }
    }

    /**
     * Type Box Save
     *
     * @param int $postId
     * @param array $values = array()
     * @return null
     */
    private static function typeBoxSave($postId, $values = array())
    {
        if (self::metaSaveVerifications('type_box_content_nonce', $postId)) {

            // save values
            if (isset($values['type'])) {
                $type = trim($values['type']);
                wp_set_object_terms($postId, $type, 'mg_event_type');
            }
        }
    }

    /* ASSETS */

    /**
     * Register Asset
     *
     * @return null
     */
    public static function registerAssets($currentScreen)
    {
        if (is_admin() && $currentScreen->post_type == self::$postType) {

            wp_register_script(
                'mg-events-admin',
                WPMU_PLUGIN_URL . "/mg-events/assets/mg-events-admin.js",
                false
            );
            wp_register_script('googlemaps', "//maps.google.com/maps/api/js?sensor=false", false);

            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_enqueue_script('mg-events-admin');
            wp_enqueue_script('googlemaps');

            wp_enqueue_style(
                'jquery-style',
                'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css'
            );
            wp_enqueue_style('thickbox');
        }
    }

    /* HELPER METHODS */

    /**
     * Meta Save Verifications
     *
     * @param string $nonceName
     * @param int $postId
     * @return boolean
     */
    private static function metaSaveVerifications($nonceName, $postId)
    {
        $return = true;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            $return = false;
        }

        if (!isset($_POST[$nonceName]) || !wp_verify_nonce($_POST[$nonceName], plugin_basename(__FILE__))) {
            $return = false;
        }

        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $postId)) {
                $return = false;
            }
        } else {
            if (!current_user_can('edit_post', $postId)) {
                $return = false;
            }
        }

        return $return;
    }
    
    public static function addAdminColumns($columns)
    {
        $original = array(
            'cb'             => $columns['cb'],
            'title'          => $columns['title'],
            'start_date'     => 'Start Date',
            'featured'       => 'Featured',
            'featured_image' => 'Image',
        );

        return $original;
    }

    public static function getAdminColumn($column, $postId)
    {
        switch ($column) {
            case 'start_date' :

				$startDateUnix = get_post_meta($postId, 'start_date', true);
				$startDate = $startDateUnix ? date('m/d/Y', intval($startDateUnix)) : '';
				echo $startDate;
                break;
            case 'featured':
            
            	$featured = get_post_meta($postId, 'event_featured', true);
            	if($featured == true) {
            		echo "Yes";
            	}
                break;
            case 'featured_image':
                if (has_post_thumbnail($postId)) {
                    echo get_the_post_thumbnail($postId, array(64, 64));
                }

                break;
        }
    }
}

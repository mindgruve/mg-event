<?php
/**
 * MgListEventsWidget
 *
 * A WordPress widget definition
 *
 * @package MG Events
 * @author kchevalier@mindgruve.com
 * @version 1.0
 */

class MgListEventsWidget extends WP_Widget
{

    /**
     *
     * PROPERTIES
     *
     */

    private $widgetName        = "List Events";
    private $widgetId          = 'mg-list-events-widget';
    private $widgetClassName   = 'mg-list-events';
    private $widgetDescription = "Display a list of events";
    private $controlId         = 'mg-list-events-widget';


    /**
     *
     * METHODS
     *
     */

    /**
     * Constructor
     */
    function MgListEventsWidget()
    {

        // create widget
        $this->WP_Widget(
            $this->widgetId,
            $this->widgetName,
            array(
                'classname'   => $this->widgetClassName,
                'description' => $this->widgetDescription,
            ),
            array(
                'id_base'     => $this->controlId,
            )
        );

        // register shortcodes
        self::registerShortcodes();
    }

    /**
     * Widget
     *
     *  Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    function widget($args, $instance)
    {

        extract($args);

        $output      = '';

        $title            = ( isset($instance['title']) ) ? $instance['title'] : '';
        $eventType        = ( isset($instance['eventType']) ) ? $instance['eventType'] : 0;
        $wordCount        = ( isset($instance['wordCount']) ) ? $instance['wordCount'] : 25;
        $showDate         = ( isset($instance['showDate']) ) ? $instance['showDate'] : false;
        $showThumbnail    = ( isset($instance['showThumbnail']) ) ? $instance['showThumbnail'] : false;
        $limit            = ( isset($instance['limit']) ) ? $instance['limit'] : 5;
        $orderBy          = ( isset($instance['orderBy']) ) ? $instance['orderBy'] : 'date';
        $order            = ( isset($instance['order']) ) ? $instance['order'] : 'DESC';
        $postCategory     = ( isset($instance['postCategory']) ) ? $instance['postCategory'] : 0;
        $postHasThumbnail = ( isset($instance['postHasThumbnail']) ) ? $instance['postHasThumbnail'] : false;
        $postLimit        = ( isset($instance['postLimit']) ) ? $instance['postLimit'] : 0;
        $postOrderBy      = ( isset($instance['postOrderBy']) ) ? $instance['postOrderBy'] : 'date';
        $postOrder        = ( isset($instance['postOrder']) ) ? $instance['postOrder'] : 'DESC';

        $output .= $before_widget . "\n";
        $output .= self::getOutput(
            $before_title,
            $after_title,
            $title,
            $eventType,
            $wordCount,
            $showDate,
            $showThumbnail,
            $limit,
            $orderBy,
            $order,
            $postCategory,
            $postHasThumbnail,
            $postLimit,
            $postOrderBy,
            $postOrder
        ) . "\n";
        $output .= $after_widget . "\n";

        echo $output;
    }




    /**
     * Instance
     *
     * @param array $instance
     */
    function form($instance)
    {

        $defaults = array(
            'title'            => '',
            'eventType'        => 0,
            'wordCount'        => 25,
            'showDate'         => true,
            'showThumbnail'    => false,
            'limit'            => 5,
            'orderBy'          => 'date',
            'order'            => 'DESC',
            'postCategory'     => 0,
            'postHasThumbnail' => true,
            'postLimit'        => 0,
            'postOrderBy'      => 'date',
            'postOrder'        => 'DESC',
        );
        $instance = wp_parse_args((array) $instance, $defaults);

        $eventTypeOptions = array(
            0 => 'All Types',
        );
        $eventTypes = get_terms(MgEventModel::$taxonomy, 'hide_empty=0&orderby=name&order=DESC');
        if ($eventTypes) {
            foreach ($eventTypes as $eventType) {
                $eventTypeOptions[$eventType->slug] = $eventType->name;
            }
        }

        $categoryOptions = array(
            0 => 'All Categories',
        );
        $categories = get_categories();
        if ($categories) {
            foreach ($categories as $category) {
                $categoryOptions[$category->term_id] = $category->name;
            }
        }

        $limitOptions = array('0' => '0', '1'  => '1', '2'  => '2', '3'  => '3', '4'  => '4', '5'  => '5', '6'  => '6',
            '7'  => '7', '8'  => '8', '9'  => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14',
            '15' => '15', '20' => '20', '25' => '25', '50' => '50');

        $orderByOptions = array(
            'menu_order' => 'Menu Order',
            'title'      => 'Title',
            'date'       => 'Date',
            'rand'       => 'Random'
        );

        $orderOptions = array(
            'ASC'  => 'Ascending',
            'DESC' => 'Descending',
        );

        include(__DIR__ . '/../views/admin-widget-form-mg-list-events.php');
    }

    /**
     * Update
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    function update($new_instance, $old_instance)
    {
        $instance                     = $old_instance;
        $instance['title']            = $new_instance['title'];
        $instance['eventType']        = $new_instance['eventType'];
        $instance['wordCount']        = intval($new_instance['wordCount']);
        $instance['showDate']         = $new_instance['showDate'];
        $instance['showThumbnail']    = $new_instance['showThumbnail'];
        $instance['limit']            = intval($new_instance['limit']);
        $instance['orderBy']          = $new_instance['orderBy'];
        $instance['order']            = $new_instance['order'];
        $instance['postCategory']     = intval($new_instance['postCategory']);
        $instance['postHasThumbnail'] = $new_instance['postHasThumbnail'];
        $instance['postLimit']        = intval($new_instance['postLimit']);
        $instance['postOrderBy']      = $new_instance['postOrderBy'];
        $instance['postOrder']        = $new_instance['postOrder'];
        return $instance;
    }



    /**
     * Register Shortcodes
     *
     * @return null
     */
    public function registerShortcodes()
    {
        add_shortcode('list_events', array('MgListEventsWidget', 'getOutput'));
    }

    /**
     * Show
     *
     *  Displays the HTML output on the standard display
     *
     * @param string $before_title
     * @param string $after_title
     */
    public static function show(
        $before_title = '<h4>',
        $after_title = '</h4>',
        $title = '',
        $eventType = 0,
        $wordCount = 25,
        $showDate = true,
        $showThumbnail = true,
        $limit = 5,
        $orderBy = 'date',
        $order = 'DESC',
        $postCategory = 0,
        $postHasThumbnail = true,
        $postLimit = 0,
        $postOrderBy = 'date',
        $postOrder = 'DESC'
    ) {
        $output = self::getOutput(
            $before_title,
            $after_title,
            $title,
            $eventType,
            $wordCount,
            $showDate,
            $showThumbnail,
            $limit,
            $orderBy,
            $order,
            $postCategory,
            $postHasThumbnail,
            $postLimit,
            $postOrderBy,
            $postOrder
        ) . "\n";
        echo $output;
    }

    /**
     * Get Output
     *
     * @param string $before_title
     * @param string $after_title
     * @return string
     */
    private static function getOutput(
        $before_title = '<h4>',
        $after_title = '</h4>',
        $title = '',
        $eventType = 0,
        $wordCount = 25,
        $showDate = true,
        $showThumbnail = true,
        $limit = 5,
        $orderBy = 'date',
        $order = 'DESC',
        $postCategory = 0,
        $postHasThumbnail = true,
        $postLimit = 0,
        $postOrderBy = 'date',
        $postOrder = 'DESC'
    ) {

        $output = '';
        $events = $posts = $postCategoryTitle = null;

        // construct query parameters
        $queryParams = array(
            'post_type'      => MgEventModel::$postType,
            'posts_per_page' => $limit,
            'orderby'        => $orderBy,
            'order'          => $order,
        );
        if ($eventType) {
            $queryParams['tax_query'] = array(
                array(
                    'taxonomy' => MgEventModel::$taxonomy,
                    'field' => 'slug',
                    'terms' => $eventType
                )
            );
        }

        $queryParams['meta_query']  =  array(
            array(
                'key' => 'end_date',
                'value' => mktime(0, 0, 0, date('n'), (date('j')-1), date('Y')),
                'type' => 'numeric',
                'compare' => '>=',
            ),
            array(
                'key' => 'event_visibility',
                'value' => 'visible',
                'compare' => '='
            ),
        );

        // perform query
        $events = new WP_Query($queryParams);

        // query posts
        if ($postLimit) {

            // construct poset query parameters
            $postQueryParams = array(
                'post_type'      => 'post',
                'posts_per_page' => $postLimit,
                'orderby'        => $postOrderBy,
                'order'          => $postOrder,
            );

            if ($postCategory) {
                $postQueryParams['cat'] = intval($postCategory);
                $postCat = get_category($postCategory);
                if ($postCat) {
                    $postCategoryTitle = $postCat->name;
                }
            }

            if ($postHasThumbnail) {
                $postQueryParams['meta_key'] = '_thumbnail_id';
            }

            // perform query
            $posts = new WP_Query($postQueryParams);
        }



        // get output
        ob_start();
        if (is_front_page() && file_exists(get_template_directory() . DIRECTORY_SEPARATOR . 'widget-mg-list-events-home.php')) {
            include(get_template_directory() . DIRECTORY_SEPARATOR . 'widget-mg-list-events-home.php');
        } elseif (file_exists(get_template_directory() . DIRECTORY_SEPARATOR . 'widget-mg-list-events.php')) {
            include(get_template_directory() . DIRECTORY_SEPARATOR . 'widget-mg-list-events.php');
        } else {
            include(__DIR__ . '/../views/widget-mg-list-events.php');
        }
        $output = ob_get_clean();

        return $output;
    }
}

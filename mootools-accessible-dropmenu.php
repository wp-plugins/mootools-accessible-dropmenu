<?php
/*
Plugin Name: MooTools Accessible Dropmenu
Plugin URI: http://wordpress.org/extend/plugins/mootools-accessible-dropmenu/
Description: WAI-ARIA Enabled Dropmenu Plugin for Wordpress
Author: Kontotasiou Dionysia
Version: 1.0
Author URI: http://www.iti.gr/iti/people/Dionisia_Kontotasiou.html
*/
include_once 'getRecentPosts.php';
include_once 'getRecentComments.php';
include_once 'getCategories.php';
include_once 'getMeta.php';

add_action("plugins_loaded", "MooToolsAccessibleDropmenu_init");
function MooToolsAccessibleDropmenu_init() {
    register_sidebar_widget(__('MooTools Accessible Dropmenu'), 'widget_MooToolsAccessibleDropmenu');
    register_widget_control(   'MooTools Accessible Dropmenu', 'MooToolsAccessibleDropmenu_control', 200, 200 );
    if ( !is_admin() && is_active_widget('widget_MooToolsAccessibleDropmenu') ) {
        wp_deregister_script('jquery');

        // add your own script
        wp_register_script('mootools-core', ( get_bloginfo('wpurl') . '/wp-content/plugins/mootools-accessible-dropmenu/lib/mootools-core.js'));
        wp_enqueue_script('mootools-core');

        wp_register_script('MooDropMenu', ( get_bloginfo('wpurl') . '/wp-content/plugins/mootools-accessible-dropmenu/lib/MooDropMenu.js'));
        wp_enqueue_script('MooDropMenu');
		
        wp_register_script('mootools-accessible-dropmenu', ( get_bloginfo('wpurl') . '/wp-content/plugins/mootools-accessible-dropmenu/lib/mootools-accessible-dropmenu.js'));
        wp_enqueue_script('mootools-accessible-dropmenu');

        wp_register_style('mootools-accessible-dropmenu_css', ( get_bloginfo('wpurl') . '/wp-content/plugins/mootools-accessible-dropmenu/lib/mootools-accessible-dropmenu.css'));
        wp_enqueue_style('mootools-accessible-dropmenu_css');
    }
}

function widget_MooToolsAccessibleDropmenu($args) {
    extract($args);

    $options = get_option("widget_MooToolsAccessibleDropmenu");
    if (!is_array( $options )) {
        $options = array(
                'title' => 'Mootools Accessible Dropmenu',
            'categories' => 'Categories',
            'meta' => 'Meta',
            'recentPosts' => 'Recent Posts',
            'recentComments' => 'Recent Comments'
        );
    }

    echo $before_widget;
    echo $before_title;
    echo $options['title'];
    echo $after_title;

    //Our Widget Content
    MooToolsAccessibleDropmenuContent();
    echo $after_widget;
}

function MooToolsAccessibleDropmenuContent() {
    $recentPosts = get_recent_posts();
    $recentComments = get_recent_comments();
    $categories = get_my_categories();
    $meta = get_my_meta();
	
    $options = get_option("widget_MooToolsAccessibleDropmenu");
    if (!is_array($options)) {
        $options = array(
            'title' => 'Mootools Accessible Dropmenu',
            'categories' => 'Categories',
            'meta' => 'Meta',
            'recentPosts' => 'Recent Posts',
            'recentComments' => 'Recent Comments'
        );
    }

    echo '
    
   <div class="demo" role="application">
   <div id="nav_wrapper">
        <ul id="nav">
                <li><a href="#">' . $options['recentPosts'] . '</a>
                    <ul aria-label="Recent Posts">
                        ' . $recentPosts . '
                    </ul>
                </li>
                <li>
                    <a href="#">' . $options['recentComments'] . '</a>
                    <ul aria-label="Recent Comments">
                        ' . $recentComments . '
                    </ul>
                </li>
                <li>
                    <a href="#">' . $options['categories'] . '</a>
                    <ul aria-label="Categories">
                        ' . $categories . '
                    </ul>
                </li>
                <li><a href="#">' . $options['meta'] . '</a>
                    <ul aria-label="Meta">
                        ' . $meta . '
                    </ul>
                </li>
            </ul>

</div>';
}

function MooToolsAccessibleDropmenu_control() {
    $options = get_option("widget_MooToolsAccessibleDropmenu");
    if (!is_array( $options )) {
        $options = array(
                 'title' => 'Mootools Accessible Dropmenu',
            'categories' => 'Categories',
            'meta' => 'Meta',
            'recentPosts' => 'Recent Posts',
            'recentComments' => 'Recent Comments'
        );
    }

    if ($_POST['MooToolsAccessibleDropmenu-SubmitTitle']) {
        $options['title'] = htmlspecialchars($_POST['MooToolsAccessibleDropmenu-WidgetTitle']);
        update_option("widget_MooToolsAccessibleDropmenu", $options);
    }
    if ($_POST['MooToolsAccessibleDropmenu-SubmitCategories']) {
        $options['categories'] = htmlspecialchars($_POST['MooToolsAccessibleDropmenu-WidgetCategories']);
        update_option("widget_MooToolsAccessibleDropmenu", $options);
    }
    if ($_POST['MooToolsAccessibleDropmenu-SubmitMeta']) {
        $options['meta'] = htmlspecialchars($_POST['MooToolsAccessibleDropmenu-WidgetMeta']);
        update_option("widget_MooToolsAccessibleDropmenu", $options);
    }
    if ($_POST['MooToolsAccessibleDropmenu-SubmitRecentPosts']) {
        $options['recentPosts'] = htmlspecialchars($_POST['MooToolsAccessibleDropmenu-WidgetRecentPosts']);
        update_option("widget_MooToolsAccessibleDropmenu", $options);
    }
    if ($_POST['MooToolsAccessibleDropmenu-SubmitRecentComments']) {
        $options['recentComments'] = htmlspecialchars($_POST['MooToolsAccessibleDropmenu-WidgetRecentComments']);
        update_option("widget_MooToolsAccessibleDropmenu", $options);
    }
    ?>
    <p>
        <label for="MooToolsAccessibleDropmenu-WidgetTitle">Widget Title: </label>
        <input type="text" id="MooToolsAccessibleDropmenu-WidgetTitle" name="MooToolsAccessibleDropmenu-WidgetTitle" value="<?php echo $options['title'];?>" />
        <input type="hidden" id="MooToolsAccessibleDropmenu-SubmitTitle" name="MooToolsAccessibleDropmenu-SubmitTitle" value="1" />
    </p>
    <p>
        <label for="MooToolsAccessibleDropmenu-WidgetCategories">Translation for "Categories": </label>
        <input type="text" id="MooToolsAccessibleDropmenu-WidgetCategories" name="MooToolsAccessibleDropmenu-WidgetCategories" value="<?php echo $options['categories'];?>" />
        <input type="hidden" id="MooToolsAccessibleDropmenu-SubmitCategories" name="MooToolsAccessibleDropmenu-SubmitCategories" value="1" />
    </p>
    <p>
        <label for="MooToolsAccessibleDropmenu-WidgetMeta">Translation for "Meta": </label>
        <input type="text" id="MooToolsAccessibleDropmenu-WidgetMeta" name="MooToolsAccessibleDropmenu-WidgetMeta" value="<?php echo $options['meta'];?>" />
        <input type="hidden" id="MooToolsAccessibleDropmenu-SubmitMeta" name="MooToolsAccessibleDropmenu-SubmitMeta" value="1" />
    </p>
    <p>
        <label for="MooToolsAccessibleDropmenu-WidgetRecentPosts">Translation for "Recent Posts": </label>
        <input type="text" id="MooToolsAccessibleDropmenu-WidgetRecentPosts" name="MooToolsAccessibleDropmenu-WidgetRecentPosts" value="<?php echo $options['recentPosts'];?>" />
        <input type="hidden" id="MooToolsAccessibleDropmenu-SubmitRecentPosts" name="MooToolsAccessibleDropmenu-SubmitRecentPosts" value="1" />
    </p>
    <p>
        <label for="MooToolsAccessibleDropmenu-WidgetRecentComments">Translation for "Recent Comments": </label>
        <input type="text" id="MooToolsAccessibleDropmenu-WidgetRecentComments" name="MooToolsAccessibleDropmenu-WidgetRecentComments" value="<?php echo $options['recentComments'];?>" />
        <input type="hidden" id="MooToolsAccessibleDropmenu-SubmitRecentComments" name="MooToolsAccessibleDropmenu-SubmitRecentComments" value="1" />
    </p>
    
    <?php
}

?>

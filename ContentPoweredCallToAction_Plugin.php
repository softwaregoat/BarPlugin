<?php


include_once('ContentPoweredCallToAction_LifeCycle.php');

class ContentPoweredCallToAction_Plugin extends ContentPoweredCallToAction_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
//            'WidthResponsive' => array(__('Set Website Width', 'my-awesome-plugin'), 'display'),
            'LicenseCCT' => array(__('Enter License Code', 'my-awesome-plugin'), 'license'),
            'WidthResponsive100' => array(__('Website 100% width / responsive', 'my-awesome-plugin'), 'display', 'false', 'true'),
            'MaxWidth' => array(__('Website Max width', 'my-awesome-plugin'), 'display'),
            'MobileViewport' => array(__('Mobile Viewport', 'my-awesome-plugin'), 'display'),
            'TopPadding' => array(__('Top Padding', 'my-awesome-plugin'), 'display'),
            'Scroll' => array(__('Appear on Scroll', 'my-awesome-plugin'), 'display'),
//            'CellWidth' => array(__('Cell Width', 'my-awesome-plugin'), 'display'),
            'ButtonWidth' => array(__('Button Width', 'my-awesome-plugin'), 'display'),
            'ImageWidth' => array(__('Image Width', 'my-awesome-plugin'), 'display'),
            'TextWidth' => array(__('Text Width', 'my-awesome-plugin'), 'display'),
            'BarBackgroundColor' => array(__('Bar Background Color', 'my-awesome-plugin'), 'style'),
            'BarShadowColor' => array(__('Bar Shadow Color', 'my-awesome-plugin'), 'style'),
            'BarShadowOpacity' => array(__('Bar Shadow Opacity', 'my-awesome-plugin'), 'style'),
            'ButtonBackgroundColor' => array(__('Button Background Color', 'my-awesome-plugin'), 'style'),
            'ButtonTextColor' => array(__('Button Text Color', 'my-awesome-plugin'), 'style'),
            'TextColor' => array(__('Text Color', 'my-awesome-plugin'), 'style'),
            'TextSizeDesktop' => array(__('Text Size (Desktop)', 'my-awesome-plugin'), 'style'),
            'TextSizeMobile' => array(__('Text Size (Mobile)', 'my-awesome-plugin'), 'style'),
            'ButtonTextSizeDesktop' => array(__('Button Text Size (Desktop)', 'my-awesome-plugin'), 'style'),
            'ButtonTextSizeMobile' => array(__('Button Text Size (Mobile)', 'my-awesome-plugin'), 'style'),
            'TextFontFamily' => array(__('Text Font Family', 'my-awesome-plugin'), 'style'),
            'UTM' => array(__('Append UTM to Button URLs', 'my-awesome-plugin'), 'other'),
            'CustomCss' => array(__('Custom CSS (Advanced)', 'my-awesome-plugin'), 'other')
//            'AmAwesome' => array(__('I like this awesome plugin', 'my-awesome-plugin'), 'false', 'true'),
//            'CanDoSomething' => array(__('Which user role can do something', 'my-awesome-plugin'),
//                                        'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone')
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'Content Powered Call to Action';
    }

    protected function getMainPluginFileName() {
        return 'content-powered-call-to-action.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37
        add_action('wp_head', array(&$this,'load_bar_on_header'));

        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );

        // Adding scripts & styles to all pages
        // Examples:
        wp_enqueue_script('jquery');
        wp_enqueue_style('my-style', plugins_url('/css/style.css', __FILE__));
        wp_enqueue_script('my-script', plugins_url('/js/script.js', __FILE__));
        wp_localize_script( 'my-script', 'mine', array( 'ajaxurl' => admin_url('admin-ajax.php' )) );

        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41
        add_action('wp_ajax_upload_image', array(&$this, 'upload_image'));
        add_action('wp_ajax_get_last_metadata', array(&$this, 'get_last_metadata'));

    }
    public function load_bar_on_header()
    {
        global $post;
        $onthispost = get_post_meta($post->ID, 'onthispost', true);
        if ($onthispost != 'on')return;
        $text = get_post_meta($post->ID, 'headline', true);
        $btnText = get_post_meta($post->ID, 'buttonText', true);
        $url = get_post_meta($post->ID, 'destinationURL', true);
        $image = get_post_meta($post->ID, 'image', true);

        $BarBackgroundColor = $this->getOption('BarBackgroundColor');
        $BarShadowColor = $this->getOption('BarShadowColor');
        list($r, $g, $b) = sscanf($BarShadowColor, "#%02x%02x%02x");
        $BarShadowOpacity = $this->getOption('BarShadowOpacity');
        $rgba = "rgba($r, $g, $b, $BarShadowOpacity)";

        $ButtonWidth = $this->getOption('ButtonWidth');
        $ButtonBackgroundColor = $this->getOption('ButtonBackgroundColor');
        $ButtonTextColor = $this->getOption('ButtonTextColor');
        $ButtonTextSizeDesktop = $this->getOption('ButtonTextSizeDesktop');
        $ButtonTextSizeMobile = $this->getOption('ButtonTextSizeMobile');

        $TextColor = $this->getOption('TextColor');
        $TextWidth = $this->getOption('TextWidth');
        $TextSizeDesktop = $this->getOption('TextSizeDesktop');
        $TextSizeMobile = $this->getOption('TextSizeMobile');
        $TextFontFamily = $this->getOption('TextFontFamily');

        $ImageWidth = $this->getOption('ImageWidth');
        $Scroll = $this->getOption('Scroll');
        $TopPadding = $this->getOption('TopPadding');
        $MobileViewport = $this->getOption('MobileViewport');
        if ($MobileViewport == '')$MobileViewport = '768px';
        $MaxWidth = $this->getOption('MaxWidth');
        $WidthResponsive100 = $this->getOption('WidthResponsive100');

        $UTM = $this->getOption('UTM');
        $CustomCss = $this->getOption('CustomCss');
?>
        <style>
            <?php echo $CustomCss?>
            #cpt-bar{
            <?php if($TopPadding != ''){?> margin-top:<?php echo $TopPadding;}?>;
                z-index: 1000;
                box-shadow: 0 1px 3px 2px <?php echo $rgba?>;
                background-color: <?php echo $BarBackgroundColor?>;
                border-color: rgb(255, 255, 255);
                color: rgb(255, 255, 255);
                font-family:sans-serif;
                height: 50px;
                display: flex;
                width: 100%;
                font-size: 17px;
                font-weight: 400;
                top: 0;
                position: fixed;
                overflow: hidden;
                justify-content: center;
            }
            #inner-bar{
            <?php if($MaxWidth != '' && $WidthResponsive100 == 'false'){?>
                max-width: <?php echo $MaxWidth;}?>;
                width: 100%;
                display: flex;
            }
            #hb-first{
                line-height: 50px;
                margin: auto;
            }
            #hb-middle{
                line-height: 50px;
                margin: auto;
                max-height: 50px;
                overflow: hidden;
            }
            #hb-last{
                margin: auto;
            }
            #image{
                height: 50px;
                <?php if($ImageWidth != ''){?>
                width: <?php echo $ImageWidth;}?>;
            }
            #text{
                text-align: center;
                color: <?php echo $TextColor?>;
            <?php if($TextWidth != ''){?>
                width: <?php echo $TextWidth;}?>;
            <?php if($TextFontFamily != ''){?>
                font-family: <?php echo $TextFontFamily;}?>;
            <?php if($TextSizeDesktop != ''){?>
                font-size: <?php echo $TextSizeDesktop;}?>;
            }
            #btnText{
                text-align: center;
                background-color: <?php echo $ButtonBackgroundColor?>;
                color: <?php echo $ButtonTextColor?>;
            <?php if($ButtonWidth != ''){?>
                width: <?php echo $ButtonWidth;}?>;
            <?php if($ButtonTextSizeDesktop != ''){?>
                font-size: <?php echo $ButtonTextSizeDesktop;}?>;
            }
            @media only screen and (max-width: <?php echo $MobileViewport?>) {
                #text{
                <?php if($TextSizeMobile != ''){?>
                    font-size: <?php echo $TextSizeMobile;}?>;
                    white-space: nowrap;
                    overflow:hidden;
                    text-overflow: ellipsis;
                }
                #btnText{
                <?php if($ButtonTextSizeMobile != ''){?>
                    font-size: <?php echo $ButtonTextSizeMobile;}?>;
                }
            }

        </style>

        <div id="cpt-bar" style="display: none;">
            <input type="hidden" id="scroll" value="<?php echo $Scroll?>">
            <div id="inner-bar">
                <div id="hb-first" style="">
                    <img id="image" src="<?php echo $image?>" alt="Bar Logo">
                </div>

                <div id="hb-middle" style="">
                    <div id="text"><?php echo $text?></div>
                </div>

                <div id="hb-last" style="">
                    <a style="text-decoration: none;" href="<?php echo $url.$UTM?>">
                        <div id="btnText"><?php echo $btnText?></div>
                    </a>
                </div>
            </div>
        </div>
<?php
    }

    public function upload_image(){
        // Upload file
        if($_FILES['file']['name'] != ''){
            $uploadedfile = $_FILES['file'];
            $upload_overrides = array( 'test_form' => false );
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
            $imageurl = "";
            if ( $movefile && ! isset( $movefile['error'] ) ) {
                $imageurl = $movefile['url'];
                echo $imageurl;
            } else {
                echo 'FAILED';
            }
        }
        die();
    }
    public function get_last_metadata(){
        $args = array( 'numberposts' => '1', 'post_type' => 'post' );
        $recent_posts = wp_get_recent_posts( $args );
        $result = [];
        foreach( $recent_posts as $post ){
            $headline = get_post_meta($post['ID'], 'headline', true);
            $buttonText = get_post_meta($post['ID'], 'buttonText', true);
            $destinationURL = get_post_meta($post['ID'], 'destinationURL', true);
            $onthispost = get_post_meta($post['ID'], 'onthispost', true);
            $copylastpost = get_post_meta($post['ID'], 'copylastpost', true);
            $image = get_post_meta($post['ID'], 'image', true);
            $result['headline'] = $headline;
            $result['buttonText'] = $buttonText;
            $result['destinationURL'] = $destinationURL;
            $result['onthispost'] = $onthispost;
            $result['copylastpost'] = $copylastpost;
            $result['image'] = $image;
        }
        $jsonstring = json_encode($result);
        echo $jsonstring;
        die();
    }
    public function print_data($value)
    {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    }
    public function add_meta_box( )
    {
        add_meta_box(
            'bar-meta-box',
            __('Content Powered - Call to Action', 'textdomain'),
            array($this, 'render_meta_box_content'),
            'post',
            'advanced',
            'high'
        );
    }
    public function save( $post_id ) {

        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( ! isset( $_POST['myplugin_inner_custom_box_nonce'] ) ) {
            return $post_id;
        }

        $nonce = $_POST['myplugin_inner_custom_box_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) ) {
            return $post_id;
        }

        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }

        /* OK, it's safe for us to save the data now. */

        // Sanitize the user input.
        $headline = sanitize_text_field( $_POST['headline'] );
        $buttonText = sanitize_text_field( $_POST['buttonText'] );
        $destinationURL = sanitize_text_field( $_POST['destinationURL'] );
        $onthispost = sanitize_text_field( $_POST['onthispost'] );
        $copylastpost = sanitize_text_field( $_POST['copylastpost'] );
        $image = sanitize_text_field( $_POST['image'] );

        // Update the meta field.
        update_post_meta( $post_id, 'headline', $headline );
        update_post_meta( $post_id, 'buttonText', $buttonText );
        update_post_meta( $post_id, 'destinationURL', $destinationURL );
        update_post_meta( $post_id, 'onthispost', $onthispost );
        update_post_meta( $post_id, 'copylastpost', $copylastpost );
        update_post_meta( $post_id, 'image', $image );
    }
    public function render_meta_box_content( $post )
    {
// Add an nonce field so we can check for it later.
        wp_nonce_field('myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce');

// Use get_post_meta to retrieve an existing value from the database.
        $headline = get_post_meta($post->ID, 'headline', true);
        $buttonText = get_post_meta($post->ID, 'buttonText', true);
        $destinationURL = get_post_meta($post->ID, 'destinationURL', true);
        $onthispost = get_post_meta($post->ID, 'onthispost', true);
        $copylastpost = get_post_meta($post->ID, 'copylastpost', true);
        $image = get_post_meta($post->ID, 'image', true);

// Display the form, using the current value.
        ?>
        <p>
            <input type="checkbox" id="onthispost" name="onthispost" <?php if($onthispost == 'on')echo 'checked'?>>Enable on this post
            <input type="checkbox" id="copylastpost" name="copylastpost" <?php if($copylastpost == 'on')echo 'checked'?>>Copy settings from last post
        </p>
        <hr>
        <p>
            <label for="image">
                <?php _e('Image', 'textdomain'); ?>
            </label>
        </p>
        <p>
            <input type="text" name="image" id="image"
                   value="<?php echo esc_attr($image) ?>" size="50" style="width: 50%;"/>
            <input type="file" id="icon-upload" >
            <input type="button" class="button-primary" value="Upload" id="image_upload"/>
        </p>
        <p>
            <label for="headline">
                <?php _e('Headline', 'textdomain'); ?>
            </label>
        </p>
        <input style="width: 100%;" type="text" id="headline" name="headline" value="<?php echo esc_attr($headline); ?>"
               size="25"/>
        <p>
            <label for="buttonText">
                <?php _e('Button Text', 'textdomain'); ?>
            </label>
        </p>
        <input style="width: 100%;" type="text" id="buttonText" name="buttonText" value="<?php echo esc_attr($buttonText); ?>" size="25"/>
        <p>
            <label for="destinationURL">
                <?php _e('Destination URL', 'textdomain'); ?>
            </label>
        </p>
        <input style="width: 100%;" type="text" id="destinationURL" name="destinationURL" value="<?php echo esc_attr($destinationURL); ?>" size="25"/>
        <?php
    }
}



<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14/06/2017
 * Time: 21:14
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class brozzmeDbPSettings
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_pages'), 110);
        add_action('admin_init', array($this, 'settings_fields'));

        /* since 1.2 */

        add_action( 'wp_ajax_get_tables', array( $this, 'get_tables' ) );
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

    }

    public function add_admin_pages()
    {
        add_submenu_page(BFSL_PLUGINS_DEV_GROUPE_ID,
            __('DB PREFIX', B7EDBP_TEXT_DOMAIN),
            __('DB PREFIX', B7EDBP_TEXT_DOMAIN),
            'manage_options',
            B7EDBP_SETTINGS_SLUG,
            array($this, 'settings_page')
        );

        add_submenu_page('tools.php',
            __('DB PREFIX', B7EDBP_TEXT_DOMAIN),
            __('DB PREFIX', B7EDBP_TEXT_DOMAIN),
            'manage_options',
            B7EDBP_SETTINGS_SLUG,
            array($this, 'settings_page')
        );

    }

    public function settings_page(){
        global $wpdb;

        if(!isset($_GET['tab']) || $_GET['tab']==='general_settings') {
            if(!isset($_GET['tab']) || ( isset($_GET['tab']) && $_GET['tab'] !== 'mysql_dump') ){

                if((isset($_POST['dbprefix_hidden'])) && $_POST['dbprefix_hidden'] == 'Y' &&
                    (isset($_POST['Submit']) && trim($_POST['Submit'])==__('Change DB Prefix', B7EDBP_TEXT_DOMAIN ))) {

                    $old_dbprefix = $_POST['dbprefix_old_dbprefix'];
                    update_option('dbprefix_old_dbprefix', $old_dbprefix);

                    $dbprefix_new = $_POST['dbprefix_new'];
                    update_option('dbprefix_new', $dbprefix_new);

                    $wpdb =& $GLOBALS['wpdb'];

                    $new_prefix = preg_replace("/[^0-9a-zA-Z_]/", "", $dbprefix_new);

                    $bprefix_Message = '';

                    if(isset($_POST['dbprefix_new']) && $_POST['dbprefix_new'] ==='' || strlen($_POST['dbprefix_new']) < 2 )
                    {
                        $bprefix_Message .= __('Please provide a proper table prefix.', B7EDBP_TEXT_DOMAIN);
                    }
                    elseif ($new_prefix == $old_dbprefix) {
                        $bprefix_Message .= __('No change! Please provide a new table prefix.', B7EDBP_TEXT_DOMAIN);
                    }
                    elseif (strlen($new_prefix) < strlen($dbprefix_new)){
                        $bprefix_Message .= __('You have used some characters disallowed for the table prefix. please use another prefix', B7EDBP_TEXT_DOMAIN) .' <b>'. $dbprefix_new .'</b>';
                    }
                    else
                    {
                        $tables = brozzme_db_prefix_core::dbprefix_getTablesToAlter();
                        if (empty($tables))
                        {
                            $bprefix_Message .= brozzme_db_prefix_core::dbprefix_eInfo(__('There are no tables to rename!', B7EDBP_TEXT_DOMAIN));
                        }
                        else
                        {
                            $result = brozzme_db_prefix_core::dbprefix_renameTables($tables, $old_dbprefix, $dbprefix_new);
                            // check for errors
                            if (!empty($result))
                            {
                                $bprefix_Message .= __('All tables have been successfully updated with prefix', B7EDBP_TEXT_DOMAIN) .' <b>'.$dbprefix_new.'</b> !<br/>';
                                // try to rename the fields
                                $bprefix_Message .= brozzme_db_prefix_core::dbprefix_renameDbFields($old_dbprefix, $dbprefix_new);

                                $dbprefix_wpConfigFile = $this->get_wp_config_file();

                                if (brozzme_db_prefix_core::dbprefix_updateWpConfigTablePrefix($dbprefix_wpConfigFile, $old_dbprefix, $dbprefix_new))
                                {
                                    $bprefix_Message .= __('The wp-config file has been successfully updated with prefix', B7EDBP_TEXT_DOMAIN) . ' <b>'.$dbprefix_new.'</b>!';
                                }
                                else {
                                    $bprefix_Message .= __('The wp-config file could not be updated! You have to manually update the table_prefix variable to the one you have specified:', B7EDBP_TEXT_DOMAIN). ' '.$dbprefix_new;
                                }
                            }// End if tables successfully renamed
                            else {
                                $bprefix_Message .= __('An error has occurred and the tables could not be updated!', B7EDBP_TEXT_DOMAIN);
                            }
                            $_POST['dbprefix_hidden'] = 'n';

                            $new_updated_prefix = get_option('dbprefix_new');
                        }

                        ?>
                    <?php }
                } else {
                    $bprefix_Message = '';
                }
                if(isset($new_updated_prefix) && $new_updated_prefix != null && $wpdb->prefix != $new_updated_prefix){
                    $input_prefix = $new_updated_prefix;
                }
                else{
                    $input_prefix = $wpdb->prefix;
                }
            }
        }



        ?>
        <div class="wrap">
            <h2>Brozzme DB PREFIX</h2>

            <?php

            $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general_settings';
            ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=<?php echo B7EDBP_SETTINGS_SLUG;?>&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Change DB Prefix', B7EDBP_TEXT_DOMAIN );?></a>
                <a href="?page=<?php echo B7EDBP_SETTINGS_SLUG;?>&tab=mysql_dump" class="nav-tab <?php echo $active_tab == 'mysql_dump' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Database dump', B7EDBP_TEXT_DOMAIN );?></a>
                <a href="?page=<?php echo B7EDBP_SETTINGS_SLUG;?>&tab=help_options" class="nav-tab <?php echo $active_tab == 'help_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Help', B7EDBP_TEXT_DOMAIN );?></a>
                <a href="admin.php?page=brozzme-plugins" class="nav-tab <?php echo $active_tab == 'brozzme' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Brozzme', B7EDBP_TEXT_DOMAIN );?></a>

            </h2>

            <?php if($active_tab === 'help_options'){

                $this->_help_tab();

            }
            elseif($active_tab === 'brozzme'){

            }
            elseif($active_tab === 'mysql_dump'){

                include_once(dirname(__FILE__) . '/brozzme_db_dump_core.php');
                $db_dump = new brozzme_db_dump_core();
                ?>
                <div id="cdtp" class="brozzme-info postbox" style="line-height:1.2rem;">
                <h3 class="hndle" style="cursor: default;"><?php _e('Export database ', B7EDBP_TEXT_DOMAIN); ?></h3>

                </hr>
                <p><?php _e('Dump with all tables by default, select options if you need. ', B7EDBP_TEXT_DOMAIN); ?></p>

                <form method="get">
                    <input type="hidden" name="page" value="<?php echo B7EDBP_SETTINGS_SLUG;?>">
                    <input type="hidden" name="tab" value="mysql_dump">
                    <input type="hidden" name="export" value="true">
                    <?php echo $db_dump->_select_table_list();?>
                    <div class="form-space" ><input type="radio" name="dump-settings[]" value="include-tables" checked="checked"> <?php _e('Include', B7EDBP_TEXT_DOMAIN); ?></div> </br>
                    <div class="form-space"><input type="radio" name="dump-settings[]" value="exclude-tables"> <?php _e('Exlude', B7EDBP_TEXT_DOMAIN); ?></div></br>
                    <input type="checkbox" name="dump-settings[]" value="no-data"> <?php _e('Table structure only', B7EDBP_TEXT_DOMAIN); ?></br></br>
                    <input type="submit" class="button button-primary" value="<?php _e('Export database tables', B7EDBP_TEXT_DOMAIN);?>">
                </form>
                </div>
            <script>
                jQuery(function ($) {

                    $('#b7eet_accepted_tables').select2({
                        ajax: {
                            url: ajaxurl, // AJAX URL is predefined in WordPress admin
                            dataType: 'json',
                            delay: 250, // delay in ms while typing when to perform a AJAX search
                            data: function (params) {
                                return {
                                    q: params.term, // search query
                                    action: 'get_tables' // AJAX action for admin-ajax.php
                                };
                            },
                            processResults: function (data) {
                                var options = [];
                                if (data) {
                                    // data is the array of arrays, and each of them contains ID and the Label of the option
                                    $.each(data, function (index, text) { // do not forget that "index" is just auto incremented value
                                        options.push({id: text['id'], text: text['id'] + ' (' + text['label'] + ')'});
                                    });
                                }
                                return {
                                    results: options
                                };
                            },
                            cache: true
                        },
                        language: 'fr',
                        placeholder: {
                            id: '-1', // the value of the option
                            text: '<?php _e('Type your search', B7EDBP_TEXT_DOMAIN);?>'
                        },
                        minimumInputLength: 3 // the minimum of symbols to input before perform a search

                    });

                });
            </script>
                <?php
                if(isset($_GET['export']) && $_GET['export'] == 'true'){
                    ?><div class="notice notice-success"><p><?php
                        if(isset($_GET['b7eet_accepted_tables']) && $_GET['b7eet_accepted_tables'] !== ''){

                            $tables = $_GET['b7eet_accepted_tables'];

                        }else{
                            $tables = '*';
                        }

                        foreach ($_GET['dump-settings'] as $setting){

                            $settings[] = $setting;
                        }

                        $dump = $db_dump->backup_table($tables, $settings);
                        if($dump !== false){
                            if(file_exists($dump)){
                                echo __('Done, the file name is:', B7EDBP_TEXT_DOMAIN) . ' <a href="' . $this->_abs_path_to_url($dump) . '">' . basename($dump) . '</a>';
                            }else{
                                echo __('There was a serious problem during this process, check your error logs', B7EDBP_TEXT_DOMAIN);
                            }
                        }

                        ?></p></div>
                    </div>
                        <?php
                }
            }
            else{
               ?>
                <form id="dbprefix_form" name="dbprefix_form" method="post" action="" >
                <input type="hidden" name="dbprefix_hidden" value="Y">
                <div id="cdtp" class="brozzme-info postbox">
                    <h3 class="hndle" style="cursor: default;"><span><?php _e('Database Prefix Settings', B7EDBP_TEXT_DOMAIN);?></span></h3>
                <div class="inside">
                    <div class="cdp">
                        <h4 style="margin-top: 15px;"><?php _e('Before execute this plugin:', B7EDBP_TEXT_DOMAIN); ?></h4>
                        <ul class="cdp-data" style="margin-top: 20px;">
                            <li><?php _e('Make sure your <code>wp-config.php</code> file is <strong>writable</strong>.', B7EDBP_TEXT_DOMAIN);?></li>
                            <li><?php _e('And check the database has <strong>ALTER</strong> rights.', B7EDBP_TEXT_DOMAIN);?></li>
                        </ul>
                    </div><!-- cdp div -->
                    <div class="success">
                        <?php echo $bprefix_Message;?>
                    </div><!-- success div -->
                    <?php if(isset($_POST['dbprefix_hidden']) && $_POST['dbprefix_hidden'] === 'Y') { ?>
                        <div class="updated">
                            <p><strong><?php _e('Options saved.' ); ?></strong></p>
                        </div><!-- updated div -->
                    <?php } ?>
                    <div class="cdp-container">
                        <label for="dbprefix_old_dbprefix" class="label01">
                                <span class="ttl02"><?php _e('Existing Prefix: ', B7EDBP_TEXT_DOMAIN );?>
                                    <span class="required">*</span></span>
                            <input type="text" disabled name="dbprefix_old_dbprefix_show" id="dbprefix_old_dbprefix_show" value="<?php echo $input_prefix; ?>" size="20" required>
                            <input type="hidden" name="dbprefix_old_dbprefix" id="dbprefix_old_dbprefix" value="<?php echo $input_prefix; ?>" size="20" >
                            <?php _e(' ex: wp_', B7EDBP_TEXT_DOMAIN ); ?><span class="error"></span>
                        </label><br/>
                        <label for="dbprefix_new" class="label01"> <span class="ttl02"><?php _e('New Prefix: ', B7EDBP_TEXT_DOMAIN ); ?>
                                <span class="required">*</span></span>
                            <input type="text" name="dbprefix_new" value="<?php echo esc_html($this->keygen());?>" size="20" id="dbprefix_new" required>
                            <?php _e(' ex: uniquekey_', B7EDBP_TEXT_DOMAIN ); ?>
                        </label>
                        <p class="margin-top:10px"><?php _e('<b>Allowed characters:</b> all latin alphanumeric as well as the <strong>_</strong> (underscore).', B7EDBP_TEXT_DOMAIN);?></p>
                        <p class="submit">
                            <input type="submit" name="Submit" class="button button-primary" value="<?php _e('Change DB Prefix', B7EDBP_TEXT_DOMAIN ); ?>" />
                        </p></div><!-- container div -->
                </div><!-- inside div -->
                </div><!-- postbox div -->
                </form>

                </div>
                <?php
            }?>

            <?php
        
    }

    public function keygen($length=4)
    {

        $length = rand(3,6);
        $key = '';
        list($usec, $sec) = explode(' ', microtime());
        mt_srand((int) $sec + ((int) $usec * 100000));

        $inputs = array_merge(range('z','a'),range(0,9));

        for($i=0; $i<$length; $i++)
        {
            $key .= $inputs[mt_rand(0,35)];
        }

        if(strlen($key)< 3){
            $add = array_rand(range('z','a'));
            $key = $key.$add;
        }
        return $key .'_';
    }

    public function get_wp_config_file() {
        if (file_exists(ABSPATH . 'wp-config.php')) {
            return ABSPATH . 'wp-config.php';
        }

        return dirname(ABSPATH) . '/wp-config.php';
    }

    public function settings_fields(){


    }

    public function _help_tab(){
        ?>

        <h2><?php _e('FAQ & HELP', B7EDBP_TEXT_DOMAIN);?></h2>

        <p><a class="doc_toggle">DB Prefix</a> - <a class="doc_toggle">DB Dump</a></p>
        <div id="db-prefix">
            <p><b><?php _e('Why do I need to change the WordPress database prefix ?', B7EDBP_TEXT_DOMAIN);?></b></p>
            <blockquote><?php _e('WordPress Database is like the heart for your WordPress site, as the database runs for every single information store, you need to protect it against hackers and spammers that could run automated code for SQL injections.
Many people forget to change the database prefix in the install wizard. This makes it easier for hackers to plan a mass attack by targeting the default prefix wp_. 
To avoid them, you can protect your database by changing the database prefix which is really easy with Brozzme DB Prefix. It takes a few seconds to change the prefix.', B7EDBP_TEXT_DOMAIN);?></blockquote>
            <p><b><?php _e('What do I need to verify before changes ?', B7EDBP_TEXT_DOMAIN);?></b></p>
            <h3><?php _e('MAKE SURE YOU HAVE A DATABASE BACKUP BEFORE USING THIS TOOL.', B7EDBP_TEXT_DOMAIN);?></h3>
            <blockquote><?php _e('You just need to verify: ', B7EDBP_TEXT_DOMAIN);?>
                <ul><li>- <?php _e('wp-config.php is writable on your server.', B7EDBP_TEXT_DOMAIN);?></li>
                    <li>- <?php _e('that mySQL ALTER rights are enable.', B7EDBP_TEXT_DOMAIN);?></li>
                </ul>
            </blockquote>
            <p><b><?php _e('What can I do if the process fails ?', B7EDBP_TEXT_DOMAIN);?></b></p>
            <blockquote><?php _e('Depending on where the fail occurs: ', B7EDBP_TEXT_DOMAIN);?>
                <ul><li><?php _e('Compare prefix in the wp-config.php and in phpmyAdmin, depending on the the situation, ', B7EDBP_TEXT_DOMAIN);?></li>
                    <li>- <?php _e('change manually $table_prefix value in wp-config.php.', B7EDBP_TEXT_DOMAIN);?></li>
                    <li>- <?php _e('suppress all tables and import the backup in phpmyAdmin.', B7EDBP_TEXT_DOMAIN);?></li>
                    <li><b><?php _e('Verify all the pre-requisite point in the previous question before processing once again.', B7EDBP_TEXT_DOMAIN);?></b></li>
                </ul></blockquote>
            <p><b><?php _e('Why can I not do it manually?', B7EDBP_TEXT_DOMAIN);?></b></p>
            <blockquote><?php _e('Of course you can, but there\'s many occurences to modify to make it works. Not only the tables name need to be modify.', B7EDBP_TEXT_DOMAIN);?>
                <ul><li><?php _e('Here is the exhaustive list of what to change, ', B7EDBP_TEXT_DOMAIN);?></li>
                    <li>- <?php _e('Tables names,', B7EDBP_TEXT_DOMAIN);?></li>
                    <li>- <?php _e('table options: {old_prefix}user_roles option name,', B7EDBP_TEXT_DOMAIN);?></li>
                    <li><?php _e('table usermeta, for each registered user, {old_prefix}capabilities and {old_prefix}user_level, option names', B7EDBP_TEXT_DOMAIN);?></li>
                    <li><?php _e('if exists you\'ll need to also modify {old_prefix}dashboard_quick_press_last_post_id option name', B7EDBP_TEXT_DOMAIN);?></li>
                    <li><?php _e('', B7EDBP_TEXT_DOMAIN);?></li>
                </ul></blockquote>
            <p><b><?php _e('I can\'t delete, edit anymore using phpmyAdmin with MAMP...', B7EDBP_TEXT_DOMAIN);?></b></p>
            <blockquote>
                <ul><li><?php _e('Only use lower-case characters to solve this.', B7EDBP_TEXT_DOMAIN);?></li></ul>
            </blockquote>
        </div>

        <div id="db-dump" style="display:none;">
            <p><b><?php _e('How to make and verify your mysql dump ?', B7EDBP_TEXT_DOMAIN);?></b></p>

            <blockquote>
                <?php _e('Make a new dump of the database:', B7EDBP_TEXT_DOMAIN);?>
                <ul>
                    <li><?php _e('If you want a copy of all tables, with structure and datas, just press Export database tables.', B7EDBP_TEXT_DOMAIN);?></li>
                    <li><b><?php _e('Use options if needed', B7EDBP_TEXT_DOMAIN);?></b>
                        <ul style="padding-left: 15px;list-style: circle;">
                            <li><?php _e('Include or exclude tables: add table with select by typing hint to find table name and add it to your list, choose by checking radio box (include or exclude).', B7EDBP_TEXT_DOMAIN); ?></li>
                            <li><?php _e('Check Table strucuture only, if you do not want to dump datas.', B7EDBP_TEXT_DOMAIN); ?></li>
                        </ul>
                    </li>
                </ul>
                <b><?php _e('Add more options', B7EDBP_TEXT_DOMAIN);?></b>
                <ul>
                    <li><?php _e('Use the filter b7e_dump_settings to modifiy settings', B7EDBP_TEXT_DOMAIN);?>
                        <ul style="padding-left: 15px;list-style: circle;">
                            <li><?php _e('How set the settings array with the b7e_dump_settings filter', B7EDBP_TEXT_DOMAIN);?></li>
                            <li><code>add_filter('b7e_dump_settings', 'b7e_dump_settings');<br><br>

                                function b7e_dump_settings($settings){<br>

                                &nbsp;&nbsp;&nbsp;&nbsp;$settings['compress'] = 'GZIP'; // BZIP2,  default: NONE<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;$settings['reset-auto-increment'] = true;<br>

                                    &nbsp;&nbsp;&nbsp;&nbsp;return $settings;<br>
                                }</code></li>
                            <li><?php _e('Available settings and defaults ', B7EDBP_TEXT_DOMAIN);?> <a href="https://github.com/ifsnop/mysqldump-php#dump-settings" target="_blank">ifsnop/mysqldump-php</a></li>
                        </ul>
                    </li>

                </ul>

                <b><?php _e('Verify dump of the database:', B7EDBP_TEXT_DOMAIN);?></b>
                <ul>
                    <li><?php _e('Go to phpmyadmin', B7EDBP_TEXT_DOMAIN);?></li>
                    <li><?php _e('If you can test the dump on other database, use the Import tab and choose the exported file.', B7EDBP_TEXT_DOMAIN);?></</li>
                    <li>
                        <ul style="padding-left: 15px;list-style: circle;">
                            <li><?php _e('Go to', B7EDBP_TEXT_DOMAIN); ?> <a href="<?php echo admin_url() .'/admin.php?page=brozzme-db-prefix&tab=general_settings'?>"><?php _e('Change DB Prefix', B7EDBP_TEXT_DOMAIN); ?></a> <?php _e('tab and modify your prefix.', B7EDBP_TEXT_DOMAIN); ?></li>
                            <li><?php _e('Go to phpmyadmin, use the Import tab and choose the exported file.', B7EDBP_TEXT_DOMAIN); ?></li>
                        </ul>
                    </li>
                </ul>

            </blockquote>
        </div>


        <script>
            jQuery(document).ready(function($){
                $(document).ready(function(){
                    $(".doc_toggle").click(function(){
                        $("#db-prefix").toggle();
                        $("#db-dump").toggle();
                    });
                });
            });
        </script>
        <?php
    }


    /* since 1.2 */
    // mysql dump adds

    // à laisser ici
    public function admin_enqueue_scripts(){
        wp_enqueue_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );

        wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'select2-fr', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/fr.js', array( 'select2' ) );

    }

    public function _abs_path_to_url( $path = '' ) {
        $url = str_replace(
            wp_normalize_path( untrailingslashit( ABSPATH ) ),
            site_url(),
            wp_normalize_path( $path )
        );
        return esc_url_raw( $url );
    }

    public function _tables_list(){
        global $wpdb;

        $dbname = $wpdb->dbname;

        $result = $wpdb->get_results( 'SHOW TABLE STATUS', ARRAY_A );
        $rows = count( $result );
        // Get Database Size

        $dbsize = 0;

        if ( $wpdb->num_rows > 0 ) {
            foreach ( $result as $row ) {

                $tables_list[] = array('id'=>$row['Name'], 'label'=> round((($row[ "Data_length" ] + $row[ "Index_length" ]) / 1024 / 1024), 2));
            }
        }

        return $tables_list;

    }

    public function get_tables(){
        $tables = $this->_tables_list();

        foreach ($tables as $table){

            if(strpos($table['id'],  sanitize_text_field($_GET['q'])) !== false){
                $results[] = array('id'=> $table['id'], 'label'=> $table['label']);
            }
        }
        echo json_encode( $results );

        die;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: benoti
 * Date: 12/10/2018
 * Time: 12:55
 * Since 1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class brozzme_db_dump_core{

    /**
     * brozzme_db_dump_core constructor.
     */
    public function __construct()
    {
        $this->filepath = $this->_path_exists(WP_CONTENT_DIR .'/uploads/brozzme-export-tables');
    }

    /**
     * @param $path
     * @return mixed
     */
    public function _path_exists($path){
        if(is_dir($path)){
            return $path;
        }
        else{
            mkdir($path);
            return $path;
        }
    }

    /**
     * @param $tables
     * @param array $settings
     * @return bool|string
     */
    public function backup_table($tables, $settings=array()){

        include_once(dirname(__FILE__) . '/class-mysqldump.php');

        $dumpSettingsDefault = array();

        if(is_array($tables) && $tables != ''  ){
            $inc_exc_tables = (!in_array('include-tables', $settings))? 'exclude-tables' : 'include-tables';

            if(in_array('include-tables', array_values($settings)) || in_array('exclude-tables', array_values($settings))){
                $dumpSettingsDefault[$inc_exc_tables] = $tables;
            }
        }
        if(in_array('no-data', array_values($settings))){
            $dumpSettingsDefault['no-data'] = true;
        }

        $final_settings = apply_filters('b7e_dump_settings', $dumpSettingsDefault);
        $ext = 'sql';
        if(in_array('compress', array_keys($final_settings))){
            $ext = strtolower($final_settings['compress']);
        }
        $file_name = $this->filepath .'/db-export_' . get_bloginfo('site_name') . '_' . time() .'_dump.'.$ext;

        $dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host='.DB_HOST.';dbname='.DB_NAME.'', DB_USER, DB_PASSWORD, $final_settings);
        $mysql_dump = $dump->start($file_name);

        if($mysql_dump === true){
            return $file_name;
        }else{
            return false;
        }
    }

    /**
     * @param string $path
     * @return string
     */
    public function _abs_path_to_url($path = '' ) {
        $url = str_replace(
            wp_normalize_path( untrailingslashit( ABSPATH ) ),
            site_url(),
            wp_normalize_path( $path )
        );
        return esc_url_raw( $url );
    }

    /**
     * @return array
     */
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

    /**
     *
     */
    public function _select_table_list(){
        $tables = $this->_tables_list();
        ?>
        <div class="b7eet-add-table" style="padding:15px 0px;">
            <label for="b7eet_accepted_tables" style="padding-bottom:4px;"><?php _e( 'List of tables to exclude or include:', 'txt-domain' ); ?></label><br/><br/>

            <select id="b7eet_accepted_tables" name="b7eet_accepted_tables[]" multiple="multiple" style="width:60%">
                <?php
                if ( $tables ) {
                    foreach ( $tables as $key=>$table ) {
                        $title             = $table['id'];
                        $title             = ( mb_strlen( $title ) > 50 ) ? mb_substr( $title, 0, 49 ) . '...' : $title;
                        $title             = $title . ' (' . $table['label'] . ' mb)';

                        $selected          = '';

                        echo '<option value="' . esc_attr( $table['id'] ) . '" ' . $selected . '>' . $title . '</option>';
                    }
                } ?>
            </select>
        </br></br>
            <a class="see_all_table"><?php _e('See all tables in database', B7EDBP_TEXT_DOMAIN);?></a>
            <div id="all_table" style="display: none;">
                <?php
                echo __('Click to select ', B7EDBP_TEXT_DOMAIN) . '</br>';
                foreach ($tables as $table) {
                    ?>
                    <span class="add_table" data-id="<?php echo $table['id'];?>" data-label="<?php echo $table['label'] ;?>"><?php echo $table['id'] . ' <small>' . $table['label'] . ' mb</small>';?></span>
                    <?php
                }?>
            </div>
        </div>

        <script>
            jQuery(document).ready(function($){
                $(document).ready(function(){
                    $(".see_all_table").click(function(){
                        $("#all_table").toggle();
                    });
                });

                $(document).ready(function(){
                    $(".add_table").click(function(e){
                        if( $('#b7eet_accepted_tables').select2("val") != null ){
                            var selected = $('#b7eet_accepted_tables').select2("val");
                            var count = selected.length;
                            selected[count] = $(this).data().id;
                        }else{
                            var selected = new Array($(this).data().id);
                        }

                        $('#b7eet_accepted_tables')
                            .val(selected) //select option of select2
                            .trigger("change");
                    });
                });
            });
        </script>
        <?php
    }

    /**
     *
     */
    public function _get_tables(){
        $tables = $this->_tables_list();

        foreach ($tables as $table){

            if(strpos($table['id'],  sanitize_text_field($_GET['q'])) !== false){
                $results[] = array('id'=> $table['id'], 'label'=> $table['label']);
            }
        }
        echo json_encode( $results );
        die;
    }

    /**
     * @param $tables
     * @return string
     */
    public function _tables_output($tables){

        $result = '';
        if(!is_array($tables)){
            return $result;
        }else{
            foreach ($tables as $table){
                $result .= $table .'_';
            }
            return $result;
        }
    }

}
<?php
/*
* Plugin Name: Custom Form
* Text Domain: vmform
*/

register_activation_hook( __FILE__, 'vmform_activate' );
 
function vmform_activate() { 
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE `{$wpdb->base_prefix}vm_form` (
      id INT AUTO_INCREMENT,
      title varchar(255) NOT NULL, 
      form longtext NOT NULL,
      colCount INT NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";
    $sql2 = "CREATE TABLE `{$wpdb->base_prefix}vm_submissions` (
      id INT AUTO_INCREMENT,
      form_id varchar(255) NOT NULL, 
      submission longtext NOT NULL,
      submissionDate DATE NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($sql2);
}

register_uninstall_hook( __FILE__, 'vmform_uninstall' );
 
function true_uninstall() {

    delete_option( 'true_plugin_settings' );

}

function wpdocs_selectively_enqueue_admin_script( $hook ) {
    if ( 'vm-form_page_new_vm_form' != $hook ) {
        return;
    }
    wp_enqueue_script( 'vm-admin', plugin_dir_url( __FILE__ ) . 'assets/js/admin-script.js', array('jquery'), null, true );
    wp_enqueue_style( 'vm-upload', 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/wtf-forms.css' );
    wp_enqueue_style( 'vm-admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css' );
}
add_action( 'admin_enqueue_scripts', 'wpdocs_selectively_enqueue_admin_script' );
function vm_front_script(){
    wp_enqueue_style( 'vm-admin', plugin_dir_url( __FILE__ ) . 'assets/css/front-style.css' );
    wp_enqueue_style( 'vm-upload', 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/wtf-forms.css' );   
}
add_action('wp_enqueue_scripts', 'vm_front_script');

add_filter( 'set_screen_option_'.'lisense_table_per_page', function( $status, $option, $value ){
    return (int) $value;
}, 10, 3 );

add_filter( 'set-screen-option', function( $status, $option, $value ){
    return ( $option == 'lisense_table_per_page' ) ? (int) $value : $status;
}, 10, 3 );

add_action( 'admin_menu', 'vmform_add_submenus' );

function vmform_add_submenus() {
    $hook = add_menu_page( 'All Forms', 'VM Form', 'manage_options', 'vm_forms', 'vm_forms_page_callback', 'dashicons-images-alt2' );
    add_submenu_page( 'vm_forms', 'All Forms', 'VM Form', 'manage_options', 'vm_forms', 'vm_forms_page_callback' );
    add_submenu_page( 'vm_forms', 'New Form', 'New Form', 'manage_options', 'new_vm_form', 'new_vm_form_callback' );
    $submission = add_submenu_page( 'vm_forms', 'Submissions', 'Submissions', 'manage_options', 'vm_forms_submissions', 'vm_forms_submissions_callback' );
    add_action( "load-$hook", 'vm_forms_page_callback_load' );
    add_action( "load-$submission", 'vm_forms_submissions_callback_load' );
}
function vm_forms_submissions_callback_load(){
    require_once __DIR__ . '/includes/class-vm_form_list_table.php';
    $GLOBALS['Form_submissions_List_Table'] = new Form_submissions_List_Table();
}
function vm_forms_page_callback_load(){
    require_once __DIR__ . '/includes/class-vm_form_list_table.php';
    $GLOBALS['Example_List_Table'] = new Example_List_Table();
}
 
function vm_forms_page_callback() {
    echo '<div class="wrap">
    <h1 class="wp-heading-inline">' . get_admin_page_title() . '</h1>
    <a href="admin.php?page=new_vm_form" class="page-title-action">Add New</a>
    </div>
    <hr class="wp-header-end">';
    ?>
    <div class="wrap">      
        <form action="" method="POST">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $GLOBALS['Example_List_Table']->display() ?>
        </form>        
    </div>
    <?php  
}

function vm_forms_submissions_callback(){
    echo '<div class="wrap">
    <h1 class="wp-heading-inline">' . get_admin_page_title() . '</h1>
    </div>
    <hr class="wp-header-end">';
    if(!isset($_GET['id'])){
        ?>
        <div class="wrap">      
            <form action="" method="POST">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                <?php $GLOBALS['Form_submissions_List_Table']->display() ?>
            </form>        
        </div>
        <?php  
    }else{
        ?>
        <div class="wrap">      
            <form action="" method="POST">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                <?php do_action('Submission_List_Table') ;?>
            </form>        
        </div>
        <?php 
    }
}

function new_vm_form_callback(){
    echo '<div class="wrap">
    <h1 class="wp-heading-inline">' . get_admin_page_title() . '</h1>
    <button class="page-title-action save-form">Publish</button>
    </div>
    <hr class="wp-header-end">';
    include_once('admin/form.php');
}
function vm_forms_page_callback_submissions() {
    echo '<div class="wrap"><h2>' . get_admin_page_title() . '</h2></div>';
}
function vm_simple_form() {
    global $wpdb;
    $res = [];
    $index = '';
    $title = $_POST['form_title'];
    $form_id = isset($_POST['form-id']) ? $_POST['form-id'] : '';
    $col_count= $_POST['col-count'];
    unset($_POST['form-id']);
    unset($_POST['form_title']);
    unset($_POST['action']);
    unset($_POST['col-count']);
    $res = sort_data();
    $table_name = $wpdb->prefix . 'vm_form';
    if(!empty($form_id)){
        $wpdb->update($table_name, ['form' => serialize($res), 'title' => strip_tags($title), 'colCount' => $col_count],['id' => $form_id]);
    }else{  
        $wpdb->insert($table_name, array('form' => serialize($res), 'title' => $title, 'colCount' => $col_count));
    }
    wp_safe_redirect('admin.php?page=vm_forms');
}

add_action( 'admin_post_nopriv_vm_simple_form', 'vm_simple_form' );
add_action( 'admin_post_vm_simple_form', 'vm_simple_form' );

function delete_form(){
    global $wpdb;
    $id = $_GET['id'];
    $wpdb->delete( $wpdb->prefix . 'vm_form', [ 'id' => $id ] );
    wp_safe_redirect('admin.php?page=vm_forms');
}

add_action( 'admin_post_nopriv_delete_form', 'delete_form' );
add_action( 'admin_post_delete_form', 'delete_form' );

function sort_data($action = ''){
    foreach($_POST as $key => $value) {
        if($action == ''){
                if (strpos($key, '_text_') == true && $id = substr($key, strrpos($key, '_' )+1)) {
                   $index = 'text-'.$id;           
                   $res[$index]['id'] = $id;    
                }elseif(strpos($key, '_email_') == true && $id = substr($key, strrpos($key, '_' )+1)){
                    $index = 'email-'.$id;
                    $res[$index]['id'] = $id;   
                }elseif(strpos($key, '_checkbox_') == true){
                    $d = explode('_',$key);
                    $id = $d[count($d)-2];
                    $index = 'checkbox-'.$id;
                    $res[$index]['id'] = $id;   
                    $res[$index]['checkboxCount'] = $d[count($d)-1]; 
                }elseif(strpos($key, 'radio') == true && $id = substr($key, strrpos($key, '_' )+1)){
                    $d = explode('_',$key);
                    $id = $d[count($d)-2];
                    $index = 'radio-'.$id;
                    $res[$index]['id'] = $id;
                    $res[$index]['radioCount'] = $d[count($d)-1];   
                }elseif(strpos($key, '_date_') == true && $id = substr($key, strrpos($key, '_' )+1)){
                    $index = 'date-'.$id;
                    $res[$index]['id'] = $id;   
                }elseif(strpos($key, '_file_') == true && $id = substr($key, strrpos($key, '_' )+1)){
                    $index = 'file-'.$id;
                    $res[$index]['id'] = $id;
                }elseif(strpos($key, 'Full_') == true && $id = substr($key, strrpos($key, '_' )+1)){
                    $index = 'emptyFull-'.$id;
                    $res[$index]['id'] = $id;  
                    continue;  
                }elseif(strpos($key, 'Grid_') == true && $id = substr($key, strrpos($key, '_' )+1)){
                    $index = 'emptyGrid-'.$id;
                    $res[$index]['id'] = $id;    
                    continue;
                }
                $res[$index][$key] = strip_tags($value);
            }else{                
                $res[$key] = $value;
            }
    }
    return $res;
}

function vm_shortcode( $atts ) {
    global $wpdb;
    $id = $atts['id'];
    $vm_form = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}vm_form WHERE id =".$id);
    $data = unserialize($vm_form->form);?>
    <input type="hidden" name="form-id" value="<?php echo $vm_form->id ?>">
    <h3><?php echo $vm_form->title?></h3>    
    <div id="form-container">
        <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST" enctype="multipart/form-data" >
            <ul>
    <?php
    $col = '';
    foreach ($data as $key => $value) {             
        $data_id = $value['id'];
        if(isset($value["field-required".$data_id]) && $value["field-required".$data_id] == 'on'){
            $required = "required='required'";
        }else{
            $required = '';
        }
        
        $cols = explode('-',$data_id);      
        if(isset($cols[1]) && $cols[1] == 1){
            echo '<li>';
        }elseif(isset($cols[1]) && $cols[1] == 2){
            echo '';  
        }elseif(!isset($cols[1])){
            echo '<li>';
        }       
            switch ($key){
                case 'text-'.$data_id:
                    echo '
                    <div class="field-option-wrapper '. $value["field-class".$data_id] .'">
                    <label>'. $value["field-label".$data_id] .'</label>
                        <input type="text" '.$required.' placeholder="Text" value="'. $value["field-value".$data_id] .'" name="field_text_'.$data_id.'">
                    </div>';
                break;
                case 'email-'.$data_id:
                    echo '
                    <div class="field-option-wrapper '. $value["field-class".$data_id] .'">
                    <label>'. $value["field-label".$data_id] .'</label>
                        <input type="email" '.$required.' placeholder="Email" value="'. $value["field-value".$data_id] .'" name="field_email_'.$data_id.'">
                    </div>';
                break;
                case 'checkbox-'.$data_id:
              echo '
                    <div class="field-option-wrapper '. $value["field-class".$data_id] .'">
                    <label>'. $value["field-label".$data_id] .'</label>
                        <div class="wrapper_field_checkbox" data-id="'.$data_id.'">';
                        for($i=1; $i <= (int)$value["checkboxCount"]; $i++){
                          echo '<label class="container">'. $value["field_checkbox_value_".$data_id."_".$i] .'
                                    <input type="checkbox" value="'. $value["field_checkbox_value_".$data_id."_".$i] .'" name="field_checkbox_'.$data_id.'[]">
                                </label>';
                        }

                  echo '</div>
                    </div>';
                break;
                case 'radio-'.$data_id:
              echo '
                    <div class="field-option-wrapper '. $value["field-class".$data_id] .'">
                        <label>'. $value["field-label".$data_id] .'</label>
                        <div class="wrapper_field_radio" data-id="'.$data_id.'">';
                        for($i=1; $i <= (int)$value["radioCount"]; $i++){
                          echo '<label class="container">'. $value["field_radio_value_".$data_id."_".$i] .'
                                    <input '.$required.' value="'. $value["field_radio_value_".$data_id."_".$i] .'" type="radio" name="field_radio_'.$data_id.'[]">
                                </label>';
                        }

                  echo '</div>
                    </div>';
                break;
                case 'date-'.$data_id:
                    echo '
                    <div class="field-option-wrapper '. $value["field-class".$data_id] .'">
                        <label>'. $value["field-label".$data_id] .'</label>
                        <input type="date" '.$required.' placeholder="Calendar" name="field_date_'.$data_id.'">
                    </div>';
                break;
                case 'file-'.$data_id:
                    echo '
                          <div class="field-option-wrapper '. $value["field-class".$data_id] .'">
                              <label class="file">
                                    '. $value["field-label".$data_id] .'
                                  <input '.$required.' name="field_file_'.$data_id.'" type="file" i>
                                  <input type="hidden" name="field_file_'.$data_id.'" >
                                  <span class="file-custom"></span>
                              </label>
                          </div>';
                break;
                default:
            }
        if(isset($cols[1]) && $cols[1] == 1){
            echo '';
        }elseif(isset($cols[1]) && $cols[1] == 2){
            echo '</li>';    
        }elseif(!isset($cols[1])){
            echo '</li>';
        }       
    } ?>
        </ul>
        <input type="hidden" name="form-id" value="<?php echo $vm_form->id ?>">
        <input type="hidden" name="action" value="vm_form_submission">
        <input type="hidden" name="red_back" value="<?php echo $_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']?> ">
        <input type="submit" name="" value="Submit">
    </form>
    </div>    
    <?php
}
add_shortcode( 'vm_form', 'vm_shortcode' );

function vm_form_submission(){
    global $wpdb;
    unset($_POST['action']);
    $form_id = $_POST['form-id'];
    unset($_POST['form-id']);
    $red_back = $_POST['red_back'];
    unset($_POST['red_back']);
    $res = sort_data('submission');
    $table_name = $wpdb->prefix . 'vm_submissions';
    $target_dir = wp_upload_dir()['basedir'].'/vm_form';
    wp_mkdir_p( $target_dir );
    foreach ($res as $key => $value) {
        if(strpos($key, '_file_') == true){
            $target_file = $target_dir . '/' . basename($_FILES[$key]['name']);
            move_uploaded_file($_FILES[$key]['tmp_name'], $target_file);
            $res[$key] = urlencode(wp_upload_dir()['baseurl'].'/vm_form/'. basename($_FILES[$key]['name']));
        }        
    }
    $date = new DateTime();
    $wpdb->insert($table_name, array('submission' => serialize($res), 'form_id' => $form_id, 'submissionDate' => $date->format('Y-m-d')));
    wp_safe_redirect($red_back);
}
add_action( 'admin_post_nopriv_vm_form_submission', 'vm_form_submission' );
add_action( 'admin_post_vm_form_submission', 'vm_form_submission' );

function Submission_List_Table(){
        global $wpdb;
        $form_id = $_GET['id'];
        $args = array('id', 'submissionDate', 'submission');
        $sql_select = implode(', ', $args);
        $orderby = isset($_GET['orderb']) ? 'GROUP BY '.$_GET['orderb'] : 'GROUP BY id';
        $order = isset($_GET['order']) ? $_GET['order'] : 'desc';
        $sort = new stdClass;
        $vm_submission = $wpdb->get_results( "SELECT ". $sql_select ." FROM {$wpdb->prefix}vm_submissions WHERE form_id = ".$form_id." ".$orderby." ".$order);
        $sort = [];
        foreach ($vm_submission as $key => $value) {
            $sort[$key]['id'] = $value->id;
            $sort[$key]['date'] = $value->submissionDate;
            foreach (unserialize($value->submission) as $subkey => $subval) { 
                $sort[$key][$subkey] = $subval;
            }
        }
        $tabs = array_keys($sort[0]); ?>
        <style type="text/css">
            td, th {
                  border: 1px solid #dddddd;
                  text-align: left;
                  padding: 8px;
                }
        </style>
        <div>
<!--             <form method="GET" action="#">
                <select name="orderby">
                    <option>Order By</option>
                    <option value="submission-id">ID</option>
                </select>
                <select name="order">
                    <option>Order</option>
                    <option value="desc">desc</option>
                    <option value="acs">asc</option>
                </select>
                <input type="submit" name="">
            </form> -->
        </div>
        <table>
            <tr>
                <?php
                    foreach ($tabs as $key => $value) {
                        if($value == 'id'){
                           echo '<th><a href="'.$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'].'&order='.($order == "desc" ? "asc" : "desc") .'&orderb=id">'.$value.'</a></th>';
                        }elseif($value == 'date'){
                            echo '<th><a href="'.$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'].'&order='.($order == "desc" ? "asc" : "desc") .'&orderb=submissionDate">'.$value.'</a></th>';
                        }else{
                            echo '<th>'.$value.'</th>';
                        }
                    }
                ?>
            </tr>
            <?php
                foreach ($sort as $key => $value) { ?>
                    <tr> 
                        <?php
                            foreach ($value as $keys => $values) {
                                echo '<td>';
                                if(is_array($values)){
                                    foreach ($values as $k => $v) {
                                        echo $v.' | ';
                                    }
                                }else{
                                    echo urldecode($values);
                                }
                                echo '</td>';
                            }
                        ?>
                    </tr>
                <?php } ?>
        </table>
<?php }

add_action('Submission_List_Table', 'Submission_List_Table');

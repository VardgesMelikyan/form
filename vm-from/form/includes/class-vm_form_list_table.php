<?php

// расширять класс нужно после или во время admin_init
// класс удобнее поместить в отдельный файл.

class Example_List_Table extends WP_List_Table {

	function __construct(){
		parent::__construct(array(
			'singular' => 'log',
			'plural'   => 'logs',
			'ajax'     => false,
		));

		$this->bulk_action_handler();
		add_screen_option( 'per_page', array(
			'label'   => 'Показывать на странице',
			'default' => 20,
			'option'  => 'logs_per_page',
		) );

		$this->prepare_items();

		add_action( 'wp_print_scripts', [ __CLASS__, '_list_table_css' ] );
	}
	function prepare_items(){
		global $wpdb;
		$per_page = get_user_meta( get_current_user_id(), get_current_screen()->get_option( 'per_page', 'option' ), true ) ?: 20;

		$this->set_pagination_args( array(
			'total_items' => 10,
			'per_page'    => $per_page,
		) );
		$cur_page = (int) $this->get_pagenum();
		$args = array('id', 'title');
		$sql_select = implode(', ', $args);
		$orderby = isset($_GET['orderby']) ? 'GROUP BY '.$_GET['orderby'] : 'GROUP BY id';
		$order = isset($_GET['order']) ? $_GET['order'] : 'desc';
		$vm_form= $wpdb->get_results( "SELECT ". $sql_select ." FROM {$wpdb->prefix}vm_form ".$orderby." ".$order);			
		$this->items = $vm_form;

	}

	function get_columns(){
		return array(
			'cb'            => '<input type="checkbox" />',
			'id'            => 'ID',
			'title' => 'Title',
			'shortcode'   => 'Shortcode',
		);
	}
	function get_sortable_columns(){
		return array(
			'title' => array( 'title', true ),
			'id' => array( 'id', true ),
		);
	}

	protected function get_bulk_actions() {
		return array(
			'delete' => 'Delete',
		);
	}
	function extra_tablenav( $which ){
		
	}
	static function _list_table_css(){
		?>
		<style>
			table.logs .column-id{ width:4em; }
			table.logs .column-vm_shortcode{ width:8em; }
			table.logs .column-form_title{ width:15%; }
		</style>
		<?php
	}

	function column_default( $item, $colname ){

		if( $colname === 'title' ){
			$actions = array();
			$actions['edit'] = sprintf( '<a href="%s">%s</a>', 'admin.php?page=new_vm_form&id='.$item->id, __('Edit','vm-forms') );
			$actions['delete'] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url('admin-post.php') ).'?action=delete_form&id='.$item->id, __('Delete','vm-forms') );
			return esc_html( $item->title ) . $this->row_actions( $actions ) ;
		}
		else {
			return isset($item->$colname) ? $item->$colname : print_r($item, 1);
		}

	}
	function column_cb( $item ){		
		echo '<input type="checkbox" name="licids[]" id="cb-select-'. $item->id .'" value="'. $item->id .'" />';
	}
	function column_shortcode( $item ){		
		echo '<input type="text" readonly id="shortcode'. $item->id .'" value="[vm_form id=\''. $item->id .'\']" />';
	}
	private function bulk_action_handler(){
		global $wpdb;
		if( empty($_POST['licids']) || empty($_POST['_wpnonce']) ) return;

		if ( ! $action = $this->current_action() ) return;

		if( ! wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) )
			wp_die('nonce error');

		if($action == 'delete'){
			$ids = implode( ',', array_map( 'absint', $_POST['licids'] ) );
			$wpdb->query("DELETE FROM ".$wpdb->prefix."vm_form WHERE id IN (".$ids.")");
		}
	}
}

class Form_submissions_List_Table extends WP_List_Table {

	function __construct(){
		parent::__construct(array(
			'singular' => 'log',
			'plural'   => 'logs',
			'ajax'     => false,
		));

		$this->bulk_action_handler();
		add_screen_option( 'per_page', array(
			'label'   => 'Показывать на странице',
			'default' => 20,
			'option'  => 'logs_per_page',
		) );

		$this->prepare_items();

		add_action( 'wp_print_scripts', [ __CLASS__, '_list_table_css' ] );
	}
	function prepare_items(){
		global $wpdb;
		$per_page = get_user_meta( get_current_user_id(), get_current_screen()->get_option( 'per_page', 'option' ), true ) ?: 20;

		$this->set_pagination_args( array(
			'total_items' => 10,
			'per_page'    => $per_page,
		) );
		$cur_page = (int) $this->get_pagenum();
		$args = array('id', 'title');
		$sql_select = implode(', ', $args);
		$orderby = isset($_GET['orderby']) ? 'GROUP BY '.$_GET['orderby'] : 'GROUP BY id';
		$order = isset($_GET['order']) ? $_GET['order'] : 'desc';
		$vm_form = $wpdb->get_results( "SELECT ". $sql_select ." FROM {$wpdb->prefix}vm_form ".$orderby." ".$order);	
		$this->items = $vm_form;

	}

	function get_columns(){
		return array(
			'cb'            => '<input type="checkbox" />',
			'id'            => 'ID',
			'title' => 'Title',
		);
	}
	function get_sortable_columns(){
		return array(
			'title' => array( 'title', true ),
			'id' => array( 'id', true ),
		);
	}

	protected function get_bulk_actions() {
		return array(
			'delete' => 'Delete',
		);
	}
	function extra_tablenav( $which ){
		
	}
	static function _list_table_css(){
		?>
		<style>
			table.logs .column-id{ width:4em; }
			table.logs .column-vm_shortcode{ width:8em; }
			table.logs .column-form_title{ width:15%; }
		</style>
		<?php
	}

	function column_default( $item, $colname ){

		if( $colname === 'title' ){
			$actions = array();
			$actions['view'] = sprintf( '<a href="%s">%s</a>', 'admin.php?page=vm_forms_submissions&id='.$item->id, __('View','vm-forms') );
			$actions['delete'] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url('admin-post.php') ).'?action=delete_form&id='.$item->id, __('Delete','vm-forms') );
			return esc_html( $item->title ) . $this->row_actions( $actions ) ;
		}
		else {
			return isset($item->$colname) ? $item->$colname : print_r($item, 1);
		}

	}
	function column_cb( $item ){		
		echo '<input type="checkbox" name="licids[]" id="cb-select-'. $item->id .'" value="'. $item->id .'" />';
	}
	private function bulk_action_handler(){
		global $wpdb;
		if( empty($_POST['licids']) || empty($_POST['_wpnonce']) ) return;

		if ( ! $action = $this->current_action() ) return;

		if( ! wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) )
			wp_die('nonce error');

		if($action == 'delete'){
			$ids = implode( ',', array_map( 'absint', $_POST['licids'] ) );
			$wpdb->query("DELETE FROM ".$wpdb->prefix."vm_submissions WHERE id IN (".$ids.")");
		}
	}
}
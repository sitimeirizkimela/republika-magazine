<?php 
	function  menu_admin(){
		$ci =& get_instance();
		$ci->db->order_by('urutan','ASC');
		$result=$ci->db->get('mst_menu_admin',array('admin_level'=>$ci->session->userdata('adminLevel')));
		return $result->result_array();
	}
<?php

class Magazine_Model extends CI_Model {
	var $imagePath = 'files/media/magazine/';
    
    function __construct() {
        parent::__construct();
    }
    function show_magazine()
    {
      $query = $this->db->get('magazine_detail');
      return $query->result_array();
    }

    function get_all($limit, $uri,$where) {
         $this->db->order_by("id", "desc"); 
        $result = $this->db->get('magazine', $limit, $uri);
        if($where != '') $this->db->where($where);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return array();
        }
    }

   
    function get_list($where = '') {
        $result = $this->db->get('magazine');
        if($where != '') $this->db->where($where);
        if ($result->num_rows() > 0) {
                 var_dump($result->result_array());
        
            return $result->result_array();
        } else {
            return array();
        }
    }

    function get_one($id) {
        $this->db->where('id', $id);
        $result = $this->db->get('magazine');
        if ($result->num_rows() == 1) {
            return $result->row_array();
        } else {
            return array();
        }
    }
  function get_detail($id = '') {
        $this->db->where('id_magazine',$id);
        $this->db->order_by("id", "asc"); 
        $result = $this->db->get('magazine_detail');
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return array();
        }
    }

    function insert() {
           $data = array(
            'title' => $this->input->post('title_ed', TRUE),
            'content' => $this->input->post('content_ed', TRUE),
            'dates' => date('Y-m-d H:i:s', time()),
           );
           if ($_FILES['image_ed']['error'] != 4)  $data['image'] = $this->uploads('image_ed'); 
        $this->db->insert('magazine', $data);
        $id = $this->db->insert_id();
        $this->chart_details($id);
    }

    function update($id) {

        $data = array(
           'title' => $this->input->post('title_ed', TRUE),
           'content' => $this->input->post('content_ed', TRUE),
        );
			  if ($_FILES['image_ed']['error'] != 4)  $data['image'] = $this->uploads('image_ed'); 
      
        $this->db->where('id', $id);
        $this->db->update('magazine', $data);
        $this->chart_details($id);

    }

    function chart_details($id) {
         $this->db->where('id_magazine', $id);
         $this->db->delete('magazine_detail');
         $imagess = $_FILES['image'];
         //print_r($_FILES['image']);
         $type = "image";
        foreach($_POST['title'] as $i => $val) {
          if($i == 99) continue;
           $data = array(
            'title' => $_POST['title'][$i],
            'content' => $_POST['content'][$i],
            'author' => $_POST['author'][$i],
          //  'image' => $_POST['old_image'][$i],
            'id_magazine' => $id,
           );
                     //print_r($imagess);
                     $_FILES = array();
                     $_FILES['userfile']['name']        = $imagess['name'][$i];
                     $_FILES['userfile']['type']        = $imagess['type'][$i];
                     $_FILES['userfile']['tmp_name']    = $imagess['tmp_name'][$i]; 
                     $_FILES['userfile']['error']       = $imagess['error'][$i];
                     $_FILES['userfile']['size']        = $imagess['size'][$i];  
                   
                 if ($_FILES['userfile']['error'] != 4) {
                    $local_path = UPLOAD_PATH ."magazine" . '/' . $id . '/' ;
                    if (!file_exists($local_path)) {
                             if(!mkdir($local_path, 0777))  return;
                    } 
                    $config['upload_path'] = $local_path;
                    $config['allowed_types'] = 'jpg|png|jpeg|gif';
                    $config['max_size'] = '200000'; 
                    $config['encrypt_name']  = TRUE;
                     
                     $this->load->library('upload', $config);
                     // $this->upload->initialize($config);   
             
                        
                    if ($this->upload->do_upload('userfile')) {
                        $image = $this->upload->data();
                        $data['image'] = $image['file_name'];
                        //$this->load->library('image_lib', $config);
                
                  } else {
                    echo($this->upload->display_errors());
                  }

                }
                $this->db->insert('magazine_detail', $data);
        }
        
    }


    function delete($id) {
        foreach ($id as $sip) {
            $this->db->where('id', $sip);
            $this->db->delete('magazine');
        }
    }

    function uploads($type) {
                $location = UPLOAD_PATH . "magazine/cover/" ;
                $config['upload_path'] = $location;
                $config['allowed_types'] = 'jpg|png|jpeg|gif|pdf';
                $config['max_size'] = '200000';
                $config['file_name'] = substr(md5($_FILES[$type]['name']),15);
               
                  $this->load->library('upload', $config);
               
                if ($this->upload->do_upload($type)) {
                    $file = $this->upload->data();
                    return $file['file_name'];
                } else {
                    echo $location;
                    var_dump($this->upload->display_errors());
                    die();
                }
          
    }

   
}
?>

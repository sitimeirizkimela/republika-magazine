<?php

class Magazine extends CI_Controller {

    var $template = 'admin-magazine/template';
    var $imagePath = 'files/media/magazine/';
    var $imageThumbs = 'files/media/magazine/thumbs/';
    var $status = array(
        '' => 'status',
	0 => 'draft',
        1 => 'published',
    );

    function __construct() {

        parent::__construct();

      //  error_reporting(E_ALL);
        $this->load->database();
	    $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form'));

        $this->load->model('magazine_model');
        $this->output->enable_profiler(TRUE);
     }

    function index() {
        $this->load->library(array('pagination'));
        $where="";
       

        $config = array(
            'base_url' => site_url() . '/admin/magazine/index/',
            'total_rows' => $this->db->count_all('magazine', $where),
            'per_page' => 5,
            'uri_segment'=>4
        );
        $this->pagination->initialize($config);
        $data['content'] = 'admin/magazine_all';
        $data['pagination'] = $this->pagination->create_links();
        $data['magazines'] = $this->magazine_model->get_all($config['per_page'], $this->uri->segment(4), $where);
        $data['option_status'] = $this->status;
        
        $this->load->view('admin-magazine/magazine_all', $data);
    }

    function add() {
        $config = array(
         array(
                'field' => 'title',
                'label' => 'title',
                'rules' => 'required'
            ),
        ); 
           // var_dump($_POST);
           // die("AAAA");
               
       $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == TRUE) {
               //   var_dump($this->input->post());
               $this->magazine_model->insert();
                $this->session->set_flashdata('notif', 'Data berhasil disimpan');
                redirect('admin/magazine');
        }
        $data['option_status'] = $this->status;          
        $data['content'] = 'admin/form_magazine';
        $data['type_form'] = 'post';
        //$data['magazine'] = $this->magazine_model->show_magazine();
        $this->load->view('admin-magazine/form_magazine', $data);
    }

    function form_update($id='') {
        if ($id != '') {
            $data['option_status'] = $this->status;
          
            $data['isi'] = $this->magazine_model->get_one($id);
            $data['magazine'] = $this->magazine_model->get_detail($id);
            
            $data['content'] = 'admin/form_magazine';
            $data['type_form'] = 'update';
            $this->load->view('admin-magazine/form_magazine', $data);
        } else {
            $this->session->set_flashdata('notif', 'no data');
            redirect('admin/magazine');
        }
    }

    function update() {
        $config = array(
         array(
                'field' => 'title',
                'label' => 'title',
                'rules' => 'required'
            ),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() == TRUE) {
            
                $this->magazine_model->update($this->input->post('id'));
                $this->session->set_flashdata('notif', 'Data is updated');
                redirect('admin/magazine');
        } else {
            $this->form_update($this->input->post('id'));
        }
    }

    function delete() {
        if (isset($_POST['id'])) {
            $this->magazine_model->delete($_POST['id']);
            
             $this->session->set_flashdata('notif','data has been deleted');
                redirect('admin/magazine');
        } else {
            $this->session->set_flashdata('notif', 'no data deleted');
            redirect('admin/magazine');
        }
    }

}

?>

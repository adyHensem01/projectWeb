<?php
if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');

class Debug extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        
        /*load Session / Validation */
        $this->load->library('session', 'form_validation');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->database();
        
        /*load Model */
        $this->load->model('coursecode');
    }
    
    public function index() {
        $this->load->library('pagination');
        
        /*url segment */
        if ($this->uri->segment(3)) {
            $page = ($this->uri->segment(3));
        } 
        else {
            $page = 1;
        }
        
        /* Pagination */
        $config = array();
        $config["base_url"] = base_url() . "index.php/course/index/";
        $config["total_rows"] = $this->coursecode->numrows();
        $config["per_page"] = 10;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        if (!$this->session->has_userdata('login')) {
            redirect('' . base_url() . 'index.php');
        }
        
        /*initialize pagination */
        $this->pagination->initialize($config);
        
        /*breadcrumb / tittle element*/
        $header['title'] = "Senarai Kursus";
        $data['frsttitle'] = "Selenggara Kursus";
        $data['sectitle'] = "Senarai Kursus";
        $data['page'] = $page;
        $data['perpage'] = $config['per_page'];
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>', '<li><a href="" class="active">Selenggara Kursus</a></li>', '<li><a href="" class="active">Senarai Kursus</a></li>');
        
        /*data from db */
        $data['coursecode'] = $this->coursecode->get_course($config['per_page'], $page);
        
        $this->load->view('templates/list/listheader', $header);
        
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
        switch ($type) {
            case '1':
                $this->load->view('templates/home/sidebar_admin', $data);
                break;

            case '2':
                $this->load->view('templates/home/sidebar_user', $data);
                break;

            case '3':
                $this->load->view('templates/home/sidebar_fasi', $data);
                break;
        }
        $this->load->view('templates/list/liststart', $data);
        $this->load->view('lists/coursecode', $data);
        $this->load->view('templates/list/listclose');
        $this->load->view('templates/list/listfooter');
    }
    
    public function setCourse() {
        if (!$this->session->has_userdata('login')) {
            redirect('' . base_url() . 'index.php');
        }
        
        /*breadcrumb / tittle element*/
        $header['title'] = "Set Kursus";
        $data['frsttitle'] = "Menu Kursus";
        $data['sectitle'] = "Set Kursus";
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Kursus</a></li>', '<li><a href="" class="active">Set Kursus</a></li>');
        
        $this->load->view('templates/home/headerhome', $header);
        
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
        switch ($type) {
            case '1':
                $this->load->view('templates/home/sidebar_admin', $data);
                break;

            case '2':
                $this->load->view('templates/home/sidebar_user', $data);
                break;

            case '3':
                $this->load->view('templates/home/sidebar_fasi', $data);
                break;
        }
        $this->load->view('templates/form/formstart', $data);
        $this->load->view('forms/setcourse');
        $this->load->view('templates/form/formclose');
        $this->load->view('templates/form/formfooter');
    }
    
    public function courseDay() {
        if (!$this->session->has_userdata('login')) {
            redirect('' . base_url() . 'index.php');
        }
        
        /*breadcrumb / tittle element*/
        $header['title'] = "Senarai Kursus";
        $data['frsttitle'] = "Selenggara Kursus";
        $data['sectitle'] = "Senarai Kursus";
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>', '<li><a href="" class="active">Selenggara Kursus</a></li>', '<li><a href="" class="active">Senarai Kursus</a></li>');
        
        $this->load->view('templates/list/listheader', $header);
        
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
        switch ($type) {
            case '1':
                $this->load->view('templates/home/sidebar_admin', $data);
                break;

            case '2':
                $this->load->view('templates/home/sidebar_user', $data);
                break;

            case '3':
                $this->load->view('templates/home/sidebar_fasi', $data);
                break;
        }
        $this->load->view('templates/list/liststart', $data);
        $this->load->view('lists/courseday');
        $this->load->view('templates/list/listclose');
        $this->load->view('templates/list/listfooter');
    }
    
    public function courseCode() {
        
        /*breadcrumb / tittle element*/
        $header['title'] = "Kod Kursus";
        $data['frsttitle'] = "Penyelengaraan";
        $data['sectitle'] = "Kod Kursus";
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Kursus</a></li>', '<li><a href="#"><i class="fa fa-fa-cogs"></i> Penyelengaraan</a></li>', '<li><a href="" class="active">Kod Kursus</a></li>');
        
        $this->load->view('templates/home/headerhome', $header);
        
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
        switch ($type) {
            case '1':
                $this->load->view('templates/home/sidebar_admin', $data);
                break;

            case '2':
                $this->load->view('templates/home/sidebar_user', $data);
                break;

            case '3':
                $this->load->view('templates/home/sidebar_fasi', $data);
                break;
        }
        
        $this->load->view('templates/form/formstart', $data);
        $this->load->view('forms/coursecode');
        $this->load->view('templates/form/formclose');
        $this->load->view('templates/form/formfooter');
    }
    
  /*  public function delete() {
        
        //retrieve specific segment of url
        $u = $this->uri->segment(3);
        
        $this->coursecode->delete($u);
        
        redirect('course');
    }*/
    
    public function save() {
        
        if ($this->input->post('simpan')) {
            
            $this->form_validation->set_rules('nama', 'NamaKursus', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            
            
            
            if ($this-> form_validation ->run() == FALSE) {
                
                $this-> courseCode();
            }
                
             else {
                    $this-> coursecode -> add();

                    redirect('course/index');
               
            }
  
        }
    }
      
      public function change_status()
        {
            $data ['status'] = $this-> coursecode -> status_course($this-> input ->post('buang'), $this-> input ->post('ID_KURSUS'));

  

            if ($status == 0){

                redirect('course/index');
            }

            else{

            }

        }

}

?>
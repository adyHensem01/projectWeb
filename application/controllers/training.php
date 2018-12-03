<?php
if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');

class Training extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        
        /*load Session / Validation */
        $this->load->library('session', 'form_validation');
        $this->load->helper('form');
        $this->load->helper('url');
        
        /*load Model */
        $this->load->model('trainingModel');
    }
    
    public function index() {
        /* library */
        $this->load->library('pagination');
        // $this->output->enable_profiler(TRUE);

        if (!$this->session->has_userdata('login')) {
            redirect('' . base_url() . 'index.php');
        }
        
        /*search */
        if($this->input->post("keyword")){

        }

        /*url segment */
        if ($this->uri->segment(3)) {
            $page = ($this->uri->segment(3));
        } 
        else {
            $page = 1;
        }

        /* Pagination */
        $config = array();
        
        $config["base_url"] = base_url() . "index.php/training/index/";
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

        /*breadcrumb / tittle element*/
        $header['title'] = "Senarai Kursus";
        $data['frsttitle'] = "Selenggara Kursus";
        $data['sectitle'] = "Senarai Kursus";
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>', '<li><a href="" class="active">Selenggara Kursus</a></li>', '<li><a href="" class="active">Senarai Kursus</a></li>');
        //$data['coursecode']['COURSE'] = $this->trainingModel->get_course();
        $data['senarai_latihan'] = $this->trainingModel->get_training_list( $config["per_page"],$page,$this->input->post('keyword')); 
        $this->load->view('templates/list/listheader', $header);

        if(!empty($data['senarai_latihan'])){
        $config["total_rows"] = $data['senarai_latihan'][0]->totalrec;
        }
        
         /*initialize pagination */
        $this->pagination->initialize($config);
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
       /*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
        $this->load->view('templates/list/liststart', $data);
        $this->load->view('lists/courseday',$data);
        $this->load->view('templates/list/listclose');
        $this->load->view('templates/list/listfooter');
    }
    
    public function setTraining() {
        if (!$this->session->has_userdata('login')) {
            redirect('' . base_url() . 'index.php');
        }
        
         if($this->uri->segment(3)){
            $data['coursebyid'] = $this -> trainingModel -> get_byid($this->uri->segment(3));
            $data['stats'] = "ups";
        }else{
            $data['stats'] = "ins";
        }


        /*breadcrumb / tittle element*/
        $header['title'] = "Set Kursus";
        $data['frsttitle'] = "Menu Kursus";
        $data['sectitle'] = "Set Kursus";
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Kursus</a></li>', '<li><a href="" class="active">Set Kursus</a></li>');
        $data['nama_kursus'] = $this->trainingModel->get_course();
        $data['nama_syarikat'] = $this->trainingModel->get_company();
        
        $this->load->view('templates/home/headerhome', $header);
        
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
      /*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
        $this->load->view('templates/form/formstart', $data);
        $this->load->view('forms/setcourse');
        $this->load->view('templates/form/formclose');
        $this->load->view('templates/form/formfooter');
    }
 public function save() {

        if ($this->input->post('simpan')) {

            $this->form_validation->set_rules('nama_kursus', 'Nama Kursus','callback_combo_check');
            
            $this->form_validation->set_rules('tempat', 'Tempat', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_rules('bilhari', 'Bil Hari', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_rules('kos', 'Kos', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_rules('tempat', 'Tempat', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_rules('tarikhmula', 'Tarikh Mula', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_rules('tarikhakhir', 'Tarikh Akhir', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_rules('masamula', 'Masa Mula', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_rules('masaakhir', 'Masa Akhir', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_rules('penganjur', 'Nama Penganjur', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_rules('penilaian', 'Borang Penilaian', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_rules('jenis', 'Jenis Latihan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_rules('category', 'Kategori', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run() == FALSE) {
                
                $this->setTraining();
            } 
            else {
           
                   if($this->input->post('stats') == "ins"){ 

                           $this->trainingModel->add();
                           $this-> session -> set_flashdata('message', 'Data Telah Berjaya Di Simpan');
                           redirect('training');
                    }

                    elseif($this->input->post('stats') == "ups"){
                           $this->trainingModel->alter($this->uri->segment(3));
                           $this-> session -> set_flashdata('message', 'Data Telah Berjaya Di Simpan');
                           redirect('training');
                }
            }
        }
    }

   public function combo_check($str)
        {
            if ($str == '-PILIH-')
            {
                $this->form_validation->set_message('combo_check', '%s Diperlukan.');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
    }

     public function delete($id) {   
        $this->output->enable_profiler(TRUE);
        $this->load->model('participateModel');
        /*0 active || 1 inactive */
        $this->trainingModel->del(1, $id);
        $this->participateModel->delByTrainId($id);

        //$id = $this->input->post('ID_LATIHAN');
        redirect('training/index');
    }


}

?>
<?php
if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');

class Course extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        
        /*load Session / Validation */
        $this->load->library('session', 'form_validation');
        $this->load->helper('form');
        $this->load->helper('url');
        
        /*load Model */
        $this->load->model('coursecode');
    }
    
    public function index() {

        //$this -> output->enable_profiler(true);

        $this->load->library('pagination');
        
        $sess = $this->session->search;

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
         
        /*breadcrumb / tittle element*/
        $header['title'] = "Senarai Kursus";
        $data['frsttitle'] = "Selenggara Kursus";
        $data['sectitle'] = "Senarai Kursus";
        $data['page'] = $page;
        $data['perpage'] = $config['per_page'];
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>', '<li><a href="" class="active">Selenggara Kursus</a></li>', '<li><a href="" class="active">Senarai Kursus</a></li>');
        
        /*data from db */
        
        /* Searching Function */
        $search = $this -> input -> post('keyword');

        if(isset($search)){

            $this->session->unset_userdata('search');

            //$search = $this -> input -> post('keyword');

            if(!empty($search))
            {
                $item = preg_split("/[-.,]/", $search);
                $data['coursecode'] = $this->coursecode->get_course($config['per_page'], $page,$item);
                $config["total_rows"] = $this->coursecode->numrows($item);
            }elseif (empty($search) ) {
                $data['coursecode'] = $this->coursecode->get_course($config['per_page'], $page);
                $config["total_rows"] = $this->coursecode->numrows();  
            }    
            /*Keep alive Result */
            }elseif(!empty($sess)){
                $data['coursecode'] = $this->coursecode->get_course($config['per_page'], $page,$sess);
                $config["total_rows"] = $this->coursecode->numrows($sess);  
            /*default view*/
            }else{
                $data['coursecode'] = $this->coursecode->get_course($config['per_page'], $page);
                $config["total_rows"] = $this->coursecode->numrows();    
            }


        /*initialize pagination */
        $this->pagination->initialize($config);

        $this->load->view('templates/list/listheader', $header);
        
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
       /*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
        $this->load->view('templates/list/liststart', $data);
        $this->load->view('lists/coursecode', $data);
        $this->load->view('templates/list/listclose');
        $this->load->view('templates/list/listfooter');
    }
    
    public function courseCode() {

        if($this->uri->segment(3)){
            $data['coursebyid'] = $this -> coursecode -> get_byid($this->uri->segment(3));
            $data['stats'] = "ups";
        }else{
            $data['stats'] = "ins";
        }
        
        /*breadcrumb / tittle element*/
        $header['title'] = "Kod Kursus";
        $data['frsttitle'] = "Penyelengaraan";
        $data['sectitle'] = "Kod Kursus";
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Kursus</a></li>', '<li><a href="#"><i class="fa fa-fa-cogs"></i> Penyelengaraan</a></li>', '<li><a href="" class="active">Kod Kursus</a></li>');
        
        $this->load->view('templates/home/headerhome', $header);
        
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
        /*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
        
        $this->load->view('templates/form/formstart', $data);
        $this->load->view('forms/coursecode',$data);
        $this->load->view('templates/form/formclose');
        $this->load->view('templates/form/formfooter');
    }

    
    public function save() {

        if ($this->input->post('simpan')) {

            $this->form_validation->set_rules('jenis', 'Bidang', 'required|callback_bidang_check');
            $this->form_validation->set_rules('nama', 'Nama Kursus', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            //$this->form_validation->set_rules('tahun', 'Tahun', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if ($this->form_validation->run() == FALSE) {
                                $this -> session -> set_flashdata('msg','Sila Pastikan Ruangan Nama Bidang dan Nama Kursus di Isi Sebelum Di Simpan');
                $this->courseCode();
            } 
            else {
                    if($this->input->post('stats') == "ins"){
                            
                            $this->coursecode->add();
                    
                    }elseif($this->input->post('stats') == "ups"){

                            $this->coursecode->alter($this->uri->segment(3));

                            $this-> session -> set_flashdata('message', 'Data Telah Berjaya Di Simpan');

                             redirect('course/');
                    }
                   
                    $this-> session -> set_flashdata('message', 'Data Telah Berjaya Di Simpan');
                    
                    redirect('course/');
            }
        }
    }

    public function bidang_check()
    {
            if ($this->input->post('jenis') === 'pilih')  {
            $this->form_validation->set_message('bidang_check', 'Sila Pilih Bidang.');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
    
    public function delete($id) {
        
        /*0 active || 1 inactive */
        
        $this->coursecode->del(1, $id);
        
        $id = $this->input->post('ID_KURSUS');
        
        $this-> session -> set_flashdata('message', 'Data Telah Dibuang');

        redirect('course/index');

    }
}
?>
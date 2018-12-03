<?php
if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');

class Attendance extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        
        //$this->load->model('pesertaModel');
        $this->load->model('attendanceModel');
    }
    
    public function index() {
        
        if (!$this->session->has_userdata('login')) {
            redirect('' . base_url() . 'index.php');
        }
        
        /*breadcrumb / tittle element*/
        $header['title'] = "Pengesahan Kehadiran";
        $data['frsttitle'] = "Menu Utama";
        $data['sectitle'] = "Pengesahan Kehadiran";
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>', '<li><a href="" class="active">Pengesahan Kehadiran</a></li>');
        
        //call the model function to get the department data
        $data['peserta'] = $this->attendanceModel->get_peserta();
        
        //tambah masa 3/12/2015
        //$this->load->model('pesertaModel');
        //$data['usr'] = $this->pesertaModel->get_user();
        //$this->load->vars($data);
        
        $this->load->view('templates/home/headerhome', $header);
        
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
        /*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
        $this->load->view('templates/list/liststart', $data);
        $this->load->view('lists/attendance', $data);
        $this->load->view('templates/list/listclose');
        $this->load->view('templates/list/listfooter');
    }
    
    public function attendByday($id, $bil) {
        /*load model */
        $this->load->model('adminvaluation');

        /*post */
        $depart = $this->input->post('department');
        $section = substr($this->input->post('section'), 0, 2);
        $units = $this->input->post('units');

        $header['title'] = "Senarai Peserta";
        $data['frsttitle'] = "Menu Utama";
        $data['sectitle'] = "Senarai Perserta";
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>', '<li><a href="" class="active">Pengesahan Kehadiran</a></li>', '<li><a href="" class="active">Senarai Peserta</a></li>');
        
        $data['usr'] = $this->attendanceModel->getUsrByTrainID($id,$depart,$section,$units);
        $data['tab'] = $bil;
        
        /*dropdown jabatan*/
        $data['dropdown'] = $this->adminvaluation->getJabatan();

        //header
        $this->load->view('templates/home/headerhome', $header);
        
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
       /*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
        //$id = $this->input->get('BIL_HARI');
        //$this->pesertaModel->get_tabs($id,TRUE);
        //redirect ('attendance/usrAttends');
        $this->load->view('templates/list/liststart', $data);
        $this->load->view('lists/usrattends', $data);
        $this->load->view('templates/list/listclose');
        $this->load->view('templates/list/listfooter');
        
        /*else {
        echo $id;
        redirect ('attendance/index');
        
        }*/
    }
    
    //public function usrAttends($userr)
    public function addAttends($id,$day,$hour) {
        $data_check = $this->input->post('data');
        //echo "<pre>";print_r($data_check);echo "</pre>";
        if(!empty($id)){
            $this->attendanceModel->updateCourseAttndStat($id);
	        foreach ($data_check as $key => $arr) {
	            $i = 0;
                foreach ($arr as $cntday) {
                    //echo $cntday;
                    if($cntday > 0){
                        $i++;
                    }
                }
                $count_day = $i;
	            $hours = $count_day * $hour;
	            $attend = implode(",", $arr);
	            $this->attendanceModel->alterAttends($key,$attend,$hours,$id);   
	        }
            redirect(base_url().'index.php/attendance/attendByday/'.$id.'/'.$day.'/'.$hour);
	    }else{
            redirect(base_url().'index.php/attendance/attendByday/'.$id.'/'.$day.'/'.$hour);
        }    
    }
}
?>
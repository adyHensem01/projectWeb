<?php
if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');

class Modal extends CI_Controller
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
    }
    
    public function setTraining() {
        $data['nama_kursus'] = $this->trainingModel->get_course();
        $this->load->view('forms/setcourse', $data);
        $this->load->view('templates/form/formclose');
    }
    
    public function save($modal = "") {
        
        if (!empty($modal)) {
            
            $this->form_validation->set_rules('nama_kursus', 'Nama Kursus', 'callback_combo_check');
            
            $this->form_validation->set_rules('tempat', 'Tempat', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_rules('bilhari', 'Bil Hari', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_rules('kos', 'Kos', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_rules('tempat', 'Tempat', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_rules('tarikhmula', 'Tarikh Mula', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_rules('tarikhakhir', 'Tarikh Akhir', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_rules('masamula', 'Masa Mula', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_rules('masaakhir', 'Masa Akhir', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_rules('urusetia', 'Urusetia', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_rules('jenis', 'Jenis Latihan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_rules('category', 'Kategori', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            
            if ($this->form_validation->run() == FALSE) {
                
                $this->setTraining();
            } 
            else {
                $this->trainingModel->add();
                
                echo '<div class="row"><div class="col-md-12">';
                echo "Maklumat telah berjaya disimpan!!!";
                echo '<button type="button" class="btn btn-default pull-right" onclick="window.location.href=\'training\'">Tutup</button>';
                echo '</div></div>';
                //display success message
                //$this->session->set_flashdata('msg', 'Maklumat telah berjaya disimpan!!!');
                //redirect('training/index');
            }
        }
    }
    
    public function combo_check($str) {
        if ($str == '-PILIH-') {
            $this->form_validation->set_message('combo_check', '%s Diperlukan.');
            return FALSE;
        } 
        else {
            return TRUE;
        }
    }
}
?>
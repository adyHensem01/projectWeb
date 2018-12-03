<?php
if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');

class Participant extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        
        /*Displaying notification */
        $this->load->library('session', 'form_validation');
        $this->load->helper('form');
        $this->load->helper('url');
        
        /*load Model */
        $this->load->model('trainingModel');
        $this->load->model('participateModel');
    }
    
    public function index() {
        $this->load->library('pagination');
        
        $sess = $this->session->search;
        
        /*url segment */
        if ($this->uri->segment(3)) {
            $page = ($this->uri->segment(3));
        } 
        else {
            $page = 1;
        }
        
        if (!$this->session->has_userdata('login')) {
            redirect('' . base_url() . 'index.php');
        }
        
        /* Pagination */
        $config = array();
        $config["base_url"] = base_url() . "index.php/participant/index/";
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
        $header['title'] = "Pilih Peserta";
        $data['frsttitle'] = "Pendaftaran Peserta";
        $data['sectitle'] = "Pilih Peserta";
        $data['page'] = $page;
        $data['perpage'] = $config['per_page'];
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>', '<li><a href="#"><i class="fa fa-fa-cogs"></i>Pendaftaran Peserta</a></li>', '<li><a href="#"><i class="fa fa-fa-cogs"></i>Senarai Kursus</a></li>', '<li><a href="" class="active">Pilih Peserta</a></li>');
        
        /* Searching Function */
        $search = $this->input->post('keyword');
        
        if (isset($search)) {
            
            $this->session->unset_userdata('search');
            
            //$search = $this -> input -> post('keyword');
            
            if (!empty($search)) {
                $data['senarai_latihan'] = $this->trainingModel->get_training_list($config['per_page'], $page, $search);
                $config["total_rows"] = !empty($data['senarai_latihan'][0]->totalrec) ? $data['senarai_latihan'][0]->totalrec : "";
            } 
            elseif (empty($search)) {
                $data['senarai_latihan'] = $this->trainingModel->get_training_list($config['per_page'], $page);
                $config["total_rows"] = !empty($data['senarai_latihan'][0]->totalrec) ? $data['senarai_latihan'][0]->totalrec : "";
            }
            
            /*Keep alive Result */
        } 
        elseif (!empty($sess)) {
            $data['senarai_latihan'] = $this->trainingModel->get_training_list($config['per_page'], $page, $sess);
            $config["total_rows"] = !empty($data['senarai_latihan'][0]->totalrec) ? $data['senarai_latihan'][0]->totalrec : "";
            
            /*default view*/
        } 
        else {
            $data['senarai_latihan'] = $this->trainingModel->get_training_list($config['per_page'], $page);
            $config["total_rows"] = !empty($data['senarai_latihan'][0]->totalrec) ? $data['senarai_latihan'][0]->totalrec : "";
        }
        
        /*initialize pagination */
        $this->pagination->initialize($config);
        
        $this->load->view('templates/home/headerhome', $header);
        
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
        /*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
        $this->load->view('templates/form/formstart', $data);
        $this->load->view('lists/coursereg', $data);
        $this->load->view('templates/list/listclose');
        $this->load->view('templates/list/listfooter');
    }
    
    public function setParticipant() {
        /* model valuation*/
        $this->load->model('adminvaluation');

        if (!$this->session->has_userdata('login')) {
            redirect('' . base_url() . 'index.php');
        }
        
        /*breadcrumb / tittle element*/
        $header['title'] = "Pilih Peserta";
        $data['frsttitle'] = "Pendaftaran Peserta";
        $data['sectitle'] = "Pilih Peserta";
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>', '<li><a href="#"><i class="fa fa-fa-cogs"></i>Pendaftaran Peserta</a></li>', '<li><a href="#"><i class="fa fa-fa-cogs"></i>Senarai Kursus</a></li>', '<li><a href="" class="active">Pilih Peserta</a></li>');
        $data['username'] = $this->participateModel->getUsers();
        $data['training'] = $this->participateModel->getTraining($this->uri->segment(3));
        $this->load->view('templates/home/headerhome', $header);
        
        /*dropdown jabatan*/
        $data['dropdown'] = $this->adminvaluation->getJabatan();

        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
        /*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
        $this->load->view('templates/form/formstart', $data);
        if (empty($data['training'])) {
            $error['title'] = "ERROR!!!";
            $error['msg'] = "ID Not Found / ID not Specified";
            $this->load->view('templates/form/formerror.php', $error);
        } 
        else {
            $this->load->view('forms/userregcourse.php');
        }
        $this->load->view('templates/form/formclose');
        $this->load->view('templates/form/formfooter');
    }
    
    public function saveParticipant() {
        
        //$this->output->enable_profiler(TRUE);
        $id = $this->input->post('id_latihan');
        $start = $this->input->post('tarikh_mula');
        $end = $this->input->post('tarikh_akhir');
        $res = $this->participateModel->checkUserAvail($this->input->post('user'), $start, $end);
        if (!empty($res)) {
            $this->session->set_flashdata('message', 'Maaf Peserta ' . $res[1] . ' Telah Menghadiri Kursus Pada Tarikh ' . date('d M Y', strtotime($res[0]->KURSUS_MULA)) . ' - ' . date('d M Y', strtotime($res[0]->KURSUS_AKHIR)));
            redirect('' . base_url() . 'index.php/participant/setParticipant/' . $id);
        } 
        else {
            $data = array('id' => $id, 'start' => $start, 'end' => $end);
            $this->participateModel->addParticipant($data, $this->input->post('user'),$this->uri->segment(3));
            redirect('' . base_url() . 'index.php/participant/notifyParticipate/' . $id);
        }
    }
    
    /* update 2/02/16 */ 
    public function changeParty() {
        $this->load->model('homeModel');
        // $this->output->enable_profiler(TRUE);
        $repid = $this->input->post('user');
        $this->form_validation->set_rules('user', 'Peserta', 'required|trim', array('required' => 'Sila Pastikan Peserta Telah Dipilih Untuk Di Ganti'));
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', validation_errors());
            redirect(base_url() . "index.php/participant/notifyParticipate/" . $this->uri->segment(3));
        } 
        else {
            
            /*grab data from previous usr*/
            $oldid = $this->input->post('previd');
            $this->participateModel->alterPartyStat($oldid, $repid);
            $this->participateModel->grabPartyByid();
            /*delete Noti */
            $this->homeModel->delNoti($oldid,$this->uri->segment(3));

            redirect(base_url() . "index.php/participant/notifyParticipate/" . $this->uri->segment(3));
        }
    }
    
    public function notifyParticipate() {
        //$this->output->enable_profiler(TRUE);
        if (!$this->session->has_userdata('login')) {
            redirect('' . base_url() . 'index.php');
        }
        
        /*breadcrumb / tittle element*/
        $header['title'] = "Senarai Peserta";
        $data['frsttitle'] = "Senarai Peserta";
        $data['sectitle'] = "Senarai Peserta";
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>', '<li><a href="#"><i class="fa fa-fa-cogs"></i>Senarai Peserta</a></li>', '<li><a href="#"><i class="fa fa-fa-cogs"></i>Senarai Kursus</a></li>', '<li><a href="" class="active">Senarai Peserta</a></li>');
        $data['participate'] = $this->participateModel->checkUsrOff($this->uri->segment(3));
        $this->load->view('templates/home/headerhome', $header);
        
        /*sidebar menu per user */
        $type = $this->session->login['Type'];
        
      /*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
        $this->load->view('templates/list/liststart', $data);
        $this->load->view('lists/userregister.php');
        $this->load->view('templates/list/listclose');
        $this->load->view('templates/list/listfooter');
    }
    
    public function partyAction() {
        $data = $this->input->post('data');

        $train_detail = $this->participateModel->getTraining($this->uri->segment(3));

        if ($this->input->post('hantar')) {

            foreach ($data as $dataex) {
                
                $dataex = explode('|', $dataex);
                
                $email = $dataex[1];
                
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                
                if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    $this->session->set_flashdata('message', 'Sila Pastikan Format Emel Adalah Tepat, Dan Emel Telah Di Sediakan Sebelum  Di Hantar');
                    redirect('' . base_url() . 'index.php/participant/notifyParticipate/' . $this->uri->segment(3));
                    break;
                } 
                else {
                    
                    /*$this->load->library('email');
                    $config['protocol'] = 'smtp';
                    $config['smtp_host'] = '210.5.47.196';
                    $config['smtp_user'] = 'alif@redajaya.com.my';
                    $config['smtp_pass'] = '@Dm1n123';
                    $config['smtp_port'] = 25;
                    
                    $this->email->initialize($config);
                    $this->email->from('MBPJ@example.com', 'MBPJ SISTEM LATIHAN');
                    $this->email->to($email);
                    //$this->email->cc('another@another-example.com');
                    //$this->email->bcc('them@their-example.com');
                    $this->email->subject('Tawaran Untuk Menjalani Kursus '.$train_detail->TAJUK_KURSUS.'');
                    $this->email->message('Anda Dijemput Hadir Untuk Menghadiri Kursus '.$train_detail->TAJUK_KURSUS.'.');
                    */
                    // Will only print the email headers, excluding the message subject and body
                                        
                    if (!$this->email->send(FALSE)) {
                        echo $this->email->print_debugger();
                    }else{
                        $this->session->set_flashdata('message', 'Emel Telah Berjaya Di Hantar');
                        
                        header('location:' . base_url() . 'index.php/participant/');
                        //redirect('' . base_url() . 'index.php/participant/');
                    } 
                
                }
            }
        }elseif ($this->input->post('buang')) {
           $this->load->model('homeModel');
           $this->participateModel->delParty($data);
           $this->session->set_flashdata('message','Peserta Telah Berjaya Di Buang');
           /*delete Noti */
            $this->homeModel->delNoti($data);
           redirect(base_url()."index.php/Participant/notifyParticipate/".$this->uri->segment(3));
        
        }elseif ($this->input->post('cetak')){
           $usrdata['data'] = $data;
           $usrdata['courseDetail'] = $train_detail;
                if(!empty($usrdata['data'])){
                   
                   /*PDF PLUGINS */
                   ob_start();
                   
                   $this->load->view('pdf/usrletteroffer',$usrdata);
                   
                   $template = ob_get_contents();
                   
                   ob_end_clean();
                   
                   $this->load->view('pdf/mpdf60/mpdf',$usrdata);

                   $mpdf=new mPDF('','', 0, 'Helvetica', 3, 3, 3, 3, 9, 9); 
         
                   $mpdf->SetDisplayMode('fullpage');
         
                   $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
         
                   $mpdf->WriteHTML($template);

                   $mpdf->Output('surat_tawaran.pdf','D');

                   exit;
                   
                }else{
                     $this->session->set_flashdata('message','Maaf Sila Pilih Peserta Terlebih Dahulu');
                     redirect(base_url()."index.php/Participant/notifyParticipate/".$this->uri->segment(3));
                }
        }
    }
    
    //ajaxcall option
    public function changeusrparty() {
        $data['username'] = $this->participateModel->getUsers();
        $this->load->view('forms/chgeusrparty', $data);
    }
}
?>
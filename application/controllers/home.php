<?php if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!'); 

class Home extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('homeModel');
	}

	public function index()
	{	
		// $this->output->enable_profiler();
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}
			
		$data['title'] = "Menu Utama";
        $dataMenu['data'] = $data['title'];
		$this -> load -> view('templates/home/headerhome', $data);
        $this -> load -> view('templates/home/menuOption',$dataMenu);
		$data['course'] = $this->homeModel->getCourseByMonth();
		$data['username'] = $this->homeModel->getUsersDrop();
		$this -> load -> view('home',$data);
	}

	/*1/15/16*/
	public function saveCause(){
		$this->form_validation->set_rules('sbb','Sebab','required|trim|htmlentities',array('required'=>'Sila Nyatakan Sebab'));
		if($this->form_validation->run()==FALSE){
			$this->index();
		}else{
			// $this->homeModel->addCause($this->session->login['Userid'],$this->input->post('idlatihan'));
			// $msj = $this->session->login['Username']." MENOLAK KURSUS";
			// /* 1 = admin noti / 2 = user noti */
			// $type = "1";
			// $link = "index.php/participant/notifyParticipate/".$this->input->post('idlatihan')."#".$this->session->login['Userid'];
			// $this->homeModel->addNoti($msj,$type,$link,$this->session->login['Userid'],$this->input->post('idlatihan'),$this->input->post('tkhlatihan'));
			
            if( $this->input->post('suggest') == 'Tiada Peganti'){
               //redirect(base_url().'index.php/home');
                $data['title'] = 'Muat Turun Fail';
                $data['submit'] = '<a href="'.base_url().'index.php/home" style="color:#fff">Kembali</a>';
                $data['content'] = '<a class ="btn btn-primary" href="'.base_url().'index.php/home/pdftiadaPegganti"><span class="glyphicon glyphicon-floppy-save"></span> Muat Turun Borang</a><br>
                                    ';

                $this->load->view('templates/login/loginheader',$data);
                $this->load->view('modals/modalPdf',$data);
            }else{
                //redirect(base_url().'index.php/home');
                //redirect(base_url().'index.php/home');
                $data['title'] = 'Muat Turun Fail';
                $data['submit'] = '<a href="'.base_url().'index.php/home" style="color:#fff">Kembali</a>';
                $data['content'] = '<a class ="btn btn-primary" href="'.base_url().'index.php/home/pdfAdaPegganti"><span class="glyphicon glyphicon-floppy-save"></span> Muat Turun Borang</a><br>
                                    ';

                $this->load->view('templates/login/loginheader',$data);
                $this->load->view('modals/modalPdf',$data);
            }

            
		}
	}

    public function pdfTiadaPegganti(){
        $file = 'pdf/TiadaPengganti.pdf';
                if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                }
    }

    public function pdfAdaPegganti(){
        $file = 'pdf/AdaPengganti.pdf';
                if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                }
    }

	public function acceptParty($id_latihan){
		$this->homeModel->alterParty($this->session->login['Userid'],$id_latihan);
		$this->session->set_flashdata('message','Terima Kasih Atas Maklumbalas Anda');
		redirect(base_url().'index.php/home');
	}

	/*1/18/16 Pdf Course Detail*/
	public function pdfCourseDetail(){
				$this->load->model('participateModel');

				$data['training'] = $this->participateModel->getTraining($this->uri->segment(3));

            	/*pdf PLUGINS*/
            	ob_start();

            	$this->load->view('pdf/coursedetail',$data);

            	$template = ob_get_contents();
                   
                ob_end_clean();

                //echo $template;

                $this->load->view('pdf/mpdf60/mpdf');

                $mpdf=new mPDF('','', 0, 'Helvetica', 3, 3, 3, 3, 9, 9); 
     
                $mpdf->SetDisplayMode('fullpage');
     
                $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
     
                $mpdf->WriteHTML($template);

                $mpdf->Output('maklumat_kursus'.$this->uri->segment(3).'.pdf','D');

                exit;
	}

	public function departUsrList(){
		//$this -> output->enable_profiler(true);
        $this->load->library('pagination');
        
        $sess = $this->session->searchUsr;

        /*url segment */
        if ($this->uri->segment(3)) {
            $page = ($this->uri->segment(3));
        } 
        else {
            $page = 1;
        }
        
        /* Pagination */
        $config = array();
        $config["base_url"] = base_url() . "index.php/home/departUsrList/";
        $config["per_page"] = 50;
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
        $header['title'] = "Senarai Pengguna Bahagian/Jabatan";
        $data['frsttitle'] = "Peserta Jabatan";
        $data['sectitle'] = "Senarai Pengguna Bahagian/Jabatan";
        $data['page'] = $page;
        $data['perpage'] = $config['per_page'];
        $data['breadcrumb'] = array('<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>', '<li><a href="" class="active">Senarai Pengguna Bahagian/Jabatan</a></li>');
        
        /*data from db */
        
        /* Searching Function */
        $search = $this -> input -> post('keyword');

        if(isset($search)){

            $this->session->unset_userdata('searchUsr');

            //$search = $this -> input -> post('keyword');

            if(!empty($search))
            {
                $item = preg_split("/[-.,]/", $search);
                $data['usrDepart'] = $this->homeModel->getUsrByDepart($config['per_page'], $page,$search);
                $config["total_rows"] = $this->homeModel->UsrNumrow($search);
            /*reset search */
            }elseif (empty($search) ) {
                $data['usrDepart'] = $this->homeModel->getUsrByDepart($config['per_page'], $page);
                $config["total_rows"] = $this->homeModel->UsrNumrow($search);  
            }    
            /*Keep alive Result */
            }elseif(!empty($sess)){
                $data['usrDepart'] = $this->homeModel->getUsrByDepart($config['per_page'], $page,$sess);
                $config["total_rows"] = $this->homeModel->UsrNumrow($sess);  
            /*default view*/
            }else{
                $data['usrDepart'] = $this->homeModel->getUsrByDepart($config['per_page'], $page);
                $config["total_rows"] = $this->homeModel->UsrNumrow();    
            }


        /*initialize pagination */
        $this->pagination->initialize($config);

        $this->load->view('templates/list/listheader', $header);
        
        /*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

        $this->load->view('templates/list/liststart', $data);
        $this->load->view('lists/departusr', $data);
        $this->load->view('templates/list/listclose');
        $this->load->view('templates/list/listfooter');
	}

}

?>
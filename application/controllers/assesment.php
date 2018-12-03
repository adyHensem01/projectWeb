<?php  if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');  

class Assesment extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('assesmentModel');
		$this->load->library('form_validation');
	}

	public function usrAssesmnt()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Borang Penilaian Kursus";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Borang Penilaian Kursus";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Penilaian</a></li>',
				'<li><a href="" class="active">Borang Penilaian Kursus</a></li>',
				 );
		$data['user'] = $this->assesmentModel->get_user_details();
		$data['user_training'] = $this->assesmentModel->get_user_training($this->uri->segment(3));
		$data['penyelia'] =$this->assesmentModel->get_penyelia();

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		switch ($type) {
			case '1':
				$this -> load -> view('templates/home/sidebar_admin', $data);
				break;
			case '2':
				$this -> load -> view('templates/home/sidebar_user', $data);
				break;
			case '3':
				$this -> load -> view('templates/home/sidebar_fasi', $data);
				break;
			
		}
		$this -> load -> view('templates/form/formstart',$data);
		$this -> load -> view('forms/usrassesmnt',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	}


	public function fasiAssesmnt($id_latihan,$id_user)
	{
		// echo $id_latihan.'/'.$id_user;

		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Borang Penilaian Penyelia";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Borang Penilaian Penyelia";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Penilaian</a></li>',
				'<li><a href="" class="active">Borang Penilaian Penyelia</a></li>',
				 );

		//$data['user_training'] = $this->penilaian->get_user_fasi($this->uri->segment(3));
		$data['user'] = $this->assesmentModel->get_user_fasi_details($id_latihan,$id_user);

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		switch ($type) {
			case '1':
				$this -> load -> view('templates/home/sidebar_admin', $data);
				break;
			case '2':
				$this -> load -> view('templates/home/sidebar_user', $data);
				break;
			case '3':
				$this -> load -> view('templates/home/sidebar_fasi', $data);
				break;
			
		}
		$this -> load -> view('templates/form/formstart',$data);
		$this -> load -> view('forms/fasiassesmnt',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	}
	
	public function userAssesmnt1()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Penilaian Penyelia";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Keseluruhan Penilaian Kursus Bagi Peserta";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Penilaian</a></li>',
				'<li><a href="" class="active">Borang Penilaian Penyelia</a></li>',
				 );

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		switch ($type) {
			case '1':
				$this -> load -> view('templates/home/sidebar_admin', $data);
				break;
			case '2':
				$this -> load -> view('templates/home/sidebar_user', $data);
				break;
			case '3':
				$this -> load -> view('templates/home/sidebar_fasi', $data);
				break;
			
		}
		$this -> load -> view('templates/form/formstart',$data);
		$this -> load -> view('lists/report1');
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	}

	public function userAssesmnt2()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Penilaian Penyelia";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Keseluruhan Penilaian Kursus Bagi Peserta";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Penilaian</a></li>',
				'<li><a href="" class="active">Borang Penilaian Penyelia</a></li>',
				 );

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		switch ($type) {
			case '1':
				$this -> load -> view('templates/home/sidebar_admin', $data);
				break;
			case '2':
				$this -> load -> view('templates/home/sidebar_user', $data);
				break;
			case '3':
				$this -> load -> view('templates/home/sidebar_fasi', $data);
				break;
			
		}
		$this -> load -> view('templates/form/formstart',$data);
		$this -> load -> view('lists/report2');
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	}

	    public function save_peserta() {
        
        if ($this->input->post('simpan')) {
        		 $this->load->model('homeModel');
	        	 $user = $this->input->post('user');
	        	 $id_latihan = $this->input->post('id_latihan');
	        	 $penyelia =$this->input->post('penyelia');

             $this->form_validation->set_rules('objektif', 'Objektif', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('penyampaian', 'Penyampaian', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('komunikasi', 'Komunikasi', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('interaksi', 'Interaksi', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('respon', 'Respon', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('difahami', 'Difahami', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('kesan', 'Kesan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('aktiviti', 'Aktiviti', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('bahan', 'Bahan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('tahap', 'Tahap', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('kemahiran', 'Kemahiran', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('tempoh', 'Tempoh', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('lokasi', 'Lokasi', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('makanan', 'Makanan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('dewan', 'Dewan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('urusetia', 'Urusetia', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
             $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

	        	 if ($this->form_validation->run() == FALSE) {

	        	  $this->session->set_flashdata('message','<div class="alert alert-danger text-center">Sila Isikan Tempat yang bertanda *</div>');  
	              redirect('' . base_url() . 'index.php/assesment/usrAssesmnt/' . $id_latihan);
	            } 
	            else{

	        	 $data = array('id_latihan' => $id_latihan, 'user' => $user,'penyelia' => $penyelia);
	 			 
	 			 /*notificate */
	 			 $msg = 'Borang Penyelia Untuk Peserta '.$this->session->login['Username'].'';
	 			 $link = 'index.php/assesment/fasiAssesmnt/'.$id_latihan.'/'.$user;
	 			 $parent = $this->input->post('penyelia');
	 			 $tkh_mula = $this->input->post('tkhmula');
	 			 $this->homeModel->addNoti($msg,'2',$link,$parent,$id_latihan,$tkh_mula,$user);
	 			 /* ./notificate */

	             $this->assesmentModel->add_peserta_borang($data);
	             $this->assesmentModel->update_peserta_borang($data);
	             $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Maklumat Telah Berjaya Disimpan</div>');
	             redirect('' . base_url() . 'index.php/history/');
	         	}
            }
        }

         

        public function save_peserta1() {
        
        if ($this->input->post('simpan')) {

        	 $user = $this->input->post('user');
        	 $id_latihan = $this->input->post('id_latihan');
        	 $penyelia = $this->input->post('penyelia');

             $this->form_validation->set_rules('objektif', 'Objektif', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('penyampaian', 'Penyampaian', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('komunikasi', 'Komunikasi', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('interaksi', 'Interaksi', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('respon', 'Respon', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('difahami', 'Difahami', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('kesan', 'Kesan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('aktiviti', 'Aktiviti', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('bahan', 'Bahan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('tahap', 'Tahap', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('kemahiran', 'Kemahiran', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('tempoh', 'Tempoh', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('lokasi', 'Lokasi', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('makanan', 'Makanan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('dewan', 'Dewan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('urusetia', 'Urusetia', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
             $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            
        	 if ($this->form_validation->run() == FALSE) {

        	  $this->session->set_flashdata('message','<div class="alert alert-danger text-center">Sila Isikan Tempat yang bertanda *</div>');  
              redirect('' . base_url() . 'index.php/report/peserta_maklumbalas/' . $id_latihan .'/'.$user);
            } 
            else{

        	 $data = array('id_latihan' => $id_latihan, 'user' => $user,'penyelia'=> $penyelia);
             $this->assesmentModel->add_peserta_borang($data);
             $this->assesmentModel->update_peserta_borang($data);
              $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Maklumat Telah Berjaya Disimpan</div>');
             redirect('' . base_url() . 'index.php/report/peserta_list/' . $id_latihan);
         }

            }
        }

     public function save_penyelia() {
        $this->load->model('homeModel');
        if ($this->input->post('simpan')) {

        	 $user = $this->input->post('user_id');
        	 $id_latihan = $this->input->post('id_latihan');
        	 $data = array('id_latihan' => $id_latihan, 'user_id' => $user);

        	 /*/05/05/16*/
        	 /*remove noti */
        	 $this->homeModel->delNotiSup($this->uri->segment(3));
        	 /* ./remove noti */

             $this->assesmentModel->add_penyelia_borang($data);
             $this->assesmentModel->update_penyelia_borang($data);
             redirect('' . base_url() . 'index.php/history/participant_fasi/'.$id_latihan);
            }
        }

         public function save_penyelia1() {
        
        if ($this->input->post('simpan')) {
          //echo '<pre>';print_r($_POST);

             $user = $this->input->post('user_id');
        	 $id_latihan = $this->input->post('id_latihan');
        	 $id_penyelia = $this->input->post('penyelia');

        	$data = array('id_latihan' => $id_latihan, 'user_id' => $user, 'penyelia'=> $id_penyelia);

        	 $this->form_validation->set_rules('objektif', 'Objektif', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('peningkatan', 'Peningkatan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('perubahan', 'Perubahan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('taklimat', 'Taklimat', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('bahankursus', 'BahanKursus', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             $this->form_validation->set_rules('aplikasi', 'Aplikasi', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
             
              if ($this->form_validation->run() == FALSE) {
                
                $this->session->set_flashdata('message','<div class="alert alert-danger text-center">Sila Isikan Tempat yang bertanda *</div>'); 
                redirect('' . base_url() . 'index.php/report/FasiForm/'.$id_latihan.'/'.$id_penyelia.'/'.$user);
        	 }
        	 else{
               $this->assesmentModel->add_penyelia_borang1($data);
               $this->assesmentModel->update_penyelia_borang($data);
               $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Maklumat Telah Berjaya Disimpan</div>');
               redirect('' . base_url() . 'index.php/report/penyelia_list/'.$id_latihan);
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

	  /*pdf ./ allif */
	  public function pdf_assement(){
	  			// $this->output->enable_profiler(TRUE);
	  			$data['user'] = $this->assesmentModel->get_user_details();
				$data['user_training'] = $this->assesmentModel->get_user_training($this->uri->segment(3));
				$data['penyelia'] =$this->assesmentModel->get_user_details();
				$data['formdetail'] = $this->assesmentModel->getFormPdf($this->session->login['Userid'],$this->uri->segment(3));
            	/*pdf PLUGINS*/
            	ob_start();

            	$this->load->view('pdf/usrassessment',$data);

            	$template = ob_get_contents();
                   
                ob_end_clean();

                 // echo $template;

                $this->load->view('pdf/mpdf60/mpdf');

                $mpdf=new mPDF('','', 0, 'Helvetica', 3, 3, 3, 3, 9, 9); 
     
                $mpdf->SetDisplayMode('fullpage');
     
                $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
     
                $mpdf->WriteHTML($template);

                $mpdf->Output('borang_maklumbalas.pdf','D');

                exit;
	  }

	  public function pdf_assementFasi(){
	  			$data['user'] = $this->assesmentModel->get_user_fasi_details($this->uri->segment(3),$this->uri->segment(4));
				$data['formdetail'] = $this->assesmentModel->getFormPdfFasi($this->uri->segment(3),$this->uri->segment(4));
            	
            	/*pdf PLUGINS*/
            	ob_start();

            	$this->load->view('pdf/fasiassesmnt',$data);

            	$template = ob_get_contents();
                   
                ob_end_clean();

                //echo $template;

                $this->load->view('pdf/mpdf60/mpdf');

                $mpdf=new mPDF('','', 0, 'Helvetica', 3, 3, 3, 3, 9, 9); 
     
                $mpdf->SetDisplayMode('fullpage');
     
                $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
     
                $mpdf->WriteHTML($template);

                $mpdf->Output('borang_maklumbalas.pdf','D');

                exit;
	  }
	   /*pdf ./allif */

}

?>
<?php  if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');  

class Report extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('assesmentModel');
		$this->load->model('adminvaluation');

	}

	public function overallRpt($keseluruhan = '')
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Keseluruhan";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Keseluruhan Kursus";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Kehadiran</a></li>',
				'<li><a href="" class="active">Keseluruhan</a></li>',
				 );
	 $data['keseluruhan'] = $keseluruhan;

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
		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/overallreport',$data);
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}
	public function Overall()
	{	
		//$this->output->enable_profiler(TRUE);
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		$start_date = $this->input->post('tarikh_mula');
		$end_date = $this->input->post('tarikh_akhir');
		$department = $this->input->post('department');
		$category=$this->input->post('anjuran');
		$title=$this->input->post('nama_kursus');
		
		
			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Keseluruhan";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Keseluruhan Kursus";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Kehadiran</a></li>',
				'<li><a href="" class="active">Keseluruhan</a></li>',
				 );

		$data['OverallData'] =  $this->adminvaluation->OverallRpt($start_date,$end_date,$department,$category,$title);
	    $data['dropdown'] = $this->adminvaluation->getJabatan();
	    $data['nama_kursus'] = $this->adminvaluation->get_course();

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/overall');
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	/* 27/1/16 */
	public function assemntRpt ()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Penilaian";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Penilaian";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Penilaian</a></li>',
				 );
		//$data['data'] = $this->adminvaluation->get_course();

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/assesmentreport',$data);
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}
	public function PenilaianFasi ()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Penilaian";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Penilaian";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Penilaian</a></li>',
				 );
        // $data['data'] = $this->adminvaluation->get_course();
		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/PenilaianPenyelia',$data);
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	public function assemntRptPenyelia($penilaian_penyelia = '')
	{	
		// $this->output->enable_profiler(TRUE);
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}
        
    	$id_kursus =$this->input->post('nama_kursus');

    	$data = array('nama_kursus' => $id_kursus);
        $this->form_validation->set_rules('nama_kursus', 'Nama Kursus','callback_combo_check');
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

        if ($this->form_validation->run() == FALSE) {

            redirect('report/PenilaianFasi');
        } 

        $data['penilaian'] = $this->adminvaluation->match_date_penyelia($data);
			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Penilaian Penyelia";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Penilaian Penyelia";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Penilaian</a></li>',
				 );
		

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/assesRptPenyelia',$data);
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	public function freqRpt()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		// $this->output->enable_profiler(TRUE);

		$group = $this->input->post('group');
		$days = $this->input->post('days');
		$year = $this->input->post('year');
		$month = $this->input->post('month');
		$depart = $this->input->post('department');
		$section = substr($this->input->post('section'), 0, 2);
		$units = $this->input->post('units');
		if(!empty($year) && !empty($month) ){
			$date = $this->input->post('year').'-'.$this->input->post('month');
		}else{
			$date="";
		}

			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Kekerapan";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Kekerapan";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Kekerapan</a></li>',
				 );

		$data['dropdown'] = $this->adminvaluation->getJabatan();

		$this->form_validation->set_rules('days','Jumlah Hari','required|htmlentities',array('required'=>'Sila Isikan %s'));

		if($this->form_validation->run()!=FALSE){
			
			$data['departmentData'] =  $this->adminvaluation->getFreqRpt($date,$depart,$section,$units,$days,$group);
		}

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
		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/frequencyreport');
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	public function more7 ()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Kekerapan";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Kekerapan";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Kekerapan</a></li>',
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
		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/moreseven');
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	public function less7 ()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Kekerapan";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Kekerapan";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Kekerapan</a></li>',
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
		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/lessseven');
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	public function rptMonthly ()
	{
		//$this->output->enable_profiler(TRUE);
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Keseluruhan Bulan";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Keseluruhan Mengikut Bulan";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Kekerapan</a></li>',
				 );
		
		$year = $this->input->post('year');
		$month = $this->input->post('month');

		
		if(!empty($year) && !empty($month) ){
			$date = $this->input->post('year').'-'.$this->input->post('month');
		}else{
			$date = "";
		}

        $data['dropdown'] = $this->adminvaluation->getJabatan();
		$data['monthlydata'] = $this->adminvaluation->getMonthlyAttndRpt($date);
		$data['byoverall'] =$this->adminvaluation->getOverallAttndDetail();
		$data['date'] = $date;
		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

	/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/reportMonthly');
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	public function rptMonthlyDetails()
	{	
		//$this->output->enable_profiler(TRUE);
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Kekerapan";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Kekerapan Mengikut Bulan";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Kekerapan</a></li>',
				 );
		$data['bydepart'] = $this->adminvaluation->getMonthlyAttndDetail();
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
		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/reportMonthlyDetails');
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	public function rptJbtn ()
	{	
		//$this->output->enable_profiler(TRUE);
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}
        
		$start = $this->input->post('tarikh_mula');
		$end =$this->input->post('tarikh_akhir');
		$depart = $this->input->post('department');
		$section = substr($this->input->post('section'), 0, 2);
		$units = $this->input->post('units');
		$days = $this->input->post('days');
		$group = $this->input->post('group');
		
			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Kekerapan";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Kekerapan Mengikut Jabatan";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Kekerapan</a></li>',
				 );
		$data['departmentData'] =  $this->adminvaluation->getFreqRpt($start,$end,$depart,$section,$units,$days,$group);
		$data['dropdown'] = $this->adminvaluation->getJabatan();
		$this->form_validation->set_rules('days','Jumlah Hari','required|htmlentities',array('required'=>'Sila Isikan %s'));
		if($this->form_validation->run()!=FALSE){
			
			$data['departmentData'] =  $this->adminvaluation->getFreqRpt($start,$end,$depart,$section,$units,$days,$group);
		}
		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

	/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/reportJabatan');
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	public function reportjabatan ()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Kekerapan";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Kekerapan Mengikut Pegawai Kumpulan";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Kekerapan</a></li>',
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
		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/senaraiJabatan');
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	public function catergRpt ()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Kategori / Jenis";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Kursus Mengikut Kategori / Jenis";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Kategori / Jenis</a></li>',
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
		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/catergrpt');
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	/* 27/1/16 */
	public function userAssesmnt()
	{	
		 // $this->output->enable_profiler(TRUE);
		 if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		 if ($this->input->post('cari')) {        	
        	// $mula=$this->input->post('tarikh_mula');
        	// $akhir=$this->input->post('tarikh_akhir');
        	$id_kursus =$this->input->post('nama_kursus');

        	$data = array('nama_kursus' => $id_kursus);
            $this->form_validation->set_rules('nama_kursus', 'Nama Kursus','required|trim');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run() == FALSE) {

                redirect('report/assemntRpt');
            } 
        }	
		$data['penilaian'] = $this->adminvaluation->match_date_peserta($data);
		$data['jumlah']=$this->adminvaluation->count_participant($data);
			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Penilaian Peserta";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Penilaian Peserta";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Penilaian Peserta</a></li>',
				 );

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('lists/report',$data);
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');	
		
	}
	//Update 6/5/2016
	public function Peserta_feedback()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}
			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Maklumbalas Peserta";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Maklumbalas Peserta";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Maklumbalas Peserta</a></li>',
				 );
		$data['peserta'] = $this->adminvaluation->peserta_feedback();

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		switch ($type) {
			case '0':
				$this -> load -> view('templates/home/sidebar_superadmin', $data);
				break;
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

		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/peserta_feedback',$data);
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');	
		
	}
		public function peserta_list()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Maklumbalas Peserta";
		$data['frsttitle'] = "Menu Kursus";
		$data['sectitle'] = "Maklumbalas Peserta";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Kursus</a></li>',
				'<li><a href="" class="active">Maklumbalas Peserta</a></li>'
				 );
	    $data['participant'] = $this->adminvaluation->name_peserta($this->uri->segment(3));

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		switch ($type) {
			case '0':
				$this -> load -> view('templates/home/sidebar_superadmin', $data);
				break;
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
		$this -> load -> view('reports/peserta_list',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	
	}
	public function peserta_maklumbalas($id_latihan,$id_user)
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Borang Penilaian Peserta";
		$data['frsttitle'] = "Maklumbalas";
		$data['sectitle'] = "Borang Penilaian Peserta";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Kursus</a></li>',
				'<li><a href="" class="active">Maklumbalas Peserta</a></li>'
				 );
	    $data['penyelia'] =$this->assesmentModel->get_penyelia();
		$data['user'] = $this->adminvaluation->get_userid($id_latihan,$id_user);
		$data['user_training'] = $this->adminvaluation->get_peserta_training($this->uri->segment(3));

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		switch ($type) {
			case '0':
				$this -> load -> view('templates/home/sidebar_superadmin', $data);
				break;
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
		$this -> load -> view('forms/feedback_peserta',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	
	}
	// Update 6/5/2016
	public function Penyelia_feedback()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}
			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Maklumbalas Penyelia";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Maklumbalas Penyelia";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Penilaian Penyelia</a></li>',
				 );
		$data['penyelia'] = $this->adminvaluation->penyelia_feedback();

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		switch ($type) {
			case '0':
				$this -> load -> view('templates/home/sidebar_superadmin', $data);
				break;
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

		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('reports/penyelia_feedback',$data);
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');	
		
	}
	public function penyelia_list()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Maklumbalas Penyelia";
		$data['frsttitle'] = "Menu Kursus";
		$data['sectitle'] = "Maklumbalas Penyelia";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Kursus</a></li>',
				'<li><a href="" class="active">Maklumbalas Penyelia</a></li>'
				 );

		$data['fasi'] = $this->adminvaluation->name_penyelia($this->uri->segment(3));

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		switch ($type) {
			case '0':
				$this -> load -> view('templates/home/sidebar_superadmin', $data);
				break;
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
		$this -> load -> view('reports/penyelia_list',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	
	}
	public function penyelia_user_list($id_latihan,$id_penyelia)
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Maklumbalas Penyelia";
		$data['frsttitle'] = "Menu Kursus";
		$data['sectitle'] = "Maklumbalas Penyelia";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Kursus</a></li>',
				'<li><a href="" class="active">Maklumbalas Penyelia</a></li>'
				 );
	
		$data['user'] = $this->adminvaluation->name_penyelia_user($id_latihan,$id_penyelia);

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		switch ($type) {
			case '0':
				$this -> load -> view('templates/home/sidebar_superadmin', $data);
				break;
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
		$this -> load -> view('reports/penyelia_user_list',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	
	}

	public function FasiForm($id_latihan,$id_user,$id_penyelia)
	{
		//echo $id_latihan.'/'.$id_user.'/'.$id_penyelia;

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
		
		$data['user'] = $this->adminvaluation->get_user_fasi_details($id_latihan,$id_user,$id_penyelia);

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		switch ($type) {
			case '0':
				$this -> load -> view('templates/home/sidebar_superadmin', $data);
				break;
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
		$this -> load -> view('forms/FasiForm',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	}
	

   public function assemntRptPeserta ()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

			/*breadcrumb / tittle element*/
		$header['title'] = "Laporan Kategori / Jenis";
		$data['frsttitle'] = "Laporan";
		$data['sectitle'] = "Laporan Kursus Mengikut Kategori / Jenis";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Laporan</a></li>',
				'<li><a href="" class="active">Kategori / Jenis</a></li>',
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

		$this -> load -> view('templates/list/liststart',$data);
        $this -> load -> view('reports/assesRptPeserta');
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
		
		

	}

     public function search_overall() {

        if ($this->input->post('cari')) {

        	$mula=$this->input->post('tarikh_mula');
        	$akhir=$this->input->post('tarikh_akhir');
        	$id_kursus =$this->input->post('nama_kursus');

        	$data = array('tarikh_mula' => $mula, 'tarikh_akhir' => $akhir, 'nama_kursus' => $id_kursus);

            $this->form_validation->set_rules('nama_kursus', 'Nama Kursus','callback_combo_check');
            $this->form_validation->set_rules('tarikh_mula', 'Tarikh Mula','required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            $this->form_validation->set_rules('tarikh_akhir', 'Tarikh Akhir','required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run() == FALSE) {

                $this->Overall();
            } 

            else{

            	$data['keseluruhan'] = $this->adminvaluation->overall_valuation($data);
            	$this->overallRpt($data['keseluruhan']);

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

}

?>
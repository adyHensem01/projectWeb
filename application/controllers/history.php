<?php if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!'); 

class History extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('assesmentModel');
	}

	public function index()
	{	
		// $this->output->enable_profiler(TRUE);
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

				/*breadcrumb / tittle element*/
		$header['title'] = "Senarai Kursus Dihadiri";
		$data['frsttitle'] = "Penilaian";
		$data['sectitle'] = "Senarai Kursus";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Penilaian</a></li>',
				'<li><a href="" class="active">Senarai Kursus Dihadiri</a></li>'
				 );
		$data['kursus'] = $this->assesmentModel->get_user_course();

		$this -> load -> view('templates/list/listheader', $header);
		
		/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('lists/historycourse',$data);
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
			
	}

	public function PenyeliaView()
	{	
		// $this->output->enable_profiler(TRUE);
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Set Kursus";
		$data['frsttitle'] = "Menu Kursus";
		$data['sectitle'] = "Set Kursus";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Kursus</a></li>',
				'<li><a href="" class="active">Set Kursus</a></li>'
				 );
		$data['kursus_penyelia'] = $this->assesmentModel->get_penyelia_course();


		$this -> load -> view('templates/home/headerhome', $header);
		
		/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
		
		$this -> load -> view('templates/form/formstart',$data);
		$this -> load -> view('lists/fasiView');
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	
	}
		public function participant_fasi()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Set Kursus";
		$data['frsttitle'] = "Menu Kursus";
		$data['sectitle'] = "Set Kursus";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Kursus</a></li>',
				'<li><a href="" class="active">Set Kursus</a></li>'
				 );
		//$data['kursus_penyelia'] = $this->penilaian->get_penyelia_course();
		$data['participant'] = $this->assesmentModel->name_participant_fasi($this->uri->segment(3));
		
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
		$this -> load -> view('lists/participant_fasi',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	
	}

	/*allif */
	public function historyUsr(){
		// $this->output->enable_profiler(TRUE);
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

				/*breadcrumb / tittle element*/
		$header['title'] = "Sejarah Kursus Dihadiri";
		$data['frsttitle'] = "History";
		$data['sectitle'] = "Sejarah Kursus";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Penilaian</a></li>',
				'<li><a href="" class="active">Sejarah Kursus Dihadiri</a></li>'
				 );
		$data['usrhistory'] = $this->assesmentModel->getHistoryUsr();
		$data['suphistory'] = $this->assesmentModel->getHistorySup();
		$data['static'] = $this->assesmentModel->getHourDay();

		$this -> load -> view('templates/list/listheader', $header);
		
		/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
		
		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('lists/historyusr',$data);
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	/*allif*/

}

?>
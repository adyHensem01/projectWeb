<?php  if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');  

class Ajax extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('adminvaluation');
		$this->load->model('participateModel');
	}

	public function ajaxBahagian(){
		$data = $this->adminvaluation->getBahagian($this->input->post('department'));
		echo '[';
			foreach ($data as $value => $label) {
				$dataex[] = '{"value":"'.$value.'","label":"'.$label.'"}';
			}
		echo $dataim = implode(",", $dataex);
		echo ']';
	}

	public function ajaxUnit(){
		$data = $this->adminvaluation->getUnit($this->input->post('section'));
		echo '[';
			foreach ($data as $value => $label) {
				$dataex[] = '{"value":"'.$value.'","label":"'.$label.'"}';
			}
		echo $dataim = implode(",", $dataex);
		echo ']';
	}

	public function ajaxUser(){
		$depart = $this->input->post('department');
		$section = substr($this->input->post('section'), 0, 2);
		$units = $this->input->post('units');

		// $depart = '7';
		// $section = '0';
		// $units = '1';

		$data = $this->participateModel->getUsers($depart,$section,$units);
		
		if(!empty($data)){
		echo '[';
			foreach ($data as $value) {
				$dataex[] = '{"value":"'.$value->USER_ID.'|'.$value->NAME.'","label":"'.$value->NAME.' - '.$value->HR_NO_PEKERJA.'"}';
			}
		echo $dataim = implode(",", $dataex);
		echo ']';	
		}else{
			//Return EMPTY JSON
			echo '[{}]';
		}
	}

	public function getNoti(){
		$this->load->model('userModel');
			
			if( $this->session->login['Type']=="1" ){
				$result = $this->userModel->getNotify();
			}elseif ( $this->session->login['Type']=="2" ){
				$result = $this->userModel->getNotifySup($this->session->login['Userid']);
			}
		
			if(!empty($result)){
					echo ' <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		                   <i class="fa fa-bell-o"></i>
		                   <span class="label label-warning">'.$result[0]->NOTI.'</span>
		                   </a>
		                   <ul class="dropdown-menu" style="width:400px">
		                   <li class="header">Notifikasi - '.$result[0]->NOTI.' Item</li>
		                   <li>
		                   <!-- inner menu: contains the actual data -->
		                   <ul class="menu">';
				foreach ($result as $noti) {
					
					if( $this->session->login['Type']=="1" ){
					 echo "<li>
						   <a href='".base_url().$noti->LINK."'>
		                   <i class=\"fa fa-warning text-red\"></i>".$noti->MSG."
		                   </a>
		                   </li>";
					}elseif( $this->session->login['Type']=="2" ){
					 echo "<li>
						   <a href='".base_url().$noti->LINK."/".$noti->ID_NOTI."'>
		                   <i class=\"fa fa-warning text-red\"></i>".$noti->MSG."
		                   </a>
		                   </li>";
					}
				}
					 echo '</ul>
		                   </li>
		                   <!--<li class="footer"><a href="#">&nbsp;</a></li>-->
		              	   </ul>';
			}
	}

	public function getUsers(){
		$this->load->model('ParticipateModel');
		$data = $this->ParticipateModel->getUsers();
		//echo '[';
		$dataex = array();
			$i=0;
			foreach ($data as $value => $label) {
					$dataex[] = $label->NAME." | ".$label->HR_NO_PEKERJA."";
					//$dataex[$i]['id'] = $value;
					//$dataex[$i]['value'] = $label; 
			$i++;}
			
		echo json_encode($dataex);
	}

	public function ajaxCourse(){
		// $this->output->enable_profiler(TRUE);
		$this->load->model('assesmentModel');
		if($this->input->post('act')=="peserta"){
			$result = $this->assesmentModel->getCourseByYear($this->input->post('year'));
		}elseif ($this->input->post('act')=="penyelia"){
			$result = $this->assesmentModel->getCourseByYearSup($this->input->post('year'));
		}

		$resEncode = array();
		$i = 0;
		if(!empty($result)){
				foreach ($result as $course) {
				$resEncode[$i]['id'] = $course-> ID_LATIHAN;
				$resEncode[$i]['value'] = $course->TAJUK_KURSUS;
				$i++;}
		}
		echo json_encode($resEncode);
	}

	/*public function ajaxAttend($id){
		$jabGe = $this->input->post('department');
		$bahGe = $this->input->post('section');
		$unitGe = $this->input->post('units');
		$this->load->model('attendanceModel');
		$res = $this->attendanceModel->getAttendCourseByID($id,$jabGe,$bahGe,$unitGe);
		$resArr = array();
		$i = 0;
		foreach ($res as $key) {
			$resArr[$i]['label'] = $key->NAME."-".$key->HR_NO_PEKERJA;
			$resArr[$i]['value'] = $key->USER_ID;
		$i++;}
		echo json_encode($resArr);	
	}*/

	public function ajaxHistory($idUsr){
		$data['userHistory'] = $this->participateModel->getUsrHistoryAjx($idUsr);
		$this->load->view('lists/ajaxHistoryusr',$data);
	}

}
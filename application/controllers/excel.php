<?php  if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');
 
 class Excel extends CI_Controller{
 	public function __construct(){
 		parent::__construct();
 		$this->load->model('adminvaluation');
 	}

 	public function excelPesertaRpt($id){
 		//$this->output->enable_profiler(TRUE);
 		$data = array('nama_kursus' => $id);
 		$rptdata['excData'] = $this->adminvaluation->match_date_peserta($data);
 		$this->load->view('excel/excelRptPeserta',$rptdata);
 	}

 	public function excelPenyeliaRpt($id){
 		//$this->output->enable_profiler(TRUE);
 		$data = array('nama_kursus' => $id);
 		$rptdata['excData'] = $this->adminvaluation->match_date_penyelia($data);
 		$this->load->view('excel/excelRptPenyelia',$rptdata);
 	}

 	public function excelOverallRpt(){
 		//$this->output->enable_profiler(TRUE);
 		$start_date = $this->input->post('tarikh_mula');
		$end_date = $this->input->post('tarikh_akhir');
		$department = $this->input->post('department');
		$category=$this->input->post('anjuran');
		$title=$this->input->post('nama_kursus');
 		$rptdata['excData'] = $this->adminvaluation->OverallRpt($start_date,$end_date,$department,$category,$title);
 		$this->load->view('excel/excelRptOverall',$rptdata);
 	}
 	public function excelRptMonthlyOverall(){
 		$rptdata['excData'] = $this->adminvaluation->getOverallAttndDetail();
 		$this->load->view('excel/excelRptMonthlyOverall',$rptdata);
 		// echo "<pre>";print_r($rptdata);echo "</pre>";
 	}
 	public function excelRptMonthlyDep(){
 		$start = $this->input->post('tarikh_mula');
		$end =$this->input->post('tarikh_akhir');
		$depart = $this->input->post('department');
		$section = substr($this->input->post('section'), 0, 2);
		$units = $this->input->post('units');
		$days = $this->input->post('days');
		$group = $this->input->post('group');

 		$rptdata['excData'] = $this->adminvaluation->getFreqRpt($start,$end,$depart,$section,$units,$days,$group);
 		$this->load->view('excel/excelRptAttendance',$rptdata);
 		// echo "<pre>";print_r($rptdata);echo "</pre>";
 	}
 }

?>
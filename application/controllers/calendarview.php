<?php if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!'); 

class Calendarview extends CI_Controller{

	public function __construct(){
		parent:: __construct();
		$this->load->model('trainingModel');
	}

	public function index(){
		$data = $this->trainingModel->getCalTraining();
		date_default_timezone_set('Asia/Kuala_Lumpur');
		$i = 0;
		foreach ($data as $key) {
		if($i > 15){$i = 0;}
		$color = array('#f56954','#f39c12','#0073b7','#00c0ef','#00a65a','#001a35','#555299','#ca195a','#7d4627','#173e43','#b56969','#98dafc','#6534ff','#5e0231','#e05038','#300032','#c43235');
		shuffle($color);
		$json[]  =  '{'.
					'"title" : "'.$key->TAJUK_KURSUS.'",'.
					'"place" : "'.$key->TEMPAT.'",'.
					'"start" : "'.$key->TARIKH_MULA.'T00:00:00",'.
					'"end" :   "'.$key->TARIKH_AKHIR.'T24:00:00",'.
					'"timeStart" : "'.date('g:i a',strtotime($key->MASA_MULA)).'",'.
					'"timeEnd" : "'.date('g:i a',strtotime($key->MASA_AKHIR)).'",'.
					'"backgroundColor" : "'.$color[$i].'",'. 
              		'"borderColor" : "'.$color[$i].'",'.
              		'"allDay" : "true"'.
					'}'; 
		$i++;}
		
		$jsonimp = implode(',',$json);

		echo '['.$jsonimp.']';

	}
}

?>
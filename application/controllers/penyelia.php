<?php
if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');

class Penyelia extends CI_Controller
{
	public function __construct() {
        parent::__construct();

          /*load Model */
        $this->load->model('penyeliaModel');

    }

    public function Fasi(){

    	if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Set Penyelia";
		$data['frsttitle'] = "Penyelia";
		$data['sectitle'] = "Set Penyelia";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="#"><i class="fa fa-fa-cogs"></i>Penyelia</a></li>',
				'<li><a href="" class="active">Set Penyelia</a></li>'
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
		$this -> load -> view('lists/setPenyelia',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
    }

    public function setFasi(){

    	if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Kemaskini Penyelia";
		$data['frsttitle'] = "Penyelia";
		$data['sectitle'] = "Kemaskini Penyelia";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="#"><i class="fa fa-fa-cogs"></i>Penyelia</a></li>',
				'<li><a href="" class="active">Set Penyelia</a></li>'
				 );
        $data['penyelia']= $this->penyeliaModel->get_penyelia();
        $data['dropdown']=$this->penyeliaModel->getJabatan();
        $data['username'] = $this->penyeliaModel->getUsers();

		$this -> load -> view('templates/home/headerhome', $header);
		
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];
		
/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/form/formstart',$data);
		$this -> load -> view('forms/registerFasi');
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
    }

    public function savePenyelia(){
    	$penyelia=$this->input->post('penyelia');
    	$name=$this->input->post('nama_penyelia');
        $data = array('penyelia' => $penyelia,'nama_penyelia' => $name);
        $this->penyeliaModel->UpdateFasi($data, $this->input->post('user'));
        $this->session->set_flashdata('message', '<div class="alert alert-success text-center">Maklumat Telah Berjaya di Kemaskini</div>');
        redirect(base_url() . "index.php/penyelia/setFasi/");
    }

}

?>
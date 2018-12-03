<?php if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!'); 

class User extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
        $this->load->helper('security');
		$this->load->library('session', 'form_validation');
		$this->load->model('adminModel');
		$this->load->model('changepassword');
		$this->load->model('penyeliaModel');
	}

	public function index()
	{	
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Senarai Pengguna";
		$data['frsttitle'] = "Pentadbir";
		$data['sectitle'] = "Senarai Pengguna";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="#"><i class="fa fa-fa-cogs"></i> Penyelengaraan</a></li>',
				'<li><a href="" class="active">Senarai Pengguna</a></li>'
				 );
		$data['senarai_admin'] = $this->adminModel->list_pentadbir();

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

	/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/form/formstart',$data);
		$this -> load -> view('lists/admin',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');


	}

	public function SuperAdmin()
	{	
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Senarai Pengguna";
		$data['frsttitle'] = "Pentadbir";
		$data['sectitle'] = "Senarai Pengguna";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="#"><i class="fa fa-fa-cogs"></i> Penyelengaraan</a></li>',
				'<li><a href="" class="active">Senarai Pengguna</a></li>'
				 );
		$data['senarai_admin'] = $this->adminModel->list_pentadbir();

		$this -> load -> view('templates/home/headerhome', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

	/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/form/formstart',$data);
		$this -> load -> view('lists/superadmin',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');


	}

	public function usrRegister()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Set Level Pengguna";
		$data['frsttitle'] = "Pentadbir";
		$data['sectitle'] = "Set Level Pengguna";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="#"><i class="fa fa-fa-cogs"></i> Penyelengaraan</a></li>',
				'<li><a href="" class="active">Set Level Pengguna</a></li>'
				 );

		$data['dropdown'] = $this->adminModel->get_jabatan();
		$data['penyelia']= $this->penyeliaModel->get_penyelia();


        if($this->uri->segment(3)){
            $data['coursebyid'] = $this -> adminModel -> get_byid($this->uri->segment(3));
            $data['stats'] = "ups";
        }else{
            $data['stats'] = "ins";
        }
    

		$this -> load -> view('templates/home/headerhome', $header);
		
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

	/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/form/formstart',$data);
		$this -> load -> view('forms/setuserlvl');
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	}

	public function usrRegister1()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Set Level Pengguna";
		$data['frsttitle'] = "Pentadbir";
		$data['sectitle'] = "Set Level Pengguna";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="#"><i class="fa fa-fa-cogs"></i> Penyelengaraan</a></li>',
				'<li><a href="" class="active">Set Level Pengguna</a></li>'
				 );

		$data['dropdown'] = $this->adminModel->get_jabatan();
		$data['penyelia']= $this->penyeliaModel->get_penyelia();


        if($this->uri->segment(3)){
            $data['coursebyid'] = $this -> adminModel -> get_byid($this->uri->segment(3));
            $data['stats'] = "ups";
        }else{
            $data['stats'] = "ins";
        }
    

		$this -> load -> view('templates/home/headerhome', $header);
		
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

	/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/form/formstart',$data);
		$this -> load -> view('forms/setuserlvl1');
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	}

	public function setPass()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

		/*breadcrumb / tittle element*/
		$header['title'] = "Set Kata laluan";
		$data['frsttitle'] = "Tukar Kata Laluan";
		$data['sectitle'] = "Set Kata lalauan";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="#"><i class="fa fa-fa-cogs"></i> Tukar Kata Laluan</a></li>',
				'<li><a href="" class="active">Set Kata lalauan</a></li>'
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
		$this -> load -> view('forms/changepass');
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	}

	public function ResetPassword($reset = '')
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}
        $data['setsemula'] = $reset;
		/*breadcrumb / tittle element*/
		$header['title'] = "Set Semula Kata Laluan";
		$data['frsttitle'] = "Kata Laluan";
		$data['sectitle'] = "Set Semula Kata Laluan";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="#"><i class="fa fa-fa-cogs"></i> Kata Laluan</a></li>',
				'<li><a href="" class="active">Set Semula Kata Laluan</a></li>'
				 );

		$this -> load -> view('templates/home/headerhome', $header);
		
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

	/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);

		$this -> load -> view('templates/form/formstart',$data);
		$this -> load -> view('forms/resetPass',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	}

	public function search_reset(){

		 if ($this->input->post('cari')) {

        	$no_pekerja=$this->input->post('no_pekerja');
        	$data = array('no_pekerja' => $no_pekerja);
            $this->form_validation->set_rules('no_pekerja', 'No Pekerja','required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run() == FALSE) {
                $this->ResetPassword();
            }

            else{

            	$data['reset'] = $this->changepassword->reset($data);
            	$this->ResetPassword($data['reset']);
            	
        }
	}
}

	  public function reset($id) {      
        $this->changepassword->resetpass('5f4dcc3b5aa765d61d8327deb882cf99', $id);
        $id = $this->input->post('HR_NO_PEKERJA');
	$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Kata Laluan Telah Berjaya di Set Semula</div>');
        redirect('user/ResetPassword');
    }


 public function change(){

	if ($this->input->post('simpan')) {
    $this->form_validation->set_rules('old_password', 'Password', 'trim|required|xss_clean', array('required' => 'Sila Isikan %s.'));
    $this->form_validation->set_rules('newpassword', 'Password Baru', 'required|matches[re_password]', array('required' => 'Sila Isikan %s.'));
    $this->form_validation->set_rules('re_password', 'Password Baru', 'required', array('required' => 'Sila Sahkan %s.'));
    if($this->form_validation->run() == FALSE){
       $this->setPass();
    }else{
      $query = $this->changepassword->checkOldPass(md5($this->input->post('old_password')));
      if($query == 1){
        $query = $this->changepassword->saveNewPass(md5($this->input->post('newpassword')));
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Kemaskini telah Berjaya</div>');
        redirect('user/setPass');    
        }
        else{
        $this->session->set_flashdata('category_error','<div class="alert alert-danger text-center">Kata Laluan Salah</div>');
        redirect('user/setPass'); 
        }
      }

    }
    
   }
    public function update_db() {

    $this->load->helper('form');

        if ($this->input->post('simpan')) {

            $this->form_validation->set_rules('nama', 'Nama Pegawai', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            $this->form_validation->set_rules('jabatan', 'Jabatan','callback_combo_check');
            $this->form_validation->set_rules('section', 'Bahagian','callback_combo_check');
            $this->form_validation->set_rules('units', 'Unit','callback_combo_check');
            $this->form_validation->set_rules('jawatan', 'Jawatan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run() == FALSE) {
              $this->session->set_flashdata('message','<div class="alert alert-danger text-center">Sila Isikan Ruangan yang bertanda *</div>');    
                $this->usrRegister();
            } 
            else {
        if($this->input->post('stats') == "ins"){
        $dept = $this->input->post('department');
        $section = $this->input->post('section');
        $unit = $this->input->post('units');
        $jawatan = $this->input->post('jawatan');

        $data = array('ID_JABATAN' => $dept,'ID_BAHAGIAN' => $section,'ID_UNIT' => $unit, 'USERLVLACC' => $jawatan);
        
        $this->adminModel->update($data,$this->input->post('nama')); 

               $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Maklumat telah Berjaya Disimpan</div>');
               redirect('user/');
           }
          elseif($this->input->post('stats') == "ups"){

                            $this->adminModel->alter($this->uri->segment(3));

                            $this-> session -> set_flashdata('message', '<div class="alert alert-success text-center">Maklumat telah Berjaya Disimpan</div>');

                             redirect('user/');
                    }
            }

        }
    }
  

    public function update_db1() {

    	 $this->load->helper('form');

        if ($this->input->post('simpan')) {

            $this->form_validation->set_rules('nama', 'Nama Pegawai', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            $this->form_validation->set_rules('jabatan', 'Jabatan','callback_combo_check');
            $this->form_validation->set_rules('section', 'Bahagian','callback_combo_check');
            $this->form_validation->set_rules('units', 'Unit','callback_combo_check');
            $this->form_validation->set_rules('jawatan', 'Jawatan', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));

            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            if ($this->form_validation->run() == FALSE) {
              $this->session->set_flashdata('message','<div class="alert alert-danger text-center">Sila Isikan Ruangan yang bertanda *</div>');    
                $this->usrRegister1();
            } 
            else {
                 if($this->input->post('stats') == "ins"){
        $dept = $this->input->post('department');
        $section = $this->input->post('section');
        $unit = $this->input->post('units');
        $jawatan = $this->input->post('jawatan');

        $data = array('ID_JABATAN' => $dept,'ID_BAHAGIAN' => $section,'ID_UNIT' => $unit, 'USERLVLACC' => $jawatan);
        
        $this->adminModel->update($data, $this->input->post('nama')); 

               $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Maklumat telah Berjaya Disimpan</div>');
                  redirect('user/SuperAdmin');
           }
          elseif($this->input->post('stats') == "ups"){

                            $this->adminModel->alter($this->uri->segment(3));

                            $this-> session -> set_flashdata('message', '<div class="alert alert-success text-center">Maklumat telah Berjaya Disimpan</div>');

                             redirect('user/SuperAdmin');
                    }
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

	//Mail Change Class //
	public function changemail($idcourse){
		 $id = $this->input->post('idusr');
		 $emel = $this->input->post('emel');
		 $this->form_validation->set_rules('emel','Email','required|trim',array('required' => 'Sila Isikan %s.'));

		 if($this->form_validation->run()==FALSE){
		 	$this->session->set_flashdata('message',validation_errors());
		 }else{
		 	$this -> load ->model('userModel');
		 	$this -> userModel->changemail($id,$emel);
		 	$this -> session -> set_flashdata('message','Emel Telah Berjaya Di Tukar');
		 	
		 }
		 redirect(base_url().'index.php/participant/notifyParticipate/'.$idcourse);
	}

	public function delete($userid){
       
        /*Y active || T inactive */
        
        $this->adminModel->del(T, $userid);
        
        $userid = $this->input->post('USER_ID');
        
        $this-> session -> set_flashdata('message', 'Pentadbir Tidak AKtif');

        redirect('user/index');

	}

}

?>
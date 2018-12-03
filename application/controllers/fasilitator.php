<?php if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');  

class Fasilitator extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();

		 /*load Model */
        $this->load->model('fasilitatorModel');
	}

	public function index()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}

				/*breadcrumb / tittle element*/
		$header['title'] = "Senarai Penceramah";
		$data['frsttitle'] = "Selenggara Kursus";
		$data['sectitle'] = "Senarai Penceramah";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Utama</a></li>',
				'<li><a href="" class="active">Selenggara Kursus</a></li>',
				'<li><a href="" class="active">Senarai Penceramah</a></li>'
				 );
		$data['penceramah'] = $this->fasilitatorModel->  get_tag_users();

		$this -> load -> view('templates/list/listheader', $header);
		/*sidebar menu per user */
		$type = $this -> session -> login['Type'];

		/*sidebar menu per user */
        $dataMenu['data'] = $data;
        $this -> load -> view('templates/home/menuOption',$dataMenu);
        
		$this -> load -> view('templates/list/liststart',$data);
		$this -> load -> view('lists/fasilitator',$data);
		$this -> load -> view('templates/list/listclose');
		$this -> load -> view('templates/list/listfooter');
	}

	public function setFasilitator()
	{
		if( !$this -> session -> has_userdata('login')) 
			{
				redirect(''.base_url().'index.php');
			}
		/*breadcrumb / tittle element*/
		$header['title'] = "Nama Penceramah/Fasilitator";
		$header['username'] = $this -> session -> login['Username'];
		$data['frsttitle'] = "Penyelengaraan";
		$data['sectitle'] = "Nama Penceramah/Fasilitator";
		$data['breadcrumb'] = array(
				'<li><a href="#"><i class="fa fa-dashboard"></i> Menu Kursus</a></li>',
				'<li><a href="#"><i class="fa fa-fa-cogs"></i> Penyelengaraan</a></li>',
				'<li><a href="" class="active">Nama Penceramah/Fasilitator</a></li>'
				 );

		 if($this->uri->segment(3)){
            $data['coursebyid'] = $this -> fasilitatorModel -> get_byid($this->uri->segment(3));
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
		
		$this -> load -> view('forms/setfasilitator',$data);
		$this -> load -> view('templates/form/formclose');
		$this -> load -> view('templates/form/formfooter');
	}

	    public function save() {

        if ($this->input->post('simpan')) {

            $this->form_validation->set_rules('syarikat', 'Nama Syarikat', 'required|trim|strip_tags', array('required' => 'Sila Isikan %s.'));
            
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

            
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');


            
            if ($this->form_validation->run() == FALSE) {

                //$this->setFasilitator();
                $this -> session -> set_flashdata('msg','Sila Pastikan Ruangan Nama Syarikat Di Isi Sebelum Di Simpan');
                redirect('fasilitator/setFasilitator/'.$this->uri->segment(3));
            } 
            else {

            	 $nama_syarikat = $this-> input -> post('syarikat');

     	          $nama_penceramah = $this-> input -> post('penceramah');

                  $no_ic = $this-> input -> post('no_ic');

                  $emel = $this-> input -> post('email');

                  $no_tel = $this-> input -> post('no_tel');

                  $catatan = $this-> input -> post('catatan');

                  $penceramah = implode(',',$nama_penceramah);

                  $ic = implode(',',$no_ic);

                  $email = implode(',',$emel);

                  $tel = implode(',',$no_tel);

            	if($this->input->post('stats') == "ins"){

                 		 $this->fasilitatorModel->add($nama_syarikat,$penceramah,$ic,$email,$tel,$catatan);
                 
                 }

                 elseif($this->input->post('stats') == "ups"){

                            $this->fasilitatorModel->alter($this->uri->segment(3),$nama_syarikat,$penceramah,$ic,$email,$tel,$catatan);

                            $this-> session -> set_flashdata('message', 'Data Telah Berjaya Di Simpan');
                }

                 $this-> session -> set_flashdata('message', 'Data Telah Berjaya Di Simpan');

                 redirect('fasilitator/');
            }    

        }
    }

        public function delete($id) {
        
        /*0 active || 1 inactive */
        $id = $this->uri->segment(3);
        
        $this->fasilitatorModel->del(1, $id);

        $this-> session -> set_flashdata('message', 'Data Telah Dibuang');

        redirect('fasilitator/index');
    }

   }

?>
<?php 
if (!defined('BASEPATH')) exit('Hacking Attempt: Get out of the system ..!');

class Login extends CI_Controller 
{		
		public function __construct()
		{
				parent::__construct();
				/*load Session / Validation */
				$this -> load -> library('session', 'form_validation');
				/*load Url Helper(redirect) / Form helper */
				$this -> load -> helper('url' , 'form');
				/*load Model */
				$this -> load -> model('loginsModel');

		}

		public function index()
		{
			//this -> session -> unset_userdata('login');
			//$this -> session -> login =  array('Username' => 'Samad');
			/*Session Check*/
			if( $this -> session -> has_userdata('login')) 
			{
				//redirect('/home');
			}

			/*view*/
			$data['title'] = "Log Masuk";

			$this->load->view('templates/login/loginheader.php', $data);
			$this->load->view('login2');

			/*close write session to prevent concurrent request */
			session_write_close();
		}

		public function checkLogin()
		{	

			$this -> form_validation -> set_rules('username', 'Username', 'required|trim|strip_tags', array('required' => 'Please Insert %s.') );
	    
		    $this -> form_validation -> set_rules('password', 'Password', 'required|md5|strip_tags', array('required' => 'Please Insert %s.'));
		        
		    $this -> form_validation -> set_error_delimiters('<span class="error">', '</span>');	

		     if ($this -> form_validation -> run() == FALSE) 
		     	{
            
            		$this -> index();
            	 
        		}else
        			{	
        				 $name = $this -> input -> post('username');
        				 $pass = $this -> input -> post('password');
        				 $rows = $this -> loginsModel-> getNumUsr($name,$pass);
        				 $count =  $this -> loginsModel -> numrows;
        				
        				if( $count == 0)
        				 {
        				 	$this->session->set_flashdata('category_error', 'Maaf Id Pengguna Dan Katalaluan Salah.');
        				 	$this -> index();
        				 } elseif( $count > 0 )
		        				 {	
		        				 	$this -> session -> login =  array(
		        				 		'Username' => $rows -> NAME,
		        				 		'Type'	=> $rows -> USERLVLACC,
		        				 		'Userid' => $rows -> USER_ID,
		        				 		'Lastlogs' => $rows -> USERLOGINTIMESTAMP,
		        				 		'Departid' => $rows -> ID_JABATAN,
		        				 		'Sectionid' => $rows -> ID_BAHAGIAN,
		        				 		'Unitid' => $rows -> ID_UNIT
		        				 		);

		        				 	if($this->input->post('rememberme'))
							        {
							            $this->load->helper('cookie');
							            $cookie = $this->input->cookie('ci_session'); // we get the cookie
							            $this->input->set_cookie('ci_session', $cookie, '35580000'); // and add one year to it's expiration
							        }

		        				 	if($rows -> USERLVLACC == '1')
		        				 	{	
		        				 		$data['title'] = 'Pilihan Login';
		        				 		$this-> load -> view('templates/login/loginheader',$data);
		        				 		$this-> load -> view('loginOption');
		        				 	}
		        				 	else if($rows -> USERLVLACC == '0'){
		        				 		$data['title'] = 'Pilihan Login';
		        				 		$this-> load -> view('templates/login/loginheader',$data);
		        				 		$this-> load -> view('loginOption1');
		        				 	}
		        				 	else
		        				 		{	
		        				 			// print_r($this->session->login);
		        				 			redirect('/home');
		        				 		}
		        				}
        			}	    

		}

		public function logadmin()
		{
			$session_data = $this->session->login;
			$session_data['Type'] = $this->input->post('usrselect');
			$this->session->set_userdata('login',$session_data);
			redirect('/home');
		}

		public function logout()
		{
			$usrid = $this -> session -> login['Userid'];
			$this -> loginsModel -> setlogs($usrid);
			$this -> session -> sess_destroy();
			redirect(''.base_url().'index.php');	
		}
}


?>
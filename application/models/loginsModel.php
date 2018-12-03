<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginsModel extends CI_Model 
{
	public $numrows;
	public function __construct()
	{
		parent::__construct();
	}

	public function getNumUsr($name,$pass)
	{
		$query =  $this-> db -> get_where('users', array('USERNAME' => $name,'USERPASSWORD' => $pass ));
		$this -> numrows = $query -> num_rows();
		return $query -> row();
		$this -> db -> close();
	}

	public function numrows()
	{
		return $this -> numrows;
	}

	public function setlogs($id)
	{
		date_default_timezone_set('Asia/Kuala_Lumpur');
		$data['USERLOGINTIMESTAMP'] = date('Y-m-d H:i:s');
		$this -> db -> where('USER_ID',$id);
		$this -> db -> update('users',$data);
		$this -> db -> close();
	}


}

?>
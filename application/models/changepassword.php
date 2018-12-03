<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChangePassword extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

public function checkOldPass($old_password)
{
          $id = $this->session->login['Userid'];
          $this->db->from('users');
          $this->db->where(array('USER_ID'=> $id,'USERPASSWORD'=> $old_password));
          $query = $this->db->get();
          $query -> result();
          $count=$query->num_rows();
    if($count > 0)
        return 1;
    else
        return 0;
}

public function saveNewPass($new_pass)
{
    $id = $this->session->login['Userid'];
    $data = array(
           'USERPASSWORD' => $new_pass
        );
    $this->db->where(array('USER_ID'=> $id));
    $this->db->update('users', $data);
    
    return true;
}

public function reset($data){
	$this->db->select('users.NAME,users.HR_NO_PEKERJA');
	$this->db->from('users');
	$this->db->where(array('users.HR_NO_PEKERJA=' => $data['no_pekerja']));
  $query = $this->db->get();
  return $query->result();
  $this->db->close();
}

public function resetpass($default,$id){

        $this->db->set('USERPASSWORD', $default);
        
        $this->db->where('HR_NO_PEKERJA', $id);
        
        $this->db->update('users');

}

    }

?>



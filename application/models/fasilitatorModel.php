<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FasilitatorModel extends CI_Model
{ 
    public function __construct() {
        parent::__construct();
        
}

 public function get_byid($id){      
        $query = $this -> db -> get_where('penceramah',array('ID_PENCERAMAH' => $id));

        if($query->num_rows() > 0){
             
             return $query -> row();
         
         }else{
            
            $this -> db -> close();
         
         }


    }

  public function get_tag_users()
   {

       $this->db->select('*');

       $this->db->from('penceramah');

       $this->db->where(array('STATUS !=' => '1'));

       $this->db->group_by('NAMA_SYARIKAT','asc');

       $query = $this->db->get();

       return $query->result();
        
       $this->db->close();
   }

    public function add($nama_syarikat,$penceramah,$ic,$email,$tel,$catatan){

    	$data = array(

       'NAMA_SYARIKAT' =>$nama_syarikat ,
       'NAMA_PENCERAMAH' => $penceramah,
       'NO_KP' => $ic , 
       'EMEL' => $email,
       'NO_TEL' => $tel,
       'CATATAN' => $catatan
     );

        $this-> db -> insert('penceramah',$data);

    }

    public function alter($id,$nama_syarikat,$penceramah,$ic,$email,$tel,$catatan){
        $data = array(

       'NAMA_SYARIKAT' =>$nama_syarikat ,
       'NAMA_PENCERAMAH' => $penceramah,
       'NO_KP' => $ic , 
       'EMEL' => $email,
       'NO_TEL' => $tel,
       'CATATAN' => $catatan
     );
        $this->db->where('ID_PENCERAMAH',$id);

        $this->db->update('penceramah',$data);
    }

    public function del($status,$id){

    $this-> db ->set('STATUS',$status); 

    $this-> db ->where('ID_PENCERAMAH', $id);

    $this-> db ->update('penceramah');

 }

}


?>
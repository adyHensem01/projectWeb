<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminModel extends CI_Model
{ 
    public function __construct() {
        parent::__construct();
        
        /*load Session / Validation */
        $this->load->library('session');
}


public function list_pentadbir(){
$sql = "SELECT users.*,jabatan.*,bahagian.*,unit.* FROM `users` 
         LEFT JOIN `jabatan` ON jabatan.GE_KOD_JABATAN = users.ID_JABATAN
         LEFT JOIN `bahagian` ON bahagian.GE_KOD_BAHAGIAN = users.ID_BAHAGIAN AND bahagian.GE_KOD_JABATAN = users.ID_JABATAN
        LEFT JOIN `unit` ON unit.GE_KOD_UNIT = users.ID_UNIT AND unit.GE_KOD_BAHAGIAN = users.ID_BAHAGIAN AND unit.GE_KOD_JABATAN = users.ID_JABATAN WHERE users.USERLVLACC = 1 AND users.HR_KAKITANGAN_IND != 'T' ";
          $query = $this->db->query($sql);
          $result = $query->result();
          return $result;
}

     public function get_jabatan(){
    $this->db->select('ID_JABATAN,GE_KETERANGAN_JABATAN');
    $this->db->from('jabatan');
    $res = $this->db->get();
    $resArr =  $res -> result();
    $id = array('');
    $name = array('-Sila Pilih-');

    for ($i = 0; $i < count($resArr); $i++) {
            array_push($id, $resArr[$i]->ID_JABATAN);
            array_push($name, $resArr[$i]->GE_KETERANGAN_JABATAN);
        }  
      return $course_result = array_combine($id, $name);
      $this->db->close();
    }
  public function getBahagian($departid){
    $this->db->select('GE_KOD_JABATAN,GE_KOD_BAHAGIAN,GE_KETERANGAN');
    $this->db->from('bahagian');
    $this->db->where(array('GE_KOD_JABATAN' => $departid));
    $res = $this->db->get();
    $resArr = $res->result();
    $id = array('');
    $name = array('-Sila Pilih-');

    for ($i=0; $i < COUNT($resArr); $i++) { 

        array_push($id, $resArr[$i]->GE_KOD_BAHAGIAN.'|'.$resArr[$i]->GE_KOD_JABATAN);
        array_push($name, $resArr[$i]->GE_KETERANGAN);
    }
    return $arrcombine = array_combine($id, $name);
    $this->db->close();
  }

  public function getUnit($sectionid){
    
    $idjabatan = substr($sectionid, strpos($sectionid,'|')+1, 2);
    $idbahagian = substr($sectionid, 0,strpos($sectionid,'|'));
    $this->db->select('GE_KOD_UNIT,GE_KETERANGAN_UNIT');
    $this->db->from('unit');
    $this->db->where(array('GE_KOD_BAHAGIAN' => $idbahagian,'GE_KOD_JABATAN'=> $idjabatan));
    $res = $this->db->get();
    $resArr = $res->result();
    $id= array('');
    $name= array('-Sila Pilih-');

    for ($i=0; $i < COUNT($resArr); $i++) { 
        array_push($id, $resArr[$i]->GE_KOD_UNIT);
        array_push($name, $resArr[$i]->GE_KETERANGAN_UNIT);
    }
    return $arrcombine = array_combine($id, $name);
    $this->db->close();
  }

     public function get_byid($id){ 

       $this->db->select('users.USERLVLACC,users.ID_JABATAN,jabatan.GE_KETERANGAN_JABATAN,users.ID_BAHAGIAN,users.ID_UNIT,bahagian.GE_KETERANGAN,unit.GE_KETERANGAN_UNIT,users.USER_ID');
       $this->db->from('users');
       $this->db->join('jabatan','jabatan.GE_KOD_JABATAN=users.ID_JABATAN');
       $this->db->join('bahagian','bahagian.GE_KOD_BAHAGIAN = users.ID_BAHAGIAN');
       $this->db->join('unit','unit.GE_KOD_UNIT = users.ID_UNIT');  
       $this -> db -> where(array('users.USER_ID' => $id));
       $result=$this->db->get();
       return $result->row();
       $this->db->close();
    }

    public function alter($id){
        $dept = $this->input->post('department');
        $section = $this->input->post('section');
        $unit = $this->input->post('units');
        $jawatan = $this->input->post('jawatan');

        $data = array('ID_JABATAN' => $dept,'ID_BAHAGIAN' => $section,'ID_UNIT' => $unit, 'USERLVLACC' => $jawatan);
        

        $this->db->where('USER_ID',$id);

        $this->db->update('users',$data);
    }

    public function update($data,$id) {
        $this->db->where(array('USER_ID'=>$id));
        $this->db->update('users',$data);
    }

    public function del($status, $id) {
        $this->db->set('HR_KAKITANGAN_IND', $status);
        
        $this->db->where('USER_ID', $id);
        
        $this->db->update('users');
    
    }
}

?>

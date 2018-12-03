<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenyeliaModel extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

 public function getUsers($depart="",$section="",$units="") {
        
        $this->db->select('NAME,USER_ID,HR_NO_PEKERJA');
        $this->db->from('users');
        if(!empty($depart)){
            $this->db->like(array('ID_JABATAN' => $depart,'ID_BAHAGIAN' => $section,'ID_UNIT' => $units));
        }
        $this->db->order_by('NAME');
        $res = $this->db->get();
        return $res->result();
    }
    // Update 6/5/2016
  public function get_penyelia(){

      $sql = "SELECT users.* FROM `users`ORDER BY NAME ASC";
      $query = $this->db->query($sql);
      $result = $query->result();
      $kursus_id = array('');
      $kursus_name = array('-PILIH-');
        
        for ($i = 0; $i < count($result); $i++) {
            
            array_push($kursus_id, $result[$i]->USER_ID);
            array_push($kursus_name, $result[$i]->NAME.'-'.$result[$i]->HR_NO_PEKERJA);
        }
        return $course_result = array_combine($kursus_id,$kursus_name);

   $this->db->close();
   }

   public function getJabatan(){
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

  public function UpdateFasi($data,$id){

    foreach($id as $userid){

      $userex = explode("|", $userid);
      $data_arr = array('ID_PENYELIA' => $data['penyelia'],
                        'NAMA_PENYELIA' => $data['nama_penyelia'],
                        );
      $this->db->where(array('USER_ID'=>$userex[0]));
      $this->db->update('users',$data_arr);

    }

  }
  
}


?>
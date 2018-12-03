<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class attendanceModel extends CI_Model
{ 
    public function __construct() 
    {
        parent::__construct();
        
        /*load Session / Validation */
        $this->load->library('session');
    }

    public function get_peserta()
     {
          $sql = "SELECT COUNT(peserta.ID_PESERTA) AS JUMLAH_PESERTA,latihan.*,kursus.* FROM `latihan` 
          JOIN `kursus` ON kursus.ID_KURSUS = latihan.ID_KURSUS 
          JOIN `peserta` ON peserta.ID_LATIHAN = latihan.ID_LATIHAN
          WHERE latihan.STATUS = 0 AND STATUS_PENAWARAN != 1 AND  STATUS_GANTI != 2 AND latihan.TARIKH_MULA <= NOW()
          GROUP BY latihan.ID_LATIHAN";
          $query = $this->db->query($sql);
          $result = $query->result();
          return $result;
     }



    public function get_tabs($id)
     {
          $result = array();
          $this-> db ->select('*');
          $this-> db ->from('latihan');
          $this-> db ->where('BIL_HARI',$id);
          $query=$this ->db->get();
          if($query-> num_rows() > 0)
          {
            $result =$query->result();

          }
          return $result;
          //return $this-> db -> get() -> result_array();
     } 


     public function get_user()
     {
          $result = array();
          $sql = "SELECT users.NAME, latihan.*,peserta.* FROM `peserta`
          LEFT JOIN `latihan` ON latihan.ID_LATIHAN = peserta.ID_LATIHAN
          LEFT JOIN `users` ON users.USER_ID = peserta.ID_USER";
          $query = $this->db->query($sql);
          //$query = $this->db->get($sql);
          //return $query->result_array();
          $result = $query->result();
          //return $result;
          //return $this->db->get()->result();
          return $this->db->get()->result_array();


     }

    public function getUsrByTrainID($id,$jabGe="",$bahGe="",$unitGe=""){
      $this->db->select('users.NAME,peserta.*,users.HR_NO_PEKERJA');
      $this->db->from('peserta');
      $this->db->join('users','users.USER_ID = peserta.ID_USER');
      $this->db->where(array('ID_LATIHAN' => $id,'STATUS_PENAWARAN !=' => '1','STATUS_GANTI !=' => '2'));
      if(!empty($jabGe) && !empty($bahGe)){
      $this->db->where(array('ID_LATIHAN' => $id,'ID_JABATAN' => $jabGe,'ID_BAHAGIAN' => $bahGe,'ID_UNIT' => $unitGe));
      }
      $res = $this->db->get();
      return $res->result();
      $this->db->close();
    }

    public function alterAttends($id,$attend,$hour,$courseID){
      $this->db->where(array('ID_USER'=> $id,'ID_LATIHAN'=> $courseID));
      $this->db->update('peserta',array('KEHADIRAN' => $attend,'BILANGAN_JAM' => $hour));
      //$this->db->close();
    }

    /*public function getAttendCourseByID($id,$jabGe,$bahGe,$unitGe){
      $this->db->select('NAME,USER_ID,HR_NO_PEKERJA');
      $this->db->from('peserta');
      $this->db->join('users','users.USER_ID = peserta.ID_USER');
      $this->db->where(array('ID_LATIHAN' => $id,'ID_JABATAN' => $jabGe,'ID_BAHAGIAN' => $bahGe,'ID_UNIT' => $unitGe));
      $res = $this->db->get();
      return $res->result();
    } */  

    public function updateCourseAttndStat($id){
      /*stat Attend 
      1 = already Check
      0 = default
      */ 
      $this->db->where(array('ID_LATIHAN'=> $id));
      $this->db->update('latihan',array('STAT_ATTEND' => '1'));
    }

}


?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrainingModel extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }
    
    public function get_training_list($limit = 0, $page = 0, $string = "") {
        
        $currpage = ($page - 1) * $limit;
        
        /*date format check*/
        if (preg_match("/^\d{1,2}-\d{2}-\d{4}/", $string)) {
            $string = date('Y-m-d', strtotime($string));
        }
        
        $concat = 'CONCAT_WS("|",  `latihan`.`ID_LATIHAN` ,  `latihan`.`ID_KURSUS` ,  `ID_SYARIKAT` ,  `TEMPAT` ,  `TARIKH_MULA` ,  `JENIS_LATIHAN` ,  `kursus`.`TAJUK_KURSUS`, `MASA_MULA`, `KATEGORI` , `URUSETIA`) LIKE "%' . $this->db->escape_like_str($string) . '%"';
        
        /* select rows */
        if (!empty($string)) {
            $select = 'latihan.*, kursus.*,COUNT( ID_PESERTA ) AS JUM,(SELECT COUNT(*) FROM latihan JOIN kursus
                                            ON kursus.ID_KURSUS = latihan.ID_KURSUS WHERE
                                            kursus.STATUS = 0 AND latihan.STATUS = 0 AND TARIKH_MULA > NOW() AND ' . $concat . ') totalrec';
        } 
        else {
            $select = "latihan.*, kursus.*,COUNT( ID_PESERTA ) AS JUM,(SELECT COUNT(*) FROM latihan JOIN kursus
                                            ON kursus.ID_KURSUS = latihan.ID_KURSUS WHERE
                                            kursus.STATUS = 0 AND latihan.STATUS = 0 AND TARIKH_MULA > NOW()) totalrec";
        }
        
        $this->db->select($select);
        $this->db->from('latihan');
        $this->db->join('kursus', 'kursus.ID_KURSUS = latihan.ID_KURSUS','left');
        $this->db->join('peserta', 'peserta.ID_LATIHAN = latihan.ID_LATIHAN AND  peserta.STATUS_GANTI !=2 AND peserta.STATUS_PENAWARAN !=1','left');
        $this->db->where(array('kursus.STATUS' => 0,'latihan.STATUS' => 0));
        $this->db->order_by('latihan.TARIKH_MULA','asc');
        
        /*sup admin */
        if($this->session->login['Type'] != 0){$this->db->where(array('latihan.TARIKH_MULA >' => date('Y-m-d')));}
        
        $this->db->order_by('latihan.ID_LATIHAN','DESC');
        /*where string exist */
        if (!empty($string)) {
            $this->db->where($concat, NULL);
        }

        $this->db->group_by('latihan.ID_LATIHAN');
        
        $this->db->limit($limit, $currpage);
        $query = $this->db->get();
        return $query->result();
        $this->db->close();
    }

     public function get_course(){

      $sql = "SELECT kursus.* FROM `kursus` WHERE kursus.STATUS = 0 ORDER BY TAJUK_KURSUS ASC";
      $query = $this->db->query($sql);
      $result = $query->result();
      $kursus_id = array('-PILIH-');
      $kursus_name = array('-PILIH-');
        
        for ($i = 0; $i < count($result); $i++) {
            
            array_push($kursus_id, $result[$i]->ID_KURSUS);
            array_push($kursus_name, $result[$i]->TAJUK_KURSUS );
        }
        return $course_result = array_combine($kursus_id,$kursus_name);

   $this->db->close();
   }
     public function get_company(){

      $sql = "SELECT penceramah.* FROM `penceramah` WHERE penceramah.STATUS = 0 ORDER BY NAMA_SYARIKAT ASC";
      $query = $this->db->query($sql);
      $result = $query->result();
      $company_id = array('-PILIH-');
      $company_name = array('-PILIH-');
        
        for ($i = 0; $i < count($result); $i++) {
            
            array_push($company_id, $result[$i]->ID_PENCERAMAH);
            array_push($company_name, $result[$i]->NAMA_SYARIKAT);
        }
        return $course_result = array_combine($company_id, $company_name);

   $this->db->close();
   }

      public function numrows($string="") {
        $limit = 10;

        $this->db->from('kursus');

        if(!empty($string)){
            
            foreach ($string as $key) {
                if(is_numeric($key)){
                    
                    $this->db->like("ID_KURSUS",$key);
                
                }elseif(is_string($key)){
                    
                    $this->db->like("TAJUK_KURSUS",$key);
                }
             } 
        }
        
        $query = $this->db->get();

        $query->result();
        
        $numrows = $query->num_rows();

        if(!empty($string) && $numrows > $limit){
            $this -> session -> search = $string;
        }

        return $numrows;
    }

    
    public function add() {
        
        date_default_timezone_set('Asia/Kuala_Lumpur');
        
        $namakursus = $this->input->post('nama_kursus');
        $tempat = $this->input->post('tempat');
          $tkhmula = $this->input->post('tarikhmula');
          $tkhahir = $this->input->post('tarikhakhir');
        $tarikh_Mula = date("Y-m-d",strtotime($tkhmula));
        $tarikh_Akhir = date("Y-m-d",strtotime($tkhahir));
        $masa_Mula = $this->input->post('masamula');
        $masa_Akhir = $this->input->post('masaakhir');
        $bil_Hari = $this->input->post('bilhari');
        $penganjur = $this->input->post('penganjur');
        $penilaian = $this->input->post('penilaian');
        $jam =$this->input->post('jam');
        $kos = $this->input->post('kos');
        $urusetia = $this->input->post('urusetia');
        $UrusName = array();
        foreach ($urusetia as $value) {
            $expName = explode('|', $value);
            $UrusName[]=$expName[0];
        }
        $nama_urusetia = implode(',',$UrusName);
        $catatan = $this->input->post('catatan');
        $jenislatihan = $this->input->post('jenis');
        $kategori = $this->input->post('category');
        $syarikat = $this->input->post('nama_syarikat');
        
        $data = array('ID_KURSUS' => $namakursus,'ID_SYARIKAT' => $syarikat, 'TEMPAT' => $tempat, 'TARIKH_MULA' => $tarikh_Mula, 'TARIKH_AKHIR' => $tarikh_Akhir, 'MASA_MULA' => $masa_Mula, 'MASA_AKHIR' => $masa_Akhir, 'TARIKH_MULA' => $tarikh_Mula, 'TARIKH_AKHIR' => $tarikh_Akhir, 'BIL_HARI' => $bil_Hari,'JAM' => $jam, 'KOS' => $kos,'PENGANJUR' => $penganjur,'PENILAIAN' =>$penilaian ,'URUSETIA' => $nama_urusetia, 'CATATAN' => $catatan, 'JENIS_LATIHAN' => $jenislatihan, 'KATEGORI' => $kategori,'CREATED_BY' => $this->session->login['Username'],'CREATED_DATE' => date('Y-m-d H:i:s'),'USER_ID' => $this->session->login['Userid']);
        
        $this->db->insert('latihan', $data);
    }


    public function getCalTraining(){
        $this->db->from('latihan');
        $this->db->join('kursus', 'kursus.ID_KURSUS = latihan.ID_KURSUS','left');
        $this->db->where(array('kursus.STATUS' => 0,'latihan.STATUS' => 0));
        $res = $this->db->get();
        return $res -> result();
    }



 public function get_byid($id){      
        $query = $this -> db -> get_where('latihan',array('ID_LATIHAN' => $id));
        
        if($query->num_rows() > 0){ 
             return $query -> row();
         }else{
            $this -> db -> close();
         }
    }



 public function del($status,$id){

    // $this-> db ->set('STATUS',$status); 

    // $this-> db ->where('ID_LATIHAN', $id);

    // $this-> db ->update('latihan');

    $this->db->delete('latihan',array('ID_LATIHAN' => $id));
  }

  

     public function alter($id){
       date_default_timezone_set('Asia/Kuala_Lumpur');
        $namakursus = $this->input->post('nama_kursus');
        $tempat = $this->input->post('tempat');
        $tkhmula = $this->input->post('tarikhmula');
          $tkhahir = $this->input->post('tarikhakhir');
        $tarikh_Mula = date("Y-m-d",strtotime($tkhmula));
        $tarikh_Akhir = date("Y-m-d",strtotime($tkhahir));
        $masa_Mula = $this->input->post('masamula');
        $masa_Akhir = $this->input->post('masaakhir');
        $bil_Hari = $this->input->post('bilhari');
        $jam =$this->input->post('jam');
        $kos = $this->input->post('kos');
        $urusetia = $this->input->post('urusetia');
        $UrusName = array();
        foreach ($urusetia as $value) {
            $expName = explode('|', $value);
            $UrusName[]=$expName[0];
        }
        $nama_urusetia = implode(',',$UrusName);
        $catatan = $this->input->post('catatan');
        $jenislatihan = $this->input->post('jenis');
        $kategori = $this->input->post('category');
        $syarikat = $this->input->post('nama_syarikat');
        $penganjur = $this->input->post('penganjur');
        $penilaian = $this->input->post('penilaian');
        
        $data = array('ID_KURSUS' => $namakursus,'PENGANJUR' => $penganjur,'PENILAIAN' => $penilaian,'ID_SYARIKAT' => $syarikat, 'TEMPAT' => $tempat, 'TARIKH_MULA' => $tarikh_Mula, 'TARIKH_AKHIR' => $tarikh_Akhir, 'MASA_MULA' => $masa_Mula, 'MASA_AKHIR' => $masa_Akhir, 'TARIKH_MULA' => $tarikh_Mula, 'TARIKH_AKHIR' => $tarikh_Akhir, 'BIL_HARI' => $bil_Hari,'JAM' => $jam, 'KOS' => $kos, 'URUSETIA' => $nama_urusetia, 'CATATAN' => $catatan, 'JENIS_LATIHAN' => $jenislatihan, 'KATEGORI' => $kategori,'CREATED_BY' => $this->session->login['Username'],'CREATED_DATE' => date('Y-m-d H:i:s'),'USER_ID' => $this->session->login['Userid']);
        
        $this->db->where('ID_LATIHAN',$id);

        $this->db->update('latihan',$data);
    }
}
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AssesmentModel extends CI_Model
{ 
    public function __construct() {
        parent::__construct();
        
}

public function get_penyelia_course(){
  $id = $this->session->login['Userid'];
  $this->db->select('TAJUK_KURSUS,TARIKH_MULA,TARIKH_AKHIR,kursus.ID_KURSUS,latihan.ID_LATIHAN,
                    DATE_ADD(TARIKH_MULA,INTERVAL 2 MONTH) AS TKH_MULA,
                    NOW() AS TODAY');
  $this->db->from('penilaianpeserta');
  $this->db->join('users','users.USER_ID=penilaianpeserta.ID_USER');
  $this->db->join('latihan','penilaianpeserta.ID_LATIHAN = latihan.ID_LATIHAN');
  $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS');
  $this->db->where(array('penilaianpeserta.ID_PENYELIA'=> $id));
  $this->db->where(array('penilaianpeserta.STAT_PENILAI !='=> '1'));
  $this->db->having('TODAY > TKH_MULA');
  $this->db->group_by('latihan.ID_LATIHAN');
  $query = $this->db->get();
  return $query->result();
 $this->db->close();
}

public function name_participant_fasi($id_latihan = ""){
  $id = $this->session->login['Userid'];
  $this->db->from('penilaianpeserta');
  $this->db->join('users','users.USER_ID=penilaianpeserta.ID_USER','left');
  $this->db->join('latihan','penilaianpeserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
  $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
  $this->db->where(array('penilaianpeserta.ID_PENYELIA'=> $id,'penilaianpeserta.STAT_PENILAI !='=> '1'));
  $this->db->where(array('latihan.ID_LATIHAN' => $id_latihan));
  $query = $this->db->get();
  return $query->result();
 $this->db->close();
}

public function get_user_fasi($id_user = "",$id_latihan = "") {
        $id = $this->session->login['Userid'];
        $this->db->from('penilaianpeserta');
        $this->db->join('users','users.USER_ID=penilaianpeserta.ID_USER','left');
        $this->db->join('latihan','penilaianpeserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
        $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
        $this->db->where(array('penilaianpeserta.ID_PENYELIA'=> $id));
        $this->db->where(array('penilaianpeserta.ID_USER'=> $id_user));
        $this->db->where(array('latihan.ID_LATIHAN' => $id_latihan));
        $query = $this->db->get();
        return $query->row();
        $this->db->close();
    }

    public function get_user_fasi_details($id_latihan = "",$id_user = "") {
        $this->db->from('penilaianpeserta');
        $this->db->join('users','users.USER_ID=penilaianpeserta.ID_USER','left');
        $this->db->join('jabatan','users.ID_JABATAN = jabatan.ID_JABATAN', 'left');
        $this->db->join('bahagian','users.ID_BAHAGIAN = bahagian.ID_BAHAGIAN', 'left');
        $this->db->join('latihan','penilaianpeserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
        $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
        $this->db->where(array('penilaianpeserta.ID_USER'=> $id_user));
        $this->db->where(array('latihan.ID_LATIHAN' => $id_latihan));
        $query = $this->db->get();
        return $query->row();
        $this->db->close();
    }

 public function get_user_course() {
        $id = $this->session->login['Userid'];
        $this->db->from('peserta');
        $this->db->join('latihan','peserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
        $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
        $this->db->where(array('peserta.ID_USER'=> $id,
                               'peserta.STAT_BORANG ='=> '0',
                               'peserta.STATUS_PENAWARAN !='=> '1',
                               'TARIKH_MULA <'=>date('Y-m-d')));
        $query = $this->db->get();
        return $query->result();
        $this->db->close();
    }

    public function get_user_details() {
        $id = $this->session->login['Userid'];
        $this->db->from('users');
        $this->db->join('jabatan','users.ID_JABATAN = jabatan.ID_JABATAN', 'left');
        $this->db->join('bahagian','users.ID_BAHAGIAN = bahagian.ID_BAHAGIAN', 'left');
        $this->db->join('unit','users.ID_UNIT = unit.ID_UNIT', 'left');
        $this->db->where(array('users.USER_ID'=> $id));
        $query = $this->db->get();
        return $query->row();
        $this->db->close();
    }

     public function get_user_training($id_latihan = "") {
        $id = $this->session->login['Userid'];
        $this->db->from('users');
        $this->db->join('peserta','peserta.ID_USER = users.USER_ID', 'left');
        $this->db->join('latihan','peserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
        $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
        $this->db->where(array('users.USER_ID'=> $id));
        $this->db->where(array('latihan.ID_LATIHAN' => $id_latihan));
        $query = $this->db->get();
        return $query->row();
        $this->db->close();
    }

  public function get_penyelia(){
      $id = $this->session->login['Userid'];
      $sql = "SELECT users.* FROM `users` WHERE users.USER_ID !=$id";
      $query = $this->db->query($sql);
      $result = $query->result();
      $kursus_id = array('-PILIH-');
      $kursus_name = array('-PILIH-');
        
        for ($i = 0; $i < count($result); $i++) {
            
            array_push($kursus_id, $result[$i]->USER_ID);
            array_push($kursus_name, $result[$i]->NAME);
        }
        return $course_result = array_combine($kursus_id,$kursus_name);

   $this->db->close();
   }

    public function add_peserta_borang($data) { 
         //$penyelia =$this -> input ->post('penyelia');
         $objektif = $this-> input -> post('objektif');
         $sebab = $this-> input -> post('sebab');
         $penyampaian =$this -> input ->post('penyampaian');
         $komunikasi = $this-> input -> post('komunikasi');
         $interaksi =$this -> input ->post('interaksi');
         $respon = $this-> input -> post('respon');
         $difahami =$this -> input ->post('difahami');
         $kesan = $this-> input -> post('kesan');
         $aktiviti =$this -> input ->post('aktiviti');
         $bahan = $this-> input -> post('bahan');
         $tahap =$this -> input ->post('tahap');
         $kemahiran = $this-> input -> post('kemahiran');
         $tempoh =$this -> input ->post('tempoh');
         $lokasi = $this-> input -> post('lokasi');
         $penginapan =$this -> input ->post('penginapan');
         $makanan = $this-> input -> post('makanan');
         $dewan =$this -> input ->post('dewan');
         $urusetia = $this-> input -> post('urusetia');
         $faedah = $this-> input -> post('faedah');
         $cadangan = $this-> input -> post('cadangan');

        

       $data = array(
       'ID_LATIHAN' => $data['id_latihan'],
       'ID_USER' => $data['user'],
       'ID_PENYELIA' =>$data['penyelia'],
       'Objektif' => $objektif,
       'Sebab' => $sebab,
       'Penyampaian' => $penyampaian,
       'Komunikasi' => $komunikasi,
       'Interaksi' => $interaksi,
       'Respon' => $respon,
       'Difahami' => $difahami,
       'Kesan' => $kesan,
       'Aktiviti' => $aktiviti,
       'BahanKursus' => $bahan,
       'TahapPengetahuan' => $tahap,
       'Kemahiran' => $kemahiran,
       'TempohLatihan' => $tempoh,
       'Lokasi' => $lokasi,
       'Makanan' => $makanan,
       'DewanKuliah' => $dewan,
       'Urusetia' => $urusetia,
       'Faedah' => $faedah,
       'Cadangan' => $cadangan
     );

        $this-> db -> insert('penilaianpeserta',$data);
 
}

  public function update_peserta_borang($data){

    $this-> db ->set('STAT_BORANG','1'); 

    $this-> db ->where('ID_LATIHAN = '.$data['id_latihan'].' AND ID_USER = '. $data['user'].'');

    $this-> db ->update('peserta');

  }

     /*edited 14/1/16 */
     public function add_penyelia_borang($data) { 
         $tujuan =$this -> input ->post('tujuan');
         $objektif = $this-> input -> post('objektif');
         $peningkatan = $this-> input -> post('peningkatan');
         $perubahan =$this -> input ->post('perubahan');
         $taklimat =$this -> input ->post('taklimat');
         $bahankursus = $this-> input -> post('bahankursus');
         $aplikasi =$this -> input ->post('aplikasi');
         $ulasan = $this-> input -> post('ulasan');


       $data = array(
       'ID_LATIHAN' => $data['id_latihan'],
       'ID_USER' => $data['user_id'],
       'ID_PENYELIA' => $this->session->login['Userid'],
       'Tujuan' => $tujuan,
       'Objektif' => $objektif,
       'Peningkatan' => $peningkatan,
       'Perubahan' => $perubahan,
       'Taklimat' => $taklimat,
       'BahanKursus' => $bahankursus,
       'Aplikasi' => $aplikasi,
       'Ulasan' => $ulasan

     );

        $this-> db -> insert('penilaianpenyelia',$data);
 
}

 public function add_penyelia_borang1($data) { 
         $tujuan =$this -> input ->post('tujuan');
         $objektif = $this-> input -> post('objektif');
         $peningkatan = $this-> input -> post('peningkatan');
         $perubahan =$this -> input ->post('perubahan');
         $taklimat =$this -> input ->post('taklimat');
         $bahankursus = $this-> input -> post('bahankursus');
         $aplikasi =$this -> input ->post('aplikasi');
         $ulasan = $this-> input -> post('ulasan');


       $data = array(
       'ID_LATIHAN' => $data['id_latihan'],
       'ID_USER' => $data['user_id'],
       'ID_PENYELIA' => $data['penyelia'],
       'Tujuan' => $tujuan,
       'Objektif' => $objektif,
       'Peningkatan' => $peningkatan,
       'Perubahan' => $perubahan,
       'Taklimat' => $taklimat,
       'BahanKursus' => $bahankursus,
       'Aplikasi' => $aplikasi,
       'Ulasan' => $ulasan

     );

        $this-> db -> insert('penilaianpenyelia',$data);
 
}


  public function update_penyelia_borang($data){

    $this-> db ->set('STAT_PENILAI','1'); 

    $this-> db ->where('ID_LATIHAN = '.$data['id_latihan'].' AND ID_USER = '. $data['user_id'].'');

    $this-> db ->update('penilaianpeserta');

  }
 /* Allif */
  public function getFormPdf($usrid,$usrlatihan){
    //$this->db->select();
    $this->db->from('penilaianpeserta');
    $this->db->where(array('ID_USER' => $usrid,'ID_LATIHAN' => $usrlatihan));
    $res = $this->db->get();
    return $res->row();
  }

   public function getFormPdfFasi($usrlatihan,$usrid){
    //$this->db->select();
    $this->db->from('penilaianpenyelia');
    $this->db->where(array('ID_USER' => $usrid,'ID_LATIHAN' => $usrlatihan,'ID_PENYELIA' => $this->session->login['Userid']));
    $res = $this->db->get();
    return $res->row();
  }

   public function getHistoryUsr() {
        $id = $this->session->login['Userid'];
        $this->db->select('TAJUK_KURSUS,ID_USER,latihan.ID_LATIHAN,TARIKH_MULA,TARIKH_AKHIR');
        $this->db->from('peserta');
        $this->db->join('latihan','peserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
        $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
        $this->db->where(array('peserta.ID_USER'=> $id,'peserta.STAT_BORANG ='=> '1'));
        $query = $this->db->get();
        return $query->result();
        $this->db->close();
    }

  public function getHistorySup() {
        $id = $this->session->login['Userid'];
        $this->db->select('TAJUK_KURSUS,ID_USER,latihan.ID_LATIHAN,TARIKH_MULA,TARIKH_AKHIR,users.NAME');
        $this->db->from('penilaianpeserta');
        $this->db->join('users','users.USER_ID=penilaianpeserta.ID_USER','left');
        $this->db->join('latihan','penilaianpeserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
        $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
        $this->db->where(array('penilaianpeserta.ID_PENYELIA'=> $id,'penilaianpeserta.STAT_PENILAI ='=> '1'));
        // $this->db->where(array('latihan.ID_LATIHAN' => $id_latihan));
        $query = $this->db->get();
        return $query->result();
       $this->db->close();
    }

     public function getHourDay() {
        $id = $this->session->login['Userid'];
        $this->db->select('COUNT(ID_USER) AS CNTCOURSE,
                            (SELECT COUNT(ID_USER) FROM peserta WHERE ID_USER ='.$id.' AND 
                              STAT_BORANG = 0 AND NOW() > STR_TO_DATE(KURSUS_MULA, "%Y-%m-%d"))NOTFILL,
                          (SELECT SUM(BILANGAN_JAM) FROM peserta WHERE (ID_USER ='.$id.' AND 
                              STAT_BORANG = 1) OR (ID_USER ='.$id.' AND STAT_BORANG = 2) )JAM');
        $this->db->from('peserta');
        $this->db->where(array('peserta.ID_USER'=> $id,'peserta.STATUS_PENAWARAN !=' => '2'));
        $this->db->group_by('ID_USER');
        $query = $this->db->get();
        return $query->row();
        $this->db->close();
    }

    public function getCourseByYear($year){
        $this->db->select('latihan.ID_LATIHAN,kursus.TAJUK_KURSUS');
        $this->db->from('latihan');
        $this->db->join('kursus', 'kursus.ID_KURSUS = latihan.ID_KURSUS');
        $this->db->join('penilaianpeserta', 'penilaianpeserta.ID_LATIHAN = latihan.ID_LATIHAN');
        $this->db->where(array('latihan.STATUS !='=>'1'));
        $this->db->like(array('YEAR(TARIKH_MULA)' => $this->input->post('year')));
        $this->db->group_by('latihan.ID_LATIHAN');
        $this->db->order_by('latihan.ID_LATIHAN');
        $res = $this->db->get();
        return $res -> result();
    }

    public function getCourseByYearSup($year){
        $this->db->select('latihan.ID_LATIHAN,kursus.TAJUK_KURSUS');
        $this->db->from('latihan');
        $this->db->join('kursus', 'kursus.ID_KURSUS = latihan.ID_KURSUS');
        $this->db->join('penilaianpenyelia', 'penilaianpenyelia.ID_LATIHAN = latihan.ID_LATIHAN');
        $this->db->where(array('latihan.STATUS !='=>'1'));
        $this->db->like(array('YEAR(TARIKH_MULA)' => $this->input->post('year')));
        $this->db->group_by('latihan.ID_LATIHAN');
        $this->db->order_by('latihan.ID_LATIHAN');
        $res = $this->db->get();
        return $res -> result();
    }   
  /* Allif */
}

?>
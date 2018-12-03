<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Adminvaluation extends CI_Model
{ 
    public function __construct() {
        parent::__construct();
        
        /*load Session / Validation */
        $this->load->library('session');
}

   public function get_course(){

 	  $sql = "SELECT latihan.*,kursus.* FROM `latihan` JOIN `kursus` ON kursus.ID_KURSUS = latihan.ID_KURSUS WHERE latihan.STATUS = 0 ORDER BY TAJUK_KURSUS ASC";
      $query = $this->db->query($sql);
      $result = $query->result();
      $kursus_id = array('');
      $kursus_name = array('-PILIH-');
        
        for ($i = 0; $i < count($result); $i++) {
            
            array_push($kursus_id, $result[$i]->ID_LATIHAN);
            array_push($kursus_name, $result[$i]->TAJUK_KURSUS );
        }
        return $course_result = array_combine($kursus_id,$kursus_name);

   $this->db->close();
   }

 public function match_date_peserta($data){
  	$this->db->from('penilaianpeserta');
  	// $this->db->join('peserta','peserta.ID_LATIHAN = penilaianpeserta.ID_LATIHAN AND peserta.STAT_BORANG = 1');
  	$this->db->join('latihan','latihan.ID_LATIHAN = penilaianpeserta.ID_LATIHAN');
  	$this->db->join('kursus','kursus.ID_KURSUS =latihan.ID_KURSUS');
  	// $this->db->where(array('peserta.STAT_BORANG='=>'1'));
    $this->db->where(array('latihan.ID_latihan' => $data['nama_kursus']));
  	$query = $this->db->get();
    return $query->result();
    $this->db->close();
  }

    public function count_participant($data){
    $this->db->select('COUNT(peserta.ID_LATIHAN) AS JUMLAH');
    $this->db->from('peserta');
    $this->db->join('latihan','latihan.ID_LATIHAN = peserta.ID_LATIHAN');
    $this->db->join('kursus','kursus.ID_KURSUS =latihan.ID_KURSUS');
    $this->db->group_by('peserta.ID_LATIHAN');
    $this->db->where(array('latihan.ID_latihan' => $data['nama_kursus']));
    $query = $this->db->get();
    return $query->row();
    $this->db->close();
  }
    
  public function match_date_penyelia($data){
    $this->db->select('`users`.`ID_JABATAN` AS  `groupjab` , GROUP_CONCAT( users.USER_ID ) ,
                        GROUP_CONCAT( users.NAME ) AS pesertaName,
                        GROUP_CONCAT( penilaianpenyelia.tujuan ) AS fasitujuan,
                        GROUP_CONCAT( penilaianpenyelia.Objektif ) AS fasiobjektif,
                        GROUP_CONCAT( penilaianpenyelia.Peningkatan ) AS fasipeningkatan,
                        GROUP_CONCAT( penilaianpenyelia.Perubahan ) AS fasiperubahan,
                        GROUP_CONCAT( penilaianpenyelia.Taklimat ) AS fasitaklimat,
                        GROUP_CONCAT( penilaianpenyelia.BahanKursus ) AS fasibahan,
                        GROUP_CONCAT( penilaianpenyelia.Aplikasi ) AS fasiaplikasi,
                        GROUP_CONCAT( penilaianpenyelia.Ulasan ) AS fasiulasan,
                        GROUP_CONCAT( usrSup.NAME ) AS fasipenyelia,
                        kursus.TAJUK_KURSUS ,
                        latihan.TARIKH_MULA ,
                        latihan.TARIKH_AKHIR,
                        latihan.MASA_MULA,
                        latihan.MASA_AKHIR,
                        latihan.TEMPAT,
                        latihan.KATEGORI,
                        latihan.JENIS_LATIHAN,
                        jabatan.GE_KETERANGAN_JABATAN');
    $this->db->from('penilaianpenyelia');
    $this->db->join('users','users.USER_ID = penilaianpenyelia.ID_USER');
    $this->db->join('(SELECT USER_ID,NAME FROM users)usrSup','usrSup.USER_ID = penilaianpenyelia.ID_PENYELIA');
    //$this->db->join('penilaianpeserta','penilaianpeserta.ID_USER = penilaianpenyelia.ID_USER');
    $this->db->join('jabatan','jabatan.ID_JABATAN = users.ID_JABATAN');
    $this->db->join('latihan','latihan.ID_LATIHAN = penilaianpenyelia.ID_LATIHAN');
    $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS');
    $this->db->where(array('latihan.ID_latihan' => $data['nama_kursus']));
    $this->db->group_by('users.ID_JABATAN');
    $query = $this->db->get();
    return $query->result();
    $this->db->close();
  }
    public function overall_valuation($data){
    $this->db->select('COUNT(peserta.ID_USER) AS JUMPARTY, latihan.TARIKH_MULA,latihan.TARIKH_AKHIR,kursus.TAJUK_KURSUS,jabatan.GE_KETERANGAN_JABATAN');
    $this->db->from('peserta');
    $this->db->join('users','users.USER_ID = peserta.ID_USER');
    $this->db->join('jabatan','jabatan.ID_JABATAN = users.ID_JABATAN');
    $this->db->join('latihan','latihan.ID_LATIHAN = peserta.ID_LATIHAN');
    $this->db->join('kursus','kursus.ID_KURSUS =latihan.ID_KURSUS');
    $this->db->where(array('latihan.TARIKH_MULA >'=> $data['tarikh_mula'],'latihan.TARIKH_AKHIR < ' => $data['tarikh_akhir'], 'latihan.ID_latihan' => $data['nama_kursus']));
    $this->db->group_by('jabatan.GE_KETERANGAN_JABATAN');
    $query = $this->db->get();
    return $query->result();
    $this->db->close();
  }
    
    /* Allif */  
    public function  getMonthlyAttndRpt($date=""){
      $this->db->select('jabatan.ID_JABATAN,jabatan.GE_KETERANGAN_JABATAN,jabatan.GE_KOD_JABATAN,
                          GROUP_CONCAT(peserta.ID_USER) AS PARTYID,
                          GROUP_CONCAT(peserta.BILJAM) AS PARTYHOUR,
                          GROUP_CONCAT(users.HR_KUMPULAN) AS PARTYGROUP');
      $this->db->from('(SELECT SUM(peserta.BILANGAN_JAM) AS BILJAM,peserta.* FROM peserta WHERE peserta.KURSUS_MULA LIKE "%'.$date.'%" GROUP BY ID_USER ) peserta');
      // $this->db->from('peserta');
      $this->db->join('latihan','latihan.ID_LATIHAN = peserta.ID_LATIHAN');
      $this->db->join('users','users.USER_ID = peserta.ID_USER');
      $this->db->join('jabatan','jabatan.GE_KOD_JABATAN = users.ID_JABATAN');
      $this->db->where(array('latihan.STATUS ='=>'0','peserta.STATUS_GANTI != '=>'2'));
      $this->db->group_by('jabatan.GE_KOD_JABATAN');
      $res = $this->db->get();
      return $res -> result();
      $this->db->close();
    }

  
    public function getMonthlyAttndDetail(){
      $this->db->select('jabatan.GE_KETERANGAN_JABATAN,SUM(peserta.BILANGAN_JAM) AS HOUR,users.USER_ID,
                         users.HR_KUMPULAN');
      $this->db->from('peserta');
      $this->db->join('latihan','latihan.ID_LATIHAN = peserta.ID_LATIHAN');
      $this->db->join('users','users.USER_ID = peserta.ID_USER');
      $this->db->join('jabatan','jabatan.GE_KOD_JABATAN = users.ID_JABATAN');
      $this->db->where(array('users.ID_JABATAN' => $this->uri->segment(3),'peserta.STATUS_GANTI != '=>'2','latihan.STATUS ='=>'0'));
      $this->db->group_by('users.USER_ID');
      $res = $this->db->get();
      return $res->result();
      $this->db->close();
    }
      /* --Allif-- */  

      /*Ain*/

  public function getOverallAttndDetail(){
      $this->db->select('jabatan.GE_KETERANGAN_JABATAN,peserta.BILANGAN_JAM,SUM(peserta.BILANGAN_JAM) AS HOUR,users.USER_ID,
                        users.HR_KUMPULAN');
      $this->db->from('peserta');
      $this->db->join('latihan','latihan.ID_LATIHAN = peserta.ID_LATIHAN');
      $this->db->join('users','users.USER_ID = peserta.ID_USER');
      $this->db->join('jabatan','jabatan.ID_JABATAN = users.ID_JABATAN');
      $this->db->where(array('peserta.STATUS_GANTI != '=>'2','latihan.STATUS ='=>'0'));
      $this->db->group_by('users.USER_ID');
      $res = $this->db->get();
      return $res->result();
      $this->db->close();
    }

//Update 7/5/2016
  public function peserta_feedback(){
    $this->db->from('peserta');
    $this->db->join('latihan','latihan.ID_LATIHAN = peserta.ID_LATIHAN');
    $this->db->join('kursus','kursus.ID_KURSUS =latihan.ID_KURSUS');
    $this->db->where(array('latihan.PENILAIAN ='=>'0'));
    $this->db->where(array('peserta.STAT_BORANG ='=>'0'));
    $this->db->where(array('peserta.STATUS_PENAWARAN !='=> '1'));
    $this->db->where(array('latihan.TARIKH_MULA <'=>date('Y-m-d')));
    $this->db->where(array('latihan.TARIKH_MULA > NOW()'));
    $this->db->group_by('latihan.ID_LATIHAN');
    $query = $this->db->get();
    return $query->result();
    $this->db->close();
  }

  public function name_peserta($id_latihan = ""){
  $this->db->from('peserta');
  $this->db->join('users','peserta.ID_USER = users.USER_ID','left');
  $this->db->join('latihan','peserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
  $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
  $this->db->where(array('latihan.ID_LATIHAN' => $id_latihan));
  $this->db->where(array('peserta.STAT_BORANG ='=>'0'));
  $query = $this->db->get();
  return $query->result();
  $this->db->close();
}

   public function get_peserta_training($id_latihan = "") {
        $this->db->from('users');
        $this->db->join('peserta','peserta.ID_USER = users.USER_ID', 'left');
        $this->db->join('latihan','peserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
        $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
        $this->db->where(array('latihan.ID_LATIHAN' => $id_latihan));
        $query = $this->db->get();
        return $query->row();
        $this->db->close();
    }

    public function get_userid($id_latihan = "",$id_user = "") {
        $this->db->from('peserta');
        $this->db->join('users','users.USER_ID=peserta.ID_USER','left');
        $this->db->join('jabatan','users.ID_JABATAN = jabatan.ID_JABATAN', 'left');
        $this->db->join('bahagian','users.ID_BAHAGIAN = bahagian.ID_BAHAGIAN', 'left');
        $this->db->join('unit','users.ID_UNIT = unit.ID_UNIT', 'left');
        $this->db->join('latihan','peserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
        $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
        $this->db->where(array('peserta.ID_USER'=> $id_user));
        $this->db->where(array('latihan.ID_LATIHAN' => $id_latihan));
        $query = $this->db->get();
        return $query->row();
        $this->db->close();
    }


 /* UPDATE 5/5/2016 */
    public function penyelia_feedback(){
    $this->db->select('TAJUK_KURSUS,TARIKH_MULA,TARIKH_AKHIR,kursus.ID_KURSUS,latihan.ID_LATIHAN,
                    DATE_ADD(TARIKH_MULA,INTERVAL 2 MONTH) AS TKH_MULA,
                    NOW() AS TODAY');
    $this->db->from('penilaianpeserta');
    $this->db->join('latihan','latihan.ID_LATIHAN = penilaianpeserta.ID_LATIHAN');
    $this->db->join('kursus','kursus.ID_KURSUS =latihan.ID_KURSUS');
    $this->db->where(array('penilaianpeserta.STAT_PENILAI ='=>'0'));
    $this->db->where(array('latihan.PENILAIAN ='=>'0'));
      $this->db->having('TODAY > TKH_MULA');
    $this->db->group_by ('latihan.ID_LATIHAN');
    $query = $this->db->get();
    return $query->result();
    $this->db->close();
  }

public function name_penyelia($id_latihan = ""){
  //$this->db->select('penilaianpeserta.ID_PENYELIA AS penyelia,users.NAMA_PENYELIA AS name,users.NAME,users.USER_ID,latihan.ID_LATIHAN,kursus.TAJUK_KURSUS');
  $this->db->from('penilaianpeserta');
  $this->db->join('users','users.USER_ID=penilaianpeserta.ID_USER','left');
  $this->db->join('latihan','penilaianpeserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
  $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
  $this->db->group_by('users.NAMA_PENYELIA');
  $this->db->where(array('latihan.ID_LATIHAN' => $id_latihan));
  $this->db->where(array('penilaianpeserta.STAT_PENILAI='=>'0'));
  $this->db->where(array('users.ID_PENYELIA !='=>'0'));
  $query = $this->db->get();
  return $query->result();
  $this->db->close();
}

 public function name_penyelia_user($id_latihan = "",$id_penyelia=""){
  $this->db->from('penilaianpeserta');
  $this->db->join('users','users.USER_ID=penilaianpeserta.ID_USER','left');
  $this->db->join('latihan','penilaianpeserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
  $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
  $this->db->where(array('latihan.ID_LATIHAN' => $id_latihan));
  $this->db->where(array('penilaianpeserta.ID_PENYELIA' => $id_penyelia));
  $this->db->where(array('penilaianpeserta.STAT_PENILAI='=>'0'));
  $query = $this->db->get();
  return $query->result();
  $this->db->close();
  }

   public function get_user_fasi_details($id_latihan = "",$id_user = "",$penyelia="") {
        //$this->db->select('penilaianpeserta.ID_PENYELIA AS penyelia,users.NAME,users.USER_ID,latihan.ID_LATIHAN,kursus.TAJUK_KURSUS');
        $this->db->from('penilaianpeserta');
        $this->db->join('users','users.USER_ID=penilaianpeserta.ID_USER','left');
        $this->db->join('jabatan','users.ID_JABATAN = jabatan.ID_JABATAN', 'left');
        $this->db->join('bahagian','users.ID_BAHAGIAN = bahagian.ID_BAHAGIAN', 'left');
        $this->db->join('latihan','penilaianpeserta.ID_LATIHAN = latihan.ID_LATIHAN','left');
        $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS','left');
        $this->db->where(array('latihan.ID_LATIHAN' => $id_latihan));
        $this->db->where(array('users.ID_PENYELIA' => $penyelia));
        $this->db->where(array('penilaianpeserta.ID_USER'=> $id_user));
        $this->db->where(array('users.ID_PENYELIA !='=>'0'));
        $query = $this->db->get();
        return $query->row();
        $this->db->close();
}

     /*--Ain--*/
    /* Allif */  
  /*USE THIS FUNCTION FOR FETCH DATA TOO DROPDOWN */
  public function getUsersFromDep(){
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

  public function getJabatan(){
    $this->db->select('GE_KOD_JABATAN,GE_KETERANGAN_JABATAN');
    $this->db->from('jabatan');
    $res = $this->db->get();
    $resArr =  $res -> result();
    $id = array('');
    $name = array('-Sila Pilih-');

    for ($i = 0; $i < count($resArr); $i++) {
            array_push($id, $resArr[$i]->GE_KOD_JABATAN);
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
    public function getPartyHourDep($date="",$department="",$section="",$units=""){
      $this->db->select('users.NAME,users.HR_NO_PEKERJA,
                         GROUP_CONCAT(peserta.BILANGAN_JAM) AS PARTYHOUR,
                         GROUP_CONCAT(kursus.TAJUK_KURSUS) AS COURSE,');
      $this->db->from('peserta');
      $this->db->join('users','users.USER_ID = peserta.ID_USER');
      $this->db->join('jabatan','jabatan.ID_JABATAN = users.ID_JABATAN');
      $this->db->join('latihan','latihan.ID_LATIHAN = peserta.ID_LATIHAN');
      $this->db->join('kursus','kursus.ID_KURSUS =latihan.ID_KURSUS');
      $this->db->like('users.ID_JABATAN',$department);
      $this->db->like('users.ID_BAHAGIAN',$section);
      $this->db->like('users.ID_UNIT',$units);
      $this->db->like('latihan.TARIKH_MULA',$date);
      // $this->db->where(array('latihan.TARIKH_MULA'=>''));
      $this->db->group_by ('peserta.ID_USER');
      $res = $this->db->get();
      return $res->result();
      $this->db->close();
    }

    public function getFreqRpt($start_date="",$end_date="",$department="",$section="",$units="",$days="",$group=""){
      $this->db->select('users.NAME,users.HR_NO_PEKERJA,SUM(peserta.BILANGAN_JAM) AS TOTAL,users.HR_KUMPULAN,latihan.TARIKH_MULA,latihan.TARIKH_AKHIR,
                         GROUP_CONCAT(peserta.BILANGAN_JAM) AS PARTYHOUR,
                         GROUP_CONCAT(kursus.TAJUK_KURSUS) AS COURSE');
      $this->db->from('peserta');
      $this->db->join('users','users.USER_ID = peserta.ID_USER');
      $this->db->join('jabatan','jabatan.ID_JABATAN = users.ID_JABATAN');
      $this->db->join('latihan','latihan.ID_LATIHAN = peserta.ID_LATIHAN');
      $this->db->join('kursus','kursus.ID_KURSUS =latihan.ID_KURSUS');
      $this->db->like('users.ID_JABATAN',$department);
      $this->db->like('users.ID_BAHAGIAN',$section);
      $this->db->like('users.ID_UNIT',$units);
      $this->db->like('users.HR_KUMPULAN',$group);
      $this->db->like('latihan.TARIKH_MULA',$start_date);
      $this->db->like('latihan.TARIKH_AKHIR',$end_date);
      $this->db->group_by ('peserta.ID_USER');
      $this->db->order_by('users.HR_KUMPULAN');
      $this->db->having ('TOTAL '.$days.'');
      $res = $this->db->get();
      return $res->result();
      $this->db->close();
    }

    /* --Allif-- */  
    public function OverallRpt($start="",$end="",$depart="",$category="",$title=""){
      $this->db->select('latihan.TARIKH_MULA,latihan.TARIKH_AKHIR,latihan.JENIS_LATIHAN,kursus.TAJUK_KURSUS,COUNT(peserta.ID_USER) AS JUMPARTY,
                         GROUP_CONCAT(jabatan.GE_KETERANGAN_JABATAN) AS DEPARTMENT');
      $this->db->from('peserta');
      $this->db->join('users','users.USER_ID=peserta.ID_USER');
      $this->db->join('jabatan','jabatan.ID_JABATAN = users.ID_JABATAN');
      $this->db->join('latihan','latihan.ID_LATIHAN = peserta.ID_LATIHAN');
      $this->db->join('kursus','kursus.ID_KURSUS =latihan.ID_KURSUS');
      $this->db->like('users.ID_JABATAN',$depart);
      $this->db->like('latihan.JENIS_LATIHAN',$category);
      $this->db->like('latihan.TARIKH_MULA',$start);
      $this->db->like('latihan.TARIKH_AKHIR',$end);
      $this->db->like('latihan.ID_LATIHAN',$title);
      $this->db->group_by ('latihan.ID_LATIHAN');
      $res = $this->db->get();
      return $res->result();
      $this->db->close();

    }

}
?>
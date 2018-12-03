<?php
defined('BASEPATH') OR die('NO direct Script Allowed');

class ParticipateModel extends CI_Model
{
    
    public function __construct() {
        parent::__construct();
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
    
    public function getTraining($id = "") {
        
        $this->db->select('kursus.TAJUK_KURSUS,TARIKH_MULA,TARIKH_AKHIR,TEMPAT,URUSETIA,MASA_MULA,MASA_AKHIR,KATEGORI,JENIS_LATIHAN');
        $this->db->from('latihan');
        $this->db->join('kursus', 'kursus.ID_KURSUS = latihan.ID_KURSUS');
        $this->db->where(array('ID_LATIHAN' => $id));
        $res = $this->db->get();
        return $res->row();
    }
    
    public function checkUserAvail($id, $start, $end) {
        
        if(!empty($id)){
            
            foreach ($id as $mix) {
                
                $id_ex = explode("|", $mix);
                $this->db->select('KURSUS_MULA,KURSUS_AKHIR');
                $this->db->from('peserta');
                $this->db->where(array('ID_USER' => $id_ex[0], 'KURSUS_MULA <=' => $end, 'KURSUS_AKHIR >=' => $start));
                $res = $this->db->get();
            
                    if ($res->num_rows() > 0) {
                    return $result = array($res->row(), $id_ex[1]);
                    break;
                }
            }
        }

        return false;
    }
    
    public function addParticipant($data,$id,$penilaian="") {
        date_default_timezone_set('Asia/Kuala_Lumpur');
        /* stat borang($penilaian) = 2 for Non Assesment Course */
         if(!empty($id)){
                foreach ($id as $userid) {
                    $userex = explode("|", $userid);
                    if($penilaian == 2){
                        $data_arr = array('ID_LATIHAN' => $data['id'],
                                      'ID_USER' => $userex[0],
                                      'KURSUS_MULA' => $data['start'],
                                      'KURSUS_AKHIR' => $data['end'],
                                      'CREATED_DATE' => date('Y-m-d H:i:s'),  
                                      'STAT_BORANG' => '2' 
                                    );
                  
                    }else{
                        $data_arr = array('ID_LATIHAN' => $data['id'],
                                          'ID_USER' => $userex[0],
                                          'KURSUS_MULA' => $data['start'],
                                          'KURSUS_AKHIR' => $data['end'],
                                          'CREATED_DATE' => date('Y-m-d H:i:s')
                                        );
                    }
                        /*insert */
                        $this->db->insert('peserta', $data_arr);
                }
        }
        return false;
    }
        
        public function checkUsrOff($id){
        
        $this->db->select('users.NAME,users.EMAIL,users.HR_NO_PEKERJA,users.USER_ID,jabatan.GE_KETERANGAN_JABATAN,
                           peserta.KURSUS_MULA,peserta.ID_LATIHAN,peserta.KURSUS_AKHIR,users.HR_NO_KPBARU,peserta.CADANGAN,
                           peserta.STATUS_PENAWARAN,peserta.SBB_TOLAK,kursus.TAJUK_KURSUS,latihan.PENILAIAN');
        $this->db->from('peserta');
        $this->db->join('users','users.USER_ID=peserta.ID_USER');
        $this->db->join('latihan','latihan.ID_LATIHAN=peserta.ID_LATIHAN');
        $this->db->join('kursus','kursus.ID_KURSUS=latihan.ID_KURSUS');
        $this->db->join('jabatan','jabatan.GE_KOD_JABATAN=users.ID_JABATAN');
        $this->db->where(array('peserta.ID_LATIHAN'=>$id,'peserta.STATUS_GANTI !=' => 2));
        $res = $this->db->get();
        return $res->result();
    }

    /*update 1/18/16 */
    public function alterPartyStat($idusr,$idrep){
        /*Status Penawaran (0 hadir,1 tolak,2 ganti,3 Terima)*/
        /*Status Ganti (1 menggantikn,2 diganti,0 default)*/

        $data = array('ID_GANTI' => $idrep,
                      'STATUS_PENAWARAN'=> 2,
                      'STATUS_GANTI'=> 2);
        $this->db->where(array('ID_USER' => $idusr,'ID_LATIHAN' => $this->uri->segment(3)));
        $this->db->update('peserta',$data);
    }

    /*update 22/2/16  insert to db*/ 
    public function grabPartyByid(){
 
        $data = array('ID_LATIHAN' => $this->uri->segment(3),
                      'ID_USER' => $this->input->post('user'),
                      'STATUS_PENAWARAN' => 2,
                      'ID_GANTI' => $this->input->post('previd'),
                      'STATUS_GANTI' => 1,
                      'STAT_BORANG' => $this->input->post('penilaian'),
                      'KURSUS_MULA' => $this->input->post('start'),
                      'KURSUS_AKHIR' => $this->input->post('end'),
                      'CREATED_DATE' => date('Y-m-d H:i:s'));
        $this->db->insert('peserta',$data);
    }

    public function delParty($data){
        foreach ($data as $mixid) {
            $id = explode("|", $mixid);
            $this->db->delete('peserta',array('ID_USER'=>$id[0]));
            $this->db->delete('peserta',array('ID_GANTI'=>$id[0]));
        }
    }

    /*1/20/16 */
    public function delByTrainId($id){
        $this->db->delete('peserta',array('ID_LATIHAN' => $id));
    }

    /*10/02/16*/
    public function getUsrHistoryAjx($idUser){
        $this->db->from('peserta');
        $this->db->join('latihan','latihan.ID_LATIHAN = peserta.ID_LATIHAN');
        $this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS');
        $this->db->where(array('ID_USER' => $idUser));
        $res = $this->db->get();
        return $res->result();
    }

}
?>
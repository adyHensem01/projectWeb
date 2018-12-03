<?php defined('BASEPATH') OR exit('No Direct Script Allowed'); 

class HomeModel extends CI_Model{

	public function __construct(){
		parent::__construct();
	}

	public function getCourseByMonth(){
		$this->db->from('latihan');
		$this->db->join('kursus','kursus.ID_KURSUS = latihan.ID_KURSUS');
		$this->db->join('peserta','peserta.ID_LATIHAN = latihan.ID_LATIHAN AND peserta.STATUS_PENAWARAN != 1 AND peserta.STATUS_PENAWARAN != 3 AND STATUS_GANTI != 2' ,'left');
		if($this->session->login['Type'] == 2){
			$this->db->where(array('peserta.ID_USER'=>$this->session->login['Userid'],'peserta.SBB_TOLAK='=> '','latihan.TARIKH_MULA > '=> date('Y-m-d')));
		}else{
			$this->db->where(array('month(latihan.TARIKH_MULA)' => date('m')));
		}
		$this->db->group_by('latihan.ID_LATIHAN');
		$res = $this->db->get();
		return $res->result();
	}

	public function addCause($id_usr,$id_latihan){
		$cause = $this->input->post('sbb');
		$suggest = $this->input->post('suggest');
		$this->db->where(array('ID_USER' => $id_usr,'ID_LATIHAN' => $id_latihan));
		$this->db->update('peserta',array('SBB_TOLAK' => $cause,'CADANGAN' => $suggest,'STATUS_PENAWARAN'=>'1'));
	}

	public function alterParty($id_usr,$id_latihan){
		$this->db->where(array('ID_USER' => $id_usr,'ID_LATIHAN' => $id_latihan));
		$this->db->update('peserta',array('STATUS_PENAWARAN'=>'3'));
	}

	public function getPartyCount($id_train){
		$this->db->select('COUNT(ID_PESERTA) AS JUM');
		$this->db->from('peserta');
		$this->db->where(array('ID_LATIHAN' => $id_train,'peserta.STATUS_GANTI !=' => '2 ','peserta.STATUS_PENAWARAN !=' => '1'));
		$res = $this->db->get();
		return $res->row();
	}

	/*15/1/16 Set Noti*/
	public function addNoti($msj,$type,$link,$usrid="",$id_latihan="",$tkh_mula="",$idpeserta=""){
		$this->db->insert('notifikasi',array('MSG' => $msj,'TYPE' => $type,'LINK' => $link,'ID_USER' => $usrid,'ID_LATIHAN' => $id_latihan,'TKH_KURSUS' => $tkh_mula,'ID_PESERTA' => $idpeserta));
	}

	public function delNoti($usrID,$courseID=""){
        
        if(is_array($usrID)){
	         foreach ($usrID as $mixid) {
	            $id = explode("|", $mixid);
	            $this->db->delete('notifikasi', array('ID_USER' => $id[0]));
	        }
        }else{
        	 $this->db->delete('notifikasi', array('ID_USER' => $usrID,'ID_LATIHAN' => $courseID));
        }
    }

    public function delNotiSup($idnoti){
       $this->db->delete('notifikasi', array('ID_NOTI' => $idnoti));
    }

    public function delNotiSupIDPeserta($idpeserta,$idsup){
       $this->db->delete('notifikasi', array('ID_NOTI' => $idpeserta,'ID_PESERTA' => $idsup));
    }

    public function getUsersDrop(){
    	$this->db->select('NAME,HR_NO_PEKERJA');
        $this->db->from('users');
        $this->db->where(array('USER_ID !='=> $this->session->login['Userid'],'HR_KAKITANGAN_IND !='=> 'Y'));
        $this->db->order_by('NAME');
        $res = $this->db->get();
        $result = $res->result();
        $userid = array('Tiada Peganti');
      	$username = array('-Tiada Peganti-');

        for ($i = 0; $i < count($result); $i++) {
          
            array_push($userid, $result[$i]->NAME);
            array_push($username, $result[$i]->NAME);
        }
        return $course_result = array_combine($userid,$username);
   		
   		$this->db->close();
    }

    public function getUsrByDepart($limit = 0, $page = 0, $string = ""){
    	 $currpage = ($page - 1) * $limit;

        $this->db->select("`NAME`,`HR_NO_PEKERJA`,`USER_ID`");
    	$this->db->from('users');
    	
        /*Depart Session */
        $departID = $this->session->login['Departid'];
        $sectionID = $this->session->login['Sectionid'];
        $unitID = $this->session->login['Unitid'];

        /*lvl access 
		type 4 = Bahagian
		type 5 = Jabatan
    	*/
    	if($this->session->login['Type'] == '4'){	
    		$this->db->where(array('ID_JABATAN'=>$departID,'ID_BAHAGIAN' => $sectionID));
    	}elseif($this->session->login['Type'] == '5'){
    		$this->db->where(array('ID_JABATAN'=>$departID));
    	}

        if(!empty($string)){
                $this->db->like("CONCAT_WS('|',`NAME`,`HR_NO_PEKERJA`,`HR_NO_KPBARU`)",$string);
        }

        $this->db->limit($limit, $currpage);
        $this->db->order_by('NAME','ASC');
    	$res = $this->db->get();
    	return $res -> result();
    }

    public function UsrNumrow($string="") {
        $limit = 50;

        /*Depart Session */
        $departID = $this->session->login['Departid'];
        $sectionID = $this->session->login['Sectionid'];
        $unitID = $this->session->login['Unitid'];

        $this->db->from('users');

        /*lvl access 
		type 4 = Bahagian
		type 5 = Jabatan
    	*/
        if($this->session->login['Type'] == '4'){	
    		
    		$this->db->where(array('ID_JABATAN'=>$departID,'ID_BAHAGIAN' => $sectionID));
    	
    	}elseif($this->session->login['Type'] == '5'){
    		
    		$this->db->where(array('ID_JABATAN'=>$departID));
    	}

        if(!empty($string)){
                $this->db->like("CONCAT_WS('|',`NAME`,`HR_NO_PEKERJA`,`HR_NO_KPBARU`)",$string);
        }
        
        $query = $this->db->get();

        $query->result();
        
        $numrows = $query->num_rows();


        if(!empty($string) && $numrows > $limit){
            $this -> session -> searchUsr = $string;
        }

        return $numrows;
    }
}

?>
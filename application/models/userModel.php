<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }
    public function changemail($id, $email) {
        $data = array(
        'EMAIL' => $email
		);
        $this->db->where(array('USER_ID' => $id));
        $this->db->update('users',$data);
        
    }

    /*1/14/16 get Noti */
    public function getNotify(){
        $this->db->select('notifikasi.*,
                         (SELECT COUNT(TYPE) FROM notifikasi WHERE TYPE = 1 GROUP BY TYPE)NOTI'
                         );
        $this->db->from('notifikasi');
        $this->db->where(array("TYPE" => "1"));
        $this->db->where(array("TKH_KURSUS >" => date('Y-m-d')));
        $res = $this->db->get();
        return $res -> result();
    }

    public function getNotifySup($idusr){
        $this->db->select('notifikasi.*, DATE_ADD(TKH_KURSUS, INTERVAL 2 MONTH) AS TKH_MULA,NOW() AS TKHNOW,
                         (SELECT COUNT(TYPE) FROM notifikasi WHERE TYPE = 2 AND ID_USER = "'.$idusr.'" GROUP BY TYPE)NOTI'
                         );
        $this->db->from('notifikasi');
        $this->db->where(array("TYPE" => "2","ID_USER" => $idusr));
        $this->db->having('TKHNOW > TKH_MULA');
        $res = $this->db->get();
        return $res -> result();
    }

 }
?>
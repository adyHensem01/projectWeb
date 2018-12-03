<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Coursecode extends CI_Model
{
    public function __construct() {
        parent::__construct();
        
        /*load Session / Validation */
        $this->load->library('session');
    }
    
    public function get_byid($id){      
        $query = $this -> db -> get_where('kursus',array('ID_KURSUS' => $id));

        if($query->num_rows() > 0){
             
             return $query -> row();
         
         }else{
            
            $this -> db -> close();
         
         }


    }

    public function get_course($limit = 0, $page = 0, $string = "") {
        $currpage = ($page - 1) * $limit;
        
        $this->db->from('kursus');
        
        $this->db->where('STATUS', 0);
        
        if (!empty($string)) {
            
            foreach ($string as $key) {
                if (is_numeric($key)) {
                    
                    $this->db->like("ID_KURSUS", $key);
                } 
                elseif (is_string($key)) {
                    
                    $this->db->like("TAJUK_KURSUS", $key);
                }
            }
        }
        
        $this->db->limit($limit, $currpage);
        
        $this->db->order_by('ID_KURSUS','DESC');

        $query = $this->db->get();
        
        return $query->result();
        
        $this->db->close();
    }
    
    public function numrows($string = "") {
        $limit = 10;
        
        $this->db->from('kursus');
        
        $this->db->where('STATUS', 0);
        
        if (!empty($string)) {
            
            foreach ($string as $key)   {
                if (is_numeric($key)) {
                    
                    $this->db->like("ID_KURSUS", $key);
                } 
                elseif (is_string($key)) {
                    
                    $this->db->like("TAJUK_KURSUS", $key);
                }
            }
        }
        
        $query = $this->db->get();
        
        $query->result();
        
        $numrows = $query->num_rows();
        
        if (!empty($string) && $numrows > $limit) {
            $this->session->search = $string;
        }
        
        return $numrows;
    }
    

    public function add() {
        $jeniskursus = $this->input->post('jenis');
        $tajukkursus = $this->input->post('nama');
        $keterangan = $this->input->post('penerangan');
        $data = array('JENIS_KURSUS' => $jeniskursus ,'TAJUK_KURSUS' => $tajukkursus, 'KETERANGAN' => $keterangan);
        $this->db->insert('kursus', $data);
    }

    public function alter($id){
        $data = array(
                      'JENIS_KURSUS' => $this->input->post('jenis'),
                      'TAJUK_KURSUS' => $this->input->post('nama'),
                      'KETERANGAN' => $this->input->post('penerangan')
                      );

        $this->db->where('ID_KURSUS',$id);

        $this->db->update('kursus',$data);
    }


    
    public function del($status, $id) {
        $this->db->set('STATUS', $status);
        
        $this->db->where('ID_KURSUS', $id);
        
        $this->db->update('kursus');
    
    }
}
?>
<?php
class ExportDB extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }

    public function file(){
        if(in_array(strtoupper($this->uri->segment(3)),["JP","MILAN","NAYAN","MANSEE"])):
            $NAME=$this->db->database;
            $SQL_NAME = $NAME."_".date("d_m_Y_H_i_s").'.sql';
            $this->load->dbutil();
            $prefs = array(
                'format' => 'zip',
                'filename' => $SQL_NAME
                );
            $backup =& $this->dbutil->backup($prefs);    
            $db_name = $NAME."_".strtoupper($this->uri->segment(3))."_".date("d_m_Y_H_i_s").'.zip';    
            $save = 'assets/db/'.$db_name;
            $this->load->helper('file');
            write_file($save, $backup);
            $this->load->helper('download');
            force_download($db_name, $backup); 
        else:
            $this->load->view('page-403');
        endif;
    }
}
?>
<?php
class Site {
    var $db_conn = NULL;
    var $db_table = "sites";
    var $camera_table = "cameras";
    var $site_name = '';
    var $site_id = 0;
    var $site_active = FALSE;
    var $site_cameras = array();
    var $displayErrors = true;

    
    function __construct($db_conn, $site_id = 0, $load_site = TRUE) {
        $this->db_conn = $db_conn;
        if (!$this->db_conn)
            die(mysql_error($this->db_conn));
            //mysql_select_db($this->dbName, $this->db_conn) or die(mysql_error($this->db_conn));        

        if($site_id > 0 && $load_site) $this->load_site($site_id);
    }


    function get_user_sites($user_id, $active = TRUE) {
        $sql = "SELECT * FROM $this->db_table WHERE `user_id` = '$user_id' ";
        if($active) $sql .= " AND active = 1";
        $res = $this->query($sql, __LINE__);

        if (mysql_num_rows($res) > 0) {
            while( $row = mysql_fetch_assoc($res) ) {
                $site_data[] = $row;
            }
            return $site_data;
        } //eof mysql_num_rows($res) > 0
        return NULL;
    }


    function get_cam_details($cam_id, $active = TRUE) {
        $sql = "SELECT * FROM $this->camera_table WHERE `id` = '$cam_id' ";
        if($active) $sql .= " AND active = 1";
        $res = $this->query($sql, __LINE__);

        if (mysql_num_rows($res) > 0) {            
            $cam_data = mysql_fetch_assoc($res);
            return $cam_data;
        } //eof mysql_num_rows($res) > 0
        return NULL;
    }


    function load_site($site_id) {
        $sql = "SELECT * FROM $this->db_table WHERE `id` = '$site_id' ";
        $res = $this->query($sql, __LINE__);

        if (mysql_num_rows($res) == 1) {
            $row = mysql_fetch_assoc($res);
            
            $this->site_id = $site_id;
            $this->site_name = $row['description'];
            $this->site_active = $row['active'];
            $this->site_cameras = $this->fetch_site_cameras($site_id);

        } //eof mysql_num_rows($res) == 1
        return true;   
    }


    function fetch_site_cameras($site_id, $active = TRUE) {
        $site_cameras = array();
        $sql = "SELECT * FROM $this->camera_table WHERE `site_id` = '$site_id' ";
        if($active) $sql .= " AND active = 1";
        $res = $this->query($sql, __LINE__);

        if (mysql_num_rows($res) > 0) {
            while( $row = mysql_fetch_assoc($res) ) {
                $site_cameras[] = $row;
            }            
        } //eof mysql_num_rows($res) > 0
        return $site_cameras;
    }


    function get_site_name($site_id) {
        if($this->site_id != $site_id) 
            $this->load_site($site_id);

        return $this->site_name;
    }


    function get_site_active($site_id) {
        if($this->site_id != $site_id) 
            $this->load_site($site_id);
        
        return $this->site_active;
    }


    function get_site_cameras($site_id) {
        if($this->site_id != $site_id) 
            $this->load_site($site_id);
        
        return $this->site_cameras;
    }


    function query($sql, $line = 'Unknown') {
        //mysql_select_db($this->dbName);
        $res = mysql_query($sql, $this->db_conn);
        if (!$res)
            $this->error(mysql_error($this->db_conn), $line);
        return $res;
    }


    function escape($str) {
        $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
        $str = mysql_real_escape_string($str, $this->db_conn);
        return $str;
    }


    function error($error, $line = '', $die = true) {
        if ($this->displayErrors)
            echo '<b>Error: </b>' . $error . '<br /><b>Line: </b>' . ($line == '' ? 'Unknown' : $line) . '<br />';
        if ($die)
            exit;
        return false;
    }
}
?>
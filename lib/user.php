<?php
class User {
    var $dbName = 'inviso';
    var $dbHost = 'localhost';
    var $dbPort = 3306;
    var $dbUser = 'inviso';
    var $dbPass = 'nvruser';
    var $dbTable = 'users';
    var $session_table = "session_store";
    var $sessionVariable = 'id';
    var $session_cache_expire_time = 20;
    var $sessionInactive = 1200;
    var $passMethod = 'nothing';
    var $displayErrors = true;
    var $userID;
    var $dbConn;
    var $userData = array();
    function User($dbConn = '', $settings = '') {
        if (is_array($settings)) {
            foreach ($settings as $k => $v) {
                if (!isset($this->{$k}))
                    die('Property ' . $k . ' does not exists. Check your settings.');
                $this->{$k} = $v;
            } //$settings as $k => $v
        } //is_array($settings)
        $this->dbConn = ($dbConn == '') ? mysql_connect($this->dbHost . ":" . $this->dbPort, $this->dbUser, $this->dbPass) : $dbConn;
        if (!$this->dbConn)
            die(mysql_error($this->dbConn));
        mysql_select_db($this->dbName, $this->dbConn) or die(mysql_error($this->dbConn));
        if (!isset($_SESSION))
            session_start();
        if (!empty($_SESSION[$this->sessionVariable])) {
            $this->loadUser($_SESSION[$this->sessionVariable]);
        } //!empty($_SESSION[$this->sessionVariable])
    }
    function get_user_data($user_id) {
        $res = $this->query("SELECT * FROM `users` WHERE `id` = '$user_id' ", __LINE__);
        if (mysql_num_rows($res) == 1) {
            $Data = mysql_fetch_array($res);
            return $Data;
        } //mysql_num_rows($res) == 1
        return NULL;
    }
    function login($uname, $password, $remember = false, $loadUser = true) {
        $uname    = $this->escape($uname);
        $password = $originalPassword = $this->escape($password);
        switch (strtolower($this->passMethod)) {
            case 'sha1':
                $password = "SHA1('$password')";
                break;
            case 'md5':
                $password = "MD5('$password')";
                break;
            case 'nothing':
                $password = "'$password'";
        }//strtolower($this->passMethod)
        
        $sql = "SELECT * FROM `users` WHERE `email` = '$uname' AND `password` = $password LIMIT 1";
        $res = $this->query($sql, __LINE__);
        
        if (mysql_num_rows($res) == 0)
            return false;
        if ($loadUser) {
            $this->userData       = mysql_fetch_array($res);
            $this->userID         = $this->userData['id'];
            $_SESSION['ID']       = $this->userID;
            $_SESSION['username'] = $this->userData['email'];
            $_SESSION['role']     = $this->userData['role'];
            $userObj              = new stdClass();
            $userObj->role        = $this->userData['role'];
            $userObj->validated   = TRUE;
            $_SESSION['start']    = time();
            $session_id           = session_id();

            $this->writeSession($session_id);
        } //$loadUser
        return $userObj;
    }


    function sessionManager() {
        session_cache_expire($this->$session_cache_expire_time);
        session_start();
        if (isset($_SESSION['start'])) {
            $session_life = time() - $_SESSION['start'];
            if ($session_life > $this->$sessionInactive)
                $this->logout("login.php?logout=1");
        } //isset($_SESSION['start'])
        $_SESSION['start'] = time();
        return true;
    }

    function confirm_logged_in() {
        $sessID = session_id();
        $userID = $_SESSION['ID'];
        $sql = "SELECT *FROM {$this->session_table} WHERE sessionId='$sessID' AND sessionUserID='{$userID}' ";
        $res    = $this->query($sql, __LINE__);
        if (@mysql_num_rows($res) == 0)
            $this->logout("login.php");
        return true;
    }

    function writeSession($session_id) {
        $userID = $this->userID;
        $res    = $this->query("SELECT *FROM {$this->session_table} WHERE sessionUserID='{$userID}' ");
        if (@mysql_num_rows($res) == 1)
            $this->query("UPDATE {$this->session_table} SET sessionId='$session_id' WHERE sessionUserID='{$userID}'");
        else
            $this->query("INSERT INTO {$this->session_table} (sessionId,sessionUserID) VALUES('$session_id','{$userID}')");
        return true;
    }
    function logout($redirectTo = '') {
        setcookie($this->remCookieName, '', time() - 3600);
        $_SESSION['ID'] = '';
        session_destroy();
        $this->userData = '';
        
        if ($redirectTo != '') {
            // ob_start();
            // header('Location: ' . $redirectTo);
            // ob_end_flush();
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=login.php">';  
            exit;
        } //$redirectTo != '' && !headers_sent()
        
    }
    function randomPass($length = 10, $chrs = '1234567890qwertyuiopasdfghjklzxcvbnm') {
        for ($i = 0; $i < $length; $i++) {
            $pwd .= $chrs{mt_rand(0, strlen($chrs) - 1)};
        } //$i = 0; $i < $length; $i++
        return $pwd;
    }
    function get_attribute($attribute = NULL) {
        return empty($this->userData[$this->tbFields[$attribute]]) ? false : $this->userData[$this->tbFields[$attribute]];
    }
    function registerUser($data) {
        if (!is_array($data))
            $this->error('Data is not an array', __LINE__);
        switch (strtolower($this->passMethod)) {
            case 'sha1':
                $password = sha1($data['password']);
                break;
            case 'md5':
                $password = "MD5('" . $data['password'] . "')";
                break;
            case 'nothing':
                $password = $data['password'];
        } //strtolower($this->passMethod)
        foreach ($data as $k => $v)
            $data[$k] = "'" . $this->escape($v) . "'";
        $data['password'] = "\"" . $password . "\"";
        $sql              = "INSERT INTO `{$this->dbTable}` (`" . implode('`, `', array_keys($data)) . "`) VALUES (" . implode(", ", $data) . ")";
        $this->query($sql);
        return (int) mysql_insert_id($this->dbConn);
    }
    function updateUser($id, $updateData) {
        foreach ($updateData as $key => $value) {
            $field_name = $key;
            if( $field_name == "password" ) {
                switch (strtolower($this->passMethod)) {
                    case 'sha1':
                        $value = sha1($value);
                        break;
                    case 'md5':
                        $value = "MD5('" . $value . "')";
                        break;
                    case 'nothing':
                        $value = $value;
                } //strtolower($this->passMethod)                
            }//if( $field_name == "password" )
            $db_query   = "UPDATE {$this->dbTable} SET $field_name='$value' WHERE id='$id'";
            $result     = $this->query($db_query);
        } //$updateData as $key => $value
        if ($result)
            return true;
    }
    function query($sql, $line = 'Unknown') {
        mysql_select_db($this->dbName);
        $res = mysql_query($sql, $this->dbConn);
        if (!$res)
            $this->error(mysql_error($this->dbConn), $line);
        return $res;
    }
    function escape($str) {
        $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
        $str = mysql_real_escape_string($str, $this->dbConn);
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
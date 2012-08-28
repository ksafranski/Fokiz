<?php

/*
 * This file is part of the Fokiz Content Management System 
 * <http://www.fokiz.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class User {

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $id            = 0;   
    public $login         = "";
    public $password      = "";
    public $type          = 0; // 0 = Admin, 1 = Standard
    
    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////
    
    // ------------------------------------------------------------ //
    
    //////////////////////////////////////////////////////////////////
    // AUTHENTICATE
    //////////////////////////////////////////////////////////////////
    
    public function Authenticate(){
        $this->EncryptPassword();
        $rs = mysql_query("SELECT usr_id, usr_type FROM cms_users WHERE 
              usr_login='" . scrub($this->login) . "' 
              AND usr_password='" . $this->password . "'");
        if(mysql_num_rows($rs)==0){
            return 0;
        }else{
            $row = mysql_fetch_array($rs);
            $_SESSION['admin'] = $row['usr_id'];
            $_SESSION['admin_type'] = $row['usr_type'];
            return 1;
        }
    }
    
    //////////////////////////////////////////////////////////////////
    // SAVE
    //////////////////////////////////////////////////////////////////
    
    public function Save(){
        // Create Account ////////////////////////////////////////////
        if($this->id=="new"){
            $this->EncryptPassword();
            $rs = mysql_query("INSERT INTO cms_users (usr_login,usr_password,usr_type) VALUES ('" 
                . scrub($this->login) . "','" . $this->password . "'," . $this->type . ")");
            $this->id = mysql_insert_id();
            
        // Modify Account ////////////////////////////////////////////
        }else{
            $rs = mysql_query("UPDATE cms_users SET usr_login='" . scrub($this->login) . "', usr_type=" . $this->type 
                . " WHERE usr_id=" . $this->id);
        }
    }
    
    //////////////////////////////////////////////////////////////////
    // CHANGE PASSWORD
    //////////////////////////////////////////////////////////////////
    
    public function ChangePassword(){
        $this->EncryptPassword();
        $rs = mysql_query("UPDATE cms_users SET usr_password='" . $this->password
              . "' WHERE usr_id=" . $this->id);
    }
    
    //////////////////////////////////////////////////////////////////
    // GENERATE PASSWORD
    //////////////////////////////////////////////////////////////////
    
    public function GeneratePassword(){
        $len = 8;
        $hex = md5("1n3B82Gh6Ti52o905" . uniqid("", true));
        $pack = pack('H*', $hex);
        $uid = base64_encode($pack);        // max 22 chars
        $uid = ereg_replace("[^A-Za-z0-9]", "", $uid);    // mixed case
        if ($len<4) $len=4;
        if ($len>128) $len=128;                       // prevent silliness, can remove
        while (strlen($uid)<$len)
            $uid = $uid . generatePassword(22);     // append until length achieved
        $key = substr($uid, 0, $len);
        return $key;  
    }
    
    //////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////
    
    public function Delete(){
        $rs = mysql_query("DELETE FROM cms_users WHERE usr_id=" . $this->id);
    }
    
    //////////////////////////////////////////////////////////////////
    // RETURN LISTING
    //////////////////////////////////////////////////////////////////
    
    public function GetList(){
    
        $output = array();
        $rs = mysql_query("SELECT * FROM cms_users ORDER BY usr_type, usr_login");
        if(mysql_num_rows($rs)==0){
            $output = 0;
        }else{
            while($row=mysql_fetch_array($rs)){
                $output[] = array(
                    "id"          => $row['usr_id'],
                    "login"       => stripslashes($row['usr_login']),
                    "type"        => $row['usr_type']
                ); 
            }
        }
        return $output; 
    
    }
    
    //////////////////////////////////////////////////////////////////
    // RETURN USER
    //////////////////////////////////////////////////////////////////
    
    public function GetAccount(){
    
        $rs = mysql_query("SELECT * FROM cms_users WHERE usr_id=" . $this->id);
        $row = mysql_fetch_array($rs);
        $this->login = stripslashes($row['usr_login']);
        $this->type = $row['usr_type'];
    
    }
    
    //////////////////////////////////////////////////////////////////
    // CHECK DUPLICATE LOGIN
    //////////////////////////////////////////////////////////////////
    
    public function CheckLogin(){
    
        $pass = 0;

        $rs = mysql_query("SELECT usr_id FROM cms_users WHERE usr_login='" . $this->login . "' AND usr_id!=" . $this->id);
        if(mysql_num_rows($rs)!=0){ $pass = 1; }
        
        return $pass;
    
    }
        
    //////////////////////////////////////////////////////////////////
    // ENCRYPT PASSWORD
    //////////////////////////////////////////////////////////////////
    
    private function EncryptPassword(){
        $this->password = sha1(md5($this->password));
    }
    
}

?>

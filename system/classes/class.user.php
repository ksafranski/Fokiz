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
    const ADMIN = 0;
    const EDITOR = 1;
    const VISITOR = 2;

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $id            = 0;
    public $login         = "";
    public $password      = "";
    public $type          = self::VISITOR;

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    // ------------------------------------------------------------ //

    //////////////////////////////////////////////////////////////////
    // AUTHENTICATE
    //////////////////////////////////////////////////////////////////

    public function Authenticate(){
        global $conn;

        $this->EncryptPassword();
        $rs = $conn->prepare("SELECT usr_id, usr_type FROM cms_users WHERE usr_login=? AND usr_password=?");
        $rs->execute(array($this->login, $this->password));

        if($rs->rowCount() == 0){
            return 0;
        }

        $row = $rs->fetch();
        $_SESSION['usr_id'] = $row['usr_id'];
        $_SESSION['usr_type'] = $row['usr_type'];
        return 1;
    }

    //////////////////////////////////////////////////////////////////
    // SAVE
    //////////////////////////////////////////////////////////////////

    public function Save(){
        global $conn;
        // Create Account ////////////////////////////////////////////
        if($this->id=="new"){
            $this->EncryptPassword();
            $rs = $conn->prepare("INSERT INTO cms_users (usr_login,usr_password,usr_type) VALUES (?,?,?)");
            $rs->execute(array($this->login, $this->password, $this->type));
            $this->id = $conn->lastInsertId();
        // Modify Account ////////////////////////////////////////////
        }else{
            $rs = $conn->prepare("UPDATE cms_users SET usr_login=?, usr_type=? WHERE usr_id=?");
            $rs->execute(array($this->login, $this->type, $this->id));
        }
    }

    //////////////////////////////////////////////////////////////////
    // CHANGE PASSWORD
    //////////////////////////////////////////////////////////////////

    public function ChangePassword(){
        global $conn;
        $this->EncryptPassword();
        $rs = $conn->prepare("UPDATE cms_users SET usr_password=? WHERE usr_id=?");
        $rs->execute(array($this->password, $this->id));
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
        global $conn;
        $rs = $conn->prepare("DELETE FROM cms_users WHERE usr_id=?");
        $rs->execute(array($this->id));
    }

    //////////////////////////////////////////////////////////////////
    // RETURN LISTING
    //////////////////////////////////////////////////////////////////

    public function GetList(){
        global $conn;

        $output = array();
        $rs = $conn->query("SELECT * FROM cms_users ORDER BY usr_type, usr_login");

        if($rs->rowCount() == 0){
            $output = 0;
        }else{
            while($row = $rs->fetch()){
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
        global $conn;

        $rs = $conn->prepare("SELECT * FROM cms_users WHERE usr_id=?");
        $rs->execute(array($this->id));
        $row = $rs->fetch();
        $this->login = stripslashes($row['usr_login']);
        $this->type = $row['usr_type'];

    }

    //////////////////////////////////////////////////////////////////
    // CHECK DUPLICATE LOGIN
    //////////////////////////////////////////////////////////////////

    public function CheckLogin(){
        global $conn;
        $pass = 0;

        $rs = $conn->prepare("SELECT usr_id FROM cms_users WHERE usr_login=? AND usr_id!=?");
        $rs->execute(array($this->login, $this->id));

        if($rs->rowCount() != 0){
            $pass = 1;
        }

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

<?php

/*
*  SEE DOCUMENTATION AT https://github.com/Fluidbyte/apiQL
*/

/////////////////////////////////////////////////////////////////////
// License
/////////////////////////////////////////////////////////////////////

/*
*    Copyright (c) 2012 Kent Safranski (fluidbyte.net)
*
*    Permission is hereby granted, free of charge, to any person
*    obtaining a copy of this software and associated documentation
*    files (the "Software"), to deal in the Software without
*    restriction, including without limitation the rights to use,
*    copy, modify, merge, publish, distribute, sublicense, and/or
*    sell copies of the Software, and to permit persons to whom
*    the Software is furnished to do so, subject to the following
*    conditions:
*
*    The above copyright notice and this permission notice shall be
*    included in all copies or substantial portions of the Software.
*
*    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
*    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
*    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
*    NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
*    HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
*    WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
*    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
*    OTHER DEALINGS IN THE SOFTWARE.
*/

class apiQL {

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $table       = '';
    public $data        = array();
    public $columns     = '*';
    public $where       = '';
    public $order       = '';
    public $query       = '';
    public $output      = '';
    public $json        = false;
    public $jdata       = '';
    public $test        = false;
    public $link        = '';

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    // -----------------------------||----------------------------- //

    //////////////////////////////////////////////////////////////////
    // Constructor
    //////////////////////////////////////////////////////////////////

    public function __construct($json){

        $this->json = $json;

        @$this->link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if(mysqli_connect_errno()){
            $error = 'MySQL Connection Error: '
            . mysqli_connect_errno() . " : "
            . mysqli_connect_error();
            if($this->json){
                die('{"status":"error","message":'.json_encode($error).'}');
            }else{
                die($error);
            }
        }

    }

    //////////////////////////////////////////////////////////////////
    // SELECT
    //////////////////////////////////////////////////////////////////

    public function Select(){

        if(!is_array($this->columns)){
            $query = "SELECT " . $this->columns . " FROM " . $this->table;
        }else{
            $columns = "";
            $counter = 0;
            foreach($this->columns as $column){
                if($counter>0){ $s=","; }else{ $s=""; }
                $columns .= $s.$column;
                $counter++;
            }
            $query = "SELECT " . $columns . " FROM " . $this->table;
        }

        $where = "";
        if($this->where){ $where = " WHERE " . $this->where; }

        $order = "";
        if($this->order){ $order = " ORDER BY " . $this->order; }

        $this->output = "select";
        $this->query = $query . $where . $order;
        return $this->Execute();

    }

    //////////////////////////////////////////////////////////////////
    // INSERT
    //////////////////////////////////////////////////////////////////

    public function Insert(){

        $columns = "";
        $values = "";

        // Loop data
        $count = 0;
        foreach($this->data as $column=>$value){
            if($count>0){ $s = ","; }else{ $s = ""; }
            $columns .= $s.$column;
            $values .= $s."'".mysqli_real_escape_string($this->link,$value)."'";
            $count++;
        }

        $this->output = "id";
        $this->query = "INSERT INTO " . $this->table . " (" . $columns . ") VALUES (" . $values . ")";
        return $this->Execute();

    }

    //////////////////////////////////////////////////////////////////
    // UPDATE
    //////////////////////////////////////////////////////////////////

    public function Update(){

        $updates = "";

        // Loop data
        $count = 0;
        foreach($this->data as $column=>$value){
            if($count>0){ $s = ","; }else{ $s = ""; }
            $updates .= $s.$column."='".mysqli_real_escape_string($this->link,$value)."'";
            $count++;
        }

        $where = "";
        if($this->where){ $where = " WHERE " . $this->where; }

        $this->query = "UPDATE " . $this->table . " SET " . $updates . $where;
        return $this->Execute();

    }

    //////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////

    public function Delete(){

        $where = "";
        if($this->where){ $where = " WHERE " . $this->where; }

        $this->query = "DELETE FROM " . $this->table . $where;
        echo("DELETE FROM " . $this->table . $where);
        return $this->Execute();

    }

    //////////////////////////////////////////////////////////////////
    // EXECUTE
    //////////////////////////////////////////////////////////////////

    public function Execute(){

        if($this->test){
            echo("<pre>".$this->query."</pre>");
        }else{
            // Fire off query
            if($result = $this->link->query($this->query)){
                switch($this->output){
                    case "select":
                        $results = array();
                        while($row = mysqli_fetch_assoc($result)){
                          $results[] = $row;
                        }
                        $this->jdata = json_encode($results);
                        $this->output = $results;
                        break;

                    case "id":
                        $this->jdata = '{"id":"'.mysqli_insert_id($this->link).'"}';
                        $this->output = mysqli_insert_id($this->link);
                        break;

                    default:
                        $this->jdata = null;
                        $this->output = 'success';
                        break;
                }
            // Return error
            }else{
                $error = "MySQL Error: " .mysqli_errno($this->link) . " : " . mysqli_error($this->link);
                if($this->json){
                    die('{"status":"error","message":'.json_encode($error).'}');
                }else{
                    die($error);
                }
            }
        }
        if($this->json){
            $this->buildJSON();
        }else{
            return $this->output;
        }
    }

    //////////////////////////////////////////////////////////////////
    // RETURN JSON
    //////////////////////////////////////////////////////////////////

    public function buildJSON(){

        if($this->jdata){
            echo '{"status":"success","data":'.$this->jdata.'}';
        }else{
            echo '{"status":"success","data":null}';
        }

    }

    //////////////////////////////////////////////////////////////////
    // CLOSE
    //////////////////////////////////////////////////////////////////

    public function Close(){
        $this->link->close();
    }

}

?>
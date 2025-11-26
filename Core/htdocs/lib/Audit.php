<?php
class Audit extends Entity
{
    public static function add(PDO $link, $pobject, $cobject, $table, $fields)
    {
        $farray = explode(",",$fields);
        foreach($pobject as $key => $value)
        {
            if(in_array($key, $farray))
            {
                if(Date::isDate($cobject->$key) or Date::isDate($pobject->$key))
                {
                    if(Date::toMySQL($cobject->$key) != Date::toMySQL($pobject->$key))
                    {
                        $note = new Note();
                        $note->parent_table = $table;
                        $note->parent_id = $cobject->id;
                        $note->subject = "Field Changed";
                        $note->note = "[" . $key . "] changed from '" . Date::toShort($pobject->$key) . "' to '" . Date::toShort($cobject->$key) . "'";
                        $note->username = $_SESSION['user']->username;
                        DAO::saveObjectToTable($link, 'notes', $note);
                    }
                }
                else
                {
                    if($cobject->$key != $pobject->$key)
                    {
                        $note = new Note();
                        $note->parent_table = $table;
                        $note->parent_id = $cobject->id;
                        $note->subject = "Field Changed";
                        $note->note = "[" . $key . "] changed from '" . $pobject->$key . "' to '" . $cobject->$key . "'";
                        $note->username = $_SESSION['user']->username;
                        DAO::saveObjectToTable($link, 'notes', $note);
                    }
                }
            }
        }
        return true;
    }
}
?>
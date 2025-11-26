<?php
/**
 * User: Richard Elmes
 * Date: 10/05/12
 * Time: 12:56
 */
class LookUp extends Entity {

    public static function loadFromDatabase(PDO $link, $table_name, $key_desc = 'id', $id) {

        if( $id == '' || $table_name == '' ) {
            return null;
        }

        $key = addslashes($id);
        $query = <<<HEREDOC
SELECT
	*
FROM
	lookup_{$table_name}
WHERE
	{$key_desc}='$key'
LIMIT 1;
HEREDOC;
        $st = $link->query($query);

        $lookup_info = null;
        if( $st ) {
            $lookup_info = null;
            $row = $st->fetch();
            if( $row ) {
                $lookup_info = new LookUp();
                $lookup_info->populate($row);
            }
        }
        else {
            throw new Exception("Could not execute database query to find lookup information. " . '----' . $query . '----' . $link->errorCode());
        }pre($lookup_info);

        return $lookup_info;
    }

    public function save(PDO $link)	{

        $table_has_primary_key = 0;


        $column_definition_sql = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'.DB_NAME.'" AND TABLE_NAME = "lookup_'.$this->table_name.'"';
        $column_definition = DAO::getResultset($link, $column_definition_sql, DAO::FETCH_ASSOC);

        foreach ( $column_definition as $col_id => $col_information ) {
            // check if the table has a primary key
            if ( $col_information['COLUMN_KEY'] == 'PRI' ) {
                $table_has_primary_key = 1;
                // do we need to do anything special here?
                if ( $col_information['EXTRA'] == 'auto_increment' ) {
                    // $this->{$col_information['COLUMN_NAME']} = NULL;
                }
                else {
                }
            }

            // no primary key - we need to check for duplicated values??
            if ( $table_has_primary_key == 0 ) {

            }
        }
        return DAO::saveObjectToTable($link, 'lookup_'.$this->table_name, $this);
    }


    public function delete(PDO $link) {

        if( !$this->isSafeToDelete($link) ) {
            throw new Exception("This Lookup Value Cannot be deleted because we cannot see if its been used anywhere else!");
        }

        $sql = <<<HEREDOC
DELETE FROM
	users_capture_info where userinfoid={$this->userinfoid}
HEREDOC;
        DAO::execute($link, $sql);
    }


    public function isSafeToDelete(PDO $link) {
        if ( !$this->_can_delete_{$this->table_name}()) {
            return false;
        }
        // always return false for now
        return false;
    }

    private function _can_delete_lookup_vacancy_type(PDO $link) {
        return true;
    }
}
?>
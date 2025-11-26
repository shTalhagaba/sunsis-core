<?php


class EmployerAgreementSchedule extends Entity
{
    /**
     * @static
     * @param PDO $link
     * @param $id
     * @return EmployerAgreementScedule
     * @throws DatabaseException
     */
    public static function loadFromDatabase(PDO $link, $id)
    {
        $schedule = null;
        if($id != '' && is_numeric($id))
        {
            $query = "SELECT * FROM employer_agreement_schedules WHERE id = " . addslashes($id) . ";";
            $st = $link->query($query);

            if($st)
            {
                $row = $st->fetch();
                if($row)
                {
                    $schedule = new EmployerAgreementSchedule();
                    $schedule->populate($row);
                }
            }
            else
            {
                throw new DatabaseException($link, $query);
            }
        }

        return $schedule;
    }

    public function save(PDO $link)
    {
        return DAO::saveObjectToTable($link, 'employer_agreement_schedules', $this);
    }

    public $id = null;
    public $employer_id = null;
    public $identifier = null;
}
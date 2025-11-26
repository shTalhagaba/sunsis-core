<?php


class CommitmentStatement
{
    /**
     * @static
     * @param PDO $link
     * @param $id
     * @return CommitmentStatement
     * @throws DatabaseException
     */
    public static function loadFromDatabase(PDO $link, $id)
    {
        $cs = null;
        if($id != '' && is_numeric($id))
        {
            $query = "SELECT * FROM commitment_statements WHERE id = " . addslashes($id) . ";";
            $st = $link->query($query);

            if($st)
            {
                $row = $st->fetch();
                if($row)
                {
                    $cs = new CommitmentStatement();
                    $cs->populate($row);
                }
            }
            else
            {
                throw new DatabaseException($link, $query);
            }
        }

        return $cs;
    }

    public $id = null;

    public $ob_learner_id = null;
    public $signed_by_learner = null;
    public $signed_by_provider = null;
    public $signed_by_employer = null;
    public $learner_sign = null;
    public $learner_sign_date = null;
    public $rovider_sign = null;
    public $provider_sign_date = null;
    public $employer_sign = null;
    public $employer_sign_date = null;
    public $provider_user_id = null;

}
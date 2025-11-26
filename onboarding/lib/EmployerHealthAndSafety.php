<?php


class EmployerHealthAndSafety extends Entity
{
    /**
     * @static
     * @param PDO $link
     * @param $id
     * @return EmployerHealthAndSafety
     * @throws DatabaseException
     */
    public static function loadFromDatabaseById(PDO $link, $id)
    {
        $hs = null;
        if($id != '' && is_numeric($id))
        {
            $query = "SELECT * FROM health_safety WHERE id = " . addslashes($id) . ";";
            $st = $link->query($query);

            if($st)
            {
                $row = $st->fetch();
                if($row)
                {
                    $hs = new EmployerHealthAndSafety();
                    $hs->populate($row);
                }

            }
            else
            {
                throw new DatabaseException($link, $query);
            }
        }

        return $hs;
    }

    public function save(PDO $link)
    {
        return DAO::saveObjectToTable($link, 'health_safety', $this);
    }

    public function delete(PDO $link)
    {
        if(!$_SESSION['user']->isAdmin() && $_SESSION['user']->type != User::TYPE_ADMIN)
        {
            throw new Exception("You are not authorised to perform this action.");
        }
        if(in_array($this->status, [self::TYPE_SENT, self::TYPE_SIGNED_BY_EMPLOYER]))
        {
            throw new Exception("This agreement cannot be deleted.");
        }

        DAO::execute($link, "DELETE FROM employer_agreements WHERE id = '{$this->id}'");

        return true;
    }

    public function getStatusDesc()
    {
        switch ($this->status)
        {
            case self::TYPE_NOT_STARTED:
                return 'NOT CREATED';
            case self::TYPE_CREATED:
                return 'CREATED';
            case self::TYPE_SENT:
                return 'EMAILED TO EMPLOYER';
            case self::TYPE_SIGNED_BY_EMPLOYER:
                return 'SIGNED BY EMPLOYER';
            case self::TYPE_COMPLETED:
                return 'COMPLETED';
            default:
                return $this->status;
        }
    }

    public function getHsForm(PDO $link)
    {
        $hs_form = new EmployerHealthAndSafetyForm();
        $hs_form->hs_id = $this->id;

        $form = DAO::getObject($link, "SELECT * FROM health_safety_form WHERE hs_id = '{$this->id}'");
        if(isset($form->hs_id))
        {
            $hs_form->populate($form);
        }

        return $hs_form;
    }

    public $id = NULL;
    public $employer_id = NULL;
    public $location_id = NULL;
    public $last_assessment = NULL;
    public $next_assessment = NULL;
    public $assessor = NULL;
    public $comments = NULL;
    public $complient = NULL;
    public $paperwork_received = NULL;
    public $age_range = NULL;
    public $pl_date = NULL;
    public $pl_insurance = NULL;
    public $el_date = NULL;
    public $el_insurance = NULL;
    public $employer_rep = NULL;
    public $assessment_type = NULL;
    public $assessment_type_other = NULL;
    public $recommendation = NULL;
    public $risk_category = NULL;
    public $hs_contact_person = NULL;
    public $pl_insurer = NULL;
    public $el_insurer = NULL;
    public $status = NULL;

    public $employer_sign = null;
    public $employer_sign_name = null;
    public $employer_sign_date = null;
    public $provider_sign = null;
    public $provider_sign_name = null;
    public $provider_sign_date = null;
    public $provider_sign_id = null;
    public $verifier_sign = null;
    public $verifier_sign_name = null;
    public $verifier_sign_date = null;


    const TYPE_NOT_STARTED = 0;
    const TYPE_CREATED = 1;
    const TYPE_SENT = 2;
    const TYPE_SIGNED_BY_EMPLOYER = 3;
    const TYPE_SIGNED_BY_PROVIDER = 4;
    const TYPE_SIGNED_BY_VERIFIER = 5;
}
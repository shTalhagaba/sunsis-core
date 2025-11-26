<?php


class DocumentSignature extends Entity
{
    /**
     * @static
     * @param PDO $link
     * @param $id
     * @return DocumentSignature
     * @throws DatabaseException
     */
    public static function loadFromDatabase(PDO $link, $entity_id, $entity_type)
    {
        $signature = null;
        if($entity_id != '' && $entity_type != '')
        {
            $query = "SELECT * FROM documents_signatures WHERE entity_id = '" . addslashes($entity_id) . "' AND entity_type = '" . addslashes($entity_type) . "';";
            $st = $link->query($query);

            if($st)
            {
                $row = $st->fetch();
                if($row)
                {
                    $signature = new DocumentSignature();
                    $signature->populate($row);
                }
            }
            else
            {
                throw new DatabaseException($link, $query);
            }
        }

        return $signature;
    }

    public function save(PDO $link)
    {
        return DAO::saveObjectToTable($link, 'documents_signatures', $this);
    }

   
    public $id = NULL;
    public $entity_id = NULL;
    public $entity_type = NULL;
    public $created = NULL;
    public $learner_sign = NULL;
    public $learner_sign_date = NULL;
    public $learner_sign_name = NULL;
    public $employer_sign = NULL;
    public $employer_sign_date = NULL;
    public $employer_sign_name = NULL;
    public $provider_sign = NULL;
    public $provider_sign_date = NULL;
    public $provider_sign_name = NULL;
    public $provider_sign_id = NULL;
    public $verifier_sign = NULL;
    public $verifier_sign_name = NULL;
    public $verifier_sign_date = NULL;

   
}
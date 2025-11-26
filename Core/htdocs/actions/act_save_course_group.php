<?php
class save_course_group implements IAction
{
    public function execute(PDO $link)
    {
        $new_pots = array_key_exists('members', $_POST)?$_POST['members']:array();

        // Populate Value Object from user's <form> submission
        $vo = new CourseGroupVO();

        $vo->populate($_POST);
        if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
            $vo->courses_id = $_POST['training_provider'];

        /*        if($vo->status[0]==1)
                    $vo->status = 1;
                else
                    $vo->status = 0;
        */

        //DAO::transaction_start($link);
        try
        {
            // Save course group object
            $dao = new CourseGroupDAO($link);
            if($vo->id == 0)
            {
                $vo->id = $dao->insert($vo);
            }
            else
            {
                $dao->update($vo);
            }

            // ACL entries for tutor
            $tutor = User::loadFromDatabase($link,$vo->tutor);
            if(isset($tutor))
                $tutor_identity = $tutor->getFullyQualifiedName();
            $old_tutor = User::loadFromDatabase($link,$vo->old_tutor);

            // Store members prior to deletion
            $sql = "SELECT tr_id FROM group_members WHERE groups_id = " . $vo->id;
            $old_pots = DAO::getSingleColumn($link, $sql);



            DAO::transaction_start($link);
            try
            {
                if(DB_NAME!='am_reed' && DB_NAME!='am_reed_demo')
                {
                    // Delete current members
                    $query = "DELETE FROM group_members WHERE groups_id = " . $vo->id . ";";
                    DAO::execute($link, $query);
                }

                // Save new members
                if(count($new_pots) > 0)
                {

                    if(DB_NAME!='am_reed' && DB_NAME!='am_reed_demo')
                    {
                        // First delete the members
                        $members = implode(",",$new_pots);
                        DAO::execute($link, "delete from group_members where tr_id in ($members) AND group_members.groups_id < 10001");
                    }

                    $query = "INSERT INTO group_members (tr_id, groups_id) VALUES ";
                    for($i = 0; $i < count($new_pots); $i++)
                    {
                        if($i > 0)
                        {
                            $query .= ', ';
                        }

                        $query .= '(' . $new_pots[$i] . ',' . $vo->id . ')';


                    }
                    $query .= ';';

                    DAO::execute($link, $query);
                }

                DAO::transaction_commit($link);
            }
            catch(Exception $e)
            {
                DAO::transaction_rollback($link);
                throw new WrappedException($e);
            }

            if(SystemConfig::getEntityValue($link, 'module_training'))
            {
                $update_sql = <<<SQL
UPDATE tr
INNER JOIN group_members ON group_members.`tr_id` = tr.id
INNER JOIN training_groups ON training_groups.`id` = tr.tg_id
SET tg_id = NULL
WHERE group_members.`groups_id` != training_groups.`group_id`;
SQL;
                DAO::execute($link, $update_sql);
            }

            // Update statistics
            $update_pots = array_unique(array_merge($old_pots, $new_pots));
            $pot_dao = new TrainingRecord($link);
            $student_dao = new User($link);
            $pot_dao->updateAttendanceStatistics($update_pots);
            if(count($update_pots) > 0)
            {
                //$student_dao->updateAttendanceStatistics("SELECT tr_id FROM tr WHERE id IN (".DAO::pdo_implode($update_pots).")");
            }

        }
        catch(Exception $e)
        {
            //DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }
        //DAO::transaction_commit($link);
        $_REQUEST['group_id'] =	$vo->id;
        http_redirect($_SESSION['bc']->getPrevious());
    }
}
?>
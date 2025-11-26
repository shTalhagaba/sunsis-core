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
            $vo->courses_id = $_SESSION['user']->employer_id;

        if($vo->status[0]==1)
            $vo->status = 1;
        else
            $vo->status = 0;

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
                // Delete current members
                //$query = "DELETE FROM group_members WHERE groups_id = " . $vo->id . ";";
                //DAO::execute($link, $query);

                // Save new members
                if(count($new_pots) > 0)
                {

                    // First delete the members
                    //$members = implode(",",$new_pots);
                    //DAO::execute($link, "delete from group_members where tr_id in ($members)");

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
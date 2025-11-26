<?php
class update_baltic_unit_titles implements IAction
{
	public function execute(PDO $link)
	{
        $old_title = 'Data Visualisation pt2';
        $new_title = 'Data Visualisation';
        $framework_id = '435';
        $tracker_id = '76'; // make sure only one tracker contains that framework
        $framework_qual_id = 'Z0001946';			


        DAO::transaction_start($link);

        try
        {

            #update the framework_qualifications
            $sql1 = " UPDATE framework_qualifications 
            SET framework_qualifications.`evidences` = REPLACE(framework_qualifications.`evidences`, '{$old_title}', '{$new_title}')
            WHERE framework_qualifications.`framework_id` = '{$framework_id}' AND framework_qualifications.id = '{$framework_qual_id}' ";
            DAO::execute($link, $sql1);

            #update student qualifications
            $sql2 = " UPDATE student_qualifications 
            SET student_qualifications.`evidences` = REPLACE(student_qualifications.`evidences`, '{$old_title}', '{$new_title}')
            WHERE student_qualifications.`framework_id` = '{$framework_id}' AND student_qualifications.id = '{$framework_qual_id}' ";
            DAO::execute($link, $sql2);

            #update op_tracker_units
            $sql3 = " UPDATE op_tracker_units SET op_tracker_units.`unit_ref` = '{$new_title}' WHERE op_tracker_units.`tracker_id` = '{$tracker_id}' AND op_tracker_units.`unit_ref` = '{$old_title}' ";
            DAO::execute($link, $sql3);

            #update op_tracker_unit_sch
            $sql4 = " UPDATE op_tracker_unit_sch SET op_tracker_unit_sch.`unit_ref` = '{$new_title}' WHERE op_tracker_unit_sch.`unit_ref` = '{$old_title}' 
            AND op_tracker_unit_sch.`tr_id` IN (SELECT tr_id FROM student_frameworks WHERE student_frameworks.`id` = '{$framework_id}') ";
            DAO::execute($link, $sql4);

            # update op_tracker_unit_mock
            $sql5 = " UPDATE op_tracker_unit_mock SET op_tracker_unit_mock.`unit_ref` = '{$new_title}' WHERE op_tracker_unit_mock.`unit_ref` = '{$old_title}'
            AND op_tracker_unit_mock.`tr_id` IN (SELECT tr_id FROM student_frameworks WHERE student_frameworks.`id` = '{$framework_id}') ";
            DAO::execute($link, $sql5);

            $sql6 = " UPDATE sessions SET sessions.`unit_ref` = '{$new_title}' WHERE sessions.`unit_ref` = '{$old_title}' AND FIND_IN_SET('{$tracker_id}', sessions.`tracker_id`) ";
            DAO::execute($link, $sql6);

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link);
            $ex_message = $e->getCode() . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getFile() . PHP_EOL . $e->getLine() . PHP_EOL . $e->getTraceAsString();
            if ($e instanceof SQLException)
            {
                $ex_message . PHP_EOL . '<br>' . $e->getSql();
            }

            throw new Exception($ex_message);
        }

        pre('process completed for ' . $old_title );


    }
}
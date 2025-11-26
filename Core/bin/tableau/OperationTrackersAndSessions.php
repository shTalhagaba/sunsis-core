<?php
function OperationTrackersAndSessions(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);
    $trackers = DAO::getLookupTable($source_link, "SELECT id, title FROM op_trackers");

    $sql = <<<HEREDOC
SELECT 
id, tracker_id
FROM sessions WHERE tracker_id IS NOT NULL
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $csv_fields = array();
    $index = -1;
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows AS $row)
    {
        $tracker_ids = explode(",", $row['tracker_id']);
        foreach($tracker_ids AS $tracker_id)
        {
            if(isset($trackers[$tracker_id]))
            {
		$index++;
                $csv_fields[$index]['EventID'] = $row['id'];        
                $csv_fields[$index]['TrackerID'] = $tracker_id;        
                $csv_fields[$index]['TrackerTitle'] = $trackers[$tracker_id];        
            }
        }
    }

    DAO::execute($target_link, "truncate OperationTrackersAndSessions");
    DAO::multipleRowInsert($target_link, "OperationTrackersAndSessions", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationTrackersAndSessions populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}
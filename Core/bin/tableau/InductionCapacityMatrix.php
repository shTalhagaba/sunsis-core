<?php
function InductionCapacityMatrix(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $capacity_by_months = [];
    $capacity_totals = DAO::getResultset($source_link, "SELECT * FROM lookup_induction_capacity WHERE capacity IS NOT NULL ORDER BY STR_TO_DATE(lookup_induction_capacity.month, '%M_%Y');", DAO::FETCH_ASSOC); 

    foreach($capacity_totals AS $capacity_total)
    {
        $month = str_replace('_', '', $capacity_total['month']);
        $obj = new stdClass();
        $obj->Total = $capacity_total['capacity'];
        $obj->Month = $month;
        $capacity_by_months[$month] = $obj;
    }

    $sql = "SELECT month_name, (SELECT REPLACE(description, ' ', '') FROM lookup_apprenticeship_titles WHERE lookup_apprenticeship_titles.id = program_capacity_matrix.ap_title_id) AS program, capacity
        FROM program_capacity_matrix WHERE capacity IS NOT NULL;";
    $prog_capacity_totals = DAO::getResultset($source_link, $sql, DAO::FETCH_ASSOC); 
    foreach($prog_capacity_totals AS $prog_capacity_total)
    {
        $month = $prog_capacity_total['month_name'];
        $program = $prog_capacity_total['program'];
        
        if(isset($capacity_by_months[$month]))
        {
            $capacity_by_months[$month]->$program = $prog_capacity_total['capacity'];
        }
        else
        {
            $obj = new stdClass();
            $obj->$program = $prog_capacity_total['capacity'];
            $obj->Total = null;
            $obj->Month = $month;
            $capacity_by_months[$month] = $obj;
        }
    }

    DAO::execute($target_link, "TRUNCATE TABLE InductionCapacityMatrix");
    foreach($capacity_by_months AS $entry)
    {
        DAO::saveObjectToTable($target_link, 'InductionCapacityMatrix', $entry);
    }

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nInductionCapacityMatrix populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}
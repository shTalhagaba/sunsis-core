<?php 

class ajax_crm_dashboard extends ActionController
{
    /**
     * The default action
     * @override
     * @param PDO $link
     * @throws Exception|UnauthorizedException
     */
    public function indexAction(PDO $link)
    {

    }

    public function updatePanelEnquiriesAction(PDO $link)
    {
        $start_date = isset($_REQUEST['enq_start_date']) ? Date::toMySQL($_REQUEST['enq_start_date']) : '';
        $end_date = isset($_REQUEST['enq_end_date']) ? Date::toMySQL($_REQUEST['enq_end_date']) : '';
        $created_by = isset($_REQUEST['frmPanelEnquiries_created_by']) ? $_REQUEST['frmPanelEnquiries_created_by'] : null;

        $sql = new SQLStatement("SELECT
            COUNT(*) AS cnt, crm_enquiries.status,
            CASE crm_enquiries.`status`
                WHEN 1 THEN 'New'
                WHEN 2 THEN 'In Progress'
                WHEN 3 THEN 'Successful'
                WHEN 4 THEN 'Unsuccessful'
                ELSE ''
            END AS status_desc		
        FROM
            crm_enquiries
        GROUP BY crm_enquiries.status    
        ");
        if($start_date != "")
            $sql->setClause("WHERE crm_enquiries.created >= '{$start_date}'");
        if($end_date != "")
            $sql->setClause("WHERE crm_enquiries.created <= '{$end_date}'");
        if($created_by != "")
            $sql->setClause("WHERE crm_enquiries.created_by = '{$created_by}'");

        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        if(count($result) == 0)
        {
            echo '<span class="text-info"><i class="fa fa-info-circle"></i> No records found.</span>';
            return;
        }

        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered">';
        echo '<tr><th>Status</th><th>Count</th></tr>';
        foreach($result AS $row)
        {
            echo HTML::viewrow_opening_tag("do.php?_action=view_enquiries&filter_status={$row['status']}&filter_owner={$created_by}");
            echo '<td>' . $row['status_desc'] . '</td>';
            echo '<td>' . $row['cnt'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }

    public function updatePanelLeadsAction(PDO $link)
    {
        $start_date = isset($_REQUEST['lead_start_date']) ? Date::toMySQL($_REQUEST['lead_start_date']) : '';
        $end_date = isset($_REQUEST['lead_end_date']) ? Date::toMySQL($_REQUEST['lead_end_date']) : '';
        $created_by = isset($_REQUEST['frmPanelLeads_created_by']) ? $_REQUEST['frmPanelLeads_created_by'] : null;

        $sql = new SQLStatement("SELECT
            COUNT(*) AS cnt, crm_leads.status,
            CASE crm_leads.`status`
                WHEN 1 THEN 'Open'
                WHEN 2 THEN 'In Progress'
                WHEN 3 THEN 'Won'
                WHEN 4 THEN 'Lost'
                ELSE ''
            END AS status_desc		
        FROM
            crm_leads
        GROUP BY crm_leads.status    
        ");
        if($start_date != "")
            $sql->setClause("WHERE crm_leads.created >= '{$start_date}'");
        if($end_date != "")
            $sql->setClause("WHERE crm_leads.created <= '{$end_date}'");
        if($created_by != "")
            $sql->setClause("WHERE crm_leads.created_by = '{$created_by}'");

        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        if(count($result) == 0)
        {
            echo '<span class="text-info"><i class="fa fa-info-circle"></i> No records found.</span>';
            return;
        }

        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered">';
        echo '<tr><th>Status</th><th>Count</th></tr>';
        foreach($result AS $row)
        {
            echo HTML::viewrow_opening_tag("do.php?_action=view_leads&filter_status={$row['status']}&filter_owner={$created_by}");
            echo '<td>' . $row['status_desc'] . '</td>';
            echo '<td>' . $row['cnt'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }

    public function updatePanelOpportunitiesAction(PDO $link)
    {
        $start_date = isset($_REQUEST['opp_start_date']) ? Date::toMySQL($_REQUEST['opp_start_date']) : '';
        $end_date = isset($_REQUEST['opp_end_date']) ? Date::toMySQL($_REQUEST['opp_end_date']) : '';
        $created_by = isset($_REQUEST['frmPanelOpportunities_created_by']) ? $_REQUEST['frmPanelOpportunities_created_by'] : null;

        $sql = new SQLStatement("SELECT
            COUNT(*) AS cnt, crm_opportunities.status,
            CASE crm_opportunities.`status`
                WHEN 1 THEN 'New'
                WHEN 2 THEN 'In Progress'
                WHEN 3 THEN 'Successful'
                WHEN 4 THEN 'Unsuccessful'
                ELSE ''
            END AS status_desc		
        FROM
            crm_opportunities
        GROUP BY crm_opportunities.status    
        ");
        if($start_date != "")
            $sql->setClause("WHERE crm_opportunities.created >= '{$start_date}'");
        if($end_date != "")
            $sql->setClause("WHERE crm_opportunities.created <= '{$end_date}'");
        if($created_by != "")
            $sql->setClause("WHERE crm_opportunities.created_by = '{$created_by}'");

        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        if(count($result) == 0)
        {
            echo '<span class="text-info"><i class="fa fa-info-circle"></i> No records found.</span>';
            return;
        }

        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered">';
        echo '<tr><th>Status</th><th>Count</th></tr>';
        foreach($result AS $row)
        {
            echo HTML::viewrow_opening_tag("do.php?_action=view_opportunities&filter_status={$row['status']}&filter_owner={$created_by}");
            echo '<td>' . $row['status_desc'] . '</td>';
            echo '<td>' . $row['cnt'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }

    public function updatePanelCrmActivitiesAction(PDO $link)
    {
        $created_by = isset($_REQUEST['frmPanelCrmActivities_created_by']) ? $_REQUEST['frmPanelCrmActivities_created_by'] : '';

        $sql = new SQLStatement("SELECT 
        crm_activities.`created_by`,
        (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = crm_activities.created_by) AS created_by_name,
        SUM( IF(crm_activities.`activity_type` = 'task' AND crm_activities.`complete` = 0, 1, 0) ) AS open_tasks,
        SUM( IF(crm_activities.`activity_type` = 'task' AND crm_activities.`complete` = 1, 1, 0) ) AS completed_tasks,
        SUM( IF(crm_activities.`activity_type` = 'phone' AND crm_activities.`complete` = 0, 1, 0) ) AS open_phones,
        SUM( IF(crm_activities.`activity_type` = 'phone' AND crm_activities.`complete` = 1, 1, 0) ) AS completed_phones,
        SUM( IF(crm_activities.`activity_type` = 'meeting' AND crm_activities.`complete` = 0, 1, 0) ) AS open_meetings,
        SUM( IF(crm_activities.`activity_type` = 'meeting' AND crm_activities.`complete` = 1, 1, 0) ) AS completed_meetings,
        SUM( IF(crm_activities.`activity_type` = 'email', 1, 0) ) AS emails
    FROM
        crm_activities
    GROUP BY 
        crm_activities.`created_by`	
    ;	    
        ");
        if($created_by == "")
            $sql->setClause("WHERE crm_activities.created_by = '{$_SESSION['user']->id}'");
        else
            $sql->setClause("WHERE crm_activities.created_by = '{$created_by}'");    

        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        if(count($result) == 0)
        {
            echo '<span class="text-info"><i class="fa fa-info-circle"></i> No records found.</span>';
            return;
        }

        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered text-center">';
        echo '<tr class="bg-gray"><th>Created By</th><th>Tasks</th><th>Phone Calls</th><th>Meetings</th><th>Emails</th></tr>';
        foreach($result AS $row)
        {
            echo '<tr>';
            echo '<td>' . $row['created_by_name'] . '</td>';
            if($row['open_tasks'] > 0 || $row['completed_tasks'] > 0)
            {
                $url = "do.php?_action=view_crm_activities_report&subview=All&_reset=1&ViewCrmActivities_filter_activity_type=4&ViewCrmActivities_filter_created_by={$row['created_by']}";
                echo '<td>';
                echo '<table class="table table-bordered">';
                echo '<tr><th>Open</th><td><a href="'.$url.'&ViewCrmActivities_filter_completed=0">' . $row['open_tasks'] . '</a></td></tr>';
                echo '<tr><th>Completed</th><td><a href="'.$url.'&ViewCrmActivities_filter_completed=1">' . $row['completed_tasks'] . '</a></td></tr>';
                echo '</table></td>';
            }
            else
            {
                echo '<td></td>';
            }
            if($row['open_phones'] > 0 || $row['completed_phones'] > 0)
            {
                $url = "do.php?_action=view_crm_activities_report&subview=All&_reset=1&ViewCrmActivities_filter_activity_type=3&ViewCrmActivities_filter_created_by={$row['created_by']}";
                echo '<td>';
                echo '<table class="table table-bordered">';
                echo '<tr><th>Open</th><td><a href="'.$url.'&ViewCrmActivities_filter_completed=0">' . $row['open_phones'] . '</a></td></tr>';
                echo '<tr><th>Completed</th><td><a href="'.$url.'&ViewCrmActivities_filter_completed=1">' . $row['completed_phones'] . '</a></td></tr>';
                echo '</table></td>';
            }
            else
            {
                echo '<td></td>';
            }
            if($row['open_meetings'] > 0 || $row['completed_meetings'] > 0)
            {
                $url = "do.php?_action=view_crm_activities_report&subview=All&_reset=1&ViewCrmActivities_filter_activity_type=2&ViewCrmActivities_filter_created_by={$row['created_by']}";
                echo '<td>';
                echo '<table class="table table-bordered">';
                echo '<tr><th>Open</th><td><a href="'.$url.'&ViewCrmActivities_filter_completed=0">' . $row['open_meetings'] . '</a></td></tr>';
                echo '<tr><th>Completed</th><td><a href="'.$url.'&ViewCrmActivities_filter_completed=1">' . $row['completed_meetings'] . '</a></td></tr>';
                echo '</table></td>';
            }
            else
            {
                echo '<td></td>';
            }
            if($row['emails'] > 0)
            {
                $url = "do.php?_action=view_crm_activities_report&subview=All&_reset=1&ViewCrmActivities_filter_activity_type=1&ViewCrmActivities_filter_created_by={$row['created_by']}";
                echo '<td><a href="'.$url.'&ViewCrmActivities_filter_completed=0">' . $row['emails'] . '</a></td>';
            }
            else
            {
                echo '<td></td>';
            }
        }
        echo '</table>';
        echo '</div>';
    }


    public function updateCrmActivitiesPanelAction(PDO $link)
    {
        $created_by = isset($_REQUEST['frmCrmActivities_created_by']) ? $_REQUEST['frmCrmActivities_created_by'] : null;
        $viewDue = ViewCrmActivities::getInstance($link, 'ViewActivitiesDue');

        $qs = [
            View::KEY_PAGE_SIZE => 0,
            '_reset' => 1,
            'ViewCrmActivities_filter_created_by' => $created_by,
        ];
        $viewDue->refresh($link, $qs);
        $due_count = $viewDue->getRowCount();
        $due_link = "do.php?_action=view_crm_activities_report&subview=Due&" . http_build_query($qs);

        $viewOverdue = ViewCrmActivities::getInstance($link, 'ViewActivitiesOverdue');
        $viewOverdue->refresh($link, $qs);
        $overdue_count = $viewOverdue->getRowCount();
        $overdue_link = "do.php?_action=view_crm_activities_report&subview=Overdue&" . http_build_query($qs);

        $viewUpcoming = ViewCrmActivities::getInstance($link, 'ViewActivitiesUpcoming');
        $viewUpcoming->refresh($link, $qs);
        $upcoming_count = $viewUpcoming->getRowCount();
        $upcoming_link = "do.php?_action=view_crm_activities_report&subview=Upcoming&" . http_build_query($qs);

        echo <<<HTML

<div class="row">
    <div class="col-sm-3">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>$due_count</h3>
                <p>Due Today</p>
            </div>
            <div class="icon"><i class="fa fa-hourglass-half"></i></div>
            <a href="$due_link" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>$overdue_count</h3>
                <p>Overdue</p>
            </div>
            <div class="icon"><i class="fa fa-clock-o"></i></div>
            <a href="$overdue_link" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>$upcoming_count</h3>
                <p>Upcoming</p>
            </div>
            <div class="icon"><i class="fa fa-calendar"></i></div>
            <a href="$upcoming_link" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>        
HTML;

    }


    public function quickSearchOrgAction(PDO $link)
    {
        if(isset($_REQUEST['quickSearchOrg']))
        {
            $q = [
                'filter_legal_name' => $_REQUEST['txtSearchOrg'],
                View::KEY_PAGE_SIZE => 0, // No limit
                '_reset' => 1,
            ];

            http_redirect('do.php?_action=view_orgs&'.http_build_query($q));
        }

        http_redirect('do.php?_action=crm_dashboard');
    }

    public function quickSearchEnquiryAction(PDO $link)
    {
        if(isset($_REQUEST['quickSearchEnquiry']))
        {
            $q = [
                'ViewEnquiries_filter_title' => $_REQUEST['txtSearchEnquiryTitle'],
                'ViewEnquiries_filter_id' => $_REQUEST['txtSearchEnquiryId'],
                'ViewEnquiries_'.View::KEY_PAGE_SIZE => 0, // No limit
                '_reset' => 1,
            ];

            http_redirect('do.php?_action=view_pool_organisations&'.http_build_query($q));
        }

        http_redirect('do.php?_action=crm_dashboard');
    }
}
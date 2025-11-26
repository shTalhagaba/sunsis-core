<?php /* @var $view VoltView*/ ?>
<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Learner LAR History</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">

    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">View Learner LAR History</div>
                <div class="ButtonBar">
                    <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                </div>
                <div class="ActionIconBar">
                    <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=view_learner_lar_history&subaction=export_csv&tr_id=<?php echo $tr_id; ?>'" title="Export to .CSV file"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php $_SESSION['bc']->render($link); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="callout callout-default">
                <?php
                $tr_details = DAO::getObject($link, "SELECT id, firstnames, surname, start_date, target_date FROM tr WHERE id = '{$tr_id}'");
                if (isset($tr_details->id)) {
                    echo 'Learner: ' . $tr_details->firstnames . ' ' . $tr_details->surname . ' | ';
                    echo 'Start Date: ' . Date::toShort($tr_details->start_date) . ' | ';
                    echo 'Planned End Date: ' . Date::toShort($tr_details->target_date) . ' | ';
                    echo 'Programme: ' . DAO::getSingleValue($link, "SELECT title FROM op_trackers WHERE id = '{$tracker_id}'");
                }
                ?>
            </div>
            <div style="margin: 5px;">
                <?php
                if (isset($notes->from_date) && isset($notes->to_date)) {
                    echo '<p class="text-center"><span class="text-bold text-info lead"><i class="fa fa-info-circle"></i> History from <u>' . Date::to($notes->from_date, Date::DATETIME) . '</u> to <u>' . Date::to($notes->to_date, Date::DATETIME) . '</u></span></p>';
                }
                ?>
            </div>
        </div>
        <div class="col-sm-12">
            <div align="center">
                <table class="resultset" style="width: auto">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Details</th>
                            <th>Actively Involved</th>
                            <th>
                                Notes
                            </th>
                            <th>
                                Summary
                            </th>
                            <th>
                                Communication
                            </th>
                            <th>
                                Contact History
                            </th>
                            <th>
                                Next Action Summary
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ragDDL = InductionHelper::getListLARRAGRating();
                        $reasonDDL = InductionHelper::getListLARReason();
                        if (!isset($notes->lar_details) || $notes->lar_details == '') {
                            echo '<tr><td colspan="7"><i>No record found.</i></td></tr>';
                        } else {
                            $risks = InductionHelper::getListLarRiskOf();
                            $retentions = InductionHelper::getListRetentionCategories();
                            $owners = InductionHelper::getListOpOwners();
                            $types = InductionHelper::getListLAR();
                            $notes = XML::loadSimpleXML($notes->lar_details);
                            foreach ($notes->Note as $note) {
                                echo '<tr>';
                                echo '<td>';
                                echo 'Creation Timestamp:&nbsp;' . Date::to($note->DateTime, Date::DATETIME) . '<br>';
                                echo 'Created By:&nbsp;' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '<br>';
                                echo isset($types[$note->Type->__toString()]) ? 'Type:&nbsp;' . $types[$note->Type->__toString()] . '<br>' : '';
                                echo 'LAR Date:&nbsp;' . Date::toShort($note->Date->__toString()) . '<br>';
                                if (isset($note->Reason))
                                    echo isset($reasonDDL[$note->Reason->__toString()]) ? 'Primary Reason:&nbsp;' . $reasonDDL[$note->Reason->__toString()] . '<br>' : '';
                                else
                                    echo '';
                                if (isset($note->SecondReason))
                                    echo isset($reasonDDL[$note->SecondReason->__toString()]) ? 'Secondary Reason:&nbsp;' . $reasonDDL[$note->SecondReason->__toString()] . '<br>' : '';
                                else
                                    echo '';
                                echo isset($retentions[$note->Retention->__toString()]) ? 'Retention Category:&nbsp;' . $retentions[$note->Retention->__toString()] . '<br>' : '';
                                echo isset($note->RetentionOther) ? 'Retention Category Other:&nbsp;' . $note->RetentionOther->__toString() . '<br>' : '';
                                echo isset($ragDDL[$note->RAG->__toString()]) ? 'RAG:&nbsp;' . $ragDDL[$note->RAG->__toString()] . '<br>' : '';
                                echo isset($note->NextActionDate) ? 'Revisit Date:&nbsp;' . Date::toShort($note->NextActionDate->__toString()) . '<br>' : '';
                                echo isset($owners[$note->Owner->__toString()]) ? 'Owner:&nbsp;' . $owners[$note->Owner->__toString()] . '<br>' : '';
                                echo isset($risks[$note->RiskOf->__toString()]) ? 'At Risk Of:&nbsp;' . $risks[$note->RiskOf->__toString()] . '<br>' : '';
                                echo (isset($note->NoContact) && $note->NoContact->__toString() == '1') ? 'No Contact: Yes<br>' : '';
                                echo '</td>';
                                if (isset($note->ActivelyInvolved) && $note->ActivelyInvolved != '') {
                                    $ActivelyInvolvedUsers = explode(",", $note->ActivelyInvolved);
                                    echo '<td>';
                                    if (count($ActivelyInvolvedUsers) > 0) {
                                        foreach ($ActivelyInvolvedUsers as $_user_id) {
                                            echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$_user_id}'") . '; ';
                                        }
                                    }
                                    echo '</td>';
                                } else {
                                    echo '<td></td>';
                                }

                                echo '<td class="small">' . html_entity_decode($note->Note) . '</td>';
                                echo isset($note->Summary) ? '<td class="small">' . html_entity_decode($note->Summary->__toString()) . '</td>' : '<td></td>';
                                echo isset($note->Communication) ? '<td class="small">' . html_entity_decode($note->Communication->__toString()) . '</td>' : '<td></td>';
                                echo isset($note->ContactHistory) ? '<td class="small">' . html_entity_decode($note->ContactHistory->__toString()) . '</td>' : '<td></td>';
                                echo isset($note->NextActionHistory) ? '<td class="small">' . html_entity_decode($note->NextActionHistory->__toString()) . '</td>' : '<td></td>';




                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>


    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

    <script language="JavaScript">
        $(function() {});
    </script>

</body>

</html>
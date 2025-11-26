<div class="row vertical-center-row">
    <div class="col-sm-12">
        <h5 class="lead text-bold"><?php echo $framework->title . ' OTJ Planner'; ?></h5>
        <span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=otj_planner_tr&tr_id=<?php echo $tr->id; ?>'">
            <i class="fa fa-edit"></i> Edit OTJ Planner
        </span>
	    <?php if($_SESSION['user']->username == 'aperspective' || SOURCE_LOCAL) { ?>
        <div class="pull-right">
            <span class="btn btn-danger btn-xs" onclick="reset_otj_planner_grid('<?php echo $tr->id; ?>');">
                <i class="fa fa-refresh"></i> Reset
            </span>
        </div>
        <?php } ?>
        <div class="text-center">
            <?php 
            $otj_signs = DAO::getObject($link, "SELECT * FROM otj_planner_signatures WHERE tr_id = '{$tr->id}'");
            if( isset($otj_signs->learner_sign) && $otj_signs->learner_sign != '' )
            {
                echo '<span class="label label-success"><i class="fa fa-check"></i> Learner Signed</span>';
            }
            if( isset($otj_signs->employer_sign) && $otj_signs->employer_sign != '' )
            {
                echo ' &nbsp; <span class="label label-success"><i class="fa fa-check"></i> Employer Signed</span>';
            }
            if( isset($otj_signs->provider_sign) && $otj_signs->provider_sign != '' )
            {
                echo ' &nbsp; <span class="label label-success"><i class="fa fa-check"></i> Provider Signed</span>';
            }
            ?>
        </div>
            
        <div class="table-responsive">
            <table class="table table-bordered text-center" id="tblOtjPlanner">
                <thead>
                    <tr class="bg-info">
                        <th></th>
                        <th>KSB</th>
                        <?php 
                        foreach(OnboardingHelper::generateOtjColumnsHeader($link, $tr->framework_id) AS $_c)
                        {
                            echo '<th>' . $_c . '</th>';
                        }
                        ?>
                        <th>Behaviours</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $otj_tr_template_sections = DAO::getResultset($link, "SELECT * FROM otj_tr_template_sections WHERE tr_id = '{$tr->id}' ORDER BY section_id", DAO::FETCH_ASSOC);

                    foreach($otj_tr_template_sections AS $otj_tr_template_section)
                    {
                        $subsections_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM otj_tr_template_subsections WHERE section_id = '{$otj_tr_template_section['section_id']}'");
                        if($subsections_count > 1)
                        {
                            $row_span = (int)$subsections_count+1;
                            echo '<tr>';

                            echo '<td class="bg-info" rowspan="' . $row_span . '">' . $otj_tr_template_section['section_desc'] . '</td>';
    
                            echo '</tr>';
                        }

                        $otj_tr_template_subsections = DAO::getResultset($link, "SELECT * FROM otj_tr_template_subsections WHERE section_id = '{$otj_tr_template_section['section_id']}' ORDER BY subsection_id", DAO::FETCH_ASSOC);
                        foreach($otj_tr_template_subsections AS $otj_tr_template_subsection)
                        {
                            echo '<tr>';

                            echo $subsections_count > 1 ? '' : '<td class="bg-info">' . $otj_tr_template_section['section_desc'] . '</td>';
                            
                            echo '<td>' . $otj_tr_template_subsection['subsection_desc'] . '</td>';

                            $otj_tr_template_activities = DAO::getResultset($link, "SELECT * FROM otj_tr_template_activities WHERE subsection_id = '{$otj_tr_template_subsection['subsection_id']}' ORDER BY activity_id", DAO::FETCH_ASSOC);
                            foreach($otj_tr_template_activities AS $otj_prog_template_activity)
                            {
                                echo '<td>' . $otj_prog_template_activity['activity_desc'] . '</td>';
                            }
                            echo '</tr>';
                        }

                        echo '<tr class="bg-warning">';
                        echo '<td></td>';
                        echo '<td></td>';
                        for($col = 2; $col <= OnboardingHelper::colsOfStandard($link, $tr->framework_id)+1; $col++)
                        {
                            echo '<td><input readonly="true" maxlength="2" name="txt_section_' . $otj_tr_template_section['section_id'] . '_col_' . $col . '" class="form-control" type="text" value="'.$otj_tr_template_section['col_'.$col.'_otj'].'" /></td>';    
                        }
                        echo '</tr>';
                    }
                    if(isset($otj_tr_template_section))
                    {
                        echo '<tr class="bg-black">';
                        echo '<td>Commulative OTJ</td>';
                        echo '<td></td>';
                        for($col = 2; $col <= OnboardingHelper::colsOfStandard($link, $tr->framework_id)+1; $col++)
                        {
                            echo '<td><input readonly="true" name="total_section_' . $otj_tr_template_section['section_id'] . '_col_' . $col . '" class="form-control" type="text" value="0" /></td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
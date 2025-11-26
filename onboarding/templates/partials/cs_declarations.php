<div class="row">
    <div class="col-sm-12">
        <p class="lead text-bold">Section 5 - Declarations</p>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <p>This document must be completed and signed on or before the start date of the apprenticeship.</p>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 8%" />
            <caption class="bg-light-blue text-bold" style="padding: 5px;">Signed by Apprentice:</caption>
            <?php
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'LEARNER' ORDER BY id", DAO::FETCH_ASSOC);
            foreach($result AS $row)
            {
                echo '<tr>';
                echo '<td align="right"><input type="checkbox" name="learner_dec[]" value="' . $row['id'] . '" /></td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 8%" />
            <caption class="bg-light-blue text-bold" style="padding: 5px;">Signed by Employer:</caption>
            <?php
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'EMPLOYER' ORDER BY id", DAO::FETCH_ASSOC);
            foreach($result AS $row)
            {
                echo '<tr>';
                echo '<td align="right"><input type="checkbox" name="employer_dec[]" value="' . $row['id'] . '" /></td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 8%" />
            <caption class="bg-light-blue text-bold" style="padding: 5px;">Signed by Main Provider (Barnsley College):</caption>
            <?php
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'PROVIDER' ORDER BY id", DAO::FETCH_ASSOC);
            foreach($result AS $row)
            {
                echo '<tr>';
                echo '<td align="right"><input type="checkbox" name="provider_dec[]" value="' . $row['id'] . '" /></td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 8%" />
            <caption class="bg-light-blue text-bold" style="padding: 5px;">Signed by Subcontractor:</caption>
            <?php
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'SUBCONTRACTOR' ORDER BY id", DAO::FETCH_ASSOC);
            foreach($result AS $row)
            {
                echo '<tr>';
                echo '<td align="right"><input type="checkbox" name="subcontractor_dec[]" value="' . $row['id'] . '" /></td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 8%" />
            <caption class="bg-light-blue text-bold" style="padding: 5px;">Working Together:</caption>
            <tr>
                <td><i>The Employer and the Apprentice will work together with the Training Provider's representatives to ensure that the Apprentice has the best chance to achieve.  In so doing, each parties� roles and responsibilities should be read carefully in this Training Plan with further recourse to the appropriate, Funding Rules in force at the time.</i></td>
            </tr>
        </table>
    </div>
</div>

<p></p>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 8%" />
            <caption class="bg-gray-light text-bold" style="padding: 5px;">Queries and Complaints Process:</caption>
            <tr>
                <td>Barnsley College strive to provide the best quality learning and services that meet or exceed the expectations of our learners and users. The College/Training Provider promotes a culture that is responsive to feedback, whether complimentary or critical. Comments about our services are actively encouraged and acknowledged as a valuable source of information that we can evaluate and use to improve the quality of provision to learners, other users and partners/stakeholders. Learners and users can bring their concerns to the attention of the college either informally or formally.</td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 8%" />
            <caption class="bg-gray-light text-bold" style="padding: 5px;">Informal Complaints:</caption>
            <tr>
                <td>
                    <ul style="margin-left: 20px;">
                        <li>In the first instance, complainants are strongly encouraged to resolve the matter informally with appropriate members of staff.</li>
                        <li>If a complaint is not resolved at this stage, the complainant should be advised to progress their complaint through the College/Training Provider formal complaints procedure.</li>
                    </ul>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 8%" />
            <caption class="bg-gray-light text-bold" style="padding: 5px;">Formal Complaints:</caption>
            <tr>
                <td>
                    <ul style="margin-left: 20px;">
                        <li>Complainants can make a formal complaint either verbally or in writing. All formal complaints should be passed to the Director of Quality and Performance.</li>
                        <li>All complaints will be formally acknowledged in writing upon receipt.</li>
                        <li>In the first instance, the Director of Quality and Performance will contact the complainant to discuss their concerns and any requirements.  Where appropriate a meeting will be arranged to discuss their concerns and requirements in more detail.</li>
                        <li>All formal complaints will be resolved within 10 working days of the receipt of the formal complaint or if this is not possible, the complainant will be advised on the progress made to address their concerns.</li>
                        <li>Upon completion of the investigation into the complaint the Director of Quality and Performance will notify the complainant in writing of the outcome.</li>
                        <li>If after due consideration by the Vice Principal Quality the complainant feels their complaint has not been addressed to their satisfaction they can refer the complaint to the Principal.</li>
                        <li>If after due consideration by the Principal or a Senior Post Holder the complainant feels their complaint has not been addressed to their satisfaction, they can refer the complaint to the Department for Education (DfE) through the apprenticeship helpline detailed below:</li>
                    </ul>
                </td>
            </tr>
        </table>
    </div>
</div>


<div class="row">
    <div class="col-sm-12">
        <p class="lead text-bold">Section 4 - Roles & Responsibilities</p>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <p>Please read and agree to the roles and responsibilities listed below.</p>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <caption class="bg-gray-light text-bold" style="padding: 5px;">The Apprentice agrees to:</caption>
            <?php
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'LEARNER' ORDER BY id", DAO::FETCH_ASSOC);
            foreach($result AS $row)
            {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
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
            <caption class="bg-gray-light text-bold" style="padding: 5px;">The Employer (Manager of Apprentice) will:</caption>
            <?php
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'EMPLOYER' AND sub_id = 0 ORDER BY id", DAO::FETCH_ASSOC);
            $first_loop = true;
            $previous_id = '';
            foreach($result AS $row)
            {
                echo '<tr>';
                echo $previous_id != $row['id'] ? '<td>' . $row['id'] . '</td>' : '<td></td>';
                echo '<td>';
                echo $row['description'];
                $subs = DAO::getSingleColumn($link, "SELECT description FROM lookup_cs_roles_responsibilities WHERE sub_id = '{$row['id']}' AND user_type = 'EMPLOYER'");
                if(count($subs) > 0)
                    echo '<ul>';
                foreach($subs AS $sub)
                {
                    echo '<li style="margin-left: 20px;">' . $sub . '</li>';
                }
                if(count($subs) > 0)
                    echo '</ul>';
                echo '</td>';
                echo '</tr>';
                $first_loop = false;
                $previous_id = $row['id'];
            }
            ?>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <caption class="bg-gray-light text-bold" style="padding: 5px;">The Main Provider agrees to:</caption>
            <?php
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'PROVIDER' AND sub_id = 0 ORDER BY id", DAO::FETCH_ASSOC);
            $first_loop = true;
            $previous_id = '';
            foreach($result AS $row)
            {
                echo '<tr>';
                echo $previous_id != $row['id'] ? '<td>' . $row['id'] . '</td>' : '<td></td>';
                echo '<td>';
                echo $row['description'];
                $subs = DAO::getSingleColumn($link, "SELECT description FROM lookup_cs_roles_responsibilities WHERE sub_id = '{$row['id']}' AND user_type = 'PROVIDER'");
                if(count($subs) > 0)
                    echo '<ul>';
                foreach($subs AS $sub)
                {
                    echo '<li style="margin-left: 20px;">' . $sub . '</li>';
                }
                if(count($subs) > 0)
                    echo '</ul>';
                echo '</td>';
                echo '</tr>';
                $first_loop = false;
                $previous_id = $row['id'];
            }
            ?>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <caption class="bg-gray-light text-bold" style="padding: 5px;">The Delivery Subcontractor agrees to:</caption>
            <?php
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'SUBCONTRACTOR' ORDER BY id", DAO::FETCH_ASSOC);
            foreach($result AS $row)
            {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>

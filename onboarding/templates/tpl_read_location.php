<?php /* @var $location Location */ ?>
<?php /* @var $organisation Organisation */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis: View Location</title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .row.is-flex {
            display: flex;
            flex-wrap: wrap;
        }
        .row.is-flex > [class*='col-'] {
            display: flex;
            flex-direction: column;
        }
        .tooltip {
            position: relative;
            display: inline-block;
        }


    </style>

</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View Location</div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=edit_location&id=<?php echo $location->id; ?>&organisations_id=<?php echo $location->organisations_id; ?>';"><i class="fa fa-edit"></i> Edit</span>
            </div>
            <div class="ActionIconBar">

            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<br>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-7">
            <div class="callout callout-default">
                <h4 class="text-bold"><?php echo $organisation->legal_name; ?></h4>
                <?php
                echo $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : '';
                echo $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : '';
                echo $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : '';
                echo $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : '';
                echo $location->postcode != '' ? '<i class="fa fa-map-marker"></i> ' . $location->postcode . '<br>' : '';
                echo $location->telephone != '' ? '<i class="fa fa-phone"></i> ' . $location->telephone . '<br>' : '';
                echo $location->fax != '' ? '<i class="fa fa-fax"></i> ' . $location->fax : '';
                ?>
            </div>
            <div class="callout callout-default">
                <h5 class="text-bold">Primary Contact</h5>
                <?php
                echo $location->contact_name != '' ? $location->contact_name . '<br>' : '';
                echo $location->contact_job_title != '' ? $location->contact_job_title . '<br>' : '';
                echo $location->contact_email != '' ? $location->contact_email . '<br>' : '';
                echo $location->contact_telephone != '' ? $location->contact_telephone . '<br>' : '';
                echo $location->contact_mobile != '' ? $location->contact_mobile . '<br>' : '';
                ?>
            </div>
        </div>
        <div class="col-sm-5">
            <?php
            echo <<<HTML
<iframe style="background-color: #ffffff;"
    src="https://maps.google.co.uk/maps?q=$location->postcode&amp;ie=UTF8&amp;hq=&amp;hnear=B1 2HF,+United+Kingdom
						&amp;gl=uk&amp;t=m&amp;vpsrc=0&amp;z=14&amp;iwloc=A&amp;output=embed"
    frameborder="0" marginwidth="0" marginheight="0" scrolling="no" align="left"
    width="100%" height="250"></iframe>
                
HTML;
            ?>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-5">
            <table class="table table-bordered">
                <tr>
                    <th>User Accounts</th>
                    <td><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.employer_location_id = '{$location->id}'"); ?></td>
                </tr>
                <tr>
                    <th>Training Records</th>
                    <td><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.provider_location_id = '{$location->id}'"); ?></td>
                </tr>
                <tr>
                    <th>Health & Safety</th>
                    <td><?php echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM health_safety WHERE location_id = '{$location->id}'"); ?></td>
                </tr>
            </table>
        </div>
    </div>

</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.js"></script>

<script>
    $(function() {


    });

</script>
</body>
</html>

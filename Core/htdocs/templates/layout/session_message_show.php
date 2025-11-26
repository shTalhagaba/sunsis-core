<?php
$icons = [
    'danger' => 'fa fa-exclamation-triangle red',
    'warning' => 'fa fa-warning orange',
    'success' => 'fa fa-check green',
    'info' => 'fa fa-info-circle blue',
];
?>

<div class="row">
    <div class="col-sm-12">
        <?php foreach (['danger', 'warning', 'success', 'info'] as $msg) {?>
            <?php if(isset($_SESSION['alert-' . $msg]) && $_SESSION['alert-' . $msg] != '') { ?>
                <div class="alert alert-<?php echo $msg; ?>">
                    <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
                    <i class="<?php echo $icons[$msg]; ?>"></i> <?php echo $_SESSION['alert-' . $msg]; ?>
                </div>
            <?php }//if ?>
        <?php }//foreach ?>
    </div>
</div>

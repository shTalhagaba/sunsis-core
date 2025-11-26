<?php
class test_invoice2 implements IAction
{
    public function execute(PDO $link)
    {
        $id = $_REQUEST['id'];

        $vo = TestSales::loadFromDatabase($link, $id);
        $p = TestPurchase::loadFromDatabase($link, $vo->pid);

        $pdf = new FPDI();
        $pagecount = $pdf->setSourceFile('invoice1.pdf');
        $tpl=$pdf->ImportPage(1);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['w'], $s['h']));
        $pdf->useTemplate($tpl);
        $pdf->SetFont('Arial', '', 10);

        $make = DAO::getSingleValue($link, "select description from car_makes where id = {$p->make}");
        $model = DAO::getSingleValue($link, "select description from car_models where id = {$p->model}");

        $pdf->Text(170,83, ($vo->invoice));
        $pdf->Text(170,88,Date::toMedium($vo->sales_date));

        $pdf->Text(75,83,$vo->c_name);
        $pdf->Text(75,88,$vo->c_address);
        $pdf->Text(75,93,$vo->c_phone);

        $pdf->Text(72,115,$make);
        $pdf->Text(72,120,$p->reg_mark);
        $pdf->Text(135,115,$model);
        $pdf->Text(135,120,$p->colour);
        $pdf->Text(135,130,$p->mileage);
//        $pdf->Text(65,106,$chassis);

//        $pdf->Text(30,149,"1");
 //       $pdf->Text(46,149,"1");
  //      $pdf->Text(61,149,"Sales Price:         ");
//        $pdf->Text(172,149,iconv("UTF-8", "ISO-8859-1", "£"));
//        $pdf->Text(175,149,$vo->price);

//        $pdf->Text(61,155,"Previous Owners:         " . $p->owners);

//        $pdf->Text(61,161,"MOT Expiry Date:         " . Date::toMedium($p->mot));
//        $pdf->Text(61,167,"Road Tax Expiry Date:    " . Date::toMedium($p->road_tax));

//        $pdf->Text(61,179,"Deposit:         ");
//        $pdf->Text(172,179,iconv("UTF-8", "ISO-8859-1", "£"));
//        $pdf->Text(175,179,$vo->deposit);

//        $pdf->Text(61,185,"To pay:         ");
//        $pdf->Text(172,185,iconv("UTF-8", "ISO-8859-1", "£"));
//        $pdf->Text(175,185,($vo->price - $vo->deposit));

//        $pdf->Text(61,197,"Total:         ");
//        $pdf->Text(172,197,iconv("UTF-8", "ISO-8859-1", "£"));
//        $pdf->Text(175,197,$vo->price);


        // Prepare directory
        $admin_reports = Repository::getRoot().'/test';
        if(is_file($admin_reports)){
            throw new Exception("admin_reports exists but it is a file and not a directory");
        }
        if(!is_dir($admin_reports)){
            mkdir($admin_reports);
        }
        $pdf->Output($admin_reports."/invoice.pdf", 'F');
        http_redirect("do.php?_action=downloader&path=/" . DB_NAME . "/test/&f=invoice.pdf");
    }
}
?>
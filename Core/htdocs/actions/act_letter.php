<?php
class letter implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        if(DB_NAME=='am_demo')
        {
            $name = isset($_REQUEST['name'])?$_REQUEST['name']:'';
            $number = isset($_REQUEST['number'])?$_REQUEST['number']:'';
            $letter = isset($_REQUEST['letter'])?$_REQUEST['letter']:'';

            if($letter==1)
            {
                if($name!='' and $number!='')
                {
                    $pdf = new FPDI();
                    $pagecount = $pdf->setSourceFile('letter1.pdf');

                    $tpl=$pdf->ImportPage(1);
                    $s = $pdf->getTemplatesize($tpl);
                    $pdf->AddPage('P', array($s['w'], $s['h']));
                    $pdf->useTemplate($tpl);

                    $pdf->SetFont('Arial', '', 12);

                    $pdf->Text(137,75,date("d/m/Y"));
                    $pdf->Text(90,114,$name);
                    $pdf->Text(25,150,$number);

                    echo $pdf->Output();
                }
            }
            elseif($letter==1978)
            {
                if($name!='' and $number!='')
                {
                    $pdf = new FPDI();
                    $pagecount = $pdf->setSourceFile('letter2.pdf');

                    $tpl=$pdf->ImportPage(1);
                    $s = $pdf->getTemplatesize($tpl);
                    $pdf->AddPage('P', array($s['w'], $s['h']));
                    $pdf->useTemplate($tpl);

                    $pdf->SetFont('Arial', '', 12);

                    $pdf->Text(137,75,date("d/m/Y"));
                    $pdf->Text(90,114,$name);
                    $pdf->Text(25,150,$number);

                    echo $pdf->Output();
                }
            }
            require_once('tpl_letter.php');
        }
    }
}
?>
<?php
class test implements IAction
{
	public function execute(PDO $link)
	{
        require dirname(__DIR__) . '/vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML('<h1>Hello World!</h1>');
        $mpdf->Output();
        exit;
    }
}
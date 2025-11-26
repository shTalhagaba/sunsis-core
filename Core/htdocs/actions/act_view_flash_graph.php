<?php
class view_flash_graph
{
	public function execute(PDO $link)
	{
include_once 'QOpenFlash.class.php';

print "<pre>";
// generate some random data
srand((double)microtime()*1000000);

$bar_red = new BarSketch( 55, 6, '#d070ac', '#000000' );
$bar_red->add('Key', '2006', 10 );

// add random height bars:
for( $i=0; $i<10; $i++ ){
  $bar_red->add(rand(2,9));
}
$g = new QChartOpenFlash();
$g    ->setTitle( 'Sketch', '{font-size:20px; color: #ffffff; margin:10px; background-color: #d070ac; padding: 5px 15px 5px 15px;}' )
    ->setBgColor('#FDFDFD')
    ->setDataSets($bar_red)
    ->setXLabelStyle( 11, '#303030', 2 )
    ->setYLabelStyle( 11, '#303030', 2 )
    ->setXLabels( array( 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct' ) )
    ->setXAxisColor( '#e0e0e0', '#ADB5C7' )
    ->setYAxisColor( '#e0e0e0', '#ADB5C7' )
    ->setYMax( 10 )
    ->setXTickSize( 9 )
    ->setYSteps( 5 )
    ->setYLegend( 'Open Flash Chart', 12, '#736AFF' );
    
echo $g->render(); 
	}
}

?>
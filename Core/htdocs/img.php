<?php

$url = rawurldecode($_REQUEST['url']);

if(strpos($url, 'http://') !== false)
{
	//$url = 'http://chart.apis.google.com/chart?chtt=Funding+predictions+for+contract+ER+TtG+180+Tyne+and+Wear&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=s:XVbg9lnrTKMI&amp;chdl=£+k&amp;chxt=y,x&amp;chxl=0:|0|30.905|61.81|92.715|123.62|1:|W01|W02|W03|W04|W05|W06|W07|W08|W09|W10|W11|W12';
	$url = str_replace('&amp;', '&', $url);
	$content = file_get_contents($url);
	header('Content-Type: image/png');
	echo $content;
}

?>
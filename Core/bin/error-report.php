#!/usr/local/bin/php
<?php
function softbreaks($str, $interval)
{
	$exp = '/([^ ]{'.$interval.','.$interval.'})/';
	return preg_replace($exp, '$1&#8203;', $str);
}

if(!($link = mysqli_connect('localhost', 'dr', 'perspective', 'test'))) /* @var $link mysqli */
{
	throw new Exception(mysqli_connect_error(), mysqli_connect_errno());
}

// Load the list of databases
//$sql = "SELECT schema_name FROM information_schema.schemata WHERE schema_name LIKE 'am_%'";
$sql = "SELECT table_schema FROM information_schema.`TABLES` WHERE      table_schema LIKE 'am\_%' AND table_name = 'error_log'";
if($result = mysqli_query($link, $sql))
{
	while($row = mysqli_fetch_array($result))
	{
		$dbs[] = $row[0];
	}

	$result->free_result();
}
else
{
	throw new Exception(mysqli_error($link), mysqli_errno($link));
}

// Construct the SQL to generate the report
$sql = "";
foreach($dbs as $db)
{
	if(strlen($sql) > 0)
	{
		$sql .= "\nUNION\n";
	}

	$db_concat = substr($db,5);
	$sql .= <<<HEREDOC
(SELECT
        '$db' AS db,
        LEFT(message,200) AS `msg`,
        COUNT(error_log.id) AS `incidences`,
        trace
FROM
        $db.error_log
WHERE
        #DATE(`date`) + INTERVAL 1 DAY  = CURRENT_DATE
        `date` >= (CURRENT_DATE - INTERVAL 1 DAY)
        #`date` >= CURRENT_DATE
GROUP BY
        db,msg,trace
)
HEREDOC;
}

$sql .= " ORDER BY db";

// Execute the query
if($result = $link->query($sql))
{
	$text = "";
	$html = '<html><body><table border="1" cellpadding="2"><tr><th>Database</th><th>Incidences</th><th>Message</th><th>Stack Trace</th></tr>';
	while($row = $result->fetch_array())
	{
		$text .= "{$row['db']}\t{$row['msg']}\n\n";

		$msg = softbreaks(htmlspecialchars($row['msg']),30);

		$trace = htmlspecialchars($row['trace']);
		$trace = preg_replace('/\\n/', '<br/><br/>', $trace);
		$trace = preg_replace('/([a-zA-Z_]+\.php\s?\(\d+\))/', '<b>$1</b>', $trace);

		$html .= "<tr>";
		$html .= '<td valign="top">'.htmlspecialchars($row['db'])."</td>";
		$html .= '<td valign="top" align="right">'.htmlspecialchars($row['incidences'])."</td>";
		$html .= '<td valign="top">'.$msg."</td>";
		$html .= '<td valign="top">'.$trace."</td>";
		$html .= "</tr>";
	}
	$html .= '</table></body></html>';

	$result->free_result();

	$yesterday = strtotime("yesterday");

	$boundary = md5(uniqid(time()));
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "From: Ian Selwyn-Smith <iss@perspective-uk.com>\r\n";
	$headers .= "Subject: Sunesis errors: ".date('l jS F Y', $yesterday)."\r\n";
	$headers .= "Content-Type: multipart/alternative;\r\n boundary=" . $boundary . "\r\n";

	$message = "This is a MIME encoded message.\r\n";

	$message .= "\r\n--" . $boundary . "\r\n";
	$message .= "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n";
	$message .= "Content-Transfer-Encoding: base64\r\n\r\n";
	$message .= chunk_split(base64_encode($text));

	$message .= "\r\n--" . $boundary . "\r\n";
	$message .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message .= "Content-Transfer-Encoding: base64\r\n\r\n";
	$message .= chunk_split(base64_encode($html));


	array_shift($argv); // Remove the name of the PHP file in $argv[0]

	foreach($argv as $arg)
	{
		if(strpos($arg, ':'))
		{
			$headers .= $arg."\r\n";
		}
		else
		{
			$recipients[] = $arg;
		}
	}

	mail(implode(',', $recipients), 'Sunesis errors: '.date('l jS F Y', $yesterday), $message, $headers);
}
else
{
	throw new Exception(mysqli_error($link), mysqli_errno($link));
}

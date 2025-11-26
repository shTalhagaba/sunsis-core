<?php
class Pin extends Entity
{
	private static function randomIntFromInterval($min,$max)
	{
		return rand($min, $max);
	}

	public static function generateHTMLForm($user_type)
	{
		$labels = array("First", "Second", "Third", "Fourth");
		$position_and_content = array();

		$p1 = Pin::randomIntFromInterval(1,4);
		do
		{
			$p2 = Pin::randomIntFromInterval(1,4);
		}while($p2 == $p1);
		do
		{
			$p3 = Pin::randomIntFromInterval(1,4);
		}while($p3 == $p1 || $p3 == $p2);


		$position_and_content[] = $p1;
		$position_and_content[] = $p2;
		$position_and_content[] = $p3;
		sort($position_and_content);

		$p1 = $position_and_content[0];
		$p2 = $position_and_content[1];
		$p3 = $position_and_content[2];

		$position1 = $labels[$position_and_content[0] - 1];
		$position2 = $labels[$position_and_content[1] - 1];
		$position3 = $labels[$position_and_content[2] - 1];

		$action = $_SERVER['PHP_SELF'];

		$html = <<<HTML
<div>
	<form name="frm_user_pin" id="frm_user_pin" action="$action" method="post">
		Enter username <input type="text" name="username_$user_type" id="username_$user_type" value="" /><br><br>
		Enter <strong>$position1</strong> <input type="password" size="1" maxlength="1" name="position$p1$user_type" id="position$p1$user_type" />, <strong>$position2</strong> <input type="password" size="1" maxlength="1" name="position$p2$user_type" id="position$p2$user_type" /> and <strong>$position3</strong> <input type="password" size="1" maxlength="1" name="position$p3$user_type" id="position$p3$user_type" /> digit of your PIN.
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
$('#position$p1$user_type').keyup(function() {
     if(this.value.length == $(this).attr('maxlength')) {
         $('#position$p2$user_type').focus();
     }
 });
$('#position$p2$user_type').keyup(function() {
     if(this.value.length == $(this).attr('maxlength')) {
         $('#position$p3$user_type').focus();
     }
 });
 function resetPanelUserPin()
 {
     document.getElementById('frm_user_pin').reset();
     document.getElementById("position$p1$user_type").focus();
 }
 //]]>
</script>
HTML;

		return $html;
	}

	public function verifyPIN(PDO $link, $username, $pin, $pos1, $pos2, $pos3)
	{
		$username = $link->quote($username);
		$user_pin = DAO::getSingleValue($link, "SELECT pin FROM users WHERE username = " . $username);

		$saved_pin  = array_map('intval', str_split($user_pin));
		$saved_pin = $saved_pin[$pos1 - 1] . $saved_pin[$pos2 - 1] . $saved_pin[$pos3 - 1];

		if($saved_pin == $pin)
			return 'valid';
		else
			return 'invalid';
	}




}
?>
<?php
define("WEBROOT", dirname(__DIR__));

// Increase memory limit
$memoryLimitBytes = 368 * 1024 * 1024;
ini_set('memory_limit', $memoryLimitBytes);
set_time_limit(0);

// Class autoloading
spl_autoload_register(function($class_name) {
    @include WEBROOT . '/htdocs/lib/' . $class_name . '.php'; // Sunesis library
});

if ((@include 'Zend/Loader/Autoloader.php')) {
    Zend_Loader_Autoloader::getInstance(); // Zend library (automatically registers autoloader on initialisation)
}

// Command line arguments
if (count($argv) < 4) {
    die("Usage: php send_duplex_emails.php {db_name} {username} {password}");
}
$db = $argv[1];
$user = $argv[2];
$pwd = $argv[3];
if (!$db) {
    die("Usage: php send_duplex_emails.php {db_name} {username} {password}");
}

echo "\n";

$host = '127.0.0.1';

try
{
    echo "\nestablishing connection\n";
    $link = new PDO("mysql:host=" . $host . ";dbname=" . $db . ";port=3306", $user, $pwd);
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "\nestablised connection\n";
}
catch(PDOException $e)
{
    die('ERROR: ' . $e->getMessage());
}

echo "\nstarting\n";

$learner_ids = EmailAutomationDuplex::getLearnersToSendWeeklyEmailAboutHsForm($link);
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 13, 'Please complete health & safety form to confirm your EV Training place');
    echo "\nFN1 (L3): email sent to " . count($learner_ids) . " learners\n";
}

$learner_ids = EmailAutomationDuplex::getLearnersWithImiCodeToSendJoingInstructions($link, 'L3');
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 2, 'Joining Instructions for your EV/Hybrid Training Course');
    echo "\nFN2 (L3): email sent to " . count($learner_ids) . " learners\n";
}

$learner_ids = EmailAutomationDuplex::getLearnersWithImiCodeToSendJoingInstructions($link, 'L4');
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 1, 'Joining Instructions for your EV/Hybrid Training Course');
    echo "\nFN2 (L4): email sent to " . count($learner_ids) . " learners\n";
}

$learner_ids = EmailAutomationDuplex::getWmpLearnersWithImiCodeToSendJoingInstructions($link, 'L3');
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 15, 'Joining Instructions for your EV/Hybrid Training Course');
    echo "\nFN3 (L3): email sent to " . count($learner_ids) . " learners\n";
}

$learner_ids = EmailAutomationDuplex::getWmpLearnersWithImiCodeToSendJoingInstructions($link, 'L4');
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 16, 'Joining Instructions for your EV/Hybrid Training Course');
    echo "\nFN3 (L4): email sent to " . count($learner_ids) . " learners\n";
}

$learner_ids = EmailAutomationDuplex::getLearnersToSend1WeekReminder($link, 'L3');
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 9, 'We\'re looking forward to seeing you next week!');
    echo "\nFN4 (L3): email sent to " . count($learner_ids) . " learners\n";
}

$learner_ids = EmailAutomationDuplex::getLearnersToSend1WeekReminder($link, 'L4');
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 10, 'We\'re looking forward to seeing you next week!');
    echo "\nFN4 (L4): email sent to " . count($learner_ids) . " learners\n";
}

$learner_ids = EmailAutomationDuplex::getLearnersToSend1DayToGo($link, 'L3');
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 11, 'Your EV Training takes place tomorrow');
    echo "\nFN5 (L3): email sent to " . count($learner_ids) . " learners\n";
}

$learner_ids = EmailAutomationDuplex::getLearnersToSend1DayToGo($link, 'L4');
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 12, 'Your EV Training takes place tomorrow');
    echo "\nFN5 (L4): email sent to " . count($learner_ids) . " learners\n";
}

$learner_ids = EmailAutomationDuplex::getLearnersToSend2WeekElearningReminder($link, 'L3');
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 18, 'Don\'t forget to start your EV training online');
    echo "\nFN6 (L3): email sent to " . count($learner_ids) . " learners\n";
}

$learner_ids = EmailAutomationDuplex::getLearnersToSend2WeekElearningReminder($link, 'L4');
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 19, 'Don\'t forget to start your EV training online');
    echo "\nFN6 (L4): email sent to " . count($learner_ids) . " learners\n";
}

$learner_ids = EmailAutomationDuplex::getLearnersToSendAfterCompletingCourseEmail($link);
if(count($learner_ids) > 0)
{
    EmailAutomationDuplex::sendEmail($link, $learner_ids, 17, 'Congratulations on completing your EV Training course!');
    echo "\nFN7: email sent to " . count($learner_ids) . " learners\n";
}

echo "\ncompleted\n";

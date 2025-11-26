<?php
function __autoload($class_name)
{
    require ("../htdocs/lib/{$class_name}.php");
}

set_time_limit(0);
ini_set("memory_limit", "8192M");

$folio_pwd = null;
$sunesis_pwd = null;
if (count($argv) < 3) {
    $handle = fopen("php://stdin", "r");

    echo "\nFolio Password: ";
    $folio_pwd = trim(fgets($handle));

    echo "\nSunesis Password: ";
    $sunesis_pwd = trim(fgets($handle));

    fclose($handle);
} else {
    $folio_pwd = $argv[1];
    $sunesis_pwd = $argv[2];
}

// Start new line
echo "\n";

$folio_host = 'pers-folio11.sensicalhosting.net';
$folio_db = "folio_ela";
$folio_user = "folio_ela";

$sunesis_host = '127.0.0.1';
$sunesis_db = "am_ela";
$sunesis_user = "am_ela";

try {
    $start = microtime(true);
    $start_log_time = date('Y-m-d H:i:s');

    echo "\nestablishing Folio connection\n";
    $folio_link = new PDO("mysql:host=" . $folio_host . ";dbname=" . $folio_db . ";port=3306", $folio_user, $folio_pwd);
    $folio_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $folio_link->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
    echo "\nestablised Folio connection\n";

    echo "\nestablishing Sunesis connection\n";
    $sunesis_link = new PDO("mysql:host=" . $sunesis_host . ";dbname=" . $sunesis_db . ";port=3306", $sunesis_user, $sunesis_pwd);
    $sunesis_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sunesis_link->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
    echo "\nestablised Sunesis connection\n";
} catch (PDOException $e) {
    die('ERROR: ' . $e->getMessage());
}

try {
    echo "\nprocess started\n";

    $tables_results = [];
    $timestamps = date('Y-m-d H:i:s');

    $time_elapsed_secs = microtime(true) - $start;
    $end_log_time = date('Y-m-d H:i:s');


    // tr ids
    $sql = "SELECT id AS folio_trid, sunesis_id AS sunesis_trid FROM tr where sunesis_id is not null;";
    $st = $folio_link->query($sql);
    if (!$st) {
        throw new DatabaseException($folio_link, $sql);
    }
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
 
    DAO::multipleRowInsert($sunesis_link, 'folio_sunesis', $rows);
    DAO::execute($sunesis_link,"update tr inner join folio_sunesis on tr.id = folio_sunesis.sunesis_trid set onefile_id = folio_sunesis.folio_trid");        

    echo "Progress started\n";
    // Progress
    $sql = "SELECT DISTINCT
  portfolios.tr_id as `ID`,
(SUM(IF(portfolio_pcs.assessor_signoff = 1 OR portfolio_pcs.accepted_evidences >= portfolio_pcs.min_req_evidences, 1, 0))/SUM(IF(1 = 1, 1, 0))*100) Progress
FROM
  portfolio_pcs
  INNER JOIN portfolio_units
    ON portfolio_pcs.portfolio_unit_id = portfolio_units.id
  INNER JOIN portfolios
    ON portfolio_units.portfolio_id = portfolios.id
   INNER JOIN tr ON portfolios.`tr_id` = tr.id
WHERE sunesis_id IS NOT NULL GROUP BY portfolios.tr_id;";

    $st = $folio_link->query($sql);
    if (!$st) {
        throw new DatabaseException($folio_link, $sql);
    }
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($rows);
    DAO::multipleRowInsert($sunesis_link, 'onefile_learners', $rows);

    echo "Progress completed\n";

    echo "TLAP started\n";

    // TLAP
    $sql = "SELECT tr_dp_sessions.id AS `ID`, tr.id AS `LearnerID`, assessor_sign_date AS `AssessorSignedOn`, session_start_date AS `PlanOn` FROM tr
    INNER JOIN tr_dp_sessions ON tr_dp_sessions.tr_id = tr.id
    WHERE sunesis_id IS NOT NULL";

    $st = $folio_link->query($sql);
    if (!$st) {
        throw new DatabaseException($folio_link, $sql);
    }
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    var_dump($rows);
    DAO::multipleRowInsert($sunesis_link, 'onefile_tlap', $rows);

    echo "TLAP completed\n";

    echo "Reviews started\n";

    // Reviews

    $sql = "SELECT training_reviews.id as `ID`, tr.id as `LearnerID`, training_review_forms.assessor_signed_at as `StartedOn`, due_date as `ScheduledFor` FROM training_review_forms
    INNER JOIN training_reviews ON training_reviews.id = review_id
    INNER JOIN tr ON tr.id = training_reviews.tr_id
    WHERE tr.`sunesis_id` IS NOT NULL AND training_review_forms.assessor_signed_at IS NOT NULL;";
   
    /*$sql = "SELECT tr.id as `ID`, tr.id as `LearnerID`, latest_date as `StartedOn` FROM tr
    INNER JOIN training_reviews ON training_reviews.tr_id = tr.id
    INNER JOIN (SELECT tr_id, MAX(assessor_signed_at) AS latest_date FROM training_reviews GROUP BY tr_id) 
    reviews ON reviews.tr_id = tr.id AND assessor_signed_at = reviews.latest_date
    WHERE sunesis_id IS NOT NULL;";*/

    $st = $folio_link->query($sql);
    if (!$st) {
        throw new DatabaseException($folio_link, $sql);
    }
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    DAO::multipleRowInsert($sunesis_link, 'onefile_reviews', $rows);

    echo "Reviews completed\n";

    echo "OTJ started\n";

    // OTJ
    $sql = "SELECT tr.id as onefile_learner_id, ROUND(SUM(TIME_TO_SEC(duration)) / 3600, 2) as actual_hours, otj_hours as planned_otj
FROM tr
INNER JOIN otj ON otj.tr_id = tr.id AND is_otj = 1
WHERE sunesis_id IS NOT NULL GROUP BY tr.id;
";

    $st = $folio_link->query($sql);
    if (!$st) {
        throw new DatabaseException($folio_link, $sql);
    }
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    DAO::multipleRowInsert($sunesis_link, 'onefile_otj', $rows);

    echo "OTJ Completed\n";

    echo "ALS started\n";

    // ALS
    $sql = "
SELECT 
als_reviews.id,
tr.sunesis_id as tr_id,
planned_date, 
date_of_review,
CONCAT(users.firstnames, ' ', users.surname) AS als_tutor
FROM als_reviews
LEFT JOIN tr ON tr.id = als_reviews.tr_id
LEFT JOIN users ON users.id = als_reviews.tutor
WHERE tr.sunesis_id IS NOT NULL    
";

    $st = $folio_link->query($sql);
    if (!$st) {
        throw new DatabaseException($folio_link, $sql);
    }
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    DAO::multipleRowInsert($sunesis_link, 'folio_als', $rows);

    echo "ALS Completed\n";

    // Progress Daily
    $sql = "SELECT DISTINCT
  portfolios.tr_id as `ID`,
(SUM(IF(portfolio_pcs.assessor_signoff = 1 OR portfolio_pcs.accepted_evidences >= portfolio_pcs.min_req_evidences, 1, 0))/SUM(IF(1 = 1, 1, 0))*100) Progress,
CURDATE() as ProgressDate
FROM
  portfolio_pcs
  INNER JOIN portfolio_units
    ON portfolio_pcs.portfolio_unit_id = portfolio_units.id
  INNER JOIN portfolios
    ON portfolio_units.portfolio_id = portfolios.id
   INNER JOIN tr ON portfolios.`tr_id` = tr.id
WHERE sunesis_id IS NOT NULL GROUP BY portfolios.tr_id;";

    $st = $folio_link->query($sql);
    if (!$st) {
        throw new DatabaseException($folio_link, $sql);
    }
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($rows);
    DAO::multipleRowInsert($sunesis_link, 'folio_progress', $rows);

    echo "Progress completed\n";



    echo "\nprocess completed in {$time_elapsed_secs} seconds.\n";
} catch (Exception $ex) {
    echo "\n" . $ex->getMessage() . "\n";
    exit;
}

$folio_link = null;
$sunesis_link = null;

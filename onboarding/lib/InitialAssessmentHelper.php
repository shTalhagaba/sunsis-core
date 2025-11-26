<?php

class InitialAssessmentHelper
{
    public static $mathTotalScores = array(
        'number' => 38,
        'measure' => 35,
        'handlingdata' => 32,
    );
    public static $englishTotalScores = array(
        'reading' => 33,
        'spag' => 12,
        'writing' => 14,
    );

    public static function generateUrl(PDO $link, $trainerId, $subject)
    {
        $assessment = self::getPendingAssessment($link, $trainerId, $subject);

        if ($assessment && $assessment->id) {
            $assessmentId = $assessment->id;
        } else {
            $data = [
                'id' => null,
                'tr_id' => $trainerId,
                'subject' => $subject,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
            ];

            DAO::saveObjectToTable($link, 'ob_tr_assessments', $data);
            $assessmentId = $data['id'];
        }

        $key = $assessmentId . "_sunesis_initial_assessment_key";

        return OnboardingHelper::getCurrentScriptUrl() . "?_action=form_learner_initial_assessment&subject={$subject}&key=" . md5($key);
    }

    public static function generateReTakeUrl(PDO $link, $trainerId, $subject, $assessment = null)
    {

        if ($assessment && $assessment->id && $assessment->status != 'completed') {
            $key = $assessment->id . "_sunesis_initial_assessment_key";

            return OnboardingHelper::getCurrentScriptUrl() . "?_action=form_learner_initial_assessment&subject={$subject}&key=" . md5($key);
        } else {
            $data = [
                'id' => null,
                'tr_id' => $trainerId,
                'subject' => $subject,
                'status' => 'pending',
                'create_at' => date('Y-m-d H:i:s'),
            ];

            DAO::saveObjectToTable($link, 'ob_tr_assessments', $data);

            $key = $data['id'] . "_sunesis_initial_assessment_key";

            return OnboardingHelper::getCurrentScriptUrl(). "?_action=form_learner_initial_assessment&subject={$subject}&key=" . md5($key);
        }

    }

    private static function getPendingAssessment(PDO $link, $trainerId, $subject)
    {
        $sql = "SELECT * FROM ob_tr_assessments WHERE `tr_id`= {$trainerId} and `subject`='{$subject}'  and `status`='pending' ORDER BY id DESC ";

        return DAO::getObject($link, $sql);
    }

    public static function getAssessmentById(PDO $link, $id)
    {
        $sql = "SELECT * FROM ob_tr_assessments WHERE id = '{$id}'";

        return DAO::getObject($link, $sql);
    }

    public static function getAssessmentByKey(PDO $link, $key)
    {
        $sql = "SELECT * FROM ob_tr_assessments WHERE MD5(CONCAT( id,'_sunesis_initial_assessment_key')) = '{$key}'";

        return DAO::getObject($link, $sql);
    }

    public static function timeDiff($start_at, $end_at, $format = 'H:i:s')
    {
        $startAt = new DateTime($start_at);
        $endAt = new DateTime($end_at);

        $seconds = $endAt->getTimestamp() - $startAt->getTimestamp();

        return gmdate($format, $seconds);
    }

    public static function getProgress($assessment_id, $link = null)
    {
        if (!$link) {
            $link = DAO::getConnection();
        }

        $sql = "SELECT qs.*, ans.answer as givin_answer, ans.correct FROM `ob_tr_assessment_answers` as ans";
        $sql .= " LEFT JOIN `ob_tr_questions` as qs on qs.id = ans.question_id";
        $sql .= " WHERE as_id = '{$assessment_id}'";
        $sql .= " ORDER BY topic = 'All' ";

        $subject = '';

        $questions = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

        $scores = array();
        if (!empty($questions)) {
            $subject = isset($questions[0]['subject']) ? $questions[0]['subject'] : '';

            $questionByTopic = Helpers::array_group_by($questions, array('topic'));

            $allTopic = isset($questionByTopic['All']) ? $questionByTopic['All'] : array();

            $allTopicTotal = array_sum(array_map(function ($ar) {
                return $ar['mark'];
            }, $allTopic));

            $allTopicGiven = array_sum(array_map(function ($ar) {
                return $ar['correct'] ? $ar['mark'] : 0;
            }, $allTopic));

            foreach ($questionByTopic as $topic => $question) {

                if (strtolower($topic) == 'all') {
                    continue;
                }

                $key = strtolower(str_replace(' ', '', $topic));
                if (isset(self::${$subject . 'TotalScores'}[$key])) {
                    $total = self::${$subject . 'TotalScores'}[$key];
                } else {
                    $total = array_sum(array_map(function ($ar) {
                            return $ar['mark'];
                        }, $question)) + $allTopicTotal;
                }

                $given = array_sum(array_map(function ($ar) {
                    return $ar['correct'] ? $ar['mark'] : 0;
                }, $question));

                $scores[$topic] = array(
                    'topic' => $topic,
                    'total' => $total,
                    'given' => $given + $allTopicGiven,
                );
            }

            foreach ($scores as $key => $row) {
                $percent = $row['total'] > 0 ? round(($row['given'] / $row['total']) * 100) : 0;

                $scores[$key]['percent'] = $percent;

                if ($subject == 'math') {
                    $scores[$key]['level'] = self::getMathLevel($row['topic'], $percent);
                } else {
                    $scores[$key]['level'] = self::getEnglishLevel($row['topic'], $percent);
                }
            }
        }

        $percentSum = array_sum(array_map(function ($ar) {
            return $ar['percent'];
        }, $scores));

        $overAllPercent = $percentSum ? $percentSum / count($scores) : 0;

        return [
            'scores' => $scores,
            'progress' => $subject == 'math' ? self::getOverallMathProgress($overAllPercent) : self::getOverallEnglishProgress($overAllPercent),
        ];
    }

    protected static function getMathLevel($topic, $percentage)
    {
        $data = array(
            'Number' => array(
                'Pre entry' => '0-4',
                'E1' => '5-10',
                'E2' => '11-22',
                'E3' => '23-38',
                'L1' => '39-65',
                'L2' => '66-100',
            ),
            'Measure' => array(
                'Pre entry' => '0-4',
                'E1' => '5-11',
                'E2' => '12-22',
                'E3' => '23-36',
                'L1' => '37-60',
                'L2' => '61-100',
            ),
            'Handling Data' => array(
                'Pre entry' => '0-4',
                'E1' => '5-12',
                'E2' => '13-20',
                'E3' => '21-30',
                'L1' => '31-60',
                'L2' => '61-100',
            ),
        );

        $levels = isset($data[$topic]) ? $data[$topic] : array();

        foreach ($levels as $key => $value) {
            list($min, $max) = array_map('trim', explode('-', $value));

            if ($percentage >= $min && $percentage <= $max) {
                return $key;
            }
        }
        return 0;
    }

    protected static function getEnglishLevel($topic, $percentage)
    {
        $data = [
            'Reading' => [
                'Pre entry' => '0-5',
                'Entry 1' => '6-12',
                'Entry 2' => '13-19',
                'Entry 3' => '20-50',
                'Level 1' => '51-83',
                'Level 2' => '84-100',
            ],
            'SPaG' => [
                'Pre entry' => '0-5',
                'Entry 1' => '6-12',
                'Entry 2' => '13-19',
                'Entry 3' => '20-50',
                'Level 1' => '51-83',
                'Level 2' => '84-100',
            ],
            'Writing' => [
                'Pre entry' => '0-8',
                'Entry 1' => '9-14',
                'Entry 2' => '15-29',
                'Entry 3' => '30-50',
                'Level 1' => '51-57',
                'Level 2' => '58-100',
            ],
        ];


        $levels = isset($data[$topic]) ? $data[$topic] : array();

        foreach ($levels as $key => $value) {
            list($min, $max) = array_map('trim', explode('-', $value));

            if ($percentage >= $min && $percentage <= $max) {
                return $key;
            }
        }

        return 0;
    }

    protected static function getOverallEnglishProgress($percentage)
    {
        $percentage = round($percentage);
        $data = [
            '0-5' => [
                'stage' => 'Pre-entry',
                'statement' => '',
            ],
            '6-7' => [
                'stage' => 'E1 Introduction',
                'statement' => 'Beginning to explore E1 content with tutor guidance',
            ],
            '8-9' => [
                'stage' => 'E1 Emerging',
                'statement' => 'Showing signs of understanding key E1 concepts',
            ],
            '10-11' => [
                'stage' => 'E1 Exploring',
                'statement' => 'Growing confidence in using E1 skills',
            ],
            '12-12' => [
                'stage' => 'E1 Competent',
                'statement' => 'Secure across most E1 skills and ready to progress',
            ],
            '13-15' => [
                'stage' => 'E2 Introduction',
                'statement' => 'Beginning to explore E2 content with tutor guidance',
            ],
            '16-18' => [
                'stage' => 'E2 Emerging',
                'statement' => 'Showing signs of understanding key E2 concepts',
            ],
            '19-22' => [
                'stage' => 'E2 Exploring',
                'statement' => 'Growing confidence in using E2 skills',
            ],
            '23-29' => [
                'stage' => 'E2 Competent',
                'statement' => 'Secure across most E2 skills and ready to progress',
            ],
            '30-34' => [
                'stage' => 'E3 Introduction',
                'statement' => 'Beginning to explore E3 content with tutor guidance',
            ],
            '35-42' => [
                'stage' => 'E3 Emerging',
                'statement' => 'Showing signs of understanding key E3 concepts',
            ],
            '43-47' => [
                'stage' => 'E3 Exploring',
                'statement' => 'Growing confidence in using E3 skills',
            ],
            '48-50' => [
                'stage' => 'E3 Competent',
                'statement' => 'Secure across most E3 skills and ready to progress',
            ],
            '51-59' => [
                'stage' => 'L1 Introduction',
                'statement' => 'Beginning to explore L1 content with tutor guidance',
            ],
            '60-67' => [
                'stage' => 'L1 Emerging',
                'statement' => 'Showing signs of understanding key L1 concepts',
            ],
            '68-75' => [
                'stage' => 'L1 Exploring',
                'statement' => 'Growing confidence in using L1 skills',
            ],
            '76-83' => [
                'stage' => 'L1 competent',
                'statement' => 'Secure across most L1 skills and ready to progress',
            ],
            '84-87' => [
                'stage' => 'L2 Introduction',
                'statement' => 'Beginning to explore L2 content with tutor guidance',
            ],
            '88-91' => [
                'stage' => 'L2 Emerging',
                'statement' => 'Showing signs of understanding key L2 concepts',
            ],
            '92-95' => [
                'stage' => 'L2 Exploring',
                'statement' => 'Growing confidence in using L2 skills',
            ],
            '96-100' => [
                'stage' => 'L2 competent',
                'statement' => 'Secure across most L2 skills and ready to progress',
            ],
        ];

        foreach ($data as $key => $value) {
            list($min, $max) = array_map('trim', explode('-', $key));

            if ($percentage >= $min && $percentage <= $max) {
                return $value;
            }
        }

        return null;


    }

    protected static function getOverallMathProgress($percentage)
    {
        $percentage = round($percentage);
        $data = [
            '0-4' => [
                'stage' => 'Pre-entry',
                'statement' => '',
            ],
            '5-6' => [
                'stage' => 'E1 Introduction',
                'statement' => 'Beginning to explore E1 content with tutor guidance',
            ],
            '7-7' => [
                'stage' => 'E1 Emerging',
                'statement' => 'Showing signs of understanding key E1 concepts',
            ],
            '8-8' => [
                'stage' => 'E1 Exploring',
                'statement' => 'Growing confidence in using E1 skills',
            ],
            '9-10' => [
                'stage' => 'E1 Competent',
                'statement' => 'Secure across most E1 skills and ready to progress',
            ],
            '11-13' => [
                'stage' => 'E2 Introduction',
                'statement' => 'Beginning to explore E2 content with tutor guidance',
            ],
            '14-16' => [
                'stage' => 'E2 Emerging',
                'statement' => 'Showing signs of understanding key E2 concepts',
            ],
            '17-19' => [
                'stage' => 'E2 Exploring',
                'statement' => 'Growing confidence in using E2 skills',
            ],
            '20-22' => [
                'stage' => 'E2 Competent',
                'statement' => 'Secure across most E2 skills and ready to progress',
            ],
            '23-26' => [
                'stage' => 'E3 Introduction',
                'statement' => 'Beginning to explore E3 content with tutor guidance',
            ],
            '27-30' => [
                'stage' => 'E3 Emerging',
                'statement' => 'Showing signs of understanding key E3 concepts',
            ],
            '31-34' => [
                'stage' => 'E3 Exploring',
                'statement' => 'Growing confidence in using E3 skills',
            ],
            '35-38' => [
                'stage' => 'E3 Competent',
                'statement' => 'Secure across most E3 skills and ready to progress',
            ],
            '39-45' => [
                'stage' => 'L1 Introduction',
                'statement' => 'Beginning to explore L1 content with tutor guidance',
            ],
            '46-52' => [
                'stage' => 'L1 Emerging',
                'statement' => 'Showing signs of understanding key L1 concepts',
            ],
            '53-58' => [
                'stage' => 'L1 Exploring',
                'statement' => 'Growing confidence in using L1 skills',
            ],
            '59-65' => [
                'stage' => 'L1 Competent',
                'statement' => 'Secure across most L1 skills and ready to progress',
            ],
            '66-74' => [
                'stage' => 'L2 Introduction',
                'statement' => 'Beginning to explore L2 content with tutor guidance',
            ],
            '75-83' => [
                'stage' => 'L2 Emerging',
                'statement' => 'Showing signs of understanding key L2 concepts',
            ],
            '84-91' => [
                'stage' => 'L2 Exploring',
                'statement' => 'Growing confidence in using L2 skills',
            ],
            '92-100' => [
                'stage' => 'L2 Competent',
                'statement' => 'Secure across most L2 skills and ready to progress',
            ],
        ];


        foreach ($data as $key => $value) {
            list($min, $max) = array_map('trim', explode('-', $key));

            if ($percentage >= $min && $percentage <= $max) {
                return $value;
            }
        }

        return null;


    }

    public static function replaceImagePath($content, $path)
    {
        $path = rtrim(str_replace('\\', '/', $path), '/');

        if (!$path) {
            return $content;
        }

        return preg_replace_callback('/<img\s+[^>]*src=["\']([^"\']+)["\']/i', function ($matches) use ($path) {
            $src = $matches[1];

            // If already contains the correct path, skip
            if (strpos($src, $path) === 0) {
                return $matches[0];
            }

            // Get the file name from src
            $filename = basename($src);

            // Build new src
            $newSrc = $path . '/' . $filename;

            // Replace the old src with the new one
            return str_replace($src, $newSrc, $matches[0]);
        }, $content);
    }
}

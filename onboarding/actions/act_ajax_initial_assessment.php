<?php

class ajax_initial_assessment extends ActionController implements IUnauthenticatedAction
{
    public function indexAction(PDO $link)
    {
        // This function is intentionally left blank
    }

    protected function startSessionAction(PDO $link)
    {
        $this->validate([
            'tr_id' => 'Trainer id is required',
            'subject' => 'Subject is required',
        ]);

        $subject = $this->_getParam('subject');
        $trainerId = $this->_getParam('tr_id');

        $assessmentId = $this->_getParam('as_id');

        $assessment = $this->getAssessmentById($link, $assessmentId);

        $assessmentId = $assessment && $assessment->id ? $assessment->id : null;

        $data = [
            'id' => $assessmentId,
            'tr_id' => $trainerId,
            'subject' => $subject,
            'start_at' => date('Y-m-d H:i:s'),
            'status' => 'started',
            'end_at' => '',
        ];

        DAO::saveObjectToTable($link, 'ob_tr_assessments', $data);

        echo json_encode([
            'id' => $data['id'],
            'start_at' => $data['start_at'],
        ]);
    }

    protected function endSessionAction(PDO $link)
    {
        $this->validate([
            'as_id' => 'Assessment id is required',
        ]);

        $assessmentId = $this->_getParam('as_id');

        $assessment = $this->getAssessmentById($link, $assessmentId);

        $data = [
            'id' => $assessment->id,
            'end_at' => date('Y-m-d H:i:s'),
            'status' => 'completed',
        ];

        DAO::saveObjectToTable($link, 'ob_tr_assessments', $data);

        $progress = InitialAssessmentHelper::getProgress($assessment->id);

        $output = [
            'start_date' => date('d/m/Y', strtotime($assessment->start_at)),
            'start_time' => date('H:i:s', strtotime($assessment->start_at)),
            'end_date' => date('d/m/Y', strtotime($data['end_at'])),
            'end_time' => date('H:i:s', strtotime($data['end_at'])),
            'time_diff' => InitialAssessmentHelper::timeDiff($assessment->start_at, $data['end_at']),
            'stage' => isset($progress['progress']['stage']) ? $progress['progress']['stage'] : '',
            'statement' => isset($progress['progress']['statement']) ? $progress['progress']['statement'] : '',
            'scores' => $progress['scores'],
        ];

        echo json_encode(array_merge($output, $progress));
    }

    protected function saveQuestionsAction(PDO $link)
    {
        $this->validate([
            'as_id' => 'Assessment id is required',
            'stage' => 'Stage is required',
            'answers' => 'Answers are required',
        ]);

        $assessmentId = $this->_getParam('as_id');
        $answers = $this->_getParam('answers');
        $currentStage = $this->_getParam('stage');
        $currentLevel = $this->_getParam('level');

        $questionIds = implode(',', array_keys($answers));

        $questions = $this->getQuestionsByIds($link, $questionIds);

        $subject = '';
        $totalMarks = 0;

        foreach ($questions as $question) {
            $subject = $question['subject'];
            $answer = trim($answers[$question['id']]);
            $isCorrect = $this->isAnswerCorrect($answer, $question);
            $marks = $isCorrect ? $question['mark'] : 0;
            $totalMarks += $marks;

            $data = [
                'question_id' => $question['id'],
                'stage' => $question['stage'],
                'as_id' => $assessmentId,
                'answer' => $answer,
                'mark' => $marks,
                'correct' => $isCorrect ? 1 : 0,
            ];

            $this->saveAssessmentAnswer($link, $data);
        }

        $nextLevel = $this->getNextLevel($subject, $totalMarks, $currentStage, $currentLevel);

        echo json_encode([
            'level' => $nextLevel,
            'success' => true,
            //'marks' => $totalMarks,
        ]);
    }

    protected function getNextLevel($subject, $marks, $currentStage, $stageLevel = null)
    {
        $newStage = $currentStage + 1;

        $levels = [
            'english' => [
                1 => [
                    0 => "E1",
                    1 => "E1",
                    2 => "E2",
                    3 => "E2",
                    4 => "E3",
                    5 => "E3",
                    6 => "E3",
                    7 => "E3",
                    8 => "L1",
                    9 => "L1",
                    10 => "L2",
                    11 => "L2",
                    12 => "L2",
                    13 => "L2",
                    14 => "L2",
                    15 => "L2",
                ],
                2 => [
                    'E1' => [
                        1 => "E1",
                        2 => "E1",
                        3 => "E2",
                        4 => "E2",
                        5 => "E2",
                        6 => "E2",
                        7 => "E3",
                        8 => "E3",
                        9 => "E3",
                    ],
                    'E2' => [
                        0 => "E1",
                        1 => "E2",
                        2 => "E2",
                        3 => "E2",
                        4 => "E2",
                        5 => "E3",
                        6 => "E3",
                        7 => "E3",
                        8 => "E3",
                        9 => "E3",
                        10 => "E3",
                        11 => "L1",
                        12 => "L1",
                        13 => "L1",
                        14 => "L1",
                    ],
                    'E3' => [
                        0 => "E1",
                        1 => "E1",
                        2 => "E1",
                        3 => "E2",
                        4 => "E2",
                        5 => "E2",
                        6 => "E3",
                        7 => "E3",
                        8 => "E3",
                        9 => "E3",
                        10 => "L1",
                        11 => "L1",
                        12 => "L1",
                        13 => "L1",
                        14 => "L1",
                        15 => "L1",
                        16 => "L2",
                        17 => "L2",
                        18 => "L2",
                        19 => "L2",
                    ],
                    'L1' => [
                        0 => 'E2',
                        1 => 'E2',
                        2 => 'E2',
                        3 => 'E3',
                        4 => 'E3',
                        5 => 'E3',
                        6 => 'E3',
                        7 => 'L1',
                        8 => 'L1',
                        9 => 'L1',
                        10 => 'L1',
                        11 => 'L1',
                        12 => 'L1',
                        13 => 'L1',
                        14 => 'L1',
                        15 => 'L1',
                        16 => 'L1',
                        17 => 'L2',
                        18 => 'L2',
                        19 => 'L2',
                        20 => 'L2',
                        21 => 'L2',
                    ],
                    'L2' => [
                        0 => "E2",
                        1 => "E2",
                        2 => "E2",
                        3 => "E3",
                        4 => "E3",
                        5 => "E3",
                        6 => "E3",
                        7 => "L1",
                        8 => "L1",
                        9 => "L1",
                        10 => "L1",
                        11 => "L1",
                        12 => "L1",
                        13 => "L1",
                        14 => "L1",
                        15 => "L1",
                        16 => "L1",
                        17 => "L2",
                        18 => "L2",
                        19 => "L2",
                        20 => "L2",
                        21 => "L2",
                    ]
                ]
            ],
            'math' => [
                1 => [
                    0 => "E1",
                    1 => "E1",
                    2 => "E2",
                    3 => "E2",
                    4 => "E3",
                    5 => "E3",
                    6 => "E3",
                    7 => "E3",
                    8 => "L1",
                    9 => "L1",
                    10 => "L2",
                    11 => "L2",
                    12 => "L2",
                    13 => "L2",
                    14 => "L2",
                    15 => "L2",
                ],
                2 => [
                    'E1' => [
                        0 => "E1",
                        1 => "E1",
                        2 => "E1",
                        3 => "E2",
                        4 => "E2",
                        5 => "E2",
                        6 => "E3",
                        7 => "E3",
                        8 => "E3",
                        9 => "E3",
                    ],
                    'E2' => [
                        0 => "E1",
                        2 => "E1",
                        3 => "E1",
                        4 => "E2",
                        5 => "E2",
                        6 => "E2",
                        7 => "E3",
                        8 => "E2",
                        9 => "E2",
                        10 => "E3",
                        11 => "E3",
                        12 => "E3",
                        13 => "L1",
                        14 => "L1",
                    ],
                    'E3' => [
                        0 => "E1",
                        2 => "E2",
                        3 => "E2",
                        4 => "E3",
                        5 => "E3",
                        6 => "E3",
                        7 => "E3",
                        8 => "E3",
                        9 => "E3",
                        10 => "E3",
                        11 => "L1",
                        12 => "L1",
                        13 => "L1",
                        14 => "L1",
                        15 => "L2",
                        16 => "L2",
                        17 => "L2",
                        18 => "L2",
                    ],
                    'L1' => [
                        0 => "E2",
                        1 => "E2",
                        2 => "E2",
                        3 => "E3",
                        4 => "E3",
                        5 => "E3",
                        6 => "E3",
                        7 => "L1",
                        8 => "L1",
                        9 => "L1",
                        10 => "L1",
                        11 => "L1",
                        12 => "L1",
                        13 => "L1",
                        14 => "L1",
                        15 => "L1",
                        16 => "L2",
                        17 => "L2",
                        18 => "L2",
                        19 => "L2",
                        20 => "L2",
                        21 => "L2",
                    ],
                    'L2' => [
                        0 => "E2",
                        1 => "E2",
                        2 => "E2",
                        3 => "E3",
                        4 => "E3",
                        5 => "E3",
                        6 => "E3",
                        7 => "L1",
                        8 => "L1",
                        9 => "L1",
                        10 => "L1",
                        11 => "L1",
                        12 => "L1",
                        13 => "L1",
                        14 => "L1",
                        15 => "L1",
                        16 => "L2",
                        17 => "L2",
                        18 => "L2",
                        19 => "L2",
                        20 => "L2",
                        21 => "L2",
                    ]
                ],
            ]
        ];

        $stageConfig = isset($levels[$subject][$currentStage]) ? $levels[$subject][$currentStage] : null;

        if (!$stageConfig) {
            return null;
        }

        if ($currentStage == 1) {
            return $newStage . '_' . (isset($stageConfig[$marks]) ? $stageConfig[$marks] : $stageConfig[0]);
        }

        return $newStage . '_' . (isset($stageConfig[$stageLevel][$marks]) ? $stageConfig[$stageLevel][$marks] : $stageConfig['E1'][$marks]);
    }

    protected function isValidSubject(PDO $link, $subject)
    {
        $subjects = DAO::getSingleColumn($link,
            "SELECT distinct subject FROM ob_tr_questions where subject IS NOT NULL");

        return !empty($subjects) && in_array($subject, $subjects);
    }

    protected function validate($rules)
    {
        $errors = [];
        foreach ($rules as $field => $message) {
            $value = $this->_getParam($field);
            if (is_null($value) || empty($value) || $value == 'null') {
                $errors[] = $message;
            } elseif ($field == 'subject') {
                if (!$this->isValidSubject($this->_link, $value)) {
                    $errors[] = 'Invalid subject provided.';
                }
            }
        }

        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => implode(', ', $errors)
            ]);
            exit();
        }
    }

    private function getAssessment(PDO $link, $trainerId, $subject)
    {
        $sql = "SELECT * FROM ob_tr_assessments WHERE `tr_id`= {$trainerId} and `subject`='{$subject}' ";
        return DAO::getObject($link, $sql);
    }

    private function getAssessmentById(PDO $link, $assessmentId)
    {
        $sql = "SELECT * FROM ob_tr_assessments WHERE `id`= {$assessmentId}";
        return DAO::getObject($link, $sql);
    }

    private function deleteExistingAnswers(PDO $link, $assessmentId)
    {
        $sql = "DELETE FROM ob_tr_assessment_answers WHERE `as_id` = '{$assessmentId}'";
        DAO::execute($link, $sql);
    }

    private function getQuestionsByIds(PDO $link, $questionIds)
    {
        $sql = "SELECT * FROM ob_tr_questions WHERE id IN ({$questionIds})";
        return DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
    }

    private function isAnswerCorrect($answer, $question)
    {
        $acceptableAnswers = $question['acceptable_answers'];

        $answer = strtolower(trim($answer));
        $correctAnswer = strtolower(trim($question['answer']));
        $acceptableAnswers = array_map('trim', explode(',', strtolower($acceptableAnswers)));

        return $answer == $correctAnswer || in_array($answer, $acceptableAnswers);
    }

    private function saveAssessmentAnswer(PDO $link, &$data)
    {
        $questionId = $data['question_id'];
        $assessmentId = $data['as_id'];

        $exist = DAO::getObject($link,
            "SELECT * FROM ob_tr_assessment_answers WHERE `question_id` = {$questionId} and  `as_id`= '{$assessmentId}'");

        if ($exist && $exist->id) {
            $data['id'] = $exist->id;
        }

        DAO::saveObjectToTable($link, 'ob_tr_assessment_answers', $data);
    }

}
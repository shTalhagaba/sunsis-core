<?php
class home_page extends ActionController
{
    public function indexAction(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=home_page", "Home");

        $toastr_message = '';
        if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '_action=login')) {
            $toastr_message = 'Welcome back, <b>' . $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname . '</b>';
            $toastr_message .= '<br>Your last login: ' . DAO::getSingleValue($link, "SELECT DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') FROM logins WHERE username = '" . $_SESSION['user']->username . "' ORDER BY id DESC LIMIT 1,1");
        }

        if (in_array(DB_NAME, ['am_eet', 'am_ela', 'am_puzzled', 'am_demo'])) {
            $aebStats = [
                'awaitingLearnerEnrolmentForm' => DAO::getSingleColumn(
                        $link,
                        "SELECT ob_tr.id FROM ob_tr INNER JOIN frameworks ON ob_tr.framework_id = frameworks.id 
                    WHERE ob_tr.learner_sign IS NULL AND frameworks.fund_model = '" . Framework::FUNDING_STREAM_ASF . "'"
                    ),
                'bespokeTpUnsignedByLearner' => DAO::getSingleColumn(
                        $link,
                        "SELECT tr_id FROM ob_learner_bespoke_training_plan 
                    INNER JOIN ob_tr ON ob_learner_bespoke_training_plan.`tr_id` = ob_tr.`id`
                    INNER JOIN frameworks ON ob_tr.`framework_id` = frameworks.`id`
                    WHERE ob_learner_bespoke_training_plan.`learner_sign` IS NULL AND frameworks.`fund_model` = '" . Framework::FUNDING_STREAM_ASF . "'"
                    ),
                'bespokeTpUnsignedByProvider' => DAO::getSingleColumn(
                        $link,
                        "SELECT tr_id FROM ob_learner_bespoke_training_plan 
                    INNER JOIN ob_tr ON ob_learner_bespoke_training_plan.`tr_id` = ob_tr.`id`
                    INNER JOIN frameworks ON ob_tr.`framework_id` = frameworks.`id`
                    WHERE ob_learner_bespoke_training_plan.`provider_sign` IS NULL AND frameworks.`fund_model` = '" . Framework::FUNDING_STREAM_ASF . "'"
                    ),
                'readyToSignEmployerForm' => DAO::getSingleColumn(
                        $link,
                        "SELECT ob_tr.id FROM ob_tr INNER JOIN frameworks ON ob_tr.framework_id = frameworks.id 
                    WHERE ob_tr.learner_sign IS NOT NULL AND ob_tr.tp_sign IS NULL AND frameworks.fund_model = '" . Framework::FUNDING_STREAM_ASF . "'"
                    ),
            ];

            $bcStats = [
                'awaitingLearnerEnrolmentForm' => DAO::getSingleColumn(
                        $link,
                        "SELECT ob_tr.id FROM ob_tr INNER JOIN frameworks ON ob_tr.framework_id = frameworks.id 
                    WHERE ob_tr.learner_sign IS NULL AND frameworks.fund_model = '" . Framework::FUNDING_STREAM_BOOTCAMP . "'"
                    ),
                'bespokeTpUnsignedByLearner' => DAO::getSingleColumn(
                        $link,
                        "SELECT tr_id FROM ob_learner_bespoke_training_plan 
                    INNER JOIN ob_tr ON ob_learner_bespoke_training_plan.`tr_id` = ob_tr.`id`
                    INNER JOIN frameworks ON ob_tr.`framework_id` = frameworks.`id`
                    WHERE ob_learner_bespoke_training_plan.`learner_sign` IS NULL AND frameworks.`fund_model` = '" . Framework::FUNDING_STREAM_BOOTCAMP . "'"
                    ),
                'bespokeTpUnsignedByProvider' => DAO::getSingleColumn(
                        $link,
                        "SELECT tr_id FROM ob_learner_bespoke_training_plan 
                    INNER JOIN ob_tr ON ob_learner_bespoke_training_plan.`tr_id` = ob_tr.`id`
                    INNER JOIN frameworks ON ob_tr.`framework_id` = frameworks.`id`
                    WHERE ob_learner_bespoke_training_plan.`provider_sign` IS NULL AND frameworks.`fund_model` = '" . Framework::FUNDING_STREAM_BOOTCAMP . "'"
                    ),
                'readyToSignEmployerForm' => DAO::getSingleColumn(
                        $link,
                        "SELECT ob_tr.id FROM ob_tr INNER JOIN frameworks ON ob_tr.framework_id = frameworks.id 
                    WHERE ob_tr.learner_sign IS NOT NULL AND ob_tr.tp_sign IS NULL AND frameworks.fund_model = '" . Framework::FUNDING_STREAM_BOOTCAMP . "'"
                    ),
            ];
        }

        $llStats = [
            'awaitingLearnerEnrolmentForm' => DAO::getSingleColumn(
                    $link,
                    "SELECT ob_tr.id FROM ob_tr INNER JOIN frameworks ON ob_tr.framework_id = frameworks.id 
                WHERE ob_tr.learner_sign IS NULL AND frameworks.fund_model_extra = '" . Framework::FUNDING_STREAM_LEARNER_LOAN . "'"
                ),
            'readyToSignEmployerForm' => DAO::getSingleColumn(
                    $link,
                    "SELECT ob_tr.id FROM ob_tr INNER JOIN frameworks ON ob_tr.framework_id = frameworks.id 
                WHERE ob_tr.learner_sign IS NOT NULL AND ob_tr.tp_sign IS NULL AND frameworks.fund_model_extra = '" . Framework::FUNDING_STREAM_LEARNER_LOAN . "'"
                ),
        ];
        
        $comStats = [
            'awaitingLearnerEnrolmentForm' => DAO::getSingleColumn(
                    $link,
                    "SELECT ob_tr.id FROM ob_tr INNER JOIN frameworks ON ob_tr.framework_id = frameworks.id 
                WHERE ob_tr.learner_sign IS NULL AND frameworks.fund_model_extra = '" . Framework::FUNDING_STREAM_COMMERCIAL . "'"
                ),
            'readyToSignEmployerForm' => DAO::getSingleColumn(
                    $link,
                    "SELECT ob_tr.id FROM ob_tr INNER JOIN frameworks ON ob_tr.framework_id = frameworks.id 
                WHERE ob_tr.learner_sign IS NOT NULL AND ob_tr.tp_sign IS NULL AND frameworks.fund_model_extra = '" . Framework::FUNDING_STREAM_COMMERCIAL . "'"
                ),
        ];


        include_once 'tpl_home_page.php';
    }

    public function quickSearchEmployerAction(PDO $link)
    {
        if (isset($_REQUEST['quickSearchEmployer'])) {
            $q = [
                'ViewEmployers_filter_legal_name' => $_REQUEST['txtSearchEmployer'],
                'ViewEmployers_filter_active' => 1, // Show all
                'ViewEmployers_' . View::KEY_PAGE_SIZE => 0, // No limit
                '_reset' => 1,
            ];

            http_redirect('do.php?_action=view_employers&' . http_build_query($q));
        }

        http_redirect('do.php?_action=home_page');
    }

    public function quickSearchLearnerAction(PDO $link)
    {
        if (isset($_REQUEST['quickSearchLearner'])) {
            $q = [
                'ViewTrainingRecords_filter_firstnames' => $_REQUEST['txtSearchLearnerFirstname'],
                'ViewTrainingRecords_filter_surname' => $_REQUEST['txtSearchLearnerSurname'],
                'ViewTrainingRecords_filter_status' => '', // No limit
                'ViewTrainingRecords_' . View::KEY_PAGE_SIZE => 0, // No limit
                '_reset' => 1,
            ];

            http_redirect('do.php?_action=view_training_records&' . http_build_query($q));
        }

        http_redirect('do.php?_action=home_page');
    }
}

<?php
class home_page_duplex extends ActionController
{
    public function indexAction(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=home_page_duplex", "Home");

        $toastr_message = '';
        if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '_action=login'))
        {
            $toastr_message = 'Welcome back, <b>' . $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname . '</b>';
            $toastr_message .= '<br>Your last login: ' . DAO::getSingleValue($link, "SELECT DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') FROM logins WHERE username = '" . $_SESSION['user']->username . "' ORDER BY id DESC LIMIT 1,1");
        }

        include_once 'tpl_home_page_duplex.php';
    }

    public function quickSearchEmployerAction(PDO $link)
    {
        if(isset($_REQUEST['quickSearchEmployer']))
        {
            $q = [
                'ViewEmployersV2_filter_legal_name' => $_REQUEST['txtSearchEmployer'],
                'ViewEmployersV2_filter_employer_telephone' => isset($_REQUEST['txtSearchEmployerTelephone']) ? str_replace(' ', '', trim($_REQUEST['txtSearchEmployerTelephone'])) : '',
		'ViewEmployersV2_filter_employer_contact_telephone' => isset($_REQUEST['txtSearchEmployerContactTelephone']) ? str_replace(' ', '', trim($_REQUEST['txtSearchEmployerContactTelephone'])) : '',
                'ViewEmployersV2_filter_active' => 1, // Show all
                'ViewEmployersV2_'.View::KEY_PAGE_SIZE => 0, // No limit
                '_reset' => 1,
            ];

            http_redirect('do.php?_action=view_employers&'.http_build_query($q));
        }

        http_redirect('do.php?_action=home_page_duplex');
    }

    public function quickSearchLearnerAction(PDO $link)
    {
        if(isset($_REQUEST['quickSearchLearner']))
        {
            $q = [
                'ViewLearnersV2_filter_learner_firstnames' => $_REQUEST['txtSearchLearnerFirstname'],
                'ViewLearnersV2_filter_learner_surname' => $_REQUEST['txtSearchLearnerSurname'],
                'ViewLearnersV2_filter_learner_mobile' => isset($_REQUEST['txtSearchLearnerMobile']) ? str_replace(' ', '', trim($_REQUEST['txtSearchLearnerMobile'])) : '',
                'ViewLearnersV2_'.View::KEY_PAGE_SIZE => 0, // No limit
                '_reset' => 1,
            ];

            http_redirect('do.php?_action=view_learners&'.http_build_query($q));
        }

        http_redirect('do.php?_action=home_page_duplex');
    }
}
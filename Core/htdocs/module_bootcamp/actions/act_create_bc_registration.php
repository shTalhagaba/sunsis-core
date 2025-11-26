<?php
class create_bc_registration implements IAction
{
	public function execute(PDO $link)
	{
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
        if($subaction == 'create')
        {
            $this->create($link, $_POST);

            http_redirect('do.php?_action=view_bc_registrations');
        }

        $_SESSION['bc']->add($link, "do.php?_action=create_bc_registration", "Create Applicant");

        $courses = DAO::getResultset($link, "SELECT courses.id, courses.title, null FROM courses WHERE courses.active = 1 ORDER BY courses.title");

        include_once('tpl_create_bc_registration.php');
    }

    private function create(PDO $link, $data)
    {
        $registration = new Registration();
        $registration->firstnames = isset($data['firstnames']) ? $data['firstnames'] : '';
        $registration->surname = isset($data['surname']) ? $data['surname'] : '';
        $registration->home_email = isset($data['home_email']) ? $data['home_email'] : '';
        $registration->course_id = isset($data['course_id']) ? $data['course_id'] : '';

        $registration->save($link);


        $template = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'BC_REGISTRATION_FORM_URL'");
        $url = BootcampHelper::getBootcampRegistrationUrl($registration->id);
        $template = str_replace('$$BC_REGISTRATION_FORM_URL$$', $url, $template);
        $template = str_replace('$$CLIENT_TELEPHONE$$', '0121506XXXX', $template);
        $template = str_replace('$$CLIENT_EMAIL$$', 'support@perspective-uk.com', $template);
        
        Emailer::html_mail(
            $registration->home_email,
            'no-reply@perspective-uk.com',
            'Bootcamp Registration Form',
            '',
            $template
        );

    }
}
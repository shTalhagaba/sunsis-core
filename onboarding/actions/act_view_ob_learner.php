<?php
class view_ob_learner implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        if(!$id)
        {
            throw new Exception("Missing or empty querystring argument 'id'");
        }

        $vo = OnboardingLearner::loadFromDatabase($link, $id);
        if (is_null($vo))
        {
            throw new Exception("No user with id '$id'");
        }
	if(DB_NAME == "am_ela")
        {
            if($_SESSION['user']->learners_caseload == 0)
            {
                // do nothing
            }
            elseif($_SESSION['user']->learners_caseload != $vo->caseload_org_id)
            {
                throw new UnauthorizedException("You are not authorised to view this record.");
            }
        }

        $_SESSION['bc']->add($link, "do.php?_action=view_ob_learner&id={$id}", "View Onboarding Learner");

        include('tpl_view_ob_learner.php');
    }

    public function renderFileRepository($vo)
    {
        $repository = Repository::getRoot().'/OnboardingModule/ob_learners/'.$vo->id;
        $files = Repository::readDirectory($repository);

        if(count($files) > 0)
        {
            echo '<div class="row is-flex">';
            foreach($files as $f)
            {
                if($f->isDir()){
                    continue;
                }
                $ext = new SplFileInfo($f->getName());
                $ext = $ext->getExtension();
                $image = 'fa-file';
                if($ext == 'doc' || $ext == 'docx')
                    $image = 'fa-file-word-o';
                elseif($ext == 'pdf')
                    $image = 'fa-file-pdf-o';
                elseif($ext == 'txt')
                    $image = 'fa-file-text-o';

                $html = '<li class="list-group-item">';
                $html .= '<i class="fa '.$image.'"></i> ' . htmlspecialchars($f->getName());
                $html .= '<br><span class="direct-chat-timestamp"><i class="fa fa-clock-o"></i> ' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</span>';
                $html .= '<br><span class="direct-chat-timestamp"><i class="fa fa-folder"></i> ' . Repository::formatFileSize($f->getSize()) .'</span>';

                $html .= '<br><p><span title="Download file" class="btn btn-xs btn-info" onclick="window.location.href=\''.$f->getDownloadURL().'\';"><i class="fa fa-download"></i></span>';

                echo '</li>';
                echo <<<HTML
<div class="col-sm-6">
	$html
</div>
HTML;
            }
            echo '</div> ';
        }
        else
        {
            echo '<p><br></p><div class="panel panel-info"><i class="fa fa-info-circle"></i> No files.</div> ';
        }
    }
}
?>
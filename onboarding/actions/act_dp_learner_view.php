<?php
class dp_learner_view implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : ''; 
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($tr_id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidDpLearnerViewUrl($link, $tr_id, $key))
            {
                OnboardingHelper::generateErrorPage($link);
                exit;
            }
        }
        else
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $dp_signatures = DAO::getObject($link, "SELECT * FROM delivery_plan_signatures WHERE tr_id = '{$tr->id}'");
        if(isset($dp_signatures->learner_sign) && $dp_signatures->learner_sign != '')
        {
            OnboardingHelper::generateAlreadyCompletedPage($link, $tr->id);
            exit;
        }

        $ob_learner = $tr->getObLearnerRecord($link);

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');

        $scroll_logic = 1;

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);



        include_once('tpl_dp_learner_view.php');
    }

    public function renderFileRepository(TrainingRecord $vo)
    {
        $repository = $vo->getDirectoryPath() . 'delivery_plan';
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

                $html .= '<br><p>';
                $html .= '<span title="Download file" class="btn btn-xs btn-info" onclick="window.location.href=\''.$f->getDownloadURL().'\';"><i class="fa fa-download"></i></span>';
                $html .= '</p>';

                echo '</li>';
                echo <<<HTML
<div class="col-sm-12">
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
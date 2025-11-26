<?php
class api_upload_file implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        //if(DB_NAME == "am_barnsley")
        //{
        //    echo json_encode(['status' => 'error', 'description' => 'API is not switched on for this site.'], JSON_PRETTY_PRINT);
        //    return;
        //}

        $accessKey = isset($_POST['accessKey']) ? $_POST['accessKey'] : '';
        $secret = isset($_POST['secret']) ? $_POST['secret'] : '';

        if($accessKey == '') {
            echo json_encode(['status' => 'error', 'description' => 'Missing access key'], JSON_PRETTY_PRINT);
            return;
        };
        if($secret == '') {
            echo json_encode(['status' => 'error', 'description' => 'Missing secret key'], JSON_PRETTY_PRINT);
            return;
        };

        $key = SystemConfig::getEntityValue($link, "barnsley_api_key");
        if(md5("barnsley_export{$accessKey}{$secret}") != $key){
            echo json_encode(['status' => 'error', 'description' => 'Invalid credentials'], JSON_PRETTY_PRINT);
            return;
        }

        $entity = isset($_POST['entity']) ? $_POST['entity'] : '';
        if($entity == '' || !in_array($entity, ["employer", "learner"]))
        {
            echo json_encode(['status' => 'error', 'description' => 'Missing or invalid entity field. Possible values ["employer", "learner"]'], JSON_PRETTY_PRINT);
            return;
        }

        if(count($_FILES) == 0)
        {
            echo json_encode(['status' => 'error', 'description' => 'Missing input file'], JSON_PRETTY_PRINT);
            return;
        }

        if(!isset($_FILES['file_content']))
        {
            echo json_encode(['status' => 'error', 'description' => 'Missing input file. Expecting parameter "file_content"'], JSON_PRETTY_PRINT);
            return;
        }

        if($entity == 'employer')
        {
            $upload_file_result = $this->processFileUploads('file_content', '/DataImports/employers', ["csv"]);
            if(isset($upload_file_result['status']) && $upload_file_result['status'] != '')
            {
                echo json_encode($upload_file_result, JSON_PRETTY_PRINT);
                return;
            }

            $file = new RepositoryFile($upload_file_result[0]);

            $import_result = $this->importEmployersFromDirectoryAction($link, $file);

            if(isset($import_result['status']) && $import_result['status'] == 'error')
            {
                echo json_encode($import_result, JSON_PRETTY_PRINT);
                return;
                //throw new Exception(json_encode($import_result, JSON_PRETTY_PRINT));
            }

            echo json_encode($import_result, JSON_PRETTY_PRINT);

        }
        elseif($entity == 'learner')
        {
            $upload_file_result = $this->processFileUploads('file_content', '/DataImports/learners', ["csv"]);
            if(isset($upload_file_result['status']) && $upload_file_result['status'] != '')
            {
                echo json_encode($upload_file_result, JSON_PRETTY_PRINT);
                return;
            }

            $file = new RepositoryFile($upload_file_result[0]);

            $import_result = $this->importLearnersFromDirectoryAction($link, $file);

            if(isset($import_result['status']) && $import_result['status'] == 'error')
            {
                echo json_encode($import_result, JSON_PRETTY_PRINT);
                return;
                //throw new Exception(json_encode($import_result, JSON_PRETTY_PRINT));
            }

            echo json_encode($import_result, JSON_PRETTY_PRINT);

        }
        else
        {
            echo json_encode(['status' => 'error', 'description' => 'Invalid entity value'], JSON_PRETTY_PRINT);
            return;
        }
    }

    private function processFileUploads($form_field_name, $target_directory, array $valid_extensions = array(), $max_individual_file_size = 0)
    {
        if(!isset($_FILES[$form_field_name])){
            return ['status' => 'error', 'description' => '1. File cannot be uploaded'];
        }
        if(!isset($_FILES[$form_field_name]['name'])){
            return ['status' => 'error', 'description' => '2. File cannot be uploaded'];
        }
        if(is_array($_FILES[$form_field_name]['name']) && count($_FILES[$form_field_name]['name']) == 0){
            return ['status' => 'error', 'description' => '3. File cannot be uploaded'];
        }
        if(!is_array($_FILES[$form_field_name]['name']) && $_FILES[$form_field_name]['name'] == ''){
            return ['status' => 'error', 'description' => '4. File cannot be uploaded'];
        }
        if(isset($_FILES[$form_field_name]['size']) && $_FILES[$form_field_name]['size'] == 0){
            return ['status' => 'error', 'description' => '5. File is empty'];
        }

        $field = $_FILES[$form_field_name];

        if(!is_array($field['error'])){
            $field['error'] = array($field['error']);
        }
        if(!is_array($field['tmp_name'])){
            $field['tmp_name'] = array($field['tmp_name']);
        }
        if(!is_array($field['name'])){
            $field['name'] = array($field['name']);
        }
        if(!is_array($field['size'])){
            $field['size'] = array($field['size']);
        }
        if(!is_array($field['type'])){
            $field['type'] = array($field['type']);
        }


        // Check for errors
        for($i = 0; $i < count($field['error']); $i++)
        {
            if($field['error'][$i] === UPLOAD_ERR_OK){
                continue; // No error
            }

            $filename = isset($field['name'][$i]) ? $field['name'][$i] : "The file ";
            switch($field['error'][$i])
            {
                case UPLOAD_ERR_INI_SIZE:
                    return ['status' => 'error', 'description' => "$filename exceeded the global maximum upload size of ".ini_get("upload_max_filesize")];
                case UPLOAD_ERR_FORM_SIZE:
                    return ['status' => 'error', 'description' => "$filename exceeded the maximum upload size" . $_FILES[$form_field_name]['size']];
                case UPLOAD_ERR_PARTIAL:
                    return ['status' => 'error', 'description' => "$filename was only partially uploaded"];
                case UPLOAD_ERR_NO_FILE:
                    return ['status' => 'error', 'description' => "No file was uploaded"];
                case UPLOAD_ERR_NO_TMP_DIR:
                    return ['status' => 'error', 'description' => "Internal server error UPLOAD_ERR_NO_TMP_DIR."];
                case UPLOAD_ERR_CANT_WRITE:
                    return ['status' => 'error', 'description' => "Internal server error UPLOAD_ERR_CANT_WRITE."];
                case UPLOAD_ERR_EXTENSION:
                    return ['status' => 'error', 'description' => "Internal server error UPLOAD_ERR_EXTENSION."];
                default:
                    return ['status' => 'error', 'description' => "Unknown file-upload error code: ".$field['error'][$i]];
            }
        }

        // Confirm maximum individual file size has not been exceeded
        if($max_individual_file_size > 0)
        {
            for($i = 0; $i < count($field['size']); $i++)
            {
                if($field['size'][$i] > $max_individual_file_size){
                    return ['status' => 'error', 'description' => "File ".$field['name'][$i]." exceeds the maximum file size of ".Repository::formatFileSize($max_individual_file_size)];
                }
            }
        }

        // Confirm the upload quota has not been exceeded
        $total_size = 0;
        foreach($field['size'] as $size){
            $total_size += $size;
        }
        $remaining_space = Repository::getRemainingSpace();
        if($total_size > $remaining_space)
        {
            return ['status' => 'error', 'description' => "Uploaded files (" . Repository::formatFileSize($total_size) . ") exceed the remaining space available ("
                . Repository::formatFileSize($remaining_space) . ")"];
        }

        // Confirm the file extension is authorised
        if(count($valid_extensions) > 0)
        {
            array_walk($valid_extensions, function(&$item, $key){$item = strtolower($item);}); // convert all valid extensions to lower-case
            foreach($field['name'] as $filename)
            {
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if(!in_array($ext, $valid_extensions)){
                    return ['status' => 'error', 'description' => "File $filename is of a type not authorised for this import. Authorised types include: ".implode(', ', $valid_extensions)];
                }
            }
        }

        // Confirm the target directory exists
        $target_directory = trim($target_directory, '/\\ ');
        $target_path = Repository::getRoot().($target_directory?('/'.$target_directory):'');
        if(is_file($target_path)){
            return ['status' => 'error', 'description' => "Target path '$target_directory' is a regular file and so cannot accept uploads"];
        }
        if(!is_dir($target_path)){
            if(!mkdir($target_path, 0777, true)){
                return ['status' => 'error', 'description' => "The target directory '$target_directory' does not exist and all attempts to create it have failed. Check file permissions."];
            }
        }

        ///////////////// if file with the same name already exists in the directory then change the file name////////////
        $absolute_paths = array();
        for($i = 0; $i < count($field['tmp_name']); $i++)
        {
            $file = pathinfo($target_path . "/" . $field['name'][$i]);
            $filename = $file['filename'];
            $j = 1;
            while(file_exists($target_path . "/" . $filename . "." . $file['extension'])){
                $filename = $file['filename']." ($j)";
                $j++;
            }
            $field['name'][$i] = $filename .".". $file['extension'];
            $cleanName = preg_replace('/[^A-Za-z0-9 .,()\\-_]/', '', $field['name'][$i]);
            $absolute_paths[] = $target_path.'/'.$cleanName;
            move_uploaded_file($field['tmp_name'][$i], $target_path.'/'.$cleanName);
        }

        // Return an array of the absolute file paths of all uploaded files
        return $absolute_paths;
    }

    private function importEmployersFromDirectoryAction(PDO $link, RepositoryFile $input_file)
    {
        $result_status = ['status' => '', 'description' => ''];

        if(!$input_file->isFile())
        {
            return ['status' => 'error', 'description' => 'File not found'];
        }

        // check the latest successful import file timestamp
        $latest_successful_file_timestamp = DAO::getSingleValue($link, "SELECT import_file_modified_time FROM data_imports WHERE import_entity = 'employer' AND import_successful = '1' ORDER BY import_timestamp DESC LIMIT 1");
        if($latest_successful_file_timestamp >= $input_file->getModifiedTime())
        {
            $message = 'Last Successful File Timestamp: ' . date('d/m/Y H:i:s.', $latest_successful_file_timestamp) . PHP_EOL;
            $message .= 'This File Timestamp: ' . date('d/m/Y H:i:s.', $input_file->getModifiedTime());
            return ['status' => 'error', 'description' => $message];
        }

        $import_result = EmployerImporter::import($link, $input_file->getAbsolutePath());

        $result_status['status'] = $import_result['status'];
        $result_status['description'] = $import_result['description'];

        //save in data_imports
        $import = (object)[
            "import_id" => null,
            "import_file" => $input_file->getName(),
            "import_file_modified_time" => $input_file->getModifiedTime(),
            "import_file_extension" => $input_file->getExtension(),
            "import_file_size" => $input_file->getSize(),
            "import_file_header" => json_encode($import_result['header']),
            "import_successful" => $import_result['status'] == 'success' ? 1 : 0,
            "import_entity" => "employer",
            "import_message" => $result_status['description'],
        ];

        DAO::saveObjectToTable($link, "data_imports", $import);

        return $result_status;
    }

    private function importLearnersFromDirectoryAction(PDO $link, RepositoryFile $input_file)
    {
        $result_status = ['status' => '', 'description' => ''];

        if(!$input_file->isFile())
        {
            return ['status' => 'error', 'description' => 'File not found'];
        }

        // check the latest successful import file timestamp
        $latest_successful_file_timestamp = DAO::getSingleValue($link, "SELECT import_file_modified_time FROM data_imports WHERE import_entity = 'learner' AND import_successful = '1' ORDER BY import_timestamp DESC LIMIT 1");
        if($latest_successful_file_timestamp >= $input_file->getModifiedTime())
        {
            $message = 'Last Successful File Timestamp: ' . date('d/m/Y H:i:s.', $latest_successful_file_timestamp) . PHP_EOL;
            $message .= 'This File Timestamp: ' . date('d/m/Y H:i:s.', $input_file->getModifiedTime());
            return ['status' => 'error', 'description' => $message];
        }

        $import_result = LearnerImporter::import($link, $input_file->getAbsolutePath());

        $result_status['status'] = $import_result['status'];
        $result_status['description'] = $import_result['description'];

        //save in data_imports
        $import = (object)[
            "import_id" => null,
            "import_file" => $input_file->getName(),
            "import_file_modified_time" => $input_file->getModifiedTime(),
            "import_file_extension" => $input_file->getExtension(),
            "import_file_size" => $input_file->getSize(),
            "import_file_header" => json_encode($import_result['header']),
            "import_successful" => $import_result['status'] == 'success' ? 1 : 0,
            "import_entity" => "learner",
            "import_message" => $result_status['description'],
        ];

        DAO::saveObjectToTable($link, "data_imports", $import);

        return $result_status;
    }


}
<?php

class Docx
{

    private $filename = null;        // name of the docx file being managed
    private $read_location = null;    // location of the docx file being managed
    private $temp_location = null;    // location of magic manipulation folder
    private $write_location = null;    // location of the docx file output
    private $docx_xml = null;        // content of the document.xml for updating

    private $fileList = array();    // array to hold the content of the word extraction folders

    /**
     *
     * Enter description here ...
     * @param string $fname - filename of docx file to manage
     * @param string $rloca - folder path of docx file being managed
     * @param string $wloca - output folder path for the docx file
     */
    function __construct($fname = '', $rloca = '', $wloca = '')
    {
        $this->filename = $fname;
        $this->read_location = $rloca != '' ? $rloca : '.';    // use the current working directory if no other has been supplied
        $this->create_temp();                                // create the manipulation folder
        $this->write_location = $wloca;
    }

    // standard gets for private variables ------------------------- //

    public function get_filename()
    {
        return $this->filename;
    }

    public function get_readlocation()
    {
        return $this->read_location;
    }

    public function get_writelocation()
    {
        return $this->write_location;
    }

    public function get_docx_xml()
    {
        return $this->docx_xml;
    }

    // docx manipulations ------------------------- //

    /**
     *
     * extract the word docx file to the selected write location
     * @throws Exception
     */
    public function extract_docx()
    {

        if ($this->read_location != '') {
            if (!file_exists($this->read_location) && !is_file($this->read_location)) {
                mkdir($this->read_location);
            }
            // does the read location exist
            if (!file_exists($this->read_location)) {
                throw new Exception('there is no read folder [' . $this->read_location . ']');
            }
        }

        // does the file exist
        $full_filepath = rtrim($this->read_location, '/') . "/" . $this->filename;
        // check if the file has a docx extension
        if (!(file_exists($full_filepath))) {
            throw new Exception('there is no docx file [' . $full_filepath . ']');
        }

        $zip = new ZipArchive();
        // open archive
        if ($zip->open($full_filepath) !== true) {
            throw new Exception("Could not open archive");
        }

        if ($this->temp_location !== null) {
            // ensure the temp location exist
            if (file_exists($this->temp_location)) {
                // clean down the directory
                $this->delete_docx_directory($this->temp_location);
            }
            mkdir($this->temp_location);
        }

        // extract contents to temporary update location directory
        $zip->extractTo($this->temp_location);
        $zip->close();

        return $this->load_docx_xml();
    }

    /**
     *
     * Enter description here ...
     */
    public function load_docx_xml()
    {
        // read file
        $this->docx_xml = file_get_contents($this->temp_location . '/word/document.xml');
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $tag
     * @param unknown_type $replacement
     */
    public function update_docx($tag = '', $replacement = '')
    {
        // do not allow blank tags
        // but allow blank $replacements
        if ($tag) {
            $this->docx_xml = $this->docx_xml && $replacement ? str_replace($tag, $replacement, $this->docx_xml) : $this->docx_xml;
            return 1;
        }
        return;
    }

    /**
     *
     * Enter description here ...
     * @throws Exception
     */
    public function write_docx()
    {

        if (!(file_exists($this->temp_location))) {
            throw new Exception('there is no temporary file location');
        }

        // output the manipulated content to the temporary location
        $fp = fopen($this->temp_location . '/word/document.xml', "w") or die("Couldn't create new file");
        fwrite($fp, $this->docx_xml);
        fclose($fp);
    }

    /**
     *
     * Enter description here ...
     * @throws Exception
     */
    public function bundle_docx()
    {

        // we do not have a write location
        // - do not proceed with attempting to.
        if ('' == $this->write_location) {
            return;
        }


        // create object
        $zip = new ZipArchive();

        // check all files and folders exist
        // - add additional check in here please
        if (!(file_exists($this->write_location))) {
            mkdir($this->write_location);
        }

        $filename = date('dmYHis') . "_" . $this->filename;

        // open archive
        // docx filetype on earlier versions of windows may require:
        // http://www.microsoft.com/downloads/en/details.aspx?FamilyId=941B3470-3AE9-4AEE-8F43-C6BB74CD1466&displaylang=en
        if ($zip->open($this->write_location . "/" . $filename, ZIPARCHIVE::CREATE) !== true) {
            throw new Exception("Could not open archive " . $this->write_location . "/" . $filename);
        }

        // Get the contents of the word document template directory
        // - this ensures all the relevant bits are included
        $this->read_docx_directory($this->temp_location);

        // add files
        foreach ($this->fileList as $f) {
            // create the path to the file as stored in the archive
            $zip_pathname = str_replace($this->temp_location . "/", "", (string)$f);
            // check if the file actually exists
            $full_file_path = realpath($f);
            if ($full_file_path != '') {
                // add it to the archive
                $zip->addFile($f, $zip_pathname) or die ("ERROR: Could not add file: $f");
            }
        }
        // close and save archive
        $zip->close();

        // return the filename
        return $filename;
    }

    // folder manipulations ------------------------- //

    /**
     *
     * create a unique & temporary location to
     * write the file to prior to manipulation of XML
     */
    private function create_temp()
    {
        $temporary_file = time();
        $this->temp_location = $this->read_location . $temporary_file . '/';
        return;
    }

    /**
     *
     * obtain the contents of a docx archive
     * - this differs depending on the content on the word document
     * - so needs to be dynamic
     */
    private function read_docx_directory($path = '')
    {
        if ('' != $path) {
            $handle = @opendir($path);
            while (false !== ($file = readdir($handle))) {
                if ($file == '.' || $file == '..') {
                    continue;
                }

                if (is_dir("$path/$file")) {
                    $this->read_docx_directory("$path/$file");
                } else {
                    $this->fileList[] = $path . '/' . $file;
                }
            }
            closedir($handle);
        }
    }

    /**
     *
     * remove the contents of a directory
     * - required to clean down the user folder to prevent erroneous files
     * - being zipped into the archive
     * @param unknown_type $path
     */
    private function delete_docx_directory($path)
    {
        if ('' != $path) {
            if ($handle = @opendir($path)) {
                $array = array();
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        if (is_dir($path . "/" . $file)) {
                            if (!@rmdir($path . "/" . $file)) {
                                $this->delete_docx_directory($path . "/" . $file . '/'); // Not empty so delete the files inside it
                            }
                        } else {
                            @unlink($path . "/" . $file);
                        }
                    }
                }
                closedir($handle);
                @rmdir($path);
            }
        }
    }

    /**
     *
     * clean up of the temporary folder
     */
    public function cleanup_docx()
    {
        $this->delete_docx_directory($this->temp_location);
    }

    /**
     * example function using the methods in this class
     *
     *    public function do_some_docx_stuff
     *  {
     *    // create Docx instance with file values
     *        $file_manager = new Docx('ilj.docx','', DATA_ROOT."/uploads/".DB_NAME."/hello/");
     *
     *        // extract the docx into its constituent elements
     *        $file_manager->extract_docx();
     *
     *        // update the content of the docx file
     *        $file_manager->update_docx('standalone', 'weasel');
     *
     *        // output the content of the docx file in xml
     *        echo $file_manager->get_docx_xml();
     *
     *        // save changes to the temporary location files
     *        $file_manager->write_docx();
     *
     *        // re-archive the temporary location files and place in write location
     *        $final_filename = $file_manager->bundle_docx();
     *
     *        // remove any temporary files created during the manipulations
     *        $file_manager->cleanup_docx();
     *  }
     *
     */

    /**
     * Below are the initial attempts at generizing the templates - using lookups to populate
     * user downloads
     */

    /**
     *
     * get the templates in the learner location
     */
    static function load_learner_templates()
    {

        $fileList = array();

        $learner_location = DATA_ROOT . '/uploads/' . DB_NAME . '/learner_doc_templates/';
        $handle = @opendir($learner_location);
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..' || is_dir("$learner_location/$file")) {
                continue;
            }
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if ($ext == 'docx') {
                ;
                $file = str_replace('.docx', '', $file);
                $fileList[] = $file;
            }
        }
        closedir($handle);
        return $fileList;
    }
}

?>
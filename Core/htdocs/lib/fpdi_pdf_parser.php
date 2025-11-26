<?php
//
//  Updated for FPDI 2.x+
//
use setasign\Fpdi\Fpdi;

require_once("pdf_parser.php");

class fpdi_pdf_parser extends pdf_parser
{
    public $pages = [];
    public $page_count = 0;
    public $pageno = 0;
    public $pdfVersion;
    /** @var Fpdi */
    public $fpdi;
    public $availableBoxes = ["/MediaBox", "/CropBox", "/BleedBox", "/TrimBox", "/ArtBox"];

    public function __construct(string $filename, Fpdi $fpdi)
    {
        $this->fpdi = $fpdi;
        $this->filename = $filename;

        parent::__construct($filename);

        // resolve Pages-Dictionary
        $pages = $this->pdf_resolve_object($this->c, $this->root[1][1]['/Pages']);

        // Read pages
        $this->read_pages($this->c, $pages, $this->pages);

        // count pages
        $this->page_count = count($this->pages);
    }

    function error($msg)
    {
        $this->fpdi->error($msg);
    }

    function getPageCount(): int
    {
        return $this->page_count;
    }

    function setPageno(int $pageno)
    {
        $pageno = $pageno - 1;

        if ($pageno < 0 || $pageno >= $this->getPageCount()) {
            $this->fpdi->Error("Pagenumber is wrong!");
        }

        $this->pageno = $pageno;
    }

    function getPageResources()
    {
        return $this->_getPageResources($this->pages[$this->pageno] ?? '');
    }

    function _getPageResources($obj)
    {
        $obj = $this->pdf_resolve_object($this->c, $obj);

        if (isset($obj[1][1]['/Resources'])) {
            $res = $this->pdf_resolve_object($this->c, $obj[1][1]['/Resources']);
            return ($res[0] === PDF_TYPE_OBJECT) ? $res[1] : $res;
        }

        if (!isset($obj[1][1]['/Parent'])) return false;

        $res = $this->_getPageResources($obj[1][1]['/Parent']);
        return ($res[0] === PDF_TYPE_OBJECT) ? $res[1] : $res;
    }

    function getContent(): string
    {
        $buffer = "";

        if (isset($this->pages[$this->pageno][1][1]['/Contents'])) {
            $contents = $this->_getPageContent($this->pages[$this->pageno][1][1]['/Contents']);
            foreach ($contents as $tmp_content) {
                $buffer .= $this->_rebuildContentStream($tmp_content) . ' ';
            }
        }

        return $buffer;
    }

    function _getPageContent($content_ref): array
    {
        $contents = [];

        if ($content_ref[0] === PDF_TYPE_OBJREF) {
            $content = $this->pdf_resolve_object($this->c, $content_ref);
            if ($content[1][0] === PDF_TYPE_ARRAY) {
                return $this->_getPageContent($content[1]);
            } else {
                return [$content];
            }
        }

        if ($content_ref[0] === PDF_TYPE_ARRAY) {
            foreach ($content_ref[1] as $tmp_content_ref) {
                $contents = array_merge($contents, $this->_getPageContent($tmp_content_ref));
            }
        }

        return $contents;
    }

    function _rebuildContentStream($obj): string
    {
        $filters = [];

        if (isset($obj[1][1]['/Filter'])) {
            $_filter = $obj[1][1]['/Filter'];
            if ($_filter[0] === PDF_TYPE_TOKEN) {
                $filters[] = $_filter;
            } elseif ($_filter[0] === PDF_TYPE_ARRAY) {
                $filters = $_filter[1];
            }
        }

        $stream = $obj[2][1] ?? '';

        foreach ($filters as $_filter) {
            switch ($_filter[1]) {
                case "/FlateDecode":
                    if (function_exists('gzuncompress')) {
                        $stream = strlen($stream) ? @gzuncompress($stream) : '';
                    } else {
                        $this->fpdi->Error(sprintf("To handle %s filter, please compile php with zlib support.", $_filter[1]));
                    }
                    if ($stream === false) {
                        $this->fpdi->Error("Error while decompressing stream.");
                    }
                    break;
                case null:
                    break;
                default:
                    if (preg_match("/^\/[a-z85]*$/i", $_filter[1], $filterName) && @include_once('decoders' . $_filter[1] . '.php')) {
                        $filterName = substr($_filter[1], 1);
                        if (class_exists($filterName)) {
                            $decoder = new $filterName($this->fpdi);
                            $stream = $decoder->decode(trim($stream));
                        } else {
                            $this->fpdi->Error(sprintf("Unsupported Filter: %s", $_filter[1]));
                        }
                    } else {
                        $this->fpdi->Error(sprintf("Unsupported Filter: %s", $_filter[1]));
                    }
            }
        }

        return $stream;
    }

    function getPageBox($page, string $box_index)
    {
        $page = $this->pdf_resolve_object($this->c, $page);
        $box = $page[1][1][$box_index] ?? null;

        if ($box && $box[0] === PDF_TYPE_OBJREF) {
            $tmp_box = $this->pdf_resolve_object($this->c, $box);
            $box = $tmp_box[1];
        }

        if ($box && $box[0] === PDF_TYPE_ARRAY) {
            $b = $box[1];
            $k = $this->fpdi->k ?: 1; // scale factor
            return [
                "x" => $b[0][1] ?? 0 / $k,
                "y" => $b[1][1] ?? 0 / $k,
                "w" => abs(($b[0][1] ?? 0) - ($b[2][1] ?? 0)) / $k,
                "h" => abs(($b[1][1] ?? 0) - ($b[3][1] ?? 0)) / $k
            ];
        }

        if (!isset($page[1][1]['/Parent'])) return false;

        return $this->getPageBox($this->pdf_resolve_object($this->c, $page[1][1]['/Parent']), $box_index);
    }

    function getPageBoxes(int $pageno): array
    {
        return $this->_getPageBoxes($this->pages[$pageno - 1] ?? '');
    }

    function _getPageBoxes($page): array
    {
        $boxes = [];
        foreach ($this->availableBoxes as $box) {
            if ($_box = $this->getPageBox($page, $box)) {
                $boxes[$box] = $_box;
            }
        }
        return $boxes;
    }

    function getPageRotation(int $pageno)
    {
        return $this->_getPageRotation($this->pages[$pageno - 1]);
    }

    function _getPageRotation($obj)
    {
        $obj = $this->pdf_resolve_object($this->c, $obj);
        if (!$obj || !is_array($obj) || !isset($obj[1][1])) return false;

        if (isset($obj[1][1]['/Rotate'])) {
            $res = $this->pdf_resolve_object($this->c, $obj[1][1]['/Rotate']);
            return ($res[0] === PDF_TYPE_OBJECT) ? $res[1] : $res;
        }

        if (!isset($obj[1][1]['/Parent'])) return false;
        $res = $this->_getPageRotation($obj[1][1]['/Parent']);
        return ($res[0] === PDF_TYPE_OBJECT) ? $res[1] : $res;
    }

    function read_pages(&$c, &$pages, &$result)
    {
        $kids = $this->pdf_resolve_object($c, $pages[1][1]['/Kids']);
        if (!is_array($kids)) {
            $this->fpdi->Error("Cannot find /Kids in current /Page-Dictionary");
        }

        foreach ($kids[1] as $v) {
            $pg = $this->pdf_resolve_object($c, $v);
            if ($pg[1][1]['/Type'][1] === '/Pages') {
                $this->read_pages($c, $pg, $result);
            } else {
                $result[] = $pg;
            }
        }
    }
    function getPDFVersion()
    {
        // Call parent method to set $this->pdfVersion
        parent::getPDFVersion();

        if ($this->fpdi && method_exists($this->fpdi, 'getPDFVersion') && method_exists($this->fpdi, 'setPDFVersion')) {
            $current = $this->fpdi->getPDFVersion(); // Use correct case
            $this->fpdi->setPDFVersion(max($current, $this->pdfVersion)); // Use correct case
        }
    }
}

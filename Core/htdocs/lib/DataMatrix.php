<?php

class DataMatrix
{
    private $columns;
    private $data;
    private $totalsColumns = array();
    private $formatNumbers = false;
    private $transforms = array();

    private $specialHeaders = array();
    private $exportLinks;

    function __construct($columns, $data, $exportLinks = true)
    {
        $this->exportLinks = $exportLinks;
        if (!is_array($columns) or !is_array($data)) {
            throw new Exception('Invalid data supplied to the data matrix');
        }

        // assuming we aren't using special headers, validate

        if (sizeof($this->specialHeaders) == 0 and sizeof($columns) > 0) {
            foreach ($data as $key => $val) {
                if (sizeof($val) != sizeof($columns)) {
                    throw new Exception(
                        "Some invalid data has been supplied to the data matrix.
						More specifically the data size for row #$key (" . sizeof($val) . ") does not equate to the size of the columns: " . sizeof($columns)
                    );
                }
            }
        }

        // if we got this far then data is initially okay
        $this->columns = $columns;
        $this->data = $data;
    }

    function to($type, $id = "dataMatrix")
    {
        if (method_exists($this, 'to' . $type)) {
            return $this->{'to' . $type}($id);
        } else {
            throw new Exception('Invalid export format supplied');
        }
    }

    function toHTML($id = "dataMatrix")
    {
        if (sizeof($this->data) == 0) {
            return '<p>No data found</p>';
        }

        $html = '';

        $html .= '<p>Total Rows: <span id="' . $id . '-totalrows">' . sizeof($this->data) . '</span></p>';

        $html .= '<table class="resultset sortData" cellpadding="6" id="' . $id . '">';

        // 1) Headings
        if (sizeof($this->specialHeaders) == 0) {
            $html .= '<thead><tr>';
            $totals = array();
            foreach ($this->columns as $key => $heading) {
                $totals["$heading"] = 0;
                $html .= '<th class="topRow">' . ucwords(str_replace('_', ' ', $heading)) . '</th>';
            }
            $html .= '</tr></thead>';
        } else {
            $html .= '<thead>';
            $head1 = $head2 = array();
            foreach ($this->specialHeaders as $column => $subcolumns) {
                $head1[] = $column;
                foreach ($subcolumns as $sub) {
                    $head2[] = $sub;
                }
            }

            $html .= '<tr>';
            foreach ($head1 as $h1) {
                $html .= '<th colspan="' . sizeof($this->specialHeaders["$h1"]) . '">' . $h1 . '</th>';
            }
            $html .= '</tr><tr>';
            foreach ($head2 as $h2) {
                $html .= '<th>' . $h2 . '</th>';
            }
            $html .= '</tr>';
            $html .= '</thead>';
        }

        // 2) Data
        $html .= '<tbody align="center">';
        foreach ($this->data as $key1 => $data) {
            $html .= '<tr>';
            foreach ($data as $key2 => $val) {
                if (in_array($key2, $this->totalsColumns)) {
                    $totals["$key2"] += $val;
                }
                $html .= '<td>';

                if (isset($this->transforms["$key2"])) {
                    if ($this->transforms["$key2"]['normal']) {
                        $func = $this->transforms["$key2"]['func'];
                        $html .= $func($val);
                    } else {
                        $html .= $val;
                    }
                } else {
                    $html .= $val;
                }
                $html .= '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';

        // totals....
        if (sizeof($this->totalsColumns) > 0) {
            $html .= '<tfoot><tr class="bottomRow">';
            foreach ($this->columns as $key => $heading) {
                $html .= '<td style="text-align: center;">';
                if (in_array($heading, $this->totalsColumns)) {
                    $html .= '<strong>';
                    if (isset($this->transforms["$key2"]) and $this->transforms["$key2"]['total']) {
                        $func = $this->transforms["$key2"]['func'];
                        $html .= $func($totals["$heading"]);
                    } else {
                        $html .= $totals["$heading"];
                    }
                    $html .= '</strong>';
                }
                $html .= '</td>';
            }
            $html .= '</tr></tfoot>';
        }

        $html .= '</table><br />';
        if ($this->exportLinks) {
            $html .= '<span class="kpi_links">';
            $html .= 'Export as: <a href="./' . str_ireplace('&output=HTML', '', substr($_SERVER['REQUEST_URI'], 1)) . '&output=BarChart">Bar Chart</a> | <a href="./' . str_ireplace('&output=HTML', '', substr($_SERVER['REQUEST_URI'], 1)) . '&output=PieChart">Pie Chart</a>';
            $html .= '</span>';
        }
        return $html;
    }

    function toCSV()
    {
        $string = '';

        // 1) Headings
        $string .= '"' . implode('","', $this->columns) . '"' . "\r\n";
        $totals = array();
        foreach ($this->columns as $key => $heading) {
            $totals["$heading"] = 0;
        }

        // 2) Data
        $strings = array();
        foreach ($this->data as $key => $data) {
            $bits = array();
            foreach ($data as $key2 => $val) {
                if (in_array($key2, $this->totalsColumns)) {
                    $totals["$key2"] += $val;
                }
                $bits[] = '"' . strip_tags((string) $val) . '"';
            }
            $strings[] = implode(',', $bits);
        }
        $string .= implode("\r\n", $strings);

        // totals....
        if (sizeof($this->totalsColumns) > 0) {
            $string .= "\r\n";
            foreach ($this->columns as $key => $heading) {
                if (in_array($heading, $this->totalsColumns)) {
                    if (isset($this->transforms["$key2"]) and $this->transforms["$key2"]['total']) {
                        $func = $this->transforms["$key2"]['func'];
                        $string .= $func($totals["$heading"]);
                    } else {
                        $string .= $totals["$heading"];
                    }
                }
                $string .= ',';
            }
            $string .= "\r\n";
        }

        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="report.csv"');
        echo $string;
        exit();
    }

    function toBarChart()
    {
        $html = '';
        // sort out the labels for each bar
        $columns = $this->columns;
        array_shift($columns);

        $data = array();

        foreach ($this->data as $row => $rowData) {
            $key = array_shift($rowData);
            foreach ($rowData as $label => $v) {
                if ($label != 'Total' && $label != 'Percentage') {
                    $data["$key"]["$label"] = strip_tags($v);
                }
            }
        }

        foreach ($data as $area => $graphData) {
            $labels = array();
            $total = 0;
            foreach ($graphData as $label => $value) {
                $labels[] = urlencode($label);
                $total += $value;
            }

            $values = array();
            // reiterate through the values to recalculate them as a percentage of the total
            foreach ($graphData as $label => $value) {
                $values[] = sprintf("%.2f", ($value / $total) * 100);
            }

            $labels = implode('|', array_reverse($labels));

            $height = 60 + (30 * sizeof($values));
            $html .= '<img src="http://chart.apis.google.com/chart?
				chtt=' . urlencode($area) . '
				&chts=000000,12
				&chs=500x' . $height . '
				&chf=bg,s,ffffff|c,s,ffffff
				&chxt=x,y
				&chxl=1:|' . $labels . '|0:|0.00|20.00|40.00|60.00|80.00|100.00
				&cht=bhs
				&chd=t:' . implode(',', $values) . '
				&chco=0000ff
				&chbh=25
			" /><br /><br /><hr /><br /><br />';
        }
        return $html;
    }

    function toPieChart()
    {
        $html = '';
        // sort out the labels for each bar
        $columns = $this->columns;
        array_shift($columns);

        $data = array();
        foreach ($this->data as $row => $rowData) {
            $key = array_shift($rowData);
            foreach ($rowData as $label => $v) {
                if ($label != 'Total' && $label != 'Percentage') {
                    $data["$key"]["$label"] = strip_tags($v);
                }
            }
        }

        foreach ($data as $area => $graphData) {
            $labels = array();
            $total = 0;
            foreach ($graphData as $label => $value) {
                $labels[] = urlencode($label);
                $total += $value;
            }

            $values = array();
            // reiterate through the values to recalculate them as a percentage of the total
            foreach ($graphData as $label => $value) {
                $values[] = sprintf("%.2f", ($value / $total) * 100);
            }

            //$labels = implode('|', array_reverse($labels));
            $labels = implode('|', $labels);

            $height = 60 + (30 * sizeof($values));
            $html .= '<img src="http://chart.apis.google.com/chart?
				chtt=' . urlencode($area) . '
				&chts=000000,12
				&chs=500x' . $height . '
				&chf=bg,s,ffffff|c,s,ffffff
				&chxt=x,y
				&chl=' . $labels . '
				&cht=p
				&chd=t:' . implode(',', $values) . '
				&chco=0000ff
				&chbh=25
			" /><br /><br /><hr /><br /><br />';
        }
        return $html;
    }

    public function toXLS()
    {
        $output = '';
        $output .= '<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>Perspective</Author>
  <LastAuthor>Perspective</LastAuthor>
  <Created>2009-07-03T14:13:07Z</Created>
  <Version>12.00</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>8415</WindowHeight>
  <WindowWidth>19095</WindowWidth>
  <WindowTopX>120</WindowTopX>
  <WindowTopY>150</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s71">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"
     ss:Color="#C0C0C0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"
     ss:Color="#C0C0C0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"
     ss:Color="#C0C0C0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"
     ss:Color="#C0C0C0"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Color="#000000" ss:Bold="1"/>
   <Interior ss:Color="#EEEEEE" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s78">
 <Alignment ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Dot" ss:Weight="1"
     ss:Color="#CCCCCC"/>
    <Border ss:Position="Left" ss:LineStyle="Dot" ss:Weight="1" ss:Color="#CCCCCC"/>
    <Border ss:Position="Right" ss:LineStyle="Dot" ss:Weight="1" ss:Color="#CCCCCC"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Color="#000000"/>
   <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
  </Style>
 </Styles>		
		';


        $output .= '<Worksheet ss:Name="Sheet 1">';
        $output .= '  <Table x:FullColumns="1" x:FullRows="1" ss:DefaultRowHeight="15">';

        // columns
        foreach ($this->columns as $key => $label) {
            $output .= '<Column ss:AutoFitWidth="0" ss:Width="200"/>';
        }
        $output .= '<Row ss:Height="20">';
        foreach ($this->columns as $key => $label) {
            $output .= '<Cell ss:StyleID="s71"><Data ss:Type="String">' . mb_convert_encoding($label, 'UTF-8') . '</Data></Cell>';
        }
        $output .= '</Row>';

        // info for each framework
        foreach ($this->data as $key => $values) {
            $output .= '<Row ss:Height="20">';
            foreach ($values as $k => $v) {
                $t = (is_numeric(strip_tags($v))) ? "Number" : "String";
                $output .= '<Cell ss:StyleID="s78"><Data ss:Type="' . $t . '">' . mb_convert_encoding(strip_tags($v), 'UTF-8') . '</Data></Cell>';
            }
            $output .= '</Row>';
        }

        $output .= '</Table>';
        $output .= '  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
		   <PageSetup>
		    <Header x:Margin="0.3"/>
		    <Footer x:Margin="0.3"/>
		    <PageMargins x:Bottom="0.75" x:Left="0.7" x:Right="0.7" x:Top="0.75"/>
		   </PageSetup>
		   <Selected/>
		   <ProtectObjects>False</ProtectObjects>
		   <ProtectScenarios>False</ProtectScenarios>
		  </WorksheetOptions>';

        $output .= '</Worksheet>';
        $output .= '</Workbook>';
        $output = str_replace('&', '&amp;', $output);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=data-collection.xml');
        echo $output;
        die;
    }

    public function toPDF()
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(238, 238, 238);
        $widths = array();
        foreach ($this->columns as $c => $heading) {
            $widths["$c"] = 2 * strlen($heading) + 10;
            $pdf->Cell($widths["$c"], 7, $heading, 1, 0, 'C', true);
        }
        $pdf->Ln();
        //Data
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetFillColor(255, 255, 255);
        foreach ($this->data as $row => $data) {
            $c = 0;
            foreach ($data as $key => $val) {
                if ($c == 0) {
                    $pdf->Cell($widths["$c"], 7, strip_tags($val), 1);
                } else {
                    $pdf->Cell($widths["$c"], 7, strip_tags(sprintf("%.2f", $val)), 1);
                }
                $c++;
            }
            $pdf->Ln();
        }
        $pdf->output();
    }

    public function addTotalColumns($columns)
    {
        if (is_array($columns)) {
            if (sizeof($columns) > 0) {
                foreach ($columns as $key => $column) {
                    if (in_array($column, $this->columns)) {
                        $this->totalsColumns[] = $column;
                    }
                }
            }
        }
    }

    /**
     * $column = column name
     * $func = function name as string
     * $doNormalRows = if set to true, normal rows for this column heading will be transformed
     * $isTotalColumn = if set to true, the total value of the column at the bottom of the page will be transformed
     *
     */
    public function transform($column, $func, $doNormalRows = true, $isTotalColumn = false)
    {
        $this->transforms["$column"] = array('func' => $func, 'normal' => $doNormalRows, 'total' => $isTotalColumn);
    }

    public function setSpecialHeaders($headers)
    {
        $this->specialHeaders = $headers;
    }
}

?>
<?php
/**
 * File for class MIAPStructULNReportResp
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructULNReportResp originally named ULNReportResp
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learnerreport.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructULNReportResp extends MIAPWsdlClass
{
    /**
     * The ResponseCode
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * @var string
     */
    public $ResponseCode;
    /**
     * The FromDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $FromDate;
    /**
     * The ToDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - nillable : false
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29)
     * @var string
     */
    public $ToDate;
    /**
     * The ULNReport
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - nillable : false
     * @var MIAPStructMIAPULNReport
     */
    public $ULNReport;
    /**
     * Constructor method for ULNReportResp
     * @see parent::__construct()
     * @param string $_responseCode
     * @param string $_fromDate
     * @param string $_toDate
     * @param MIAPStructMIAPULNReport $_uLNReport
     * @return MIAPStructULNReportResp
     */
    public function __construct($_responseCode,$_fromDate,$_toDate = NULL,$_uLNReport = NULL)
    {
        parent::__construct(array('ResponseCode'=>$_responseCode,'FromDate'=>$_fromDate,'ToDate'=>$_toDate,'ULNReport'=>$_uLNReport),false);
    }
    /**
     * Get ResponseCode value
     * @return string
     */
    public function getResponseCode()
    {
        return $this->ResponseCode;
    }
    /**
     * Set ResponseCode value
     * @param string $_responseCode the ResponseCode
     * @return string
     */
    public function setResponseCode($_responseCode)
    {
        return ($this->ResponseCode = $_responseCode);
    }
    /**
     * Get FromDate value
     * @return string
     */
    public function getFromDate()
    {
        return $this->FromDate;
    }
    /**
     * Set FromDate value
     * @param string $_fromDate the FromDate
     * @return string
     */
    public function setFromDate($_fromDate)
    {
        return ($this->FromDate = $_fromDate);
    }
    /**
     * Get ToDate value
     * @return string|null
     */
    public function getToDate()
    {
        return $this->ToDate;
    }
    /**
     * Set ToDate value
     * @param string $_toDate the ToDate
     * @return string
     */
    public function setToDate($_toDate)
    {
        return ($this->ToDate = $_toDate);
    }
    /**
     * Get ULNReport value
     * @return MIAPStructMIAPULNReport|null
     */
    public function getULNReport()
    {
        return $this->ULNReport;
    }
    /**
     * Set ULNReport value
     * @param MIAPStructMIAPULNReport $_uLNReport the ULNReport
     * @return MIAPStructMIAPULNReport
     */
    public function setULNReport($_uLNReport)
    {
        return ($this->ULNReport = $_uLNReport);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructULNReportResp
     */
    public static function __set_state(array $_array,$_className = __CLASS__)
    {
        return parent::__set_state($_array,$_className);
    }
    /**
     * Method returning the class name
     * @return string __CLASS__
     */
    public function __toString()
    {
        return __CLASS__;
    }
}

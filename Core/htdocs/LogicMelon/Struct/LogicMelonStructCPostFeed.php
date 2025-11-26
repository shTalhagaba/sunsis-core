<?php
/**
 * File for class LogicMelonStructCPostFeed
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructCPostFeed originally named CPostFeed
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructCPostFeed extends LogicMelonWsdlClass
{
    /**
     * The EffectiveQuota
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var decimal
     */
    public $EffectiveQuota;
    /**
     * The FeedID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $FeedID;
    /**
     * The Postings
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var decimal
     */
    public $Postings;
    /**
     * The Slots
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var decimal
     */
    public $Slots;
    /**
     * The Spend
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var decimal
     */
    public $Spend;
    /**
     * The UserID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var int
     */
    public $UserID;
    /**
     * The DefaultSelected
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var unsignedByte
     */
    public $DefaultSelected;
    /**
     * The ForceSelected
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var unsignedByte
     */
    public $ForceSelected;
    /**
     * The Restricted
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var unsignedByte
     */
    public $Restricted;
    /**
     * The FeedCost
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var decimal
     */
    public $FeedCost;
    /**
     * The FeedCostTypeID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var unsignedByte
     */
    public $FeedCostTypeID;
    /**
     * The JobDescriptionMinimumLength
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var int
     */
    public $JobDescriptionMinimumLength;
    /**
     * The JobDescriptionMaximumLength
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var int
     */
    public $JobDescriptionMaximumLength;
    /**
     * The PostingsUsed
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var decimal
     */
    public $PostingsUsed;
    /**
     * The SpendUsed
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var decimal
     */
    public $SpendUsed;
    /**
     * The PostingsForward
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : true
     * @var decimal
     */
    public $PostingsForward;
    /**
     * The CultureID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $CultureID;
    /**
     * The CurrencyID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $CurrencyID;
    /**
     * The FeedIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $FeedIdentifier;
    /**
     * The FeedName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $FeedName;
    /**
     * The LogoURL
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $LogoURL;
    /**
     * The ShortDescription
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $ShortDescription;
    /**
     * The FeedCostType
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $FeedCostType;
    /**
     * The HTMLSupport
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $HTMLSupport;
    /**
     * The SpendUsedCurrencyID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $SpendUsedCurrencyID;
    /**
     * Constructor method for CPostFeed
     * @see parent::__construct()
     * @param decimal $_effectiveQuota
     * @param int $_feedID
     * @param decimal $_postings
     * @param decimal $_slots
     * @param decimal $_spend
     * @param int $_userID
     * @param unsignedByte $_defaultSelected
     * @param unsignedByte $_forceSelected
     * @param unsignedByte $_restricted
     * @param decimal $_feedCost
     * @param unsignedByte $_feedCostTypeID
     * @param int $_jobDescriptionMinimumLength
     * @param int $_jobDescriptionMaximumLength
     * @param decimal $_postingsUsed
     * @param decimal $_spendUsed
     * @param decimal $_postingsForward
     * @param string $_cultureID
     * @param string $_currencyID
     * @param string $_feedIdentifier
     * @param string $_feedName
     * @param string $_logoURL
     * @param string $_shortDescription
     * @param string $_feedCostType
     * @param string $_hTMLSupport
     * @param string $_spendUsedCurrencyID
     * @return LogicMelonStructCPostFeed
     */
    public function __construct($_effectiveQuota,$_feedID,$_postings,$_slots,$_spend,$_userID,$_defaultSelected,$_forceSelected,$_restricted,$_feedCost,$_feedCostTypeID,$_jobDescriptionMinimumLength,$_jobDescriptionMaximumLength,$_postingsUsed,$_spendUsed,$_postingsForward,$_cultureID = NULL,$_currencyID = NULL,$_feedIdentifier = NULL,$_feedName = NULL,$_logoURL = NULL,$_shortDescription = NULL,$_feedCostType = NULL,$_hTMLSupport = NULL,$_spendUsedCurrencyID = NULL)
    {
        parent::__construct(array('EffectiveQuota'=>$_effectiveQuota,'FeedID'=>$_feedID,'Postings'=>$_postings,'Slots'=>$_slots,'Spend'=>$_spend,'UserID'=>$_userID,'DefaultSelected'=>$_defaultSelected,'ForceSelected'=>$_forceSelected,'Restricted'=>$_restricted,'FeedCost'=>$_feedCost,'FeedCostTypeID'=>$_feedCostTypeID,'JobDescriptionMinimumLength'=>$_jobDescriptionMinimumLength,'JobDescriptionMaximumLength'=>$_jobDescriptionMaximumLength,'PostingsUsed'=>$_postingsUsed,'SpendUsed'=>$_spendUsed,'PostingsForward'=>$_postingsForward,'CultureID'=>$_cultureID,'CurrencyID'=>$_currencyID,'FeedIdentifier'=>$_feedIdentifier,'FeedName'=>$_feedName,'LogoURL'=>$_logoURL,'ShortDescription'=>$_shortDescription,'FeedCostType'=>$_feedCostType,'HTMLSupport'=>$_hTMLSupport,'SpendUsedCurrencyID'=>$_spendUsedCurrencyID),false);
    }
    /**
     * Get EffectiveQuota value
     * @return decimal
     */
    public function getEffectiveQuota()
    {
        return $this->EffectiveQuota;
    }
    /**
     * Set EffectiveQuota value
     * @param decimal $_effectiveQuota the EffectiveQuota
     * @return decimal
     */
    public function setEffectiveQuota($_effectiveQuota)
    {
        return ($this->EffectiveQuota = $_effectiveQuota);
    }
    /**
     * Get FeedID value
     * @return int
     */
    public function getFeedID()
    {
        return $this->FeedID;
    }
    /**
     * Set FeedID value
     * @param int $_feedID the FeedID
     * @return int
     */
    public function setFeedID($_feedID)
    {
        return ($this->FeedID = $_feedID);
    }
    /**
     * Get Postings value
     * @return decimal
     */
    public function getPostings()
    {
        return $this->Postings;
    }
    /**
     * Set Postings value
     * @param decimal $_postings the Postings
     * @return decimal
     */
    public function setPostings($_postings)
    {
        return ($this->Postings = $_postings);
    }
    /**
     * Get Slots value
     * @return decimal
     */
    public function getSlots()
    {
        return $this->Slots;
    }
    /**
     * Set Slots value
     * @param decimal $_slots the Slots
     * @return decimal
     */
    public function setSlots($_slots)
    {
        return ($this->Slots = $_slots);
    }
    /**
     * Get Spend value
     * @return decimal
     */
    public function getSpend()
    {
        return $this->Spend;
    }
    /**
     * Set Spend value
     * @param decimal $_spend the Spend
     * @return decimal
     */
    public function setSpend($_spend)
    {
        return ($this->Spend = $_spend);
    }
    /**
     * Get UserID value
     * @return int
     */
    public function getUserID()
    {
        return $this->UserID;
    }
    /**
     * Set UserID value
     * @param int $_userID the UserID
     * @return int
     */
    public function setUserID($_userID)
    {
        return ($this->UserID = $_userID);
    }
    /**
     * Get DefaultSelected value
     * @return unsignedByte
     */
    public function getDefaultSelected()
    {
        return $this->DefaultSelected;
    }
    /**
     * Set DefaultSelected value
     * @param unsignedByte $_defaultSelected the DefaultSelected
     * @return unsignedByte
     */
    public function setDefaultSelected($_defaultSelected)
    {
        return ($this->DefaultSelected = $_defaultSelected);
    }
    /**
     * Get ForceSelected value
     * @return unsignedByte
     */
    public function getForceSelected()
    {
        return $this->ForceSelected;
    }
    /**
     * Set ForceSelected value
     * @param unsignedByte $_forceSelected the ForceSelected
     * @return unsignedByte
     */
    public function setForceSelected($_forceSelected)
    {
        return ($this->ForceSelected = $_forceSelected);
    }
    /**
     * Get Restricted value
     * @return unsignedByte
     */
    public function getRestricted()
    {
        return $this->Restricted;
    }
    /**
     * Set Restricted value
     * @param unsignedByte $_restricted the Restricted
     * @return unsignedByte
     */
    public function setRestricted($_restricted)
    {
        return ($this->Restricted = $_restricted);
    }
    /**
     * Get FeedCost value
     * @return decimal
     */
    public function getFeedCost()
    {
        return $this->FeedCost;
    }
    /**
     * Set FeedCost value
     * @param decimal $_feedCost the FeedCost
     * @return decimal
     */
    public function setFeedCost($_feedCost)
    {
        return ($this->FeedCost = $_feedCost);
    }
    /**
     * Get FeedCostTypeID value
     * @return unsignedByte
     */
    public function getFeedCostTypeID()
    {
        return $this->FeedCostTypeID;
    }
    /**
     * Set FeedCostTypeID value
     * @param unsignedByte $_feedCostTypeID the FeedCostTypeID
     * @return unsignedByte
     */
    public function setFeedCostTypeID($_feedCostTypeID)
    {
        return ($this->FeedCostTypeID = $_feedCostTypeID);
    }
    /**
     * Get JobDescriptionMinimumLength value
     * @return int
     */
    public function getJobDescriptionMinimumLength()
    {
        return $this->JobDescriptionMinimumLength;
    }
    /**
     * Set JobDescriptionMinimumLength value
     * @param int $_jobDescriptionMinimumLength the JobDescriptionMinimumLength
     * @return int
     */
    public function setJobDescriptionMinimumLength($_jobDescriptionMinimumLength)
    {
        return ($this->JobDescriptionMinimumLength = $_jobDescriptionMinimumLength);
    }
    /**
     * Get JobDescriptionMaximumLength value
     * @return int
     */
    public function getJobDescriptionMaximumLength()
    {
        return $this->JobDescriptionMaximumLength;
    }
    /**
     * Set JobDescriptionMaximumLength value
     * @param int $_jobDescriptionMaximumLength the JobDescriptionMaximumLength
     * @return int
     */
    public function setJobDescriptionMaximumLength($_jobDescriptionMaximumLength)
    {
        return ($this->JobDescriptionMaximumLength = $_jobDescriptionMaximumLength);
    }
    /**
     * Get PostingsUsed value
     * @return decimal
     */
    public function getPostingsUsed()
    {
        return $this->PostingsUsed;
    }
    /**
     * Set PostingsUsed value
     * @param decimal $_postingsUsed the PostingsUsed
     * @return decimal
     */
    public function setPostingsUsed($_postingsUsed)
    {
        return ($this->PostingsUsed = $_postingsUsed);
    }
    /**
     * Get SpendUsed value
     * @return decimal
     */
    public function getSpendUsed()
    {
        return $this->SpendUsed;
    }
    /**
     * Set SpendUsed value
     * @param decimal $_spendUsed the SpendUsed
     * @return decimal
     */
    public function setSpendUsed($_spendUsed)
    {
        return ($this->SpendUsed = $_spendUsed);
    }
    /**
     * Get PostingsForward value
     * @return decimal
     */
    public function getPostingsForward()
    {
        return $this->PostingsForward;
    }
    /**
     * Set PostingsForward value
     * @param decimal $_postingsForward the PostingsForward
     * @return decimal
     */
    public function setPostingsForward($_postingsForward)
    {
        return ($this->PostingsForward = $_postingsForward);
    }
    /**
     * Get CultureID value
     * @return string|null
     */
    public function getCultureID()
    {
        return $this->CultureID;
    }
    /**
     * Set CultureID value
     * @param string $_cultureID the CultureID
     * @return string
     */
    public function setCultureID($_cultureID)
    {
        return ($this->CultureID = $_cultureID);
    }
    /**
     * Get CurrencyID value
     * @return string|null
     */
    public function getCurrencyID()
    {
        return $this->CurrencyID;
    }
    /**
     * Set CurrencyID value
     * @param string $_currencyID the CurrencyID
     * @return string
     */
    public function setCurrencyID($_currencyID)
    {
        return ($this->CurrencyID = $_currencyID);
    }
    /**
     * Get FeedIdentifier value
     * @return string|null
     */
    public function getFeedIdentifier()
    {
        return $this->FeedIdentifier;
    }
    /**
     * Set FeedIdentifier value
     * @param string $_feedIdentifier the FeedIdentifier
     * @return string
     */
    public function setFeedIdentifier($_feedIdentifier)
    {
        return ($this->FeedIdentifier = $_feedIdentifier);
    }
    /**
     * Get FeedName value
     * @return string|null
     */
    public function getFeedName()
    {
        return $this->FeedName;
    }
    /**
     * Set FeedName value
     * @param string $_feedName the FeedName
     * @return string
     */
    public function setFeedName($_feedName)
    {
        return ($this->FeedName = $_feedName);
    }
    /**
     * Get LogoURL value
     * @return string|null
     */
    public function getLogoURL()
    {
        return $this->LogoURL;
    }
    /**
     * Set LogoURL value
     * @param string $_logoURL the LogoURL
     * @return string
     */
    public function setLogoURL($_logoURL)
    {
        return ($this->LogoURL = $_logoURL);
    }
    /**
     * Get ShortDescription value
     * @return string|null
     */
    public function getShortDescription()
    {
        return $this->ShortDescription;
    }
    /**
     * Set ShortDescription value
     * @param string $_shortDescription the ShortDescription
     * @return string
     */
    public function setShortDescription($_shortDescription)
    {
        return ($this->ShortDescription = $_shortDescription);
    }
    /**
     * Get FeedCostType value
     * @return string|null
     */
    public function getFeedCostType()
    {
        return $this->FeedCostType;
    }
    /**
     * Set FeedCostType value
     * @param string $_feedCostType the FeedCostType
     * @return string
     */
    public function setFeedCostType($_feedCostType)
    {
        return ($this->FeedCostType = $_feedCostType);
    }
    /**
     * Get HTMLSupport value
     * @return string|null
     */
    public function getHTMLSupport()
    {
        return $this->HTMLSupport;
    }
    /**
     * Set HTMLSupport value
     * @param string $_hTMLSupport the HTMLSupport
     * @return string
     */
    public function setHTMLSupport($_hTMLSupport)
    {
        return ($this->HTMLSupport = $_hTMLSupport);
    }
    /**
     * Get SpendUsedCurrencyID value
     * @return string|null
     */
    public function getSpendUsedCurrencyID()
    {
        return $this->SpendUsedCurrencyID;
    }
    /**
     * Set SpendUsedCurrencyID value
     * @param string $_spendUsedCurrencyID the SpendUsedCurrencyID
     * @return string
     */
    public function setSpendUsedCurrencyID($_spendUsedCurrencyID)
    {
        return ($this->SpendUsedCurrencyID = $_spendUsedCurrencyID);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructCPostFeed
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

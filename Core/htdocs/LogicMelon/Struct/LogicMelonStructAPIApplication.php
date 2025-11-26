<?php
/**
 * File for class LogicMelonStructAPIApplication
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructAPIApplication originally named APIApplication
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructAPIApplication extends LogicMelonWsdlClass
{
    /**
     * The AdvertCandidateID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $AdvertCandidateID;
    /**
     * The AdvertID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $AdvertID;
    /**
     * The PostingID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $PostingID;
    /**
     * The FeedID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $FeedID;
    /**
     * The CandidateID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $CandidateID;
    /**
     * The ApplicationDate
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var dateTime
     */
    public $ApplicationDate;
    /**
     * The Viewed
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var boolean
     */
    public $Viewed;
    /**
     * The Score
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var short
     */
    public $Score;
    /**
     * The ProgressID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $ProgressID;
    /**
     * The UserID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $UserID;
    /**
     * The OrganisationID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var int
     */
    public $OrganisationID;
    /**
     * The Archived
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var boolean
     */
    public $Archived;
    /**
     * The Favourite
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var boolean
     */
    public $Favourite;
    /**
     * The SchemaIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $SchemaIdentifier;
    /**
     * The AdvertIdentifier
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $AdvertIdentifier;
    /**
     * The AdvertReference
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $AdvertReference;
    /**
     * The AdvertTitle
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $AdvertTitle;
    /**
     * The FeedName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $FeedName;
    /**
     * The EmailAddress
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $EmailAddress;
    /**
     * The LastName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $LastName;
    /**
     * The FirstName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $FirstName;
    /**
     * The HomePhone
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $HomePhone;
    /**
     * The WorkPhone
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $WorkPhone;
    /**
     * The MobilePhone
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $MobilePhone;
    /**
     * The Address
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Address;
    /**
     * The Ranking
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Ranking;
    /**
     * The Progress
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Progress;
    /**
     * The OrganisationName
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $OrganisationName;
    /**
     * The EmailBody
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIApplicationAttachment
     */
    public $EmailBody;
    /**
     * The EmailFirstAttachment
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIApplicationAttachment
     */
    public $EmailFirstAttachment;
    /**
     * The Parsed
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIApplicationAttachment
     */
    public $Parsed;
    /**
     * The Email
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructAPIApplicationAttachment
     */
    public $Email;
    /**
     * The APIProcessingMessages
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $APIProcessingMessages;
    /**
     * Constructor method for APIApplication
     * @see parent::__construct()
     * @param int $_advertCandidateID
     * @param int $_advertID
     * @param int $_postingID
     * @param int $_feedID
     * @param int $_candidateID
     * @param dateTime $_applicationDate
     * @param boolean $_viewed
     * @param short $_score
     * @param int $_progressID
     * @param int $_userID
     * @param int $_organisationID
     * @param boolean $_archived
     * @param boolean $_favourite
     * @param string $_schemaIdentifier
     * @param string $_advertIdentifier
     * @param string $_advertReference
     * @param string $_advertTitle
     * @param string $_feedName
     * @param string $_emailAddress
     * @param string $_lastName
     * @param string $_firstName
     * @param string $_homePhone
     * @param string $_workPhone
     * @param string $_mobilePhone
     * @param string $_address
     * @param string $_ranking
     * @param string $_progress
     * @param string $_organisationName
     * @param LogicMelonStructAPIApplicationAttachment $_emailBody
     * @param LogicMelonStructAPIApplicationAttachment $_emailFirstAttachment
     * @param LogicMelonStructAPIApplicationAttachment $_parsed
     * @param LogicMelonStructAPIApplicationAttachment $_email
     * @param string $_aPIProcessingMessages
     * @return LogicMelonStructAPIApplication
     */
    public function __construct($_advertCandidateID,$_advertID,$_postingID,$_feedID,$_candidateID,$_applicationDate,$_viewed,$_score,$_progressID,$_userID,$_organisationID,$_archived,$_favourite,$_schemaIdentifier = NULL,$_advertIdentifier = NULL,$_advertReference = NULL,$_advertTitle = NULL,$_feedName = NULL,$_emailAddress = NULL,$_lastName = NULL,$_firstName = NULL,$_homePhone = NULL,$_workPhone = NULL,$_mobilePhone = NULL,$_address = NULL,$_ranking = NULL,$_progress = NULL,$_organisationName = NULL,$_emailBody = NULL,$_emailFirstAttachment = NULL,$_parsed = NULL,$_email = NULL,$_aPIProcessingMessages = NULL)
    {
        parent::__construct(array('AdvertCandidateID'=>$_advertCandidateID,'AdvertID'=>$_advertID,'PostingID'=>$_postingID,'FeedID'=>$_feedID,'CandidateID'=>$_candidateID,'ApplicationDate'=>$_applicationDate,'Viewed'=>$_viewed,'Score'=>$_score,'ProgressID'=>$_progressID,'UserID'=>$_userID,'OrganisationID'=>$_organisationID,'Archived'=>$_archived,'Favourite'=>$_favourite,'SchemaIdentifier'=>$_schemaIdentifier,'AdvertIdentifier'=>$_advertIdentifier,'AdvertReference'=>$_advertReference,'AdvertTitle'=>$_advertTitle,'FeedName'=>$_feedName,'EmailAddress'=>$_emailAddress,'LastName'=>$_lastName,'FirstName'=>$_firstName,'HomePhone'=>$_homePhone,'WorkPhone'=>$_workPhone,'MobilePhone'=>$_mobilePhone,'Address'=>$_address,'Ranking'=>$_ranking,'Progress'=>$_progress,'OrganisationName'=>$_organisationName,'EmailBody'=>$_emailBody,'EmailFirstAttachment'=>$_emailFirstAttachment,'Parsed'=>$_parsed,'Email'=>$_email,'APIProcessingMessages'=>$_aPIProcessingMessages),false);
    }
    /**
     * Get AdvertCandidateID value
     * @return int
     */
    public function getAdvertCandidateID()
    {
        return $this->AdvertCandidateID;
    }
    /**
     * Set AdvertCandidateID value
     * @param int $_advertCandidateID the AdvertCandidateID
     * @return int
     */
    public function setAdvertCandidateID($_advertCandidateID)
    {
        return ($this->AdvertCandidateID = $_advertCandidateID);
    }
    /**
     * Get AdvertID value
     * @return int
     */
    public function getAdvertID()
    {
        return $this->AdvertID;
    }
    /**
     * Set AdvertID value
     * @param int $_advertID the AdvertID
     * @return int
     */
    public function setAdvertID($_advertID)
    {
        return ($this->AdvertID = $_advertID);
    }
    /**
     * Get PostingID value
     * @return int
     */
    public function getPostingID()
    {
        return $this->PostingID;
    }
    /**
     * Set PostingID value
     * @param int $_postingID the PostingID
     * @return int
     */
    public function setPostingID($_postingID)
    {
        return ($this->PostingID = $_postingID);
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
     * Get CandidateID value
     * @return int
     */
    public function getCandidateID()
    {
        return $this->CandidateID;
    }
    /**
     * Set CandidateID value
     * @param int $_candidateID the CandidateID
     * @return int
     */
    public function setCandidateID($_candidateID)
    {
        return ($this->CandidateID = $_candidateID);
    }
    /**
     * Get ApplicationDate value
     * @return dateTime
     */
    public function getApplicationDate()
    {
        return $this->ApplicationDate;
    }
    /**
     * Set ApplicationDate value
     * @param dateTime $_applicationDate the ApplicationDate
     * @return dateTime
     */
    public function setApplicationDate($_applicationDate)
    {
        return ($this->ApplicationDate = $_applicationDate);
    }
    /**
     * Get Viewed value
     * @return boolean
     */
    public function getViewed()
    {
        return $this->Viewed;
    }
    /**
     * Set Viewed value
     * @param boolean $_viewed the Viewed
     * @return boolean
     */
    public function setViewed($_viewed)
    {
        return ($this->Viewed = $_viewed);
    }
    /**
     * Get Score value
     * @return short
     */
    public function getScore()
    {
        return $this->Score;
    }
    /**
     * Set Score value
     * @param short $_score the Score
     * @return short
     */
    public function setScore($_score)
    {
        return ($this->Score = $_score);
    }
    /**
     * Get ProgressID value
     * @return int
     */
    public function getProgressID()
    {
        return $this->ProgressID;
    }
    /**
     * Set ProgressID value
     * @param int $_progressID the ProgressID
     * @return int
     */
    public function setProgressID($_progressID)
    {
        return ($this->ProgressID = $_progressID);
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
     * Get OrganisationID value
     * @return int
     */
    public function getOrganisationID()
    {
        return $this->OrganisationID;
    }
    /**
     * Set OrganisationID value
     * @param int $_organisationID the OrganisationID
     * @return int
     */
    public function setOrganisationID($_organisationID)
    {
        return ($this->OrganisationID = $_organisationID);
    }
    /**
     * Get Archived value
     * @return boolean
     */
    public function getArchived()
    {
        return $this->Archived;
    }
    /**
     * Set Archived value
     * @param boolean $_archived the Archived
     * @return boolean
     */
    public function setArchived($_archived)
    {
        return ($this->Archived = $_archived);
    }
    /**
     * Get Favourite value
     * @return boolean
     */
    public function getFavourite()
    {
        return $this->Favourite;
    }
    /**
     * Set Favourite value
     * @param boolean $_favourite the Favourite
     * @return boolean
     */
    public function setFavourite($_favourite)
    {
        return ($this->Favourite = $_favourite);
    }
    /**
     * Get SchemaIdentifier value
     * @return string|null
     */
    public function getSchemaIdentifier()
    {
        return $this->SchemaIdentifier;
    }
    /**
     * Set SchemaIdentifier value
     * @param string $_schemaIdentifier the SchemaIdentifier
     * @return string
     */
    public function setSchemaIdentifier($_schemaIdentifier)
    {
        return ($this->SchemaIdentifier = $_schemaIdentifier);
    }
    /**
     * Get AdvertIdentifier value
     * @return string|null
     */
    public function getAdvertIdentifier()
    {
        return $this->AdvertIdentifier;
    }
    /**
     * Set AdvertIdentifier value
     * @param string $_advertIdentifier the AdvertIdentifier
     * @return string
     */
    public function setAdvertIdentifier($_advertIdentifier)
    {
        return ($this->AdvertIdentifier = $_advertIdentifier);
    }
    /**
     * Get AdvertReference value
     * @return string|null
     */
    public function getAdvertReference()
    {
        return $this->AdvertReference;
    }
    /**
     * Set AdvertReference value
     * @param string $_advertReference the AdvertReference
     * @return string
     */
    public function setAdvertReference($_advertReference)
    {
        return ($this->AdvertReference = $_advertReference);
    }
    /**
     * Get AdvertTitle value
     * @return string|null
     */
    public function getAdvertTitle()
    {
        return $this->AdvertTitle;
    }
    /**
     * Set AdvertTitle value
     * @param string $_advertTitle the AdvertTitle
     * @return string
     */
    public function setAdvertTitle($_advertTitle)
    {
        return ($this->AdvertTitle = $_advertTitle);
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
     * Get EmailAddress value
     * @return string|null
     */
    public function getEmailAddress()
    {
        return $this->EmailAddress;
    }
    /**
     * Set EmailAddress value
     * @param string $_emailAddress the EmailAddress
     * @return string
     */
    public function setEmailAddress($_emailAddress)
    {
        return ($this->EmailAddress = $_emailAddress);
    }
    /**
     * Get LastName value
     * @return string|null
     */
    public function getLastName()
    {
        return $this->LastName;
    }
    /**
     * Set LastName value
     * @param string $_lastName the LastName
     * @return string
     */
    public function setLastName($_lastName)
    {
        return ($this->LastName = $_lastName);
    }
    /**
     * Get FirstName value
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->FirstName;
    }
    /**
     * Set FirstName value
     * @param string $_firstName the FirstName
     * @return string
     */
    public function setFirstName($_firstName)
    {
        return ($this->FirstName = $_firstName);
    }
    /**
     * Get HomePhone value
     * @return string|null
     */
    public function getHomePhone()
    {
        return $this->HomePhone;
    }
    /**
     * Set HomePhone value
     * @param string $_homePhone the HomePhone
     * @return string
     */
    public function setHomePhone($_homePhone)
    {
        return ($this->HomePhone = $_homePhone);
    }
    /**
     * Get WorkPhone value
     * @return string|null
     */
    public function getWorkPhone()
    {
        return $this->WorkPhone;
    }
    /**
     * Set WorkPhone value
     * @param string $_workPhone the WorkPhone
     * @return string
     */
    public function setWorkPhone($_workPhone)
    {
        return ($this->WorkPhone = $_workPhone);
    }
    /**
     * Get MobilePhone value
     * @return string|null
     */
    public function getMobilePhone()
    {
        return $this->MobilePhone;
    }
    /**
     * Set MobilePhone value
     * @param string $_mobilePhone the MobilePhone
     * @return string
     */
    public function setMobilePhone($_mobilePhone)
    {
        return ($this->MobilePhone = $_mobilePhone);
    }
    /**
     * Get Address value
     * @return string|null
     */
    public function getAddress()
    {
        return $this->Address;
    }
    /**
     * Set Address value
     * @param string $_address the Address
     * @return string
     */
    public function setAddress($_address)
    {
        return ($this->Address = $_address);
    }
    /**
     * Get Ranking value
     * @return string|null
     */
    public function getRanking()
    {
        return $this->Ranking;
    }
    /**
     * Set Ranking value
     * @param string $_ranking the Ranking
     * @return string
     */
    public function setRanking($_ranking)
    {
        return ($this->Ranking = $_ranking);
    }
    /**
     * Get Progress value
     * @return string|null
     */
    public function getProgress()
    {
        return $this->Progress;
    }
    /**
     * Set Progress value
     * @param string $_progress the Progress
     * @return string
     */
    public function setProgress($_progress)
    {
        return ($this->Progress = $_progress);
    }
    /**
     * Get OrganisationName value
     * @return string|null
     */
    public function getOrganisationName()
    {
        return $this->OrganisationName;
    }
    /**
     * Set OrganisationName value
     * @param string $_organisationName the OrganisationName
     * @return string
     */
    public function setOrganisationName($_organisationName)
    {
        return ($this->OrganisationName = $_organisationName);
    }
    /**
     * Get EmailBody value
     * @return LogicMelonStructAPIApplicationAttachment|null
     */
    public function getEmailBody()
    {
        return $this->EmailBody;
    }
    /**
     * Set EmailBody value
     * @param LogicMelonStructAPIApplicationAttachment $_emailBody the EmailBody
     * @return LogicMelonStructAPIApplicationAttachment
     */
    public function setEmailBody($_emailBody)
    {
        return ($this->EmailBody = $_emailBody);
    }
    /**
     * Get EmailFirstAttachment value
     * @return LogicMelonStructAPIApplicationAttachment|null
     */
    public function getEmailFirstAttachment()
    {
        return $this->EmailFirstAttachment;
    }
    /**
     * Set EmailFirstAttachment value
     * @param LogicMelonStructAPIApplicationAttachment $_emailFirstAttachment the EmailFirstAttachment
     * @return LogicMelonStructAPIApplicationAttachment
     */
    public function setEmailFirstAttachment($_emailFirstAttachment)
    {
        return ($this->EmailFirstAttachment = $_emailFirstAttachment);
    }
    /**
     * Get Parsed value
     * @return LogicMelonStructAPIApplicationAttachment|null
     */
    public function getParsed()
    {
        return $this->Parsed;
    }
    /**
     * Set Parsed value
     * @param LogicMelonStructAPIApplicationAttachment $_parsed the Parsed
     * @return LogicMelonStructAPIApplicationAttachment
     */
    public function setParsed($_parsed)
    {
        return ($this->Parsed = $_parsed);
    }
    /**
     * Get Email value
     * @return LogicMelonStructAPIApplicationAttachment|null
     */
    public function getEmail()
    {
        return $this->Email;
    }
    /**
     * Set Email value
     * @param LogicMelonStructAPIApplicationAttachment $_email the Email
     * @return LogicMelonStructAPIApplicationAttachment
     */
    public function setEmail($_email)
    {
        return ($this->Email = $_email);
    }
    /**
     * Get APIProcessingMessages value
     * @return string|null
     */
    public function getAPIProcessingMessages()
    {
        return $this->APIProcessingMessages;
    }
    /**
     * Set APIProcessingMessages value
     * @param string $_aPIProcessingMessages the APIProcessingMessages
     * @return string
     */
    public function setAPIProcessingMessages($_aPIProcessingMessages)
    {
        return ($this->APIProcessingMessages = $_aPIProcessingMessages);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructAPIApplication
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

<?php
interface VoltIViewFilter
{
	public function setParentView(VoltView $v);
	
	public function getParentView();
	
	/**
	 * Each filter for a view must have a unique name.  The name is used
	 * to request a particular filter's data from a view and forms part
	 * of the name of the HTML form element(s) used to represent the filter
	 * on a web page.
	 *
	 * @param string $key
	 */
	public function setName($key);
	
	/**
	 * @return string the name of the filter
	 */
	public function getName();

	/**
	 * Called by the View object's refresh() method.  Use this method to
	 * update any options displayed to the user (included mainly with
	 * dropdown boxes, checkboxes and radio buttons in mind).
	 * 
	 * @param mixed dataSource An array, a database link...
	 */ 
	public function refresh(PDO $dataSource = null);

	
	/**
	 * Equivalent of the old setState() method
	 *
	 * @param unknown_type $selected
	 */
	public function setValue($value);
	
	public function getValue();

	/**
	 * Returns the filter to its initial state.
	 *
	 */
	public function reset();
	
	public function toHTML();
		
	public function getSQLStatement();
	
	/**
	 * The format for a description
	 *
	 * @param string $strDescriptionFormat sprintf() compatibile string
	 */
	public function setDescriptionFormat($strFormat);
	
	/**
	 * Used for creating filter breadcrumbs
	 * 
	 * @return a sort description of the filter's status
	 */
	public function getDescription();
}
?>
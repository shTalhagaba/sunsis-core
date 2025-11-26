<?php
/**
 * ISS framework equivalent of Zend_Controller_Action.
 * @author iss
 */
abstract class ActionController implements IAction
{
	public function __construct()
	{
		$this->_init();
	}

	/**
	 * @override
	 * @param PDO $link
	 */
	public function execute(PDO $link)
	{
		$this->_link = $link;

		$subaction = $this->_getParam('_subaction');
		if (!$subaction) {
			$subaction = $this->_getParam('subaction', 'index');
		}

		$method = $subaction . 'Action';
		if ($subaction && method_exists($this, $method)) {
			$this->$method($link);
		} else {
			$this->indexAction($link);
		}
	}

	/**
	 * Override this to customise initialisation of the ActionController
	 */
	protected function _init()
	{

	}

	/**
	 * Default action. Must be implemented in subclass.
	 * @abstract
	 * @param PDO $link
	 */
	public abstract function indexAction(PDO $link);

	/**
	 * Retrieves a variable from $_REQUEST
	 * @param string $name
	 * @param mixed $default Default value if the variable does not exist
	 * @return string|null
	 */
	protected function _getParam($name, $default = null)
	{
		$value = isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
		if (is_string($value)) {
			$value = trim($value);
		} else if(is_array($value)) {
			array_walk($value, 'trim');
		} else {
			$value = $default;
		}
		return $value;
	}

	/** @var PDO */
	protected $_link;
}
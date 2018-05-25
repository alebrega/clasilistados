<?php
require_once ("view.fw.php");
require_once ("controller.fw.php");

/**
 * This class manages details involving the communication between modules and views
 *
 */
abstract class cModuleController_fw extends cController_fw
{
	/**
	 * The view id
	 *
	 * @var string
	 */
	protected $viewId = null;

	/**
	 * Initialize a new module controller
	 *
	 * @param string $name
	 * @param string $id
	 */
	public function __construct($name, $id = null)
	{
		$this->viewId = $id;
		parent::__construct($name);
	}

	/**
	 * Gets the view for the controller
	 *
	 * @return cModuleView_fw
	 */
	public function getView()
	{
		if (is_null($this->view)) {
			$this->view = new cModuleView_fw($this->viewName, $this->viewId);
		}
		return $this->view;
	}

	/**
	 * Renders the view asociated with the page
	 *
	 */
	public function render()
	{
		$this->view->render();
	}
}
?>
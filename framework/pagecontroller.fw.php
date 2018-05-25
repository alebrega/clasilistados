<?php
require_once("pageview.fw.php");

/**
 * Class that represent a single page within the site.
 *
 */
class cPageController_fw extends cController_fw
{
	/**
	 * Renders the view asociated with the page
	 *
	 */
	public function render()
	{
		$this->view->render();
	}

	/**
	 * Gets the view of the page
	 *
	 * @return cPageView_fw
	 */
	public function getView() {
		if(is_null($this->view)) {
			$this->view = new cPageView_fw($this->viewName);
		}
		return $this->view;
	}
	
	/**
	 * Get the content of the view without rendering
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->getView()->getContent();
	}
}
?>
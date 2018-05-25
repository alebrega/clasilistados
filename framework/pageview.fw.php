<?php
include_once("view.fw.php");

/**
 * Class that represents the visible end of a page. In general this includes all the
 * generated content of modules.
 *
 */
class cPageView_fw extends cView_fw {
	const WEB_VIEW_PATH = "/vistas/paginas/";

	/**
	 * Renders the content to an internal buffer
	 *
	 */
	public function process() {
		$path=self::WEB_VIEW_PATH;
		$filename = $_SERVER["DOCUMENT_ROOT"] . $path . strtolower($this->getName());
		$this->setViewFile($filename . ".pv.php");
		parent::process();
	}
	
	/**
	 * Renders the profiling summary of the execution time of the request
	 *
	 */
	protected function renderProfileSummary() {
		$view = $this->findView("profilesummary");
		$view->render();
	}
}
?>
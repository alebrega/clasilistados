<?php
require_once("modulecontroller.fw.php");
require_once("view.fw.php");
/**
 * Class that represents the visible end of a module
 *
 */
class cModuleView_fw extends cView_fw {
	const WEB_VIEW_PATH = "/vistas/modulos/";

	/**
	 * Renders the view to an internal buffer
	 *
	 */
	public function process() {
		$path=self::WEB_VIEW_PATH;
		$filename = $_SERVER["DOCUMENT_ROOT"] . $path . strtolower($this->getName());
		$this->setViewFile($filename . ".mv.php");
		parent::process();
	}
}
?>
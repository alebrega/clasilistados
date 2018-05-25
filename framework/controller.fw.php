<?php
require_once("view.fw.php");
require_once("moduleview.fw.php");

abstract class cController_fw
{
	const WEB_MODULES_PATH = "/controladores/modulos/";
	const WEB_PAGES_PATH = "/controladores/paginas/";

	private $modules = array();
	protected $view = null;
	protected $viewName = null;
	protected $registro = null;
	
	public function __construct($name)
	{
		global $registro;
		$this->registro=$registro;
		$this->viewName = $name;
	}
	
	public function setRegistro(Registro $registro){
		$this->registro=$registro;
	}
	public function getRegistro(){
		return $this->registro;
	}
	public abstract function getView();


	static public function getPage($pageName)
	{
		$viewName = strtolower($pageName);

		$filename = $_SERVER['DOCUMENT_ROOT'] . self::WEB_PAGES_PATH .$viewName;
		
		$className = "c{$pageName}";
		
		$className .= "_pc";
		
		require_once($filename . ".pc.php");
		
		$controller = new $className($viewName);
		self::initializeController($controller);
		
		return $controller;
	}

	/**
	 * Creates a module
	 *
	 * @param string $name
	 * @param string $id
	 * @return cModuleController_fw
	 */
	static public function createModule($name,$id=null){
		
		$className = "c" . $name;
		$fileName = $_SERVER["DOCUMENT_ROOT"] . self::WEB_MODULES_PATH . strtolower($name);
		
		$className .= "_mc";
		if(file_exists($fileName.".mc.php")){
			require_once($fileName . ".mc.php");

			$controller = new $className($name, $id);

			self::initializeController($controller);

			return $controller;
		}
		else{
			return null;
		}
	}

	static private function initializeController(cController_fw $controller) {
		$view = $controller->getView();

		$view->setUseCache($controller->useCache());
		$view->setCacheKey($controller->getCacheKey());

		if($controller->getView()->loadCache() === false) {
			$view->setShouldBeCached($controller->shouldBeCached());
			$controller->init();
		}
		return $controller;
	}

	/**
	 * Gets a ModuleController with a specific name
	 *
	 * @param string $name
	 * @param string $id
	 * @param string $viewName
	 * @return cModuleController_fw
	 */
	public function getModule($name, $id = null)
	{
		$moduleId = ($id !== null ? $name.$id : $name);

		if(!isset($this->modules[$moduleId]))
		{
			$module = cController_fw::createModule($name,$id);
			if(is_null($module))
			{
				trigger_error("No existe el controlador para el modulo: {$name}",E_USER_ERROR);
			}

			$this->getView()->addView($module->getView());
			$this->modules[$moduleId] = $module;
		}
		
		return $this->modules[$moduleId];
	}

	/**
	 * Sets a variable value to be used by the view
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function setVar($name, $value)
	{
		$this->getView()->setVar($name, $value);
	}
	/**
	 * Indicates which is the key to be used by the caching feature
	 *
	 * @return array
	 */
	public function getCacheKey()
	{
		return false;
	}

	/**
	 * Indicates if the controller should be cached
	 *
	 * @return boolean
	 */
	protected function shouldBeCached()
	{
		return $this->useCache();
	}

	/**
	 * Indicates if the controller use cache
	 *
	 * @return boolean
	 */
	protected function useCache()
	{
		return false;
	}
	
}
?>
<?php
require_once("controller.fw.php");
require_once("modulecontent.fw.php");
/**
 * Represent visible content
 *
 */
abstract class cView_fw
{
	private $vars = array();
	private $views = array();
	private $cacheKey = false;
	private $cachedContent = false;
	private $useCache = false;
	private $shouldBeCached = false;
	private $viewFile;
	private $name;
	private $id;
	protected $itHasContent = false;
	protected $content="";
	private $processed = false;
	private $rendered = false;
	private $helper=null;
	private static $preRenderInterceptors = array();
	/**
	 * Initialize a new view
	 *
	 * @param string $name
	 * @param string $id
	 */
	public function __construct ($name, $id = null)
	{
		$id = ($id !== null ? $name . $id : $name);
		$this->setName($name);
		$this->setId($id);
	}

	/**
	 * Sets the name of the view
	 *
	 * @param string $name
	 */
	public function setName ($name)
	{
		$this->name = $name;
	}

	/**
	 * Gets the name of the view
	 *
	 * @return string
	 */
	public function getName ()
	{
		return $this->name;
	}

	/**
	 * Sets the ID of the view
	 *
	 * @param string $id
	 */
	public function setId ($id)
	{
		$this->id = $id;
	}

	public function getId ()
	{
		return $this->id;
	}

	/**
	 * Get the cached content of the view. If there is no content this will return FALSE.
	 *
	 * @return string or FALSE
	 */
	public function getCachedContent ()
	{
		return $this->cachedContent;
	}

	/**
	 * Sets the cached content of the view
	 *
	 * @param string $cachedContent
	 */
	public function setCachedContent ($cachedContent)
	{
		$this->cachedContent = $cachedContent;
	}

	/**
	 * Sets a specific value to be used by the view with a specific name.
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function setVar ($name, $value)
	{
		if (! isset($this->vars)) $this->vars = array();
		$this->vars[$name] = $value;
	}

	/**
	 * Gets a specific value for the view variables
	 *
	 * @param string $name
	 * @return string
	 */
	public function getVar ($name)
	{
		if (! array_key_exists($name, $this->vars)) {
			trigger_error("Variable: '" . $name . "' no fue seteada");
		}
		return $this->vars[$name];
	}

	/**
	 * Sets the key to be used when caching
	 *
	 * @param array $cacheKey
	 */
	public function setCacheKey ($cacheKey)
	{
		$this->cacheKey = $cacheKey;
	}

	/**
	 * Gets the cache key
	 *
	 * @return array
	 */
	public function getCacheKey ()
	{
		return $this->cacheKey;
	}

	/**
	 * Tries to load view content from cache
	 *
	 * @return boolean
	 */
	public function loadCache ()
	{
		if($this->cachedContent === false)
		{
			if ($this->useCache() && (($cacheKey = $this->getCacheKey()) !== false)) {
				$this->cachedContent = Cache::get($cacheKey['key']);
				echo ('get del cache KEY '.$cacheKey['key']);
			}
		}
		return ($this->cachedContent !== false);
	}

	/**
	 * Sets the view file to use for the content
	 *
	 * @param string $filename
	 */
	public function setViewFile ($filename)
	{
		$this->viewFile = $filename;
	}

	/**
	 * Gets the view file used to render the content
	 *
	 * @return string
	 */
	public function getViewFile ()
	{
		return $this->viewFile;
	}

	/**
	 * Adds a view to be rendered when this view is rendered.
	 *
	 * @param cView_fw $view
	 */
	public function addView (cView_fw $view)
	{
		//echo("<br> ****** " . $view->getId());
		$this->views[$view->getId()] = $view;
	}
	/**
	 * Renders the view to an internal buffer
	 *
	 */
	public function process() {
		if (!$this->getCachedContent()) {
			ob_start();
			$viewFile=$this->getViewFile();
			if(empty($viewFile)) {
				trigger_error("Vista para " . $this->name . " no fue seteada");
			}
			include ($viewFile);
			$this->content = ob_get_contents();
			ob_end_clean();
				
			if ($this->shouldBeCached() && (($cacheKey = $this->getCacheKey()) !== false)) {
				Cache::set($cacheKey['key'], serialize(new cModuleContent_fw($this->content)) , $cacheKey['compress'], $cacheKey['expire']);
				echo ('set del cache KEY '.$cacheKey['key']);
			}
				
		}
		else {
			$moduleContent = unserialize($this->cachedContent);
			if(is_object($moduleContent)) {
				$this->content = $moduleContent->getContent();
			} else {
				$this->content = $this->cachedContent;
			}
		}
		$this->itHasContent = strlen($this->content)>0;

		$this->processed = true;
	}

	/**
	 * Renders the view to an internal buffer and then flushes the buffer to the output
	 *
	 */
	public function render ()
	{
		$this->process();
		$this->showContent();
		$this->rendered=true;
	}

	/**
	 * Flushes the view content to the output
	 *
	 */
	public function showContent() {
		echo $this->content;
	}

	/**
	 * Gets the view content
	 * First check if the view has been processed
	 *
	 * @return string
	 */
	public function getContent() {
		if(!$this->processed)
		$this->process();

		return $this->content;
	}

	/**
	 * Indicates if the view has content
	 *
	 * @return boolean
	 */
	public function hasContent() {
		return $this->itHasContent;
	}

	/**
	 * Gets the specified view. Renders it's content to it's internal buffer and returns it.
	 *
	 * @param string $name
	 * @param string $id
	 * @return cView_fw
	 */
	public function getModuleView($name, $id = null) {
		$view = $this->findView($name, $id);
		$view->process();
		return $view;
	}

	/**
	 * Finds and returns a view. If the view doesn't exists it'll generate a dumb view.
	 *
	 * @param string $name
	 * @param string $id
	 * @return cView_fw
	 */
	protected function findView($name, $id = null) {
		$id = ($id !== null ? $name . $id : $name);
		if (!isset($this->views[$id])) {
			$module = cController_fw::createModule($name,$id);
			if(is_null($module)) {
				$view = new cModuleView_fw($name, $id);
			}
			else{
				$view = $module->getView();
			}
				
			$this->views[$id] = $view;
		}
		return $this->views[$id];
	}

	/**
	 * Finds a view and renders it.
	 *
	 * @param string $name
	 * @param string $id
	 */
	public function renderModule ($name, $id = null)
	{

		$view = $this->findView($name, $id);
		$view->render();
	}

	/**
	 * Sets the flag that indicates if the view uses cache
	 *
	 * @param boolean $useCache
	 */
	public function setUseCache($useCache)
	{
		$this->useCache = $useCache;
	}

	/**
	 * Indicates if the view uses cache
	 *
	 * @return boolean
	 */
	public function useCache()
	{
		return $this->useCache;
	}

	/**
	 * Sets the flag that indicates if the content will be cached
	 *
	 * @param boolean $should
	 */
	public function setShouldBeCached($should)
	{
		$this->shouldBeCached = $should;
	}

	/**
	 * Indicates if the view content will be cached
	 *
	 * @return boolean
	 */
	public function shouldBeCached()
	{
		return $this->shouldBeCached;
	}
	/**
	 * Indicates if the view has been rendered
	 *
	 * @return boolean
	 */
	public function wasRendered() {
		return $this->rendered;
	}
	public function helper(){
		if (is_null($this->$this->helper)){
			$this->helper=new Helper();
		}
		return $this->helper;
	}
}
?>
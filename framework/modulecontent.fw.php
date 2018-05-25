<?php
class cModuleContent_fw {
	private $content="";
	private $timestamp;

	public function __construct( $content) {
		$this->content = $content;
		$this->timestamp = time();
	}

	public function getContent() {
		return $this->content;
	}
}
?>
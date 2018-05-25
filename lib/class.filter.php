<?php

class Filter {
	private static $instance = null;


	/**
	 * Return a singleton instance of Filter
	 *
	 * @return Filter
	 */
	public static function getInstance() {
		if (self::$instance == null)
		self::$instance = new self();
		return self::$instance;

	}

	protected function normalize_chars($html)
	{
		$html = rawurldecode($html);
		return $html;
	}
	
	protected function remove_bad_words($string) {
	$bad = array(
					"vbscript\s*:"		=> '',
					"javascript\s*:"	=> '',
					"expression\s*\("	=> '', // CSS and IE
					"Redirect\s+302"	=> ''
					);
					
		foreach ($bad as $key => $val)
		{
			$string = preg_replace("#".$key."#i", $val, $string);   
		}
		
		return $string;
	}
	
	/**
	 * Method how add a target="_blank" atribute to  <a> tags
	 *
	 * @param String $html
	 * @return String
	 */
	protected function linksToBlank($html)
	{
		$html = preg_replace("/target(?:\s*)=(?:\s*)[\'\"]_blank[\'\"]/si", "", $html);
		$html = str_ireplace("<a", "<a target='_blank' rel='nofollow' ", $html);
		return $html;
	}
	
	public function filter_html($html)
	{
		
		$html = $this->normalize_chars($html);
		$html = $this->remove_bad_words($html);
		$html = $this->linksToBlank($html);	
		
		$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                      '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly		
	  		          "@<object[^>]*?>.*?</object>@si",    // Strip embed tags properly
                      '@<iframe[^>]*?>.*?</iframe>@siU',    // Strip style tags properly
                      '@<![\s\S]*?--[ \t]*>@'        // Strip multi-line comments including CDATA
		);
    	//$html = @str_ireplace(array("\n", "\r"), "<br>", $html);
    	//$html = str_replace("\n", "<br>", $html);
		$html = @preg_replace($search, '', $html);
		return $html;
	}
	/**
	 * Removes chararcters that are not valid for usernames and emails
	 *
	 * @param string $nicknameOrEmail
	 * @return string
	 */
	public function nicknameOrEmail($nicknameOrEmail) {
		return @preg_replace('/[^A-Za-z0-9_\+\-@\.]/', '',$nicknameOrEmail);
	}
	
	public function filter_nickname($nickname) {
		return @preg_replace('/[^A-Za-z0-9_\-]/', '',$nickname);
	}


	/**
	 * Method used by cleanArray() to sanitize array nodes.
	 *
	 * @param string $val
	 * @return string
	 * @access public
	 */
	public function clean_value($val) {
		if ($val == "") {
			return "";
		}
		//Replace odd spaces with safe ones
		$val = str_replace(" ", " ", $val);
		$val = str_replace(chr(0xCA), "", $val);
		//Encode any HTML to entities.
		$val = $this->clean_for_render_html($val);
		//Double-check special chars and replace carriage returns with new lines
		$val = preg_replace("/\\\$/", "$", $val);
		$val = preg_replace("/\r\n/", "\n", $val);
		$val = str_replace("!", "!", $val);
		$val = str_replace("'", "'", $val);
		//Allow unicode (?)
		$val = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $val);
		//Swap user-inputted backslashes (?)
		$val = preg_replace("/\\\(?!&amp;#|\?#)/", "\\", $val);
		return $val;
	}


	/**
	 * Returns given string safe for display as HTML. Renders entities.
	 *
	 * @param string $string
	 * @param boolean $remove If true, the string is stripped of all HTML tags
	 * @return string
	 * @access public
	 */
	public function clean_for_render_html($string, $remove = false) {
		if ($remove) {
			$string = strip_tags($string);
		} else {
			$patterns = array("/\&/", "/%/", "/</", "/>/", '/"/', "/'/", "/\(/", "/\)/", "/\+/", "/-/");
			$replacements = array("&amp;", "&#37;", "&lt;", "&gt;", "&quot;", "&#39;", "&#40;", "&#41;", "&#43;", "&#45;");
			$string = preg_replace($patterns, $replacements, $string);
		}
		return $string;
	}


	public function filter_clear_text($string) {
		$string = trim($this->normalize_chars(trim($string)));
		$html = $this->remove_bad_words($html);

    $remove = array("%3c", "&#x3C;", "%253c" /* < */, "&#60", '<',  "%3e" /* > */, "%0e" /* > */, '>', ";&#x3E;", "&#62");
		$string = str_ireplace($remove, '', $string);
    $string = str_ireplace(array("\n", "\r"), " ", $string);
		return $string;
	}
}
?>
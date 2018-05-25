<?php 
class Form
{
	private $fields=array(); # contains field names and labels
	private $action; # name of program to process form
	private $submit = "Submit Form"; # value for the submit button
	private $Nfields = 0; # number of fields added to the form
	private $fname; # any name ou ant to appear at the top as the form name or title
	private $type; # this allows the class to generate a file upload form. You can pass a string or, path to a file, for example an image logo. The argument will automatically display as the form name or form title. 
	private $method="get";
	private $enctype=null;
			   
			    
	/* Constructor: User passes in the name of the script where
	* form data is to be sent ($processor) and the value to show
	* on the submit button.
	*/
	function __construct($action,$submit,$fname,$method=null,$enctype=null){
		$this->action = $action;
		$this->submit    = $submit;
		$this->fname     = $fname;
		if (!is_null($method))
			$this->method    = $method;
		if (!is_null($enctype))
			$this->enctype    = $enctype;
	}
	public function getFormOpenTag(){
		$enctype=(!is_null($this->enctype))?'enctype="'.$this->enctype.'"':'';
		return '<form action="'.$this->action.'" name="'.$this->fname.'" id="'.$this->fname.'" method="'.$this->method.'" '.$enctype.'>';
	}
	public function getFormCloseTag(){
		return '</form>';
	}
	public function getInputTextTag($input){
		$eventos=!is_null($input['evento'])?$input['evento']:'';
		return '<input name="'.$input['name'].'" id="'.$input['name'].'" value="'.$input['value'].'" type="text" '.$eventos.'> ';
	}
	public function getInputHiddenTag($input){
		return '<input name="'.$input['name'].'" id="'.$input['name'].'" value="'.$input['value'].'" type="hidden">';	
	}
	public function addFieldHidden($name,$value){
		$this->fields["hidden"][]= array ("name"=>$name,"value"=>$value);
	}
	
	
	function displaySearchForm(){
		$html= $this->getFormOpenTag();
		foreach ($this->fields as $type=>$inputs)
		{
			switch ($type){
				case "hidden":
					foreach ($inputs as $input){
						$html.=$this->getInputHiddenTag($input);	
					}
					break;
				case "text":
					foreach ($inputs as $input){
						$html.=$this->getInputTextTag($input);
						$html.='<br />';
					}
					break;
				case "select":
					foreach ($inputs as $input){
						$html.='<select name="'.$input['name'].'" id="'.$input['name'].'">';
						foreach ($input["options"] as $option){
							if ($option['value']==10){
								continue; //no se busca en foros de debate
							}
							$selectedHtml=($option["selected"])? ' selected="selected"' : '';
							$html.='<option value="'.$option['value'].'"'.$selectedHtml.'>'.$option['text'].'</option>';
						}
						$html.='</select>';
					}
					break;
					
			}
		}
		$html.=$this->getSubmitButton("go");
		$html.=$this->getFormCloseTag();
		echo $html;
	}
	public function getSubmitButton($name){
		return '<input type="submit" value="'.$this->submit.'" name="'.$name.'" id="'.$name.'">';
	}
	public function setSubmitValue($value){
		$this->submit=$value;
	}
	//option $text=>$value
	public function addFieldSelect($name,array $option){
		$this->fields["select"][]= array ("name"=>$name,"options"=>$option);
	}
	public function addFieldText($name,$value,$evento=null){
		$this->fields["text"][]= array ("name"=>$name,"value"=>$value,"evento"=>$evento);
	}
}
?>
<?php
class Publicidad_EnlacesPatrocinados{
	
	private $tabla="cl2_enlaces_patrocinados";
	private $enlaces="";
	
	public function cargar($registro){
		$sql="SELECT titulo,vinculo FROM ".$this->tabla." WHERE enabled='1' LIMIT 5;";
		$result=$registro->get("db")->query($sql);
		while ($row = $result->fetch_array()){
			$this->enlaces.='<a href="'.$row["vinculo"].'" rel="nofollow" target="_blank">'.strtolower($row["titulo"]).'</a>';
		}
	}
	public function get(){
		return $this->enlaces;
	}
}
?>
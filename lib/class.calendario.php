<?php
class Calendario{

	private static $filtroLocation;
	
	
	
	public function setFiltroLocation ($filtroLocation)
	{
		self::$filtroLocation=$filtroLocation;
	}
	public function getFiltroLocation(){
		global $locacion;
		return $locacion->getFiltroLocation();
	}
	public function dia_semana ($maketime) {
    	$dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
    	return ($dias[date("w", $maketime)]);
	}
	public static function getMesEspanol($month_name){
    	if ($month_name=="January") $month_name="Enero";
		if ($month_name=="February") $month_name="Febrero";
		if ($month_name=="March") $month_name="Marzo";
		if ($month_name=="April") $month_name="Abril";
		if ($month_name=="May") $month_name="Mayo";
		if ($month_name=="June") $month_name="Junio";
		if ($month_name=="July") $month_name="Julio";
		if ($month_name=="August") $month_name="Agosto";
		if ($month_name=="September") $month_name="Setiembre";
		if ($month_name=="October") $month_name="Octubre";
		if ($month_name=="November") $month_name="Noviembre";
		if ($month_name=="December") $month_name="Diciembre";
		return $month_name;
    }
    public function calendarLinkSeo($day,$month,$year){
    	global $h;
    	if ($day==date("j")){
    		return '<a href="'.$h->getFechaEventoLink($day,$month,$year).'">'.self::renderBoldDay($day).'</a>';
 	  	}
    	return '<a href="'.$h->getFechaEventoLink($day,$month,$year).'">'.$day.'</a>';
    }
    public function getDayStart(){
    	$day=date("j");
    	$day_start=0;
    	while ($day_start!=7){ //mientras no sea lunes
	    	$year=date('Y');
			$month=date('n');
			$maketime = mktime (0,0,0, $month, $day,$year);
			$day_start= date("N", $maketime);
			$day--;
		}
		return date("j",$maketime);
    }
    /*
	* 
	* retorna un array con todos los dias del calendario a mostrar. Siempre es 28 la cantidad de dias.
	* */
    public function getCalendarDays(){
			
		$maximo_dias=MAXIMO_DIAS_CALENDARIO;
		$pos=0;
		$dias=array();
		$month=date('n');
		$year=date('Y');
		$day_start=self::getDayStart();
		$dias_del_mes=date("t",$month);
		if ($month==11){
			$dias_del_mes=30;
		}
		for ($i=$day_start;$i<=$dias_del_mes;$i++){
			if ($pos>=$maximo_dias){
				break;
			}
			$dias[$pos]= array("day"=>$i,"month"=>$month,"year"=>$year);
			$pos++;
			
		}
    	$month_next=$month+1;
		$maketime = mktime (0,0,0, $month_next, 1,$year);
		$day_next= 1;
		$month_next=date("n", $maketime) ;
		$year_next=date("Y", $maketime);
			
		while ($pos<$maximo_dias){
			$dias[$pos]= array("day"=>$day_next,"month"=>$month_next,"year"=>$year_next);
			$pos++;
			$day_next++;
		}
		return $dias;
	}
	public function renderBoldDay($day){
		return "<b>".$day."</b>";
	}
	public static function getMkTimeMenosDias($dias){
		return mktime (0,0,0,date("m"),date("d")-$dias,date("Y"));
	}
	public static function renderCalendar(){
		global $h,$locacion;
		$dias=self::getCalendarDays();
		
		$calendar ='<div class="bancalendar"><a href="'.$h->getFechaEventoLink(date("d"),date("m"),date("Y")).'">agenda de eventos</a></div>';
		$calendar .= '<table class="cal">'."\n";
		
		//$calendar .='<div id="caldiv">';
		$first_day=7;
		$day_names = array(); #generate all the day names according to the current locale
		$calendar .='<tr id="dias">';
		for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400){ #January 4, 1970 was a Sunday
			$d = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name
			if ($d=="Monday") $d="l";
			if ($d=="Tuesday") $d="m";
			if ($d=="Wednesday") $d="m";
			if ($d=="Thursday") $d="j";
			if ($d=="Friday") $d="v";
			if ($d=="Saturday") $d="s";
			if ($d=="Sunday") $d="d"; 
			$d=strtoupper($d);
			$calendar .= '<th abbr="'.htmlentities($d).'">'.$d.'</th>';
		}
		$calendar .= "</tr><tr>";
		for($posday=0;$posday<count($dias);$posday++){
			
			if($posday%7 == 0){
				$calendar .= "</tr><tr>";
			}
			if ($dias[$posday]["day"]==date("j")){
				
			}
			if ($dias[$posday]["day"]==date("j")){
				$calendar .= '<td style="background: #FFFF99;">'.self::calendarLinkSeo($dias[$posday]["day"],$dias[$posday]["month"],$dias[$posday]["year"]).'</td>';
			}else{
				$calendar .= '<td>'.self::calendarLinkSeo($dias[$posday]["day"],$dias[$posday]["month"],$dias[$posday]["year"]).'</td>';	
			}
			
		}
		
		$calendar.='</tr></table>';
		return $calendar;
		
	}
}
?>
<?php
class Tracking{
	
	static private $track_instance = null;
	private $track_table='cl2_tracking_feeds';
	
	private function __construct(){}
	
	static public function getInstance(){
		if (!self::$track_instance) {
			self::$track_instance = new Tracking();
		}
		return self::$track_instance;
	}
	public function trackFeedUrl($feed_id,$url){
		$q="INSERT INTO ".$this->track_table." (feed_id,url) VALUES (?,?);";    	
	    if ($stmt=db::getInstance()->prepare($q)){
	    	$stmt->bind_param("is", $feed_id ,$url);
	    	$stmt->execute();	
		    $stmt->close();
	    	return true;
	    }
	    return false;
		
	}
}
?>
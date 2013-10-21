<?

	class Functionor {
		
		private $key = "C74DDF38-0B11-49b0-B199-2E2A11D1CC13";
		
		public function baseID($resourceid){
			$rid = $resourceid;
			$version = 0;
			WHILE ($rid > 16777216){
				$version++;
				if ($version == 1){
					//the constant applied to all items
					$rid -= 1342177280;
				}elseif ($version == 2){
					//the value added to the first updated version
					$rid -= 50331648;
				}else{
					//the value added on all subsequent versions
					$rid -= 16777216;
				}
			}				
			return $rid;
		}
		
		public function playerinfo($baseID){
                	$playerurl = "http://cdn.content.easports.com/fifa/fltOnlineAssets/".$this->key."/2014/fut/items/web/". $baseID .".json";
                	$EAPLAYER = file_get_contents($playerurl, false);
                	return $EAPLAYER;
        	}
        	
        	public function clubimage($id){
                	$pic = "http://cdn.content.easports.com/fifa/fltOnlineAssets/".$this->key."/2014/fut/items/images/clubbadges/web/s".$id.".png";
                	return $pic;
        	}
		
	}

?>
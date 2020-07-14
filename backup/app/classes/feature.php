<?php
class feature
{
	private static function setup_db() {
		$db = new db();
		$sql= "CREATE TABLE `features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `image` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `date` date DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;";
		$db->query( $sql );
	}
	
	private static function insert($feature) {
		$db = new db();
		$sql = "INSERT into features values(" . implode($feature) . ")";
		$db->query( $sql );
	}
	
	private static function update($feature) {
		$db = new db();
		$sql = 
			"UPDATE features set 
						title = '$feature->title',
						active = '$feature->active',
						content = '$feature->content',
						image = '$feature->image',
						date = '$feature->date',
						timestamp = '$feature->timestamp'
					WHERE id = $feature->id;
			";
		$db->query( $sql );
	}
	
	public function latest() {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM features WHERE active = 1 ORDER BY sort_order LIMIT 4";
		$features = $db->query( $sql );
			while( $row = $db->fetch($features) ) {
			$feature = new stdClass();
				$feature->id = $row['id'];
				$feature->title = $row['title'];
				$feature->active = $row['active'];
				$feature->content = $row['content'];
				$feature->image = $row['image'];
				$feature->link = $row['link'];
				$results[] = $feature;
			}
			
		return $results;
	}
	
	public function get($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM features WHERE id = $id";
		$features = $db->query( $sql );
			while( $row = $db->fetch($features) ) {
			$feature = new stdClass();
				$feature->id = $row['id'];
				$feature->title = $row['title'];
				$feature->active = $row['active'];
				$feature->content = $row['content'];
				$feature->image = $row['image'];
				$feature->link = $row['link'];
				$results[] = $feature;
			}
			
		return $results;
	}
	
}
?>
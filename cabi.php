<?php

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }

class Cabi
{

	// XML path declaration
	public $xmlorigin = 'http://www.capitalbikeshare.com/data/stations/bikeStations.xml';
	public $xmlpath = 'src.xml';

	// Parsed XML declaration (populated via __construct)
	public $xml = null;

	// Array of all stations (populated via __construct)
	public $stations = null;

	function __construct($xmlpath = '') {
		// if no explicitly defined XML path, then use default
		if (!empty($xmlpath)) {
			$this->xmlpath = $xmlpath;
		}

		// determine the time since the last XML update
		$diff = false;
		if (file_exists($this->xmlpath)) {
			$diff = time() - filemtime($this->xmlpath);
		}

		// if there is no file, or it's been more than 5 minutes since last update, refresh the XML
		if (!$diff || $diff > 60*5) {
			$this->fetch_latest_xml($this->xmlorigin,$this->xmlpath);
		}
		// parse the XML from given path
		$this->xml = $this->get_parsed_xml($this->xmlpath);
		$this->stations = $this->get_stations_rekeyed();
	}

	private function fetch_latest_xml($url, $path) {
		$newfname = $path;
		$file = fopen ($url, "rb");
		if ($file) {
			$newf = fopen ($newfname, "wb");

			if ($newf)
			while(!feof($file)) {
				fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
			}
		}

		if ($file) {
			fclose($file);
		}

		if ($newf) {
			fclose($newf);
		}
	}
	
	// return parsed Capital Bikeshare XML via SimpleXML, using path
	public function get_parsed_xml($path) {
		if (file_exists($path)) {
			$xml = simplexml_load_file($path);
			return $xml;
		} else {
			throw new Exception('XML file does not exist');
		}
	}

	// stations are now keyed according to their assigned CaBi ID
	public function get_stations_rekeyed() {
		$stations_arr = array();
		foreach ($this->xml->station as $station) {
			$stations_arr[(int) $station->id[0]] = $station;
		}
		return $stations_arr;
	}

	// parse the data version from the Capital Bikeshare header
	public function get_data_version() {
		if ($ver = $this->xml->attributes()['version'])
			return $ver;
		else
			return false;
	}

	// get the number of stations
	public function get_num_stations() {
		$num = count($this->stations);
		return $num;
	}

	// get stations, optionally sorted by a parameter
	public function get_stations($orderby='',$idsOnly=false) {
		$stations_arr = $this->stations;
		// perform uasort if necessary
		switch ($orderby) {
			case 'bikes':
				uasort($stations_arr,"sort_by_most_bikes_available");
				break;
			case 'docks':
				uasort($stations_arr,"sort_by_most_docks_available");
				break;
			default:
				break;
		}
		if ($idsOnly) {
			return array_keys($stations_arr);
		} else {
			return $stations_arr;
		}
	}

	// get a station array based on ID
	public function get_station($id) {
		return $this->stations[$id];
	}

	// get number of available bikes at a station, based on ID
	public function get_bikes($id) {
		if ($bikes = (int) $this->stations[$id]->nbBikes[0])
			return $bikes;
		else
			return false;
	}

	// get number of available docks at a station, based on ID
	public function get_docks($id) {
		if ($docks = (int) $this->stations[$id]->nbEmptyDocks[0])
			return $docks;
		else
			return false;
	}

	public function get_station_name($id) {
		if ($name = (string) $this->stations[$id]->name[0])
			return $name;
		else
			return false;
	}

	public function get_station_lat_long($id) {
		$lat = (float) $this->stations[$id]->lat[0];
		$long = (float) $this->stations[$id]->long[0];
		if ($lat && $long)
			return array($lat, $long);
		else
			return false;
	}

	public function get_distance_to_station($id, $rel_lat, $rel_long, $round=2) {
		$station_lat_long = $this->get_station_lat_long($id);
		if ($station_lat_long && $rel_lat && $rel_long) {
			return number_format(round(distance($station_lat_long[0], $station_lat_long[1], $rel_lat, $rel_long), $round),$round);
		}
	}

	public function get_closest_stations($rel_lat, $rel_long, $num=5, $round=2) {
		$stations_tmp = array();
		foreach ($this->stations as $id=>$station) {
			$station['distance'] = $this->get_distance_to_station($id, $rel_lat, $rel_long, $round);
			$stations_tmp[] = $station;
		}
		uasort($stations_tmp,"sort_by_closest");
		return array_slice($stations_tmp, 0, $num);
	}
	
}

/********************************
** Distance helper functions ****
********************************/

function sort_by_most_bikes_available($a, $b){
	if ((int)$a->nbBikes == (int)$b->nbBikes) {
		return 0;
	}
	return ((int)$a->nbBikes > (int)$b->nbBikes) ? -1 : 1;
}

function sort_by_most_docks_available($a, $b){
	if ((int)$a->nbEmptyDocks == (int)$b->nbEmptyDocks) {
		return 0;
	}
	return ((int)$a->nbEmptyDocks > (int)$b->nbEmptyDocks) ? -1 : 1;
}

function sort_by_closest($a, $b){
	if ((float)$a->attributes()['distance'] == (float)$b->attributes()['distance']) {
		return 0;
	}
	return ((float)$a->attributes()['distance'] < (float)$b->attributes()['distance']) ? -1 : 1;
}

// Calculates distance between two latitude/longitude points
function distance($lat1, $lon1, $lat2, $lon2, $unit="M") { 
	$theta = $lon1 - $lon2; 
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
	$dist = acos($dist); 
	$dist = rad2deg($dist); 
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);

	if ($unit == "K") {
		return ($miles * 1.609344); 
	} else {
		return $miles;
	}
}

?>
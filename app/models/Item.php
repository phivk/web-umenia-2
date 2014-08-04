<?php

class Item extends Eloquent {

    const ARTWORKS_DIR = '/images/diela/';

	protected $fillable = array(
		'id',
		'identifier',
		'author',
		'title',
		'description',
		'work_type',
		'work_level',
		'topic',
		'subject',
		'measurement',
		'dating',
		'date_earliest',
		'date_latest',
		'medium',
		'technique',
		'inscription',
		'place',
		'lat',
		'lng',
		'state_edition',
		'integrity',
		'integrity_work',
		'gallery',
		'img_url',
		'iipimg_url',
		'item_type',
	);

	public static $rules = array(
		'author' => 'required',
		'title' => 'required',
		'dating' => 'required',
		);

    // protected $appends = array('measurements');

	public $incrementing = false;

	protected $guarded = array('featured');


	public function collections()
    {
        return $this->belongsToMany('Collection', 'collection_item', 'item_id', 'collection_id');
    }

	public function getImagePath($full=false) {

		$levels = 1;
	    $dirsPerLevel = 100;

	    $transformedWorkArtID = $this->hashcode((string)$this->id);
		$workArtIdInt = abs($this->intval32bits($transformedWorkArtID));
	    $tmpValue = $workArtIdInt;
	    $dirsInLevels = array();

	    $galleryDir = substr($this->id, 4, 3);

	    for ($i = 0; $i < $levels; $i++) {
	            $dirsInLevels[$i] = $tmpValue % $dirsPerLevel;
	            $tmpValue = $tmpValue / $dirsPerLevel;
	    }

	    $path = implode("/", $dirsInLevels);

		// adresar obrazkov workartu sa bude volat presne ako id, kde je ':' nahradena '_'
		$trans = array(":" => "_", " " => "_");
	    $file = strtr($this->id, $trans);

	    $relative_path = self::ARTWORKS_DIR . "$galleryDir/$path/$file/";
	    $full_path =  public_path() . $relative_path;

	    // ak priecinky este neexistuju - vytvor ich
	    if ($full && !file_exists($full_path)) {
	    	mkdir($full_path, 0777, true);
	    }

	    // dd($full_path . "$file.jpeg");
	    if ($full) {
	    	$result_path = $full_path . "$file.jpeg";
	    } else {
		    if (file_exists($full_path . "$file.jpeg")) {
		    	$result_path =  $relative_path . "$file.jpeg";
		    } else {
		    	$result_path =  self::ARTWORKS_DIR . "no-image.jpg";
		    }
	    }

		return $result_path;
	}

	public function getDetailUrl() {
		return URL::to('dielo/' . $this->id);
	}

	private function intval32bits($value)
	{
	    $value = ($value & 0xFFFFFFFF);

	    if ($value & 0x80000000)
	        $value = -((~$value & 0xFFFFFFFF) + 1);

	    return $value;
	}

	/*
	public function getAuthorAttribute($value)
	{
		$authors = $this->authors;
		return implode(', ', $authors);
	}
	*/

	public function getAuthorsAttribute($value)
	{
		$authors_array = $this->makeArray($this->attributes['author']);
		$authors = array();
		foreach ($authors_array as $author) {
			$authors[] = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $author);
		}

		return $authors;
	}

	public function getSubjectsAttribute($value)
	{
		$subjects_array = $this->makeArray($this->attributes['subject']);
		return $subjects_array;
	}

	public function getMeasurementsAttribute($value)
	{
		$measurements_array = explode(';', $this->attributes['measurement']);
		$measurements = array();
		$measurements[0] = array();
		$i = -1;
		if (!empty($this->attributes['measurement'])) {
			foreach ($measurements_array as $key=>$measurement) {
				if ($key%2 == 0) {
					$i++;
					$measurements[$i] = array();
				}
				if (!empty($measurement)) {				
					$measurement = explode(' ', $measurement, 2);
					$measurements[$i][$measurement[0]] = $measurement[1];
				}
			}			
		}
		return $measurements;
	}

	public function getDatingFormated() {
		$trans = array("/" => "&ndash;");
		return strtr($this->attributes['dating'], $trans);
	}


	/**
	* Same as java String.hashcode()
	*/
	private function hashcode($s) {
	    $len = strLen($s);
	    $sum = 0;
	    for ($i = 0; $i < $len; $i++) {
	        $char = ord($s[$i]);
	        $sum = (($sum<<5)-$sum)+$char;
	        $sum = $sum & 0xffffffff; // Convert to 32bit integer
	    }

	    return $sum;
	}

	public function setLat($value)
	{
	    $this->attributes['lat'] = $value ?: null;
	}

	public function setLng($value)
	{
	    $this->attributes['lng'] = $value ?: null;
	}

	public function makeArray($str) {
		return explode('; ', $str);
	}

	public static function listValues($attribute, $delimiter = ';', $only_first = false)
	{
		//najskor over, ci $attribute je zo zoznamu povolenych 
		if (!in_array($attribute, array('author', 'work_type', 'subject'))) return false;

		$unformated_list = Item::select(DB::raw($attribute . ', count(*) AS pocet'))
		->groupBy($attribute)
		->orderBy('pocet', 'desc')
		->whereNotNull($attribute)
		->where($attribute, '!=', '')
		->remember(30)
		->get();

		$formated_list=array();
		foreach ($unformated_list as $result) {
			$values = explode($delimiter, $result->$attribute);
			if ($only_first) {
				$single_value = trim($values[0]);
				if (!isSet($formated_list[$single_value])) $formated_list[$single_value] = 0;
				$formated_list[$single_value] += $result->pocet;
			} else {
				foreach ($values as $single_value) {
					$single_value = trim($single_value);					
					if (!isSet($formated_list[$single_value])) $formated_list[$single_value] = 0;
					$formated_list[$single_value] += $result->pocet;
				}
			}
		}
		arsort($formated_list);

		$return_list = array();
		foreach ($formated_list as $key => $value) {
			$single_value = $key;
			if ($attribute=='author') $single_value = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $key);
			$return_list[$key] = "$single_value ($value)";
		}

		return $return_list;

	}


}
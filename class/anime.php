<?php
class Anime
{
	// Attributs
	private $_id;
	private $_name;
    private $_shortname;
	private $_banner;
    private $_valid;


	// Hydratation
	public function __construct($data) {
        if($data != false)
		  $this->hydrate($data);
	}

	public function hydrate(array $data) {
		foreach ($data as $key => $value) {
			$method = 'set'.ucfirst(substr($key,2));
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
		}
		unset($value);
	}


	// Getters
	public function id() {return $this->_id;}
	public function name() {return $this->_name;}
	public function shortname() {return $this->_shortname;}
	public function banner() {return $this->_banner;}
	public function valid() {return $this->_valid;}


	// Setters
	public function setId($id) {
		$id = (int)$id;
		if ($id > 0) {
			$this->_id = $id;
		}
	}
    
	public function setName($name) {
		if(is_string($name)){
			$this->_name = $name;
		}
	} 
    
	public function setShortName($name) {
		if(is_string($name)){
			$this->_shortname = $name;
		}
	}

        
	public function setBanner($banner) {
		if(is_string($banner)){
			$this->_banner = $banner;
		}
	}
    
    public function setValid($v) {
		if($v >= 0 && $v <= 1){
			$this->_valid = $v;
		}
	}
}


class AnimeManager
{
	// Initialisation
	private $_db;
	public function __construct($db) {
		$this->setDb($db);
	}

	// Méthodes
	public function add(Anime $anime) {
		$q = $this->_db->prepare("INSERT INTO anime(a_name, a_shortname, a_banner) VALUES(:name, :shortname, :banner)");
		$q->bindValue(':name', $anime->name());
		$q->bindValue(':shortname', $anime->shortname());
		$q->bindValue(':banner', $anime->banner());
		$q->execute();
	}


	// Supprimer un membre
	public function delete(Anime $anime) {
		$this->_db->exec('DELETE FROM `anime` WHERE `a_id` = '.$anime->id());
	}


	// Récupérer les informations d'un membre par son ID
	public function getById($id) {
		$id = (int)$id;
		$q = $this->_db->query('SELECT * FROM `anime` WHERE `a_id` = '.$id);
		$data = $q->fetch(PDO::FETCH_ASSOC);
		return new Anime($data);
	}

	// Récupérer les informations d'un membre par son pseudo
	public function getByName($name) {
		$q = $this->_db->query('SELECT * FROM `anime` WHERE `a_name` = "'.$name.'"');
		$data = $q->fetch(PDO::FETCH_ASSOC);
		return new Anime($data);
	}
    
    public function getByGender($gender) {
        $animes = [];
        $q = $this->_db->query('SELECT * FROM anime WHERE a_id IN (SELECT a_id FROM anime_gender WHERE g_id = '. $gender .') ORDER BY a_name');
        while($data = $q->fetch(PDO::FETCH_ASSOC)) {
            $animes[] = new Anime($data);
        }
        return $animes;
    }


	// Récupérer une liste des membres triées par ID
	public function getList() {
		$members = [];
		$q = $this->_db->query('SELECT * FROM `anime` ORDER BY `a_name`');
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
	    	$members[] = new Anime($data);
		}
    	return $members;
	}

	public function getUnvalidList() {
		$animes = [];
		$q = $this->_db->query('SELECT * FROM anime WHERE a_valid = 0');
		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$animes[] = new Anime($data);
		}

		return $animes;
	}
    
    public function getListLimit($min, $max) {
		$members = [];
		$q = $this->_db->query('SELECT * FROM `anime` ORDER BY `a_name` LIMIT '. $min .', '. $max);
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
	    	$members[] = new Anime($data);
		}
    	return $members;
	}

	// Mis à jours d'un membre
	public function update(Anime $anime) {
		$q = $this->_db->prepare('UPDATE `anime` SET `a_id`=?,`a_name`=?, `a_shortname`=?, `a_banner`=?, `a_valid`=? WHERE `a_id`=?');
		$q->bindValue('1', $anime->id(), PDO::PARAM_INT);
		$q->bindValue('2', $anime->name());
		$q->bindValue('3', $anime->shortname());
		$q->bindValue('4', $anime->banner());
		$q->bindValue('5', $anime->valid());
		$q->bindValue('6', $anime->id(), PDO::PARAM_INT);
		$q->execute();
	}
    
    public function approve(Anime $anime){
        $q = $this->_db->prepare('UPDATE anime SET a_valid = 1 WHERE a_id = :id');
        $q->bindValue(':id', $anime->id(), PDO::PARAM_INT);
		$q->execute();
    }
    
    public function moyenne(Anime $anime, $gid){
        $q = $this->_db->prepare("SELECT AVG(n_note) AS moyenne FROM note WHERE n_aid = :aid AND n_gid = :gid");
        $q->bindValue(':aid', $anime->id());
        $q->bindValue(':gid', $gid);
        $q->execute();
        $moy = $q->fetch();
        
        return $moy['moyenne'];
    }
    
    public function moyenneGlobal(Anime $anime){
        $q = $this->_db->prepare("SELECT AVG(n_note) AS moyenne FROM note WHERE n_aid = :aid");
        $q->bindValue(':aid', $anime->id());
        $q->execute();
        $moy = $q->fetch();
        
        return $moy['moyenne'];
    }
    
    public function getTier(Anime $anime, $gid){
        $q = $this->_db->prepare("SELECT AVG(n_note) AS moyenne FROM note WHERE n_aid = :aid AND n_gid = :gid");
        $q->bindValue(':aid', $anime->id());
        $q->bindValue(':gid', $gid);
        $q->execute();
        $moy = $q->fetch();
		$m = floor($moy['moyenne']);
		
        
        if($m <= 20 && $m >= 17){
            return 'SS';
        }
        if($m <= 16 && $m >= 14){
            return 'S';
        }
        if($m <= 13 && $m >= 11){
            return 'A';
        }
        if($m <= 10 && $m >= 8){
            return 'B';
        }
        if($m <= 7 && $m >= 5){
            return 'C';
        }
        if($m <= 4 && $m >= 1){
            return 'D';
        }
    }


	// Changer la base de donnée
	public function setDb(PDO $db) {
		$this->_db = $db;
	}
}
?>
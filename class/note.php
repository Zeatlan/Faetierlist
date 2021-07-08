<?php
class Note
{
	// Attributs
	private $_id;
	private $_aid;
	private $_gid;
	private $_uid;
	private $_note;
    private $_date;

	// Hydratation
	public function __construct($data) {
		$this->hydrate($data);
	}

	public function hydrate(array $data) {
		foreach ($data as $key => $value) {
			$method = 'set'.ucfirst(substr($key, 2));
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
		}
		unset($value);
	}


	// Getters
	public function id() {return $this->_id;}
	public function aid() {return $this->_aid;}
	public function gid() {return $this->_gid;}
	public function uid() {return $this->_uid;}
	public function note() {return $this->_note;}
	public function datetime() {return $this->_date;}


	// Setters
	public function setId($id) {
		$id = (int)$id;
		if ($id > 0) {
			$this->_id = $id;
		}
	}
    
    public function setAid($aid) {
        $aid = (int)$aid;
        if($aid > 0) {
            $this->_aid = $aid;
        }
    }
    
    public function setGid($gid) {
        $gid = (int)$gid;
        if($gid > 0) {
            $this->_gid = $gid;
        }
    }
    
    public function setUid($uid) {
        $uid = (int)$uid;
        if($uid > 0) {
            $this->_uid = $uid;
        }
    }    
    
    public function setNote($note) {
        $note = (int)$note;
        if($note >= 0 && $note <= 20) {
            $this->_note = $note;
        }
    }
    
    public function setDate($date) {
        $date = (int)$date;
        if($date > 0) {
            $this->_date = $date;
        }
    }


}


class NoteManager
{
	// Initialisation
	private $_db;
	public function __construct($db) {
		$this->setDb($db);
	}

	// Méthodes
    // Ajouter une note
	public function add(Note $note) {
		$q = $this->_db->prepare("INSERT INTO note(n_aid, n_gid, n_uid, n_note, n_date) VALUES(:aid, :gid, :uid, :note, :date)");
		$q->bindValue(':aid', $note->aid());
		$q->bindValue(':gid', $note->gid());
		$q->bindValue(':uid', $note->uid());
		$q->bindValue(':note', $note->note());
		$q->bindValue(':date', $note->datetime());
		$q->execute();
	}


	// Supprimer une note
	public function delete(Note $note) {
		$this->_db->exec('DELETE FROM `note` WHERE `n_id` = '.$note->id());
	}


	// Récupérer les informations d'une note par son ID
	public function getById($id) {
		$id = (int)$id;
		$q = $this->_db->query('SELECT `n_id`, `n_aid`, `n_gid`, `n_uid`, `n_note`, `n_date` FROM `note` WHERE `n_id` = '.$id);
		$data = $q->fetch(PDO::FETCH_ASSOC);
		return new Note($data);
	}


	// Récupérer une liste des notes triées par ID
	public function getList() {
		$members = [];
		$q = $this->_db->query('SELECT `n_id`, `n_aid`, `n_gid`, `n_uid`, `n_note`, `n_date` FROM `note` ORDER BY `n_id`');
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
	    	$members[] = new Note($data);
		}
    	return $members;
	}
    
    // Récupérer une liste des notes groupée par gid
    public function getByGid($gid){
        $notes = [];
        $q = $this->_db->query('SELECT `n_id`, `n_aid`, `n_gid`, `n_uid`, `n_note`, `n_date` FROM `note` WHERE `n_gid`='. $gid);
        while($data = $q->fetch(PDO::FETCH_ASSOC)) {
            $notes[] = new Note($data);
        }
        return $notes;
    }    
    
    // Récupérer une liste des notes groupée par aid
    public function getByAid($aid){
        $notes = [];
        $q = $this->_db->query('SELECT `n_id`, `n_aid`, `n_gid`, `n_uid`, `n_note`, `n_date` FROM `note` WHERE `n_aid`='. $aid);
        while($data = $q->fetch(PDO::FETCH_ASSOC)) {
            $notes[] = new Note($data);
        }
        return $notes;
    }   
    
    // Récupérer une liste des notes groupée par UID
    public function getByUid($uid){
        $notes = [];
        $q = $this->_db->query('SELECT `n_id`, `n_aid`, `n_gid`, `n_uid`, `n_note`, `n_date` FROM `note` WHERE `n_uid`='. $uid);
        while($data = $q->fetch(PDO::FETCH_ASSOC)) {
            $notes[] = new Note($data);
        }
        return $notes;
    }
    
    // Récupérer une liste des notes groupée par sa date
    public function getByDate(){
        $notes = [];
        $q = $this->_db->query('SELECT `n_id`, `n_aid`, `n_gid`, `n_uid`, `n_note`, `n_date` FROM `note` ORDER BY n_date DESC');
        while($data = $q->fetch(PDO::FETCH_ASSOC)) {
            $notes[] = new Note($data);
        }
        return $notes;
    }
    
    public function calculatePerTier($tierlist, $min, $max, $prohibited){
        $q = $this->_db->prepare("SELECT avg(n_note), n_aid FROM note WHERE n_gid = ? GROUP BY n_aid HAVING floor(avg(n_note)) >= ? AND floor(avg(n_note)) <= ?");
        $q->bindValue('1', $tierlist);
        $q->bindValue('2', $min);
        $q->bindValue('3', $max);
        $q->execute();
    
        $fe = $q->fetch();
        
        $data = $q->rowCount();
        if(!in_array($fe['n_aid'], $prohibited)){
            return $data;
        }else{
            return 0;
        }
        
    }
    
        
    public function lastVote(Anime $anime){
        $votes = [];
        $q = $this->_db->prepare("SELECT * FROM note WHERE n_aid = :aid ORDER BY n_date DESC LIMIT 5");
        $q->bindValue(':aid', $anime->id(), PDO::PARAM_INT);
        $q->execute();
        while($data = $q->fetch()){
            $votes[] = new Note($data);
        }
        return $votes;
    }

	// Mis à jours d'une note
	public function update(Note $note) {
		$q = $this->_db->prepare('UPDATE `note` SET `n_id`=?,`n_aid`=?,`n_gid`=?,`n_uid`=?,`n_note`=?,`n_date`=? WHERE `n_id`=?');
		$q->bindValue(1, $note->id(), PDO::PARAM_INT);
		$q->bindValue(2, $note->aid(), PDO::PARAM_INT);
		$q->bindValue(3, $note->gid(), PDO::PARAM_INT);
		$q->bindValue(4, $note->uid(), PDO::PARAM_INT);
		$q->bindValue(5, $note->note(), PDO::PARAM_INT);
		$q->bindValue(6, $note->datetime(), PDO::PARAM_INT);
		$q->bindValue(7, $note->id(), PDO::PARAM_INT);
		$q->execute();
	}

	// Changer la base de donnée
	public function setDb(PDO $db) {
		$this->_db = $db;
	}
}
?>
<?php
class Restriction
{
	// Attributs
	private $_uid;
    private $_tid;


	// Hydratation
	public function __construct($data) {
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
	public function uid() {return $this->_uid;}
	public function tid() {return $this->_tid;}

	// Setters
    
	public function setUid($uid) {
		if($uid > 0){
			$this->_uid = $uid;
		}
	}
    
    public function setTid($tid) {
		if($tid > 0){
			$this->_tid = $tid;
		}
	}

}


class RestrictionManager
{
	// Initialisation
	private $_db;
	public function __construct($db) {
		$this->setDb($db);
	}

	// Méthodes
	public function add(Restriction $restrict) {
		$q = $this->_db->prepare("INSERT INTO restriction(r_uid, r_tid) VALUES(:uid, :tid)");
		$q->bindValue(':uid', $restrict->uid());
		$q->bindValue(':tid', $restrict->tid());
		$q->execute();
	}


	// Supprimer un membre
	public function delete(Restriction $restrict) {
		$this->_db->exec('DELETE FROM `restriction` WHERE `r_uid` = '. $restrict->uid().' AND r_tid = '. $restrict->tid());
	}


	// Récupérer une liste des membres triées par ID
	public function getList() {
		$members = [];
		$q = $this->_db->query('SELECT * FROM `restriction` ORDER BY `r_uid`');
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
	    	$members[] = new Restriction($data);
		}
    	return $members;
    }

	// Changer la base de donnée
	public function setDb(PDO $db) {
		$this->_db = $db;
	}
}
?>
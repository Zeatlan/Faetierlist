<?php
class Tierlist
{
	// Attributs
	private $_id;
	private $_name;
	private $_nbvote;
	private $_tag;


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
	public function name() {return $this->_name;}
	public function nbvote() {return $this->_nbvote;}
	public function tag() {return $this->_tag;}


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
    
	public function setNbvote($nbvote) {
        $nbvote = (int)$nbvote;
		$this->_nbvote = $nbvote;
	}
    
    public function setTag($tag) {
        if(is_string($tag)) {
            $this->_tag = $tag;
        }
    }

}


class TierlistManager
{
	// Initialisation
	private $_db;
	public function __construct($db) {
		$this->setDb($db);
	}

	// Méthodes
	public function add(Tierlist $tierlist) {
		$q = $this->_db->prepare("INSERT INTO tierlist(t_name, t_nbvote, t_tag) VALUES(:name, :nbvote, :tag)");
		$q->bindValue(':name', $tierlist->name());
		$q->bindValue(':nbvote', $tierlist->nbvote());
		$q->bindValue(':tag', $tierlist->tag());
		$q->execute();
	}


	// Supprimer un membre
	public function delete(Tierlist $tierlist) {
		$this->_db->exec('DELETE FROM `tierlist` WHERE `t_id` = '.$tierlist->id());
	}


	// Récupérer les informations d'un membre par son ID
	public function getById($id) {
		$id = (int)$id;
		$q = $this->_db->query('SELECT `t_id`, `t_name`, `t_nbvote`, `t_tag` FROM `tierlist` WHERE `t_id` = '.$id);
		$data = $q->fetch(PDO::FETCH_ASSOC);
		return new Tierlist($data);
	}

	// Récupérer les informations d'un membre par son pseudo
	public function getByName($name) {
		$q = $this->_db->query('SELECT `t_id`, `t_name`, `t_nbvote`, `t_tag` FROM `tierlist` WHERE `t_name` = "'.$name.'"');
		$data = $q->fetch(PDO::FETCH_ASSOC);
		return new Tierlist($data);
	}


	// Récupérer une liste des membres triées par ID
	public function getList() {
		$members = [];
		$q = $this->_db->query('SELECT `t_id`, `t_name`, `t_nbvote`, `t_tag` FROM `tierlist` ORDER BY `t_id`');
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
	    	$members[] = new Tierlist($data);
		}
    	return $members;
	}
    
	// Mis à jours d'un membre
	public function update(Tierlist $tierlist) {
		$q = $this->_db->prepare('UPDATE `tierlist` SET `t_id`=?,`t_name`=?,`t_nbvote`=?,`t_tag`=? WHERE `t_id`=?');
		$q->bindValue('1', $tierlist->id(), PDO::PARAM_INT);
		$q->bindValue('2', $tierlist->name());
		$q->bindValue('3', $tierlist->nbvote());
		$q->bindValue('4', $tierlist->tag());
		$q->bindValue('5', $tierlist->id(), PDO::PARAM_INT);
		$q->execute();
	}

	// Changer la base de donnée
	public function setDb(PDO $db) {
		$this->_db = $db;
	}
}
?>
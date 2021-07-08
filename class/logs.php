<?php
class Logs
{
	// Attributs
	private $_pseudo;
	private $_timestamp;
	private $_ipaddress;


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
	public function pseudo() {return $this->_pseudo;}
	public function timestamp() {return $this->_timestamp;}
	public function ip() {return $this->_ipaddress;}

	// Setters
    
	public function setPseudo($pseudo) {
		if(is_string($pseudo)){
			$this->_pseudo = $pseudo;
		}
	}
    
	public function setTimestamp($timestamp) {
		$this->_timestamp = $timestamp;
	}
    
    public function setIpaddress($ip) {
        if(is_string($ip)) {
            $this->_ipaddress = $ip;
        }
    }

}


class LogsManager
{
	// Initialisation
	private $_db;
	public function __construct($db) {
		$this->setDb($db);
	}

	// Méthodes
	public function add(Logs $log) {
		$q = $this->_db->prepare("INSERT INTO logs(l_pseudo, l_timestamp, l_ipaddress) VALUES(:pseudo, :timestamp, :ip)");
		$q->bindValue(':pseudo', $log->pseudo());
		$q->bindValue(':timestamp', $log->timestamp());
		$q->bindValue(':ip', $log->ip());
		$q->execute();
	}


	// Supprimer un membre
	public function delete(Logs $log) {
		$this->_db->exec('DELETE FROM `logs` WHERE `l_pseudo` = "'. $log->pseudo().'"');
	}


	// Récupérer une liste des membres triées par ID
	public function getList() {
		$members = [];
		$q = $this->_db->query('SELECT `l_pseudo`, `l_timestamp`, `l_ipaddress` FROM `logs` ORDER BY `l_timestamp` DESC');
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
	    	$members[] = new Logs($data);
		}
    	return $members;
	}

	// Changer la base de donnée
	public function setDb(PDO $db) {
		$this->_db = $db;
	}
}
?>
<?php
class Member
{
	// Attributs
	private $_id;
	private $_pseudo;
	private $_password;
	private $_discord;
    private $_joinedtime;
    private $_canVote;
    private $_admin;
    private $_avatar;
	private $_banner;
	private $_contribution;
	private $_maxVote;


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
	public function pseudo() {return $this->_pseudo;}
	public function password() {return $this->_password;}
	public function discord() {return $this->_discord;}
	public function joinedtime() {return $this->_joinedtime;}
	public function admin() {return $this->_admin;}
	public function canVote() {return $this->_canVote;}
	public function avatar() {return $this->_avatar;}
	public function banner() {return $this->_banner;}
	public function contribution() {return $this->_contribution;}
	public function maxVote() {return $this->_maxVote;}


	// Setters
	public function setId($id) {
		$id = (int)$id;
		if ($id > 0) {
			$this->_id = $id;
		}
	}
    
	public function setPseudo($pseudo) {
		if(is_string($pseudo)){
			$this->_pseudo = $pseudo;
		}
	}
    
	public function setPassword($mdp) {
		$this->_password = $mdp;
	}
    
	public function setDiscord($discord) {
		$d = explode("#", $discord);
        // $d[0] = pseudo discord;
        // $d[1] = tag (0727) discord
        if(count($d) == 2){
            $this->_discord = $d[0] ."#". $d[1];
        }
	}
    
    public function setJoinedtime($j) {
        $j = (int)$j;
        if($j > 0) {
            $this->_joinedtime = $j;
        }
    }
    
    
    public function setAdmin($a) {
        $a = (int)$a;
        if($a >= 0) {
            $this->_admin = $a;
        }
    }
    
	public function setAvatar($avatar) {
		if(is_string($avatar)){
			$this->_avatar = $avatar;
		}
	}
    
    public function setBanner($banner) {
		if(is_string($banner)){
			$this->_banner = $banner;
		}
	}
    
    public function setCanVote($vote) {
		if($vote == 1 || $vote == 0 || $vote == -1){
			$this->_canVote = $vote;
		}
	}

	public function setContribution($n) {
		if($n > 0){
			$this->_contribution = intval($n);
		}
	}

	public function setMaxVote($m) {
		if($m > 0){
			$this->_maxVote = intval($m);
		}
	}

}


class MemberManager
{
	// Initialisation
	private $_db;
	public function __construct($db) {
		$this->setDb($db);
	}

	// Méthodes
	public function add(Member $member) {
		$q = $this->_db->prepare("INSERT INTO membre(u_pseudo, u_password, u_discord, u_joinedtime) VALUES(:pseudo, :pass, :discord, :joined)");
		$q->bindValue(':pseudo', $member->pseudo());
		$q->bindValue(':pass', $member->password());
		$q->bindValue(':discord', $member->discord());
		$q->bindValue(':joined', $member->joinedtime());
		$q->execute();
	}


	// Supprimer un membre
	public function delete(Member $member) {
		$this->_db->exec('DELETE FROM `membre` WHERE `u_id` = '.$member->id());
	}


	// Récupérer les informations d'un membre par son ID
	public function getById($id) {
		$id = (int)$id;
		$q = $this->_db->query('SELECT * FROM `membre` WHERE `u_id` = '.$id);
		$data = $q->fetch(PDO::FETCH_ASSOC);
		return new Member($data);
	}

	// Récupérer les informations d'un membre par son pseudo
	public function getByPseudo($pseudo) {
		$q = $this->_db->query('SELECT * FROM `membre` WHERE `u_pseudo` = "'.$pseudo.'"');
		$data = $q->fetch(PDO::FETCH_ASSOC);
		return new Member($data);
	}


	// Récupérer une liste des membres triées par ID
	public function getList() {
		$members = [];
		$q = $this->_db->query('SELECT * FROM `membre` ORDER BY `u_id`');
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
	    	$members[] = new Member($data);
		}
    	return $members;
	}

	// Mis à jours d'un membre
	public function update(Member $member) {
		$q = $this->_db->prepare('UPDATE `membre` SET `u_id`=?,`u_pseudo`=?,`u_password`=?,`u_discord`=?,`u_joinedtime`=?,`u_canVote`=?,`u_admin`=?, `u_avatar`=?,`u_banner`=? WHERE `u_id`=?');
		$q->bindValue('1', $member->id(), PDO::PARAM_INT);
		$q->bindValue('2', $member->pseudo());
		$q->bindValue('3', $member->password());
		$q->bindValue('4', $member->discord());
		$q->bindValue('5', $member->joinedtime(), PDO::PARAM_INT);
		$q->bindValue('6', $member->canVote(), PDO::PARAM_INT);
		$q->bindValue('7', $member->admin());
		$q->bindValue('8', $member->avatar());
		$q->bindValue('9', $member->banner());
		$q->bindValue('10', $member->id(), PDO::PARAM_INT);
		$q->execute();
	}

	public function decreaseMaxVote(Member $member){
		$q = $this->_db->prepare('UPDATE membre SET u_contribution = u_contribution+1, u_maxVote = u_maxVote-1 WHERE u_id = :id');
		$q->bindValue(':id', $member->id(), PDO::PARAM_INT);
		$q->execute();
	}

	public function animeApprovedGain(Member $member){
		$q = $this->_db->prepare('UPDATE membre SET u_contribution = u_contribution+3 WHERE u_id = :id');
		$q->bindValue(':id', $member->id(), PDO::PARAM_INT);
		$q->execute();
	}

	public function classementContribution(Member $member){
		$id = intval($member->id());
		$youladder = $this->_db->prepare("SELECT COUNT(*) as classement FROM membre WHERE u_contribution > (SELECT u_contribution FROM membre WHERE u_id = :id)");
		$youladder->bindParam(':id', $id);
		$youladder->execute();
		$data = $youladder->fetch();
		return $data['classement']+1;
	}

	public function totalVote(Member $member){
		$id = intval($member->id());
		$q = $this->_db->prepare("SELECT COUNT(*) as totalVote FROM note WHERE n_uid = :id");
		$q->bindParam(':id', $id);
		$q->execute();
		$data = $q->fetch();
		return $data['totalVote'];
	}

	public function moyenneVote(Member $member){
		$id = intval($member->id());
		$q = $this->_db->prepare("SELECT AVG(n_note) AS moyenne FROM note WHERE n_uid = :id");
		$q->bindParam(':id', $id);
		$q->execute();
		$data = $q->fetch();
		return number_format($data['moyenne'], 2);
	}
	// Changer la base de donnée
	public function setDb(PDO $db) {
		$this->_db = $db;
	}
}
?>
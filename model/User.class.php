<?php
class User extends Model {
	protected $username;
	protected $googleIdentifier;
	protected $email;
	protected $private;
	protected $existing;

	public function __construct($googleIdentifier = '') {
		if (trim($googleIdentifier) != '') {
			$sql = "SELECT * FROM User WHERE googleIdentifier='?' LIMIT 1";
			$param = array(trim($googleIdentifier));
			$db = new Database();
			$results = $db->query($sql, $param);
			// todo check to make sure something was returned
			$this->setUsername($results[0]['username']);
			$this->setGoogleIdentifier($results[0]['googleIdentifier']);
			$this->setEmail($results[0]['email']);
			$this->setPrivate($results[0]['private']);
			$this->setExisting(true);
		} else {
			$this->setUsername('');
			$this->setGoogleIdentifier('');
			$this->setEmail('');
			$this->setPrivate(true);
			$this->setExisting(false);
		}
	}

	public function save() {
		if ($this->getExisting()) { // update user
			$sql = "UPDATE User SET username='?', email=?, private=? WHERE googleIdentifier='?' LIMIT 1";
			$params = array($this->getUsername(), $this->getEmail(), $this->getPrivate(), $this->getGoogleIdentifier());
			$db = new Database();
			$db->query($sql, $params);
		} else { // new user
			$sql = "INSERT INTO User (username, email, private, googleIdentifier) VALUES ('?', '?', ?, '?')";
			$params = array($this->getUsername(), $this->getEmail(), $this->getPrivate(), $this->getGoogleIdentifier());
			$db = new Database();
			$db->query($sql, $params);
			$this->setExisting(true);
		}
	}

	public function delete() {
		$sql = "DELETE FROM Book WHERE bookId=? LIMIT 1";
		$param = array($this->getBookId());
		$db = new Database();
		$db->query($sql, $param);
		$this->setExisting(false);
	}


	#***************************************************************************
	# Getters and Setters
	#***************************************************************************

	public function getUsername() {
		return $this->username;
	}

	public function setUsername($value) {
		$this->username = trim(strval($value));
	}

	public function getGoogleIdentifier() {
		return $this->googleIdentifier;
	}

	public function setGoogleIdentifier($value) {
		$this->googleIdentifier = trim(strval($value));
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($value) {
		$this->email = trim(strval($value));
	}

	public function getPrivate() {
		return ($this->private) ? 1 : 0;
	}

	public function setPrivate($value) {
		if ($value == true || $value != 0) {
			$this->private = true;
		} else {
			$this->private = false;
		}
	}

	public function getExisting() {
		return $this->existing;
	}

	public function setExisting($value) {
		if ($value == true || $value != 0) {
			$this->existing = true;
		} else {
			$this->existing = false;
		}
	}
}
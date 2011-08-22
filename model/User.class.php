<?php
class User extends Model
{

	/**
	 * also slug
	 * @var string
	 */
	protected $username;

	/**
	 * google identifyer
	 * @var string
	 */
	protected $googleIdentifier;

	/**
	 * 
	 * @var string
	 */
	protected $email;

	/**
	 * 
	 * @var bool
	 */
	protected $private;

	/****************************************************************************//**
	 * Constructor for this class, set all properties to a default value
	 */
	public function __construct()
	{
		$this->setUsername('');
		$this->setGoogleIdentifier('');
		$this->setEmail('');
		$this->setPrivate(1);
	}

	#***************************************************************************
	# Getters and Setters
	#***************************************************************************

	public function getUsername ()
	{
		return($this->username);
	}

	public function setUsername ($value)
	{
		$this->username = $value . '';
	}

	public function getGoogleIdentifier ()
	{
		return($this->googleIdentifier);
	}

	public function setGoogleIdentifier ($value)
	{
		$this->googleIdentifier = $value . '';
	}

	public function getEmail ()
	{
		return($this->email);
	}

	public function setEmail ($value)
	{
		$this->email = $value . '';
	}

	public function getPrivate ()
	{
		if($this->private)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function setPrivate ($value)
	{
		if($value == true || $value != 0)
		{
			$this->private = true;
		}
		else
		{
			$this->private = false;
		}
	}
}

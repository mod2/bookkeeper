<?php
class Book extends Model
{

	/**
	 * 
	 * @var pk
	 */
	protected $bookId;

	/**
	 * 
	 * @var string
	 */
	protected $username;

	/**
	 * 
	 * @var string
	 */
	protected $title;

	/**
	 * 
	 * @var int
	 */
	protected $totalPages;

	/**
	 * 
	 * @var date
	 */
	protected $startDate;

	/**
	 * 
	 * @var date
	 */
	protected $endDate;

	/**
	 * 
	 * @var bool
	 */
	protected $sunday;

	/**
	 * 
	 * @var bool
	 */
	protected $monday;

	/**
	 * 
	 * @var bool
	 */
	protected $tuesday;

	/**
	 * 
	 * @var bool
	 */
	protected $wednesday;

	/**
	 * 
	 * @var bool
	 */
	protected $thursday;

	/**
	 * 
	 * @var bool
	 */
	protected $friday;

	/**
	 * 
	 * @var bool
	 */
	protected $saturday;

	/**
	 * 
	 * @var bool
	 */
	protected $hidden;

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
		$this->setTitle('');
		$this->setTotalPages(0);
		$this->setStartDate(new DateTime());
		$this->setEndDate(new DateTime());
		$this->setSunday(1);
		$this->setMonday(1);
		$this->setTuesday(1);
		$this->setWednesday(1);
		$this->setThursday(1);
		$this->setFriday(1);
		$this->setSaturday(1);
		$this->setHidden(0);
		$this->setPrivate(1);
	}

	#***************************************************************************
	# Getters and Setters
	#***************************************************************************

	public function getBookId ()
	{
		return($this->bookId);
	}

	public function setBookId ($value)
	{
		$this->bookId = intval($value);
	}

	public function getUsername ()
	{
		return($this->username);
	}

	public function setUsername ($value)
	{
		$this->username = $value . '';
	}

	public function getTitle ()
	{
		return($this->title);
	}

	public function setTitle ($value)
	{
		$this->title = $value . '';
	}

	public function getTotalPages ()
	{
		return($this->totalPages);
	}

	public function setTotalPages ($value)
	{
		$this->totalPages = intval($value);
	}

	public function getStartDate ()
	{
		return($this->startDate);
	}

	public function getMYSQLStartDate ()
	{
		return($this->startDate->format('Y-m-d'));
	}

	public function setStartDate ($value)
	{
		if($value != null && !is_a($value, 'DateTime'))
		{
			$type = 'Type exception';
			$msg = 'Invalid DateTime type passed into Book.getStartDate()';
			$exception = new ClassTypeException($type, $msg);
			throw($exception);
		}

		$this->startDate = $value;
	}

	public function getEndDate ()
	{
		return($this->endDate);
	}

	public function getMYSQLEndDate ()
	{
		return($this->endDate->format('Y-m-d'));
	}

	public function setEndDate ($value)
	{
		if($value != null && !is_a($value, 'DateTime'))
		{
			$type = 'Type exception';
			$msg = 'Invalid DateTime type passed into Book.getEndDate()';
			$exception = new ClassTypeException($type, $msg);
			throw($exception);
		}

		$this->endDate = $value;
	}

	public function getSunday ()
	{
		if($this->sunday)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function setSunday ($value)
	{
		if($value == true || $value != 0)
		{
			$this->sunday = true;
		}
		else
		{
			$this->sunday = false;
		}
	}

	public function getMonday ()
	{
		if($this->monday)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function setMonday ($value)
	{
		if($value == true || $value != 0)
		{
			$this->monday = true;
		}
		else
		{
			$this->monday = false;
		}
	}

	public function getTuesday ()
	{
		if($this->tuesday)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function setTuesday ($value)
	{
		if($value == true || $value != 0)
		{
			$this->tuesday = true;
		}
		else
		{
			$this->tuesday = false;
		}
	}

	public function getWednesday ()
	{
		if($this->wednesday)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function setWednesday ($value)
	{
		if($value == true || $value != 0)
		{
			$this->wednesday = true;
		}
		else
		{
			$this->wednesday = false;
		}
	}

	public function getThursday ()
	{
		if($this->thursday)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function setThursday ($value)
	{
		if($value == true || $value != 0)
		{
			$this->thursday = true;
		}
		else
		{
			$this->thursday = false;
		}
	}

	public function getFriday ()
	{
		if($this->friday)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function setFriday ($value)
	{
		if($value == true || $value != 0)
		{
			$this->friday = true;
		}
		else
		{
			$this->friday = false;
		}
	}

	public function getSaturday ()
	{
		if($this->saturday)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function setSaturday ($value)
	{
		if($value == true || $value != 0)
		{
			$this->saturday = true;
		}
		else
		{
			$this->saturday = false;
		}
	}

	public function getHidden ()
	{
		if($this->hidden)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function setHidden ($value)
	{
		if($value == true || $value != 0)
		{
			$this->hidden = true;
		}
		else
		{
			$this->hidden = false;
		}
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

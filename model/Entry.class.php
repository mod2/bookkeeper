<?php
class Entry extends Model
{

	/**
	 * 
	 * @var pk
	 */
	protected $entryId;

	/**
	 * 
	 * @var int
	 */
	protected $bookId;

	/**
	 * 
	 * @var int
	 */
	protected $pageNumber;

	/**
	 * 
	 * @var date
	 */
	protected $entryDate;

	/****************************************************************************//**
	 * Constructor for this class, set all properties to a default value
	 */
	public function __construct()
	{
		$this->setBookId(0);
		$this->setPageNumber(0);
		$this->setEntryDate(new DateTime());
	}

	#***************************************************************************
	# Getters and Setters
	#***************************************************************************

	public function getEntryId ()
	{
		return($this->entryId);
	}

	public function setEntryId ($value)
	{
		$this->entryId = intval($value);
	}

	public function getBookId ()
	{
		return($this->bookId);
	}

	public function setBookId ($value)
	{
		$this->bookId = intval($value);
	}

	public function getPageNumber ()
	{
		return($this->pageNumber);
	}

	public function setPageNumber ($value)
	{
		$this->pageNumber = intval($value);
	}

	public function getEntryDate ()
	{
		return($this->entryDate);
	}

	public function getMYSQLEntryDate ()
	{
		return($this->entryDate->format('Y-m-d'));
	}

	public function setEntryDate ($value)
	{
		if($value != null && !is_a($value, 'DateTime'))
		{
			$type = 'Type exception';
			$msg = 'Invalid DateTime type passed into Entry.getEntryDate()';
			$exception = new ClassTypeException($type, $msg);
			throw($exception);
		}

		$this->entryDate = $value;
	}
}

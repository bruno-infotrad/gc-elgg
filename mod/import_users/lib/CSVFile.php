<?php
class CSVFile extends SplFileObject
{
	private $keys;

	public function __construct($file)
	{
		parent::__construct($file);
		$this->setFlags(SplFileObject::READ_CSV);
	}

	public function rewind()
	{
		parent::rewind();
		$this->keys = parent::current();
		parent::next();
	}

	public function current()
	{
		return array_combine($this->keys, parent::current());
	}

	public function getKeys()
	{
		return $this->keys;
	}
}
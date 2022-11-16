<?php namespace Src\Http\Validators;

/*
 * for use this class for validate values you can use like following:
 * $validation->setName('email')->setValue($data['email'])->isEmail();
 */

class FormValidator
{
	private array $errors = [];
	
	
	private string $name = '';
	
	
	private mixed $value = null;
	
	
	public array $validated = []; //list of validated values
	
	
	/**
	 * @return FormValidator
	 */
	public function isString() : self
	{
		if (!is_string($this->value)) {
			$this->errors[] = $this->name . ' is not String!';
		}
		return $this;
	}
	
	
	
	/**
	 * @return FormValidator
	 */
	public  function isNumber() : self
	{
		if (!is_numeric($this->value)) {
			$this->errors[] = $this->name . ' is not Integer!';
		}
		return $this;
	}
	
	
	/**
	 * @param int $length
	 * @return $this
	 */
	public function min( int $length) : self
	{
		if (is_string($this->value)) {
			if (strlen($this->value) < $length) {
				$this->errors[] = $this->name . ' Min Error!';
			}
		}
		
		return $this;
	}
	
	
	/**
	 * @param int $length
	 * @return $this
	 */
	public function max( int $length) : self
	{
		if (is_string($this->value)) {
			if (strlen($this->value) > $length) {
				$this->errors[] = $this->name . ' Max Error!';
			}
		}
		
		return $this;
	}
	
	
	/**
	 * @return FormValidator
	 */
	public  function isEmail() : self
	{
		if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
			$this->errors[] = $this->name . ' not Valid!';
		}
		return $this;
	}
	
	
	/**
	 * @return $this
	 */
	public function isUrl() : self
	{
		if (!filter_var($this->value, FILTER_VALIDATE_URL)) {
			$this->errors[] = $this->name . ' not Valid!';
		}
		return $this;
	}
	
	/**
	 * @return $this
	 */
	public function required() : self
	{
		if ($this->value == '' || $this->value == null) {
			$this->errors[] = $this->name.' is Required!';
		}
		return $this;
	}
	
	
	
	/**
	 * @return bool
	 */
	public function isSuccess() : bool
	{
		if (empty($this->errors)) {
			return true;
		}
		return false;
	}
	
	
	/**
	 * @param string $name
	 * @return FormValidator
	 */
	public function setName( string $name ) : self
	{
		$this->name = $name;
		return $this;
	}
	
	
	/**
	 * @param mixed $value
	 * @return FormValidator
	 */
	public function setValue( mixed $value ) : self
	{
		$this->value = $value;
		$this->validated[$this->name] = ($this->value != null) ? htmlspecialchars(trim($this->value)) : null ;
		
		return $this;
	}
	
	
	/**
	 * @return array
	 */
	public function getErrors() : array
	{
		return $this->errors;
	}
	
	
}
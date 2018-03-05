<?php

class SapidValidator extends CValidator
{
	
	public $sapidPattern = "^[A-Z]-[A-Z]{2}-[A-Z]{4}-A{1}G{1}[1-3]-[A-Z0-9]{4}$";

	
	protected function validateAttribute($object,$attribute)
	{
		$value=$object->$attribute;
		if(($value===null || $value===''))
			return;

		$valid = preg_match('/' . $this->sapidPattern . '/', $value) ;

		if(!$valid)
		{
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} is not valid');
			$this->addError($object,$attribute,$message);
		}
	}
}


<?php

class IUGOTemplate extends TemplatePower
{
	public $showTplMarks; //show template comment marks

	public function __construct($file, $filePath = '')
	{
		try
		{
			if ($filePath == '')
				parent::TemplatePower(ROOT_PATH.$file);
			else
				parent::TemplatePower($filePath.$file);
                        
			$this->fileName = $file;
			$this->showTplMarks = true;
			$this->prepare();
			$this->assignGlobal('BASE_PATH',BASE_PATH);
		}
		catch (Exception $ex)
		{
			throw $ex;
		}
	}

	public function reset()
	{
		try
		{
			$this->serialized = false;
			$this->index = array();
			$this->content = array();
			$this->prepare();
		}
		catch (Exception $ex)
		{
			throw $ex;
		}
	}
}

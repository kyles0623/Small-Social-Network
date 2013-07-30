

<?php



class PageClass
{
	protected $template;
	protected $page;
	protected $function;
	public function __construct($page,$function)
	{
		$this->template = 'main';
		$this->page = $page;
		$this->function = $function;
	}	


}
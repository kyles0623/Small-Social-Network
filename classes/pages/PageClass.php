<?php



class PageClass
{
	protected $template;
	protected $page;
	protected $function;
	protected $user;
	public function __construct($page,$function)
	{
		$this->template = 'main';
		$this->page = $page;
		$this->function = $function;

		
		$temp = new User();
		$this->user = $temp->getLoggedInUser();
	}	


}
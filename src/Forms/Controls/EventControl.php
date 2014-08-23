<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Venne\Forms\Controls;

/**
 * @author     Josef Kříž
 */
class EventControl extends \Nette\Forms\Controls\HiddenField
{

	/** @var callable */
	public $onAttached;

	/**
	 * @param string|null $caption
	 */
	public function __construct($caption = null)
	{
		parent::__construct($caption);

		$this->monitor('Nette\Application\UI\Presenter');
		$this->setOmitted(true);
	}

	protected function attached($form)
	{
		$this->onAttached($this);
	}

}

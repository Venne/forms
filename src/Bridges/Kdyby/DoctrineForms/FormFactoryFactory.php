<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Venne\Bridges\Kdyby\DoctrineForms;

use Kdyby\DoctrineForms\EntityFormMapper;
use Venne\Forms\IFormFactory;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormFactoryFactory implements \Venne\Forms\IFormFactoryFactory
{

	/** @var \Kdyby\DoctrineForms\EntityFormMapper */
	private $entityMapper;

	/**
	 * @param \Kdyby\DoctrineForms\EntityFormMapper $entityMapper
	 */
	public function __construct(EntityFormMapper $entityMapper)
	{
		$this->entityMapper = $entityMapper;
	}

	/**
	 * @param \Venne\Forms\IFormFactory|NULL $formFactory
	 * @return \Venne\Bridges\Kdyby\DoctrineForms\FormFactory
	 */
	public function create(IFormFactory $formFactory = null)
	{
		return new FormFactory($this->entityMapper, $formFactory);
	}

}

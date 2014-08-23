<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Venne\Forms;

use Nette\Application\UI\Form;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormFactory extends \Nette\Object implements \Venne\Forms\IFormFactory
{

	/** @var \Venne\Forms\IFormFactory|callable|null */
	private $formFactory;

	/**
	 * @param \Venne\Forms\IFormFactory|callable|null $formFactory
	 */
	public function __construct($formFactory = null)
	{
		if ($formFactory && !$formFactory instanceof IFormFactory && !is_callable($formFactory)) {
			throw new InvalidArgumentException("Form factory must be instance of 'Venne\Forms\IFormFactory' OR callable.");
		}

		$this->formFactory = $formFactory;
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	public function create()
	{
		$form = $this->formFactory
			? ($this->formFactory instanceof IFormFactory ? $this->formFactory->create() : call_user_func($this->formFactory))
			: new Form;

		if (!$form instanceof Form) {
			throw new InvalidStateException("Created object is not instance of 'Nette\Application\UI\Form'.");
		}

		return $form;
	}

}

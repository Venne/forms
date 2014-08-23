<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Venne\Forms\DI;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormsExtension extends \Nette\DI\CompilerExtension
{

	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();

		if (count($this->compiler->getExtensions('Kdyby\DoctrineForms\DI\FormsExtension'))) {
			$container->addDefinition($this->prefix('doctrineFormFactoryFactory'))
				->setClass('Venne\Bridges\Kdyby\DoctrineForms\FormFactoryFactory');
		}
	}

}

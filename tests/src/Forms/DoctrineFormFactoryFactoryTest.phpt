<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace VenneTests\Forms;

use Kdyby\DoctrineForms\EntityFormMapper;
use Nette\Application\UI\Form;
use Tester\Assert;
use Venne\Bridges\Kdyby\DoctrineForms\FormFactoryBuilder;
use Venne\Bridges\Kdyby\DoctrineForms\FormFactoryFactory;

require __DIR__ . '/../bootstrap.php';

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class DoctrineFormFactoryFactoryTest extends \Tester\TestCase
{

	public function testCreate()
	{
		$entityFormMapper = new EntityFormMapper;
		$formFactoryFactory = new FormFactoryFactory($entityFormMapper);

		Assert::type('Venne\Bridges\Kdyby\DoctrineForms\FormFactory', $formFactoryFactory->create());
	}

}

$testCache = new DoctrineFormFactoryFactoryTest;
$testCache->run();

namespace Kdyby\DoctrineForms;

class EntityFormMapper
{

	public $em;

	public $load;

	public $save;

	public function load($entity, $form)
	{
		$this->load = array($entity, $form);
	}

	public function save($entity, $form)
	{
		$this->save = array($entity, $form);
	}

	public function getEntityManager()
	{
		return $this->em;
	}

}

namespace Kdyby\Doctrine\Entities;

class BaseEntity
{

}


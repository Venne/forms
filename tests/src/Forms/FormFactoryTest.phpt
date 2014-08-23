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

use Nette\Application\UI\Form;
use Tester\Assert;
use Venne\Forms\FormFactory;

require __DIR__ . '/../bootstrap.php';

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormFactoryTest extends \Tester\TestCase
{

	public function testConstructor()
	{
		Assert::type('Venne\Forms\FormFactory', new FormFactory);
		Assert::type('Venne\Forms\FormFactory', new FormFactory(new FormFactory));
		Assert::type('Venne\Forms\FormFactory', new FormFactory(function () {
			return new Form;
		}));

		Assert::exception(function () {
			new FormFactory('foo');
		}, 'Nette\InvalidArgumentException');

		Assert::exception(function () {
			new FormFactory($this);
		}, 'Nette\InvalidArgumentException');
	}

	public function testCreate()
	{
		Assert::type('Nette\Application\UI\Form', (new FormFactory())->create());
		Assert::type(__NAMESPACE__ . '\MyForm', (new FormFactory(function () {
			return new MyForm;
		}))->create());
		Assert::exception(function () {
			(new FormFactory(function () {
				return null;
			}))->create();
		}, 'Nette\InvalidStateException');
	}

}

class MyForm extends Form
{

}

$testCache = new FormFactoryTest;
$testCache->run();

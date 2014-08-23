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
use Venne\Forms\Controls\EventControl;

require __DIR__ . '/../../bootstrap.php';

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class EventControlTest extends \Tester\TestCase
{

	public function testAttached()
	{
		$test = false;

		$presenter = new Presenter;
		$form = new Form;
		$form['_foo'] = $control = new EventControl('_foo');
		$control->onAttached[] = function () use (&$test) {
			$test = true;
		};

		Assert::false($test);

		$presenter['form'] = $form;

		Assert::true($test);
	}

}

class Presenter extends \Nette\Application\UI\Presenter
{

}

$testCache = new EventControlTest;
$testCache->run();

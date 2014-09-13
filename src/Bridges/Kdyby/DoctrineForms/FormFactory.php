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

use Kdyby\Doctrine\Entities\BaseEntity;
use Kdyby\DoctrineForms\EntityFormMapper;
use Nette\Application\UI\Form;
use Nette\Forms\ISubmitterControl;
use Nette\InvalidArgumentException;
use Venne\Forms\Controls\EventControl;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class FormFactory extends \Venne\Forms\FormFactory implements \Venne\Forms\IFormFactory
{

	/** @var \Kdyby\DoctrineForms\EntityFormMapper */
	private $entityMapper;

	/** @var \Kdyby\Doctrine\Entities\BaseEntity */
	private $entity;

	/** @var callable */
	private $saveEntity;

	/** @var bool */
	private $inTransaction = false;

	/**
	 * @param \Kdyby\DoctrineForms\EntityFormMapper $entityMapper
	 * @param \Venne\Forms\IFormFactory|callable|NULL $formFactory
	 */
	public function __construct(EntityFormMapper $entityMapper, $formFactory = null)
	{
		parent::__construct($formFactory);

		$this->entityMapper = $entityMapper;
		$this->saveEntity = function (Form $form) {
			return isset($form['_submit']) && $form->isSubmitted() === $form['_submit'];
		};
	}

	/**
	 * @param \Kdyby\Doctrine\Entities\BaseEntity $entity
	 * @return \Venne\Forms\FormFactory
	 */
	public function setEntity(BaseEntity $entity)
	{
		$this->entity = $entity;

		return $this;
	}

	/**
	 * @param callable $saveEntity
	 * @return \Venne\Forms\FormFactory
	 */
	public function setSaveEntity($saveEntity)
	{
		if ($saveEntity && !is_callable($saveEntity)) {
			throw new InvalidArgumentException("Argument must be callable.");
		}

		$this->saveEntity = $saveEntity;

		return $this;
	}

	/**
	 * @return \Venne\Forms\IFormFactory
	 */
	public function create()
	{
		$form = parent::create();

		$form['_eventControl'] = $eventControl = new EventControl('_eventControl');
		$entity = $this->entity;
		$saveEntity = $this->saveEntity;
		$eventControl->onAttached[] = function () use ($form, $entity) {
			$this->entityMapper->load($entity, $form);
			unset($form['_eventControl']);
		};
		$form->onValidate[] = function () use ($form, $entity, $saveEntity) {
			$this->entityMapper->save($entity, $form);
			if ($saveEntity && $saveEntity($form)) {
				try {
					$this->entityMapper->getEntityManager()->beginTransaction();
					$this->inTransaction = true;
					$this->entityMapper->getEntityManager()->getRepository($entity::getClassName())->save($entity);
				} catch (\Exception $e) {
					$this->entityMapper->getEntityManager()->rollback();
					$this->inTransaction = false;
					$form->addError($e->getMessage());
				}
			}
		};
		$form->onSuccess[] = function (Form $form) {
			if ($this->inTransaction) {
				try {
					$this->entityMapper->getEntityManager()->commit();
					$this->inTransaction = false;
				} catch (\Exception $e) {
					$this->entityMapper->getEntityManager()->rollback();
					$this->inTransaction = false;
					$form->addError($e->getMessage());
				}
			}
		};
		$form->onError[] = function () {
			if ($this->inTransaction) {
				$this->entityMapper->getEntityManager()->rollback();
			}
		};

		return $form;
	}

}

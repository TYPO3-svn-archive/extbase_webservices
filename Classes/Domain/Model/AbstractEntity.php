<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2010 Thomas Maroschik <tmaroschik@dfau.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * A WSDL Document
 *
 * @version $Id:$
 * @copyright Copyright belongs to the respective authors
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 * @scope prototype
 * @entity
 */
class Tx_ExtbaseWebservices_Domain_Model_AbstractEntity extends Tx_Extbase_DomainObject_AbstractEntity {

	/*
	 * Class Reflection
	 * @var Tx_Extbase_Reflection_Service
	 */
	protected $reflectionService;

	/*
	 * Request
	 * @var Tx_Extbase_MVC_Web_Request
	 */
	protected $request;

	/*
	 * Namespace
	 * @var string
	 */
	protected $namespace = 'http://www.w3.org/ns/wsdl';

	/*
	 * Namespace prefix
	 * @var string
	 */
	protected $namespacePrefix;

	/*
	 * Tag Name
	 * @var string
	 */
	protected $tagName;

	/*
	 * Target Namespace
	 * @var string
	 */
	protected $targetNamespace;

	/*
	 * Class Name
	 * @var string
	 */
	protected $className;

	/*
	 * XML Children
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_ExtbaseWebservices_Domain_Model_AbstractEntity>
	 */
	protected $children;

	/*
	 * The Constructor
	 * @return void
	 */
	public function __construct() {
		$this->children = t3lib_div::makeInstance('Tx_Extbase_Persistence_ObjectStorage');
	}

	/*
	 * Inject Class Reflection
	 * @param Tx_Extbase_Reflection_ClassReflection $classReflection: The Class Reflection with preset object class
	 * @return void
	 */
	public function injectReflectionService(Tx_Extbase_Reflection_Service $reflectionService) {
		$this->reflectionService = $reflectionService;
	}

	/*
	 * Inject Request
	 * @param Tx_Extbase_MVC_Web_Request $request: The webrequest
	 * @return void
	 */
	public function injectRequest(Tx_Extbase_MVC_Web_Request $request) {
		$this->request = $request;
	}

	/**
	 * Sets namespace
	 *
	 * @param string $namespace
	 * @return void
	 */
	public function setNamespace($namespace) {
		$this->namespace = $namespace;
	}

	/**
	 * Returns namespace
	 *
	 * @return string
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * Sets namespacePrefix
	 *
	 * @param String $namespacePrefix
	 * @return void
	 */
	public function setNamespacePrefix($namespacePrefix) {
		$this->namespacePrefix = $namespacePrefix;
	}

	/**
	 * Returns namespacePrefix
	 *
	 * @return String
	 */
	public function getNamespacePrefix() {
		if(is_string($this->namespacePrefix) && strlen($this->namespacePrefix)) {
			return $this->namespacePrefix . ':';
		} else {
			return;
		}
	}

	/**
	 * Sets tagName
	 *
	 * @param string $tagName
	 * @return void
	 */
	public function setTagName($tagName) {
		$this->tagName = $tagName;
	}

	/**
	 * Returns tagName
	 *
	 * @return string
	 */
	public function getTagName() {
		return $this->tagName;
	}

	/**
	 * Sets targetNamespace
	 *
	 * @param string $targetNamespace
	 * @return void
	 */
	public function setTargetNamespace($targetNamespace) {
		$this->targetNamespace = $targetNamespace;
	}

	/**
	 * Returns targetNamespace
	 *
	 * @return string
	 */
	public function getTargetNamespace() {
		return $this->targetNamespace;
	}

	/**
	 * Sets className
	 *
	 * @param string $className
	 * @return void
	 */
	public function setClassName($className) {
		$this->className = $className;
	}

	/**
	 * Returns className
	 *
	 * @return string
	 */
	public function getClassName() {
		return $this->className;
	}

	/**
	 * Sets Children
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_ExtbaseWebservices_Domain_Model_Xsd_AbstractEntity> $children An object storage containing the childrens to add
	 * @return void
	 */
	public function setChildren(Tx_Extbase_Persistence_ObjectStorage $children) {
		$this->children = $children;
	}

	/**
	 * Adds a Child
	 *
	 * @param Tx_ExtbaseWebservices_Domain_Model_Xsd_AbstractEntity Children
	 * @return void
	 * @api
	 */
	public function addChild(Tx_ExtbaseWebservices_Domain_Model_AbstractEntity $child) {
		$this->children->attach($child);
	}

	/**
	 * Removes a Child
	 *
	 * @param Tx_ExtbaseWebservices_Domain_Model_Xsd_AbstractEntity $children
	 * @return void
	 * @api
	 */
	public function removeChild(Tx_ExtbaseWebservices_Domain_Model_AbstractEntity $child) {
		$this->children->detach($child);
	}

	/**
	 * Returns the Children
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage An object storage containing the Children
	 * @api
	 */
	public function getChildren() {
		return $this->children;
	}

	/*
	 * Create a Child and inject reflection & request
	 * @param String $className: The Class to create
	 * @return Tx_ExtbaseWebservices_Domain_Model_AbstractEntity
	 */
	public function createChild($className) {
		$child = t3lib_div::makeInstance($className);
		$child->injectReflectionService($this->reflectionService);
		$child->injectRequest($this->request);
		$child->setClassName($this->getClassName());
		return $child;
	}

	/*
	 * Resolve the children
	 */
	public function resolveChildren() {

	}

}

?>
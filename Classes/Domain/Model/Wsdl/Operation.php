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
class Tx_ExtbaseWebservices_Domain_Model_Wsdl_Operation extends Tx_ExtbaseWebservices_Domain_Model_AbstractEntity {

	/*
	 * Tag Name
	 * @var string
	 */
	protected $tagName = 'operation';

	/*
	 * @var String
	 */
	protected $name;

	/*
	 * @var String
	 */
	protected $pattern = "http://www.w3.org/ns/wsdl/in-out";

	/*
	 * @var String
	 */
	protected $style = "http://www.w3.org/ns/wsdl/style/iri";

	/**
	 * Sets name
	 *
	 * @param String $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns name
	 *
	 * @return String
	 */
	public function getName() {
		return $this->name;
	}
	/**
	 * Sets pattern
	 *
	 * @param String $pattern
	 * @return void
	 */
	public function setPattern($pattern) {
		$this->pattern = $pattern;
	}

	/**
	 * Returns pattern
	 *
	 * @return String
	 */
	public function getPattern() {
		return $this->pattern;
	}

	/**
	 * Sets style
	 *
	 * @param String $style
	 * @return void
	 */
	public function setStyle($style) {
		$this->style = $style;
	}

	/**
	 * Returns style
	 *
	 * @return String
	 */
	public function getStyle() {
		return $this->style;
	}

	/*
	 * Parse and create children
	 */
	public function resolveChildren() {
		var_dump($this->reflectionService->getMethodParameters($this->getClassName(), $this->getName()));
	}

}
?>
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
class Tx_ExtbaseWebservices_Domain_Model_Xsd_AbstractEntity extends Tx_ExtbaseWebservices_Domain_Model_AbstractEntity {

	/*
	 * @var String
	 */
	protected $name;

	/*
	 * @var String
	 */
	protected $type;

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
	 * Sets type
	 *
	 * @param String $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Returns type
	 *
	 * @return String
	 */
	public function getType() {
		return $this->type;
	}


}

?>
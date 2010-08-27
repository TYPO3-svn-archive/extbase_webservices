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
class Tx_ExtbaseWebservices_Domain_Model_Wsdl_Document extends Tx_ExtbaseWebservices_Domain_Model_AbstractEntity {

	/*
	 * Tag Name
	 * @var string
	 */
	protected $tagName = 'description';


	/*
	 * Parses class name and creates children
	 */
	public function resolveChildren() {
		$classReflection = t3lib_div::makeInstance('Tx_Extbase_Reflection_ClassReflection', $this->getClassName());
		$bindableMethods = array();
		foreach($classReflection->getMethods() as $method) {
			if(
				substr($method->name,-6,6) == 'Action' &&
				$method->isPublic() == true &&
				$method->isStatic() == false &&
				$method->isProtected() == false &&
				$method->isStatic() == false
			) {
				$methodTags = $this->reflectionService->getMethodTagsValues($this->getClassName(), $method->name);
				if(
					array_key_exists('binding.soap', $methodTags) ||
					array_key_exists('binding.rest', $methodTags) ||
					array_key_exists('binding.xmlrpc', $methodTags)
				) {
					$bindableMethods[$method->name] = $methodTags;
				}
			}
		}
		if(is_array($bindableMethods) && !empty($bindableMethods)) {
			$interface = $this->createChild('Tx_ExtbaseWebservices_Domain_Model_Wsdl_Interface');
			$interface->setName($this->request->getControllerName(). 'Interface');
			foreach($bindableMethods as $methodName=>$method) {
				$operation = $this->createChild('Tx_ExtbaseWebservices_Domain_Model_Wsdl_Operation');
				$operation->setName($methodName);
				$operation->resolveChildren();
				$interface->addChild($operation);
			}
			$this->addChild($interface);
		}
//		$types = t3lib_div::makeInstance('Tx_ExtbaseWebservices_Domain_Model_Wsdl_Types');
//		$binding = t3lib_div::makeInstance('Tx_ExtbaseWebservices_Domain_Model_Wsdl_Binding');
	}
}

?>
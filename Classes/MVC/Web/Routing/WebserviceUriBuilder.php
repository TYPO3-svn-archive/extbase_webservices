<?php
/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * An URI Builder
 *
 * @package ExtbaseWebservices
 * @subpackage MVC\Web\Routing
 * @version $Id: UriBuilder.php 2184 2010-04-08 14:59:58Z jocrau $
 * @api
 */
class Tx_ExtbaseWebservices_MVC_Web_Routing_WebserviceUriBuilder extends Tx_Extbase_MVC_Web_Routing_UriBuilder {

	/**
	 * @var boolean
	 */
	protected $createAbsoluteUri = TRUE;


	/**
	 * @var int The Requested typeNum
	 */
	protected $typeNum;

	/**
	 * Sets typeNum
	 *
	 * @param integer $typeNum
	 * @return void
	 */
	public function setTypeNum($typeNum) {
		$this->typeNum = $typeNum;
	}

	/**
	 * Returns typeNum
	 *
	 * @return integer
	 */
	public function getTypeNum() {
		return $this->typeNum;
	}

	/**
	 * Creates an URI used for linking to an Extbase action.
	 * Works in Frondend and Backend mode of TYPO3.
	 *
	 * @param string $actionName Name of the action to be called
	 * @param array $controllerArguments Additional query parameters. Will be "namespaced" and merged with $this->arguments.
	 * @param string $controllerName Name of the target controller. If not set, current ControllerName is used.
	 * @param string $extensionName Name of the target extension, without underscores. If not set, current ExtensionName is used.
	 * @param string $pluginName Name of the target plugin. If not set, current PluginName is used.
	 * @return string the rendered URI
	 * @api
	 * @see build()
	 */
	public function uriFor($actionName = NULL, $controllerArguments = array(), $controllerName = NULL, $extensionName = NULL, $pluginName = NULL) {
		if ($actionName !== NULL) {
			$controllerArguments['action'] = $actionName;
		}
		if ($controllerName !== NULL) {
			$controllerArguments['controller'] = $controllerName;
		} else {
			$controllerArguments['controller'] = $this->request->getControllerName();
		}
		if ($extensionName === NULL) {
			$extensionName = $this->request->getControllerExtensionName();
		}
		if ($pluginName === NULL) {
			$pluginName = $this->request->getPluginName();
		}
		if ($this->format !== '') {
			$controllerArguments['format'] = $this->format;
		}
		$argumentPrefix = strtolower('tx_' . $extensionName . '_' . $pluginName);
		$prefixedControllerArguments = array($argumentPrefix => $controllerArguments);
		if ($this->typeNum === NULL) {
			$this->setTypeNum($this->request->getTypeNum());
		}
		if (is_int($this->typeNum) > 0) {
			$prefixedControllerArguments['type'] = $this->typeNum;
		}
		$this->arguments = t3lib_div::array_merge_recursive_overrule($this->arguments, $prefixedControllerArguments);

		return $this->build();
	}

	/**
	 * Creates an URI used for linking to an Extbase action.
	 * Works in Frondend and Backend mode of TYPO3.
	 *
	 * @param string $actionName Name of the action to be called
	 * @param array $controllerArguments Additional query parameters. Will be "namespaced" and merged with $this->arguments.
	 * @param string $controllerName Name of the target controller. If not set, current ControllerName is used.
	 * @param string $extensionName Name of the target extension, without underscores. If not set, current ExtensionName is used.
	 * @param string $pluginName Name of the target plugin. If not set, current PluginName is used.
	 * @return string the rendered URI
	 * @api
	 * @see build()
	 */
	public function uriForWsdl() {
		$arguments = array();
		if ($this->typeNum === NULL) {
			$this->setTypeNum($this->request->getTypeNum());
		}
		if (is_int($this->typeNum) > 0) {
			$arguments['type'] = $this->typeNum;
		}
		$this->arguments = t3lib_div::array_merge_recursive_overrule($this->arguments, $arguments);

		return $this->build();
	}

	/**
	 * Builds the URI, frontend flavour
	 *
	 * @return string The URI
	 * @see buildTypolinkConfiguration()
	 */
	public function build() {
		$typolinkConfiguration = $this->buildTypolinkConfiguration();

		if ($this->createAbsoluteUri === TRUE) {
			$typolinkConfiguration['forceAbsoluteUrl'] = TRUE;
		}

		$uri = $this->contentObject->typoLink_URL($typolinkConfiguration);
		return $uri;
	}
}
?>
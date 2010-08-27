<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Thomas Maroschik <tmaroschik@dfau.de>
*  All rights reserved
*
*  This class is a backport of the corresponding class of FLOW3.
*  All credits go to the v5 team.
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
 * Builds a web request.
 *
 * @package ExtbaseWebservices
 * @subpackage MVC\Webservices
 * @version $ID:$
 *
 * @scope prototype
 */
class Tx_ExtbaseWebservices_MVC_Web_WebserviceRequestBuilder extends Tx_Extbase_MVC_Web_RequestBuilder {

	/*
	 * The webservice request configuration
	 * @var array
	 */
	protected $requestConfiguration;


	/*
	 * The webservice format individual configuration
	 */
	protected $requestFormatConfiguration;


	public function initialize($configuration) {
		$this->configuration = $configuration;
		parent::initialize($configuration);
		$this->requestConfiguration = $configuration['webservice']['request'];
	}

	/**
	 * Builds a web request object from the raw HTTP information and the configuration
	 *
	 * @return Tx_Extbase_MVC_Web_Request The web request as an object
	 */
	public function build() {
		$parameters = t3lib_div::_GPmerged('tx_extbasewebservices');
		$parameters = t3lib_div::array_merge_recursive_overrule($parameters, t3lib_div::_GPmerged('tx_' . strtolower($this->extensionName) . '_' . strtolower($this->pluginName)));
		if(is_int($typeNum = (int) t3lib_div::_GP('type'))) {
			$parameters['typeNum'] = $typeNum;
		}

		if($this->requestConfiguration['className']) {
			$request = t3lib_div::makeInstance($this->requestConfiguration['className']);
		} else {
			$request = t3lib_div::makeInstance('Tx_Extbase_MVC_Web_Request');
		}

		if (is_string($parameters['format']) && (strlen($parameters['format']))) {
			$request->setFormat(filter_var($parameters['format'], FILTER_SANITIZE_STRING));
		}

		if(is_array($this->requestConfiguration['format'][$request->getFormat()])) {
			$this->setRequestFormatConfiguration($this->requestConfiguration['format'][$request->getFormat()]);
			parent::initialize(t3lib_div::array_merge_recursive_overrule($this->configuration, $this->requestConfiguration['format'][$request->getFormat()]));
		}

		if (is_string($parameters['controller']) && array_key_exists($parameters['controller'], $this->allowedControllerActions)) {
			$controllerName = filter_var($parameters['controller'], FILTER_SANITIZE_STRING);
			$allowedActions = $this->allowedControllerActions[$controllerName];
			if (is_string($parameters['action']) && is_array($allowedActions) && in_array($parameters['action'], $allowedActions)) {
				$actionName = filter_var($parameters['action'], FILTER_SANITIZE_STRING);
			} else {
				$actionName = $this->defaultActionName;
			}
		} else {
				$controllerName = $this->defaultControllerName;
				$actionName = $this->defaultActionName;
		}

		if(is_int($parameters['typeNum']) && $parameters['typeNum'] > 0) {
			$request->setTypeNum($parameters['typeNum']);
		}

		$request->setPluginName($this->pluginName);
		$request->setControllerExtensionName($this->extensionName);
		$request->setControllerName($controllerName);
		$request->setControllerActionName($actionName);
		$request->setRequestURI(t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'));
		$request->setBaseURI(t3lib_div::getIndpEnv('TYPO3_SITE_URL'));
		$request->setMethod((isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : NULL);


		foreach ($parameters as $argumentName => $argumentValue) {
			$request->setArgument($argumentName, $argumentValue);
		}

		return $request;
	}

	/**
	 * Sets requestFormatConfiguration
	 *
	 * @param array $requestFormatConfiguration
	 * @return void
	 */
	protected function setRequestFormatConfiguration($requestFormatConfiguration) {
		$this->requestFormatConfiguration = $requestFormatConfiguration;
	}

	/**
	 * Returns requestFormatConfiguration
	 *
	 * @return array
	 */
	public function getRequestFormatConfiguration() {
		return $this->requestFormatConfiguration;
	}


}
?>
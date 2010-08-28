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
 * A multi action webservice controller. This is by the most common base class for webservice Controllers.
 *
 * @package ExtbaseWebservices
 * @subpackage MVC\Controller
 * @version $ID:$
 * @api
 */
class Tx_ExtbaseWebservices_MVC_Controller_WebserviceController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * Handles a request. The result output is returned by altering the given response.
	 *
	 * @param Tx_Extbase_MVC_Request $request The request object
	 * @param Tx_Extbase_MVC_Response $response The response, modified by this handler
	 * @return void
	 */
	public function processRequest(Tx_Extbase_MVC_Request $request, Tx_Extbase_MVC_Response $response) {
		if (!$this->canProcessRequest($request)) throw new Tx_Extbase_MVC_Exception_UnsupportedRequestType(get_class($this) . ' does not support requests of type "' . get_class($request) . '". Supported types are: ' . implode(' ', $this->supportedRequestTypes) , 1187701131);

		$this->request = $request;
		$this->request->setDispatched(TRUE);
		$this->response = $response;

		$this->uriBuilder = t3lib_div::makeInstance('Tx_ExtbaseWebservices_MVC_Web_Routing_WebserviceUriBuilder');
		$this->uriBuilder->setRequest($request);

		$this->actionMethodName = $this->resolveActionMethodName();
//TODO: Create an Service
		if($this->request->getFormat() == 'soap') {
			$this->webService = t3lib_div::makeInstance('Tx_ExtbaseWebservices_Service_Soap');
			$this->webService->setWsdl($this->uriBuilder->uriForWsdl());
			$this->webService->setRequest($this->request);
			$this->webService->setResponse($this->response);
		}

		$this->initializeActionMethodArguments();
		$this->initializeActionMethodValidators();

		$this->initializeAction();
		$actionInitializationMethodName = 'initialize' . ucfirst($this->actionMethodName);
		if (method_exists($this, $actionInitializationMethodName)) {
			call_user_func(array($this, $actionInitializationMethodName));
		}

		$this->mapRequestArgumentsToControllerArguments();
		$this->checkRequestHash();
		$this->view = $this->resolveView();
		if ($this->view !== NULL) $this->initializeView($this->view);
		$this->callActionMethod();
	}

	/**
	 * Calls the specified action method and passes the arguments.
	 *
	 * If the action returns a string, it is appended to the content in the
	 * response object. If the action doesn't return anything and a valid
	 * view exists, the view is rendered automatically.
	 *
	 * @param string $actionMethodName Name of the action method to call
	 * @return void
	 * @api
	 */
//	protected function callActionMethod() {
//		$argumentsAreValid = TRUE;
//		$preparedArguments = array();
//		foreach ($this->arguments as $argument) {
//			$preparedArguments[] = $argument->getValue();
//		}
//
//		if ($this->argumentsMappingResults->hasErrors()) {
//			$actionResult = call_user_func(array($this, $this->errorMethodName));
//		} else {
//				$actionResult = call_user_func_array(array($this, $this->actionMethodName), $preparedArguments);
//			}
//		}
//		if ($actionResult === NULL && $this->view instanceof Tx_Extbase_MVC_View_ViewInterface) {
//			$this->response->appendContent($this->view->render());
//		} elseif (is_string($actionResult) && strlen($actionResult) > 0) {
//			$this->response->appendContent($actionResult);
//		}
	}

	/**
	 * Handles a wsdl request. The result output is returned by altering  response.
	 *
	 * @return string
	 */
	protected function wsdlAction() {
//		$wsdlDocument = t3lib_div::makeInstance('Tx_ExtbaseWebservices_Domain_Model_Wsdl_Document');
//		$wsdlDocument->injectReflectionService($this->reflectionService);
//		$wsdlDocument->injectRequest($this->request);
//		$wsdlDocument->setClassName(get_class($this));
//		$wsdlDocument->resolveChildren();
//		$this->view->assign('document' ,$wsdlDocument);
		$wsdl = t3lib_div::makeInstance('Tx_ExtbaseWebservices_Helper_Wsdl');
		$wsdl->injectClassReflection(t3lib_div::makeInstance('Tx_Extbase_Reflection_ClassReflection', get_class($this)));
		$wsdl->injectRequest($this->request);
		$wsdl->injectUriBuilder($this->uriBuilder);
		return $wsdl->getContent();
	}

	/**
	 * An ping action
	 *
	 * @binding.soap
	 * @binding.rest
	 * @binding.xmlrpc
	 * @param
	 * @return string
	 */
	public function pingAction() {
		return 'ping';
	}


	/**
	 * Prepares a view for the current action and stores it in $this->view.
	 * By default, this method tries to locate a view with a name matching
	 * the current action.
	 *
	 * @return void
	 * @api
	 */
	protected function resolveView() {
		$view = $this->objectManager->getObject('Tx_Fluid_View_TemplateView');
		$controllerContext = $this->buildControllerContext();
		$view->setControllerContext($controllerContext);

		// Template Path Override
		$extbaseFrameworkConfiguration = Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration();
		if (isset($extbaseFrameworkConfiguration['view']['templatePathAndFilename']) && strlen($extbaseFrameworkConfiguration['view']['templatePathAndFilename']) > 0) {
			$view->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templatePathAndFilename']));
		}
		if (isset($extbaseFrameworkConfiguration['view']['templateRootPath']) && strlen($extbaseFrameworkConfiguration['view']['templateRootPath']) > 0) {
			$view->setTemplateRootPath(t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']));
		}
		if (isset($extbaseFrameworkConfiguration['view']['layoutRootPath']) && strlen($extbaseFrameworkConfiguration['view']['layoutRootPath']) > 0) {
			$view->setLayoutRootPath(t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['view']['layoutRootPath']));
		}
		if (isset($extbaseFrameworkConfiguration['view']['partialRootPath']) && strlen($extbaseFrameworkConfiguration['view']['partialRootPath']) > 0) {
			$view->setPartialRootPath(t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['view']['partialRootPath']));
		}

		if ($view->hasTemplate() === FALSE) {
			$viewObjectName = $this->resolveViewObjectName();
			if (class_exists($viewObjectName) === FALSE) $viewObjectName = 'Tx_Extbase_MVC_View_EmptyView';
			$view = $this->objectManager->getObject($viewObjectName);
			$view->setControllerContext($controllerContext);
		}
		if (method_exists($view, 'injectSettings')) {
			$view->injectSettings($this->settings);
		}
		$view->initializeView(); // In FLOW3, solved through Object Lifecycle methods, we need to call it explicitely
		$view->assign('settings', $this->settings); // same with settings injection.
		return $view;
	}
}

?>
<?php

/*
 * -------------------------------------
 * View.php
 * -------------------------------------
 */

class View
{
	private $_controller;
	private $_js;
	private $_jsGr;
	private $_cssGr;

	public function __construct(Request $peticion) {
		$this->_controller = $peticion->getController();
		$this->_js = array();
		$this->_jsGr = array();
		$this->_cssGr = array();
	}

	/*
	 *  Function reloads the view
	 */
	public function renderizar($view, $partial = false)
	{
		$js = array();
		$jsGr = array();
		$cssGr = array();

		if (count($this->_js)) {
			$js = $this->_js;
		}

		if (count($this->_jsGr)) {
			$jsGr = $this->_jsGr;
		}

		if (count($this->_cssGr)) {
			$cssGr = $this->_cssGr;
		}

		$_layoutParams = array(
			'js' => $js,
			'jsGr' => $jsGr,
			'cssGr' => $cssGr
		);


		$routeView = ROOT . 'views' . DS . $this->_controller . DS . $view . '.phtml';
		 
		if(is_readable($routeView)){
			if (!$partial) {
				include_once ROOT . 'views'. DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'header.phtml';
				include_once $routeView;
				include_once ROOT . 'views'. DS . 'layout' . DS . DEFAULT_LAYOUT . DS . 'footer.phtml';
			}
			else
			include_once $routeView;
		}
		else {
			throw new Exception('View not found');
		}
	}

	/*
	 *  Load the specific js of the controller
	 */
	public function setJs(array $js)
	{
		if(is_array($js) && count($js)){
			for($i=0; $i < count($js); $i++){
				$this->_js[] = BASE_URL . 'views/' . $this->_controller . '/js/' . $js[$i] . '.js?v=' . LAST_VERSION_SOURCE;
			}
		} else {
			throw new Exception('Javascript not found');
		}
	}

	/*
	 *  Load the general js of the controller
	 *
	 */

	public function setJs_Gral(array $js)
	{
		if (is_array($js) && count($js)) {
			for ($i = 0; $i < count($js); $i++) {
				$this->_jsGr[] = BASE_URL . 'public/assets' . DS . $js[$i] . '.js?v=' . LAST_VERSION_SOURCE;
			}
		} else {
			throw new Exception('Js not found');
		}
	}

	/*
	 *  Load the general css of the controller
	 *
	 */

	public function setCss_Gral(array $css)
	{
		if (is_array($css) && count($css)) {
			for ($i = 0; $i < count($css); $i++) {
				$this->_cssGr[] = BASE_URL . 'public/assets' . DS . $css[$i] . '.css?v=' . LAST_VERSION_SOURCE;
			}
		} else {
			throw new Exception('Css not found');
		}
	}
}
<?php

/*
 * -------------------------------------

 * Controller.php
 * -------------------------------------
 */


abstract class Controller
{
	protected $_view;

	public function __construct()
	{
		$this->_view = new View(new Request);
	}

	abstract public function index();

	protected function loadModel($model)
	{
		$model = $model . 'Model';
		$routeModel = ROOT . 'models' . DS . $model . '.php';

		if (is_readable($routeModel)) {
			require_once $routeModel;
			$model = new $model;
			return $model;
		} else {
			throw new Exception('Model not found');
		}
	}

	public function loadHelper($helper)
	{
		$helper = $helper . '_helper';
		$routeHelper = ROOT . 'helpers' . DS . $helper . '.php';

		if (is_readable($routeHelper)) {
			require_once $routeHelper;		
		} else {
			throw new Exception('Helper not found');
		}
	}

	public function getLibrary($library)
	{
		$routeLibrary = ROOT . 'libs' . DS . $library . '.php';

		if (is_readable($routeLibrary)) {
			require_once $routeLibrary;
		} else {
			throw new Exception('Library not found');
		}
	}

	protected function redireccionar($ruta = false)
	{
		if ($ruta) {
			header('location:' . BASE_URL . $ruta);
			exit;
		} else {
			header('location:' . BASE_URL);
			exit;
		}
	}

	public function format_fecha($fecha, $format)
	{
		if (strlen($fecha) == 10) {
			if ($format == "d/m/y") {
				$anio = explode("-", $fecha);
				$fecha_format = $anio[2] . "/" . $anio[1] . "/" . $anio[0];
				return $fecha_format;
			} else if ($format == "y-m-d") {
				$anio = explode("/", $fecha);
				$fecha_format = $anio[0] . "-" . $anio[1] . "-" . $anio[2];
				return $fecha_format;
			}
		} else {
			return false;
		}
	}

	protected function is_valid_email($str)
	{
		$matches = null;
		return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
	}

	protected function is_valid_email_domain($email)
	{
		if (!empty($email)) {
			$pos = strpos($email, "@");
			if ($pos != "") {
				$explode = explode("@", $email);
				if (
					$explode[1] == "Biomont.com.pe" || $explode[1] == "biomont.com.pe" || $explode[1] == "BIOMONT.COM.PE" ||
					$explode[1] == "Gmail.com" || $explode[1] == "gmail.com" || $explode[1] == "GMAIL.COM" ||
					$explode[1] == "Hotmail.com" || $explode[1] == "gmail.com" || $explode[1] == "GMAIL.COM"
				) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return true;
		}
	}

	public function is_ruc($ruc)
	{
		$est = false;
		if (preg_match('/^(10|20|15|17)[0-9]{9}$/', $ruc)) {
			$suma = 0;
			$x = 6;
			for ($i = 0; $i < 10; $i++) {
				if ($i == 4) $x = 8;
				$digito = substr($ruc, $i, 1);
				$x--;
				$suma += ($digito * $x);
			}
			$resto = $suma % 11;
			$resto = 11 - $resto;
			if ($resto >= 10) {
				$resto = $resto - 10;
			}
			if ($resto == substr($ruc, 10, 1)) {
				$est = true;
			}
		}
		return $est;
	}

	public function valida_post($post)
	{
		if (isset($post) && !empty($post) && strlen($post) > 0)
			return true;
		else
			return false;
	}

	public function translate_for_bd($texto)
	{

		$texto = trim($texto);
		$texto = str_replace("Á", "&Aacute;", $texto);
		$texto = str_replace("É", "&Eacute;", $texto);
		$texto = str_replace("Í", "&Iacute;", $texto);
		$texto = str_replace("Ó", "&Oacute;", $texto);
		$texto = str_replace("Ú", "&Uacute;", $texto);
		$texto = str_replace("Ñ", "&Ntilde;", $texto);

		$texto = str_replace("á", "&aacute;", $texto);
		$texto = str_replace("é", "&eacute;", $texto);
		$texto = str_replace("í", "&iacute;", $texto);
		$texto = str_replace("ó", "&oacute;", $texto);
		$texto = str_replace("ú", "&uacute;", $texto);
		$texto = str_replace("ñ", "&ntilde;", $texto);

		$texto = str_replace("°", "&deg;", $texto);
		$texto = str_replace("¿", "&iquest;", $texto);
		$texto = str_replace("&‌", "&‌amp;", $texto);
		$texto = str_replace(">", "&‌gt;", $texto);
		$texto = str_replace("<", "&‌lt;", $texto);

		return $texto;
	}

	public function translate_from_bd($texto)
	{

		$texto = str_replace("&Aacute;", "Á", $texto);
		$texto = str_replace("&Eacute;", "É", $texto);
		$texto = str_replace("&Iacute;", "Í", $texto);
		$texto = str_replace("&Oacute;", "Ó", $texto);
		$texto = str_replace("&Uacute;", "Ú", $texto);
		$texto = str_replace("&Ntilde;", "Ñ", $texto);

		$texto = str_replace("&aacute;", "á", $texto);
		$texto = str_replace("&eacute;", "é", $texto);
		$texto = str_replace("&iacute;", "í", $texto);
		$texto = str_replace("&oacute;", "ó", $texto);
		$texto = str_replace("&uacute;", "ú", $texto);
		$texto = str_replace("&ntilde;", "ñ", $texto);

		$texto = str_replace("&deg;", "°", $texto);
		$texto = str_replace("&iquest;", "¿", $texto);
		$texto = str_replace("&‌amp;", "&‌", $texto);
		$texto = str_replace("&‌gt;", ">", $texto);
		$texto = str_replace("&‌lt;", "<", $texto);

		return $texto;
	}

	public function paginate($page, $tpages, $adjacents)
	{
		$prevlabel = "<i class='fa fa-chevron-left'></i>";
		$nextlabel = "<i class='fa fa-chevron-right'></i>";
		$out = '<nav aria-label="Page navigation example"><ul class="pagination">';

		// previous label

		if ($page == 1) {
			$out .= "<li class='page-item disabled'><span><a class='page-link'>" . $prevlabel . "</a></span></li>";
		} else if ($page == 2) {
			$out .= "<li class='page-item'><span><a class='page-link' href='javascript:void(0);' onclick='load(1)'>" . $prevlabel . "</a></span></li>";
		} else {
			$out .= "<li class='page-item'><span><a class='page-link' href='javascript:void(0);' onclick='load(" . ($page - 1) . ")'>" . $prevlabel . "</a></span></li>";
		}

		// first label
		if ($page > ($adjacents + 1)) {
			$out .= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(1)'>1</a></li>";
		}
		// interval
		if ($page > ($adjacents + 2)) {
			$out .= "<li class='page-item'><a class='page-link'>...</a></li>";
		}

		// pages

		$pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
		$pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
		for ($i = $pmin; $i <= $pmax; $i++) {
			if ($i == $page) {
				$out .= "<li class='page-item active'><a class='page-link'>$i</a></li>";
			} else if ($i == 1) {
				$out .= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(1)'>" . $i . "</a></li>";
			} else {
				$out .= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(" . $i . ")'>" . $i . "</a></li>";
			}
		}

		// interval

		if ($page < ($tpages - $adjacents - 1)) {
			$out .= "<li class='page-item'><a class='page-link'>...</a></li>";
		}

		// last

		if ($page < ($tpages - $adjacents)) {
			$out .= "<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='load(" . $tpages . ")'>" . $tpages . "</a></li>";
		}

		// next

		if ($page < $tpages) {
			$out .= "<li class='page-item'><span><a class='page-link' href='javascript:void(0);' onclick='load(" . ($page + 1) . ")'>" . $nextlabel . "</a></span></li>";
		} else {
			$out .= "<li class='page-item disabled'><span><a class='page-link'>" . $nextlabel . "</a></span></li>";
		}

		$out .= "</ul></nav>";
		return $out;
	}
}

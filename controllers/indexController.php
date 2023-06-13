<?php

class indexController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo "BIOMONT NETSUITE OK";
		error_log("BIOMONT NETSUITE ERROR LOG OK");
	}

}
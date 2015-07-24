<?php

class Mathmultiply1Controller extends \BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		
		for ($i=0;$i<81;$i++){
			$Multiply1row = new Mathmultiply1;
			$operand1 = rand(1,9);
			$operand2 = rand(1,9);
			$invisualcolumns = rand(1,3);
			$Multiply1row->invisualcolumns = $invisualcolumns;
			$Multiply1row->operand1 = $operand1;
			$Multiply1row->operand2 = $operand2;
			$Multiply1row->multiplydata = $operand1*$operand2;
			$Multiply1row->difficulty = 1;
			try {
			    $Multiply1row->save();
			} catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
			
		}
		return "done!";
	}
	
}

<?php
	function showDiscount($amount, $percent)
	{
		$sc = 0;
		if( $amount > 0 && $percent == 0 )
		{
			$sc = $amount;	
		}
		else if( $percent > 0 && $amount == 0 )
		{
			$sc = $percent;	
		}
		return $sc;
	}


	function discountType($amount, $percent)
	{
		$sc = $amount > 0 ? 'amount' : 'percent';
		return $sc;	
	}
	
	
	//------ Add Symbol
	function viewDiscount($amount, $percent)
	{
		$sc = 0;
		if( $amount > 0 && $percent == 0 )
		{
			$sc = number_format($amount, 2);	
		}
		else if( $percent > 0 && $amount == 0 )
		{
			$sc = $percent.'%';
		}
		return $sc;
	}

?>
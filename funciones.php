<?php
		#TIR

	    function idh_tir ($capital, $p, $cupon, $n, $amort)
		{ 	$TIR = 0.10;
			$mayor = 1;
			$menor = 0;
			$amort = $amort*$capital/100;
			$van = idh_van($capital, $TIR, $n, $cupon, $amort, $precio);
			$x = 0;
			while (($van < -$capital*$p*0.000001 || $van > $capital*$p*0.000001) && ($x < 100)) {
			$van = idh_van($capital, $TIR, $n, $cupon, $amort, $p);
			if ($van < -$capital*$p*0.000001) {$mayor = $TIR; $TIR = ($mayor+$menor)/2; $x = $x + 1;	}
				elseif ($van > $capital*$p*0.000001) {$menor = $TIR; $TIR = ($menor+$mayor)/2;  $x = $x + 1;	};
			};
		   return $TIR;
		}

		#VAN

	    function idh_van ($k, $i, $n, $Q, $amort, $p)	{		
		$van = ($Q/$i)*(1-(1/pow(1+$i,$n))) - ($k*$p/100) ;		
		if ($amort != 0) { $van = $van + $amort/pow(1+$i,$n);	};	
			return $van;
		}
		
?>
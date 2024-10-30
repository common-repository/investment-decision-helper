<?php
/* 	@package Investment Decision Helper
	@version 1.1.1  
	
	Plugin Name: Investment Decision Helper
	Plugin URI: http://calculadorafinanciera.co
	Description: This tool will allow you to compare return rates of two different custom instruments in order to help you taking the best decision.
	Author: Aníbal Álvarez
	Version: 1.1.1
	Author URI: http://anibalalvarez.com */

class investment_decision {
    public function __construct()  
    {
        add_shortcode('investment_decision', array($this, 'calculator_shortcode'));  
		add_action('wp_enqueue_scripts', array($this, 'idh_add_stylesheet'));
    }

    public function idh_add_stylesheet() {
        wp_register_style( 'idh-style', plugins_url('investment-decision.css', __FILE__) );
        wp_enqueue_style( 'idh-style' );
    }
	
    public function calculator_shortcode($atts) {
    	$output = $this->idh_calculator();
    	
    	return $output;
    }

	/**
	 * Form and result Output
	 */
    public function idh_calculator() {
	
		include 'funciones.php';
	
        $output = '<form class="idh_calculator" method="post">';
		
		//Instrumento 1
        $err1 = array();
		
        //Verifica los datos ingresados
		if (!isset($_POST['rend1']) || $_POST['rend1'] == '' || (!is_float(0 + $_POST['rend1']) &&  !is_int(0 + $_POST['rend1']))) {
		
		$cupon1 = '';
        if (isset($_POST['cupon1'])) {
            $cupon1 = $_POST['cupon1'];
            if ((!is_float(0 + $cupon1) && !is_int(0 + $cupon1)) || '' == $cupon1) {
                $err1['cupon1'] = '<font color="red">' . __('Please enter a valid coupon', 'investment-decision') . '</font>'; }
			}
        $numero_de_pagos1 = '';
        if (isset($_POST['numero_de_pagos1'])) {
            $numero_de_pagos1 = $_POST['numero_de_pagos1'];
            if ((!is_int(0 + $numero_de_pagos1)) || '' == $numero_de_pagos1) {
                $err1['numero_de_pagos1'] = '<font color="red">' . __('Please enter a valid number of payments', 'investment-decision') . '</font>'; }
			}
                
        $capital1 = '';
        if (isset($_POST['capital1'])) {
            $capital1 = $_POST['capital1'];
            if ((!is_int(0 + $capital1) && !is_float(0 + $capital1)) || ('' == $capital1)) {
                $err1['capital1'] = '<font color="red">' . __('Please enter a valid amount', 'investment-decision') . '</font>'; }
			} 
        
        $amortizacion1 = '';
        if (isset($_POST['amortizacion1'])) {
            $amortizacion1 = $_POST['amortizacion1'];
            if (!is_int(0 + $amortizacion1) && ('' != $amortizacion1)) {
                $err1['amortizacion1'] = '<font color="red">' . __('Please enter a valid amount', 'investment-decision') . '</font>'; }
			}
        
		$precio1 = '';
		if (isset($_POST['precio1'])) {
            $precio1 = $_POST['precio1'];
            if ((!is_float(0 + $precio1) && !is_int(0 + $precio1)) || ($precio1 < 0)) {
                $err1['precio1'] = '<font color="red">' . __('Please enter a valid amount', 'investment-decision') . '</font>'; }
			}
		
		unset($idh_frequency_1_0); unset($idh_frequency_1_1); unset($idh_frequency_1_2); unset($idh_frequency_1_3);
		if ($_POST["frequency1"] == "biannual") { $idh_frequency_1_1 = "selected"; }
		elseif ($_POST["frequency1"] == "quarterly") { $idh_frequency_1_2 = "selected"; }
		elseif ($_POST["frequency1"] == "monthly") { $idh_frequency_1_3 = "selected"; }
		else { $idh_frequency_1_0 = "selected"; };
		
		// SI no se rellenan "Amortización" y "Precio", se asignan valores por defecto
		
		if ('' == $amortizacion1) { $amortizacion1 = 0;};
		
		if ('' == $precio1) { $precio1 = 100;};
		
        if (count($err1) > 0) {
            $output .= '<p class="sfc_validation_error"><font color="red">' . __('There was an error in the 1st Instrument', 'investment-decision') . '</font></p>';
        } else {

		//AJUSTO EL CUPÓN			
			if ($_POST["frequency1"] == 'biannual') { $cupon_tir1 = $cupon1*((1-(1/pow(1+$cupon1/$capital1,2)))/($cupon1/$capital1))*pow(1+($cupon1/$capital1),2); }
			elseif ($_POST["frequency1"] == 'quarterly') { $cupon_tir1 = $cupon1*((1-(1/pow(1+$cupon1/$capital1,4)))/($cupon1/$capital1))*pow(1+($cupon1/$capital1),4); }
			elseif ($_POST["frequency1"] == 'monthly') { $cupon_tir1 = $cupon1*((1-(1/pow(1+$cupon1/$capital1,12)))/($cupon1/$capital1))*pow(1+($cupon1/$capital1),12); }
			else { $cupon_tir1 = $cupon1; };
			
		//AJUSTO EL NÚMERO DE PAGOS		
			if ($_POST["frequency1"] == 'biannual') { $numero_tir1 = floor($numero_de_pagos1/2);
														}
			elseif ($_POST["frequency1"] == 'quarterly') { $numero_tir1 = floor($numero_de_pagos1/4);
														}
			elseif ($_POST["frequency1"] == 'monthly') { $numero_tir1 = floor($numero_de_pagos1/12);
														}
			else { $numero_tir1 = $numero_de_pagos1;
					};
			
		if (($capital1 != "") && ($precio1 != "") && ($cupon1 != "") && ($numero_de_pagos1 != "") && ($amortizacion1 != "")) { $TIR1 = idh_tir($capital1, $precio1, $cupon_tir1, $numero_tir1, $amortizacion1); };	
		
				} ;
		
		} else { $TIR1 = $_POST['rend1'] / 100;
					if ($frequency1 == 'biannual') { $TIR1 = pow(1+$TIR1,2)-1; }
					elseif ($frequency1 == 'quarterly') { $TIR1 = pow(1+$TIR1,4)-1; }
					elseif ($frequency1 == 'monthly') { $TIR1 = pow(1+$TIR1,12)-1; };
				};
		
		$result1 = '<div align="center"><font size="1">' . __('Annual adjustment:', 'investment-decision') . '</font><h2>' . __('RETURNS:', 'investment-decision') . '</h2>' . __('1st Instrument:', 'investment-decision') . '<br /><font size="4"><b>' . number_format($TIR1*100, 2,'.','') . '</b></font>';
		
		//Instrumento 2
        $err2 = array();
        //Verifica los datos ingresados
		if (!isset($_POST['rend2']) || $_POST['rend2'] == '' || (!is_float(0 + $_POST['rend2']) &&  !is_int(0 + $_POST['rend2']))) {
		
		$cupon2 = '';
        if (isset($_POST['cupon2'])) {
            $cupon2 = $_POST['cupon2'];
            if ((!is_float(0 + $cupon2) && !is_int(0 + $cupon2)) || '' == $cupon2) {
                $err2['cupon2'] = '<font color="red">' . __('Please enter a valid coupon', 'investment-decision') . '</font></p>';
            }
        }
        $numero_de_pagos2 = '';
        if (isset($_POST['numero_de_pagos2'])) {
            $numero_de_pagos2 = $_POST['numero_de_pagos2'];
            if ((!is_int(0 + $numero_de_pagos2)) || '' == $numero_de_pagos2) {
                $err2['numero_de_pagos2'] = '<font color="red">' . __('Please enter a valid number of payments', 'investment-decision') . '</font></p>';
            }
        }
                
        $capital2 = '';
        if (isset($_POST['capital2'])) {
            $capital2 = $_POST['capital2'];
            if ((!is_int(0 + $capital2) && !is_float(0 + $capital2)) || ('' == $capital2)) {
                $err2['capital2'] = '<font color="red">' . __('Please enter a valid amount', 'investment-decision') . '</font></p>';
            }
        } 
        
        $amortizacion2 = '';
        if (isset($_POST['amortizacion2'])) {
            $amortizacion2 = $_POST['amortizacion2'];
            if (!is_int(0 + $amortizacion2) && ('' != $amortizacion2)) {
                $err2['amortizacion2'] = '<font color="red">' . __('Please enter a valid amount', 'investment-decision') . '</font></p>';
            }
        }
        
		$precio2 = '';
		if (isset($_POST['precio2'])) {
            $precio2 = $_POST['precio2'];
            if ((!is_float(0 + $precio2) && !is_int(0 + $precio2)) || ($precio2 < 0)) {
                $err2['precio2'] = '<font color="red">' . __('Please enter a valid amount', 'investment-decision') . '</font></p>';
            }
        }
		
		unset($idh_frequency_2_0); unset($idh_frequency_2_1); unset($idh_frequency_2_2); unset($idh_frequency_2_3);
		if ($_POST["frequency2"] == "biannual") { $idh_frequency_2_1 = "selected"; }
		elseif ($_POST["frequency2"] == "quarterly") { $idh_frequency_2_2 = "selected"; }
		elseif ($_POST["frequency2"] == "monthly") { $idh_frequency_2_3 = "selected"; }
		else { $idh_frequency_2_0 = "selected"; };
		
		// SI no se rellenan "Amortización" y "Precio", se asignan valores por defecto
		
		if ('' == $amortizacion2) { $amortizacion2 = 0;};
		
		if ('' == $precio2) { $precio2 = 100;};
		
        if (count($err2) > 0) {
            $output .= '<p class="sfc_validation_error"><font color="red">' . __('There was an error in the 2nd Instrument', 'investment-decision') . '</font></p>';
        } else {

		//AJUSTO EL CUPÓN			
			if ($_POST["frequency2"] == 'biannual') { $cupon_tir2 = $cupon2*((1-(1/pow(1+$cupon2/$capital2,2)))/($cupon2/$capital2))*pow(1+($cupon2/$capital2),2); }
			elseif ($_POST["frequency2"] == 'quarterly') { $cupon_tir2 = $cupon2*((1-(1/pow(1+$cupon2/$capital2,4)))/($cupon2/$capital2))*pow(1+($cupon2/$capital2),4); }
			elseif ($_POST["frequency2"] == 'monthly') { $cupon_tir2 = $cupon2*((1-(1/pow(1+$cupon2/$capital2,12)))/($cupon2/$capital2))*pow(1+($cupon2/$capital2),12); }
			else { $cupon_tir2 = $cupon2; };
			
		//AJUSTO EL NÚMERO DE PAGOS		
			if ($_POST["frequency2"] == 'biannual') { $numero_tir2 = floor($numero_de_pagos2/2);
														}
			elseif ($_POST["frequency2"] == 'quarterly') { $numero_tir2 = floor($numero_de_pagos2/4);
														}
			elseif ($_POST["frequency2"] == 'monthly') { $numero_tir2 = floor($numero_de_pagos2/12);
														}
			else { $numero_tir2 = $numero_de_pagos2;
					};
			
		if (($capital2 != "") && ($precio2 != "") && ($cupon2 != "") && ($numero_de_pagos2 != "") && ($amortizacion2 != "")) { $TIR2 = idh_tir($capital2, $precio2, $cupon_tir2, $numero_tir2, $amortizacion2); };	
		
				} ;
		
		} else { $TIR2 = $_POST['rend2'] / 100;
					if ($frequency2 == 'biannual') { $TIR2 = pow(1+$TIR2,2)-1; }
					elseif ($frequency2 == 'quarterly') { $TIR2 = pow(1+$TIR2,4)-1; }
					elseif ($frequency2 == 'monthly') { $TIR2 = pow(1+$TIR2,12)-1; };
				};	
		
		$result2 = '<div align="center">' . __('2nd Instrument:', 'investment-decision') . '<br /><font size="4"><b>' . number_format($TIR2*100, 2,'.','') . '</b></font>';	

		// DECIDIMOS CUAL INVERSIÓN ELEGIR Y MARCAMOS LA TIR MÁXIMA PARA LA GRÁFICA
		
		if ($TIR1 > $TIR2) { 
			$output .= 		
            '<div class="anp">'		.	
			(($TIR1) ? '<font color="green">' . $result1 . '%</font></div>' : '') .	
			(($TIR2) ? '' . $result2 . '%</div>' : ''); 
			$max_axis = floor($TIR1*100) + 1; }
			elseif ($TIR2 > $TIR1) { 
			$output .= 		
            '<div class="anp">'		.	
			(($TIR1) ? '' . $result1 . '%</div>' : '') .	
			(($TIR2) ? '<font color="green">' . $result2 . '%</font></div>' : ''); 
			$max_axis = floor($TIR2*100) + 1; }
			else { 
			$output .= 		
            '<div class="anp">'		.	
			(($TIR1) ? '' . $result1 . '%</div>' : '') .	
			(($TIR2) ? '' . $result2 . '%</div>' : ''); 
			$max_axis = floor($TIR1*100) + 1; };
			
		// CREAMOS ARREGLO DE VALORES PARA LA GRÁFICA
		
		if ((($capital1 != "") && ($precio1 != "") && ($cupon1 != "") && ($numero_de_pagos1 != "") && ($amortizacion1 != "")) || (($capital2 != "") && ($precio2 != "") && ($cupon2 != "") && ($numero_de_pagos2 != "") && ($amortizacion2 != ""))) {
		
		$idh_clave = 0;
		$amort1 = $amortizacion1*$capital1/100;
		$amort2 = $amortizacion2*$capital2/100;
		
			//AJUSTO LOS CUPONES PARA LA GRÁFICA			
			if ($_POST["frequency1"] == 'biannual') { $cupon_ajustado1 = $cupon1*((1-(1/pow(1+$cupon1/$capital1,2)))/($cupon1/$capital1))*pow(1+($cupon1/$capital1),2); }
			elseif ($_POST["frequency1"] == 'quarterly') { $cupon_ajustado1 = $cupon1*((1-(1/pow(1+$cupon1/$capital1,4)))/($cupon1/$capital1))*pow(1+($cupon1/$capital1),4); }
			elseif ($_POST["frequency1"] == 'monthly') { $cupon_ajustado1 = $cupon1*((1-(1/pow(1+$cupon1/$capital1,12)))/($cupon1/$capital1))*pow(1+($cupon1/$capital1),12); }
			else { $cupon_ajustado1 = $cupon1; };
			
			if ($_POST["frequency2"] == 'biannual') { $cupon_ajustado2 = $cupon2*((1-(1/pow(1+$cupon2/$capital2,2)))/($cupon2/$capital2))*pow(1+($cupon2/$capital2),2); }
			elseif ($_POST["frequency2"] == 'quarterly') { $cupon_ajustado2 = $cupon2*((1-(1/pow(1+$cupon2/$capital2,4)))/($cupon2/$capital2))*pow(1+($cupon2/$capital2),4); }
			elseif ($_POST["frequency2"] == 'monthly') { $cupon_ajustado2 = $cupon2*((1-(1/pow(1+$cupon2/$capital2,12)))/($cupon2/$capital2))*pow(1+($cupon2/$capital2),12); }
			else { $cupon_ajustado2 = $cupon2; };
			//FIN AJUSTE
			
			//AJUSTO EL NÚMERO DE PAGOS PARA LA GRÁFICA			
			if ($_POST["frequency1"] == 'biannual') { $numero_ajustado1 = floor($numero_de_pagos1/2);
												$resto1 = $numero_de_pagos1%2;
												$potencia1 = 2;}
			elseif ($_POST["frequency1"] == 'quarterly') { $numero_ajustado1 = floor($numero_de_pagos1/4);
												$resto1 = $numero_de_pagos1%4;
												$potencia1 = 4;}
			elseif ($_POST["frequency1"] == 'monthly') { $numero_ajustado1 = floor($numero_de_pagos1/12);
												$resto1 = $numero_de_pagos1%12;
												$potencia1 = 12;}
			else { $numero_ajustado1 = $numero_de_pagos1;
					$resto1 = 0;
					$potencia1 = 1;};
			
			if ($_POST["frequency2"] == 'biannual') { $numero_ajustado2 = floor($numero_de_pagos2/2);
												$resto2 = $numero_de_pagos2%2;
												$potencia2 = 2;}
			elseif ($_POST["frequency2"] == 'quarterly') { $numero_ajustado2 = floor($numero_de_pagos2/4);
												$resto2 = $numero_de_pagos2%4;
												$potencia2 = 4;}
			elseif ($_POST["frequency2"] == 'monthly') { $numero_ajustado2 = floor($numero_de_pagos2/12);
												$resto2 = $numero_de_pagos2%12;
												$potencia2 = 12;}
			else { $numero_ajustado2 = $numero_de_pagos2;
					$resto2 = 0;
					$potencia2 = 1;};
			//FIN AJUSTE
		
		while ($idh_clave <= $max_axis) { if ($idh_clave == 0) { $idh_tasa = "0.00000000001";}
											else { $idh_tasa = ($idh_clave)/100; };
										
										$idh_first[$idh_clave] = idh_van($capital1, $idh_tasa, $numero_ajustado1, $cupon_ajustado1, $amort1, $precio1);
										if ($resto1 > 0) { 	$tasa_menor1 = pow(1 + $idh_tasa,1/$potencia1) - 1;
															$idh_first[$idh_clave] = $idh_first[$idh_clave] + ($cupon1+$capital1)/pow(1+$tasa_menor1,$numero_de_pagos1) - $capital1/pow(1+$idh_tasa,$numero_ajustado1);
															};
										$idh_first[$idh_clave] = number_format($idh_first[$idh_clave],2,'.','');
										
										$idh_second[$idh_clave] = idh_van($capital2, $idh_tasa, $numero_ajustado2, $cupon_ajustado2, $amort2, $precio2);
										if ($resto2 > 0) { 	$tasa_menor2 = pow(1 + $idh_tasa,1/$potencia2) - 1;
															$idh_second[$idh_clave] = $idh_second[$idh_clave] + ($cupon2+$capital2)/pow(1+$tasa_menor2,$numero_de_pagos2) - $capital2/pow(1+$idh_tasa,$numero_ajustado2);
															};
										$idh_second[$idh_clave] = number_format($idh_second[$idh_clave],2,'.','');
										
										$idh_axis[$idh_clave] = $idh_clave;
										$idh_clave = $idh_clave + 1;
										};
										
		$idh_clave = $idh_clave - 1;
		
		$idh_etiquetas[0]=__('1st Instrument:', 'investment-decision');
		$idh_etiquetas[1]=__('2nd Instrument:', 'investment-decision');
		$idh_etiquetas[2]=__('NPV', 'investment-decision');
		$idh_etiquetas[3]=__('IRR', 'investment-decision');
		$idh_etiquetas[4]=__('NPV', 'investment-decision') . ' vs ' . __('IRR', 'investment-decision');
		
		// Reduzco los arreglos a un máximo de 20 datos
		$idh_patron = floor($idh_clave/20);
		$idh_guia = 0;
		while ($idh_guia <= ($idh_clave/$idh_patron)) { $idh_first_ready[$idh_guia] = $idh_first[$idh_guia*$idh_patron];
										   $idh_second_ready[$idh_guia] = $idh_second[$idh_guia*$idh_patron];
										   $idh_axis_ready[$idh_guia] = $idh_axis[$idh_guia*$idh_patron];
										   $idh_guia = $idh_guia + 1;
										   };
										   
		// Serializo los arreglos para pasarlos por URL
		$idh_serial1=serialize($idh_first_ready); $idh_serial1=urlencode($idh_serial1);
		$idh_serial2=serialize($idh_second_ready); $idh_serial2=urlencode($idh_serial2);
		$idh_serial3=serialize($idh_axis_ready); $idh_serial3=urlencode($idh_serial3);
		$idh_tags=serialize($idh_etiquetas); $idh_tags=urlencode($idh_tags);
		
		$output .=
			/* 'Instrumento 1<br />Frequency: ' . $_POST["frequency1"] . '<br />Cupon: ' . $cupon1 . '<br />Cupon Ajustado: ' . $cupon_ajustado1 . '<br />Instrumento 2<br />Frequency: ' . $_POST["frequency2"] . '<br />Cupon: ' . $cupon2 . '<br />Cupon Ajustado: ' . $cupon_ajustado2 . '<br />
			 Instrumento 1<br />Numero: ' . $numero_de_pagos1 . '<br />Numero Ajustado: ' . $numero_ajustado1 . '<br />Resto: ' . $resto1 . '<br />Instrumento 2<br />Numero: ' . $numero_de_pagos2 . '<br />Numero Ajustado: ' . $numero_ajustado2 . '<br />Resto: ' . $resto2 . '<br /> */
			'<div>
				<img style="max-width:100%" src="wp-content/plugins/investment-decision-helper/grafica-comparacion.php?serie1='.$idh_serial1.'&serie2='.$idh_serial2.'&serie3='.$idh_serial3.'&etiquetas='.$idh_tags.'" />
			 </div>';/* .$idh_first[0].'-'.$idh_first[1].'-'.$idh_first[2].'-'.$idh_first[3].'-'.$idh_first[4].'-'.$idh_first[5].'-'.$idh_first[6].'-'.$idh_first[7].'-'.$idh_first[8].'-'.$idh_first[9].'-'.$idh_first[10].'-'
						.$idh_first[11].'-'.$idh_first[12].'-'.$idh_first[13].'-'.$idh_first[14].'-'.$idh_first[15].'-'.$idh_first[16].'-'.$idh_first[17].'-'.$idh_first[18].'-'.$idh_first[19].'-'.$idh_first[20].'-'.$idh_first[21].'-'.$idh_first[22].'<br />'
					.$idh_second_ready[0].'-'.$idh_second_ready[1].'-'.$idh_second_ready[2].'-'.$idh_second_ready[3].'-'.$idh_second_ready[4].'-'.$idh_second_ready[5].'-'.$idh_second_ready[6].'-'.$idh_second[7].'-'.$idh_second[8].'-'.$idh_second[9].'-'.$idh_second[10].'-'
						.$idh_second[11].'-'.$idh_second[12].'-'.$idh_second[13].'-'.$idh_second[14].'-'.$idh_second[15].'-'.$idh_second[16].'-'.$idh_second[17].'-'.$idh_second[18].'-'.$idh_second[19].'-'.$idh_second[20].'-'.$idh_second[21].'-'.$idh_second[22].'<br />'
					.$idh_axis[0].'-'.$idh_axis[1].'-'.$idh_axis[2].'-'.$idh_axis[3].'-'.$idh_axis[4].'-'.$idh_axis[5].'-'.$idh_axis[6].'-'.$idh_axis[7].'-'.$idh_axis[8].'-'.$idh_axis[9].'-'.$idh_axis[10].'-'
						.$idh_axis[11].'-'.$idh_axis[12].'-'.$idh_axis[13].'-'.$idh_axis[14].'-'.$idh_axis[15].'-'.$idh_axis[16].'-'.$idh_axis[17].'-'.$idh_axis[18].'-'.$idh_axis[19].'-'.$idh_axis[20].'-'.$idh_axis[21].'-'.$idh_axis[22].'MAX AXIS: '.$max_axis.'<br />'; */
		
		};
		
		// IMPRIMIMOS EL FORMULARIO
		
        $output .=
            '</div>
						<div class="anp" id="ins1"><div align="center"><h2>' . __('FIRST', 'investment-decision') . '</h2></div>
						<div class="interno">
						<div>
							<label>' . __('Starting Expenditure($)', 'investment-decision') . '<font color="orange" size="1"> (*)</font><br />
								<input type="float" name="capital1" value="' . $capital1 . '" />
							</label>' .
							(isset($err1['capital1']) ? '<p class="idh_error">' . $err1['capital1'] . '</p>' : '') .
						'</div>
						<div>
							<label>' . __('Coupon/Net-Income($)', 'investment-decision') . '<font color="orange" size="1"> (*)</font><br />
								<input type="float" name="cupon1" value="' . $cupon1 . '" />
							</label>' .
							(isset($err1['cupon1']) ? '<p class="idh_error">' . $err1['cupon1'] . '</p>' : '') .
						'</div>
						<div>
							<label>' . __('Nº of Payments to be Received', 'investment-decision') . '<font color="orange" size="1"> (*)</font><br />
								<input type="number" name="numero_de_pagos1" value="' . $numero_de_pagos1 . '" />
							</label>' .
							(isset($err1['numero_de_pagos1']) ? '<p class="idh_error">' . $err1['numero_de_pagos1'] . '</p>' : '') .
						'</div>
						<div>
							<label>' . __('Amortization(%)', 'investment-decision') . '<br /><font size="1">(' . __('Default', 'investment-decision') . ': 0 - ' . __('Use "100" for Bonds', 'investment-decision') . ')</font><br />
								<input type="float" name="amortizacion1" value="' . $amortizacion1 . '" />
							</label>' .
							(isset($err1['amortizacion1']) ? '<p class="idh_error">' . $err1['amortizacion1'] . '</p>' : '') .
						'</div>
						<div>
							<label>' . __('Market Price(%)', 'investment-decision') . '<br /><font size="1">(' . __('Default', 'investment-decision') . ': 100)</font><br />
								<input type="float" name="precio1" value="' . $precio1 . '" />
							</label>' .
							(isset($err1['precio1']) ? '<p class="idh_error">' . $err1['precio1'] . '</p>' : '') .
						'</div>
						</div>
						<div>
							<label>' . __('Frequency', 'investment-decision') . '<br />
								<select name="frequency1">
								  <option value="annual" '.$idh_frequency_1_0.' >' . __('Annual', 'investment-decision') . '</option>
								  <option value="biannual" '.$idh_frequency_1_1.' >' . __('Biannual', 'investment-decision') . '</option>
								  <option value="quarterly" '.$idh_frequency_1_2.' >' . __('Quarterly', 'investment-decision') . '</option>
								  <option value="monthly" '.$idh_frequency_1_3.' >' . __('Monthly', 'investment-decision') . '</option>
								</select>
							</label>
						</div><font color="orange" size="1">(*) ' . __('Required', 'investment-decision') . '</font><br />
						<div class="separator"><div>' . __('OR', 'investment-decision') . '</div></div><br />
						<div>
							<label>' . __('Internal Rate of Return(%)', 'investment-decision') . '<br />
								<input type="float" name="rend1" value="' . $_POST['rend1'] . '" />
							</label>
						</div>
						<font color="orange" size="1">' . __('Adding manual IRR will ignore previous cells', 'investment-decision') . '</font>
						</div>

						<div class="anp" id="ins2"><div align="center"><h2>' . __('SECOND', 'investment-decision') . '</h2></div>
						<div class="interno">
						<div>
							<label>' . __('Starting Expenditure($)', 'investment-decision') . '<font color="orange" size="1"> (*)</font><br />
								<input type="float" name="capital2" value="' . $capital2 . '" />
							</label>' .
							(isset($err2['capital2']) ? '<p class="idh_error">' . $err2['capital2'] . '</p>' : '') .
						'</div>
						<div>
							<label>' . __('Coupon/Net-Income($)', 'investment-decision') . '<font color="orange" size="1"> (*)</font><br />
								<input type="float" name="cupon2" value="' . $cupon2 . '" />
							</label>' .
							(isset($err2['cupon2']) ? '<p class="idh_error">' . $err2['cupon2'] . '</p>' : '') .
						'</div>
						<div>
							<label>' . __('Nº of Payments to be Received', 'investment-decision') . '<font color="orange" size="1"> (*)</font><br />
								<input type="number" name="numero_de_pagos2" value="' . $numero_de_pagos2 . '" />
							</label>' .
							(isset($err2['numero_de_pagos2']) ? '<p class="idh_error">' . $err2['numero_de_pagos2'] . '</p>' : '') .
						'</div>
						<div>
							<label>' . __('Amortization(%)', 'investment-decision') . '<br /><font size="1">(' . __('Default', 'investment-decision') . ': 0 - ' . __('Use "100" for Bonds', 'investment-decision') . ')</font><br />
								<input type="float" name="amortizacion2" value="' . $amortizacion2 . '" />
							</label>' .
							(isset($err2['amortizacion2']) ? '<p class="idh_error">' . $err2['amortizacion2'] . '</p>' : '') .
						'</div>
						<div>
							<label>' . __('Market Price(%)', 'investment-decision') . '<br /><font size="1">(' . __('Default', 'investment-decision') . ': 100)</font><br />
								<input type="float" name="precio2" value="' . $precio2 . '" />
							</label>' .
							(isset($err2['precio2']) ? '<p class="idh_error">' . $err2['precio2'] . '</p>' : '') .
						'</div>
						</div>
						<div>
							<label>' . __('Frequency', 'investment-decision') . '<br />
								<select name="frequency2">
								  <option value="annual" '.$idh_frequency_2_0.' >' . __('Annual', 'investment-decision') . '</option>
								  <option value="biannual" '.$idh_frequency_2_1.' >' . __('Biannual', 'investment-decision') . '</option>
								  <option value="quarterly" '.$idh_frequency_2_2.' >' . __('Quarterly', 'investment-decision') . '</option>
								  <option value="monthly" '.$$idh_frequency_2_3.' >' . __('Monthly', 'investment-decision') . '</option>
								</select>
							</label>
						</div><font color="orange" size="1">(*) ' . __('Required', 'investment-decision') . '</font><br />
						<div class="separator"><div>' . __('OR', 'investment-decision') . '</div></div><br />
						<div>
							<label>' . __('Internal Rate of Return(%)', 'investment-decision') . '<br />
								<input type="float" name="rend2" value="' . $_POST['rend2'] . '" />
							</label>
						</div>
						<font color="orange" size="1">' . __('Adding manual IRR will ignore previous cells', 'investment-decision') . '</font>
						</div>			
            <div>
                <input type="submit" value="' . __('Submit', 'investment-decision') . '" class="wpb_button" />
            </div>
			</form>';
        
        return $output;
		
    }

}
		

$calc = new investment_decision();

/**
 * Adds IDH_Widget widget.
 */
class IDH_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'idh_widget', // Base ID
			'Investment Decision Helper', // Name
			array( 'description' => __( 'Investment Decision Helper', 'investment-decision' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
        $calc = new investment_decision();

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo $calc->idh_calculator();
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( '', 'investment-decision' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

}

add_action( 'plugins_loaded', 'idh_textdomain' );

function idh_textdomain() {
  load_plugin_textdomain( 'investment-decision', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' ); 
}

add_action( 'widgets_init', create_function( '', 'register_widget( "idh_widget" );' ) );
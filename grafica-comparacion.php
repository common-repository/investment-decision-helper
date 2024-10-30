<?php
session_start();
require_once ('jpgraph/src/jpgraph.php');
require_once ('jpgraph/src/jpgraph_line.php');

$datos1 = stripslashes($HTTP_GET_VARS["serie1"]); $datos1 = urldecode($datos1);  $datos1 = unserialize($datos1);
$datos2 = stripslashes($HTTP_GET_VARS["serie2"]); $datos2 = urldecode($datos2);  $datos2 = unserialize($datos2);
$datos_axis = stripslashes($HTTP_GET_VARS["serie3"]); $datos_axis = urldecode($datos_axis);  $datos_axis = unserialize($datos_axis);
$tags = stripslashes($HTTP_GET_VARS["etiquetas"]); $tags = urldecode($tags);  $tags = unserialize($tags);
$first = $tags[0];
$second = $tags[1];
$yaxis = $tags[2].' ($)';
$xaxis = $tags[3].' (%)';
$caption = $tags[4];


// Create the graph. These two calls are always required 
$graph = new Graph(360,240,"auto"); 
$graph->SetScale("textlin","auto","auto","auto","auto"); 
$graph->img->SetAntiAliasing(true); 
/* $graph->img->SetMargin(10,10,10,10); */
/* $graph->SetColor('lightblue'); */
$graph->SetMarginColor('#FAFAFA');
$graph->SetFrame(true,'#FAFAFA',1);
$graph->xgrid->Show(); 

//Definimos el titulo
$graph->title->Set($caption);
$graph->title->SetFont(FF_ARIAL,FS_NORMAL,14);
$graph->title->SetMargin(10);
$graph->legend->SetFont(FF_ARIAL,FS_NORMAL,12);
$graph->legend->SetColumns(2);
$graph->legend->Pos(0.10,0.97,'left','bottom');
$graph->legend->SetHColMargin(10);
$graph->legend->SetVColMargin(10);
$graph->legend->SetLineWeight(10);
$graph->legend->SetFillColor('#fafafa@0.7');

// Create the linear plot 
$lineplot1=new LinePlot($datos1); 
$lineplot1->SetWeight(3); 
$lineplot1->SetLegend($first);
$lineplot2=new LinePlot($datos2); 
$lineplot2->SetWeight(3); 
$lineplot2->SetLegend($second); 

// Setup margin and titles 
$graph->img->SetMargin(50,10,10,20);
$graph->xaxis->title->Set($xaxis); 
$graph->xaxis->title->SetFont(FF_ARIAL,FS_NORMAL,10); // Text font
$graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,10); // Text font
$graph->xaxis->SetTickLabels($datos_axis);
$graph->xaxis->SetLabelAngle(45);
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,10);
$graph->xaxis->SetTitlemargin(10); // Specifies the distance between the axis and the title
$graph->yaxis->SetTitlemargin(10); // Specifies the distance between the axis and the title
$graph->yaxis->title->Set($yaxis);
$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,10);
$graph->ygrid->SetFill(true,'white@0.5','#e6e6fa@0.5'); 
//$graph->SetShadow(); 

// Add the plot to the graph 
$graph->Add($lineplot1); 
$graph->Add($lineplot2); 

// Display the graph 
$graph->Stroke();
?>
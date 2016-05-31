<?php

function base_chart($x, $y, $size_y = 300, $step = 100, $left = 20){
	$size_x = $step+$left;
  $pos_x = $left;
  $pos_y = $size_y+20;
  
	$base = "<g class='grid'>";
  foreach ($x AS $key){
		$base .= "<line x1='".$pos_x."' x2='".$pos_x."' y1='0' y2='".$pos_y."'></line>";
  	$pos_x += $step/(count($x)-1);
	}
  	$base .= "<line x1='".$left."' x2='".$size_x."' y1='0' y2='0'></line>";
  foreach ($y AS $key){
  	$base .= "<line x1='".$left."' x2='".$size_x."' y1='".$pos_y."' y2='".$pos_y."'></line>";
  	$pos_y -= $size_y/count($y);
  }
	$base .= "</g>";
	
	
  $pos_x = $left-10;
  $pos_y = $size_y+40;
  $base .= "<g class='chart-label'>";
  if (count($x) < 10) $stop = 1;
  if (count($x) >= 10) $stop = 2;
  if (count($x) >= 20) $stop = 3;
  if (count($x) >= 30) $stop = 4;
  if (count($x) >= 40) $stop = 5;
  $i = 1;
  foreach ($x AS $key){
  	if ($i == $stop){
  		$base .= "<text x='".$pos_x."' y='".$pos_y."'>".$key."</text>";
  		$i = 0;
  	}
  	$i++;
  	$pos_x += $step/(count($x)-1);
  }
  $pos_y = $size_y+25;
  $left -= 5;
  $base .= "<text text-anchor='end' x='".$left."' y='".$pos_y."'>0</text>";
  $pos_y -= $size_y/count($y);
  foreach ($y AS $key){
  	$base .= "<text text-anchor='end' x='".$left."' y='".$pos_y."'>".init($key,'number')."</text>";
  	$pos_y -= $size_y/count($y);
  }
  $base .= "</g>";
  
	return $base;
}

function data_chart($data_points, $i, $max, $size_y = 300, $step = 100, $left = 20){
	$chart = "<polyline class='chart_line color-".$i."' points='";
	$x = $left;
	foreach ($data_points AS $key => $value){
		$y = ($size_y-($size_y/$max)*($value))+20;
		$chart .= $x.",".$y." ";
  	$x += $step/(count($data_points)-1);
	}
	$chart .= "' />";
	
	$chart .= "<g class='data color-".$i."'>";
	$x = $left;
	foreach ($data_points AS $key => $value){
		$y = ($size_y-($size_y/$max)*($value)+20);
		$chart .= "<circle cx='".$x."' cy='".$y."' data-info = '".$value."' r='4'><title>".init($value,'number')."</title></circle>";
  	$x += $step/(count($data_points)-1);
	}
	$chart .= "</g>";
	
	return $chart;
}

function print_chart($params, $line, $option = NULL, $select = 0){
	global $conn;
	$height = 300;
	$chart_height = 360;
	$max_width = 800;
	$chart_width = 900;
	if (!isset($params['FILTER'])) $params['FILTER'] = NULL;
	elseif (!isset($params['SORT']))  $params['SORT'] = NULL;
	if (!isset($params['GROUP']))  $params['GROUP'] = NULL;
	if (!isset($params['ADD']))  $params['ADD'] = NULL;
	$result = db_mysql_query(select_tbody($params['TABLE'], $params['SORT'], $params['FILTER'], $params['GROUP'], $params['ADD']), $conn);
	$max = 0;
	$base_arr = array();
	$count = db_mysql_num_rows($result);
	while($arr = db_mysql_fetch_array($result)){
		if ($arr[$line['VAL']] >= $max) $max = $arr[$line['VAL']];
		if ($line['DATA'] == 'date'){
			$date = explode('-', $arr['datefromto']);
			if ($option == 'hour'){
				array_push($base_arr, date('H', $date[0]));
				$data_arr[$arr[$line['KEY']]][date('H', $date[0])] = $arr[$line['VAL']];
				if ($count == 1) {
					array_unshift($base_arr, date('H' , strtotime('-1 hour', $date[0])));
					array_push($base_arr, date('H' , strtotime('+1 hour', $date[0])));
				}
			}
			if ($option == 'day'){
				array_push($base_arr, date('d.m', $date[0]));
				$data_arr[$arr[$line['KEY']]][date('d.m', $date[0])] = $arr[$line['VAL']];
				if ($count == 1) {
					array_unshift($base_arr, date('d.m' , strtotime('-1 day', $date[0])));
					array_push($base_arr, date('d.m' , strtotime('+1 day', $date[0])));
				}
			}
			if ($option == 'month'){
				array_push($base_arr, date('F', $date[0]));
				$data_arr[$arr[$line['KEY']]][date('F', $date[0])] = $arr[$line['VAL']];
				if ($count == 1) {
					array_unshift($base_arr, date('F' , strtotime('-1 month', $date[0])));
					array_push($base_arr, date('F' , strtotime('+1 month', $date[0])));
				}
			}
			if ($option == 'year'){
				array_push($base_arr, date('Y', $date[0]));
				$data_arr[$arr[$line['KEY']]][date('Y', $date[0])] = $arr[$line['VAL']];
				if ($count == 1) {
					array_unshift($base_arr, date('Y' , strtotime('-1 year', $date[0])));
					array_push($base_arr, date('Y' , strtotime('+1 year', $date[0])));
				}
			}
		} else {
			array_push($base_arr, $arr[$line['DATA']]);
			$data_arr[$arr[$line['KEY']]][$arr[$line['DATA']]] = $arr[$line['VAL']];
		}
	}
	foreach ($data_arr AS $key => $value){
		if (count($value) == 1) {
			array_unshift($data_arr[$key], 0);
			array_push($data_arr[$key], 0);
		}
	}
	
	print "<div class='chart-wrapper'>";
	print "<svg version='1.2' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' class='chart' aria-labelledby='title' role='img' height='100%' width='100%' viewBox='0 0 ".$chart_width." ".$chart_height."'>";
	
	print base_chart($base_arr, array(floor($max/2),$max), $height, $max_width, 50);
	if ($select != 0) print data_chart($data_arr[$select], 1, $max, $height, $max_width, 50);
	else {
		$i = 1;
		foreach ($data_arr AS $key => $value){
			print data_chart($value, $i, $max, $height, $max_width, 50);
			$i++;
		}
	}
	print "</svg>";
	if (count($data_arr) > 1){
		print "<ul class='legend'>";
		$i = 1;
		foreach ($data_arr AS $key => $value){
			print "<li><div class='color-box color-".$i."'></div>".$key."</li>";
			$i++;
		}
		print "</ul>";
	}
	print "</div>";
}
?>
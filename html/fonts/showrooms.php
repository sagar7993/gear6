<?php
    require_once("../includes/db/connection.php"); 
function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}
    $query2 = "SELECT * FROM area";
    $query_result2 = mysql_query($query2,$con)
    or die("Invalid query: " . mysql_error());
    $O_row=" ";
   
   
    
    $areas="'Domlur'";
   
    while ($rw1 = mysql_fetch_array($query_result2)) {
          if(distance($_POST['lati'],$_POST['longi'],$rw1['lati'],$rw1['longi'],"M")<3 and $rw1['area']!='Domlur'){
            $areas = $areas.",'".$rw1['area']."'";
        }
    }

    $stack = array();

    $queryx = "SELECT * FROM serv_center where area in (".$areas.") AND company='".$_POST['company']."'";
    $queryx_result = mysql_query($queryx,$con)
    or die("Invalid query: " . mysql_error());
    while ($rw1 = mysql_fetch_array($queryx_result)) {

        $query = "SELECT (rate_1*1+rate_2*2+rate_3*3+rate_4*4+rate_5*5)/(rate_1+rate_2+rate_3+rate_4+rate_5) as rate FROM rating where S_id='".$rw1[S_id]."'";
        $query_result = mysql_query($query,$con)
        or die("Invalid query: " . mysql_error());
        $qual = mysql_fetch_array($query_result);
        $q=$qual['rate'];
     


        array_push($stack, array(
        "lati" => $rw1['lati'],
        "name" => $rw1['S_name'],
        "longi" => $rw1['longi'],
        "rate" => $q,
        "address" => $rw1['address'],
        "sid" => $rw1['S_id']
     ));

    }


$json = json_encode($stack);
echo $json;
?>

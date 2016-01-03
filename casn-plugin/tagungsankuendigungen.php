<?php 

/* changed to lib/EasyRdf.php and SD version 3.1 */

require_once("lib/EasyRdf.php");

function tagungen($atts) {
  $query = '
PREFIX sd: <http://symbolicdata.org/Data/Model#>
PREFIX ical: <http://www.w3.org/2002/12/cal/ical#>
select distinct ?a ?l ?from ?to ?loc ?description ?url
from <http://symbolicdata.org/Data/UpcomingConferences/>
Where { 
?a a sd:UpcomingConference .
?a rdfs:label ?l .
optional { ?a  ical:dtstart ?from . } 
optional { ?a  ical:dtend ?to . } 
optional { ?a  ical:location ?loc . } 
optional { ?a ical:description ?description . } 
optional { ?a ical:url ?url . } 
} order by (?from) 
';
  
  $sparql = new EasyRdf_Sparql_Client('http://symbolicdata.org:8890/sparql');
  $result=$sparql->query($query); // a SELECT query returns an EasyRdf_Sparql_Result
  /* generate data structure for output table */
  $out="";
  foreach ($result as $v) {
    $a=$v->{'a'};
    $label=$v->{'l'};
    $from=date_format(date_create($v->{'from'}),"d.m.Y");
    $to=date_format(date_create($v->{'to'}),"d.m.Y");
    $loc=$v->{'loc'};
    $url="";
    if (isset($v->{'url'})) { $url=$v->{'url'}; } 
    $description=$v->{'description'};
    $out.='
<h2> <a href="'.$a.'">'.$label.'</a></h2>
<div>Vom '.$from.' bis '.$to.' in '.$loc.'.</div>
<p/><div> '.$description.'</div><p/>
';
    if ($url!="") { 
	$out.='<div> URL der Tagung: <a href="'.$url.'">'.$url.'</a></div><p/> 
' ;
    }
  }
  return $out ; 		
}

if ( ! defined( 'ABSPATH' ) ) { // make test
  $a=array(); echo '<meta charset="utf8">'.tagungen($a);
} else {
  add_shortcode( 'tagungen', 'tagungen' );
}
?>

<?php 

require_once("lib/EasyRdf.php");

function upcomingConferences() {  
  EasyRdf_Namespace::set('ical', 'http://www.w3.org/2002/12/cal/ical#');
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');

  $query = '
PREFIX sd: <http://symbolicdata.org/Data/Model#>
PREFIX ical: <http://www.w3.org/2002/12/cal/ical#>
construct { ?a ?b ?c . ?a sd:Series ?a2 . }
from <http://symbolicdata.org/Data/UpcomingConferences/>
from <http://symbolicdata.org/Data/ConferenceSeries/>
Where { ?a a sd:UpcomingConference ; ?b ?c . 
optional {?a sd:toConferenceSeries ?a1 . ?a1 rdfs:label ?a2 . }
} 
';
  
  $sparql = new EasyRdf_Sparql_Client('http://symbolicdata.org:8890/sparql');
  $result=$sparql->query($query); // a CONSTRUCT query returns an EasyRdf_Graph
  //echo $result->dump("turtle");
  /* generate data structure for output table */
  $s=array();
  foreach ($result->allOfType("sd:UpcomingConference") as $v) {
    $a=$v->getUri();
    $label=$v->get('rdfs:label'); 
    $from=date_format(date_create($v->get('ical:dtstart')),"Y/m/d");
    $to=date_format(date_create($v->get('ical:dtend')),"Y/m/d");
    $loc=$v->get('ical:location');
    $series=$v->get('sd:Series');
    $description=$v->get('ical:description');
    $out='
<h2> <a href="'.$a.'">'.$label.'</a></h2>
<dl><dt> '.$from.' &ndash; '.$to.' in '.$loc.'.</dt>
<dd> <strong>Announcement:</strong> '.$description.'</dd>
';
    foreach($v->all('ical:url') as $url) {
	$out.='<dd> Conference URL: <a href="'.$url.'">'.$url.'</a></dd>' ;
    }
    if (!empty($series)) {
	$out.='<dd> Conference Series: '.$series.'</dd>' ;
    } 
    $out.='</dl>';
    $s["$from.$a"]=$out;
  }
  ksort($s);
  return join($s,"\n") ; 		
}

function pastConferences() {  
  EasyRdf_Namespace::set('ical', 'http://www.w3.org/2002/12/cal/ical#');
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');

  $jahr  = (empty($_GET['year']))  ? "2012" : $_GET['year'] ;
  
  $query = '
PREFIX sd: <http://symbolicdata.org/Data/Model#>
PREFIX ical: <http://www.w3.org/2002/12/cal/ical#>
construct { ?a ?b ?c . ?a sd:Series ?a2 . }
from <http://symbolicdata.org/Data/PastConferences/>
from <http://symbolicdata.org/Data/ConferenceSeries/>
Where { ?a a sd:Conference ; ?b ?c ; ical:dtstart ?d . 
optional {?a sd:toConferenceSeries ?a1 . ?a1 rdfs:label ?a2 . }
filter regex(?d, "'.$jahr.'")
} ';
  
  $sparql = new EasyRdf_Sparql_Client('http://symbolicdata.org:8890/sparql');
  $result=$sparql->query($query); // a CONSTRUCT query returns an EasyRdf_Graph
  //echo $result->dump("turtle");
  /* generate data structure for output table */
  $s=array();
  foreach ($result->allOfType("sd:Conference") as $v) {
    $a=$v->getUri();
    $label=$v->get('rdfs:label'); 
    $from=date_format(date_create($v->get('ical:dtstart')),"Y/m/d");
    $to=date_format(date_create($v->get('ical:dtend')),"Y/m/d");
    $loc=$v->get('ical:location');
    $series=$v->get('sd:Series');
    $description=$v->get('ical:description');
    $out='
<p><dl> <dt><strong><a href="'.$a.'">'.$label.'</a></strong></dt>
<dd>'.$from.' &ndash; '.$to.' in '.$loc.'.</dd>';
    foreach($v->all('ical:url') as $url) {
	$out.='<dd> Conference URL: <a href="'.$url.'">'.$url.'</a></dd>' ;
    }
    if (!empty($series)) {
	$out.='<dd> Conference Series: '.$series.'</dd>' ;
    } 
    $out.='</dl></p>';
    $s["$from.$a"]=$out;
  }
  krsort($s);
  return join($s,"\n") ; 		
}

// ---- test ----
//echo upcomingConferences();
//echo pastConferences();
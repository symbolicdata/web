<?php 

/* changed to lib/EasyRdf.php and CONSTRUCT query */

require_once("lib/EasyRdf.php");

function dissertationen($atts) {
  EasyRdf_Namespace::set('bibo', 'http://purl.org/ontology/bibo/');
  EasyRdf_Namespace::set('bibo_degrees', 'http://purl.org/ontology/bibo/degrees/');
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');
  EasyRdf_Namespace::set('dct', 'http://purl.org/dc/terms/');
  $dissertations=getDissertations();
  $people=getPeople();
  return displayAll($dissertations,$people);
}

function getPeople() {
  $query = '
PREFIX sd: <http://symbolicdata.org/Data/Model#>
construct { ?a a foaf:Person; foaf:name ?name . }
from <http://symbolicdata.org/Data/People/>
Where {
?a a foaf:Person; foaf:name ?name .
} 
LIMIT 20
';
  $sparql = new EasyRdf_Sparql_Client('http://symbolicdata.org:8890/sparql');
  $result = $sparql->query($query); // a CONSTRUCT query returns an EasyRdf_Graph  
  //echo $result->dump("turtle");
  return $result ; 
}

function getDissertations() {

  $query = '
PREFIX sd: <http://symbolicdata.org/Data/Model#>
PREFIX bibo: <http://purl.org/ontology/bibo/>
PREFIX bibo_degrees: <http://purl.org/ontology/bibo/degrees/>
PREFIX dct: <http://purl.org/dc/terms/>
construct {
?a a bibo:Thesis .
?a dct:creator ?c . 
?a dct:title ?title . 
?a bibo:degree ?degree . 
?a bibo:institution ?inst . 
?a dct:date ?year . 
?a sd:hasSupervisor ?s .
?a sd:hasReviewer ?r . 
?a sd:hasURL ?url . 
}
from <http://symbolicdata.org/Data/Dissertations/>
Where {
?a a bibo:Thesis ; dct:creator ?c ; dct:title ?title; 
dct:date ?year; bibo:degree ?degree. 
optional { ?a bibo:institution ?inst .}
optional { ?a sd:hasSupervisor ?s .}
optional { ?a sd:hasReviewer ?r . }
optional { ?a sd:hasURL ?url . }
} 
';
  $sparql = new EasyRdf_Sparql_Client('http://symbolicdata.org:8890/sparql');
  $result = $sparql->query($query); // a CONSTRUCT query returns an EasyRdf_Graph
  //echo $result->dump("turtle");
  return $result ; 
}

function displayAll($result,$people) {
  $a=array(); $b=array();
  foreach ($result->allOfType("bibo:Thesis") as $v) {  
    $year=$v->get('dct:date');
    $content=displayThesis($v,$people);
    if ($v->get('bibo:degree') == 'bibo_degrees:habil') {
      $a[]=array("location" => "$year", "content" => $content);
    } else { 
      $b[]=array("location" => "$year", "content" => $content);
    }
  }  
  array_multisort($a, SORT_DESC);
  $out="<h3>Habilitations</h3>\n\n"; foreach($a as $v) { $out.=$v["content"]; }
  array_multisort($b, SORT_DESC);
  $out="<h3>Promotions</h3>\n\n"; foreach($b as $v) { $out.=$v["content"]; }
  return $out;
}

function displayThesis($v,$people) {
  $cn=$people->get($v->get('dct:creator'),'foaf:name');
  $title=$v->get('dct:title');
  $year=$v->get('dct:date');
  $a=array(); 
  foreach ($v->all('sd:hasReviewer') as $rev) {
    $a[]=$people->get($rev->getUri(),"foaf:name");
  }
  $reviewers=join(", ",$a);
  $a=array(); 
  foreach ($v->all('sd:hasSupervisor') as $rev) {
    $a[]=$people->get($rev->getUri(),"foaf:name");
  }
  $supervisors=join(", ",$a);
  $out='
<p><dt> '.$cn.':<br/> '.$title.' </dt></p>'
	.' <table><tr><td width="40%"> <strong>Jahr der Verteidigung:</strong> </td><td>'.$year.' </td></tr> '
	.' <tr><td width="40%"> <strong>Betreuer:</strong> </td><td>'.$supervisors.' </td></tr> '
	.' <tr><td width="40%"> <strong>Gutachter:</strong> </td><td>'.$reviewers.' </td></tr> '
	.' <tr><td colspan="2" align="left"> <a href="'.$v.'">Eintrag im CASN</a> </td></tr></table> ' ;
  return '<dl>'.$out.'</dl>' ; 	
}

if ( ! defined( 'ABSPATH' ) ) { // make test
  $a=array(); echo '<meta charset="utf8">'.dissertationen($a);
} else {
  add_shortcode( 'dissertationen', 'dissertationen' );
}

?>

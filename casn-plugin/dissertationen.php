<?php 

/* changed to lib/EasyRdf.php and local data */

require_once("lib/EasyRdf.php");

function dissertationen($atts) {
  EasyRdf_Namespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
  EasyRdf_Namespace::set('bibo', 'http://purl.org/ontology/bibo/');
  EasyRdf_Namespace::set('bibo_degrees', 'http://purl.org/ontology/bibo/degrees/');
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');
  EasyRdf_Namespace::set('dct', 'http://purl.org/dc/terms/');
  $diss = new EasyRdf_Graph("http://fachgruppe-computeralgebra.de/rdf/Dissertationen/");
  $diss->parseFile("http://fachgruppe-computeralgebra.de/rdf/Dissertationen.rdf");
  $people = new EasyRdf_Graph("http://fachgruppe-computeralgebra.de/rdf/People/");
  $people->parseFile("http://fachgruppe-computeralgebra.de/rdf/People.rdf");
  $groups = new EasyRdf_Graph("http://fachgruppe-computeralgebra.de/rdf/WorkingGroups/");
  $groups->parseFile("http://fachgruppe-computeralgebra.de/rdf/WorkingGroups.rdf");

  $a=array();
  foreach ($diss->allOfType("bibo:Thesis") as $v) {  
    $year=$v->get('dct:date');
    $content=displayThesis($v,$people,$groups);
    $a[]=array("location" => "$year", "content" => $content);
  }  
  array_multisort($a, SORT_DESC);
  $out=''; foreach($a as $v) { $out.=$v["content"]; }
  return $out;
}

function displayThesis($v,$people,$groups) {
  $cn=$people->get($v->get('dct:creator'),'foaf:name');
  $loc='Unknown';
  $loc=$groups->get($v->get('sd:affiliates'),'skos:prefLabel');
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
<p><dt> '.$cn.':<br/> '.$title.' </dt><dt> '.$loc.' </dt></p>'
	.' <table><tr><td width="40%"> <strong>Jahr der Verteidigung:</strong> </td><td>'.$year.' </td></tr> '
	.' <tr><td width="40%"> <strong>Betreuer:</strong> </td><td>'.$supervisors.' </td></tr> '
	.' <tr><td width="40%"> <strong>Gutachter:</strong> </td><td>'.$reviewers.' </td></tr> </table> ' ;
  return '<dl>'.$out.'</dl>' ; 	
}


if ( ! defined( 'ABSPATH' ) ) { // make test
  $a=array(); echo '<meta charset="utf8">'.dissertationen($a);
} else {
  add_shortcode( 'dissertationen', 'dissertationen' );
}

?>

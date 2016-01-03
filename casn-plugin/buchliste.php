<?php 

require_once("lib/EasyRdf.php");

function buchliste($atts) {
  EasyRdf_Namespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
  EasyRdf_Namespace::set('bibo', 'http://purl.org/ontology/bibo/');
  EasyRdf_Namespace::set('bibo_degrees', 'http://purl.org/ontology/bibo/degrees/');
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');
  EasyRdf_Namespace::set('dcterms', 'http://purl.org/dc/terms/');
  $buchliste = new EasyRdf_Graph("http://fachgruppe-computeralgebra.de/rdf/Buchliste/");
  $buchliste->parseFile("http://fachgruppe-computeralgebra.de/rdf/Buchliste-Alt.rdf");

  $a=array();
  foreach ($buchliste->allOfType("sd:CASBook") as $v) {  
    $year=$v->get('dcterms:issued'); 
    $content=displayBook($v);
    $a[]=array("location" => "$year", "content" => $content);
  }  
  array_multisort($a, SORT_DESC);
  $out=''; foreach($a as $v) { $out.=$v["content"]; }
  return $out;
}

function displayBook($v) {
  $authors=$v->join('dcterms:creator',"; ");
  $editors=$v->join('sd:editor',"; ");
  $title=$v->get('dcterms:title');
  $isbn=$v->get('dcterms:identifier');
  $year=$v->get('dcterms:issued');
  $serie=$v->get('dcterms:partOf');
  $abstract=$v->get('dcterms:abstract');
  $comment=$v->get('rdfs:comment');
  $out='<p>';
  if ($authors) { $out.='<strong>Autoren:</strong> '.$authors; }
  else { $out.='<strong>Editoren:</strong> '.$editors;}
  $out.='<br/> <strong>Titel:</strong> '.$title;
  if ($year) { $out.='<br/> <strong>Erscheinungsjahr:</strong> '.$year;}
  if ($serie) { $out.='<br/> '.$serie;}
  if ($isbn) { $out.='<br/> '.$isbn;}
  if ($abstract) { $out.='<br/> '.$abstract;}
  if ($comment) { $out.='<br/> '.$comment;}
  return $out.' </p>'."\n\n" ; 	
}


if ( ! defined( 'ABSPATH' ) ) { // make test
  $a=array(); echo '<meta charset="utf8">'.buchliste($a);
} else {
  add_shortcode( 'buchliste', 'buchliste' );
}

?>

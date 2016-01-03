<?php 

/* changed to lib/EasyRdf.php and local RDF data */

require_once("lib/EasyRdf.php");

function arbeitsgruppen($atts) {
  EasyRdf_Namespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
  EasyRdf_Namespace::set('org', 'http://www.w3.org/ns/org#');
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');
  $people = new EasyRdf_Graph("http://fachgruppe-computeralgebra.de/rdf/People/");
  $people->parseFile("http://fachgruppe-computeralgebra.de/rdf/People.rdf");
  $groups = new EasyRdf_Graph("http://fachgruppe-computeralgebra.de/rdf/WorkingGroups/");
  $groups->parseFile("http://fachgruppe-computeralgebra.de/rdf/WorkingGroups.rdf");
  $a=array(); 
  foreach ($groups->allOfType("sd:WorkingGroup") as $v) {
    $location=$v->get('sd:hasLocation');
    $content=displayWorkingGroup($v,$people); 
    $a[]=array("location" => "$location", "content" => $content); 
  }
  array_multisort($a); 
  // print_r($a);
  $out=''; foreach($a as $v) { $out.=$v["content"]; }
  return $out;
}

function displayWorkingGroup($v,$people) {
  $name=$v->get('skos:prefLabel');
  $url=$v->get('foaf:homepage');
  $interests=$v->join('foaf:topic_interest',", ");
  $a=array(); 
  foreach ($v->all('org:hasMember') as $rev) {
    $a[]=$people->get($rev->getUri(),"foaf:name");
  }
  $members=join(", ",$a);
  $out='<h2>'.$name.'</h2>
<ul>
 <li>Arbeitsgebiete: '.$interests.'</li>
 <li>Mitglieder: '.$members.'</li>
 <li>URL: <a href="'.$url.'">'.$url.'</a> </li>
</ul>
' ;
  return $out ; 		
}

if ( ! defined( 'ABSPATH' ) ) { // make test
  $a=array(); echo '<meta charset="utf8">'.arbeitsgruppen($a);
} else {
  add_shortcode( 'arbeitsgruppen', 'arbeitsgruppen' );
}

?>

<?php 

/* changed to lib/EasyRdf.php and CONSTRUCT query */

require_once("lib/EasyRdf.php");

function projekte($atts) {  
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');
  $people = new EasyRdf_Graph("http://fachgruppe-computeralgebra.de/rdf/People/");
  $people->parseFile("http://fachgruppe-computeralgebra.de/rdf/People.rdf");
  $projekte = new EasyRdf_Graph("http://fachgruppe-computeralgebra.de/rdf/SPP-Projekte/");
  $projekte->parseFile("http://fachgruppe-computeralgebra.de/rdf/SPP-Projekte.rdf");
  // $projekte->parseFile("/home/graebe/ComputerAlgebra/web/rdf/SPP-Projekte.rdf");

  $out="";
  foreach ($projekte->allOfType("sd:Project") as $v) {   
    $uri=$v->getUri();
    $a=array();
    foreach ($v->all('sd:projectLeader') as $rev) {
      $a[]=$people->get($rev->getUri(),"foaf:name");
    }
    $name=join(", ",$a);
    $title=$v->getLiteral('sd:projectTitle');
    $description=$v->getLiteral('sd:projectDescription');
    $out.='
<dl><dt> '.$name.' </dt> <dt> <strong>'.$title.'</strong> </dt> <dd> '.$description.' </dd> </dl> 

' ;
  }
  return $out ; 	
}

if ( ! defined( 'ABSPATH' ) ) { // make test
  $a=array(); echo '<meta charset="utf8">'.projekte($a);
} else {
  add_shortcode( 'spp-projekte', 'projekte' );
}
?>

<?php 

require_once("lib/EasyRdf.php");

function getPeople($name,$affil) {  
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');

  $query = '
PREFIX sd: <http://symbolicdata.org/Data/Model#>
construct { ?a ?b ?c . }
from <http://symbolicdata.org/Data/People/>
from <http://symbolicdata.org/Data/ZBMathPeople/>
Where { 
?a a foaf:Person ; ?b ?c; foaf:name $n . 
optional { ?a sd:affiliation $f . } 
optional { ?a sd:hasZMathSearchString $z . } 
filter regex(?n, "'.$name.'","i")
filter regex(?f, "'.$affil.'","i")
}  
LIMIT 100';
  
  $sparql = new EasyRdf_Sparql_Client('http://symbolicdata.org:8890/sparql');
  $result=$sparql->query($query); // a CONSTRUCT query returns an EasyRdf_Graph
  //echo $result->dump("turtle");
  /* generate data structure for output table */
  $s=array();
  foreach ($result->allOfType("foaf:Person") as $v) {
    $a=$v->getUri();
    $label=$v->get('foaf:name');
    $loc=$v->get('sd:affiliation');
    $zb=$v->get('sd:hasZMathSearchString');
    $out='<p><dl> <dt><strong><a href="'.$a.'">'.$label.'</a></strong></dt>
';
    if (!empty($loc)) { $out.='<dd>Affiliation: '.$loc.'.</dd>'; }
    if (!empty($zb)) { $out.='<dd>ZBMath Author Code: <a href="'.$zb.'">'.$zb.'</a></dd>'; }
    $out.='</dl></p>';
    $s["$a"]=$out;
  }
  ksort($s);
  return '<p>Search for entries with foaf:name contains "'.$name
      .'" and sd:affiliation contains "'.$affil.'"</p>'. join($s,"\n") ; 		
}

// ---- test ----
// echo getPeople($_GET['name'],$_GET['affil']);
//echo getPeople("G","U");

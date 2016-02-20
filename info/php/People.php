<?php 

require_once("lib/EasyRdf.php");

function getPeople($name,$affil) {  
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');

  $query = '
PREFIX sd: <http://symbolicdata.org/Data/Model#>
construct { ?a ?b ?c . }
from <http://symbolicdata.org/Data/People/>
from <http://symbolicdata.org/Data/ZBMathPeople/>
from <http://symbolicdata.org/Data/PersonalProfiles/>
Where { 
?a a foaf:Person ; ?b ?c; foaf:name $n . 
optional { ?a sd:affiliation $f . } 
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
    $hp=$v->get('foaf:homepage');
    $zb=$v->get('sd:hasZMathSearchString');
    $pp=$v->get('sd:hasPersonalProfile');
    $out='<p><dl> <dt><strong><a href="'.$a.'">'.$label.'</a></strong></dt>
';
    if (!empty($loc)) { $out.='<dd>Affiliation: '.$loc.'.</dd>'; }
    if (!empty($hp)) { $out.='<dd>Homepage: <a href="'.$hp.'">'.$hp.'</a></dd>'; }
    if (!empty($pp)) { $out.='<dd>Personal FOAF Profile: <a href="'.$pp.'">'.$pp.'</a></dd>'; }
    if (!empty($zb)) { $out.='<dd>ZBMath Author Code: <a href="'.$zb.'">'.$zb.'</a></dd>'; }
    $out.='</dl></p>';
    $s["$a"]=$out;
  }
  ksort($s);
  return '<h4>List of entries with foaf:name containing "'.$name
      .'" and sd:affiliation containing "'.$affil.'"</h4>'. join($s,"\n") ; 		
}

// ---- test ----
// echo getPeople($_GET['name'],$_GET['affil']);
// echo getPeople("G","U");

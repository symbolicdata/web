<?php 

/* changed to lib/EasyRdf.php and CONSTRUCT query */

require_once("lib/EasyRdf.php");

function casystems() {
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');
  EasyRdf_Namespace::set('dct', 'http://purl.org/dc/terms/');
  EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
  $people = new EasyRdf_Graph("http://symbolicdata.org/Data/People/");
  $people->parseFile("http://symbolicdata.org/rdf/People.rdf");
  //$people->parseFile("/home/graebe/git/SD/web/rdf/People.rdf");
  $systems = new EasyRdf_Graph("http://symbolicdata.org/Data/CA-Systems/");
  $systems->parseFile("http://symbolicdata.org/rdf/People.rdf");
  //$systems->parseFile("/home/graebe/git/SD/web/rdf/CA-Systems.rdf");
  $out=displaySystems($systems,$people);
  return $out;
}

function getSystems() {
  $query = '
PREFIX sd: <http://symbolicdata.org/Data/Model#>
PREFIX dct: <http://purl.org/dc/terms/>
construct {
?a ?b ?c . ?d ?e ?f . 
}
from <http://symbolicdata.org/Data/CA-Systems/>
Where {
?a a sd:CAS ; ?b ?c; rdfs:seeAlso ?d .
?d ?e ?f . 
} 
';
  $sparql = new EasyRdf_Sparql_Client('http://symbolicdata.org:8890/sparql');
  $result = $sparql->query($query); // a CONSTRUCT query returns an EasyRdf_Graph
  //echo $result->dump("turtle");
  return $result ; 
}

function displaySystems($result,$people) {
  $a=array(); 
  foreach ($result->allOfType("sd:CAS") as $v) { 
    $title=$v->get('rdfs:label');
    $content=displaySystem($v,$people);
    $a[]=array("link" => "$title", "content" => $content);
  }  
  array_multisort($a);
  $out=""; foreach($a as $v) { $out.=$v["content"]; }
  return $out;
}

function displaySystem($v,$people) {
  $title=$v->get('rdfs:label');
  $swmath=$v->get('owl:sameAs');
  $sigsamurl=$v->get('sd:hasSIGSAMURL');
  $summary=$v->get('dct:summary');
  $also=$v->all('rdfs:seeAlso');
  $out='<h3> <i>CA System:</i> '.$title.'</strong></h3><dl>';
  if (!empty($swmath)) { 
    $out.='<dd> <i>swmath Link:</i> <a href="'.$swmath.'">'.$swmath.'</a></dd>'; }
  if (!empty($sigsamurl)) { 
    $out.='<dd> <i>SIGSAM Link:</i> <a href="'.$sigsamurl.'">'.$sigsamurl.'</a></dd>'; }
  if (!empty($summary)) { 
    $out.='<dd> <i>Summary:</i> '.$summary.'</dd>'; }
  $out.='<dd> <i>SD Entry:</i> <a href="'.$v.'">'.$v.'</a></dd>';
  if (!empty($also)) { 
    $out.='<dd> <i>Additional information:</i> ';
    foreach($also as $w) { $out.=additionalInformation($w,$people); }
    $out.='</dd>'; 
  }
  return $out.'</dl></p>' ; 	
}

function additionalInformation($w,$people) {
  $desc=$w->get('dct:description');
  $autoren=getAutoren($w->all('dct:creator'),$people);
  $out=$w ;
  if (!empty($autoren)) { $out.='<br/> <strong>Author(s):</strong> '.$autoren; }
  if (!empty($desc)) { $out.='<br/> <strong>Description:</strong> '.$desc; }
  return '<blockquote>'.$out.'</blockquote>';
}

function getAutoren($a,$people) {
  $b=array(); 
  foreach ($a as $rev) {
    $c=$people->get($rev->getUri(),"foaf:name");
    $b[]=(!empty($c) ? $c : $rev->getUri() );
  }
  return join(", ",$b);
}

// echo '<meta charset="utf8">'.casystems();

?>

<?php 

/* changed to lib/EasyRdf.php and CONSTRUCT query */

require_once("lib/EasyRdf.php");

function dissertationen($atts) {
  EasyRdf_Namespace::set('bibo', 'http://purl.org/ontology/bibo/');
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');
  EasyRdf_Namespace::set('dct', 'http://purl.org/dc/terms/');
  $people = new EasyRdf_Graph("http://symbolicdata.org/Data/People/");
  //$people->parseFile("http://symbolicdata.org/rdf/People.rdf");
  $people->parseFile("/home/graebe/git/SD/web/rdf/People.rdf");
  $out="\n\n<h2 align=\"center\">Habilitationen</h2>\n\n";
  $out.=displayAll(getDissertations('habil'),$people);
  $out.="\n\n<h2 align=\"center\">Promotionen</h2>\n\n";
  $out.=displayAll(getDissertations('phd'),$people);
  return $out;
}

function getDissertations($what) {

  $query = '
PREFIX sd: <http://symbolicdata.org/Data/Model#>
PREFIX bibo: <http://purl.org/ontology/bibo/>
PREFIX dct: <http://purl.org/dc/terms/>
construct {
?a a bibo:Thesis .
?a dct:creator ?c . 
?a dct:title ?title . 
?a bibo:institution ?inst . 
?a dct:date ?year .  
?a dct:abstract ?abstract . 
?a sd:hasSupervisor ?s .
?a sd:hasReviewer ?r . 
?a sd:hasURL ?url . 
}
from <http://symbolicdata.org/Data/Dissertations/>
Where {
?a a bibo:Thesis ; dct:creator ?c ; dct:title ?title; 
dct:date ?year; bibo:degree <http://purl.org/ontology/bibo/degrees/'.$what.'>. 
optional { ?a bibo:institution ?inst .}
optional { ?a sd:hasSupervisor ?s .}
optional { ?a sd:hasReviewer ?r . }
optional { ?a sd:hasURL ?url . }
optional { ?a dct:abstract ?abstract . }
} 
';
  $sparql = new EasyRdf_Sparql_Client('http://symbolicdata.org:8890/sparql');
  $result = $sparql->query($query); // a CONSTRUCT query returns an EasyRdf_Graph
  //echo $result->dump("turtle");
  return $result ; 
}

function displayAll($result,$people) {
  $a=array(); 
  foreach ($result->allOfType("bibo:Thesis") as $v) {  
    $year=$v->get('dct:date');
    $content=displayThesis($v,$people);
    $a[]=array("location" => "$year", "content" => $content);
  }  
  array_multisort($a, SORT_DESC);
  $out=""; foreach($a as $v) { $out.=$v["content"]; }
  return $out;
}

function displayThesis($v,$people) {
  $cn=$people->get($v->get('dct:creator'),'foaf:name');
  $title=$v->get('dct:title');
  $institution=$v->get('bibo:institution');
  $year=$v->get('dct:date');
  $abstract=$v->get('dct:abstract');
  $url=$v->get('sd:hasURL');
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
  $out='<dd> <i>Author:</i> <span class="author">'.$cn.'</span></dd>'
    .'<dd> <i>Title:</i> <span class="title">'.$title.' </span></dd>';
  if (!empty($institution)) { $out.='<dd> <i>Institution:</i> <span class="institution">'.$institution.' </span></dd>'; }
  if (!empty($supervisors)) { $out.='<dd> <i>Thesis Advisor:</i> <span class="thesisadvisor">'.$supervisors.' </span></dd>'; }
  if (!empty($reviewers)) { $out.='<dd> <i>Thesis Reviewer:</i> <span class="thesisreviewer">'.$reviewers.' </span></dd>'; }
  $out.='<dd> <i>Defended:</i> <span class="defence">'.$year.' </span></dd>';
  if (!empty($url)) { $out.='<dd> <i>URL:</i> <span class="url"> <a href="'.$url.'">'.$url.'</a></span></dd>'; }
  if (!empty($abstract)) { $out.='<dd> <i>Abstract:</i> <span class="abstract">'.$abstract.' </span></dd>'; }
  $out.='<dd> <i>CASN Entry:</i> <span class="casn-entry"> <a href="'.$v.'">'.$v.'</a></span></dd>';
  return '
<p>
<dl>'.$out.'</dl>
</p>' ; 	
}

/* if ( ! defined( 'ABSPATH' ) ) { // make test */
/*   $a=array(); echo '<meta charset="utf8">'.dissertationen($a); */
/* } else { */
/*   add_shortcode( 'dissertationen', 'dissertationen' ); */
/* } */

?>

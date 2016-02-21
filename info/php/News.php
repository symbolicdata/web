<?php 
/**
 * User: Hans-Gert Gräbe
 * Date: 2016-02-21
 */

require_once("lib/EasyRdf.php");

function getNews() {  
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');
  EasyRdf_Namespace::set('sioc', 'http://rdfs.org/sioc/ns#');

  $query1 = '
PREFIX sd: <http://symbolicdata.org/Data/Model#>
construct { ?a ?b ?c . }
from <http://symbolicdata.org/Data/News/>
Where { ?a a sioc:BlogPost ; ?b ?c . }
';
  $query2 = '
PREFIX sd: <http://symbolicdata.org/Data/Model#>
construct { ?p foaf:name ?n . }
from <http://symbolicdata.org/Data/News/>
from <http://symbolicdata.org/Data/People/>
Where { ?a a sioc:BlogPost ; dc:publisher ?p . 
  ?p foaf:name ?n . }
';
  
  $sparql = new EasyRdf_Sparql_Client('http://symbolicdata.org:8890/sparql');
  $result=$sparql->query($query1); // a CONSTRUCT query returns an EasyRdf_Graph
  //echo $result->dump("turtle");
  $people=$sparql->query($query2);
  //echo $people->dump("turtle");
  /* generate data structure for output table */
  $s=array();
  foreach ($result->allOfType("sioc:BlogPost") as $v) {
    $a=$v->getUri();
    $label=$v->get('rdfs:label');
    $created=$v->get('dc:created');
    $subject=$v->join('dc:subject');
    $abstract=$v->get('dc:abstract');
    $publisher=$people->get($v->get('dc:publisher'),'foaf:name');
    $link=$v->get('sioc:link');
    $linksTo=$v->get('sioc:links_to');
    $out='<p><dl> <dt><strong><a href="'.$a.'">'.$label.'</a></strong></dt>
';
    $out.=addLine($created,"Created");
    $out.=addLine($subject,"Subject");
    $out.=addLine($abstract,"Abstract");
    $out.=addLine($publisher,"Publisher");
    $out.=addLink($link,"More");
    $out.=addLink($linksTo,"Links to");
    $out.='</dl></p>';
    $s["$created_$a"]=$out;
  }
  krsort($s);
  return join($s,"\n") ; 		
}

function addLine($value,$text) {
  if(empty($value)) { return ""; }
  return '<dd>'.$text.': '.$value.'.</dd>'; 
}

function addLink($value,$text) {
  if(empty($value)) { return ""; }
  return '<dd>'.$text.': <a href="'.$value.'">'.$value.'.</a></dd>'; 
}


// ---- test ----
// echo getNews();

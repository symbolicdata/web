<?php 

/* changed to lib/EasyRdf.php and CONSTRUCT query */

require_once("lib/EasyRdf.php");

function showSystems() {
  EasyRdf_Namespace::set('sd', 'http://symbolicdata.org/Data/Model#');
  EasyRdf_Namespace::set('dct', 'http://purl.org/dc/terms/');
  EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
  $people = new EasyRdf_Graph("http://symbolicdata.org/Data/People/");
  $people->parseFile("http://symbolicdata.org/rdf/People.rdf");
  // $people->parseFile("/home/graebe/git/SD/web/rdf/People.rdf");
  $systems = new EasyRdf_Graph("http://symbolicdata.org/Data/Systems/");
  $systems->parseFile("http://symbolicdata.org/rdf/Systems.rdf");
  /// $systems->parseFile("/home/graebe/git/SD/web/rdf/Systems.rdf");
  $descriptions = new EasyRdf_Graph("http://symbolicdata.org/Data/SystemDescriptions/");
  $descriptions->parseFile("http://symbolicdata.org/rdf/SystemDescriptions.rdf");
  // $descriptions->parseFile("/home/graebe/git/SD/web/rdf/SystemDescriptions.rdf");
  $out=displaySystems($systems,$descriptions,$people);
  return $out;
}

function displaySystems($systems,$descriptions,$people) {
    $a=array(); 
    foreach ($descriptions->allOfType("sd:CASDescription") as $v) {
      $cas=$v->get('sd:describes');
      $a["$cas"][]=showDescription($v,$people);
    }
    // print_r($a);
    $b[]=array();
    foreach ($systems->allOfType("sd:CAS") as $w) {
        $title=$w->get('rdfs:label');
        $content=displaySystem($w);
        if (isset($a["$w"])) { 
            $content.=join("\n\n",$a["$w"]);
        } else { $content.=noDescriptionAvailable(); }
        $b[]=array("link" => "$title", "content" => $content);
    }
    array_multisort($b);
    $out=""; foreach($b as $v) { $out.=$v["content"]; }
    return $out;
}

function noDescriptionAvailable() {
    return "\n<p>No description available</p>\n";
}

function displaySystem($v) {
  $title=$v->get('rdfs:label');
  $swmath=$v->get('sd:hasSWMathEntry');
  $sigsamurl=$v->get('sd:hasSIGSAMEntry');
  $summary=$v->get('dct:summary');
  $out='<h3> <i>CA System:</i> '.$title.'</strong></h3><dl>';
  if (!empty($swmath)) { 
    $out.='<dd> <i>swMath Link:</i> <a href="'.$swmath.'">'.$swmath.'</a></dd>'; }
  if (!empty($sigsamurl)) { 
    $out.='<dd> <i>SIGSAM Link:</i> <a href="'.$sigsamurl.'">'.$sigsamurl.'</a></dd>'; }
  if (!empty($summary)) { 
    $out.='<dd> <i>Summary:</i> '.$summary.'</dd>'; }
  $out.='<dd> <i>SD Entry:</i> <a href="'.$v.'">'.$v.'</a></dd>';
  return $out.'</dl></p>' ; 	
}

function showDescription($v,$people) {
    $description=$v->get('dct:description');
    $source=$v->get('dct:source');
    $autoren=getAutoren($v->all('dct:creator'),$people);
    $institution=join("; ",$v->all('dct:institution'));
    $url=$v->get('sd:hasURL');
    $out='<p><strong>Description:</strong> '.$description;
    if (!empty($autoren)) {
        $out.='<br/><strong>Author(s):</strong> '.$autoren;
    }
    if (!empty($institution)) {
        $out.='<br/><strong>Produced by:</strong> '.$institution;
    }
    if (!empty($url)) {
        $out.='<br/><strong>URL:</strong>  <a href="'.$url.'">'.$url.'</a>';
    }
    $out.='<br/><strong>Source:</strong> '.$source.'</p>

'; 
  return $out ; 	
}

function getAutoren($a,$people) {
  $b=array(); 
  foreach ($a as $rev) {
    $c=$people->get($rev->getUri(),"foaf:name");
    $b[]=(!empty($c) ? $c : $rev->getUri() );
  }
  return join(", ",$b);
}

// echo '<meta charset="utf8">'.showSystems();

?>

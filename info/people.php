<?php
/**
 * User: Hans-Gert Gräbe
 * Date: 2016-02-20
 */

include_once("layout.php");
include_once("php/People.php");

$content='      
<div class="container">
<h1 align="center">People</h1>

<p>The following list of academic people working in Computer Algebra is
extracted from the SymbolicData <a href="http://symbolicdata.org/Data/People/"
>CASN People Database</a>.  </p>

<p>The CASN Database contains more than 1000 instances of foaf:Person (as of
Febr. 2016).  In 2014 we identified in a joint effort with ZBMath (Wolfram
Sperber) the author strings of 347 persons within the author disambiguation
system of the Zentralblatt.  </p>

<p>For performance reasons the output is restricted to about 20 entries (100
RDF triples).  Use HTTP Get Parameter as in
<code>info/people.php?name=G&affil=J</code> to display available information
about people with foaf:name containing the string "G" and sd:affiliation
containing the string "J". The search is case insensitive.</p>

'.getPeople($_GET['name'],$_GET['affil']).'
</div>
';
echo showPage($content);

?>
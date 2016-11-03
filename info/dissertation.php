<?php
/**
 * User: Hans-Gert Gräbe
 * Date: 2016-02-21
 */

require_once "layout.php";
require_once "php/Dissertations.php";

$content='      
<div class="container">
<h1 align="center">Dissertations</h1>

<p>The following list of dissertations was compiled from announcements of the
German Fachgruppe in their CA Rundbrief.  Note that for performance the people
URIs are resolved from the file <a
href="http://symbolicdata.org/rdf/People.rdf" >rdf/People.rdf</a>. </p>

'.dissertationen().'
</div>
';
echo showPage($content);

?>
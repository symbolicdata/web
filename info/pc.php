<?php
/**
 * User: Hans-Gert Gräbe
 * Date: 2015-07-26
 */

require_once "layout.php";
require_once "php/Conferences.php";

$content='      
<div class="container">
<h1 align="center">Past Conferences</h1>

<p>The following list of Past CA Conferences is extracted from the <a
href="http://symbolicdata.org/Data/PastConferences/" >CASN Past Conferences
Database</a>. The heading of each conference entry links to the corresponding
CASN Database entry that may contain more information (organizers, invited
speakers, deadlines etc.) about the conference. </p>

<p>The information is displayed for the year 2012. Use HTTP Get Parameter as in
<code>info/pc.php?year=2002</code> to display available information for another
year.  Conference records are available until 2018. </p>

'.pastConferences().'
</div>
';
echo showPage($content);

?>
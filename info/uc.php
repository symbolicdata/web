<?php
/**
 * User: Hans-Gert Gräbe
 * Date: 2015-07-26
 */

include_once("layout.php");
include_once("php/Conferences.php");

$content='      
<div class="container">
<h1 align="center">Upcoming Conferences</h1>

<p>The following list of Upcoming CA Conferences is extracted from the <a
href="http://symbolicdata.org/casn/UpcomingConferences/" >CASN Upcoming
Conferences Database</a>. The heading of each conference entry links to the
corresponding CASN Database entry that may contain more information
(organizers, invited speakers, deadlines etc.) about the conference. </p>

'.upcomingConferences().'

</div>
';
echo showPage($content);

?>
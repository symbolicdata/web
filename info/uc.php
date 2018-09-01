<?php
/**
 * User: Hans-Gert Gräbe
 * Date: 2015-07-26
 * Last Update: 2018-09-01
 */

require_once "layout.php";
require_once "php/Conferences.php";

$content='      
<div class="container">
<h1 align="center">Upcoming Conferences</h1>

<p>The list of Upcoming CA Conferences formerly extracted from the CASN
Upcoming Conferences Database, is no more maintained at the moment.  </p>

</div>
';
echo showPage($content);

?>
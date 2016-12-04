<?php
/**
 * User: Hans-Gert Gräbe
 * Date: 2016-02-21
 */

require_once "layout.php";
require_once "php/Systems.php";

$content='      
<div class="container">
<h1 align="center">CA Systems</h1>

<p>This is an experimental listing compiled from our store, part of swmath and from the SIGSAM system\'s page.  </p>

'.showSystems().'
</div>
';
echo showPage($content);

?>
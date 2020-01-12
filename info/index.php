<?php
/**
 * User: Hans-Gert Gräbe
 * Date: 2015-07-26
 */

require_once "layout.php";

$content='      
<div class="container">
  <h1 align="center">The Goal of this Site</h1>
<p>At this site we demonstrate how the RDF based information maintained within
the CASN can be explored and included into a web page using small PHP scripts.
Our demonstration site is based on the the <a href="http://getbootstrap.com"
>Bootstrap Framework</a> and the <a href="http://www.easyrdf.org/" >EasyRdf
PHP library</a>.  The code is available from
our <a href="https://github.com/symbolicdata/web">git repo</a>. </p>

<p>Addendum 2020: Most of the code does no more work since it uses EasyRdf
CONSTRUCT queries that conflict with PHP 7. </p>

</div>
';
echo showPage($content);

?>

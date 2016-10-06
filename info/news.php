<?php
/**
 * User: Hans-Gert Gräbe
 * Date: 2016-02-21
 */

require_once "layout.php";
require_once "php/News.php";

$content='      
<div class="container">
<h1 align="center">News</h1>

<p>The following list of news is extracted from different sources.  Most of the
news are retweeted to the <a
href="http://lists.informatik.uni-leipzig.de/mailman/listinfo/sd-announce"
>sd-announce mailing list</a> to have reliable archive links for reference.
</p>


'.getNews().'
</div>
';
echo showPage($content);

?>
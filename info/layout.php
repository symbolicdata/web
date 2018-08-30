<?php
/**
 * User: Hans-Gert Gräbe
 * Date: 2015-07-26
 */

function pageHeader() 
{
    return '
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="SymbolicData Standalone Info Page"/>
    <meta name="author" content="SymbolicData Project"/>

    <title>SymbolicData Standalone Info Page</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    
  </head>
<!-- end header -->
  <body>

';
}

function pageNavbar() 
{
    return '

    <!-- Fixed navbar -->
    <nav class="navbar navbar-default" role="navigation">
      <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Index</a></li> 
            <li><a href="uc.php">Upcoming Conferences</a></li> 
            <li><a href="pc.php">Past Conferences</a></li> 
            <li><a href="people.php">CA People</a></li> 
            <li><a href="dissertation.php">Dissertations</a></li> 
            <li><a href="ca-systems.php">CA Systems</a></li> 
            <li><a href="news.php">News</a></li> 
          </ul>
        </div><!-- navbar end -->
      </div><!-- container end -->
    </nav>';
}

function generalContent() 
{
    return '
<div class="container">
  <h1 align="center">The CASN Demonstration Site</h1>

<p> Within the <a href="http://symbolicdata.org">SymbolicData Project</a>
we started the <strong>CASN subproject</strong> &ndash; Computer Algebra Social
Network &ndash; as a germ of a “Facebook like“ communication infrastructure
about the scientific activities within the CA community using a modern RDF
based "Web 2.0" approach.  We refer to our wiki for <a
href="http://symbolicdata.github.io/CASN" >more information about CASN goal and
concepts</a>.</p> </div> 

<div class="footer"> <div class="container"> <p class="text-muted">&copy; <a
href="http://symbolicdata.org">SymbolicData Project</a> 2015-2018 </p> </div>
</div>
';
}

function pageFooter() 
{
    return '

    <!-- jQuery (necessary for Bootstrap JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    
  </body>
</html>';
}

function showPage($content) 
{
    return pageHeader().generalContent().pageNavbar().($content).pageFooter();
}

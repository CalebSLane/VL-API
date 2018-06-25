<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Instructions</title>
</head>
<body>
API usage:
<ul>
    <li>Selecting resource from a table or multiple tables
    	<ul>
    		<li>GET base_url/{collectionName}/{type}  ie. http://<?php echo $_SERVER['SERVER_NAME']?>:<?php echo $_SERVER['SERVER_PORT']?>/vldash-api/summaries/all</li>
    		<li>GET base_url/{itemName}/{type}/{id}  ie. http://<?php echo $_SERVER['SERVER_NAME']?>:<?php echo $_SERVER['SERVER_PORT']?>/vldash-api/summary/county/12</li>
    	</ul>
    </li>
    <li>Inserting a new resource
    	<ul>
    		<li>POST base_url/{collectionName}/{type}  ie. http://<?php echo $_SERVER['SERVER_NAME']?>:<?php echo $_SERVER['SERVER_PORT']?>/vldash-api/summaries/all</li>
    	</ul>
    </li>
    <li>Updating an already inserted resource
    	<ul>
    		<li>PUT base_url/{itemName}/{type}/{id}  ie. http://<?php echo $_SERVER['SERVER_NAME']?>:<?php echo $_SERVER['SERVER_PORT']?>/vldash-api/summary/county/12</li>
    	</ul>
    </li>
    <li>Deleting an already inserted resource (unimplemented)
    	<ul>
    		<li>DELETE base_url/{itemName}/{type}/{id}  ie. http://<?php echo $_SERVER['SERVER_NAME']?>:<?php echo $_SERVER['SERVER_PORT']?>/vldash-api/summary/county/12</li>
    	</ul>
    </li>
</ul>	

</body>
</html>
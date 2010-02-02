<?php

/**
 *
 * qPlanet.: Another free RSS planet.
 * @author Jorge Fuertes - jorge@jorgefuertes.com
 * @version 2.0
 * @package qPlanet
 * 
 **/

# Setup class autoload:
function __autoload($clase)
{
	require_once("include/".$clase.".class.php");
}

# General configuration, options and feeds:
$Config = new Config;
$Config->SetOpts();
error_reporting($Config->getOpt('options', 'reporting'));

# Simplepie: 3rd-party rss processing:
require_once('3rd-party/simplepie/simplepie.inc');
include_once('3rd-party/simplepie/idn/idna_convert.class.php');

# RSS init:
$rss = new Rss;

# Get the posts:
Debug::info("Getting the posts");
$posts = $rss->getPosts($Config->getFeeds());

# Write mode output openning:
Debug::info("Generating HTML output at ".OUTDIR."/".OUTFILE);
Debug::info("Generating RSS 2.0 output at ".OUTDIR."/rss20.xml");
$fOutput = new Output;

#
# Output files generation:
#

# Header:
Debug::info("Headers");
$fOutput->write(File::ReadAndParse('html/header.html'));
$fOutput->writeRss(File::ReadAndParse('xml/rss20_head.xml'));

# Javascript:
Debug::info("HTML: Javascript functions");
$fOutput->write(File::Read('html/functions.js'));

# Body:
Debug::info("Bodies");

# The posts:
Debug::info("The posts");
$fOutput->write('<div id="posts">');

# Posts loop:
foreach($posts as $post)
{
	Debug::info("  Output (".$post['author'].") ".$post['title']);
	$fOutput->write(File::ReadAndParse('html/post.html', $post));
	$fOutput->writeRss(File::ReadAndParse('xml/rss20_item.xml', $post));
}
$fOutput->write('</div>');
Debug::info("Posts end");
# ----------------

# -----------------------------------------------------
# Sidebar construction and OPML generation:
# -----------------------------------------------------
Debug::info("HTML: Sidebar");
$fOutput->writeOpml(File::ReadAndParse('xml/opml_head.xml'));
$blog_list = "<ul>\n";
$previous = array();
foreach($posts as $post)
{
	if(array_search($post['autor_link'], $previous) === false)
	{
		$previous[] = $post['autor_link'];
		$blog_list .= "<li><a href='".$post['autor_link']."'><img src='/gfx/feed-icon-10x10.png' "
                        ."alt='RSS feed' /></a>"
			."<a href='".$post['blog_url']."'>".$post['blog_title']."</a></li>\n";
		$fOutput->writeOpml(File::ReadAndParse('xml/opml_item.xml', $post));
	}
}
$fOutput->writeOpml(File::ReadAndParse('xml/opml_foot.xml'));
$blog_list .= "</ul>\n";
define('BLOG_LIST', $blog_list); unset($blog_list);

# Other similar planets:
$planets = $Config->getPlanetas();
$planet_list = "<ul>\n";
foreach($planets as $planet)
{
	$planet_list .= "<li><a href='".$planet['rss']."'><img src='/gfx/feed-icon-10x10.png' alt='Feed RSS' /></a>"
			."<a href='".$planet['url']."'>".$planet['name']."</a></li>\n";
}
$planet_list .= "</ul>\n";
define('PLANET_LIST', $planet_list); unset($planet_list);

# Sidebar generation:
$fOutput->write(File::ReadAndParse('html/sidebar.html'));

# Extended content initially hidden:
$fOutput->write('<script type="text/javascript">$(".content").hide();</script>');

# Statistics:
Debug::info("HTML: Statistics code");
$fOutput->write(File::ReadAndParse('html/stats.html'));
	
# Closure:
Debug::info("Closures");
$fOutput->write(File::ReadAndParse('html/pie.html'));
$fOutput->writeRss(File::Read('xml/rss20_foot.xml'));

Debug::info("EOF");

?>
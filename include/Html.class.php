<?php

class Html
{
    /**
     *
     * Clean the feedburner bullshit.
     *
     * @param string $txt Text to clean
     * @return string Clean txt
     */
    public static function BurnerClean($txt)
    {
        return preg_replace(
                "/\<div\>.*(\<a href\=[\"|\']http\:\/\/feeds\.feedburner\.com.*[\"|\']\>.*\<\/a\>.*){2,}\<\/div\>/",
		"\n<!-- Filtered Feedburner -->\n",
		$txt);
    }

    /**
     *
     * Strip the tags and crop the html code to a fixed size.
     * Usefull to make summarys.
     * @param string $txt HTML code to be cutted
     * @param integer $size Cut the html to tis size
     * @return string Cutted html
     */
    public static function Cut($txt, $size = 350)
    {
        $txt = strip_tags($txt);
        if(strlen($txt) > $size)
        {
            $txt = substr($txt, 0, $size)."[...]";
        }
        return $txt;
    }
	
    /**
     *
     * Cleans the html
     * @param string $html String cotaining the html to clean
     * @return string Cleaned an tidyed
     */
    public static function Clean($html)
    {
        $html = Html::BurnerClean($html);

        $config = Array(
                "show-body-only" => true,
                "alt-text"       => "Pic without description",
                "hide-endtags"   => false,
                "output-xhtml"   => true
        );
        $tidy = new tidy;
        $tidy->parseString($html, $config, 'utf8');
        $tidy->cleanRepair();

        return $tidy;
    }
}
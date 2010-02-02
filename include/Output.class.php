<?php

/**
 *
 * Final output class.
 */
class Output
{
	private $fOut, $rssOut;
	
	public function Output()
	{
		# Opening the output stream:
		$this->fOut = fopen(OUTDIR."/".OUTFILE, "w");
		if(!$this->fOut)
		{
			echo "\nERROR: Can't open output file: ".OUTDIR."/".OUTFILE."\n\n";
			die(1);
		}
		# Opens rss20 too:
		$this->rssOut = fopen(OUTDIR."/rss20.xml", "w");
		if(!$this->rssOut)
		{
			echo "\nERROR: Can't open output file: ".OUTDIR."/rss20.xml\n\n";
			die(1);
		}
		# Opens the opml:
		$this->opmlOut = fopen(OUTDIR."/opml.xml", "w");
		if(!$this->opmlOut)
		{
			echo "\nERROR: Can't open output file: ".OUTDIR."/opml.xml\n\n";
			die(1);
		}		
   	}

        /**
         *
         * Write something to output file
         * @param string $txt Write this to output
         * @return bool true
         */
   	public function write($txt)
   	{
  		fwrite($this->fOut, $txt);
  		return true;
   	}

        /**
         *
         * Write something to the rss output.
         * @param string $txt String to write
         * @return bool true
         */
   	public function writeRss($txt)
   	{
  		fwrite($this->rssOut, $txt);
  		return true;
   	}

        /**
         *
         * Write something to the opml output.
         * @param string $txt String to write
         * @return bool true
         */
   	public function writeOpml($txt)
   	{
  		fwrite($this->opmlOut, $txt);
  		return true;
   	}
}

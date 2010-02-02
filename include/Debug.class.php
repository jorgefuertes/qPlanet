<?php

class Debug 
{
        /**
         *
         * Dumps var to console for debugging:
         * Only if debug is activated.
         * @param string $array Array to dump
         * @param string $title Title of the dump
         *
         **/
	public static function dump($array, $title = "Debug")
	{
		if(DEBUG)
		{
			echo "--------------------------------------------------\n"
				.$title."\n"
				."..................................................\n\n"
				.print_r($array, true)."\n"
				."--------------------------------------------------\n\n";
		}
	}

        /**
         *
         * Say something to console.
         * Only if debug is activated.
         * @param string $txt Text to print
         *
         **/
	public static function say($txt)
	{
		if(DEBUG) echo "DEBUG> ".$txt."\n";
	}

        /**
         *
         * Send info to console.
         * @param string $txt Text to print
         *
         */
	public static function info($txt)
	{
		if(INFO) echo "INFO> ".$txt."\n";
	}

        /**
         * Show a critical error and die.
         * @param string $txt Error description.
         * @param integer $level System's errorlevel. Defaults to 1.
         */
	public static function error($txt, $level = 1)
	{
		echo "+-----------------+\n"
		    ."| CRITICAL ERROR: |\n"
		    ."+-----------------+\n"
		    .$txt."\n";
		    
		die(1);
	}
}

?>
<?php

/**
 *
 * Clase para entrada/salida de ficheros.
 */
class File
{
        /**
         *
         * Reads a file and parse the vars, then returns it as a string:
         * @param string $file File to parse
         * @param string $post Post object
         * @return string String with the file already parsed
         */
	public static function ReadAndParse($file, $post = "")
	{
		if(!file_exists($file))
		{
			Debug::error("File '$file' not found.");
		}
		
		Debug::say("Parsing and including '$file'");
		$string = file_get_contents($file, 1024);

		# Date is the first:
		while(preg_match("/\\$\{(GMDATE)\}/", $string, $aRegs))
		{
                    $string = str_replace($aRegs[0], gmdate("r"), $string);
		}
		while(preg_match("/\\$\{(DATE)\}/", $string, $aRegs))
		{
                    $string = str_replace($aRegs[0], date(), $string);
		}
		
		# Parsing with regular expressions:
				
		# CONSTANTS ${CONSTANT}:
		while(preg_match("/\\$\{([A-Z]*)\}/", $string, $aRegs))
		{
			Debug::dump($aRegs);
			Debug::say("  Replace $aRegs[0] by ".constant($aRegs[1])."...");
                        Debug::say("  Constant: ".print_r($aRegs, true));
			$string = str_replace($aRegs[0], constant($aRegs[1]), $string);
		}
		
		# Vars from post ${post:key}:
		if(!empty($post) and is_array($post))
		{
			while(preg_match("/\\$\{post\:([a-z_]*)\}/", $string, $aRegs))
			{
				Debug::dump($aRegs);
				Debug::say("  Replace $aRegs[0] by post['".$aRegs[1]."']...");
				$string = str_replace($aRegs[0], $post[$aRegs[1]], $string);
			}
		}		
		
		return $string;
	}

	/**
         *
         * Reads a file and returns it as a string.
         * @param string $file Filename
         */
	public static function Read($file)
	{
		if(!file_exists($file))
		{
			Debug::error("File '$file' not found.");
		}
		
		Debug::say("Including '$file'");
		return file_get_contents($file, 1024);
	}
}
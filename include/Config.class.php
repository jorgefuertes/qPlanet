<?php

/**
 *
 * Planet configuration:
 * @package qPlanet
 * @subpackage config
 *
 **/

class Config
{
	private $aConfig;
	
	# Builder: Loads the yaml file:
	function __construct()
	{
		# Include the SPYC library for yaml parsing:
		require_once('3rd-party/spyc/spyc.php');

                # Loads an array with the config:
		$this->aConfig = Spyc::YAMLLoad('config.yml');
	}

        # Maps options to defines:
	public function SetOpts()
	{
		# Extract some values:
		define('DEBUG',          $this->aConfig['options']['debug']);
		define('INFO',           $this->aConfig['options']['info']);
		define('MAXPOSTS',       $this->aConfig['options']['maxposts']);
		define('OUTDIR',         $this->aConfig['options']['outdir']);
		define('OUTFILE',        $this->aConfig['options']['outfile']);
		define('STATSFILE',      $this->aConfig['options']['statistics']);
		define('DEFAULT_AVATAR', $this->aConfig['options']['default_avatar']);
		define('REFRESH',        $this->aConfig['options']['refresh']);
		define('TITLE',          $this->aConfig['meta']['title']);
		define('DESCRIPTION',    $this->aConfig['meta']['description']);
		define('KEYWORDS',       $this->aConfig['meta']['keywords']);
		define('LINK',           $this->aConfig['meta']['link']);
		define('ICON',           $this->aConfig['meta']['link'].$this->aConfig['meta']['icon']);
		define('LANGUAGE',       $this->aConfig['meta']['language']);
		define('OWNER',          $this->aConfig['meta']['owner']);
		define('EMAIL',          $this->aConfig['meta']['email']);
                define('DATE_FORMAT',    $this->aConfig['options']['date_format']);
	}
		
	# Dumps the config for debug purposes:
	public function Debug()
	{				
        	# Dumps the config for debug purposes:
		# Dump to console if debug its activated::
		Debug::dump($this->aConfig, "Configuration");
	}

        public function getOpt($section = 'options', $opt)
        {
            if(!empty($this->aConfig[$section][$opt]))
            {
                return $this->aConfig[$section][$opt];
            }
            else
            {
                Debug::error("Config option not found: ".$section."->".$opt);
            }
        }
	
	# Get an array with the feeds:
	public function getFeeds()
	{
		return $this->aConfig['feeds'];
	}
	# Get an array with the similar planets:
	public function getPlanetas()
	{
		return $this->aConfig['planets'];
	}

	public function __destruct()
	{
		unset($this->aConfig);
	}
}
<?php

/**
 *
 * RSS class
 *
 */
class Rss
{
	private $feedOut;
	
	/**
         *
         * Includes de rss library.
         */
	public function Rss()
	{
		# Simplepie: 3rd-party rss processing library:
		require_once('3rd-party/simplepie/simplepie.inc');
		include_once('3rd-party/simplepie/idn/idna_convert.class.php');
	}
	
	/**
         *
         * Get posts from feeds:
         * @param array $feeds Array with the feeds.
         * @return array Array with the posts.
         */
	public function getPosts($feeds)
	{
		$posts = array();
		foreach ($feeds as $name => $feed)
		{
			Debug::info("Getting posts from ".$feed['url']);
			$rss = new SimplePie($feed['url']);
			$rss->handle_content_type();
			$counter = 1;
			foreach($rss->get_items() as $item)
			{
				Debug::info("  post $counter of ".MAXPOSTS.".");
				if(!is_object($item))
				{
					Debug::error("Parsing item (".$item.").");
				}
				else if(strstr($item->get_title(), "[minipost]"))
				{
					Debug::say("Minipost...passing.");
				}
				else
				{
					$counter++;
					$timestamp = strtotime($item->get_date());
					
					# Post:
					$posts[$timestamp]['id']             = "p".$timestamp."-".$counter;
					$posts[$timestamp]['pubdate']        = $item->get_date('r');
					$posts[$timestamp]['date']           = $item->get_local_date();
					$posts[$timestamp]['time']           = $item->get_local_date("%T");
					$posts[$timestamp]['permalink']      = $item->get_permalink();
					$posts[$timestamp]['title']          = $item->get_title();
					$posts[$timestamp]['description']    = $item->get_description();
					$posts[$timestamp]['content']        = $item->get_content();
					if($posts[$timestamp]['description'] == $posts[$timestamp]['content']
						and strlen($posts[$timestamp]['content']) < 400)
					{
						$posts[$timestamp]['description'] =
                                                    Html::Cut(Html::BurnerClean($item->get_description()));
						$posts[$timestamp]['content']   = "";
						$posts[$timestamp]['toggle']      = "";
					}
					else
					{
						$posts[$timestamp]['description'] = 
                                                    Html::Cut(Html::BurnerClean($item->get_description()));
						$posts[$timestamp]['content']   = Html::Clean($item->get_content());
						$posts[$timestamp]['toggle']      = "<a onclick='ToggleContent(\""
                                                    .$posts[$timestamp]['id']
                                                    ."\"); return false;' href=\"#\">&dArr; Read more</a>";
					}
					// Microblogging posts have same description as title
					if( FALSE !== strpos( $posts[$timestamp]['title'], $posts[$timestamp]['description'] ) )
					{
						$posts[$timestamp]['description']     = "";
						$posts[$timestamp]['content']       = "";
						$posts[$timestamp]['toggle']          = "";
					}
					# Rss:
					$posts[$timestamp]['description_rss'] = $posts[$timestamp]['description'];
					$posts[$timestamp]['content_rss']     = $posts[$timestamp]['content'];
					# author:
					if(method_exists($item, 'get_author') and is_object($item->get_author()))
					{
						# The feed ones:
						$posts[$timestamp]['author']        = $item->get_author()->get_name();
						$posts[$timestamp]['author_link']   = $item->get_author()->get_link();
					}
					else
					{
						Debug::say("No author data feed ".$name);
						$posts[$timestamp]['author'] = $name;
						$posts[$timestamp]['author_link'] = $rss->get_permalink();
					}	
					# Si han quedado vacíos pese a todo:
					if(empty($posts[$timestamp]['author']))
					{
						$posts[$timestamp]['author'] = $name;
					}
					if(empty($posts[$timestamp]['author_link']))
					{
						$posts[$timestamp]['author_link'] = $feed['url'];
					}
	
					$posts[$timestamp]['author_email'] = $feed['email'];
					# Gravatar:
					if( strlen( $feed['avatar'] ) )
						$posts[$timestamp]['author_avatar'] =
                                            "http://www.gravatar.com/avatar.php?gravatar_id=".md5($feed['avatar'])
                                            ."&amp;size=40&amp;default=".urlencode(DEFAULT_AVATAR);
					else if( strlen( $feed['avatar_url'] ) )
						$posts[$timestamp]['author_avatar'] = $feed['avatar_url']
					# Blog:
					$posts[$timestamp]['blog_title']   = $rss->get_title();
					$posts[$timestamp]['blog_url']     = $rss->get_permalink();
					$posts[$timestamp]['blog_desc']    = $rss->get_description();
					# Logo:
					$posts[$timestamp]['logo_url']     = $rss->get_image_url();
					$posts[$timestamp]['logo_link']    = $rss->get_image_link();
					$posts[$timestamp]['logo_title']   = $rss->get_image_title();
				}
				if($counter > MAXPOSTS) break;
			}
		}
		krsort($posts);
		#Debug::dump($posts);
		return($posts);
	}
}

<?php
class NewsFeed
{
	/**
	 * Retrieves and merges Twitter, RSS2 and Atom feeds, returning them
	 * in reverse date order. Because retrieving Twitter feeds is rate limited, this
	 * method insists on compulsory use of a variable-cache. If a variable cache is not present,
	 * this method returns an empty array.
	 * Feeds are retrieved in parallel for performance.
	 * <br/><br/>
	 * Useful feeds at the time of writing:
	 * <ul>
	 * <li>http://www.theia.org.uk/News/rss/</li>
	 * <li>http://www.theia.org.uk/ilr/ilrdocuments/rss/</li>
	 * <li>http://skillsfundingagency.bis.gov.uk/news/rss/</li>
	 * <li>http://skillsfundingagency.bis.gov.uk/providers/allthelatest/rss/</li>
	 * <li>http://www.thedataservice.org.uk/News/rss/</li>
	 * <li>theia</li>
	 * <li>TheDataService</li>
	 * <li>SkillsFunding</li>
	 * </ul>
	 * @static
	 * @param array $feeds An array of newsfeed URIs and Twitter handles e.g. array("http://www.theia.org.uk/News/rss/", "@theia"). Twitter
	 * handles can be formatted with or without their @ prefix.
	 * @param int $cache_ttl How long (seconds) to cache feeds for. Default 3600 seconds (1 hour)
	 * @return array an array of arrays, in reverse date order (most recent first)
	 */
	public static function getFeeds(array $feeds, $cache_ttl=3600)
	{
		if(!Cache::isAvailable()){
			return array();
		}
		
		// Create Cache key from a hash of all URLs
		$cache_key = "";
		foreach($feeds as $feed){
			$cache_key .= $feed;
		}
		$cache_key = "NewsFeed ".sha1($cache_key);

		// Try to fulfil the request from the Cache first
		$data = Cache::get($cache_key);
		if($data){
			return $data;
		}
		
		// Convert Twitter handles to URLs
		for($i = 0; $i < count($feeds); $i++)
		{
			$feeds[$i] = trim($feeds[$i], '@ ');
			if(!preg_match('#^http#', $feeds[$i]))
			{
				$feeds[$i] = "http://api.twitter.com/1/statuses/user_timeline.json?screen_name={$feeds[$i]}&include_entities=true&include_rts=true&count=20";
			}			
		}
		
		// Download URLs
		$downloads = NewsFeed::getURLs($feeds);
		
		// Parse downloaded data
		$posts = array();
		foreach($downloads as $url=>$download)
		{
			if(preg_match('#^application/json#', $download['type']))
			{
				$items = NewsFeed::parseTwitterFeed($download['data']);
				$posts = array_merge($posts, $items);		
			}
			elseif(preg_match('#^text/xml#', $download['type']))
			{
				$xml = XML::loadSimpleXML($download['data']);
				
				$ns = $xml->getNameSpaces();
				if(in_array("http://www.w3.org/1999/02/22-rdf-syntax-ns#", $ns))
				{
					// RSS1 does not include a publication date per published item which means we cannot order its content
					throw new Exception("RSS1 feeds are not supported. Supported types: Twitter handles, RSS2 and Atom");
				}
				elseif($xml->getName() == "rss")
				{
					$items = NewsFeed::parseRSS2Feed($xml);
				}
				elseif($xml->getName() == "feed")
				{
					$items = NewsFeed::parseAtomFeed($xml);
				}
				else
				{
					throw new Exception("Unknown feed type. Supported types: Twitter handles, RSS2 and Atom");
				}

				$posts = array_merge($posts, $items);			
			}
			else
			{
				// Ignore any other MIME type
			}
		}
		
		// Sort the posts by their timestamp
		usort($posts, function($a, $b) {
			if($a['timestamp'] == $b['timestamp']){
				return 0;
			}
			return $a['timestamp'] > $b['timestamp'] ? -1 : +1; // reverse order
		});
		
		// Cache the posts for next time and return
		Cache::set($cache_key, $posts, $cache_ttl);
		return $posts;
	}

	
	/**
	 * Convenience method providing one way of rendering newsfeeds. If you need
	 * greater control, use the {@link NewsFeed::getFeeds} method to return a raw array
	 * of newsfeed data.<br/>
	 * The HTML returned by this method is structured but not formatted. Use the CSS
	 * returned by {@link NewsFeed::getCSS} as an example.<br/>
	 * Note that the width of the <b>div.NewsFeedScroller</b> expands to fill the size of its container,
	 * so if you output this on a blank HTML page it will expand to fill the size of the screen.
	 * Ensure the output from this method is rendered inside a container of fixed width.
	 * @static
	 * @param array $feeds an array of RSS URLs and Twitter handles
	 * @param int $max_posts the maximum number of posts to render (default 100)
	 * @return string HTML
	 */
	public static function getHTML(array $feeds, $max_posts = 100)
	{
		if(!Cache::isAvailable()){
			return "XCache must be enabled to use the NewsFeed class. Twitter allows only 150 hits a day.";
		}

		ob_start();
		$posts = NewsFeed::getFeeds($feeds);
		$count = 0;
		echo '<div class="NewsFeedScroller">';
		foreach($posts as $post)
		{
			echo "<div class=\"NewsFeedPost\">";
			echo "<table>";
			echo "<tr>";
			echo "<td width=\"70\" valign=\"top\">";
			if($post['type'] == 'Twitter')
			{
				echo '<div class="NewsFeedPostImage" title="', htmlspecialchars((string)$post['title']) ,'">';
			}
			else
			{
				echo '<div class="NewsFeedPostImage">';
			}

			if($post['source_image'])
			{
				echo '<a href="', $post['source_url'], "\" target=\"_blank\"><image border=\"0\" src=\"",
					$post['source_image'], "\"/></a>";
			}
			else
			{
				echo $post['source_title'];
			}
			echo '</div>';
			echo "</td>";
			echo "<td valign=\"top\">";
			if($post['type'] == 'ATOM' || $post['type'] == 'RSS'){
				echo '<div class="NewsFeedPostTitle"><a href="', $post['url'], '" target=\"_blank\">', $post['title'], '</a></div>';
			}
			echo '<div class="NewsFeedPostText">', $post['text'], '</div>';
			echo '<div class="NewsFeedPostDate">', Date::to($post['timestamp'], "jS M Y H:i"), '</div>';
			echo '</td>';
			echo '</tr>';
			echo '</table>';
			echo '</div>';
			
			if($count++ >= $max_posts){
				break;
			}
		}
		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Example formatting to be used in conjunction with {@link NewsFeed::getHTML}.
	 * Note that the width of <b>div.NewsFeedScroller</b> is intentionally not set
	 * so that it will expand to fill the size of its container.
	 * @static
	 * @return string CSS
	 */
	public static function getCSS()
	{
		return <<<CSS
div.NewsFeedScroller{
	border: 1px solid gray;
	height: 300px;
	overflow: scroll;
	margin-top: 10px;
}

div.NewsFeedPost{
	border-top: solid silver 1px;
	background-color: white;
}

div.NewsFeedPost:first-child{
	border-top: none;
}

div.NewsFeedPostTitle{
	font-size:10pt;
	margin-bottom: 5px;
	margin-top: 3px;
}

div.NewsFeedPostTitle a{
	color: #CC4A31;
	font-weight: normal;
	text-decoration:none;
}

div.NewsFeedPostTitle a:hover{
	text-decoration: underline;
}

div.NewsFeedPostDate{
	font-size: 0.8em;
	color: gray;
	margin-top: 5px;
	text-align: right;
}

div.NewsFeedPostImage{
	font-size: 8pt;
	color: #444444;
	width: 64px;
	margin-top: 3px;
}

div.NewsFeedPostText{
	color: #333333;
	font-size: 9pt;
}

div.NewsFeedPostText a{
	color: #CC4A31;
}
CSS;
	}
	
	/**
	 * @static
	 * @param string $json
	 * @return array
	 */
	private static function parseTwitterFeed($json)
	{
		$tweets = json_decode($json);
		
		$posts = array();
		foreach($tweets as $tweet)
		{
			$post = array();
			$post['timestamp'] = strtotime($tweet->created_at);
			$post['title'] = '@'.$tweet->user->screen_name;
			$post['text'] = Text::utf8_to_latin1($tweet->text);
			$post['url'] = 'http://www.twitter.com/'.$tweet->user->screen_name;
			$post['source_title'] = $tweet->user->name;
			$post['source_description'] = $tweet->user->description;
			$post['source_url'] = 'http://www.twitter.com/'.$tweet->user->screen_name;
			$post['source_image'] = isset($tweet->user->profile_image_url_https) ? $tweet->user->profile_image_url_https:null;
			$post['type'] = "Twitter";
			
			// Replace tiny URLs with full URLs
			if(isset($tweet->entities->urls))
			{
				foreach($tweet->entities->urls as $url)
				{
					if(!isset($url->display_url)){
						continue;
					}
					$html = '<a href="'.$url->expanded_url.'" target="_blank">'.Text::utf8_to_latin1($url->display_url).'</a>';
					$post['text'] = str_replace($url->url, $html, $post['text']);
				}
			}
			
			// Replace Twitter handles with URLs
			if(isset($tweet->entities->user_mentions))
			{
				foreach($tweet->entities->user_mentions as $user)
				{
					$html = '<a href="http://www.twitter.com/'.$user->screen_name.'" target="_blank" title="@'.$user->screen_name.'">'.$user->name.'</a>';
					$post['text'] = str_replace('@'.$user->screen_name, $html, $post['text']);
				}
			}
			
			// Replace hashtags with search URLs
			$post['text'] = preg_replace('/#(\\w+)/', '<a href="http://twitter.com/#!/search?q=%23$1" target="_blank">#$1</a>', $post['text']);
			
			$posts[] = $post;
		}
		
		return $posts;
	}
	
	/**
	 * @static
	 * @param SimpleXMLElement $xml
	 * @return array
	 */
	private static function parseRSS2Feed(SimpleXMLElement $xml)
	{
		$source_title = count($xml->channel->title) ? Text::utf8_to_latin1((string)$xml->channel->title) : '';
		$source_description = count($xml->channel->description) ? Text::utf8_to_latin1((string)$xml->channel->description) : '';
		$source_url = count($xml->channel->link) ? (string)$xml->channel->link : '';
		$source_image = '';
		if(count($xml->channel->image)){
			$source_image = count($xml->channel->image->url) ? (string)$xml->channel->image->url : '';
		}
		
		$posts = array();
		foreach($xml->channel->item as $item)
		{
			$post = array();
			$post['timestamp'] = strtotime((string)$item->pubDate);
			$post['title'] = Text::utf8_to_latin1((string)$item->title);
			$post['url'] = (string)$item->link;
			$post['text'] = Text::utf8_to_latin1((string)$item->description);
			$post['source_title'] = $source_title;
			$post['source_description'] = $source_description;
			$post['source_url'] = $source_url;
			$post['source_image'] = $source_image;
			$post['type'] = 'RSS';
			$posts[] = $post;
		}
		
		return $posts;
	}
	
	/**
	 * @static
	 * @param SimpleXMLElement $xml
	 * @return array
	 */
	private static function parseAtomFeed(SimpleXMLElement $xml)
	{
		$source_title = count($xml->title) ? Text::utf8_to_latin1((string)$xml->title) : '';
		$source_description = count($xml->subtitle) ? Text::utf8_to_latin1((string)$xml->subtitle) : '';
		$source_url = count($xml->link) ? (string)$xml->link[0] : '';
		$source_image = '';
		
		$posts = array();
		foreach($xml->entry as $item)
		{
			$post = array();
			$post['timestamp'] = strtotime((string)$item->updated);
			$post['title'] = Text::utf8_to_latin1((string)$item->title);
			$post['url'] = (string)$item->link[0];
			$post['text'] = Text::utf8_to_latin1((string)$item->summary);
			$post['source_title'] = $source_title;
			$post['source_description'] = $source_description;
			$post['source_url'] = $source_url;
			$post['source_image'] = $source_image;
			$post['type'] = 'ATOM';
			$posts[] = $post;
		}
		
		return $posts;		
	}
	
	
	/**
	 * Download HTTP resources in parallel
	 * @param array $urls
	 * @return array
	 */
	private static function getURLs(array $urls)
	{
		if(count($urls) == 0){
			return array();
		}
		
		// Holds the pages returned from calling the URLs
		$pages = array();
		
		// Initialise
		$multi_curl = curl_multi_init();
		$curls = array();
		foreach($urls as $url)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2) Gecko/20100115 Firefox/3.6");
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curl, CURLOPT_TIMEOUT, 7);
			$curls[] = $curl;
			curl_multi_add_handle($multi_curl, $curl);
		}

		// Connect and process responses (the liklihood of data being returned immediately must be almost zero)
		$active = null;
		do{
			$mrc = curl_multi_exec($multi_curl, $active); // Process handles in the stack
		} while ($mrc == CURLM_CALL_MULTI_PERFORM); // Keep processing until all data has been read
		if($mrc != CURLM_OK){
			NewsFeed::curl_clean_up($multi_curl, $curls);
			return array();
		}
		
		// Wait for further activity and then process
		do 
		{
			$descriptors = curl_multi_select($multi_curl, 1); // BLOCK until there's data to read or the block times out (at 1 second)
			if($descriptors > -1)
			{
				do{
					$mrc = curl_multi_exec($multi_curl, $active); // Process handles in the stack
				} while ($mrc == CURLM_CALL_MULTI_PERFORM); // Keep processing until all data has been read
			}
		} while ($active && $mrc == CURLM_OK); // Keep looping while there are pages to process and there are no multi-errors.
		if($mrc != CURLM_OK){
			NewsFeed::curl_clean_up($multi_curl, $curls);
			return array();
		}
	
		// Retrieve content
		$i = 0;
		foreach($curls as $curl)
		{
			if(curl_error($curl) == CURLE_OK){
				$pages[$urls[$i]] = array("type"=>curl_getinfo($curl, CURLINFO_CONTENT_TYPE), "data"=>curl_multi_getcontent($curl));
			}
			$i++;
		}

		NewsFeed::curl_clean_up($multi_curl, $curls);
		
		return $pages;
	}

	/**
	 * @static
	 * @param resource $multi_handle
	 * @param array $curl_handles
	 */
	private static function curl_clean_up($multi_handle, array $curl_handles)
	{
		foreach($curl_handles as $ch){
			curl_multi_remove_handle($multi_handle, $ch);
			curl_close($ch);
		}
		curl_multi_close($multi_handle);
	}
}

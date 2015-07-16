<?php
/**
 * BP_Links Embed Service classes
 *
 * @package BP_Links
 * @author Marshall Sorenson
 */

/**
 * Video sites by reach:
 * YouTube*, Flickr*, MetaCafe*, Hulu (no api), Veoh, Vimeo, ustream.tv, BlinkX, Revver
 *
 * Photo sites by reach:
 * Flickr*, PhotoBucket, PicasaWeb, TwitPic, BuzzNet, twitgoo, imageshack.us?
 *
 * Just keeping track of thumb sizes here...
 * (so far the sweet spot is 100 to 120 pixels)
 * ---------------------------------
 * YouTube = 120x90 or 130x97 wtf guys??
 * MetaCafe = 136x81
 * Flickr = 75x75, 100xN, 240xN
 * PicasaWeb = tons of options
 *		http://picasaweb.google.com/data/feed/api/user/joe.geiger/photoid/5410429216279597890?thumbsize=150u&imgmax=512u
 */

/**
 * Generic web page embedding service
 *
 * @package BP_Links
 * @author Marshall Sorenson
 */
final class BP_Links_Embed_Service_WebPage
	extends BP_Links_Embed_Service
		implements	BP_Links_Embed_From_Url,
					BP_Links_Embed_From_Html,
					BP_Links_Embed_Has_Selectable_Image
{

	// max number of images to grab from page
	const WEBPAGE_MAX_IMAGES = 12;
	const WEBPAGE_MAX_IMAGE_HEAD = 24;
	const WEBPAGE_MIN_IMAGE_BYTES = 2048;
	const WEBPAGE_MAX_IMAGE_BYTES = 51200;

	/**
	 * @var BP_Links_Embed_Page_Parser
	 */
	private $parser;

	//
	// required concrete methods
	//

	final public function from_url( $url )
	{
		if ( bp_links_is_url_valid( $url ) ) {

			$this->data()->url = $url;

			$page_parser = BP_Links_Embed_Page_Parser::GetInstance();

			if ( $page_parser->from_url( $url ) ) {
				$this->parser = $page_parser;
				return $this->find_elements();
			}
		}
		
		return false;
	}

	final public function from_html( $html )
	{
		$page_parser = BP_Links_Embed_Page_Parser::GetInstance();

		if ( $page_parser->from_html( $html ) ) {
			$this->parser = $page_parser;
			return $this->find_elements();
		}

		return false;
	}

	final public function url()
	{
		return $this->data()->url;
	}

	final public function title()
	{
		return $this->data()->title;
	}

	final public function description()
	{
		return $this->data()->description;
	}

	final public function image_url()
	{
		if ( isset( $this->data()->images_idx ) ) {
			$idx = $this->data()->images_idx;
			return $this->data()->images[$idx]['src'];
		}
		
		return null;
	}

	final public function image_thumb_url()
	{
		return $this->image_url();
	}

	final public function image_large_thumb_url()
	{
		return $this->image_url();
	}

	final public function service_name()
	{
		return __( 'Web Page', 'buddypress-links' );
	}

	final public function from_url_pattern()
	{
		return '/^https?:\/\/([a-z0-9-]+\.)+[a-z0-9-]{2,4}\/?.*/i';
	}

	final public function image_selection()
	{
		$image_array = array();

		foreach ( $this->data()->images as $image ) {
			if ( isset( $image['bytes'] ) && $image['bytes'] >= self::WEBPAGE_MIN_IMAGE_BYTES && $image['bytes'] <= self::WEBPAGE_MAX_IMAGE_BYTES ) {
				$image_array[] = $image['src'];
			}
		}

		return $image_array;
	}

	final public function image_set_selected( $index )
	{
		// do some sanity checking
		if ( is_numeric( $index ) && $index <= self::WEBPAGE_MAX_IMAGES ) {
			// cast to integer
			$idx = (integer) $index;
			// must exist in found images array
			if ( array_key_exists( $idx, $this->data()->images ) ) {
				// ok, set the index
				$this->data()->images_idx = (integer) $idx;
				return true;
			}
		} else {
			$this->data()->images_idx = null;
		}

		return false;
	}

	final public function image_get_selected()
	{
		return ( isset( $this->data()->images_idx ) ) ? $this->data()->images_idx : null;
	}

	//
	// private methods
	//
	
	private function find_elements()
	{
		//
		// try to get the title
		//
		$page_title = $this->parser->title();

		if ( !empty( $page_title ) ) {
			$this->data()->title = $page_title;
		} else {
			return false;
		}

		//
		// try to get the description
		//
		$page_desc = $this->parser->description();

		if ( !empty( $page_desc ) ) {
			$this->data()->description = $page_desc;
		} else {
			$this->data()->description = null;
		}

		//
		// try to find some images
		//
		$page_images = $this->parser->images( 100, 800, 2, 50 );
		$page_images_sorted = $this->filter_images( $page_images );
		$page_images_bytes = $this->get_images_bytes( $page_images_sorted );

		if ( is_array( $page_images_bytes ) && count( $page_images_bytes ) ) {

			// use the array as is
			$this->data()->images = $page_images_bytes;

			// set the default index if image selection has at least one entry
			if ( count( $this->image_selection() ) ) {
				// set index to first key from array
				$this->data()->images_idx = key( $page_images_bytes );
			} else {
				// no images returned by selection method
				$this->data()->images_idx = null;
			}
			
		} else {
			return false;
		}

		return true;
	}

	// TODO implement a timeout based on total seconds elapsed
	private function get_images_bytes( $images )
	{
		global $bp;

		if ( count( $images ) < 1 ) {
			return $images;
		}
		
		$return_array = array();
		$checked_count = 0;
		$good_count = 0;

		foreach ( $images as $image ) {

			// don't loop forever
			$checked_count++;

			// bytes are null by default
			$image['bytes'] = null;

			// run checks only if we are under thresholds
			if ( $checked_count < self::WEBPAGE_MAX_IMAGE_HEAD && $good_count < self::WEBPAGE_MAX_IMAGES ) {

				// do a head request for the image
				$response =
					wp_remote_head(
						$image['src'],
						array( 'timeout' => 2, 'headers' => array( 'Referer' => $bp->root_domain ) )
					);

				// did we get a valid response?
				if ( 200 == wp_remote_retrieve_response_code( $response ) ) {
					// yes, grab content length (remote file size)
					$content_length = (integer) wp_remote_retrieve_header( $response, 'content-length' );
					// did we get the length header?
					if ( $content_length > 0 ) {
						// set the bytes
						$image['bytes'] = $content_length;
						// check if size is within range
						if ( $content_length >= self::WEBPAGE_MIN_IMAGE_BYTES && $content_length <= self::WEBPAGE_MAX_IMAGE_BYTES ) {
							// increment found count
							$good_count++;
						}
					}
				}
			}

			// append modified image details to return array
			$return_array[] = $image;
		}

		return $return_array;
	}
	
	private function filter_images( $images )
	{
		if ( count( $images ) < 1 ) {
			return $images;
		}

		$array_high = array();
		$array_med = array();
		$array_low = array();
		$array_lowest = array();

		foreach ( $images as $image ) {

			$url = $image['src'];
			$width = $image['width'];
			$height = $image['height'];

			// try to parse url
			$url_parsed = parse_url( $url );

			if ( isset( $url_parsed['path'] ) ) {
				// parsed url successfully
				if ( preg_match( '/\.jpe?g/i', $url_parsed['path'] ) ) {
					// its a JPEG
					if ( $width > 0 && $height > 0 ) {
						// width and height were set in DOM
						$array_high[] = $image;
					} else {
						// width and/or height missing from DOM
						$array_med[] = $image;
					}
				} elseif ( preg_match( '/\.png/i', $url_parsed['path'] ) ) {
					// PNG, lower priority
					$array_low[] = $image;
				} elseif ( preg_match( '/\.gif/i', $url_parsed['path'] ) ) {
					// GIF, lowest priority
					$array_lowest[] = $image;
				} else {
					// some other image type, we don't want it
					continue;
				}
			} else {
				// unable to parse url
				continue;
			}
		}

		return array_merge( $array_high, $array_med, $array_low, $array_lowest );
	}
}


/**
 * YouTube video embedding service
 *
 * Known URL formats:
 *   1) https://www.youtube.com/watch?v=qjzLuddjIUI
 *   2) https://youtu.be/qjzLuddjIUI
 *
 * @package BP_Links
 * @author Marshall Sorenson
 */
final class BP_Links_Embed_Service_YouTube
	extends BP_Links_Embed_Service
		implements BP_Links_Embed_From_Url, BP_Links_Embed_From_Json, BP_Links_Embed_Has_Html
{
	// thumb constants
	const YT_TH_DEFAULT = 0;
	const YT_TH_SMALL_1 = 1;
	const YT_TH_SMALL_2 = 2;
	const YT_TH_SMALL_3 = 3;

	//
	// required concrete methods
	//

	final public function from_url( $url )
	{
		if ( $this->check_url( $url ) ) {
			if ( $this->parse_url( $url ) === true ) {
				return $this->from_json( $this->api_oembed_fetch() );
			} else {
				throw new BP_Links_Embed_User_Exception( $this->err_embed_url() );
			}
		} else {
			return false;
		}
	}

	final public function from_json( $json )
	{
		// decode the json string
		$result = json_decode( $json );

		if ( $result instanceof stdClass ) {

			// set data items
			$this->data()->oembed_title = $this->deep_clean_string( (string) $result->title );
			$this->data()->oembed_thumbnail_url = $this->deep_clean_string( (string) $result->thumbnail_url );
			$this->data()->oembed_html = $this->deep_clean_string( (string) $result->html );

			return true;

		} else {
			// could not load feed data
			throw new BP_Links_Embed_User_Exception( $this->err_api_fetch() );
		}
	}

	final public function url()
	{
		// have oembed url?
		if ( true === isset( $this->data()->oembed_url ) ) {
			// yep use it
			return $this->data()->oembed_url;
		// back compat
		} else {
			// return old YT link
			return $this->data()->api_link_alt;
		}
	}

	final public function title()
	{
		// have oembed title?
		if ( true === isset( $this->data()->oembed_title ) ) {
			// yep, use it
			return $this->data()->oembed_title;
		// back compat
		} else {
			// return old YT title
			return $this->data()->api_title;
		}
	}

	final public function description()
	{
		// only old YT api provided description
		if ( true === isset( $this->data()->api_content ) ) {
			return $this->data()->api_content;
		}
	}

	final public function image_url()
	{
		return $this->yt_thumb_url();
	}

	final public function image_thumb_url()
	{
		return $this->yt_thumb_url( self::YT_TH_SMALL_2 );
	}

	final public function image_large_thumb_url()
	{
		return $this->yt_thumb_url( self::YT_TH_DEFAULT );
	}

	final public function html()
	{
		// have oembed html?
		if ( true === isset( $this->data()->oembed_html ) ) {
			// yep, use it
			return $this->data()->oembed_html;
		} else {
			// try to launch old player
			return sprintf(
				'<object width="640" height="385" style="height: 385px;">' .
				'<param name="movie" value="%1$s"></param>' .
				'<param name="allowFullScreen" value="true"></param>' .
				'<param name="allowscriptaccess" value="always"></param>' .
				'<embed src="%1$s" type="application/x-shockwave-flash" ' .
					'allowscriptaccess="always" allowfullscreen="true" ' .
					'width="640" height="385" style="height: 385px;"></embed>' .
				'</object>',
				esc_url( $this->yt_player_url() )
			);
		}
	}

	public function service_name()
	{
		return __( 'YouTube', 'buddypress-links' );
	}

	public function from_url_pattern()
	{
		return '#^https?:\/\/(www\.)?(youtube\.com\/watch|youtu\.be\/)#';
	}

	//
	// optional concrete methods
	//

	final public function avatar_play_video()
	{
		return true;
	}

	//
	// private methods
	//

	private function check_url( $url )
	{
		return preg_match( '#^https?:\/\/(www\.)?(youtube\.com\/watch|youtu\.be\/).+$#', $url );
	}

	private function parse_url( $url )
	{
		// store url
		$this->data()->oembed_url = (string) $url;
		
		// parse the url
		$url_parsed = parse_url( $url );

		// is it the vanity url?
		if (
			false === empty( $url_parsed['host'] ) &&
			false === empty( $url_parsed['path'] ) &&
			'youtu.be' === $url_parsed['host']
		) {

			// yep, split the path up
			$path_elements = array_filter( explode( '/', $url_parsed['path'] ) );
			// the first path element is the video hash
			$this->data()->video_hash = current( $path_elements );
			// success
			return true;

		} elseif ( false === empty( $url_parsed['query'] ) ) {

			// parse the query string
			parse_str( $url_parsed['query'], $qs_vars );

			// get the video hash
			if ( false === empty( $qs_vars['v'] ) ) {
				// found it!
				$this->data()->video_hash = $qs_vars['v'];
				// success
				return true;
			}
		}

		// failed to parse url
		return false;
	}

	private function api_oembed_url()
	{
		return 'http://www.youtube.com/oembed?format=json&url=' . urlencode( $this->data()->oembed_url );
	}

	private function api_oembed_fetch()
	{
		// get oembed JSON data for this video
		return $this->api_fetch( $this->api_oembed_url() );
	}

	private function yt_player_url()
	{
		return sprintf( 'http://www.youtube.com/v/%s&hl=%s&fs=1&&autoplay=1', $this->data()->video_hash, get_locale() );
	}

	private function yt_thumb_url( $num = self::YT_TH_DEFAULT )
	{
		// oembed thumbnail set?
		if ( true === isset( $this->data()->oembed_thumbnail_url ) ) {

			// yes, use it
			return $this->data()->oembed_thumbnail_url;

		// back compat with old YT data?
		} elseif ( is_numeric( $num ) && $num >= self::YT_TH_DEFAULT && $num <= self::YT_TH_SMALL_3 ) {

			// return old style image url
			return sprintf( 'http://img.youtube.com/vi/%s/%d.jpg', $this->data()->video_hash, $num );

		} else {
			// fatal
			throw new BP_Links_Embed_Fatal_Exception( 'YouTube thumbnail number must 0, 1, 2, or 3.' );
		}
	}
}

/**
 * Flickr photo and video embedding service
 *
 * @link http://www.flickr.com/services/api/
 * @package BP_Links
 * @author Marshall Sorenson
 */
final class BP_Links_Embed_Service_Flickr
	extends BP_Links_Embed_Service
		implements BP_Links_Embed_From_Url, BP_Links_Embed_From_Json, BP_Links_Embed_Has_Html
{
	// Flickr API keys
	const FLICKR_API_KEY = 'e5fe3652529c0f75332019c3605cd46e';
	const FLICKR_API_SECRET = '7600876afd78c7a2';

	// Flickr media types
	const FLICKR_MEDIA_PHOTO = 'photo';
	const FLICKR_MEDIA_VIDEO = 'video';

	// Flickr image sizes
	const FLICKR_IMAGE_SQUARE = 's'; // 75 x 75
	const FLICKR_IMAGE_THUMB = 't'; // 100 x N
	const FLICKR_IMAGE_SMALL = 'm'; // 240 x N
	const FLICKR_IMAGE_MEDIUM = null; // 500 x N
	const FLICKR_IMAGE_LARGE = 'b'; // 1024 x N
	
	//
	// required concrete methods
	//

	final public function from_url( $url )
	{
		if ( $this->check_url( $url ) ) {
			if ( $this->parse_url( $url ) === true ) {
				return $this->from_json( $this->api_json_fetch( 'flickr.photos.getInfo' ) );
			} else {
				throw new BP_Links_Embed_User_Exception( $this->err_embed_url() );
			}
		} else {
			return false;
		}
	}

	final public function from_json( $json )
	{
		// decode json data
		$api_data = json_decode( $json, true );

		// if decoding successful, add details to embed data
		if ( !empty( $api_data[self::FLICKR_MEDIA_PHOTO] ) ) {

			// photo array
			$photo = $api_data[self::FLICKR_MEDIA_PHOTO];

			// copy values
			$this->data()->api_id = $photo['id'];
			$this->data()->api_secret = $photo['secret'];
			$this->data()->api_server = $photo['server'];
			$this->data()->api_farm = $photo['farm'];
			$this->data()->api_license = $photo['license'];
			$this->data()->api_title = $this->deep_clean_string( $photo['title']['_content'] );
			$this->data()->api_description = $this->deep_clean_string( $photo['description']['_content'] );

			// try for media type
			switch ( $photo['media'] ) {
				case self::FLICKR_MEDIA_PHOTO:
					$this->data()->api_media = self::FLICKR_MEDIA_PHOTO;
					break;
				case self::FLICKR_MEDIA_VIDEO:
					$this->data()->api_media = self::FLICKR_MEDIA_VIDEO;
					break;
				default:
					throw new BP_Links_Embed_User_Exception( $this->err_api_fetch() );
			}
			
			// try for the url
			if ( !empty( $photo['urls']['url'][0]['type'] ) && $photo['urls']['url'][0]['type'] == 'photopage' ) {
				$this->data()->api_url = $this->deep_clean_string( $photo['urls']['url'][0]['_content'] );
			}

			// make sure we REALLY have a good url
			if ( $this->parse_url( $this->data()->api_url ) !== true ) {
				throw new BP_Links_Embed_User_Exception( $this->err_api_fetch() );
			}
			
			// made it!
			return true;
			
		} else {
			throw new BP_Links_Embed_User_Exception( $this->err_api_fetch() );
		}
	}

	final public function url()
	{
		return $this->data()->api_url;
	}

	final public function title()
	{
		return $this->data()->api_title;
	}

	final public function description()
	{
		return $this->data()->api_description;
	}

	final public function image_url()
	{
		return $this->flickr_image( self::FLICKR_IMAGE_MEDIUM );
	}

	final public function image_thumb_url()
	{
		return $this->flickr_image( self::FLICKR_IMAGE_THUMB );
	}

	final public function image_large_thumb_url()
	{
		return $this->flickr_image( self::FLICKR_IMAGE_SMALL );
	}

	final public function html()
	{
		switch ( $this->data()->api_media ) {
			case self::FLICKR_MEDIA_PHOTO:
				return $this->html_photo();
			case self::FLICKR_MEDIA_VIDEO:
				return $this->html_video();
			default:
				// this should never happen!
				return null;
		}
	}

	final public function service_name()
	{
		return __( 'Flickr', 'buddypress-links' );
	}

	final public function from_url_pattern()
	{
		return '/^https?:\/\/(www\.)?flickr\.com\/photos\/[^\/]+\/\d+\//';
	}

	//
	// optional concrete methods
	//

	final public function avatar_play_photo()
	{
		return ( self::FLICKR_MEDIA_PHOTO == $this->data()->api_media );
	}

	final public function avatar_play_video()
	{
		return ( self::FLICKR_MEDIA_VIDEO == $this->data()->api_media );
	}

	//
	// private methods
	//

	private function check_url( $url )
	{
		return preg_match( $this->from_url_pattern(), $url );
	}

	private function parse_url( $url )
	{
		// parse the url
		$url_parsed = parse_url( $url );

		// make sure we got something
		if ( !empty( $url_parsed['path'] ) ) {

			// get the video id
			if ( preg_match( '/^\/photos\/[^\/]+\/(\d+)\//', $url_parsed['path'], $matches ) ) {
				// must save this as a string, as its too long to be an integer
				$this->data()->photo_id = (string) $matches[1];
				return true;
			}
		}

		return false;
	}

	private function flickr_image( $size = self::FLICKR_IMAGE_MEDIUM )
	{
		$suffix = ( $size ) ? '_' . $size : null;
		
		return sprintf(
			'http://farm%1$s.static.flickr.com/%2$s/%3$s_%4$s%5$s.jpg',
			$this->data()->api_farm, // arg 1
			$this->data()->api_server, // arg 2
			$this->data()->api_id, // arg 3
			$this->data()->api_secret, // arg 4
			$suffix // arg 5
		);
	}

	private function html_photo()
	{
		return sprintf(
			'<img src="%1$s" alt="%2$s">',
			esc_url( $this->image_url() ),
			esc_attr( $this->data()->api_title )
		);
	}

	private function html_video()
	{
		return sprintf(
			'<object type="application/x-shockwave-flash" width="500" height="281"
				data="https://www.flickr.com/apps/video/stewart.swf?v=1535363810"
				classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
				<param name="flashvars" value="intl_lang=en-us&photo_secret=%2$s&photo_id=%1$s&flickr_show_info_box=true"></param>
				<param name="movie" value="https://www.flickr.com/apps/video/stewart.swf?v=1535363810"></param>
				<param name="bgcolor" value="#000000"></param> <param name="allowFullScreen" value="true"></param>
				<embed type="application/x-shockwave-flash"
					src="https://www.flickr.com/apps/video/stewart.swf?v=1535363810"
					bgcolor="#000000" allowfullscreen="true"
					flashvars="intl_lang=en-us&photo_secret=%2$s&photo_id=%1$s&flickr_show_info_box=true"
					height="281" width="500">
				</embed>
			</object>',
			esc_attr( $this->data()->api_id ), // arg 1
			esc_attr( $this->data()->api_secret ) // arg 2
		);
	}

	private function api_rest_url( $method, $format = 'rest' )
	{
		return sprintf( 'https://api.flickr.com/services/rest/?method=%1$s&photo_id=%2$s&format=%3$s&api_key=%4$s&nojsoncallback=1', $method, $this->data()->photo_id, $format, self::FLICKR_API_KEY );
	}

	private function api_json_fetch( $method )
	{
		// get RSS2 feed data for this video
		return $this->api_fetch( $this->api_rest_url( $method, 'json' ) );
	}
}

/**
 * MetaCafe video embedding service
 *
 * Fetching new content has been disabled as of 0.9.4 since the API is more or less defunct now.
 *
 * @link http://help.metacafe.com/?page_id=181
 * @package BP_Links
 * @author Marshall Sorenson
 */
final class BP_Links_Embed_Service_MetaCafe
	extends BP_Links_Embed_Service
		implements BP_Links_Embed_From_Url, BP_Links_Embed_From_Xml, BP_Links_Embed_Has_Html
{

	//
	// required concrete methods
	//

	final public function from_url( $url )
	{
		// don't try to fetch content anymore
		return false;
	}

	final public function from_xml( $xml )
	{
		// load xml string into a SimpleXML object
		libxml_use_internal_errors(true);
		$sxml = simplexml_load_string( $xml );

		if ( $sxml instanceof SimpleXMLElement ) {

			// get nodes in media: namespace for media information
			$media = $sxml->channel->item->children('http://search.yahoo.com/mrss/');

			// do we have media namespace to look at?
			if ( $media instanceof SimpleXMLElement ) {
				// set title and content
				$this->data()->api_title = $this->deep_clean_string( (string) $media->title );
				$this->data()->api_description = $this->deep_clean_string( (string) $media->description );

				$cont_attrs = $media->content->attributes();
				$this->data()->api_content_url = $this->deep_clean_string( (string) $cont_attrs['url'] );
			} else {
				return false;
			}

			// set alternate link
			$this->data()->api_link_alt = (string) $sxml->channel->item->link;

			// make sure we have an alternate link
			if ( empty( $this->data()->api_link_alt ) === false ) {
				// set video id if missing
				if ( empty( $this->data()->video_id ) ) {
					$this->parse_url( $this->data()->api_link_alt );
				}
			} else {
				throw new BP_Links_Embed_User_Exception( $this->err_api_fetch() );
			}

			return true;

		} else {
			// could not load feed data
			throw new BP_Links_Embed_User_Exception( $this->err_api_fetch() );
		}
	}

	final public function url()
	{
		return $this->data()->api_link_alt;
	}

	final public function title()
	{
		return $this->data()->api_title;
	}

	final public function description()
	{
		return $this->data()->api_description;
	}

	final public function image_url()
	{
		return $this->image_thumb_url();
	}

	final public function image_thumb_url()
	{
		return sprintf( 'http://www.metacafe.com/thumb/%s.jpg', $this->data()->video_id );
	}

	final public function image_large_thumb_url()
	{
		return $this->image_thumb_url();
	}

	final public function html()
	{
		return sprintf(
			'<embed src="%1$s"
				width="498" height="423" wmode="transparent"
				pluginspage="http://www.macromedia.com/go/getflashplayer"
				type="application/x-shockwave-flash" allowFullScreen="true"
				allowScriptAccess="always" name="Metacafe_%2$s">
			</embed>',
			esc_url( $this->data()->api_content_url ),
			$this->data()->video_id
		);
	}

	public function service_name()
	{
		return __( 'MetaCafe', 'buddypress-links' );
	}

	public function from_url_pattern()
	{
		return '/^https?:\/\/(www\.)?metacafe.com\/watch\//';
	}

	//
	// optional concrete methods
	//

	final public function avatar_play_video()
	{
		return true;
	}

	//
	// private methods
	//

	private function check_url( $url )
	{
		return preg_match( $this->from_url_pattern(), $url );
	}

	private function parse_url( $url )
	{
		// parse the url
		$url_parsed = parse_url( $url );

		// make sure we got something
		if ( !empty( $url_parsed['path'] ) ) {

			// get the video id
			if ( preg_match( '/^\/watch\/(\d+)\//', $url_parsed['path'], $matches ) ) {
				// save this as a string in case its a huge integer!
				$this->data()->video_id = (string) $matches[1];
				return true;
			}
		}

		return false;
	}

	private function api_xml_url()
	{
		return sprintf( 'http://www.metacafe.com/api/item/%s', $this->data()->video_id );
	}

	private function api_xml_fetch()
	{
		// get RSS2 feed data for this video
		return $this->api_fetch( $this->api_xml_url() );
	}
}
?>

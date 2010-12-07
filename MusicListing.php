<?php
/**
 * MusicListing
 * @author Joris Berthelot <admin@eexit.net>
 * @version 3.0 2010-12-06
 */
class MusicListing extends Listing
{
    /**
     * Application version
     */
    const VERSION = 3.0;
    
    /**
     * Full file URL base to generate m3u playlist
     */
    const URL = 'http://foo.tld/bar/';
    
    public function playlist()
	{
		$stream = '#EXTM3U' . "\n";
		
		foreach($this->_files as $mp3 => $size) {
			$stream .= sprintf('#EXTINF:-1,%s%s' . "\n", self::URL, $mp3);
			$stream .= sprintf('%s%s' . "\n", self::URL, rawurlencode($mp3));
		}
		
		file_put_contents('playlist.m3u', $stream, LOCK_EX);
	}
}
?>
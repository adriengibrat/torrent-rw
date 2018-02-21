Torrent RW

PHP version 5.2+

**THIS PROJECT IS NOT MAINTAINED**

I don't use nor have time to maintain this Class. Maintainer are welcome!

It was a great pet project started as an exercice to go from [RFC](http://jonas.nitro.dk/bittorrent/bittorrent-rfc.html) to small & simple implementation, thanks for all the issues, Pull request and enthousiasm around this hackish piece of PHP code (maybe the most dirty API I ever wrote).

If you need alternatives:
- https://github.com/christeredvartsen/php-bittorrent
- https://github.com/Devristo/torrent
- https://github.com/johannes85/PHP-Torrent-Scraper
and keep looking for [active PHP projects around torrents](https://github.com/search?l=PHP&o=desc&q=torrent&s=updated&type=Repositories)

LICENSE: This source file is subject to version 3 of the GNU GPL
that is available through the world-wide-web at the following URI:
http://www.gnu.org/licenses/gpl.html.  If you did not receive a copy of
the GNU GPL License and are unable to obtain it through the web, please
send a note to adrien.gibrat@gmail.com so I can mail you a copy.

1) Features:
- Decode torrent file or data
- Build torrent from source folder/file(s)
- Silent Exception error system

2) Usage example
```php
require_once 'Torrent.php';

// get torrent infos
$torrent = new Torrent( './test.torrent' );
echo '<br>private: ', $torrent->is_private() ? 'yes' : 'no', 
	 '<br>annonce: ', $torrent->announce(), 
	 '<br>name: ', $torrent->name(), 
	 '<br>comment: ', $torrent->comment(), 
	 '<br>piece_length: ', $torrent->piece_length(), 
	 '<br>size: ', $torrent->size( 2 ),
	 '<br>hash info: ', $torrent->hash_info(),
	 '<br>stats: ';
var_dump( $torrent->scrape() );
echo '<br>content: ';
var_dump( $torrent->content() );
echo '<br>source: ',
	 $torrent;

// get magnet link
$torrent->magnet(); // use $torrent->magnet( false ); to get non html encoded ampersand

// create torrent
$torrent = new Torrent( array( 'test.mp3', 'test.jpg' ), 'http://torrent.tracker/annonce' );
$torrent->save('test.torrent'); // save to disk

// modify torrent
$torrent->announce('http://alternate-torrent.tracker/annonce'); // add a tracker
$torrent->announce(false); // reset announce trackers
$torrent->announce(array('http://torrent.tracker/annonce', 'http://alternate-torrent.tracker/annonce')); // set tracker(s), it also works with a 'one tracker' array...
$torrent->announce(array(array('http://torrent.tracker/annonce', 'http://alternate-torrent.tracker/annonce'), 'http://another-torrent.tracker/annonce')); // set tiered trackers
$torrent->comment('hello world');
$torrent->name('test torrent');
$torrent->is_private(true);
$torrent->httpseeds('http://file-hosting.domain/path/'); // Bittornado implementation
$torrent->url_list(array('http://file-hosting.domain/path/','http://another-file-hosting.domain/path/')); // GetRight implementation

// print errors
if ( $errors = $torrent->errors() )
	var_dump( $errors );

// send to user
$torrent->send();
```

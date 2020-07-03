<?php

declare(strict_types=1);

use App\Torrent;

require __DIR__ . '/../vendor/autoload.php';

$file    = './bin/Torrent RW Demo.torrent';
$torrent = new Torrent($file);
$br      = php_sapi_name() === 'cli' ? PHP_EOL : "\n";
if (!isset($argv[1]) || $argv[1] !== 'send') {
    echo
        $br . 'private:      ', $torrent->is_private() ? 'yes' : 'no',
        $br . 'announce:     ';
    if (is_array($torrent->announce())) {
        echo 'array (';
        foreach ($torrent->announce() as $index => $tracker) {
            echo $br . '    ' . $tracker[0];
        }
        echo $br . ')';
    } else {
        echo $torrent->announce();
    }
    echo
        $br . 'name:         ', $torrent->name(),
        $br . 'comment:      ', $torrent->comment(),
        $br . 'piece_length: ', $torrent->piece_length(),
        $br . 'size:         ', $torrent->size(2),
        $br . 'hash info:    ', $torrent->hash_info(),
        $br . 'stats:        ';

    try {
        var_export($torrent->scrape());
    } catch (Exception $e) {
        var_export($torrent->errors());
        die();
    }
    echo $br . 'content: ';
    var_export($torrent->content());
    echo $br . 'source:   ', $file;

    // Magnet links
    echo
        $br . 'magnet link:   ', $torrent->magnet(),
        $br . $br . 'use $torrent->magnet( false ); to get non html encoded ampersand',
        $br . 'magnet link:   ', $torrent->magnet(false);

    // Create a torrent, single tracker url
    try {
        $torrent = new Torrent('/etc/php/', 'http://tracker.opentrackr.org:1337/announce');
        $torrent->save('test.torrent');
    } catch (Exception $e) {
        die($torrent->errors());
    }

    // Create a torrent, multiple tracker urls
    /*    try {
            $torrent = new Torrent('/etc/php/', [
                'http://tracker.opentrackr.org:1337/announce',
                'udp://tracker.opentrackr.org:1337/announce',
                'https://w.wwwww.wtf:443/announce',
            ]);
            $torrent->save('test.torrent');
        } catch (Exception $e) {
            die($torrent->errors());
        }
    */
    // Modify a torrent
    echo $br . 'Adding a tracker - you can only add 1 tracker at a time, if you include an array, it replaces the original announce urls' . $br;
    $torrent->announce('udp://tracker.opentrackr.org:1337/announce');
    var_export($torrent->announce());

    echo $br . $br . 'Reset announce trackers' . $br;
    $torrent->announce(false);
    var_export($torrent->announce());

    echo $br . $br . 'Set multiple trackers' . $br;
    $torrent->announce([
        'http://tracker.opentrackr.org:1337/announce',
        'udp://tracker.opentrackr.org:1337/announce',
    ]);
    var_export($torrent->announce());

    echo $br . $br . 'Set multiple, tiered tracker(s)' . $br;
    $torrent->announce([
        [
            'http://tracker.opentrackr.org:1337/announce',
            'udp://tracker.opentrackr.org:1337/announce',
        ],
        'https://w.wwwww.wtf:443/announce',
    ]);
    var_export($torrent->announce());

    echo $br . $br . 'Set comment' . $br;
    $torrent->comment('hello world');
    echo $torrent->comment();

    echo $br . $br . 'Set private' . $br;
    $torrent->is_private(true);
    echo $torrent->is_private() ? 'yes' : 'no';

    echo $br . $br . 'Set httpseeds' . $br;
    $torrent->httpseeds('http://file-hosting.domain/path/');
    is_array($torrent->httpseeds()) ? var_export($torrent->httpseeds()) : $torrent->httpseeds();

    echo $br . $br . 'Set multiple httpseeds' . $br;
    $torrent->url_list([
        'http://file-hosting.domain/path/',
        'http://another-file-hosting.domain/path/',
    ]);
    var_export($torrent->url_list());

    echo $br . $br . 'Save the torrent changes' . $br;
    $torrent->save('test_single.torrent');

    echo $br . $br . 'Print errors' . $br;
    var_export($torrent->errors());
} else {
    $torrent->send($file);
}

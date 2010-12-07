# Listing

A very basic PHP script to list files within a directory. Useful to share a FTP directory with only a code snippet.
PHP code is old because I use these scripts on shared hosting servers which have 5.1 PHP version.

## Usage

    <?php
    require_once 'Listing.php';
    echo new Listing('directory');
    ?>

## Extension

MusicListing is a very small extension of Listing:

    <?php
    require_once 'Listing.php';
    require_once 'MusicListing.php';
    $music = new MusicListing('music_directory');
    $music->playlist();
    echo $music;
    ?>
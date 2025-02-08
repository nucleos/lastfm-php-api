Last.fm PHP library
===================
[![Latest Stable Version](https://poser.pugx.org/nucleos/lastfm/v/stable)](https://packagist.org/packages/nucleos/lastfm)
[![Latest Unstable Version](https://poser.pugx.org/nucleos/lastfm/v/unstable)](https://packagist.org/packages/nucleos/lastfm)
[![License](https://poser.pugx.org/nucleos/lastfm/license)](LICENSE.md)

[![Total Downloads](https://poser.pugx.org/nucleos/lastfm/downloads)](https://packagist.org/packages/nucleos/lastfm)
[![Monthly Downloads](https://poser.pugx.org/nucleos/lastfm/d/monthly)](https://packagist.org/packages/nucleos/lastfm)
[![Daily Downloads](https://poser.pugx.org/nucleos/lastfm/d/daily)](https://packagist.org/packages/nucleos/lastfm)

[![Continuous Integration](https://github.com/nucleos/lastfm/actions/workflows/continuous-integration.yml/badge.svg?event=push)](https://github.com/nucleos/lastfm/actions?query=workflow%3A"Continuous+Integration"+event%3Apush)
[![Code Coverage](https://codecov.io/gh/nucleos/lastfm/graph/badge.svg)](https://codecov.io/gh/nucleos/lastfm)

This library provides a wrapper for using the [Last.fm API] inside PHP.

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this library:

```
composer require nucleos/lastfm
# To define a default http client and message factory
composer require symfony/http-client nyholm/psr7
```

## Usage

```php
// Create connection
use Nucleos\LastFm\Service\AuthService;
use Nucleos\LastFm\Service\ChartService;
use Nucleos\LastFm\Service\PsrClientConnection;

$connection = new PsrClientConnection($httpClient, $requestFactory);

// Auth user to get a token
// http://www.last.fm/api/auth/?api_key=API_KEY

// Create a session (with generated token)
$token = 'API token';
$authApi = new AuthService($connection);
$session = $authApi->createSession($token);

$chartApi = new ChartService($connection);
$tags = $chartApi->getTopTags(10);
```

## Limitations

Last.fm removed some of their favorite APIs due their relaunch in March 2016. Some of the following removed methods are available via a webcrawler. Please have a look at the `Nucleos\LastFm\Crawler` package.

```
    Album
        album.getBuylinks
        album.getShouts
        album.share
    Artist
        artist.getEvents
        artist.getPastEvents
        artist.getPodcast
        artist.getShouts
        artist.getTopFans
        artist.share
        artist.shout
    Chart
        chart.getHypedArtists
        chart.getHypedTracks
        chart.getLovedTracks
    Event
        event.attend
        event.getAttendees
        event.getInfo
        event.getShouts
        event.share
        event.shout
    Geo
        geo.getEvents
        geo.getMetroArtistChart
        geo.getMetroHypeArtistChart
        geo.getMetroHypeTrackChart
        geo.getMetroTrackChart
        geo.getMetroUniqueArtistChart
        geo.getMetroUniqueTrackChart
        geo.getMetroWeeklyChartlist
        geo.getMetros
    Group
        group.getHype
        group.getMembers
        group.getWeeklyAlbumChart
        group.getWeeklyArtistChart
        group.getWeeklyChartList
        group.getWeeklyTrackChart
    Library
        library.addAlbum
        library.addArtist
        library.addTrack
        library.getAlbums
        library.getTracks
        library.removeAlbum
        library.removeArtist
        library.removeScrobble
        library.removeTrack
    Playlist
        playlist.addTrack
        playlist.create
    Radio
        radio.getPlaylist
        radio.search
        radio.tune
    Tag
        tag.getWeeklyArtistChart
        tag.search
    Tasteometer
        tasteometer.compare
        tasteometer.compareGroup
    Track
        track.ban
        track.getBuylinks
        track.getFingerprintMetadata
        track.getShouts
        track.getTopFans
        track.share
        track.unban
    User
        user.getArtistTracks
        user.getBannedTracks
        user.getEvents
        user.getNeighbours
        user.getNewReleases
        user.getPastEvents
        user.getPlaylists
        user.getRecentStations
        user.getRecommendedArtists
        user.getRecommendedEvents
        user.getShouts
        user.shout
        user.signUp
        user.terms
    Venue
        venue.getEvents
        venue.getPastEvents
        venue.search

```

## License

This library is under the [MIT license](LICENSE.md).

[Last.fm API]: http://www.last.fm/api

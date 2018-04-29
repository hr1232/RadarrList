# RadarrList

This tool provides a comprehensive list with many filter options for use in Radarr. It allows you to import movies automatically into Radarr for further use. It is based on the entire database of http://www.themoviedb.org. If you so desire you can import the entire movie database of almost 400k movies into Radarr. And of course you may filter the responses according to your needs.

As a special feature it is the only list available, that completes movie collections. Set whatever filters you want and you will probably have some collections only partially in your database. This list will complete all collections with those parts that do not match your filters.

The list is available through the following base URL:
https://www.heikorichter.name/movies.php

You can add the following parameters to filter the lists content:
- minyear      (Minimum year of release)
- maxyear      (Maximum year of release)
- maxage       (Danymic alternative for minyear)
- minvote      (Minimum vote average)
- maxvote      (Maximum vote average)
- collections  (Wether or not to complete collections, 1: comlete collections, 0: strictly adhere to filter opions)
- adult        (Wether or not to return porn movies, 1: porn only, 0: no porn)
- lang         (Original language of a movie)
- nogenres     (Comma seperated list of TMDB genre IDs that are unwanted, see below)

If no parameters are given the default settings are as follows:
https://www.heikorichter.name/movies.php?maxage=3&collections=1&adult=0

# Installation

To use this list in Radarr do the following simple steps:
* Go to the lists tab in Radarr settings
* Add a new List, choose StevenLu
* Give your list a name and choose options as you desire
* Remove the default URL and add this list's URL with your desired parameters

# Genre IDs

The following list of genre IDs can be used in the nogenres parameter:
- 12	Adventure
- 14	Fantasy
- 16	Animation
- 18	Drama
- 27	Horror
- 28	Action
- 35	Comedy
- 36	History
- 37	Western
- 53	Thriller
- 80	Crime
- 99	Documentary
- 878	Science Fiction
- 9648	Mystery
- 10402	Music
- 10749	Romance
- 10751	Family
- 10752	War
- 10770	TV Movie

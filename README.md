# RadarrList

This tool provides a comprehensive list with many filter options for use in Radarr. It allows you to import movies automatically into Radarr for further use. It is based on the entire database of ihttp://www.themoviedb.org. If you so desire you can import the entire movie database of almost 400k movies into Radarr. And of course you may filter the responses according to your needs.

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

If no parameters are given the default settings are as follows:
https://www.heikorichter.name/movies.php?maxage=3&collections=1&adult=0

# Installation

To use this list in Radarr do the following simple steps:
- Go to the lists tab in Radarr settings
- Add a new List, choose StevenLu
- Give your list a name and choose options as you desire
- Remove the default URL and add this list's URL with your desired parameters

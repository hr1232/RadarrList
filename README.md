# RadarrList

This tool provides a comprehensive list with many filters for use in Radarr. It allows you to import movies automatically into Radarr for further use.

As a special feature it is the only list available, that completes movie collections. Set whatever filters you want and you will probably have collections only partially in your database. This list will complete all collections with those parts that do not match your filters.

The list is available through the following base URL:
https://www.heikorichter.name/movies.php

You can add the following parameters to filter the lists content:
- minyear      Minimum year of release
- maxyear      Maximum year of release
- maxage       Danymic alternative for minyear
- minvote      Minimum vote average
- maxvote      Maximum vote average
- collections  Wether or not to complete collections, 1: comlete collections, 0: strictly adhere to filter opions
- adult        Wether or not to return porn movies, 1: porn only, 0: no porn
- lang         Original language of a movie

If no parameters are given the default settings are as follows:
https://www.heikorichter.name/movies.php&maxage=3&collections=1&adult=0

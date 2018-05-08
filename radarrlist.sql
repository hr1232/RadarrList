SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `collections`;
CREATE TABLE `collections` (
  `collectionId` bigint(20) UNSIGNED NOT NULL,
  `collectionTitle` varchar(255) DEFAULT NULL,
  `collectionPoster` varchar(255) DEFAULT NULL,
  `collectionUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all movie collections';

DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `companyId` bigint(20) UNSIGNED NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `companyLogo` varchar(100) DEFAULT NULL,
  `companyCountry` char(2) DEFAULT NULL,
  `companyUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all production companies';

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `countryCode` char(2) NOT NULL,
  `countryName` varchar(255) DEFAULT NULL,
  `countryUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all production countries';

DROP TABLE IF EXISTS `genres`;
CREATE TABLE `genres` (
  `genreId` bigint(20) UNSIGNED NOT NULL,
  `genreName` varchar(255) DEFAULT NULL,
  `genreUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all movie genres';

DROP TABLE IF EXISTS `keywords`;
CREATE TABLE `keywords` (
  `keywordId` bigint(20) UNSIGNED NOT NULL,
  `keywordName` varchar(255) DEFAULT NULL,
  `keywordUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='keywords used for mvies and series';

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `languageCode` char(2) NOT NULL,
  `languageName` varchar(255) DEFAULT NULL,
  `languageUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='languages (original lang. and spoken lang.)';

DROP TABLE IF EXISTS `movies`;
CREATE TABLE `movies` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `movieTitle` varchar(255) DEFAULT NULL,
  `movieTagline` varchar(255) DEFAULT NULL,
  `movieOriginalTitle` varchar(255) DEFAULT NULL,
  `movieOriginalLanguage` char(2) DEFAULT NULL,
  `movieStatus` enum('Rumored','Planned','In Production','Post Production','Released','Canceled') DEFAULT NULL,
  `movieOverview` text,
  `movieReleaseDate` date DEFAULT NULL,
  `movieImdb` char(9) DEFAULT NULL,
  `movieCollection` bigint(20) UNSIGNED DEFAULT NULL,
  `moviePoster` varchar(100) DEFAULT NULL,
  `movieRuntime` smallint(5) UNSIGNED DEFAULT NULL,
  `moviePopularity` double DEFAULT '0',
  `movieAdult` bit(1) DEFAULT NULL,
  `movieVideo` bit(1) DEFAULT NULL,
  `movieVoteAverage` double DEFAULT NULL,
  `movieVoteCount` mediumint(8) UNSIGNED DEFAULT NULL,
  `movieUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='all movies available on themoviedb.org';

DROP TABLE IF EXISTS `moviesCompanies`;
CREATE TABLE `moviesCompanies` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `companyId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='production companies for any given movie';

DROP TABLE IF EXISTS `moviesCountries`;
CREATE TABLE `moviesCountries` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `countryCode` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='production countries for any given movie';

DROP TABLE IF EXISTS `moviesGenres`;
CREATE TABLE `moviesGenres` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `genreId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='genres for any given movie';

DROP TABLE IF EXISTS `moviesKeywords`;
CREATE TABLE `moviesKeywords` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `keywordId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `moviesLanguages`;
CREATE TABLE `moviesLanguages` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `languageCode` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='spoken languages for any given movie';

DROP TABLE IF EXISTS `moviesPersons`;
CREATE TABLE `moviesPersons` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `personId` bigint(20) UNSIGNED NOT NULL,
  `personType` enum('cast','crew') NOT NULL DEFAULT 'cast',
  `personCharacter` varchar(255) DEFAULT NULL,
  `personDepartment` varchar(255) DEFAULT NULL,
  `personJob` varchar(255) DEFAULT NULL,
  `personOrder` mediumint(8) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `networks`;
CREATE TABLE `networks` (
  `networkId` bigint(20) UNSIGNED NOT NULL,
  `networkName` varchar(255) DEFAULT NULL,
  `networkUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all tv networks';

DROP TABLE IF EXISTS `persons`;
CREATE TABLE `persons` (
  `personId` bigint(20) UNSIGNED NOT NULL,
  `personName` varchar(255) DEFAULT NULL,
  `personPicture` varchar(100) DEFAULT NULL,
  `personGender` enum('f','m') DEFAULT NULL,
  `personPopularity` double NOT NULL DEFAULT '0',
  `personImdb` char(15) DEFAULT NULL,
  `personBirth` date DEFAULT NULL,
  `personDeath` date DEFAULT NULL,
  `personAdult` bit(1) DEFAULT NULL,
  `personUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all persons involved in any way with movies or series';

DROP TABLE IF EXISTS `series`;
CREATE TABLE `series` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `seriesOriginalTitle` varchar(255) DEFAULT NULL,
  `seriesOriginalLanguage` char(2) DEFAULT NULL,
  `seriesPopularity` double NOT NULL DEFAULT '0',
  `seriesAdult` bit(1) DEFAULT NULL,
  `seriesVideo` bit(1) DEFAULT NULL,
  `seriesUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='all tv series available on themoviedb.org';

DROP TABLE IF EXISTS `tempMovies`;
CREATE TABLE `tempMovies` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `movieTitle` varchar(255) DEFAULT NULL,
  `movieTagline` varchar(255) DEFAULT NULL,
  `movieOriginalTitle` varchar(255) DEFAULT NULL,
  `movieOriginalLanguage` char(2) DEFAULT NULL,
  `movieStatus` enum('Rumored','Planned','In Production','Post Production','Released','Canceled') DEFAULT NULL,
  `movieOverview` text,
  `movieReleaseDate` date DEFAULT NULL,
  `movieImdb` char(9) DEFAULT NULL,
  `movieCollection` bigint(20) UNSIGNED DEFAULT NULL,
  `moviePoster` varchar(100) DEFAULT NULL,
  `movieRuntime` smallint(5) UNSIGNED DEFAULT NULL,
  `moviePopularity` double DEFAULT '0',
  `movieAdult` bit(1) DEFAULT NULL,
  `movieVideo` bit(1) DEFAULT NULL,
  `movieVoteAverage` double DEFAULT NULL,
  `movieVoteCount` mediumint(8) UNSIGNED DEFAULT NULL,
  `movieUpdated` timestamp NULL DEFAULT NULL,
  `movieCollectionTitle` varchar(255) DEFAULT NULL,
  `movieCollectionPoster` varchar(255) DEFAULT NULL,
  `movieCompanies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `movieCountries` text,
  `movieGenres` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `movieKeywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `movieLanguages` text,
  `moviePersons` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


ALTER TABLE `collections`
  ADD PRIMARY KEY (`collectionId`),
  ADD KEY `collectionUpdated` (`collectionUpdated`);

ALTER TABLE `companies`
  ADD PRIMARY KEY (`companyId`),
  ADD KEY `companyUpdated` (`companyUpdated`),
  ADD KEY `companyCountry` (`companyCountry`);

ALTER TABLE `countries`
  ADD PRIMARY KEY (`countryCode`),
  ADD KEY `countryUpdated` (`countryUpdated`);

ALTER TABLE `genres`
  ADD PRIMARY KEY (`genreId`),
  ADD KEY `genreUpdated` (`genreUpdated`);

ALTER TABLE `keywords`
  ADD PRIMARY KEY (`keywordId`),
  ADD KEY `keywordUpdated` (`keywordUpdated`);

ALTER TABLE `languages`
  ADD PRIMARY KEY (`languageCode`),
  ADD KEY `languageUpdated` (`languageUpdated`);

ALTER TABLE `movies`
  ADD PRIMARY KEY (`movieId`),
  ADD KEY `movieUpdated` (`movieUpdated`),
  ADD KEY `moviePopularity` (`moviePopularity`),
  ADD KEY `movieAdult` (`movieAdult`),
  ADD KEY `movieOriginalLanguage` (`movieOriginalLanguage`),
  ADD KEY `movieStatus` (`movieStatus`),
  ADD KEY `movieRuntime` (`movieRuntime`),
  ADD KEY `movieVoteCuunt` (`movieVoteCount`),
  ADD KEY `movieVoteAverage` (`movieVoteAverage`),
  ADD KEY `movieCollection` (`movieCollection`),
  ADD KEY `movieVideo` (`movieVideo`);

ALTER TABLE `moviesCompanies`
  ADD PRIMARY KEY (`movieId`,`companyId`),
  ADD KEY `moviesCompanies_ibfk_2` (`companyId`);

ALTER TABLE `moviesCountries`
  ADD PRIMARY KEY (`movieId`,`countryCode`),
  ADD KEY `moviesCountries_ibfk_1` (`countryCode`);

ALTER TABLE `moviesGenres`
  ADD PRIMARY KEY (`movieId`,`genreId`),
  ADD KEY `genreId` (`genreId`);

ALTER TABLE `moviesKeywords`
  ADD PRIMARY KEY (`movieId`,`keywordId`);

ALTER TABLE `moviesLanguages`
  ADD PRIMARY KEY (`movieId`,`languageCode`),
  ADD KEY `moviesLanguages_ibfk_1` (`languageCode`);

ALTER TABLE `moviesPersons`
  ADD PRIMARY KEY (`movieId`,`personId`),
  ADD KEY `personId` (`personId`),
  ADD KEY `personType` (`personType`);

ALTER TABLE `networks`
  ADD PRIMARY KEY (`networkId`),
  ADD KEY `networkUpdated` (`networkUpdated`);

ALTER TABLE `persons`
  ADD PRIMARY KEY (`personId`),
  ADD KEY `personPopularity` (`personPopularity`),
  ADD KEY `personUpdated` (`personUpdated`),
  ADD KEY `persongender` (`personGender`),
  ADD KEY `personAdult` (`personAdult`);

ALTER TABLE `series`
  ADD PRIMARY KEY (`seriesId`),
  ADD KEY `seriesPopularity` (`seriesPopularity`),
  ADD KEY `seriesAdult` (`seriesAdult`),
  ADD KEY `seriesVideo` (`seriesVideo`),
  ADD KEY `seriesUpdated` (`seriesUpdated`),
  ADD KEY `seriesOriginalLanguage` (`seriesOriginalLanguage`);

ALTER TABLE `tempMovies`
  ADD PRIMARY KEY (`movieId`),
  ADD KEY `movieOriginalLanguage` (`movieOriginalLanguage`),
  ADD KEY `movieStatus` (`movieStatus`),
  ADD KEY `movieReleaseDate` (`movieReleaseDate`),
  ADD KEY `movieVoteAverage` (`movieVoteAverage`),
  ADD KEY `movieVoteCount` (`movieVoteCount`);

ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`companyCountry`) REFERENCES `countries` (`countryCode`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`movieOriginalLanguage`) REFERENCES `languages` (`languageCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movies_ibfk_2` FOREIGN KEY (`movieCollection`) REFERENCES `collections` (`collectionId`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `moviesCompanies`
  ADD CONSTRAINT `moviesCompanies_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `moviesCompanies_ibfk_2` FOREIGN KEY (`companyId`) REFERENCES `companies` (`companyId`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `moviesCountries`
  ADD CONSTRAINT `moviesCountries_ibfk_1` FOREIGN KEY (`countryCode`) REFERENCES `countries` (`countryCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `moviesCountries_ibfk_2` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `moviesGenres`
  ADD CONSTRAINT `moviesGenres_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `moviesGenres_ibfk_2` FOREIGN KEY (`genreId`) REFERENCES `genres` (`genreId`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `moviesKeywords`
  ADD CONSTRAINT `moviesKeywords_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `moviesLanguages`
  ADD CONSTRAINT `moviesLanguages_ibfk_1` FOREIGN KEY (`languageCode`) REFERENCES `languages` (`languageCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `moviesLanguages_ibfk_2` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`);

ALTER TABLE `moviesPersons`
  ADD CONSTRAINT `moviesPersons_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `moviesPersons_ibfk_2` FOREIGN KEY (`personId`) REFERENCES `persons` (`personId`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `series`
  ADD CONSTRAINT `series_ibfk_1` FOREIGN KEY (`seriesOriginalLanguage`) REFERENCES `languages` (`languageCode`) ON DELETE SET NULL ON UPDATE CASCADE;

COMMIT;


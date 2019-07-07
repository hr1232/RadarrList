-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 07. Jul 2019 um 20:55
-- Server-Version: 10.1.38-MariaDB-0+deb9u1
-- PHP-Version: 7.0.33-0+deb9u3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `radarrlist`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `collections`
--

DROP TABLE IF EXISTS `collections`;
CREATE TABLE `collections` (
  `collectionId` bigint(20) UNSIGNED NOT NULL,
  `collectionTitle` varchar(255) DEFAULT NULL,
  `collectionPoster` varchar(255) DEFAULT NULL,
  `collectionUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all movie collections';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
  `companyId` bigint(20) UNSIGNED NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `companyLogo` varchar(100) DEFAULT NULL,
  `companyCountry` char(2) DEFAULT NULL,
  `companyUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all production companies';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `countryCode` char(2) NOT NULL,
  `countryName` varchar(255) DEFAULT NULL,
  `countryUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all production countries';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `genres`
--

DROP TABLE IF EXISTS `genres`;
CREATE TABLE `genres` (
  `genreId` bigint(20) UNSIGNED NOT NULL,
  `genreName` varchar(255) DEFAULT NULL,
  `genreUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all movie genres';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `keywords`
--

DROP TABLE IF EXISTS `keywords`;
CREATE TABLE `keywords` (
  `keywordId` bigint(20) UNSIGNED NOT NULL,
  `keywordName` varchar(255) DEFAULT NULL,
  `keywordUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='keywords used for mvies and series';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `languageCode` char(2) NOT NULL,
  `languageName` varchar(255) DEFAULT NULL,
  `languageUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='languages (original lang. and spoken lang.)';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `movies`
--

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
  `movieVoteAverage` double UNSIGNED DEFAULT NULL,
  `movieVoteCount` mediumint(8) UNSIGNED DEFAULT NULL,
  `movieUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='all movies available on themoviedb.org';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesCompanies`
--

DROP TABLE IF EXISTS `moviesCompanies`;
CREATE TABLE `moviesCompanies` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `companyId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='production companies for any given movie';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesCountries`
--

DROP TABLE IF EXISTS `moviesCountries`;
CREATE TABLE `moviesCountries` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `countryCode` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='production countries for any given movie';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesGenres`
--

DROP TABLE IF EXISTS `moviesGenres`;
CREATE TABLE `moviesGenres` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `genreId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='genres for any given movie';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesKeywords`
--

DROP TABLE IF EXISTS `moviesKeywords`;
CREATE TABLE `moviesKeywords` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `keywordId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesLanguages`
--

DROP TABLE IF EXISTS `moviesLanguages`;
CREATE TABLE `moviesLanguages` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `languageCode` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='spoken languages for any given movie';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesPersons`
--

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `networks`
--

DROP TABLE IF EXISTS `networks`;
CREATE TABLE `networks` (
  `networkId` bigint(20) UNSIGNED NOT NULL,
  `networkName` varchar(255) DEFAULT NULL,
  `networkUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all tv networks';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `persons`
--

DROP TABLE IF EXISTS `persons`;
CREATE TABLE `persons` (
  `personId` bigint(20) UNSIGNED NOT NULL,
  `personName` varchar(255) DEFAULT NULL,
  `personPicture` varchar(100) DEFAULT NULL,
  `personGender` enum('f','m') DEFAULT NULL,
  `personPopularity` double NOT NULL DEFAULT '0',
  `personAdult` bit(1) DEFAULT NULL,
  `personUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all persons involved in any way with movies or series';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `series`
--

DROP TABLE IF EXISTS `series`;
CREATE TABLE `series` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `seriesOriginalTitle` varchar(255) DEFAULT NULL,
  `seriesOriginalLanguage` char(2) DEFAULT NULL,
  `seriesPopularity` double NOT NULL DEFAULT '0',
  `seriesPoster` varchar(100) NOT NULL,
  `seriesStartDate` date DEFAULT NULL,
  `seriesEndDate` date DEFAULT NULL,
  `seriesSeasons` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `seriesEpisodes` smallint(9) UNSIGNED NOT NULL DEFAULT '0',
  `seriesVoteAverage` double UNSIGNED DEFAULT NULL,
  `seriesVoteCount` mediumint(9) UNSIGNED DEFAULT NULL,
  `seriesRunning` tinyint(1) DEFAULT NULL,
  `seriesUpdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='all tv series available on themoviedb.org';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesCompanies`
--

DROP TABLE IF EXISTS `seriesCompanies`;
CREATE TABLE `seriesCompanies` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `companyId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesCountries`
--

DROP TABLE IF EXISTS `seriesCountries`;
CREATE TABLE `seriesCountries` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `countryCode` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesGenres`
--

DROP TABLE IF EXISTS `seriesGenres`;
CREATE TABLE `seriesGenres` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `tvgenreId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesKeywords`
--

DROP TABLE IF EXISTS `seriesKeywords`;
CREATE TABLE `seriesKeywords` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `keywordId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesLanguages`
--

DROP TABLE IF EXISTS `seriesLanguages`;
CREATE TABLE `seriesLanguages` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `languageCode` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesNetworks`
--

DROP TABLE IF EXISTS `seriesNetworks`;
CREATE TABLE `seriesNetworks` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `networkId` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesPersons`
--

DROP TABLE IF EXISTS `seriesPersons`;
CREATE TABLE `seriesPersons` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `personId` bigint(20) UNSIGNED NOT NULL,
  `personType` enum('cast','crew') NOT NULL DEFAULT 'cast',
  `personCharacter` varchar(255) DEFAULT NULL,
  `personDepartment` varchar(255) DEFAULT NULL,
  `personJob` varchar(255) DEFAULT NULL,
  `personOrder` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tempMovies`
--

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tvgenres`
--

DROP TABLE IF EXISTS `tvgenres`;
CREATE TABLE `tvgenres` (
  `tvgenreId` bigint(20) UNSIGNED NOT NULL,
  `tvgenreName` varchar(255) NOT NULL,
  `tvgenreUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`collectionId`),
  ADD KEY `collectionUpdated` (`collectionUpdated`);

--
-- Indizes für die Tabelle `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`companyId`),
  ADD KEY `companyUpdated` (`companyUpdated`),
  ADD KEY `companyCountry` (`companyCountry`);

--
-- Indizes für die Tabelle `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`countryCode`),
  ADD KEY `countryUpdated` (`countryUpdated`);

--
-- Indizes für die Tabelle `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genreId`),
  ADD KEY `genreUpdated` (`genreUpdated`);

--
-- Indizes für die Tabelle `keywords`
--
ALTER TABLE `keywords`
  ADD PRIMARY KEY (`keywordId`),
  ADD KEY `keywordUpdated` (`keywordUpdated`);

--
-- Indizes für die Tabelle `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`languageCode`),
  ADD KEY `languageUpdated` (`languageUpdated`);

--
-- Indizes für die Tabelle `movies`
--
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

--
-- Indizes für die Tabelle `moviesCompanies`
--
ALTER TABLE `moviesCompanies`
  ADD PRIMARY KEY (`movieId`,`companyId`),
  ADD KEY `moviesCompanies_ibfk_2` (`companyId`);

--
-- Indizes für die Tabelle `moviesCountries`
--
ALTER TABLE `moviesCountries`
  ADD PRIMARY KEY (`movieId`,`countryCode`),
  ADD KEY `moviesCountries_ibfk_1` (`countryCode`);

--
-- Indizes für die Tabelle `moviesGenres`
--
ALTER TABLE `moviesGenres`
  ADD PRIMARY KEY (`movieId`,`genreId`),
  ADD KEY `genreId` (`genreId`);

--
-- Indizes für die Tabelle `moviesKeywords`
--
ALTER TABLE `moviesKeywords`
  ADD PRIMARY KEY (`movieId`,`keywordId`),
  ADD KEY `keywordId` (`keywordId`);

--
-- Indizes für die Tabelle `moviesLanguages`
--
ALTER TABLE `moviesLanguages`
  ADD PRIMARY KEY (`movieId`,`languageCode`),
  ADD KEY `moviesLanguages_ibfk_1` (`languageCode`);

--
-- Indizes für die Tabelle `moviesPersons`
--
ALTER TABLE `moviesPersons`
  ADD PRIMARY KEY (`movieId`,`personId`),
  ADD KEY `personId` (`personId`),
  ADD KEY `personType` (`personType`);

--
-- Indizes für die Tabelle `networks`
--
ALTER TABLE `networks`
  ADD PRIMARY KEY (`networkId`),
  ADD KEY `networkUpdated` (`networkUpdated`);

--
-- Indizes für die Tabelle `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`personId`),
  ADD KEY `personPopularity` (`personPopularity`),
  ADD KEY `personUpdated` (`personUpdated`),
  ADD KEY `persongender` (`personGender`),
  ADD KEY `personAdult` (`personAdult`);

--
-- Indizes für die Tabelle `series`
--
ALTER TABLE `series`
  ADD PRIMARY KEY (`seriesId`),
  ADD KEY `seriesPopularity` (`seriesPopularity`),
  ADD KEY `seriesUpdated` (`seriesUpdated`),
  ADD KEY `seriesOriginalLanguage` (`seriesOriginalLanguage`),
  ADD KEY `seriesVoteAverage` (`seriesVoteAverage`),
  ADD KEY `seriesVoteCount` (`seriesVoteCount`),
  ADD KEY `seriesEpisodes` (`seriesEpisodes`),
  ADD KEY `seriesSeasons` (`seriesSeasons`),
  ADD KEY `seriesRunning` (`seriesRunning`);

--
-- Indizes für die Tabelle `seriesCompanies`
--
ALTER TABLE `seriesCompanies`
  ADD PRIMARY KEY (`seriesId`,`companyId`),
  ADD KEY `seriesCompanies_ibfk_2` (`companyId`);

--
-- Indizes für die Tabelle `seriesCountries`
--
ALTER TABLE `seriesCountries`
  ADD PRIMARY KEY (`seriesId`,`countryCode`),
  ADD KEY `countryCode` (`countryCode`);

--
-- Indizes für die Tabelle `seriesGenres`
--
ALTER TABLE `seriesGenres`
  ADD PRIMARY KEY (`tvgenreId`,`seriesId`),
  ADD KEY `genreId` (`tvgenreId`),
  ADD KEY `seriesId` (`seriesId`);

--
-- Indizes für die Tabelle `seriesKeywords`
--
ALTER TABLE `seriesKeywords`
  ADD PRIMARY KEY (`seriesId`,`keywordId`),
  ADD KEY `keywordId` (`keywordId`);

--
-- Indizes für die Tabelle `seriesLanguages`
--
ALTER TABLE `seriesLanguages`
  ADD PRIMARY KEY (`seriesId`,`languageCode`),
  ADD KEY `languageCode` (`languageCode`);

--
-- Indizes für die Tabelle `seriesNetworks`
--
ALTER TABLE `seriesNetworks`
  ADD PRIMARY KEY (`seriesId`,`networkId`),
  ADD KEY `networkId` (`networkId`);

--
-- Indizes für die Tabelle `seriesPersons`
--
ALTER TABLE `seriesPersons`
  ADD PRIMARY KEY (`seriesId`,`personId`),
  ADD KEY `personId` (`personId`),
  ADD KEY `personType` (`personType`);

--
-- Indizes für die Tabelle `tempMovies`
--
ALTER TABLE `tempMovies`
  ADD PRIMARY KEY (`movieId`),
  ADD KEY `movieOriginalLanguage` (`movieOriginalLanguage`),
  ADD KEY `movieStatus` (`movieStatus`),
  ADD KEY `movieReleaseDate` (`movieReleaseDate`),
  ADD KEY `movieVoteAverage` (`movieVoteAverage`),
  ADD KEY `movieVoteCount` (`movieVoteCount`);

--
-- Indizes für die Tabelle `tvgenres`
--
ALTER TABLE `tvgenres`
  ADD PRIMARY KEY (`tvgenreId`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`companyCountry`) REFERENCES `countries` (`countryCode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`movieOriginalLanguage`) REFERENCES `languages` (`languageCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movies_ibfk_2` FOREIGN KEY (`movieCollection`) REFERENCES `collections` (`collectionId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `moviesCompanies`
--
ALTER TABLE `moviesCompanies`
  ADD CONSTRAINT `moviesCompanies_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `moviesCompanies_ibfk_2` FOREIGN KEY (`companyId`) REFERENCES `companies` (`companyId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `moviesCountries`
--
ALTER TABLE `moviesCountries`
  ADD CONSTRAINT `moviesCountries_ibfk_1` FOREIGN KEY (`countryCode`) REFERENCES `countries` (`countryCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `moviesCountries_ibfk_2` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `moviesGenres`
--
ALTER TABLE `moviesGenres`
  ADD CONSTRAINT `moviesGenres_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `moviesGenres_ibfk_2` FOREIGN KEY (`genreId`) REFERENCES `genres` (`genreId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `moviesKeywords`
--
ALTER TABLE `moviesKeywords`
  ADD CONSTRAINT `moviesKeywords_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `moviesLanguages`
--
ALTER TABLE `moviesLanguages`
  ADD CONSTRAINT `moviesLanguages_ibfk_1` FOREIGN KEY (`languageCode`) REFERENCES `languages` (`languageCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `moviesLanguages_ibfk_2` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`);

--
-- Constraints der Tabelle `moviesPersons`
--
ALTER TABLE `moviesPersons`
  ADD CONSTRAINT `moviesPersons_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movies` (`movieId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `moviesPersons_ibfk_2` FOREIGN KEY (`personId`) REFERENCES `persons` (`personId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `series`
--
ALTER TABLE `series`
  ADD CONSTRAINT `series_ibfk_1` FOREIGN KEY (`seriesOriginalLanguage`) REFERENCES `languages` (`languageCode`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints der Tabelle `seriesCompanies`
--
ALTER TABLE `seriesCompanies`
  ADD CONSTRAINT `seriesCompanies_ibfk_1` FOREIGN KEY (`seriesId`) REFERENCES `series` (`seriesId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seriesCompanies_ibfk_2` FOREIGN KEY (`companyId`) REFERENCES `companies` (`companyId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `seriesCountries`
--
ALTER TABLE `seriesCountries`
  ADD CONSTRAINT `seriesCountries_ibfk_1` FOREIGN KEY (`seriesId`) REFERENCES `series` (`seriesId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seriesCountries_ibfk_2` FOREIGN KEY (`countryCode`) REFERENCES `countries` (`countryCode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `seriesGenres`
--
ALTER TABLE `seriesGenres`
  ADD CONSTRAINT `seriesGenres_ibfk_1` FOREIGN KEY (`seriesId`) REFERENCES `series` (`seriesId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seriesGenres_ibfk_2` FOREIGN KEY (`tvgenreId`) REFERENCES `tvgenres` (`tvgenreId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `seriesKeywords`
--
ALTER TABLE `seriesKeywords`
  ADD CONSTRAINT `seriesKeywords_ibfk_1` FOREIGN KEY (`seriesId`) REFERENCES `series` (`seriesId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seriesKeywords_ibfk_2` FOREIGN KEY (`keywordId`) REFERENCES `keywords` (`keywordId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `seriesLanguages`
--
ALTER TABLE `seriesLanguages`
  ADD CONSTRAINT `seriesLanguages_ibfk_1` FOREIGN KEY (`seriesId`) REFERENCES `series` (`seriesId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seriesLanguages_ibfk_2` FOREIGN KEY (`languageCode`) REFERENCES `languages` (`languageCode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `seriesNetworks`
--
ALTER TABLE `seriesNetworks`
  ADD CONSTRAINT `seriesNetworks_ibfk_1` FOREIGN KEY (`seriesId`) REFERENCES `series` (`seriesId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seriesNetworks_ibfk_2` FOREIGN KEY (`networkId`) REFERENCES `networks` (`networkId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `seriesPersons`
--
ALTER TABLE `seriesPersons`
  ADD CONSTRAINT `seriesPersons_ibfk_1` FOREIGN KEY (`seriesId`) REFERENCES `series` (`seriesId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seriesPersons_ibfk_2` FOREIGN KEY (`personId`) REFERENCES `persons` (`personId`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- Metadaten
--
USE `phpmyadmin`;

--
-- Metadaten für Tabelle collections
--

--
-- Metadaten für Tabelle companies
--

--
-- Metadaten für Tabelle countries
--

--
-- Metadaten für Tabelle genres
--

--
-- Metadaten für Tabelle keywords
--

--
-- Metadaten für Tabelle languages
--

--
-- Metadaten für Tabelle movies
--

--
-- Daten für Tabelle `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('heikorichter', 'radarrlist', 'movies', '{\"sorted_col\":\"`movies`.`movieId` ASC\"}', '2019-07-05 17:48:18');

--
-- Metadaten für Tabelle moviesCompanies
--

--
-- Metadaten für Tabelle moviesCountries
--

--
-- Metadaten für Tabelle moviesGenres
--

--
-- Metadaten für Tabelle moviesKeywords
--

--
-- Metadaten für Tabelle moviesLanguages
--

--
-- Metadaten für Tabelle moviesPersons
--

--
-- Metadaten für Tabelle networks
--

--
-- Metadaten für Tabelle persons
--

--
-- Metadaten für Tabelle series
--

--
-- Daten für Tabelle `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('heikorichter', 'radarrlist', 'series', '{\"sorted_col\":\"`seriesPopularity`  DESC\"}', '2019-07-07 18:38:18');

--
-- Metadaten für Tabelle seriesCompanies
--

--
-- Metadaten für Tabelle seriesCountries
--

--
-- Metadaten für Tabelle seriesGenres
--

--
-- Metadaten für Tabelle seriesKeywords
--

--
-- Metadaten für Tabelle seriesLanguages
--

--
-- Metadaten für Tabelle seriesNetworks
--

--
-- Metadaten für Tabelle seriesPersons
--

--
-- Metadaten für Tabelle tempMovies
--

--
-- Metadaten für Tabelle tvgenres
--

--
-- Metadaten für Datenbank radarrlist
--
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


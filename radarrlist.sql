-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 18. Jul 2019 um 07:26
-- Server-Version: 10.3.15-MariaDB-1
-- PHP-Version: 7.3.4-2

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
CREATE TABLE IF NOT EXISTS `collections` (
  `collectionId` bigint(20) UNSIGNED NOT NULL,
  `collectionTitle` varchar(255) DEFAULT NULL,
  `collectionPoster` varchar(255) DEFAULT NULL,
  `collectionUpdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`collectionId`),
  KEY `collectionUpdated` (`collectionUpdated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all movie collections';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `companyId` bigint(20) UNSIGNED NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `companyLogo` varchar(100) DEFAULT NULL,
  `companyCountry` char(2) DEFAULT NULL,
  `companyUpdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`companyId`),
  KEY `companyUpdated` (`companyUpdated`),
  KEY `companyCountry` (`companyCountry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all production companies';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `countryCode` char(2) NOT NULL,
  `countryName` varchar(255) DEFAULT NULL,
  `countryUpdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`countryCode`),
  KEY `countryUpdated` (`countryUpdated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all production countries';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `genres`
--

DROP TABLE IF EXISTS `genres`;
CREATE TABLE IF NOT EXISTS `genres` (
  `genreId` bigint(20) UNSIGNED NOT NULL,
  `genreName` varchar(255) DEFAULT NULL,
  `genreUpdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`genreId`),
  KEY `genreUpdated` (`genreUpdated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all movie genres';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `keywords`
--

DROP TABLE IF EXISTS `keywords`;
CREATE TABLE IF NOT EXISTS `keywords` (
  `keywordId` bigint(20) UNSIGNED NOT NULL,
  `keywordName` varchar(255) DEFAULT NULL,
  `keywordUpdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`keywordId`),
  KEY `keywordUpdated` (`keywordUpdated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='keywords used for mvies and series';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `languageCode` char(2) NOT NULL,
  `languageName` varchar(255) DEFAULT NULL,
  `languageUpdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`languageCode`),
  KEY `languageUpdated` (`languageUpdated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='languages (original lang. and spoken lang.)';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `movies`
--

DROP TABLE IF EXISTS `movies`;
CREATE TABLE IF NOT EXISTS `movies` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `movieTitle` varchar(255) DEFAULT NULL,
  `movieTagline` varchar(255) DEFAULT NULL,
  `movieOriginalTitle` varchar(255) DEFAULT NULL,
  `movieOriginalLanguage` char(2) DEFAULT NULL,
  `movieStatus` varchar(50) DEFAULT NULL,
  `movieOverview` text DEFAULT NULL,
  `movieReleaseDate` date DEFAULT NULL,
  `movieImdb` varchar(15) DEFAULT NULL,
  `movieCollection` bigint(20) UNSIGNED DEFAULT NULL,
  `moviePoster` varchar(100) DEFAULT NULL,
  `movieRuntime` smallint(5) UNSIGNED DEFAULT NULL,
  `moviePopularity` double DEFAULT 0,
  `movieAdult` bit(1) DEFAULT NULL,
  `movieVideo` bit(1) DEFAULT NULL,
  `movieVoteAverage` double UNSIGNED DEFAULT NULL,
  `movieVoteCount` mediumint(8) UNSIGNED DEFAULT NULL,
  `movieBudget` int(10) UNSIGNED DEFAULT NULL,
  `movieRevenue` int(11) DEFAULT NULL,
  `movieHomepage` varchar(255) DEFAULT NULL,
  `movieUpdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`movieId`),
  KEY `movieUpdated` (`movieUpdated`),
  KEY `moviePopularity` (`moviePopularity`),
  KEY `movieAdult` (`movieAdult`),
  KEY `movieOriginalLanguage` (`movieOriginalLanguage`),
  KEY `movieStatus` (`movieStatus`),
  KEY `movieRuntime` (`movieRuntime`),
  KEY `movieVoteCuunt` (`movieVoteCount`),
  KEY `movieVoteAverage` (`movieVoteAverage`),
  KEY `movieCollection` (`movieCollection`),
  KEY `movieVideo` (`movieVideo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='all movies available on themoviedb.org';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesCompanies`
--

DROP TABLE IF EXISTS `moviesCompanies`;
CREATE TABLE IF NOT EXISTS `moviesCompanies` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `companyId` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`movieId`,`companyId`),
  KEY `moviesCompanies_ibfk_2` (`companyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='production companies for any given movie';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesCountries`
--

DROP TABLE IF EXISTS `moviesCountries`;
CREATE TABLE IF NOT EXISTS `moviesCountries` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `countryCode` char(2) NOT NULL,
  PRIMARY KEY (`movieId`,`countryCode`),
  KEY `moviesCountries_ibfk_1` (`countryCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='production countries for any given movie';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesGenres`
--

DROP TABLE IF EXISTS `moviesGenres`;
CREATE TABLE IF NOT EXISTS `moviesGenres` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `genreId` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`movieId`,`genreId`),
  KEY `genreId` (`genreId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='genres for any given movie';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesKeywords`
--

DROP TABLE IF EXISTS `moviesKeywords`;
CREATE TABLE IF NOT EXISTS `moviesKeywords` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `keywordId` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`movieId`,`keywordId`),
  KEY `keywordId` (`keywordId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesLanguages`
--

DROP TABLE IF EXISTS `moviesLanguages`;
CREATE TABLE IF NOT EXISTS `moviesLanguages` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `languageCode` char(2) NOT NULL,
  PRIMARY KEY (`movieId`,`languageCode`),
  KEY `moviesLanguages_ibfk_1` (`languageCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='spoken languages for any given movie';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moviesPersons`
--

DROP TABLE IF EXISTS `moviesPersons`;
CREATE TABLE IF NOT EXISTS `moviesPersons` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `personId` bigint(20) UNSIGNED NOT NULL,
  `personType` enum('cast','crew') NOT NULL DEFAULT 'cast',
  `personCharacter` varchar(255) DEFAULT NULL,
  `personDepartment` varchar(255) DEFAULT NULL,
  `personJob` varchar(255) DEFAULT NULL,
  `personOrder` mediumint(8) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`movieId`,`personId`),
  KEY `personId` (`personId`),
  KEY `personType` (`personType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `networks`
--

DROP TABLE IF EXISTS `networks`;
CREATE TABLE IF NOT EXISTS `networks` (
  `networkId` bigint(20) UNSIGNED NOT NULL,
  `networkName` varchar(255) DEFAULT NULL,
  `networkLogo` varchar(100) DEFAULT NULL,
  `networkCountry` char(2) DEFAULT NULL,
  `networkUpdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`networkId`),
  KEY `networkUpdated` (`networkUpdated`),
  KEY `networkLogo` (`networkLogo`),
  KEY `networkCountry` (`networkCountry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all tv networks';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `persons`
--

DROP TABLE IF EXISTS `persons`;
CREATE TABLE IF NOT EXISTS `persons` (
  `personId` bigint(20) UNSIGNED NOT NULL,
  `personName` varchar(255) DEFAULT NULL,
  `personBirthday` date DEFAULT NULL,
  `personDeathday` date DEFAULT NULL,
  `personPlaceOfBirth` varchar(255) DEFAULT NULL,
  `personImdb` varchar(15) DEFAULT NULL,
  `personBiography` text DEFAULT NULL,
  `personPicture` varchar(100) DEFAULT NULL,
  `personGender` enum('f','m') DEFAULT NULL,
  `personPopularity` double NOT NULL DEFAULT 0,
  `personAdult` bit(1) DEFAULT NULL,
  `personHomepage` varchar(255) DEFAULT NULL,
  `personUpdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`personId`),
  KEY `personPopularity` (`personPopularity`),
  KEY `personUpdated` (`personUpdated`),
  KEY `persongender` (`personGender`),
  KEY `personAdult` (`personAdult`),
  KEY `personBirthday` (`personBirthday`),
  KEY `personDeathday` (`personDeathday`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='list of all persons involved in any way with movies or series';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `series`
--

DROP TABLE IF EXISTS `series`;
CREATE TABLE IF NOT EXISTS `series` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `seriesTitle` varchar(255) DEFAULT NULL,
  `seriesOverview` text DEFAULT NULL,
  `seriesOriginalTitle` varchar(255) DEFAULT NULL,
  `seriesOriginalLanguage` char(2) DEFAULT NULL,
  `seriesPopularity` double NOT NULL DEFAULT 0,
  `seriesPoster` varchar(100) NOT NULL,
  `seriesStartDate` date DEFAULT NULL,
  `seriesEndDate` date DEFAULT NULL,
  `seriesSeasons` tinyint(3) UNSIGNED DEFAULT NULL,
  `seriesEpisodes` smallint(9) UNSIGNED DEFAULT NULL,
  `seriesVoteAverage` double UNSIGNED DEFAULT NULL,
  `seriesVoteCount` mediumint(9) UNSIGNED DEFAULT NULL,
  `seriesInProduction` tinyint(1) DEFAULT NULL,
  `seriesStatus` varchar(50) DEFAULT NULL,
  `seriesType` varchar(50) DEFAULT NULL,
  `seriesHomepage` varchar(255) DEFAULT NULL,
  `seriesUpdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`seriesId`),
  KEY `seriesPopularity` (`seriesPopularity`),
  KEY `seriesUpdated` (`seriesUpdated`),
  KEY `seriesOriginalLanguage` (`seriesOriginalLanguage`),
  KEY `seriesVoteAverage` (`seriesVoteAverage`),
  KEY `seriesVoteCount` (`seriesVoteCount`),
  KEY `seriesEpisodes` (`seriesEpisodes`),
  KEY `seriesSeasons` (`seriesSeasons`),
  KEY `seriesRunning` (`seriesInProduction`),
  KEY `seriesStatus` (`seriesStatus`),
  KEY `seriesType` (`seriesType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='all tv series available on themoviedb.org';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesCompanies`
--

DROP TABLE IF EXISTS `seriesCompanies`;
CREATE TABLE IF NOT EXISTS `seriesCompanies` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `companyId` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`seriesId`,`companyId`),
  KEY `seriesCompanies_ibfk_2` (`companyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesCountries`
--

DROP TABLE IF EXISTS `seriesCountries`;
CREATE TABLE IF NOT EXISTS `seriesCountries` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `countryCode` char(2) NOT NULL,
  PRIMARY KEY (`seriesId`,`countryCode`),
  KEY `countryCode` (`countryCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesGenres`
--

DROP TABLE IF EXISTS `seriesGenres`;
CREATE TABLE IF NOT EXISTS `seriesGenres` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `tvgenreId` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`tvgenreId`,`seriesId`),
  KEY `genreId` (`tvgenreId`),
  KEY `seriesId` (`seriesId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesKeywords`
--

DROP TABLE IF EXISTS `seriesKeywords`;
CREATE TABLE IF NOT EXISTS `seriesKeywords` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `keywordId` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`seriesId`,`keywordId`),
  KEY `keywordId` (`keywordId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesLanguages`
--

DROP TABLE IF EXISTS `seriesLanguages`;
CREATE TABLE IF NOT EXISTS `seriesLanguages` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `languageCode` char(2) NOT NULL,
  PRIMARY KEY (`seriesId`,`languageCode`),
  KEY `languageCode` (`languageCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesNetworks`
--

DROP TABLE IF EXISTS `seriesNetworks`;
CREATE TABLE IF NOT EXISTS `seriesNetworks` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `networkId` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`seriesId`,`networkId`),
  KEY `networkId` (`networkId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seriesPersons`
--

DROP TABLE IF EXISTS `seriesPersons`;
CREATE TABLE IF NOT EXISTS `seriesPersons` (
  `seriesId` bigint(20) UNSIGNED NOT NULL,
  `personId` bigint(20) UNSIGNED NOT NULL,
  `personType` enum('cast','crew') NOT NULL DEFAULT 'cast',
  `personCharacter` varchar(255) DEFAULT NULL,
  `personDepartment` varchar(255) DEFAULT NULL,
  `personJob` varchar(255) DEFAULT NULL,
  `personOrder` mediumint(8) UNSIGNED NOT NULL,
  PRIMARY KEY (`seriesId`,`personId`),
  KEY `personId` (`personId`),
  KEY `personType` (`personType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tempMovies`
--

DROP TABLE IF EXISTS `tempMovies`;
CREATE TABLE IF NOT EXISTS `tempMovies` (
  `movieId` bigint(20) UNSIGNED NOT NULL,
  `movieTitle` varchar(255) DEFAULT NULL,
  `movieTagline` varchar(255) DEFAULT NULL,
  `movieOriginalTitle` varchar(255) DEFAULT NULL,
  `movieOriginalLanguage` char(2) DEFAULT NULL,
  `movieStatus` enum('Rumored','Planned','In Production','Post Production','Released','Canceled') DEFAULT NULL,
  `movieOverview` text DEFAULT NULL,
  `movieReleaseDate` date DEFAULT NULL,
  `movieImdb` char(9) DEFAULT NULL,
  `movieCollection` bigint(20) UNSIGNED DEFAULT NULL,
  `moviePoster` varchar(100) DEFAULT NULL,
  `movieRuntime` smallint(5) UNSIGNED DEFAULT NULL,
  `moviePopularity` double DEFAULT 0,
  `movieAdult` bit(1) DEFAULT NULL,
  `movieVideo` bit(1) DEFAULT NULL,
  `movieVoteAverage` double DEFAULT NULL,
  `movieVoteCount` mediumint(8) UNSIGNED DEFAULT NULL,
  `movieBudget` int(10) UNSIGNED DEFAULT NULL,
  `movieRevenue` int(11) DEFAULT NULL,
  `movieHomepage` varchar(255) DEFAULT NULL,
  `movieUpdated` timestamp NULL DEFAULT NULL,
  `movieCollectionTitle` varchar(255) DEFAULT NULL,
  `movieCollectionPoster` varchar(255) DEFAULT NULL,
  `movieCompanies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `movieCountries` text DEFAULT NULL,
  `movieGenres` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `movieKeywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `movieLanguages` text DEFAULT NULL,
  `moviePersons` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`movieId`),
  KEY `movieOriginalLanguage` (`movieOriginalLanguage`),
  KEY `movieStatus` (`movieStatus`),
  KEY `movieReleaseDate` (`movieReleaseDate`),
  KEY `movieVoteAverage` (`movieVoteAverage`),
  KEY `movieVoteCount` (`movieVoteCount`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tvgenres`
--

DROP TABLE IF EXISTS `tvgenres`;
CREATE TABLE IF NOT EXISTS `tvgenres` (
  `tvgenreId` bigint(20) UNSIGNED NOT NULL,
  `tvgenreName` varchar(255) NOT NULL,
  `tvgenreUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`tvgenreId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Constraints der Tabelle `networks`
--
ALTER TABLE `networks`
  ADD CONSTRAINT `networks_ibfk_1` FOREIGN KEY (`networkCountry`) REFERENCES `countries` (`countryCode`) ON DELETE CASCADE ON UPDATE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


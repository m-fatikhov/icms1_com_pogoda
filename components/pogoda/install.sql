--
-- Удаление таблиц предыдущих версий
--
DROP TABLE IF EXISTS `#__pogoda_current`;
DROP TABLE IF EXISTS `#__pogoda_forecast`;

--
-- Таблицы текущей версии компонента
--
DROP TABLE IF EXISTS `#__pogoda_5days`;
CREATE TABLE IF NOT EXISTS `#__pogoda_5days` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `xml` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `#__pogoda_16days`;
CREATE TABLE IF NOT EXISTS `#__pogoda_16days` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `xml` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `#__pogoda_current`;
CREATE TABLE IF NOT EXISTS `#__pogoda_current` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `xml` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

DROP TABLE IF EXISTS `#__pogoda_props`;
CREATE TABLE IF NOT EXISTS `#__pogoda_props` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `days` tinyint(2) unsigned NOT NULL,
  `h1` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  `meta_keys` text NOT NULL,
  `meta_desc` text NOT NULL,
  `before_text` text NOT NULL,
  `after_text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `days` (`days`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Удаление записей задач CRON предыдущих версий
--
DELETE FROM `#__cron_jobs` WHERE `component` = 'pogoda';

--
-- Добавление записей задач CRON
--
INSERT INTO `#__cron_jobs` (`job_name`, `job_interval`, `component`, `model_method`, `custom_file`, `is_enabled`, `is_new`, `comment`, `class_name`, `class_method`)
VALUES ('parse5daysForecastWeather', 12, 'pogoda', 'parse5daysForecastWeather', '', 1, 0, 'Импортирует прогноз погоды на 5 дней с сайта openweathermap.org', '', ''),
('parse16daysForecastWeather', 12, 'pogoda', 'parse16daysForecastWeather', '', 1, 0, 'Импортирует прогноз погоды на 16 дней с сайта openweathermap.org', '', ''),
('parseCurrentWeather', 1, 'pogoda', 'parseCurrentWeather', '', 1, 0, 'Импортирует текущую погоду с сайта openweathermap.org', '', '');

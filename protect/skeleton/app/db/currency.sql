SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `directory_currency_rate`;
CREATE TABLE IF NOT EXISTS `directory_currency_rate` (
  `currency_from` varchar(3) NOT NULL COMMENT 'Currency Code Convert From',
  `currency_to` varchar(3) NOT NULL COMMENT 'Currency Code Convert To',
  `rate` decimal(24,12) NOT NULL DEFAULT '0.000000000000' COMMENT 'Currency Conversion Rate',
  PRIMARY KEY (`currency_from`,`currency_to`),
  KEY `IDX_DIRECTORY_CURRENCY_RATE_CURRENCY_TO` (`currency_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Directory Currency Rate';

INSERT INTO `directory_currency_rate` (`currency_from`, `currency_to`, `rate`) VALUES
('EUR', 'AED', 5.057500000000),
('EUR', 'CAD', 1.457400000000),
('EUR', 'CHF', 1.221100000000),
('EUR', 'CNY', 8.360600000000),
('EUR', 'DKK', 7.460000000000),
('EUR', 'DOP', 58.697200000000),
('EUR', 'EGP', 9.484400000000),
('EUR', 'EUR', 1.000000000000),
('EUR', 'GBP', 0.843800000000),
('EUR', 'IDR', 16675.634800000000),
('EUR', 'INR', 85.385000000000),
('EUR', 'JPY', 141.800000000000),
('EUR', 'KWD', 0.389500000000),
('EUR', 'KZT', 212.097700000000),
('EUR', 'LBP', 2074.988300000000),
('EUR', 'MXN', 17.817800000000),
('EUR', 'PAB', 1.376900000000),
('EUR', 'PEN', 3.811300000000),
('EUR', 'PKR', 147.328300000000),
('EUR', 'QAR', 5.013600000000),
('EUR', 'RUB', 45.363300000000),
('EUR', 'SAR', 5.164200000000),
('EUR', 'SEK', 9.056400000000),
('EUR', 'SGD', 1.727900000000),
('EUR', 'THB', 44.053900000000),
('EUR', 'TND', 2.297400000000),
('EUR', 'TRY', 2.791600000000),
('EUR', 'TTD', 8.825900000000),
('EUR', 'TWD', 40.838900000000),
('EUR', 'USD', 1.376900000000);


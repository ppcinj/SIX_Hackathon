load data infile 'C:/import.csv'
into table import
fields terminated by ";"
lines terminated by "\r\n"
ignore 1 lines
(@id, days, location, merchCategory, country, countryCode, longtitude, latitude, spend, numTrx, homeLocation)
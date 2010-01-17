ALTER TABLE `kwl_anderobjectbezoek` 
CHANGE `toestand_niet_bekeken` `toestand_bekeken` VARCHAR( 16 );

update `kwl_anderobjectbezoek` 
set `toestand_bekeken` = 'bekeken'
where `toestand_bekeken` = '1';

update `kwl_anderobjectbezoek` 
set `toestand_bekeken` = 'niet bekeken'
where `toestand_bekeken` = '0';


ALTER TABLE `kwl_anderobjectbezoek` 
CHANGE `bedreigingen_niet_bekeken` `bedreigingen_bekeken` VARCHAR( 16 );

update `kwl_anderobjectbezoek` 
set `bedreigingen_bekeken` = 'bekeken'
where `bedreigingen_bekeken` = '1';

update `kwl_anderobjectbezoek` 
set `bedreigingen_bekeken` = 'niet bekeken'
where `bedreigingen_bekeken` = '0';


ALTER TABLE `kwl_anderobjectbezoek` 
CHANGE `recreatieve_ontsluiting_niet_bekeken` `recreatieve_ontsluiting_bekeken` VARCHAR( 16 );

update `kwl_anderobjectbezoek` 
set `recreatieve_ontsluiting_bekeken` = 'bekeken'
where `recreatieve_ontsluiting_bekeken` = '1';

update `kwl_anderobjectbezoek` 
set `recreatieve_ontsluiting_bekeken` = 'niet bekeken'
where `recreatieve_ontsluiting_bekeken` = '0';




ALTER TABLE `kwl_anderobjectbezoek` 
CHANGE `recreatieve_ontsluiting_andere` `recreatieve_ontsluiting_andere_omschrijving` VARCHAR( 64 );

ALTER TABLE `kwl_anderobjectbezoek` 
ADD `recreatieve_ontsluiting_langs_trage_weg` BOOL NULL DEFAULT NULL AFTER `recreatieve_ontsluiting_bekeken` ,
ADD `recreatieve_ontsluiting_fietspad` BOOL NULL DEFAULT NULL AFTER `recreatieve_ontsluiting_langs_trage_weg` ,
ADD `recreatieve_ontsluiting_andere` BOOL NULL DEFAULT NULL AFTER `recreatieve_ontsluiting_fietspad` ;

update `kwl_anderobjectbezoek` 
set `recreatieve_ontsluiting_langs_trage_weg` = 1
where `recreatieve_ontsluiting` = 'Ligt langs trage weg';

update `kwl_anderobjectbezoek` 
set `recreatieve_ontsluiting_fietspad` = 1
where `recreatieve_ontsluiting` = 'Fietspad';

update `kwl_anderobjectbezoek` 
set `recreatieve_ontsluiting_andere` = 1
where `recreatieve_ontsluiting` = 'Andere';

ALTER TABLE `kwl_anderobjectbezoek` DROP `recreatieve_ontsluiting`;

ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `niet_bekeken` `bekeken` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `bekeken` = 'bekeken'
where `bekeken` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `bekeken` = 'niet bekeken'
where `bekeken` = '0';






ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `toestand_buiten_niet_bekeken` `toestand_buiten_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek` 
set `toestand_buiten_bekeken` = 'bekeken'
where `toestand_buiten_bekeken` = '1';

update `kwl_bunkerbezoek` 
set `toestand_buiten_bekeken` = 'niet bekeken'
where `toestand_buiten_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `bedreigingen_niet_bekeken` `bedreigingen_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek` 
set `bedreigingen_bekeken` = 'bekeken'
where `bedreigingen_bekeken` = '1';

update `kwl_bunkerbezoek` 
set `bedreigingen_bekeken` = 'niet bekeken'
where `bedreigingen_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
ADD COLUMN `toestand_buiten_toegankelijk_bekeken` varchar(16) DEFAULT NULL;

update `kwl_bunkerbezoek` 
set `toestand_buiten_toegankelijk_bekeken` = 'niet bekeken'
where `toestand_buiten_toegankelijk` = 'Niet bekeken';

update `kwl_bunkerbezoek` 
set `toestand_buiten_toegankelijk_bekeken` = 'bekeken'
where not `toestand_buiten_toegankelijk` <=> 'Niet bekeken';

ALTER TABLE `kwl_bunkerbezoek` 
ADD COLUMN `toestand_buiten_ontoegankelijk_reden_bekeken` varchar(16) DEFAULT NULL;

update `kwl_bunkerbezoek` 
set `toestand_buiten_ontoegankelijk_reden_bekeken` = 'niet bekeken'
where `toestand_buiten_ontoegankelijk_reden` = 'Niet bekeken';

update `kwl_bunkerbezoek` 
set `toestand_buiten_ontoegankelijk_reden_bekeken` = 'bekeken'
where not `toestand_buiten_ontoegankelijk_reden` <=> 'Niet bekeken';






ALTER TABLE `kwl_bunkerbezoek` 
ADD COLUMN `toestand_binnen_gebruik_bekeken` varchar(16) DEFAULT NULL;

update `kwl_bunkerbezoek` 
set `toestand_binnen_gebruik_bekeken` = 'niet bekeken'
where `toestand_binnen_gebruik` = 'Niet bekeken';

update `kwl_bunkerbezoek` 
set `toestand_binnen_gebruik_bekeken` = 'bekeken'
where not `toestand_binnen_gebruik` <=> 'Niet bekeken';

ALTER TABLE `kwl_bunkerbezoek` 
ADD COLUMN `toestand_binnen_toestand_bekeken` varchar(16) DEFAULT NULL;

update `kwl_bunkerbezoek` 
set `toestand_binnen_toestand_bekeken` = 'niet bekeken'
where `toestand_binnen_toestand` = 'Niet bekeken';

update `kwl_bunkerbezoek` 
set `toestand_binnen_toestand_bekeken` = 'bekeken'
where not `toestand_binnen_toestand` <=> 'Niet bekeken';

ALTER TABLE `kwl_bunkerbezoek` 
ADD COLUMN `toestand_binnen_vochtigheid_bekeken` varchar(16) DEFAULT NULL;

update `kwl_bunkerbezoek` 
set `toestand_binnen_vochtigheid_bekeken` = 'niet bekeken'
where `toestand_binnen_vochtigheid` = 'Niet bekeken';

update `kwl_bunkerbezoek` 
set `toestand_binnen_vochtigheid_bekeken` = 'bekeken'
where not `toestand_binnen_vochtigheid` <=> 'Niet bekeken';





ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `omgeving_10m_niet_bekeken` `omgeving_10m_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek` 
set `omgeving_10m_bekeken` = 'bekeken'
where `omgeving_10m_bekeken` = '1';

update `kwl_bunkerbezoek` 
set `omgeving_10m_bekeken` = 'niet bekeken'
where `omgeving_10m_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `omgeving_100m_niet_bekeken` `omgeving_100m_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek` 
set `omgeving_100m_bekeken` = 'bekeken'
where `omgeving_100m_bekeken` = '1';

update `kwl_bunkerbezoek` 
set `omgeving_100m_bekeken` = 'niet bekeken'
where `omgeving_100m_bekeken` = '0'

ALTER TABLE `kwl_bunkerbezoek` 
ADD COLUMN `ligging_bekeken` varchar(16) DEFAULT NULL;

update `kwl_bunkerbezoek` 
set `ligging_bekeken` = 'niet bekeken'
where `ligging` = 'Niet bekeken';

update `kwl_bunkerbezoek` 
set `ligging_bekeken` = 'bekeken'
where not `ligging` <=> 'Niet bekeken';

ALTER TABLE `kwl_bunkerbezoek` 
ADD COLUMN `expositie_bekeken` varchar(16) DEFAULT NULL;

update `kwl_bunkerbezoek` 
set `expositie_bekeken` = 'niet bekeken'
where `expositie` = 'Niet bekeken';

update `kwl_bunkerbezoek` 
set `expositie_bekeken` = 'bekeken'
where not `expositie` <=> 'Niet bekeken';

ALTER TABLE `kwl_bunkerbezoek` 
ADD COLUMN `relief_bekeken` varchar(16) DEFAULT NULL;

update `kwl_bunkerbezoek` 
set `relief_bekeken` = 'niet bekeken'
where `relief` = 'Niet bekeken';

update `kwl_bunkerbezoek` 
set `relief_bekeken` = 'bekeken'
where not `relief` <=> 'Niet bekeken';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `afstand_berijdbare_weg_niet_bekeken` `afstand_berijdbare_weg_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek` 
set `afstand_berijdbare_weg_bekeken` = 'bekeken'
where `afstand_berijdbare_weg_bekeken` = '1';

update `kwl_bunkerbezoek` 
set `afstand_berijdbare_weg_bekeken` = 'niet bekeken'
where `afstand_berijdbare_weg_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
ADD COLUMN `recreatieve_ontsluiting_bekeken` varchar(16) DEFAULT NULL;

update `kwl_bunkerbezoek` 
set `recreatieve_ontsluiting_bekeken` = 'niet bekeken'
where `recreatieve_ontsluiting` = 'Niet bekeken';

update `kwl_bunkerbezoek` 
set `recreatieve_ontsluiting_bekeken` = 'bekeken'
where not `recreatieve_ontsluiting` <=> 'Niet bekeken';





ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `recreatieve_ontsluiting_andere` `recreatieve_ontsluiting_andere_omschrijving` VARCHAR( 64 );

ALTER TABLE `kwl_bunkerbezoek` 
ADD `recreatieve_ontsluiting_langs_trage_weg` BOOL NULL DEFAULT NULL AFTER `recreatieve_ontsluiting_bekeken` ,
ADD `recreatieve_ontsluiting_fietspad` BOOL NULL DEFAULT NULL AFTER `recreatieve_ontsluiting_langs_trage_weg` ,
ADD `recreatieve_ontsluiting_informatiebord` BOOL NULL DEFAULT NULL AFTER `recreatieve_ontsluiting_fietspad` ,
ADD `recreatieve_ontsluiting_andere` BOOL NULL DEFAULT NULL AFTER `recreatieve_ontsluiting_informatiebord` ;

update `kwl_bunkerbezoek` 
set `recreatieve_ontsluiting_langs_trage_weg` = 1
where `recreatieve_ontsluiting` = 'Ligt langs trage weg';

update `kwl_bunkerbezoek` 
set `recreatieve_ontsluiting_fietspad` = 1
where `recreatieve_ontsluiting` = 'Fietspad';

update `kwl_bunkerbezoek` 
set `recreatieve_ontsluiting_informatiebord` = 1
where `recreatieve_ontsluiting` = 'Nuttig om informatiebord te plaatsen?';

update `kwl_bunkerbezoek` 
set `recreatieve_ontsluiting_andere` = 1
where `recreatieve_ontsluiting` = 'Andere';

ALTER TABLE `kwl_bunkerbezoek` DROP `recreatieve_ontsluiting`;




ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `binnendeur_niet_bekeken` `binnendeur_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek`
set `binnendeur_bekeken` = 'bekeken'
where `binnendeur_bekeken` = '1';

update `kwl_bunkerbezoek`
set `binnendeur_bekeken` = 'niet bekeken'
where `binnendeur_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `buitendeur_niet_bekeken` `buitendeur_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek`
set `buitendeur_bekeken` = 'bekeken'
where `buitendeur_bekeken` = '1';

update `kwl_bunkerbezoek`
set `buitendeur_bekeken` = 'niet bekeken'
where `buitendeur_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `camouflage_niet_bekeken` `camouflage_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek`
set `camouflage_bekeken` = 'bekeken'
where `camouflage_bekeken` = '1';

update `kwl_bunkerbezoek`
set `camouflage_bekeken` = 'niet bekeken'
where `camouflage_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `dakplaten_niet_bekeken` `dakplaten_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek`
set `dakplaten_bekeken` = 'bekeken'
where `dakplaten_bekeken` = '1';

update `kwl_bunkerbezoek`
set `dakplaten_bekeken` = 'niet bekeken'
where `dakplaten_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `afsluitluik_granaatwerper_niet_bekeken` `afsluitluik_granaatwerper_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek`
set `afsluitluik_granaatwerper_bekeken` = 'bekeken'
where `afsluitluik_granaatwerper_bekeken` = '1';

update `kwl_bunkerbezoek`
set `afsluitluik_granaatwerper_bekeken` = 'niet bekeken'
where `afsluitluik_granaatwerper_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `ingang_niet_bekeken` `ingang_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek`
set `ingang_bekeken` = 'bekeken'
where `ingang_bekeken` = '1';

update `kwl_bunkerbezoek`
set `ingang_bekeken` = 'niet bekeken'
where `ingang_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `rooster_ingang_niet_bekeken` `rooster_ingang_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek`
set `rooster_ingang_bekeken` = 'bekeken'
where `rooster_ingang_bekeken` = '1';

update `kwl_bunkerbezoek`
set `rooster_ingang_bekeken` = 'niet bekeken'
where `rooster_ingang_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `nooduitgang_niet_bekeken` `nooduitgang_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek`
set `nooduitgang_bekeken` = 'bekeken'
where `nooduitgang_bekeken` = '1';

update `kwl_bunkerbezoek`
set `nooduitgang_bekeken` = 'niet bekeken'
where `nooduitgang_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `afsluitluik_pistoolkoker_niet_bekeken` `afsluitluik_pistoolkoker_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek`
set `afsluitluik_pistoolkoker_bekeken` = 'bekeken'
where `afsluitluik_pistoolkoker_bekeken` = '1';

update `kwl_bunkerbezoek`
set `afsluitluik_pistoolkoker_bekeken` = 'niet bekeken'
where `afsluitluik_pistoolkoker_bekeken` = '0';

ALTER TABLE `kwl_bunkerbezoek` 
CHANGE `verluchtingspijpen_niet_bekeken` `verluchtingspijpen_bekeken` VARCHAR( 16 );

update `kwl_bunkerbezoek`
set `verluchtingspijpen_bekeken` = 'bekeken'
where `verluchtingspijpen_bekeken` = '1';

update `kwl_bunkerbezoek`
set `verluchtingspijpen_bekeken` = 'niet bekeken'
where `verluchtingspijpen_bekeken` = '0';

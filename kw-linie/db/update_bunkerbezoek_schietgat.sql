
ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `metalen_schap_met_haken_aanwezig` `metalen_schap_met_haken_aanwezig` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `metalen_schap_met_haken_aanwezig` = 'aanwezig'
where `metalen_schap_met_haken_aanwezig` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `metalen_schap_met_haken_aanwezig` = 'niet aanwezig'
where `metalen_schap_met_haken_aanwezig` = '0';


ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `zitbankje_aanwezig` `zitbankje_aanwezig` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `zitbankje_aanwezig` = 'aanwezig'
where `zitbankje_aanwezig` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `zitbankje_aanwezig` = 'niet aanwezig'
where `zitbankje_aanwezig` = '0';


ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `houten_schapje_aanwezig` `houten_schapje_aanwezig` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `houten_schapje_aanwezig` = 'aanwezig'
where `houten_schapje_aanwezig` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `houten_schapje_aanwezig` = 'niet aanwezig'
where `houten_schapje_aanwezig` = '0';


ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `haken_petroleumlampen_aanwezig` `haken_petroleumlampen_aanwezig` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `haken_petroleumlampen_aanwezig` = 'aanwezig'
where `haken_petroleumlampen_aanwezig` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `haken_petroleumlampen_aanwezig` = 'niet aanwezig'
where `haken_petroleumlampen_aanwezig` = '0';


ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `observatiesleuf_luikje_aanwezig` `observatiesleuf_luikje_aanwezig` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `observatiesleuf_luikje_aanwezig` = 'aanwezig'
where `observatiesleuf_luikje_aanwezig` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `observatiesleuf_luikje_aanwezig` = 'niet aanwezig'
where `observatiesleuf_luikje_aanwezig` = '0';


ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `witte_lijn_aanwezig` `witte_lijn_aanwezig` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `witte_lijn_aanwezig` = 'aanwezig'
where `witte_lijn_aanwezig` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `witte_lijn_aanwezig` = 'niet aanwezig'
where `witte_lijn_aanwezig` = '0';


ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `affuit_nummer_inscripties_aanwezig` `affuit_nummer_inscripties_aanwezig` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `affuit_nummer_inscripties_aanwezig` = 'aanwezig'
where `affuit_nummer_inscripties_aanwezig` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `affuit_nummer_inscripties_aanwezig` = 'niet aanwezig'
where `affuit_nummer_inscripties_aanwezig` = '0';


ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `affuit_verankeringspunten_aanwezig` `affuit_verankeringspunten_aanwezig` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `affuit_verankeringspunten_aanwezig` = 'aanwezig'
where `affuit_verankeringspunten_aanwezig` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `affuit_verankeringspunten_aanwezig` = 'niet aanwezig'
where `affuit_verankeringspunten_aanwezig` = '0';


ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `affuit_aanwezig` `affuit_aanwezig` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `affuit_aanwezig` = 'aanwezig'
where `affuit_aanwezig` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `affuit_aanwezig` = 'niet aanwezig'
where `affuit_aanwezig` = '0';


ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `afsluitluik_bedieningsketting_aanwezig` `afsluitluik_bedieningsketting_aanwezig` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `afsluitluik_bedieningsketting_aanwezig` = 'aanwezig'
where `afsluitluik_bedieningsketting_aanwezig` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `afsluitluik_bedieningsketting_aanwezig` = 'niet aanwezig'
where `afsluitluik_bedieningsketting_aanwezig` = '0';


ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `afsluitluik_buitenzijde_aanwezig` `afsluitluik_buitenzijde_aanwezig` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `afsluitluik_buitenzijde_aanwezig` = 'aanwezig'
where `afsluitluik_buitenzijde_aanwezig` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `afsluitluik_buitenzijde_aanwezig` = 'niet aanwezig'
where `afsluitluik_buitenzijde_aanwezig` = '0';






ALTER TABLE `kwl_bunkerbezoek_schietgat` 
CHANGE `niet_bekeken` `bekeken` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_bunkerbezoek_schietgat` 
set `bekeken` = 'bekeken'
where `bekeken` = '1';

update `kwl_bunkerbezoek_schietgat` 
set `bekeken` = 'niet bekeken'
where `bekeken` = '0';


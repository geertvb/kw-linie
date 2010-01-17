ALTER TABLE `kwl_vleermuisbezoek` 
CHANGE `maatregelen_bekeken` `maatregelen_bekeken` VARCHAR( 16 ) NULL DEFAULT NULL ;

update `kwl_vleermuisbezoek` 
set `maatregelen_bekeken` = 'bekeken'
where `maatregelen_bekeken` = '1';

update `kwl_vleermuisbezoek` 
set `maatregelen_bekeken` = 'niet bekeken'
where `maatregelen_bekeken` = '0';
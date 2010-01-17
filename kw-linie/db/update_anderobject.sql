ALTER TABLE `kwl_anderobject` 
CHANGE `aanwezig` `aanwezig` VARCHAR( 16 );

update `kwl_anderobject` 
set `aanwezig` = 'aanwezig'
where `aanwezig` = '1';

update `kwl_anderobject` 
set `aanwezig` = 'niet aanwezig'
where `aanwezig` = '0';
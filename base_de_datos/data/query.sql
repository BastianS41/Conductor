

------------------ EJERCICIO 4 ----------------------------------------
create table reportes(id serial, confirmados int, vivos int, muertos int, geom geometry);

insert into reportes(confirmados,vivos,muertos,geom) values (174,30,144, st_setsrid(st_makepoint(-76,3),4326));

------------------- EJERCICIO 5 ----------------------------------------
create table reportes2(id serial, adultos int, jovenes int, ancianos int, bebes int, geom geometry);

insert into reportes2(adultos,jovenes,ancianos,bebes,geom) values (30,30,10,50, st_setsrid(st_makepoint(-76.55119,3.39091),4326));
insert into reportes2(adultos,jovenes,ancianos,bebes,geom) values (15,2,20,30, st_setsrid(st_makepoint(-76.58624,3.46637),4326));
insert into reportes2(adultos,jovenes,ancianos,bebes,geom) values (5,90,30,22, st_setsrid(st_makepoint(-76.53117,3.37521),4326));
insert into reportes2(adultos,jovenes,ancianos,bebes,geom) values (20,9,1,17, st_setsrid(st_makepoint(-76.54501,3.45088),4326));
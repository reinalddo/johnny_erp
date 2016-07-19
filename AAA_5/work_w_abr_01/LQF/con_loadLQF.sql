
delete from tmp_det;
load data local infile 
'/root/Escritorio/temp/LQF/AMEN_LQF.csv' 
into table  tmp_det 
fields escaped by '\\' terminated by ',' enclosed by '"' 
lines terminated by '\n'
ignore 2 lines; 
;

/*para poder insertar un nuevo tema en una materia especifica, periodo y año especifico, dado por sfkey.*/
insert into temasporcalificar (sfkey,nombretema) values('03010328232A','Tema1');

/*para seleccionar la lista de alumnos del maximo tema del grupo especifico esto servira para la insercion en calificacionportemas*/

SELECT TemasPorCalificar.idTemaCalificar, Listas.NumCont
FROM Listas, TemasPorCalificar 
where listas.sfkey=temasporcalificar.sfkey and 
listas.sfkey='03010328232A' and 
TemasPorCalificar.idtemacalificar in(select max(idtemacalificar) from temasporcalificar where sfkey='03010328232A')

/*insertamos las calificaciones de los temas cuando se crea un nuevo tema para el grupo tomando en cuenta la consulta anterior 
se debe hacer solo cuando se inserta un nuevo tema*/

insert into calificaciontema(idtemacalificar,numcont)
SELECT TemasPorCalificar.idTemaCalificar, Listas.NumCont
FROM Listas, TemasPorCalificar 
where listas.sfkey=temasporcalificar.sfkey and 
listas.sfkey='03010328232A' and 
TemasPorCalificar.idtemacalificar in(select max(idtemacalificar) from temasporcalificar where sfkey='03010328232A')

/*muestra numero de control, apellidos y nombre, junto con las calificaciones de los temas existentes de un sfkey*/

TRANSFORM Sum(calificaciontema.calificacion) AS calificacion
SELECT alumnos.numcont, alumnos.nom, alumnos.ape
FROM ((alumnos 
        INNER JOIN Listas ON alumnos.NumCont = Listas.NumCont) 
        INNER JOIN TemasPorCalificar ON Listas.sFKey = TemasPorCalificar.sFKey) 
        INNER JOIN CalificacionTema ON (alumnos.NumCont = CalificacionTema.NumCont) 
        AND (TemasPorCalificar.idTemaCalificar = CalificacionTema.idTemaCalificar) 
WHERE Listas.sFKey='03010328232A'
GROUP BY alumnos.numcont, alumnos.nom, alumnos.ape
PIVOT temasporcalificar.nombretema;
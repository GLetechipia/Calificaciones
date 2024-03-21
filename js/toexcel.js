function htmlExcel(idTabla, nombreArchivo = '') {
    let linkDescarga;
    let tipoDatos = 'application/vnd.ms-excel';
    let tablaDatos = document.getElementById(idTabla);
    let tablaHTML = tablaDatos.outerHTML.replace(/ /g, '%20');

    // Nombre del archivo
    nombreArchivo = nombreArchivo ? nombreArchivo + '.xlsx' : 'Reporte_Puntos_Canjeados.xlsx';

    // Crear el link de descarga
    linkDescarga = document.createElement("a");

    document.body.appendChild(linkDescarga);

    if (navigator.msSaveOrOpenBlob) {
        let blob = new Blob(['\ufeff', tablaHTML], {
            type: tipoDatos
        });
        navigator.msSaveOrOpenBlob(blob, nombreArchivo);
    } else {
        // Crear el link al archivo
        linkDescarga.href = 'data:' + tipoDatos + ', ' + tablaHTML;

        // Setear el nombre de archivo
        linkDescarga.download = nombreArchivo;

        //Ejecutar la función
        linkDescarga.click();
    }
}


/*** otro más
 */

function exportReportToExcel(identificador, nombre) {
    let table = document.getElementsByID(identificador);
    TableToExcel.convert(table[0], {
        name: nombre,
        sheet: {
            name: 'Sheet 1'
        }
    });
}

function exportReportToExcel() {
    let table = document.getElementsByID("table");
    TableToExcel.convert(table[0], {
        name: `file.xlsx`,
        sheet: {
            name: 'Sheet 1'
        }
    });
}
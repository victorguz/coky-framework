<?php
//========================================================================================
/*                                                                                      *
 *                         CONFIGURACIONES DE LAS BASE DE DATOS                         *
 *                                                                                      */
//========================================================================================
/**
 * $config['database']: Configuraciones de las bases de datos es un array multidimensional para brindar
 * la posibilidad de configurar múltiples bases de datos siguiento la lógia $config['database']['GRUPO']['CONFIGURACIÓN'].
 * Las confiraciones posibles son [driver,db,user,password,host,charset]
 *
 * Por ejemplo:
 * $config['database']['default']['driver'] = 'mysql'; //Driver que se usará en PDO
 * $config['database']['default']['db'] = 'piecesphp'; //Nombre de la base de datos
 * $config['database']['default']['user'] = 'root'; //Usuario
 * $config['database']['default']['password'] = ''; //Contraseña
 * $config['database']['default']['host'] = 'localhost'; //Host
 * $config['database']['default']['charset'] = 'utf8'; //Juego de caracteres
 *
 * Nota: el ejemplo anterior muestra los valores que la aplicación asume por defecto.
 *
 */

//──── Bases de datos ────────────────────────────────────────────────────────────────────
if (is_local()) {
    $config['database']['default']['driver'] = 'mysql';
    $config['database']['default']['db'] = 'coky-framework';
    $config['database']['default']['user'] = 'admin_general';
    $config['database']['default']['password'] = '123456';
    $config['database']['default']['host'] = 'localhost';
    $config['database']['default']['charset'] = 'utf8';
} else {
    $config['database']['default']['driver'] = 'mysql';
    $config['database']['default']['db'] = 'coky-framework';
    $config['database']['default']['user'] = 'admin_general';
    $config['database']['default']['password'] = 'VirsMA8ph6bd';
    $config['database']['default']['host'] = 'localhost';
    $config['database']['default']['charset'] = 'utf8';
}
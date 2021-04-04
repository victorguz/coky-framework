# PiecesPHP Framework Edited by Victorguz

## Pasos de instalación

**Requerimientos:**

- NodeJS y Gulp: Compilar SASS
- PHP y Composer: Lenguaje base
- Apache y MariaDB: Servidor y base de datos por defecto.

**Comandos:**

- Instalar módulos de npm requeridos (por el momento solo gulp)

npm install

- Instalar módulos de composer (debes dirigirte a /src primero)

composer install

- Para Linux es necesario otorgar estos permisos a /src

sudo chmod -Rf 0777 src

- Compilar SASS de la zona pública

*Para compilar solo una vez*
gulp sass:init 
*Para compilar con cada modificación (observar cambios)*
gulp sass:watch 

- Compilar SASS de la zona administrativa

*Para compilar solo una vez*
gulp sass-vendor:init 
*Para compilar con cada modificación (observar cambios)*
gulp sass-vendor:watch 


- Compilar SASS de la zona pública y administrativa a la vez

*Para compilar solo una vez*
gulp sass-vendor:init sass:init 
*Para compilar con cada modificación (observar cambios)*
gulp sass-vendor:watch sass:watch 

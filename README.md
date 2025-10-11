# DSTicket — Instalador (beta)

Instalador web en **PHP** para **DSTicket**, un sistema de tickets/soporte.  
Crea la configuración inicial, prueba la conexión a MySQL y (opcional) ejecuta migraciones.

> Estado: **beta** — Paso 1 (requisitos) y Paso 2 (configuración) estables. Paso 3 para migraciones disponible.

---ver instalacion de xammp en linea 182

## Requisitos

- PHP **8.0+** con extensión **pdo_mysql**
- MySQL/MariaDB (en local puede ser XAMPP; en este proyecto usamos **puerto 3307**)
- Servidor web (Apache/Nginx). En local: `http://127.0.0.1/dsticket/public/`

---

## Instalación rápida (local con XAMPP)

1. **Clonar el repo**
   ```bash
   htdocs/
   └── dsticket/
       ├── public/
       └── app/
Crear la base de datos (phpMyAdmin → SQL)

CREATE DATABASE IF NOT EXISTS dsticket
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

Abrir el instalador en el navegador
  http://127.0.0.1/dsticket/public/install/
Paso 1: revisa requisitos (PHP, pdo_mysql, permisos de /app).

Paso 2: completa el formulario:
Sistema: nombre + email
Admin: nombre + email + contraseña
Base de datos:
Host: 127.0.0.1
Puerto: 3306 ← si tu MySQL es XAMPP en 3306

BD: dsticket
Usuario: dstuser (o el que creaste)
Contraseña: (la definida arriba)
Prefijo: dst_ (o el que prefieras)
Al guardar:
Se validan los datos y la conexión PDO (crea/borra una tabla temporal)
Se generan:
app/config.php
app/install_state.json

Paso 3 (migraciones / opcional)
Si incluyes SQL en app/sql/*.sql, Step 3 las ejecuta (prefijo con {prefix_}).
Inserta el admin usando el password_hash guardado.
Crea app/INSTALL_OK.

Post-instalación (seguridad)
Elimina o renombra /public/install/.
Comprueba que existe app/INSTALL_OK.


se generan tras instalación:
app/config.php
app/install_state.json
app/INSTALL_OK

<img width="558" height="288" alt="image" src="https://github.com/user-attachments/assets/57ce8a59-91f0-4c5d-af4d-13c62c8b256c" />

Reinstalar (limpio) si falla o quieres rehacer

Restaurar /public/install/ si la eliminaste.

Borrar:

app/INSTALL_OK
app/config.php
app/install_state.json


Vaciar o borrar y recrear la base de datos:

DROP DATABASE IF EXISTS dsticket;
CREATE DATABASE dsticket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


Repetir la instalación desde /public/install/.

//------------------------------------
//## Instalación con XAMPP (Windows)
//------------------------------------

1. **Instala y arranca XAMPP**
   - Abre **XAMPP Control Panel** → inicia **Apache** y **MySQL**.
   - (Opcional) Comprueba el **puerto de MySQL**: en el panel, botón **Config** (de MySQL) → `my.ini` → busca `port=` (suele ser `3306` o `3307`).

2. **Coloca el proyecto**
   - Carpeta: `C:\xampp\htdocs\dsticket`
   - Estructura: `dsticket/public/...` y `dsticket/app/...`

3. **Habilita PDO MySQL (si hace falta)**
   - Edita `C:\xampp\php\php.ini` y asegúrate de tener:
     ```
     extension=pdo_mysql
     ```
   - Reinicia **Apache**.

4. **Crea la base de datos y el usuario (phpMyAdmin)**
   - Abre: `http://localhost/phpmyadmin/`
   - SQL → ejecuta:
     ```sql
     CREATE DATABASE IF NOT EXISTS dsticket
       CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

     -- Usuario dedicado (ajusta la contraseña)
     CREATE USER IF NOT EXISTS 'dstuser'@'127.0.0.1' IDENTIFIED BY 'TuClaveFuerte';
     GRANT ALL PRIVILEGES ON dsticket.* TO 'dstuser'@'127.0.0.1';
     FLUSH PRIVILEGES;
     ```

5. **Lanza el instalador**
   - Navega a: `http://localhost/dsticket/public/install/`
   - Paso 1: requisitos (PHP, pdo_mysql, permisos de `/app`)
   - Paso 2: formulario
     - **Host:** `127.0.0.1`
     - **Puerto:** (tu puerto real, p. ej. `3306`)
     - **BD:** `dsticket`
     - **Usuario:** `dstuser`
     - **Contraseña:** *(la que definiste)*
     - **Prefijo:** `dst_` (o el que quieras)

6. **Finaliza y asegura**
   - Tras completar, verifica que existen en `app/`: `config.php`, `install_state.json` y `INSTALL_OK`.
   - **Borra o renombra** `public/install/` por seguridad.
   - Entra a la app: `http://localhost/dsticket/public/`

### Problemas comunes (XAMPP)
- **`Access denied for user ...`** → el usuario no tiene permisos sobre la BD o el **host no coincide**. Si usas `127.0.0.1` en el formulario, da permisos a `'usuario'@'127.0.0.1'`.
- **Conecta a MySQL pero no a tu BD** → revisa el **puerto** que pones en el Step 2 (debe ser el de MySQL en XAMPP).
- **Falta PDO** → asegúrate de `extension=pdo_mysql` y reinicia Apache.

- 
Accede a la app:
http://127.0.0.1/dsticket/public/

## Créditos
**Autor/a:** CodeWinterStudio ([@shionwinter](https://github.com/shionwinter))  

**Logo/arte:** Propio.


## Licencia
© 2025 CodeWinterStudio — Licencia **MIT**.  
Se permite uso comercial y no comercial, modificación y redistribución, manteniendo este aviso y el archivo `LICENSE`.



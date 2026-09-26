# Control de Finanzas

Aplicación web para el control y registro de finanzas desarrollada con **PHP, MySQL, HTML, CSS y JavaScript**, utilizando **programación orientada a objetos (POO)**.

## Realizada por:

* Josue David Cortez Aguilar
* Jeferson Enrique Crespín Galdámez
* Kelvin René Guillen Alfaro
* Mariana Beatriz González Lopez
* Erick Alirio Méndez Méndez

## Descripción

El sistema permite administrar y consultar movimientos financieros mediante el registro de **entradas** y **salidas**, generando una factura en formato PDF para cada movimiento registrado.

Además, cuenta con un módulo de balance que permite consultar los totales de entradas, salidas y el balance financiero actual.

## Funcionalidades

* 🔐 Inicio y cierre de sesión.
* 💰 Registro de entradas financieras.
* 💸 Registro de salidas financieras.
* 📋 Consulta de entradas registradas.
* 📋 Consulta de salidas registradas.
* 🧾 Generación automática de facturas en formato PDF.
* 📊 Cálculo del total de entradas.
* 📊 Cálculo del total de salidas.
* 💵 Cálculo del balance financiero.
* 📈 Gráfica de distribución entre entradas y salidas.
* 📄 Exportación del balance a PDF.
* 📱 Interfaz adaptable a diferentes tamaños de pantalla.

## Tecnologías utilizadas

* **PHP 8**
* **MySQL / MariaDB**
* **HTML5**
* **CSS3**
* **JavaScript**
* **PDO** para la conexión con la base de datos.
* **Dompdf** para la generación de documentos PDF.
* **Chart.js** para la gráfica del balance.
* **Composer** para la gestión de dependencias.

## Programación orientada a objetos

El proyecto utiliza clases para separar las principales responsabilidades del sistema:

* `Database` — Gestiona la conexión con la base de datos.
* `Login` — Gestiona el inicio y cierre de sesión.
* `Entrada` — Gestiona el registro y consulta de entradas.
* `Salida` — Gestiona el registro y consulta de salidas.
* `ReporteBalance` — Obtiene los totales y calcula el balance financiero.

## Estructura del proyecto

```text
control-finanzas/
│
├── assets/
│   ├── css/
│   ├── img/
│   └── js/
│
├── classes/
│   ├── Entrada.php
│   ├── Login.php
│   ├── ReporteBalance.php
│   └── Salida.php
│
├── config/
│   └── Database.php
│
├── includes/
│   ├── footer.php
│   ├── header.php
│   └── sidebar.php
│
├── uploads/
│   ├── entradas/
│   └── salidas/
│
├── balance.php
├── dashboard.php
├── database.sql
├── exportar_pdf.php
├── index.php
├── login.php
├── logout.php
├── registrar_entrada.php
├── registrar_salida.php
├── ver_entradas.php
├── ver_salidas.php
├── composer.json
└── composer.lock
```

## Requisitos

Para ejecutar el proyecto localmente se necesita:

* **XAMPP** o un entorno equivalente.
* **PHP 8.0 o superior**.
* **MySQL o MariaDB**.
* **Composer**.
* Un navegador web.

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/JeferGal/control-finanzas.git
```

### 2. Entrar al proyecto

```bash
cd control-finanzas
```

### 3. Instalar las dependencias

```bash
composer install
```

### 4. Configurar la base de datos

Crear una base de datos llamada:

```text
control_finanzas
```

Después importar el archivo:

```text
database.sql
```

### 5. Configurar la conexión

La conexión a la base de datos se encuentra en:

```text
config/Database.php
```

Por defecto utiliza:

```text
Servidor: localhost
Base de datos: control_finanzas
Usuario: root
Contraseña: vacía
```

### 6. Ejecutar el proyecto

Colocar el proyecto dentro de la carpeta `htdocs` de XAMPP y acceder desde el navegador:

```text
http://localhost/control-finanzas/
```

## Facturas

Las facturas generadas automáticamente por el sistema se almacenan dentro de:

```text
uploads/entradas/
uploads/salidas/
```

Los archivos PDF generados no se incluyen en el repositorio debido a la configuración del `.gitignore`.

## Base de datos

El sistema utiliza tres tablas principales:

* `usuarios`
* `entradas`
* `salidas`

Los datos financieros se almacenan en MySQL/MariaDB y son consultados mediante PDO utilizando consultas preparadas.

## Licencia

Proyecto académico desarrollado para la asignatura **Lenguajes Interpretados en el Servidor**.

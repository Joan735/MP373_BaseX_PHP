## Implementación de BBDD con XML y BaseX

---

### 📋 Requisitos

* **PHP**
* **BaseX server** corriendo y accesible
* **Navegador web** 

---

### 🗂 Estructura del repositorio

```
/
├── BaseXClient/         # Librería de BaseX para PHP  
├── eventos.xml          # XML de ejemplo con los eventos  
├── load.php             # Incluye y configura BaseXClient  
├── Actualizar.html      # Formulario para actualizar un evento  
├── Actualizar.php       # Lógica para actualizar por ID  
├── Borrar.php           # Formulario y lógica para borrar por ID  
├── Filtrar.php          # Formulario y lógica para filtrar por ID  
├── Insertar.php         # Formulario y lógica para insertar un nuevo evento  
├── Lectura.php          # Muestra listados de eventos actuales  
├── Menu.html            # Página principal con enlaces a cada operación  
└── Menu_resultados.php  # Procesa la opción seleccionada y redirige  
```

---

### ▶️ Uso rápido

1. **Cargar** `eventos.xml` en tu base de datos BaseX.
2. **Abrir** `Menu.html` en el navegador.
3. **Seleccionar** la operación deseada y **completar** el formulario para gestionar las acciones deseadas.

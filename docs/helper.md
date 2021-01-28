# Crear un Helper

Los helper no son mas que funciones auxiliares que pueden ser invocadas desde cualquier otro fichero.

## Helpers preinstalados

```text
view() para trabajar con plantillas blade en caso de haber sido instalado
d() para el manejo de funciones de debug (Dump)
dd() para el mandejo de funciones de debug (Dump and Die)
```

## Como registrar mis propios helpers

```bash
php antonella helper mihelper
```

Out: src/Helpers/mihelper.php

```php
<?php
if (!function_exists('mihelper')) {
    /**
     * Make your Helper
     * not use exist helpers
     * for call this function globally:
     * mihelper();
     */
    function mihelper(){
    } 
}
?>
```

[Volver al índice](https://github.com/d3turnes/antonella-framework-for-wp/tree/2.0/docs/readme.md)
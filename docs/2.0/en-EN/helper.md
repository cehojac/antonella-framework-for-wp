# Creating a helper

The helpers are just auxiliary functions that can be invoked from any other file.

## Preinstalled Helpers

```text
view() to work with blade templates in case it has been installed
d() for handling debug functions (Dump)
dd() for debug function management (Dump and Die)
```

```text
For these pre-installed helpers to work, their modules must be installed.
in the case of view()
```

```bash
php antonella add blade
```

```text
En el caso de d() y dd()
```

```bash
php antonella add dd
```

## How to register my own helpers

```bash
php antonella make:helper mihelper
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

[Go Back](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/en-EN/readme.md)

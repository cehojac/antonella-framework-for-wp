---
sidebar_position: 3
---

# Antonella Console

The Antonella console is a powerful tool that allows you to create and manage your plugin components efficiently. All commands are executed from your project root.

## Available Commands

### General Help

```bash
php antonella help
```

Shows all available commands and their description.

### Namespace Management

```bash
# Change namespace to a specific one
php antonella namespace MY_NAMESPACE

# Generate a random namespace
php antonella namespace
```

## Component Creation

### Controllers

```bash
php antonella controller MyController
```

Creates a new controller in `src/Controllers/MyController.php`

### Models

```bash
php antonella model MyModel
```

Creates a new model in `src/Models/MyModel.php`

### Widgets

```bash
php antonella widget MyWidget
```

Creates a new custom widget for WordPress.

### Helpers

```bash
php antonella helper MyHelper
```

Creates a custom helper in `src/Helpers/MyHelper.php`

### Gutenberg Blocks

```bash
php antonella block my-first-block
```

Creates a custom Gutenberg block with all necessary structure.

## Development and Testing

### Development Server

```bash
php antonella serve
```

Mounts a local testing server at `http://localhost:8010` with test WordPress.

### Refresh Changes

```bash
php antonella test refresh
```

Refreshes changes made in development and shows them in the testing server.

### Docker

```bash
php antonella docker
```

Starts Docker environment for development. The site will be available at `http://localhost:8080`.

## Generated File Structure

### Controller Example

```php
<?php

namespace YOUR_NAMESPACE\Controllers;

class MyController {
    
    public function __construct() {
        // Constructor
    }
    
    public function myFunction() {
        // Your logic here
    }
}
```

### Model Example

```php
<?php

namespace YOUR_NAMESPACE\Models;

class MyModel {
    
    public function __construct() {
        // Constructor
    }
    
    public function getData() {
        // Logic to get data
    }
}
```

### Helper Example

```php
<?php

namespace YOUR_NAMESPACE\Helpers;

class MyHelper {
    
    public static function utility() {
        // Utility function
    }
}
```

## Tips and Best Practices

- 📝 **Naming**: Use PascalCase for classes and camelCase for functions
- 📁 **Organization**: Keep your controllers focused on a single responsibility
- 🔄 **Testing**: Use `php antonella test refresh` regularly during development
- 🐳 **Docker**: Use Docker for a consistent development environment

## Example Workflow

1. Create a controller:
   ```bash
   php antonella controller ProductController
   ```

2. Create a related model:
   ```bash
   php antonella model Product
   ```

3. Start development server:
   ```bash
   php antonella serve
   ```

4. Make changes and refresh:
   ```bash
   php antonella test refresh
   ```

This workflow allows you to develop quickly and see changes in real-time.

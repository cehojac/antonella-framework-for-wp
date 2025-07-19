---
sidebar_position: 1
---

# Installation

## Minimum Requirements

Antonella Framework needs the following elements:

- [**Git**](https://git-scm.com/)
- [**Composer**](https://getcomposer.org/)
- [**PHP 8.0 or higher**](http://php.net/downloads.php)
- **PHP-ZIP** (on Linux systems)
- [**Docker**](https://www.docker.com/products/docker-desktop/) (for Live Testing)

### Verify installation

You can verify that you have the requirements installed:

```bash
# Verify PHP
php --version

# Verify Composer
composer --version

# Verify Git
git --version

# Verify Docker
docker --version
```

## Antonella Framework Installation

You can install Antonella Framework in two ways:

### Method 1: Using Composer (Recommended)

```bash
composer create-project cehojac/antonella-framework-for-wp your-plugin-name
```

### Method 2: Development version

```bash
composer create-project cehojac/antonella-framework-for-wp -s dev your-plugin-name
```

### Navigate to project directory

```bash
cd your-plugin-name
```

## Initial Configuration

During installation, you'll be asked if you want to install the Blade template system:

```bash
You need add blade? (Template system)? Type 'yes' or 'y' to continue:
```

Answer `yes` or `y` if you want to use the Blade template system in your project.

## Project Structure

Once installed, you'll see the following folder structure:

```
your-plugin-name/
├── src/                    # Framework files
│   ├── Controllers/        # Controllers
│   ├── Models/            # Models
│   └── Helpers/           # Custom helpers
├── resources/             # Project resources
│   └── views/             # Blade views
│       └── template/      # Templates
├── wp-test/               # Test WordPress (testing)
├── languages/             # Language files
├── composer.json          # Composer dependencies
├── antonella-framework.php # Main plugin file
└── config.php            # Plugin configuration
```

## Configure Namespace

During installation, Antonella creates a random namespace. You can change it:

```bash
# Change to a specific namespace
php antonella namespace YOUR_NAMESPACE

# Generate a random namespace
php antonella namespace
```

## Verify Installation

You can verify that everything works correctly by running:

```bash
php antonella help
```

This should display the Antonella console help.

## Ready to Start!

Now you have Antonella Framework installed and configured. In the next section, you'll learn how to configure your first plugin.

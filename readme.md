# 🚀 Antonella Framework for WordPress

![Antonella Framework](https://legacy.antonellaframework.com/wp-content/uploads/2018/06/anonella-repositorio.png)

[![DeepWiki](https://img.shields.io/badge/DeepWiki-Antonella_Framework-blue?logo=wikipedia)](https://deepwiki.com/cehojac/antonella-framework-for-wp)

[![Total Downloads](https://poser.pugx.org/cehojac/antonella-framework-for-wp/downloads)](https://packagist.org/packages/cehojac/antonella-framework-for-wp)
[![Latest Version](https://poser.pugx.org/cehojac/antonella-framework-for-wp/v/stable)](https://packagist.org/packages/cehojac/antonella-framework-for-wp)
[![License](https://poser.pugx.org/cehojac/antonella-framework-for-wp/license)](https://packagist.org/packages/cehojac/antonella-framework-for-wp)
[![Gitter](https://badges.gitter.im/Antonella-Framework/community.svg)](https://gitter.im/Antonella-Framework/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

**Framework for developing WordPress plugins based on Model View Controller with enterprise-level security**

📖 **Full Documentation**: [https://antonellaframework.com](https://antonellaframework.com)  
🎥 **Video Tutorial**: [https://tipeos.com/anto](https://tipeos.com/anto)

---

## ✨ What's New in Version 1.9.0

### 🔒 **Enterprise-Level Security**
- **CSRF Protection**: Automatic nonce verification
- **Permission Control**: Granular user capability checks  
- **Input Sanitization**: Automatic data cleaning
- **Output Escaping**: XSS attack prevention
- **Security Class**: Centralized API for all security functions

### 🛠️ **Technical Improvements**
- **PHP 8.2 Compatible**: Full compatibility with latest PHP
- **Enhanced Headers**: Complete plugin metadata
- **Docker Integration**: Improved development environment
- **Auto Root File Change**: Automatic plugin file renaming

---

## 📋 Requirements

### **Core Requirements**
- **PHP**: 8.0 or higher
- **Composer**: Latest version
- **Git**: For version control
- **WordPress**: 5.0 or higher

### **Docker Development Environment**
- **Docker Desktop**: 4.53.0+ (⚠️ **Required for ARM64/Windows compatibility**)
- **Docker Compose**: v2.0+ 
- **Available Ports**: 8080 (WordPress), 3306 (MySQL), 9000 (phpMyAdmin)

> **💡 Note**: For optimal ARM64 compatibility on Windows/Mac, ensure Docker Desktop is updated to version 4.53.0 or higher. Earlier versions may experience container startup issues.

---

## 🚀 Quick Installation

### 1. Create Your Plugin Project
Via Antonella installer

```bash
composer global require cehojac/antonella-installer
antonella new my-awesome-plugin
cd my-awesome-plugin
```

or via composer CLI

```bash
composer create-project --prefer-dist cehojac/antonella-framework-for-wp my-awesome-plugin
cd my-awesome-plugin
```

### 2. Initialize Your Project
```bash
php antonella namespace MyPlugin
php antonella updateproject
```

### 3. Start Development

#### **Option A: Traditional WordPress Development**
Your plugin is now ready! Upload to WordPress and start developing.

#### **Option B: Docker Development Environment**
For a complete development setup with database and admin interface:

```bash
# Start the development environment
php antonella serve
# or manually with Docker Compose
docker compose up -d

# Access your development site
# WordPress: http://localhost:8080
# Admin Panel: http://localhost:8080/wp-admin (test/test)
# phpMyAdmin: http://localhost:9000
```

**🐳 Docker Environment Includes:**
- WordPress with automatic framework activation
- MySQL 8.0 with persistent data
- phpMyAdmin for database management
- WP-CLI for command automation
- Development plugins (Query Monitor, Debug Bar)

**📋 Default Credentials:**
- **WordPress Admin**: `test` / `test`
- **MySQL**: `wordpress` / `wordpress`

> **🔧 Troubleshooting**: If containers fail to start, ensure Docker Desktop is updated to 4.53.0+ and required ports (8080, 3306, 9000) are available.

---

## 🎯 Core Features

### **Console Commands**
| Command | Description |
|---------|-------------|
| `php antonella namespace FOO` | Rename namespace across all files |
| `php antonella make MyController` | Create controller class |
| `php antonella widget MyWidget` | Create widget class |
| `php antonella helper myFunction` | Create helper function |
| `php antonella cpt MyPostType` | Create custom post type |
| `php antonella block MyBlock` | Create Gutenberg block |
| `php antonella makeup` | Generate ZIP for distribution |
| `php antonella serve` | Start development server |

### **Security API**
```php
use CH\Security;

// Verify user permissions
Security::check_user_capability('manage_options');

// Create secure forms
echo Security::create_nonce_field('my_action');
Security::verify_nonce('my_nonce', 'my_action');

// Sanitize input data
$data = Security::sanitize_input($_POST['data'], 'text');

// Escape output data
echo Security::escape_output($data);
```

### **Built-in Capabilities**
- ✅ **MVC Architecture**: Clean separation of concerns
- ✅ **Security First**: Enterprise-level protection
- ✅ **Auto-loading**: PSR-4 compliant
- ✅ **Blade Templates**: Optional template engine
- ✅ **Custom Post Types**: Easy CPT creation
- ✅ **Gutenberg Blocks**: Block development tools
- ✅ **Docker Support**: Containerized development
- ✅ **Testing Framework**: Built-in testing tools

---

## 🛡️ Security Features

### **CSRF Protection**
```php
// In your form
echo Security::create_nonce_field('update_settings');

// In your controller
Security::verify_nonce('settings_nonce', 'update_settings');
```

### **Data Sanitization**
```php
$text = Security::sanitize_input($_POST['text'], 'text');
$email = Security::sanitize_input($_POST['email'], 'email');
$url = Security::sanitize_input($_POST['url'], 'url');
$html = Security::sanitize_input($_POST['content'], 'html');
```

### **Output Escaping**
```php
echo Security::escape_output($user_data, 'html');
echo '<img src="' . Security::escape_output($image_url, 'attr') . '">';
echo '<script>var data = ' . Security::escape_output($js_data, 'js') . ';</script>';
```

---

## 🐳 Development with Docker

### Start Development Environment
```bash
php antonella serve
# or
php antonella serve -d  # detached mode
```

### Features Include:
- WordPress latest version
- PHP 8.2
- MySQL 8.0
- Automatic plugin installation
- Hot reloading

---

## 📦 Plugin Distribution

### Create Production ZIP
```bash
php antonella makeup
```

This command:
- ✅ Excludes development files
- ✅ Includes only production dependencies
- ✅ Creates optimized ZIP file
- ✅ Maintains proper file structure

---

## 🔧 Migration from 1.8.x

### Update Your Controllers
**Before (1.8.x):**
```php
public function process_form() {
    $data = $_POST['data'];
    update_option('my_option', $data);
}
```

**After (1.9.0):**
```php
public function process_form() {
    Security::check_user_capability('manage_options');
    Security::verify_nonce('my_nonce', 'my_action');
    
    $data = Security::sanitize_input($_POST['data'], 'text');
    update_option('my_option', $data);
}
```

---

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📞 Support

- **Documentation**: [antonellaframework.com/documentacion](https://antonellaframework.com/documentacion)
- **Community Chat**: [Gitter](https://gitter.im/Antonella-Framework/community)
- **Issues**: [GitHub Issues](https://github.com/cehojac/antonella-framework-for-wp/issues)
- **Email**: antonella.framework@carlos-herrera.com

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🎉 Made with ❤️ by Carlos Herrera

**Antonella Framework** - Making WordPress plugin development secure, fast, and enjoyable! 



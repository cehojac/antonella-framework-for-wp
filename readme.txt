=== Antonella Framework ===
Contributors: cehojac
Donate link: https://antonellaframework.com/
Tags: framework, mvc, development, plugin-development, wordpress-framework
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 8.0
Stable tag: 1.9.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A powerful WordPress framework for developers based on Model View Controller architecture.

== Description ==

Antonella Framework is a comprehensive WordPress development framework that follows the Model-View-Controller (MVC) architectural pattern. It provides developers with a robust foundation for creating professional WordPress plugins with clean, maintainable code.

**Key Features:**

* **MVC Architecture**: Clean separation of concerns with Model-View-Controller pattern
* **Modern PHP**: Built for PHP 8.0+ with modern programming practices
* **Database Management**: Intelligent table creation and updates using WordPress dbDelta
* **Security First**: Built-in security features including nonce verification, data sanitization, and SQL injection protection
* **Internationalization**: Full i18n support with modern translation loading
* **Gutenberg Ready**: Built-in support for custom Gutenberg blocks
* **REST API**: Easy REST API endpoint creation and management
* **Custom Post Types**: Simplified custom post type and taxonomy registration
* **Admin Interface**: Professional admin panels and dashboard widgets
* **Composer Integration**: Modern dependency management with Composer
* **Developer Tools**: Comprehensive debugging and logging system

**Perfect for:**

* Plugin developers who want a solid foundation
* Teams building complex WordPress applications
* Developers who prefer structured, maintainable code
* Projects requiring scalable architecture

**Documentation:**

Complete documentation is available at [https://antonellaframework.com/documentacion](https://antonellaframework.com/documentacion)

== Installation ==

**Method 1: Using Antonella Installer (Recommended)**

1. Install the Antonella installer globally via Composer:
   `composer global require cehojac/antonella-installer`

2. Create a new project:
   `antonella new my-awesome-plugin`

3. Navigate to your project:
   `cd my-awesome-plugin`

**Method 2: Manual Installation**

1. Create a new directory for your plugin
2. Run: `composer create-project --prefer-dist cehojac/antonella-framework-for-wp:dev-master my-awesome-plugin`
3. Navigate to the project directory
4. Start developing your plugin

**Requirements:**

* PHP 8.0 or higher
* WordPress 5.0 or higher
* Composer for dependency management

== Frequently Asked Questions ==

= What is the MVC pattern? =

MVC (Model-View-Controller) is an architectural pattern that separates application logic into three interconnected components:
- **Model**: Handles data and business logic
- **View**: Manages the presentation layer
- **Controller**: Handles user input and coordinates between Model and View

= Is this framework suitable for beginners? =

While Antonella Framework is designed to be developer-friendly, it's best suited for developers with some PHP and WordPress experience. The framework provides structure and best practices that help developers create professional plugins.

= Can I use this for commercial projects? =

Yes! Antonella Framework is released under the GPL v2 license, making it suitable for both personal and commercial projects.

= Does it work with the latest WordPress version? =

Yes, Antonella Framework is regularly updated to ensure compatibility with the latest WordPress versions. It's currently tested up to WordPress 6.4.

= Where can I get support? =

- Documentation: [https://antonellaframework.com/documentacion](https://antonellaframework.com/documentacion)
- Community: [Gitter Chat](https://gitter.im/Antonella-Framework/community)
- Issues: [GitHub Repository](https://github.com/cehojac/antonella-framework-for-wp)

== Screenshots ==

1. Framework structure showing MVC organization
2. Admin interface example
3. Custom post type configuration
4. REST API endpoint setup
5. Gutenberg block integration

== Changelog ==

= 1.9.0 =
* Complete refactoring of database management system
* Improved security with enhanced data sanitization
* Modern translation system without deprecated functions
* Centralized hooks management
* Professional logging system with debug mode
* Enhanced Gutenberg blocks support
* Better error handling and validation
* Updated for WordPress 6.4 compatibility
* Improved documentation and examples

= 1.8.0 =
* Added REST API endpoint management
* Enhanced security features
* Improved admin interface
* Better Gutenberg integration
* Updated dependencies

= 1.7.0 =
* Custom post types and taxonomies support
* Enhanced MVC structure
* Improved database handling
* Better internationalization support

= 1.6.0 =
* Initial stable release
* Core MVC framework implementation
* Basic admin interface
* Security features implementation

== Upgrade Notice ==

= 1.9.0 =
Major update with improved security, modern database management, and enhanced WordPress compatibility. Recommended for all users.

== Development ==

**Contributing:**

Contributions are welcome! Please visit our [GitHub repository](https://github.com/cehojac/antonella-framework-for-wp) to:
- Report bugs
- Request features
- Submit pull requests
- Review code

**Testing Environment:**

The framework includes a complete Docker development environment with:
- WordPress installation
- Plugin development tools
- Database management
- Debugging utilities

**Architecture:**

```
my-plugin/
├── src/                    # Core framework files
│   ├── Controllers/        # Application controllers
│   ├── Admin/             # WordPress admin functionality
│   ├── helpers/           # Utility functions
│   ├── Config.php         # Central configuration
│   ├── Security.php       # Security utilities
│   └── Api.php           # REST API management
├── resources/             # Views and templates
├── Assets/               # CSS, JS, images
├── languages/            # Translation files
└── antonella-framework.php # Main plugin file
```

== Privacy Policy ==

Antonella Framework does not collect, store, or transmit any personal data. It's a development framework that provides tools for building WordPress plugins. Any data handling depends on how developers implement their plugins using the framework.

== Support ==

For support, documentation, and community discussions:

* **Documentation**: [https://antonellaframework.com/documentacion](https://antonellaframework.com/documentacion)
* **Community Chat**: [https://gitter.im/Antonella-Framework/community](https://gitter.im/Antonella-Framework/community)
* **GitHub Issues**: [https://github.com/cehojac/antonella-framework-for-wp/issues](https://github.com/cehojac/antonella-framework-for-wp/issues)
* **Website**: [https://antonellaframework.com](https://antonellaframework.com)

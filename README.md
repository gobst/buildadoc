![Latest Stable Version](https://img.shields.io/packagist/v/gobst/buildadoc?label=latest%20stable&color=darkgreen)
![License](https://img.shields.io/packagist/l/gobst/buildadoc?label=license&color=darkgreen)
![PHP Version Required](https://img.shields.io/packagist/php-v/gobst/buildadoc?label=PHP%20required&color=darkgreen)
[![Coverage Status](https://coveralls.io/repos/github/gobst/buildadoc/badge.svg?branch=main)](https://coveralls.io/github/gobst/buildadoc?branch=main)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fgobst%2Fbuildadoc%2Fmain)](https://dashboard.stryker-mutator.io/api/reports/github.com/gobst/buildadoc/main)
![Total Downloads](https://img.shields.io/packagist/dt/gobst/buildadoc?label=downloads&color=blue)
![Monthly Downloads](https://img.shields.io/packagist/dm/gobst/buildadoc?label=downloads&color=blue)
![Daily Downloads](https://img.shields.io/packagist/dd/gobst/buildadoc?label=downloads&color=blue)

# BuildADoc

## About

BuildADoc is a PHP documentation generator designed specifically for [DokuWiki](https://github.com/dokuwiki/dokuwiki). 
It parses your PHP classes and converts them into DokuWiki-compatible syntax for seamless integration with your documentation workflow.

### Key Features:
- Automatic generation of class documentation.
- Easy integration with DokuWiki installations.
- **Planned Features:**
    - Markdown format support.
    - `usedByClasses` feature for enhanced class relationship tracking.
    - `trait` support.

## Requirements
- PHP 8.3 or higher
- [Composer](https://getcomposer.org/)
- [Docker](https://www.docker.com/) (optional)

## Installation

You can install BuildADoc using either Composer or Docker, depending on your preference.

### 1. With Composer

To install BuildADoc directly into your project using [Composer](https://getcomposer.org/), run:

```bash
composer require gobst/buildadoc
```

### 2. With Docker

You can also use the [gobst/buildadoc](https://hub.docker.com/r/gobst/buildadoc) Docker image to avoid setting up PHP dependencies directly on your system:

```bash
docker pull gobst/buildadoc
```

## Usage

To generate class documentation for DokuWiki, use the following command:

```bash 
php bin/console.php DokuWiki:create-doc path/to/src/ /path/to/destination/dir/ projectname
```

The generated directories and files must be placed under your DokuWiki installation at /data/pages.
For more options and information run:

```bash 
php bin/console.php DokuWiki:create-doc -help
```

## Support

If you encounter any issues or have questions, feel free to open an issue in the [GitHub Issue Tracker](https://github.com/gobst/buildadoc/issues).

## License

Distributed under the BSD 3-Clause license. See [LICENSE](LICENSE) for more information.

## Acknowledgments

- [ApiGen](https://github.com/ApiGen/ApiGen) for inspiration.
- [DokuWiki](https://github.com/dokuwiki/dokuwiki) for providing an excellent documentation platform.
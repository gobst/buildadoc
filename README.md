[![Latest Stable Version](http://poser.pugx.org/gobst/buildadoc/v)](https://packagist.org/packages/gobst/buildadoc)
[![Latest Unstable Version](http://poser.pugx.org/gobst/buildadoc/v/unstable)](https://packagist.org/packages/gobst/buildadoc)
[![License](http://poser.pugx.org/gobst/buildadoc/license)](https://packagist.org/packages/gobst/buildadoc)
[![PHP Version Require](http://poser.pugx.org/gobst/buildadoc/require/php)](https://packagist.org/packages/gobst/buildadoc)
[![Coverage Status](https://coveralls.io/repos/github/gobst/buildadoc/badge.svg?branch=main)](https://coveralls.io/github/gobst/buildadoc?branch=main)
[![Code Coverage](https://img.shields.io/badge/Code_Coverage-44.69%25-brightgreen)](https://img.shields.io/badge/Code_Coverage-44.69%25-brightgreen)
[![Mutation Score](https://img.shields.io/badge/Mutation_Score-35.87%25-brightgreen)](https://img.shields.io/badge/Mutation_Score-35.87%25-brightgreen)
[![Total Downloads](http://poser.pugx.org/gobst/buildadoc/downloads)](https://packagist.org/packages/gobst/buildadoc)
[![Monthly Downloads](http://poser.pugx.org/gobst/buildadoc/d/monthly)](https://packagist.org/packages/gobst/buildadoc)
[![Daily Downloads](http://poser.pugx.org/gobst/buildadoc/d/daily)](https://packagist.org/packages/gobst/buildadoc)

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
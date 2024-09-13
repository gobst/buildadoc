[![Code Coverage](https://img.shields.io/badge/Code_Coverage-20.31%25-brightgreen)](https://img.shields.io/badge/Code_Coverage-20.31%25-brightgreen)
[![Mutation Score](https://img.shields.io/badge/Mutation_Score-13.20%25-brightgreen)](https://img.shields.io/badge/Mutation_Score-13.20%25-brightgreen)

# BuildADoc

## About
BuildADoc generates a PHP class documentation for [DokuWiki](https://github.com/dokuwiki/dokuwiki). 
The code will be parsed and then converted into the right syntax.
This tool is inspired by [ApiGen](https://github.com/ApiGen/ApiGen).

In addition the following features are planned:

- Markdown format support
- usedByClasses feature

## Installation

### With composer

You can install BuildADoc directly in your project with composer:

``` composer require gobst/buildadoc ```

### With Docker

BuildADoc is available as [gobst/buildadoc](https://hub.docker.com/r/gobst/buildadoc) Docker image which you can directly use.

## Usage

To generate a class documentaion for DokuWiki you can use the following command. 
The generated directories and files have to allocated under your DokuWiki installation in /data/pages.

``` php bin/console.php DokuWiki:create-doc path/to/src/ /path/to/destination/dir/ projectname ```

For more information run:

``` php bin/console.php ```
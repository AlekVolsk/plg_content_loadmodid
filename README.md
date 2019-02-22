# Load modules by ID

![Version](https://img.shields.io/badge/VERSION-1.2.1-0366d6.svg?style=for-the-badge)
![Joomla](https://img.shields.io/badge/joomla-3.7+-1A3867.svg?style=for-the-badge)
![Php](https://img.shields.io/badge/php-5.6+-8892BF.svg?style=for-the-badge)

_description in Russian [here](README.ru.md)_

Within content this plugin loads Modules by this ID.

Beneficially differs from the regular Joomla plugin in that it works directly only with the specified module, and does not process the entire list of published modules in the search for the necessary.

Syntax: `{loadmodid module_id[;style]}`, where style is the template style used to display the module.

You can specify one of the following system styles: `html5`, `xhtml`, `table`, `horz`, `rounded`, `outline`, `none`, as well as styles embedded in the system template you are using.

Please note: your system template can override or ignore the style you specified.

The default style to apply is `none`.

Unpublished modules are not processed.

**IMPORTANT**: Avoid calling the module inside yourself! This will lead to the inoperability of your site!
Notices Module - Installation
=============================

Assets:
-------

The Notices module includes some media files in the `media` folder. These should
be copied into a `media` folder in the web root so that they are accessible. You
will need to make sure the following files are included into your template or
assests system so that notices are styled and functioning properly on the pages
you desire:

- media/css/notices.css
- media/js/notices.js

JavaScript:
-----------

The JavaScript for this module uses
[jQuery](http://docs.jquery.com/Downloading_jQuery), so please load jQuery
before attempting to use the Notices module's JavaScript. The easiest way to do
this is to [load jQuery via Google's
CDN](http://code.google.com/apis/ajaxlibs/documentation/#jquery).

**Note:** The JavaScript for Notices is not required for the module to function,
but does enhance the module with the ability to add and remove notices on the
live page via AJAX.

Icons:
------

Excluding the wizard hat icon, all of the icons in this module are from the
[FatCow: Farm-Fresh Web Icons](http://www.fatcow.com/free-icons) library. Please
refer to their website for Terms of Use. The wizard hat icon was designed by
[Edgar Hassler](http://twitter.com/edgarhassler) and must always be included
with this module in order to preserve his legacy.

PHP Version:
------------

PHP 5.3+ is the preferred PHP version for using of this module. The
`__callStatic()` magic method is used to simplify the API; however, PHP 5.3 is
not required. The `Notices::add()` method backwards compatible and may be the
preferred choice by some developers. Here is the difference:

    // PHP 5.3+ Version
    Notices::success('Congratulations! You did it!');

    // PHP 5.2 Version
    Notices::add('success', 'Congratulations! You did it!');
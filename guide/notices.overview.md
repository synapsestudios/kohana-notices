Notices Module
==============

A Ko3 Module by **Jeremy Lindblom** of **[Synapse Studios](http://synapsestudios.com)**

Requirements
------------

The Notices module includes some media files in the `media` folder. These should
be copied into a `media` folder in the web root so that they are accessible. You
will need to make sure the following files are included into your template or
assests system so that notices are available on the pages you desire:

- media/css/notices.css
- media/js/notices.js

The JavaScript for this module uses
[jQuery](http://docs.jquery.com/Downloading_jQuery), so please load jQuery
before attempting to use the Notices module's JavaScript.

**Note:** The JavaScript for Notices is not required, but does enhance the module
with the ability to add and remove notices on the live page via AJAX.

Introduction
------------

The purpose of this module is to provide an easy way to send messages to the
user on any page, based on events and decisions in the application. Most
commonly, these message are used to inform users of errors. The Notices module
uses the session to pass messages on to the next page.

The following example illustrates a possible use case:

    //...
    if ($comment->check())
    {
        $comment->save();
        Notices::add('success', 'Your comment has been saved.');
        $this->request->redirect('comment/index');
    }
    else
    {
        $errors = $comment->errors();
        Notices::add('error', 'Your comment is invalid.');
    }
    //...

Then in the template (or any other view file) `<?php echo Notices::display() ?>`
is called to display any notices in the queue. The Notices module supports any
type of Notice, but there are 10 types that already have CSS and images defining
their styles:

- denied
- error
- event
- help
- info
- message
- success
- tip
- warning
- wizard

To see Notices in action, please look at the [demo page](../notice/demo).
Notices Module - Adding New Notice Types
========================================

The Notices module has 10 Notice types that are already styled; however, the
module can support any arbitrary Notice types. All Notice types without styles
will default to the style of the *message* type. When you add new Notice types
to your project, you must do the following:

1. Create a 32x32-pixel png image to graphically represent your new Notice type.
This should be placed in the `media/js/notices` folder.
2. Write CSS styles for your Notice type to override the styles of the *message*
type. You can use the following CSS styles but replace `[[type-name]]` with the
name of your new Notice type and replace the colors with the ones you would like
to use.

<pre><code>
div.notice.[[type-name]] strong.notice-type {color: #181818;}
div.notice.[[type-name]] {
	border-color: #181818;
	background-color: #cccccc;
	background-image: -moz-linear-gradient(top, #ffffff, #bbbbbb);
	background-image: -webkit-gradient(linear, left top, left bottom,
		color-stop(0, #ffffff),color-stop(1, #bbbbbb));
	filter: progid:DXImageTransform.Microsoft.Gradient(GradientType=0,
		startColorstr='#ffffff', endColorstr='#bbbbbb');
	-ms-filter: "progid:DXImageTransform.Microsoft.Gradient(GradientType=0,
		startColorstr='#ffffff', endColorstr='#bbbbbb')";
}
</code></pre>

The gradients work for Safari, Chrome, Firefox 3.6+, and IE 6+. If you want to
ensure that the gradients are seen by all, then you should consider using a
tiled background image.
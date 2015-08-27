# HTML5 Doctype #

This is an extension for [Symphony 2](http://getsymphony.com/): A cludge to enforce a basic HTML5 doctype regardless of your XSLT output.


## Usage

Enable the extension to replace XHTML syntax with basic HTML5 syntax. What it actually does is parse any HTML output after XSLT processing to swap out the first four lines of the HTML output. For example, the following XHTML doctype:

```html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
```

is replaced with an HTML5 doctype:

```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
```

Regular expressions are used to parse only the first 4 lines of the output. That way, the script will not parse any code examples contained within the document and the regex processing will be confined to the string fragment that needs to be modified.

### XSL Comments

If XSL comments are added to the beginning of the document, it would be necessary to increase the number of lines of text being processed by the regex. In this case, because the `limit` argument is set to a value of `15`, the `explode` function returns an array of five strings: the first fourteen elements of the array contain each of the first fourteen lines of the HTML output, and the last element contains the rest of the HTML output.

To adjust the number of lines at the beginning of the document which are parsed, modify the value of the `limit` argument for the explode function. For example, to reduce the `limit` to `5` to parse only the first four lines:

```php
$html_array = explode("\n", $html, 5);
```

### XML Namespace

To preserve the XML namespace declaration on the HTML element, comment out this line:

```php
// $html_doctype = preg_replace('/(<html ).*(lang="[a-z]+").*>/i', '\1\2>', $html_doctype);
```

### Conditional Classes

Because the extension now parses more lines at the beginning of the document, it is now possible to integrate code such as [Paul Irish's conditional classes](http://paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/). Add the following code:

```xslt
<xsl:comment> paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ </xsl:comment>
<xsl:comment><![CDATA[[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]]]></xsl:comment>
<xsl:comment><![CDATA[[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]]]></xsl:comment>
<xsl:comment><![CDATA[[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]]]></xsl:comment>
<xsl:comment><![CDATA[[if gt IE 8]><!]]></xsl:comment><html lang="en"><xsl:comment><![CDATA[<![endif]]]></xsl:comment>
```

### Possible Future Feature: Preferences Page

I'm not sure if it's worth creating a preferences page to manage options like this. I think it would be best to keep the code for this extension as sparse as possible.

However, because the extension hijacks the output of every page, it might be good to have a multiple select box to configure which pages to apply this hack to. For example, if you have a page that is meant to output only XML, it would be a waste for this script to run on that page.


## Installation & Updating

Information about [installing and updating extensions](http://getsymphony.com/learn/tasks/view/install-an-extension/) can be found in the Symphony documentation at <http://getsymphony.com/learn/>.


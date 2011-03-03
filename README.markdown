# HTML5 Doctype #

This is an extension for [Symphony 2](http://symphony-cms.com/): A cludge to enforce a basic HTML5 doctype regardless of your XSLT output.

- Version: 1.2.4
- Date: 3rd March 2011
- Requirements: Symphony 2
- Author: Nick Dunn
- Constributors: Stephen Bau, Michael Eichelsdoerfer
- GitHub Repository: <https://github.com/domain7/html5_doctype>


## Usage

Enable the extension to replace XHTML syntax with basic HTML5 syntax. What it actually does is parse any HTML output after XSLT processing to swap out the first four lines of the HTML output. For example, the following XHTML doctype:

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	  <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

is replaced with an HTML5 doctype:

	<!DOCTYPE html>
	<html lang="en">
	  <head>
	    <meta charset="UTF-8" />

Regular expressions are used to parse only the first 4 lines of the output. That way, the script will not parse any code examples contained within the document and the regex processing will be confined to the string fragment that needs to be modified.

### XSL Comments

If XSL comments are added to the beginning of the document, it would be necessary to increase the number of lines of text being processed by the regex. In this case, because the `limit` argument is set to a value of `5`, the `explode` function returns an array of five strings: the first four elements of the array contain each of the first four lines of the HTML output, and the last element contains the rest of the HTML output.

If you wanted to add a couple lines of comments at the beginning of the document, you could accommodate this by increasing the value of the `limit` argument for the explode function. For example, increase the `limit` to `10`.

     $html_array = explode("n", $html, 10);

### XML Namespace

To preserve the XML namespace declaration on the HTML element, comment out this line:

     // $html_doctype = preg_replace('/(<html ).*(lang="[a-z]+").*>/i', '\1\2>', $html_doctype);


### Possible Future Feature: Preferences Page

I'm not sure if it's worth creating a preferences page to manage options like this. I think it would be best to keep the code for this extension as sparse as possible.

However, because the extension hijacks the output of every page, it might be good to have a multiple select box to configure which pages to apply this hack to. For example, if you have a page that is meant to output only XML, it would be a waste for this script to run on that page.


## Installation & Updating

Information about [installing and updating extensions](http://symphony-cms.com/learn/tasks/view/install-an-extension/) can be found in the Symphony documentation at <http://symphony-cms.com/learn/>.


## Change Log

**Version 1.2.4**

- Simplify regex to only remove XHTML xmlns attribute and xml:lang namespace. This will preserve any other attributes, including classes on the html element.

**Version 1.2.3**

- Improved extension description: "Replace XHTML syntax with basic HTML5 syntax." (Michael Eichelsdoerfer)
- Fixed problems with Symphony error pages coming up blank. Prohibit any parsing attempts for document types other than HTML. (Michael Eichelsdoerfer)

**Version 1.2.2**

- Limit processing of regex to the first 5 lines of the result document. (Stephen Bau)

**Version 1.2.1**

- Fix regex for meta charset: escape forward slashes to use case insensitive match (Stephen Bau)

**Version 1.2**

- Preserve the language and meta charset attributes from the original HTML document (Stephen Bau)

**Version 1.1**

- Modify Nick Dunn's HTML5 Doctype hack to include regex to fix the html and meta charset elements http://symphony-cms.com/discuss/thread/43003/3/#position-56 (Stephen Bau)

**Version 1.0**

- Inital Release (Nick Dunn)
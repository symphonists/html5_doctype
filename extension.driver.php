<?php

	Class extension_html5_doctype extends Extension{

		public function about(){
			return array(
				'name' => 'HTML5 doctype',
				'description' => 'Replace XHTML doctype with basic HTML5 doctype',
				'version' => '1.2.2',
				'release-date' => '2010-11-11',
				'author' => array(
					'name' => 'Nick Dunn'
				)
			);
		}

		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/frontend/',
					'delegate' => 'FrontendOutputPostGenerate',
					'callback' => 'parse_html'
				),
			);
		}
		
		public function parse_html($context) {
			$html = $context['output'];
			
			// Split the HTML output into two variables:
			// $html_doctype contains the first four lines of the HTML document
			// $html_doc contains the rest of the HTML document
			$html_array = explode("\n", $html, 5);
			$html_doc = array_pop($html_array);
			$html_doctype = implode("\n", $html_array);
			
			// Parse the doctype to convert XHTML syntax to HTML5
			$html_doctype = preg_replace("/<!DOCTYPE [^>]+>/", "<!DOCTYPE html>", $html_doctype);
			$html_doctype = preg_replace('/(<html ).*(lang=\"[a-z]+\").*>/i', '\\1\\2>', $html_doctype);
			$html_doctype = preg_replace('/<meta http-equiv=\"Content-Type\" content=\"text\/html; charset=(.*[a-z0-9-])\" \/>/i', '<meta charset="\\1" />', $html_doctype);

			// Concatenate the fragments into a complete HTML5 document
			$html = $html_doctype . "\n" . $html_doc;

			$context['output'] = $html;
		}

	}
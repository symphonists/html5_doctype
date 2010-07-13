<?php

	Class extension_html5_doctype extends Extension{

		public function about(){
			return array(
				'name' => 'HTML5 doctype',
				'description' => 'Replace any generated HTML doctype with basic HTML5 doctype',
				'version' => '1.0',
				'release-date' => '2010-07-13',
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
			$html = preg_replace("/<!DOCTYPE [^>]+>/", "<!DOCTYPE html>", $html);
			$context['output'] = $html;
		}

	}
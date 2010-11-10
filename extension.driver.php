<?php

	Class extension_html5_doctype extends Extension{

		public function about(){
			return array(
				'name' => 'HTML5 doctype',
				'description' => 'Replace XHTML doctype with basic HTML5 doctype',
				'version' => '1.2',
				'release-date' => '2010-11-10',
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
			$html = preg_replace('/(<html ).*(lang=\"[a-z]+\").*>/i', '\\1\\2>', $html);
			$html = preg_replace('<meta http-equiv=\"Content-Type\" content=\"text/html; charset=(.*[a-z0-9-])\" />', 'meta charset="\\1" /', $html);
			$context['output'] = $html;
		}

	}
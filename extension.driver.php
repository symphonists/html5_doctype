<?php

	Class extension_html5_doctype extends Extension{

		public function about(){
			return array(
				'name' => 'HTML5 doctype',
				'description' => 'Replace XHTML doctype with basic HTML5 doctype',
				'version' => '1.1',
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
			$html = preg_replace("/<html [^>]+>/", "<html lang=\"en\">", $html);
			$html = preg_replace("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />", "meta charset=\"utf-8\" /", $html);
			$context['output'] = $html;
		}

	}
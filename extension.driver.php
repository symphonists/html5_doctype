<?php

    class extension_html5_doctype extends Extension{

        private $_trigger;
        private static  $_name = 'HTML5 Doctype';

		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/frontend/',
					'delegate' => 'FrontendOutputPostGenerate',
					'callback' => 'parse_html'
				),

				array(
					'page' => '/frontend/',
                    'delegate' => 'FrontendPageResolved',
                    'callback' => 'setRenderTrigger'
				),
				array(
					'page' => '/system/preferences/',
                    'delegate' => 'AddCustomPreferenceFieldsets',
                    'callback' => 'appendPreferences'
				),
				array(
					'page' => '/system/preferences/',
					'delegate' => 'Save',
					'callback' => '__SavePreferences'
				)
			);
        }

        /**
         * append the preferences field
         * @return void
         */
        public function appendPreferences($context, $errors = null) {
            $fieldset = new XMLElement('fieldset', null, array('class' => 'settings'));
            $legend = new XMLElement('legend', __(self::$_name));
            $div = new XMLElement('div');

            $label = Widget::Label(__('Exclude Types'),
                Widget::Input(
                    'settings[html5_doctype][exclude_pagetypes]',
                    Symphony::Configuration()->get('exclude_pagetypes', 'html5_doctype')
                ));

            $tags = new XMLElement('ul', null, array('class' => 'tags'));

			$types = PageManager::fetchAvailablePageTypes();

			foreach($types as $type) {
				$tags->appendChild(new XMLElement('li', $type));
			}

            $div->appendChild($label);
            $div->appendChild($tags);

            $fieldset->appendChild($legend);
            $fieldset->appendChild($div);

            $context['wrapper']->appendChild($fieldset);

			// checkbox to use XHTML5 namespace attributes
			$label = Widget::Label();
			$input = Widget::Input('settings[html5_doctype][use_xhtml5]', 'yes', 'checkbox');
			if(Symphony::Configuration()->get('use_xhtml5', 'html5_doctype') == 'yes') $input->setAttribute('checked', 'checked');
			$label->setValue($input->generate() . ' ' . __('Use XHTML5 (include namespace attributes on <code>html</code> element)'));
            $fieldset->appendChild($label);

        }

        public function setRenderTrigger($context)
        {
            $this->_trigger = true;
            $conf = preg_split(
                '~,~',
                preg_replace('/\s+/', '',
                Symphony::Configuration()->get('exclude_pagetypes', 'html5_doctype')),
                -1,
                PREG_SPLIT_NO_EMPTY
            );

            $types = $context['page_data']['type'];

            if (!empty($types) && !empty($conf)) {
                foreach($conf as $type) {
                    if (in_array($type, $types)) {
                        $this->_trigger = false;
                        break;
                    }
                }
            }
        }

        public function parse_html($context) {
            if (!$this->_trigger) return;
			// Parse only if $context['output'] exists and it's an HTML document
			if(substr($context['output'], 0, 14) == '<!DOCTYPE html') {
				$html = $context['output'];

				// Split the HTML output into two variables:
				// $html_doctype contains the first fifteen lines of the HTML document
				// $html_doc contains the rest of the HTML document
				$html_array = explode("\n", $html, 15);
				$html_doc = array_pop($html_array);
				$html_doctype = implode("\n", $html_array);

				// Parse the doctype to convert XHTML syntax to HTML5
				$html_doctype = preg_replace("/<!DOCTYPE [^>]+>/", "<!DOCTYPE html>", $html_doctype);

				// Use XHTML5 by default, but if preference is set, include namespace attributes on html element
				if(Symphony::Configuration()->get('use_xhtml5', 'html5_doctype') == 'yes') {
					// leave html attributes as is
				} else {
					$html_doctype = preg_replace('/ xmlns=\"http:\/\/www.w3.org\/1999\/xhtml\"| xml:lang="[^\"]*\"/', '', $html_doctype);
				}

				$html_doctype = preg_replace('/<meta http-equiv=\"Content-Type\" content=\"text\/html; charset=(.*[a-z0-9-])\"( \/)?>/i', '<meta charset="\1"\2>', $html_doctype);

				// Concatenate the fragments into a complete HTML5 document
				$html = $html_doctype . "\n" . $html_doc;

				$context['output'] = $html;
			}
		}

		/**
		 * Save preferences
		 *
		 * @param array $context
		 *  delegate context
		 */
		public function __SavePreferences($context) {

			if(!isset($context['settings']['html5_doctype']['use_xhtml5'])){
				$context['settings']['html5_doctype']['use_xhtml5'] = 'no';
			}
		}
	}

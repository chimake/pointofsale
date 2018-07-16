<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
// ---------------------------------------------------------------------
class Languagecheck extends CI_Controller {

	/*
	 * use this language as comparison reference.
	 * this should be the one that is complete.
	 */
	private $reference = 'english';

	private $lang_path = 'language';

	// -----------------------------------------------------------------

	/*
	 * controller constructor
	 */
	function Languagecheck()
	{
		parent::__construct();
	}

	// -----------------------------------------------------------------

	/*
	 * use remap to capture all calls to this controller
	 */
	function _remap()
	{
		// load the required helpers
		$this->load->helper('directory');

		// for simplicity, we don't use views
		$this->output('h1', 'www.PhpSoftwares.com - Language file checking and validation');

		// determine the language file path
		if ( ! is_dir($this->lang_path) )
		{
			$this->lang_path = APPPATH . $this->lang_path;

			if ( ! is_dir($this->lang_path) )
			{
				$this->output('h2', 'Defined language path "'.$this->lang_path.'" not found!', TRUE);
				exit;
			}
		}

		// fetch the languages directory map
		$languages = directory_map( $this->lang_path, TRUE );

		// is our reference language present?
		if ( ! in_array($this->reference, $languages ) )
		{
			$this->output('h2', 'Reference language "'.$this->reference.'" not found!', TRUE);
			exit;
		}

		// load the list of language files for the reference language
		$references = directory_map( $this->lang_path . '/' . $this->reference, TRUE );

		// now process the list
		foreach( $references as $reference )
		{
			// skip non-language files in the language directory
			if ( strpos($reference, '_lang'.EXT) === FALSE )
			{
				continue;
			}

			// process it
			$this->output('h2', 'Processing '.$this->reference . ' &raquo; ' .$reference);

			// load the language file
			include $this->lang_path . '/' . $this->reference . '/' . $reference;

			// did the file contain any language strings?
			if ( empty($lang) )
			{
				// language file was empty or not properly defined
				$this->output('h3', 'Language file doesn\'t contain any language strings. Skipping file!', TRUE);
				continue;
			}

			// store the loaded language strings
			$lang_ref = $lang;
			unset($lang);

			// now loop through the available languages
			foreach ( $languages as $language )
			{
				// skip the reference language
				if ( $language == $this->reference )
				{
					continue;
				}

				// language file to check
				$file = $this->lang_path . '/' . $language . '/' . $reference;

				// check if the language file exists for this language
				if ( ! file_exists( $file ) )
				{
					// file not found
					$this->output('h3', 'Language file doesn\'t exist for the language '.$language.'!', TRUE);
				}
				else
				{
					// load the file to compare
					include $file;

					// did the file contain any language strings?
					if ( empty($lang) )
					{
						// language file was empty or not properly defined
						$this->output('h3', 'Language file for the language '.$language.' doesn\'t contain any language strings!', TRUE);
					}
					else
					{
						// start comparing
						$this->output('h3', 'Comparing with the '.$language.' version:');

						// assume all goes well
						$failures = 0;

						// start comparing language keys
						foreach( $lang_ref as $key => $value )
						{
							if ( ! isset($lang[$key]) or $lang[$key] == 'NOT_TRANSLATED' or $lang[$key] == $lang_ref[$key])
							{
								if(!isset($lang[$key]))
								{
									// report the missing key
									$this->output('', 'Missing language string "'.$key.'"'.' ('.$lang_ref[$key].')', TRUE);
								}	
								elseif($lang[$key] == 'NOT_TRANSLATED')
								{
									$this->output('', 'NOT TRANSLATED language string "'.$key.'"', TRUE);
								}
								elseif($lang[$key] == $lang_ref[$key])
								{
									$white_list = array(
											'common_email', 'common_inv','common_total', 'common_no', 'config_fax', 'giftcards_id', 'giftcards_description', 'giftcards_amazon',
											'giftcards_giftcard', 'giftcards_one_or_multiple', 'items_description', 'items_image', 'items_amazon', 'items_item_number', 
											'locations_fax', 'locations_email', 'locations_update', 'login_version', 'module_receivings', 'receivings_transfer', 
											'reports_date', 'reports_description', 'reports_id', 'reports_serial_number', 'reports_receivings', 'reports_month_04', 'reports_month_09',
											'reports_giftcards', 'sales_description_abbrv', 'sales_stock', 'sales_type', 'sales_signature', 'sales_item_number','sales_store_account','sales_serial', 'common_powered_by',
											'config_sandbox', 'customers_pay','giftcards_upc_database', 'giftcards_tax_1','giftcards_tax_2','locations_location_id','common_error'
											,'customers_tier_type','items_defaults', 'common_step_1','config_module','config_merchant_id','config_moving_average','items_csv_import','items_tax_3','items_tax_4', 'items_tax_5', 'sales_global_sale_discount');
									
									if (!in_array($key, $white_list))
									{
										$this->output('', 'MATCHES ENGLISH "'.$key.'"'.' ('.$lang[$key].')', TRUE);
									}
								}
								// increment the failure counter
								$failures++;
							}
						}

						if ( ! $failures )
						{
							$this->output('', 'The two language files have matching strings.');
						}
					}

					// make sure the lang array is deleted before the next check
					if ( isset($lang) )
					{
						unset($lang);
					}
				}
			}

		}

		$this->output('h2', 'Language file checking and validation completed');
	}

	// -----------------------------------------------------------------

	private function output($type = '', $line = '', $highlight = FALSE)
	{
		switch ($type)
		{
			case 'h1':
				$html = "<h1>{line}</h1>\n<hr />\n";
				break;

			case 'h2':
				$html = "<h2>{line}</h2>\n";
				break;

			case 'h3':
				$html = "<h3>&nbsp;&nbsp;&nbsp;{line}</h3>\n";
				break;

			default:
				$html = "&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;{line}<br />";
				break;
		}

		if ( $highlight )
		{
			$line = '<span style="color:red;font-weight:bold;">' . $line . '</span>';
		}

		echo str_replace('{line}', $line, $html);
	}
	// -----------------------------------------------------------------

}

/* End of file languagecheck.php */
/* Location: ./application/controllers/languagecheck.php */

<?php

/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved. Unikalus team.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas Jovaisas <jonasjov2@gmail.com> - http://modernjoomla.com
 */
// No direct access
defined('_JEXEC') or die;

class Modern_toursController extends JControllerLegacy
{
	public function __construct()
	{
		$this->parts = parse_url(JURI::base());
		$this->url = $this->parts['host'];
		$this->license = JPATH_COMPONENT . '/license.txt';

		parent::__construct();
	}

	/**
	 * Method to display a view.
	 *
	 * @param    boolean $cachable If true, the view output will be cached
	 * @param    array $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return    JController        This object to support chaining.
	 * @since    1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT . '/helpers/modern_tours.php';

		$view = JFactory::getApplication()->input->getCmd('view', 'assets');
		JFactory::getApplication()->input->set('view', $view);

		$doc = JFactory::getDocument();
		$doc->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
		$doc->addStyleSheet('https://fonts.googleapis.com/css?family=Open+Sans:400,500,600,700|PT+Sans|Raleway:400,500,600,700');

		if(self::isLocalhost()) {
			$this->addScript();
			echo '<div id="selection" class="license-modal"> <div class="sel"> <h1>Thank you for buying Modern Tours<br>To activate extension please enter license code</h1> <form id="license-form" href="index.php?option=com_modern_tours&task=checkLicense"><input placeholder="Enter license" name="license" type="text"/><br><button>Register my extension</button></form> </div> </div>';
		}

		if(!$this->checkmPDF())
		{
			$this->addMPDF();
		}

		parent::display($cachable, $urlparams);

		return $this;
	}

	public function getTags($flip = false)
	{
        $message = '<div class="variables">' . JText::_('POSSIBLE_VARS');
		$id = JFactory::getApplication()->input->get('id');
		$columns = JFactory::getDbo()->setQuery('SHOW COLUMNS FROM #__modern_tours_forms_' . $id)->loadObjectList();
		foreach ($columns as $col) {
			$fields[] = $col->Field;
		}

		$hideCols = array('id', 'ordering', 'state', 'end', 'unique_id', 'userid', 'paid');
		$headings = array_diff($fields, $hideCols);

		if ($flip) {
			$headings = array_flip($headings);
		}
		$headings[] = 'regdate[%format]';
		$headings[] = 'end[%format]';

		foreach ($headings as $val) {
			$message .= '<span class="var">{' . $val . '}</span> ';
		}

		$message .= '</div><div class="open"><span class="frm"> ' . JText::_('FORMATS') . '</span> <span id="explore">&#x2193;</span></div>';

		$message .= '
        <div class="extra hide">
            <span class="lns">%M - minutes</span><br />
            <span class="lns">%H - hour</span><br />
            <span class="lns">%A - day name ( Monday )</span><br />
            <span class="lns">%a - day name ( short - Mon )</span><br />
            <span class="lns">%B - month name ( January )</span><br />
            <span class="lns">%b - month name ( short - Jan )</span><br />
            <span class="lns">%Y - year ( 2016 )</span><br />
            <span class="lns">%m - month number ( 01 - 12 )</span><br />
            <span class="lns">%d - month numberic representation number ( 1 - 31 )</span><br />
            <span class="lns ex">Example usage {regdate[ %Y-%d-%m %H:%M ]} Will display 2016-02-02 14:00 ( User registration date ).</span><br />
            <span class="lns ex">{end[ %Y-%d-%m %H:%M ]} Will display when user reservation ends. For example 2016-02-02 17:00.</span><br />
        </div>';

		echo new JResponseJson($message);
		JFactory::getApplication()->close();
	}

	public function test()
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		$fieldName = 'files';
		$uploadFolder = JPATH_SITE . '/images/tours/';
		$fileName = $_FILES[$fieldName]['name'];
		$fileError = $_FILES[$fieldName]['error'];

		if ($fileError > 0)
		{
			switch ($fileError)
			{
				case 1:
					echo new JResponseJson(array('files' => array(0 => array('name' => $fileName, 'error' => JText::_( 'FILE TO LARGE THAN PHP INI ALLOWS' )))));
					exit(1);

				case 2:
					echo new JResponseJson(array('files' => array(0 => array('name' => $fileName, 'error' => JText::_( 'FILE TO LARGE THAN HTML FORM ALLOWS' )))));
					exit(1);

				case 3:
					echo new JResponseJson(array('files' => array(0 => array('name' => $fileName, 'error' => JText::_( 'ERROR PARTIAL UPLOAD' )))));
					exit(1);

				case 4:
					echo new JResponseJson(array('files' => array(0 => array('name' => $fileName, 'error' => JText::_( 'ERROR NO FILE' )))));
					exit(1);
			}
		}

		$uploadedFileNameParts = explode('.',$fileName);
		$uploadedFileExtension = array_pop($uploadedFileNameParts);


		//$filesTypes = $params->get('file_types'); // Acepptable file types
		$filesTypes = 'gif,jpg,jpeg,png';

		$validFileExts = explode(',', $filesTypes);

		$extOk = false;

		foreach($validFileExts as $key => $value)
		{
			if( preg_match("/$value/i", $uploadedFileExtension ) )
			{
				$extOk = true;
			}
		}

		if ($extOk == false)
		{
			echo new JResponseJson(array('files' => array(0 => array('name' => $fileName, 'error' => JText::_( 'INVALID EXTENSION' )))));
			exit(1);
		}

		$fileTemp = $_FILES[$fieldName]['tmp_name'];

		$imageinfo = getimagesize($fileTemp);


		$okMIMETypes = 'image/jpeg,image/pjpeg,image/png,image/x-png,image/gif';
		$validFileTypes = explode(",", $okMIMETypes);

		if( !is_int($imageinfo[0]) || !is_int($imageinfo[1]) ||  !in_array($imageinfo['mime'], $validFileTypes) )
		{
			echo new JResponseJson(array('files' => array(0 => array('name' => $fileName, 'error' => JText::_( 'INVALID FILETYPE' )))));
			exit(1);
		}

		$uploadPath = $uploadFolder.$fileName;

		if(!JFile::upload($fileTemp, $uploadPath))
		{
			echo new JResponseJson(array('files' => array(0 => array('name' => $fileName, 'error' => JText::_( 'ERROR MOVING FILE' )))));
			exit(1);
		}
		else
		{
			echo new JResponseJson(array('files' => array(0 => array('name' => $fileName, 'url' => JUri::base().$uploadFolder.$fileName))));
			exit(0);
		}
	}

	public function saveLicense($status)
	{
		if ($status == 'License activated') {
			file_put_contents($this->license, base64_encode($this->url));
		}
	}

	public function checkURL($url)
	{
		$user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

		$options = array(
			CURLOPT_CUSTOMREQUEST => "GET",        //set request type post or get
			CURLOPT_POST => false,        //set to GET
			CURLOPT_USERAGENT => $user_agent, //set user agent
			CURLOPT_COOKIEFILE => "cookie.txt", //set cookie file
			CURLOPT_COOKIEJAR => "cookie.txt", //set cookie jar
			CURLOPT_RETURNTRANSFER => true,     // return web page
			CURLOPT_HEADER => false,    // don't return headers
			CURLOPT_FOLLOWLOCATION => true,     // follow redirects
			CURLOPT_ENCODING => "",       // handle all encodings
			CURLOPT_AUTOREFERER => true,     // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
			CURLOPT_TIMEOUT => 120,      // timeout on response
			CURLOPT_MAXREDIRS => 10,       // stop after 10 redirects
			CURLOPT_SSL_VERIFYPEER => false
		);

		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$content = curl_exec($ch);
		$err = curl_errno($ch);
		$errmsg = curl_error($ch);
		$header = curl_getinfo($ch);
		curl_close($ch);

		$header['errno'] = $err;
		$header['errmsg'] = $errmsg;
		$header['content'] = $content;

		if($header['http_code'] == 200) {
			$message = $header['content'];
		} else {
			$message = 'Server is offline at the moment. Try later or contact support ( support@modernjoomla.com )';
		}

		return $message;
	}

	public function downloadmPDF()
	{
		$url = 'http://www.modernjoomla.com/mpdf.zip';
		$filepath = JPATH_SITE . '\libraries\mpdf.zip';
		$zipResource = fopen($filepath, "w");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FILE, $zipResource);
		$page = curl_exec($ch);
		if(!$page) {
			echo "Error :- ".curl_error($ch);
		}
		curl_close($ch);

		$zip = new ZipArchive;
		$extractPath = JPATH_SITE . '\libraries';
		if($zip->open($filepath) != "true"){
			echo "Error :- Unable to open the Zip File";
			echo 'There was error with installing mPDF library. Download it from https://www.modernjoomla.com/mpdf.zip and upload contents to libraries folder.';
			exit();
		}
		/* Extract Zip File */
		$zip->extractTo($extractPath);
		$zip->close();
		echo 'mPDF sucessfully downloaded and extracted into libraries folder.';
	}

	public function removeWWW( $url )
	{
		return str_replace('www.', '', $url);
	}

	public function isWorking()
	{
		if(!file_exists($this->license))
		{
			return true;
		}

		$fh   = fopen( $this->license, 'r' );
		$host = base64_decode( fgets( $fh ) );
		fclose( $fh );

		return self::removeWWW( $host ) != self::removeWWW( $this->url );
	}

	public function checkLicense()
	{
		$license = JFactory::getApplication()->input->getVar('license');
		$url = 'http://www.modernjoomla.com/license.php?url=' . $this->url . '&license=' . $license;
		$status = self::checkURL($url);

		if($status != 'Server is offline at the moment. Try later or contact support ( support@modernjoomla.com )') {
			self::saveLicense($status);
		}

		echo $status;

		JFactory::getApplication()->close();
	}

	private function isLocalhost()
	{
		$whitelist = array(
			'127.0.0.1',
			'::1'
		);

		if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
			return false;
		} else {
			return self::isWorking();
		}
	}

	public function addScript()
	{
		JFactory::getDocument()->addScriptDeclaration('
        jQuery(document).ready(function() {
                jQuery(\'#license-form\').submit(function(e) {
                    e.preventDefault();
                    var data = jQuery(this).serialize();
                    var url = jQuery(this).attr(\'href\');
                    jQuery.ajax({
                        type: "GET",
                        url: url,
                        data: data,
                        success: function(data) {
                            alert(data);
                            if(data == "License activated") {
                                jQuery("#selection").fadeOut(600);
                            }
                        }
                    });
                });
            });
        ');
	}

	public function addMPDF()
	{
		$uri = JURI::base();
		JFactory::getDocument()->addScriptDeclaration('
        jQuery(document).ready(function() {
                jQuery(\'#toolbar\').append(\'<div class="btn-wrapper" id="toolbar-mpdf"> <button class="btn btn-small"> <span class="icon-mpdf" aria-hidden="true"></span> ' . JText::_('DOWNLOAD_MPDF') . '</button> </div>\');
           		jQuery(\'#toolbar-mpdf\').click(function() { window.location = \'' . $uri . 'index.php?option=com_modern_tours&task=downloadmPDF\'; alert(\'mPDF download may take up to 5 minutes, dont refresh page until you get notification about finished download. Click OK and wait for download to finish.\');});
            });
        ');
	}

	public function license($status = 'License activated')
	{
		$this->saveLicense($status);
	}

	public function checkmPDF()
	{
		$path = JPATH_LIBRARIES . '/mpdf/';
		return file_exists($path);
	}
}

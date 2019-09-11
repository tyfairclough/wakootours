<?php
/**
 * @version    CVS: 1.0.0
 * @package    com_modern_tours
 * @author     modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class GoogleCalendar
{
    public static function getToken()
    {
        require_once JPATH_LIBRARIES . '/google/vendor/autoload.php';

	    $code = JFactory::getApplication()->input->getVar('code');
	    $delete = JFactory::getApplication()->input->getVar('delete');
        $access_token = JFactory::getDbo()->setQuery('SELECT token FROM #__modern_tours_google_calendars')->loadResult();

        if($delete) {
        	$query = 'TRUNCATE TABLE #__modern_tours_google_calendars';
        	$truncate = JFactory::getDbo()->setQuery($query)->query();
        	echo $truncate;
        }

        $guzzleClient = new \GuzzleHttp\Client(array(
            'verify' => false,
            'curl' => array(
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
            ),
        ));
        $client = new Google_Client();
        $client->setHttpClient($guzzleClient);
        $client->setAuthConfig(JPATH_LIBRARIES . '/google/client_secret.json');
        $client->addScope("https://www.googleapis.com/auth/calendar");
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        if ($access_token) {
            $client->setAccessToken($access_token);
        }

        if (!$code && !$access_token) {
            $auth_url = $client->createAuthUrl();
            $url = filter_var($auth_url, FILTER_SANITIZE_URL);

            header('Location: ' . $url);
        }

        if ($code && !$access_token) {
            $client->fetchAccessTokenWithAuthCode($code);
            $access_token = $client->getAccessToken();
            $obj = new stdClass();
            $obj->token = json_encode($access_token);
            JFactory::getDbo()->insertObject('#__modern_tours_google_calendars', $obj);
        }

        if (array_key_exists('refresh_token', $access_token)) {
        	echo 'Google client approved';
	        JFactory::getApplication()->close();
            if ($client->isAccessTokenExpired()) {
                $client->refreshToken($access_token['refresh_token']);
            }
        }

        return $client;
    }

    public static function createEvent($start, $end, $text = '')
    {
        self::getToken();
        $config = JFactory::getConfig();
        $offset = $config->get('offset');

        date_default_timezone_set($offset);

        $startEvent = date('c', strtotime($start));

	    $endEvent = date('c', strtotime($end));

        $cal = new Google_Service_Calendar(self::getToken());
        $event = new Google_Service_Calendar_Event();

        $event->setSummary($text);
        $start = new Google_Service_Calendar_EventDateTime();
        $start->setDateTime($startEvent);
        $event->setStart($start);

        $end = new Google_Service_Calendar_EventDateTime();
        $end->setDateTime($endEvent);
        $event->setEnd($end);

	    $agent = MTHelper::getAgents();
	    $calendar_id = $agent->calendar_id;

	    if($calendar_id)
	    {
		    $cal->events->insert($calendar_id, $event);
	    }
    }


}
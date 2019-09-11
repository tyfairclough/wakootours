<?php
/**
 * @version    CVS: 1.0.0
 * @package    com_modern_tours
 * @author     modernjoomla.com <support@modernjoomla.com>
 * @copyright  modernjoomla.com
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class Maintenance
{

    public static function runTest()
    {
        $params = MTHelper::getParams();

        $sendUserEmail = $params->send_email;
        $userMessage = $params->{'user_' . $params->cash_paid . '_message'};

        $sendAdminEmail = $params->send_emails;
        $adminMessage = $params->{'admin_' . $params->paid . '_message'};

        self::check('admin', $sendAdminEmail, $adminMessage);
        self::check('user', $sendUserEmail, $userMessage);

        JFactory::getApplication()->close();
    }

    public static function displayTest()
    {
        $user = JFactory::getUser();

        if($user->authorise('core.admin')) {
            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            if(strpos($actual_link,'&')) {
                $link = $actual_link . '&task=runTest';
            } else {
                $link = $actual_link . '?task=runTest';
            }
//            echo '<a style="color: white; position: fixed; top: 10px; left: 10px; background: #39b949; text-align: center; line-height: 30px; font-size: 25px; padding: 5px 29px; border-radius: 2px;" href="' . $link . '" id="check">Check system settings</a>';
        }
    }

    public static function checkMsg($msg)
    {
        $msg = $msg ? 1 : 0;

        $message = array(
            1 => '<div class="label green">Email message filled</div>',
            0 => '<div class="label red">Email message is empty</div>'
        );

        return $message[$msg];
    }

    public static function check($area, $status, $message)
    {
        echo '<style>.block-area { margin: 15px; background: #f1f1f1; width: 250px; padding: 15px; color: black; } .label { color: white; margin: 5px 0; border-radius: 2px; padding: 3px; } .green {  background: green; } .red { background: red; } </style>';

        $data = array(
            'user' => array(
                'send_email' => array(
                    0 => '<div class="label red">Send email is turned off</div>',
                    1 => '<div class="label green">Send email is on</div>',
                ),
                'message' => self::checkMsg($message)
            ),
            'admin' => array(
                'send_email' => array(
                    0 => '<div class="label red">Send email is turned off</div>',
                    1 => '<div class="label green">Send email is turned on</div>',
                    2 => '<div class="label green">Send email is turned on</div>'
                ),
                'message' => self::checkMsg($message)
            )
        );

        $msg = '<div class="block-area"><div class="area">' . ucfirst($area) . ' email settings</div>';
        $msg .= $data[$area]['send_email'][$status];
        $msg .= $data[$area]['message'];
        $msg .= self::searchBrackets($message);

        if($status && $message && !self::searchBrackets($message))
        {
            $msg .= 'Seems all is good.';
        }
        else
        {
            $msg .= '<br>Please change/fix settings in red.';
        }

        echo $msg . '</div>';
    }

    public static function searchBrackets($message)
    {
        $illegal = array();

        preg_match_all("/{(.*?)}/", $message, $matches);

        foreach($matches[1] as $string)
        {
            if(preg_match("/<[^<]+>/",$string,$m) != 0){
                $illegal[$string] = $string;
            }
        }

        if($illegal) {
            return '<div class="label red">You have some illegal characters ( html code ) in your email variables: <br>' . implode(', ', $illegal) . '</div>To this fix switch to code editor and search for html tags in curly field brackets.';
        }
    }

}


<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This class handles all of the alerts. Tells the admin if something was added successfully or not. 
*
* @since 4.4.0
*/
class MlwicodeAlertManager {

	public $alerts = array();

        /**
        * This function passes the alert message into the arrray $alerts
        *
        * @param $message This is the variable that contains the message to given as an alert.
        * @param $type This variable holds either success/error and displays the correct message accordingly. 
        * @since 4.4.0
        */
	public function newAlert($message, $type)
	{
		$this->alerts[] = array( 'message' => $message, 'type' => $type );
	}

        /**
        * This function shows the alerts. It shows either a success or error message. 
        *
        * @since 4.4.0
        */
	public function showAlerts()
	{
		$alert_list = "";
		foreach ($this->alerts as $alert)
		{
			if ($alert['type'] == "success")
			{
				$alert_list .= "<div id=\"message\" class=\"updated below-h2\"><p><strong>".__('Success!', 'icode-survey-master')." </strong>".$alert["message"]."</p></div>";
			}
			if ($alert['type'] == "error")
			{
				$alert_list .= "<div id=\"message\" class=\"error below-h2\"><p><strong>".__('Error!', 'icode-survey-master')." </strong>".$alert["message"]."</p></div>";
			}
		}
		echo $alert_list;
	}

}
?>

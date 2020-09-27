<?php
  // By @andreyazimov
  // How to use:
    // 1. add this file into your PHP project
    // 2. require_once "send_mailgun_batch.php"
    // 3. change $mailgun_api_url and $mailgun_api_key
    // 4. Call this function send_mailgun_batch($emails_to_send_array, $subject, $message);
    // 5. If success you should see a message "Queued. Thank you."
  // Below is the demo that you can remove:

	//<demo of how to use this function>
		$emails_to_send_array = [
			"test1@example.com",
			"test2@example.com",
			"test3@example.com"
		];

		$from = 'Andrey from Sheet2Site support@sheet2site.com';
		$subject = "Test Subject";
		$message = "How are you today?";

		send_mailgun_batch($emails_to_send_array, $from, $subject, $message);
	//</demo of how to use this function>

//--------------------------------------------------------------------------------

	//<send_mailgun_batch>
		function send_mailgun_batch($emails_to_send_array, $from, $subject, $message) {

			# maximum is 1000 emails reciept per 1 send
			# doc: https://documentation.mailgun.com/en/latest/user_manual.html#batch-sending

			// todo: change to your
				$mailgun_api_url = "https://api.mailgun.net/v3/email.example.com/messages";
				$mailgun_api_key = 'api:27fb5dff743534dsfg8asdasdg-gdfgdf342-bfgdgdfeg24';
			//

			$recipient_variables = "{ ";

			foreach ($emails_to_send_array as $email) {
				$recipient_variables = $recipient_variables . "\"" . trim($email) . "\": \"{}\", ";
			}

			$recipient_variables = substr($recipient_variables , 0, -2);
			$recipient_variables = $recipient_variables . "}";

			//echo $recipient_variables; exit();

			$data = [
				'from'                => $from,
				'to'                  => $emails_to_send_array,
				'recipient-variables' => $recipient_variables,
				'subject'             => $subject,       
				'html'                => $message
			];

			echo json_encode(postCurl($mailgun_api_url, $data));
		}
	//<//send_mailgun_batch>


	//<postCurl>
		function postCurl($url, $data = NULL, $mailgun_api_key){

			# https://github.com/mailgun/mailgun-php/issues/642
			
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			if ($data !== NULL) {
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			}
			curl_setopt($ch, CURLOPT_USERPWD, $mailgun_api_key);
			$o=curl_exec($ch);
			$http_status=curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			return $o;
		}
	//</postCurl>

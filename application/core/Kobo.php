<?php
namespace app\helpers;

/**
 * Kobo REST API helper class.
 */
class KoboHelper
{
	CONST BASE_URL="https://kc.humanitarianresponse.info/api/v1/";
	CONST API_FORMS = 'forms';
	CONST API_FORM_SUBMISSIONS = 'data';

	public function __construct($config)
	{
		$config=[
			'base_url'=>"https://kc.humanitarianresponse.info/api/v1/",
			'form_id'=>80100,
			"form_uuid" => "aFLHqaNMiUfiNuhDQ9dbcc",
			"username" => "nset_bg_bcs",
			"password" => "nsetbcs8"
		];
	}


	public static function curlHandler($url, $headers = null)
	{
		$defaultHeaders = [];
		$headers = isset($headers) ? array_merge($defaultHeaders, $headers) : $defaultHeaders;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, self::USERNAME . ':' . self::PASSWORD);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

		return $ch;
	}

	/**
	 * @desc    Do a DELETE request with cURL
	 *
	 * @param   string $path   path that goes after the URL fx. "/user/login"
	 * @param   array  $json   If you need to send some json with your request. For me delete requests are always blank
	 * @return  Obj    $result HTTP response from REST interface in JSON decoded.
	 */
	public static function curl_del($path, $json='')
	{
		$url =self::BASE_URL.$path;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_USERPWD, self::USERNAME . ':' . self::PASSWORD);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

		$result = curl_exec($ch);
		return $result;
	}

	public static function prepareQueryString($queryArray)
	{
		//$queryArray =['_submission_time' => ['$gt' => $_lastSubmissionTime]];
		return urlencode(json_encode($queryArray));
	}

	public static function prepareUtl($apiType, $queryArray = null)
	{
		$url = '';
		Switch ($apiType) {
			case self::API_FORM_SUBMISSIONS:
				$url .= self::BASE_URL . 'data/' . self::FORM_ID;
				break;
			case self::API_FORMS:
				$url .= self::BASE_URL . '/forms';
				break;
			default:
				$url .= self::BASE_URL;
				break;
		}
		if (isset($queryArray)) {
			$url .= '?query=' . self::prepareQueryString($queryArray);
		}
		return $url;
	}

	public static function executeApi($url)
	{
		$ch = self::curlHandler($url);
		$response_data = curl_exec($ch);
		if (curl_errno($ch) > 0) {
			die('There was a cURL error: ' . curl_error($ch));
		} else {
			//Close the handler and release resources
			curl_close($ch);
		}

		$results = json_decode($response_data, TRUE);
		return $results;
	}

	public static function getPracticeData_Dolakha()
	{
		$url = self::prepareUtl(self::API_FORM_SUBMISSIONS_DOLAKHA, ["start" => ["\$lt" => "2016-08-03T00:00:00"]]);

		return self::executeApi($url);
	}

	public static function getPracticeData_Dhading()
	{
		$url = self::prepareUtl(self::API_FORM_SUBMISSIONS_DHADING, ["start" => ["\$lt" => "2016-08-14T00:00:00"]]);

		return self::executeApi($url);
	}

	public static function deleteSubmission($formId,$submissionId){
		$path ='/data/'.$formId.'/'.$submissionId;

		$response = self::curl_del($path);
		var_dump($response);
	}

}
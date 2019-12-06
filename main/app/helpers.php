<?php
use Illuminate\Contracts\Filesystem\FileNotFoundException as FileGetException;

use League\Flysystem\FileNotFoundException as FileDownloadException;
use Carbon\Carbon;

// if (env('APP_DEBUG')) ini_set('opcache.revalidate_freq', '0');

/**
 * Custom asset helper to append public/ to all asset generated urls
 *
 * @param string path The path to the asset
 * @param Boolean secure Should https be used explicitly for the path?
 * @return string
 */

function myAsset($path, $secure = null)
{
	return asset('/public/' . trim($path, '/'), $secure);
}

/**
 * Custom Url helper to generate urls using the storage facade
 *
 * @param string path The path to the asset
 * @param Boolean secure Should https be used explicitly for the path?
 * @return string
 */

function img($path, $secure = null)
{
	return Storage::disk('public')->url($path);
}

if (!function_exists('unique_random')) {

	/**
	 *
	 * Generate a unique random string of characters
	 * uses str_random() helper for generating the random string
	 *
	 * @param     $table - name of the table
	 * @param     $col - name of the column that needs to be tested
	 * @param int $chars - length of the random string
	 *
	 * @return string
	 */
	function unique_random($table, $col, $chars = null)
	{
		$unique = false;

          // Store tested results in array to not test them again
		$tested = [];

		do {

            // Generate random string of characters

			if ($chars == null) {
				$random = rand(100001, 999999999999);
			} else {
				$random = str_random($chars);
			}

            // Check if it's already testing
            // If so, don't query the database again
			if (in_array($random, $tested)) {
				continue;
			}

            // Check if it is unique in the database
			$count = DB::table($table)->where($col, '=', $random)->count();

            // Store the random character in the tested array
            // To keep track which ones are already tested
			$tested[] = $random;

            // String appears to be unique
			if ($count == 0) {
              // Set unique to true to break the loop
				$unique = true;
			}

            // If unique is still false at this point
            // it will just repeat all the steps until
            // it has generated a random string of characters

		} while (!$unique);


		return $random;
	}
}


if (!function_exists('download_file')) {

	/**
	 *
	 * Generate a file download response
	 * uses default filesystems disk
	 *
	 * @param     $file - The file to download
	 * @param     $name_to_use - the name the user sees (optional)
	 *
	 * @return response
	 */
	function download_file($file, $name_to_use = null)
	{
		try {
			return Storage::download($file, $name_to_use);
		} catch (FileGetException $e) {
			abort(404, "Requested file ({$e->getMessage()}) not found");
		} catch (FileDownloadException $e) {
			abort(404, $e->getMessage());
		}
	}
}


if (!function_exists('view_file_in_browser')) {

	/**
	 *
	 * Generate a url redirect response that enables browser to access the file directly
	 * uses a custom filesystems disk that does not append app_url to the url method
	 * Something like this
	 *
	 *      'browser_view' => [
	 *          'driver' => 'local',
	 *          'root' => storage_path('app/public'),
	 *          'url' => '/storage',
	 *          'visibility' => 'public',
	 *      ],
	 *
	 * @param     $file - The file to download
	 *
	 *
	 *
	 * @return redirect response
	 */
	function view_file_in_browser($file)
	{

		try {
			return redirect(Storage::disk('browser_view')->url($file));
		} catch (FileGetException $e) {
			abort(404, "Requested file ({$e->getMessage()}) not found");
		} catch (FileDownloadException $e) {
			abort(404, $e->getMessage());
		}
	}
}



function unique_random2()
{
	do {
		$token_id = makeRandomToken();
		$token_key = makeRandomTokenKey();
	} while (User::where("token_id", "=", $token_id)->where("token_key", "=", $token_key)->first() instanceof User);
}

function mail_utf8($to, $from_user, $from_email, $subject = '(No subject)', $message = '')
{
	$from_user = "=?UTF-8?B?" . base64_encode($from_user) . "?=";
	$subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

	$headers = "From: $from_user <$from_email>\r\n" .
		"MIME-Version: 1.0" . "\r\n" .
		"Content-type: text/html; charset=UTF-8" . "\r\n";

	return mail($to, $subject, $message, $headers);
}


function sendmail($data)
{
	$encoding = "utf-8";

  // Preferences for Subject field
	$subject_preferences = array(
		"input-charset" => $encoding,
		"output-charset" => $encoding,
		"line-length" => 76,
		"line-break-chars" => "\r\n"
	);

  // Mail header
	$header = "Content-type: text/html; charset=" . $encoding . " \r\n";
	$header .= "From: " . $from_name . " <" . $from_mail . "> \r\n";
	$header .= "MIME-Version: 1.0 \r\n";
	$header .= "Content-Transfer-Encoding: 8bit \r\n";
	$header .= "Date: " . date("r (T)") . " \r\n";
	$header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

  // Send mail
	mail($mail_to, $mail_subject, $mail_message, $header);
}

function mydd(...$args)
{
	$content = "<!DOCTYPE html><html><body>";

	foreach ($args as $arg) {
		$val = (new \Symfony\Component\VarDumper\Cloner\VarCloner)->cloneVar($arg);
		$dumper = new \Illuminate\Support\Debug\HtmlDumper;
		$content .= $dumper->dump($val, true);
	}

	$content .= "</body></html>";

	return response($content, 500)->header('Content-Type', 'text/html')->send();
}

function _dd($args)
{
	http_response_code(500);
	dd($args);
}

if (!function_exists('alt_dd')) {
	function alt_dd(...$data)
	{
		header('HTTP/1.1 500 Internal Server Error');
		call_user_func_array('dd', $data);
	}
}

if (!function_exists('slug_to_string')) {
	function slug_to_string($data)
	{
		str_replace('-', ' ', $data);
	}
}

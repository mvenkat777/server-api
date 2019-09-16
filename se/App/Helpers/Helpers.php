<?php

namespace Platform\App\Helpers;

use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Carbon\Carbon;

class Helpers
{
    /**
     * Creates a temporary password.
     *
     * @param string $string
     *
     * @return string
     */
    public static function makeTemporaryPassword($string)
    {
        return substr(bcrypt($string . time()), 0, 10);
    }

    /**
     * Return Authenticated Users Display Name.
     *
     * @return string
     */
    public static function getAuthUserName()
    {
        return isset(Auth::user()->display_name) ? Auth::user()->display_name : Auth::user()->email;
    }

    /**
     * Checks if the given string is a valid emailid.
     *
     * @param string $email
     *
     * @return bool true if valid email
     */
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Check if the request came from platform.
     *
     * @return bool
     */
    public static function isOriginPlatform()
    {
        if (!isset($_SERVER['HTTP_ORIGIN'])) {
            return false;
        }
        return strpos($_SERVER['HTTP_ORIGIN'], 'platform');
    }

    /**
     * Get IP Address from where the request is coming.
     *
     * @return string
     */
    public static function getIPAddress()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            return getenv('HTTP_CLIENT_IP');
        }

        if (getenv('HTTP_X_FORWARDED_FOR')) {
            return getenv('HTTP_X_FORWARDED_FOR');
        }

        if (getenv('HTTP_X_FORWARDED')) {
            return getenv('HTTP_X_FORWARDED');
        }

        if (getenv('HTTP_FORWARDED_FOR')) {
            return getenv('HTTP_FORWARDED_FOR');
        }

        if (getenv('HTTP_FORWARDED')) {
            return getenv('HTTP_FORWARDED');
        }

        if (getenv('REMOTE_ADDR')) {
            return getenv('REMOTE_ADDR');
        }

        return 'UNKNOWN';
    }

    /**
     * Get from which browser the result came from.
     *
     * @return string
     */
    public static function getBrowser()
    {
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            return 'other';
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), strtolower('MSIE'))) {
            return 'ie';
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), strtolower('Presto'))) {
            return 'opera';
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), strtolower('CHROME'))) {
            return 'chrome';
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), strtolower('SAFARI'))) {
            return 'safari';
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), strtolower('FIREFOX'))) {
            return 'firefox';
        }

        return 'other';
    }

    /**
     * Check if an email is valid sourceeasy email.
     *
     * @param string $email
     *
     * @return bool
     */
    public static function isSeEmail($email)
    {
        $domain = '@sourceeasy.com';

        return (($temp = strlen($email) - strlen($domain)) >= 0 && strpos($email, $domain, $temp) !== false);
    }

    /**
     * @param $user
     *
     * @return paginate object
     */
    public static function paginateTransformer($user, $users)
    {
        $queryParams = array_diff_key($_GET, array_flip(['page']));
        foreach ($queryParams as $key => $value) {
            $user->addQuery($key, $value);
        }

        $paginatorAdapter = new IlluminatePaginatorAdapter($user);
        $users->setPaginator($paginatorAdapter);
        $fractal = new Manager();

        return $fractal->createData($users)->toArray();
    }

    /**
     * Simplify a fraction
     * @param  integer $numerator
     * @param  integer $denominator
     * @return array
     */
    public static function simplifyFraction($numerator, $denominator)
    {
        $gcd = self::gcd($numerator, $denominator);
        return [$numerator/$gcd, $denominator/$gcd];
    }

    /**
     * Find GCD of two numbers
     *
     * @param mixed $a
     * @param mixed $b
     * @return integer
     */
    public static function gcd($a, $b)
    {
        if ($a == 0 || $b == 0)
            return abs( max(abs($a), abs($b)) );

        $r = $a % $b;
        return ($r != 0) ? self::gcd($b, $r) : abs($b);
    }

    /**
     * Convert a decimal number to fraction
     * @param  string $number
     * @return string
     */
    public static function decimalToFraction($number)
    {
        if (!is_numeric($number)) {
            return $number;
        }

        $number = (float) $number;
        $base   = floor($number);

        if ($base == $number) {
            return $base;
        }
        if ($base < 0) {
            $base += 1;
        }

        $decimal = $number - $base;

        $numberOfDecimal = pow(10, (strlen((string)$decimal) - 2));
        $fraction = self::simplifyFraction((int) round($decimal * $numberOfDecimal), $numberOfDecimal);

        if ($base == 0) {
            return sprintf("%d/%d", $fraction[0], $fraction[1]);
        }
        if($fraction[0] < 0) {
            $fraction[0] = -$fraction[0];
        }
        return sprintf("%d %d/%d", $base, $fraction[0], $fraction[1]);
    }

   /**
     * Check if data in an array is not empty
     *
     * @param  array   $data
     * @param  string  $variable
     * @return boolean
     */
    public static function isSetAndIsNotEmpty(array $data, $variable)
    {
        return isset($data[$variable]) && !empty($data[$variable]);
    }

	/**
	 * Get today's date
	 *
	 * @param  boolean $wantObject
	 * @return Object/String
	 */
    public static function today($wantObject = false)
    {
		if($wantObject)
			return Carbon::now();
		else
			return Carbon::now()->toDateTimeString();
    }

    /**
     * Convert a string to snake case
     *
     * @param string $string
     * @return string
     */
    public static function toSnakecase($string) {
        return ltrim(
            strtolower(
                preg_replace(
                    '/[A-Z]/',
                    '_$0',
                    str_replace(" ", "", ucwords($string))
                )
            ),
            '_'
        );
    }

    /**
     * Convert snake cased string to noraml casing with white space in between
     * words and first letter of each word capitalized.
     *
     * @param string $string
     * @return string
     */
    public static function snakecaseToNormalcase($string) {
	   return ucfirst(ltrim(str_replace('_', " ", $string)));
    }

    /**
     * conver snake cased to camelcase wit first character as small case
     *
     * @param  string $string
     * @return string
     */
    public static function snakeCaseToCamelCase($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(ltrim(str_replace('_', " ", $string)))));
    }

    /**
     * Gets the authenticated user's id
     * @return string
     */
    public static function getAuthUserId()
    {
        if (Auth::user()) {
            return Auth::user()->id;
        }
        throw new SeException("You must be authenticated to perform this action.", 401);
    }

    public static function getJsonDifference($firstData, $secondData)
    {
        $data = [];
        foreach ($firstData as $key => $value) {
            if (
                is_array($firstData[$key]) &&
                isset($secondData[$key]) &&
                is_array($secondData[$key])
            ){
               $difference = self::call($firstData[$key], $secondData[$key]);
               if(count($difference)){
                   $data[$key] = $difference;
               }
           } elseif($firstData[$key] !== $secondData[$key]){
               $data[$key] = $secondData[$key];
           }
       }
       return $data;
    }

    protected static function call($first, $second)
    {
       $data = [];
       foreach ($first as $key => $value) {
            if(
                is_array($first[$key]) &&
                isset($second[$key]) &&
                is_array($second[$key])
            ){
               $difference = self::call($first[$key], $second[$key]);
               if(count($difference))
                   $data[$key] = $difference;
           } elseif(isset($second[$key]) && ($first[$key] !== $second[$key])) {
               $data[$key] = $second[$key];
           }
       }
       return $data;
    }

    /**
     * Generate random code
     *
     * @param string $string
     *
     * @return string
     */
    public static function generateRandomCode($string)
    {
        return self::url(bcrypt($string) . bcrypt($string));
    }

    /**
     * Removes url not allowed characters from a string
     *
     * @param mixed $string
     * @return string
     */
    public static function url($string) {
       $string = preg_replace('~[^\\pL0-9_]+~u', '-', $string);
       $string = trim($string, "-");
       $string = iconv("utf-8", "us-ascii//TRANSLIT", $string);
       $string = strtolower($string);
       $string = preg_replace('~[^-a-z0-9_]+~', '', $string);
       return $string;
    }

}

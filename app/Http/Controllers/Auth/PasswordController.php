<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */

    /**
     * user password encription string
     *
     * @access private
     * @var array
     */
    private static $pass_enc = array("1234234vnnMSKKJSHJALKE14534356565KMDKJcmdkriJJIEIJKDJKXMDJKDHFKJDaircmkALLANDGO",
        "bv1219398djcdasne83ojfmvb2trkrtnpq4994NCKMLKMWQI3JKDJKC2323ffkjhdfajnnxALLANDGO",
        "KCJSAKDCJ2221232838378Djkljd34djoi4d48wjIJJ8DU3DQ8U4JkMaqjwkdkjdkqjdc2sALLANDGO",
        "32434FDSJJJ4J3L4K3sedijwiekwjwieji4o2ioijdkkjelwkjkkdcjjekwjekwjjkd1211ALLANDGO",
        "222dldjaldj3i2ocsddegtfEdFeODDS122328954956039LLKJCKLSDJCKSJKAKSJDaawdfALLANDGO");

    /**
     * Key Identifier for stringprotect
     * @var type 
     */
    private $keyId = "20q79jug7gl84nnsr0fmt95pa0";
    // blowfish
    private static $algo = '$2a';
    // cost parameter
    private static $cost = '$10';

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    // mainly for internal use
    public static function unique_salt() {
        return substr(sha1(mt_rand()), 0, 22);
    }

    // this will be used to generate a hash
    public static function hash($password) {

        return crypt($password, self::$algo .
                self::$cost .
                '$' . self::unique_salt());
    }

    // this will be used to compare a password against a hash
    public static function check_password($hash, $password) {
        $full_salt = substr($hash, 0, 29);
        $new_hash = crypt($password, $full_salt);
        return ($hash == $new_hash);
    }

    /**
     * Generating random Unique MD5 String for user Api key
     */
    public static function generateApiKey() {
        return md5(uniqid(rand(), true));
    }

    /**
     * Generate Random String Value
     * 
     * @param int $length - the length of random string to be generated
     * @return string
     */
    public static function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Encrypt user password
     *
     * @param string $user_id
     * @param string $password
     * @return string encrpted password
     */
    public static function Encrypt($user_id, $password) {
        $checksum = $pass_str = "";
        for ($i = 0; $i < strlen($user_id); $i++) {
            $checksum = $checksum + ord(substr($user_id, $i, 1));
        }
        $fmod = fmod($checksum, 5);
        for ($i = 0; $i < strlen($password); $i++) {
            $padstr = str_pad(ord(substr($password, $i, 1)) + ord(substr(self::$pass_enc[$fmod], $i, 1)), 3, "0", STR_PAD_LEFT);
            $pass_str .= $padstr;
        }
        return $pass_str;
    }

}

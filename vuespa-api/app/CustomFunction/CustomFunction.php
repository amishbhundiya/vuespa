<?php

namespace App\CustomFunction;

use DB;
use Carbon;
use DateTime;
use Illuminate\Support\Facades\File;

class CustomFunction
{
    public static function random_string($limit)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $limit; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return $token;
    }

    public static function random_string_capital($limit)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $limit; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return $token;
    }

    public static function filter_input($input)
    {
        $input = trim($input);
        $input = htmlentities($input);
        $input = addslashes($input);

        return $input;
    }

    public static function decode_input($input)
    {
        $input = htmlspecialchars_decode($input);
        $input = stripslashes($input);
        return $input;
    }

    public static function fileUpload($file, $oldFileName = null)
    {
        $random_no = CustomFunction::random_string(10);

        $name = $random_no . '.' . $file->getClientOriginalExtension();
        $name_original = $file->getClientOriginalName();

        if ($file->move(public_path('uploads/'), $name) && $oldFileName != null) {

            $filename = public_path('uploads/') . '/' . $oldFileName;

            if (file_exists($filename)) {
                unlink($filename);
            }
        }

        $output = array('name' => $name, 'name_original' => $name_original);
        return $output;
    }

    public static function fileRemove($oldFileName)
    {
        if ($oldFileName != '' && File::exists(public_path('uploads/' . $oldFileName))) {
            File::delete(public_path('uploads/' . $oldFileName));
        }

        return true;
    }

    public static function validate_input($input, $vtype)
    {
        if ($vtype == "email") {
            if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                return true;
            }
        }
        if ($vtype == "text_only") {
            if (preg_match("/^[a-zA-Z]*$/", $input)) {
                return true;
            }
        }
        if ($vtype == "text_with_space") {
            if (preg_match("/^[a-zA-Z ]*$/", $input)) {
                return true;
            }
        }
        if ($vtype == "text_with_number") {
            if (preg_match("/^[a-zA-Z0-9]*$/", $input)) {
                return true;
            }
        }
        if ($vtype == "text_with_number_space") {
            if (preg_match("/^[a-zA-Z0-9 ]*$/", $input)) {
                return true;
            }
        }
        if ($vtype == "slug") {
            if (preg_match("/^[a-zA-Z0-9-]*$/", $input)) {
                return true;
            }
        }
        if ($vtype == "price") {
            if (preg_match("/^[0-9.]*$/", $input)) {
                return true;
            }
        }
        if ($vtype == "all_text") {
            $text = strpos($input, '`');
            if (empty($text)) {
                return true;
            }
        }
        if ($vtype == "number_only") {
            if (preg_match("/^[0-9]*$/", $input)) {
                return true;
            }
        }
        if ($vtype == "phone_number") {
            if (preg_match("/^[+0-9-]*$/", $input)) {
                return true;
            }
        }
        if ($vtype == "url") {
            if (filter_var($input, FILTER_VALIDATE_URL)) {
                return true;
            }
        }
        return false;
    }

}
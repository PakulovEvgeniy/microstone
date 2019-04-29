<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Captcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
          'secret' => '6LcArp8UAAAAAKiG5D-7JJxjnnFF8HaaaHwq1k0U',
          'response' => $value
        ];
        $options = [
            'http' => [
            'method' => 'POST',
            'header' =>  "Content-Type: application/x-www-form-urlencoded",
            'content' => http_build_query($data)
            ]
        ];
        $context  = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        $captcha_success=json_decode($verify);
        if ($captcha_success && $captcha_success->success) {
            return true;
        };
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ошибка проверки captcha';
    }
}

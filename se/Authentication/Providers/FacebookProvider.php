<?php

namespace Platform\Authentication\Providers;

class FacebookProvider
{
    protected $USERINFO_URL = 'https://graph.facebook.com/me?fields=name,email,picture&access_token=';

    /**
     * Gets user details based on access_token
     * @param  string $token
     * @return array
     */
    public function getUserByToken($token)
    {
        $ch = curl_init($this->USERINFO_URL . $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        $data = json_decode($data);
        if (property_exists($data, 'error')) {
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return $this->createUserArray($data);
        }
    }

    /**
     * Extracts only required information from Google API returned JSON array
     * @param  object $user
     * @return array
     */
    protected function createUserArray($user)
    {
        return [
            'displayName' => $user->name,
            'email' => $user->email,
        ];
    }
}

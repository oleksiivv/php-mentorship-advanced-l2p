<?php

namespace Http\Controllers;

use Exception;
use Http\Core\Request;
use Http\Core\Response;

class ExternalAvatarsController
{
    private const URL = 'https://randomuser.me/api';

    public function get(Request $request): Response
    {
        try {
            // Fetch XML data from API
            $xmlData = $this->fetchXmlData(self::URL . '?results=3&format=xml');
            $avatarUrls = $this->extractAvatarUrls($xmlData);

            return new Response(['avatars' => $avatarUrls], 200);
        } catch (Exception $e) {
            return new Response(['error' => $e->getMessage()], 500);
        }
    }

    private function extractAvatarUrls(string $xmlData): array
    {
        $avatars = [];
        $xml = simplexml_load_string($xmlData);

        foreach ($xml->results as $user) {
            $avatars[] = (string) $user->picture->large;
        }

        return $avatars;
    }

    private function fetchXmlData(string $url): string
    {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);

        $data = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }

        curl_close($curl);

        return $data;
    }
}

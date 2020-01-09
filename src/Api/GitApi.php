<?php


namespace App\Api;


use GuzzleHttp\Client;

class GitApi {
   public function getCompleteProfile($username) {
      try {
         $userProfile = $this->getProfile($username);
         if ($userProfile) {
            $repos = $this->getRepositories($username);
            return [
               'profile' => $userProfile,
               'repos' => $repos,
               'languages' => $this->getLanguagesRatio($repos)
            ];
         }
      } catch (\Exception $e) {
         return false;
      }
      return false;
   }

   public function getProfile($username) {
      $client = new Client(['verify' => false]);
      $res = $client->get('https://api.github.com/users/' . $username);
      return $res->getStatusCode() === 200 ? json_decode($res->getBody()) : false;
   }

   public function getRepositories($username) {
      $client = new Client(['verify' => false]);
      $res = $client->get('https://api.github.com/users/' . $username . '/repos');
      return $res->getStatusCode() === 200 ? json_decode($res->getBody()) : [];
   }

   private function getLanguagesRatio($repos) {
      $languages = [];
      $total = 0;
      foreach ($repos as $repo) {
         if ($repo->language) {
            if (!isset($languages[$record->language])) {
               $languages[$repo->language] = 0;
            }
            $total += 1;
            $languages[$repo->language] += 1;
         }
      }
      $summary = [];
      foreach ($languages as $language => $size) {
         $summary[] = [
            'name' => $language,
            'ratio' => floor(($size / $total) * 100),
         ];
      }
      return $summary;
   }
}
<?php

namespace common\components\oauth;

use nodge\eauth\services\GoogleOAuth2Service;

class IBCGoogleService extends GoogleOAuth2Service {
    protected $scopes = [self::SCOPE_USERINFO_PROFILE, self::SCOPE_USERINFO_EMAIL];

    protected function fetchAttributes() {
        $info = $this->makeSignedRequest('https://www.googleapis.com/plus/v1/people/me');

        if (isset($info['emails'])) {
            if (sizeof($info['emails']) > 0) {
                $info['email'] = $info['emails'][0]['value'];
            }
            unset($info['emails']);
        }
        $info['name'] = (isset($info['displayName'])) ? $info['displayName'] :'';

        $photoLink = "https://plus.google.com/s2/photos/profile/".$info['id'];
        $info['photo'] = $photoLink;
        $info['smallPhoto'] = $photoLink.'?sz=50';
        if (isset($info['image'])){
            unset($info['image']);
        }
        $this->attributes = $info;
        return true;
    }

}
?>
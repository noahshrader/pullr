<?php

namespace common\components\oauth;

use nodge\eauth\services\TwitterOAuth1Service;

class IBCTwitterService extends TwitterOAuth1Service {

    protected function fetchAttributes() {
        $info = $this->makeSignedRequest('account/verify_credentials.json');

        if (isset($info['screen_name'])){
            $info['url'] = 'http://twitter.com/'.$info['screen_name'];
        }
        if (isset($info['profile_image_url'])){
            $info['smallPhoto'] = $info['profile_image_url'];
            unset($info['profile_image_url']);
        }
        
        $this->attributes = $info;
    }

}

?>
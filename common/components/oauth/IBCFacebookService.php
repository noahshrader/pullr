<?php

namespace common\components\oauth;

use nodge\eauth\services\FacebookOAuth2Service;

class IBCFacebookService extends FacebookOAuth2Service{
    protected $scopes = array(
		self::SCOPE_EMAIL,
		self::SCOPE_USER_BIRTHDAY,
    );

    protected function fetchAttributes() {
        $info = $this->makeSignedRequest('me');
        if (isset($info['link'])){
            $info['url'] = $info['link'];
            unset($info['link']);
        }
        
        $photoLink = "http://graph.facebook.com/".$info['username'].'/picture?type=';
        $info['photo'] = $photoLink.'large';
        $info['smallPhoto'] = $photoLink.'square';
        $date = date_parse_from_format("j/n/Y", $info['birthday']);
        /**
         * let's format to mysql Date type
         */
        $info['birthday'] = $date['year'].'-'.$date['month'].'-'.$date['day'];
        $this->attributes = $info;
        return true;
    }

}
?>
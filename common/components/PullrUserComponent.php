<?php
namespace common\components;

use Yii;
/** 
 * This is custom User component implementation
 */
class PullrUserComponent extends \yii\web\User{
        /**
     * Returns the URL that the user should be redirected to after successful login.
     * This property is usually used by the login action. If the login is successful,
     * the action should read this property and use it to redirect the user browser.
     * @param string|array $defaultUrl the default return URL in case it was not set previously.
     * If this is null and the return URL was not set previously, [[Application::homeUrl]] will be redirected to.
     * Please refer to [[setReturnUrl()]] on accepted format of the URL.
     * @return string the URL that the user should be redirected to after login.
     * @see loginRequired()
     */
    public function getReturnUrl($defaultUrl = null) {
        $returnUrl = Yii::$app->request->get('return');       
        
        if ($returnUrl)
        {
            return $returnUrl;
        }
        
        $url = Yii::$app->getSession()->get($this->returnUrlParam, $defaultUrl);
        if (is_array($url)) {
            if (isset($url[0])) {
                $route = array_shift($url);
                return Yii::$app->getUrlManager()->createUrl($route, $url);
            } else {
                $url = null;
            }
        }
        
        return $url === null ? Yii::$app->getHomeUrl() : $url;
    }
    
    public function getSessionReturnUrl(){
         return Yii::$app->getSession()->get($this->returnUrlParam);
    }
    
    public function loginRequired($checkAjax = true) {
        if (\Yii::getAlias($this->loginUrl) == ''){
            /**that is workaround for bug fix (seems at yii) when website main page looks like http://pullr.io (haven't subdirectories)*/
            $this->loginUrl = '/';
        }
        parent::loginRequired($checkAjax);
    }
}
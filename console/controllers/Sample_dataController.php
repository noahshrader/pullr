<?php

namespace console\controllers;

use frontend\models\streamboard\WidgetAlertsPreference;
use PayPal\Common\PPReflectionUtil;
use Yii;
use yii\console\Controller;
use common\models\User;
use common\models\OpenIDToUser;
use common\models\Charity;
use common\models\Campaign;
use common\models\notifications\SystemNotification;
use common\models\CampaignInvite;
use common\models\Donation;
use frontend\models\streamboard\StreamboardRegion;

class Sample_dataController extends Controller
{
    public function actionIndex()
    {
        echo "Disabling MySQL strict mode\n";
        $connection=Yii::$app->db;
        $command=$connection->createCommand("SET sql_mode=''");
        $command->execute();
        
        echo "\nStarting to load sample data\n";
        echo "Starting to load sample users\n";
        $this->sampleUsers();
        echo "Starting to load sample charities\n";
        $this->sampleCharities();
        echo "Starting to load sample notification\n";
        $this->sampleNotification();
        echo "Starting to load sample campaigns\n";
        $this->sampleCampaigns();
        echo "Starting to load sample donations\n";
        $this->sampleDonations();
        echo "Starting to make pro-accounts\n";
        $this->sampleProAccounts();
        echo "Starting to make streamboard\n";
        $this->sampleStreamboard();
        echo "Data was loaded successfully\n\n";
    }

    /**
     * @var User
     */
    private $user1 = null;
    /**
     * @var User
     */
    private $user2 = null;
    /**
     * @var User
     */
    private $adminUser = null;

    /**
     * @var Campaign
     */
    private $campaign1 = null;

    /**
     * @var Campaign
     */
    private $childCampaign1 = null;

    /**
     * @var Campaign
     */
    private $childCampaign2 = null;

    private function sampleUsers()
    {
        /*first user*/
        $user = new User();
        $user->setScenario('signup');
        $user->login = 'stanislav@gmail.com';
        $user->name = 'Stanislav Gmail';
        $user->password = 'Stanislav';
        $user->confirmPassword = $user->password;
        $user->email = 'stas.msu@gmail.com';
        $user->save();

        $openId = new OpenIDToUser();
        $openId->userId = $user->id;
        $openId->serviceName = 'twitch';
        $openId->serviceId = '55052982';
        $openId->url = 'http://twitch.tv/klyukin';
        $openId->save();

        $user->setScenario('openId');
        $user->name = 'Klyukin';
        $user->uniqueName = 'klyukin';
        $user->photo = 'http://static-cdn.jtvnw.net/jtv_user_pictures/klyukin-profile_image-c5ca1ccc61c3c330-300x300.jpeg';
        $user->smallPhoto = 'http://static-cdn.jtvnw.net/jtv_user_pictures/klyukin-profile_image-c5ca1ccc61c3c330-300x300.jpeg';
        $user->save();

        $user->userFields->twitchChannel = 'supermcgamer';
        $user->userFields->twitchAccessToken = 'glijyh4npl1dl1414r8fxt3g9lljnz';
        $user->userFields->twitchAccessTokenDate = time();
        $user->userFields->twitchPartner = true;
        $user->userFields->save();
        /*end of first user*/
        $this->user1 = $user;

        $user = new User();
        $user->setScenario('signup');
        $user->login = 'funforfreedom@gmail.com';
        $user->uniqueName = 'funforfreedom_test';
        $user->name = 'Fun For Freedom';
        $user->password = 'funforfreedom';
        $user->confirmPassword = $user->password;
        $user->email = 's.klyukin@yandex.ru';
        $user->save();
        $user->userFields->twitchChannel = 'funforfreedom';
        $user->userFields->save();
        $this->user2 = $user;

        $user = new User();
        $user->setScenario('signup');
        $user->login = 'admin1@gmail.com';
        $user->name = 'Admin1';
        $user->password = 'Admin';
        $user->email = 'pullr1@yandex.com';
        $user->save();

        $user->setScenario('roles');
        $user->role = User::ROLE_ADMIN;
        $user->save();

        $user = new User();
        $user->setScenario('signup');
        $user->login = 'admin2@gmail.com';
        $user->name = 'Admin2';
        $user->password = 'Admin';
        $user->email = 'pullr2@yandex.com';
        $user->save();

        $user->setScenario('roles');
        $user->role = User::ROLE_ADMIN;
        $user->save();

        $user = new User();
        $user->setScenario('signup');
        $user->login = 'admin3@gmail.com';
        $user->name = 'Admin3';
        $user->password = 'Admin';
        $user->email = 'pullr3@yandex.com';
        $user->save();

        $user->setScenario('roles');
        $user->role = User::ROLE_ADMIN;
        $user->save();
        $this->adminUser = $user;
    }

    private function sampleCharities()
    {
        $charity = new Charity();
        $charity->name = 'St. Jude';
        $charity->type = 'Community';
        $charity->paypal = 'paypal@stjude.org';
        $charity->contact = 'Noah Shrader';
        $charity->contactEmail = 'noahshrader@gmail.com';
        $charity->save();

        $charity = new Charity();
        $charity->name = 'PETA';
        $charity->type = 'Animals';
        $charity->paypal = 'paypal@peta.org';
        $charity->contact = 'Stanislav Klyukin';
        $charity->contactEmail = 'stas.msu@gmail.com';
        $charity->save();
    }

    private function sampleNotification()
    {
        $notification = new SystemNotification();
        $notification->message = 'Welcome beta tester! Please excuse my bugs ^_^';
        $notification->save();
    }

    private function sampleCampaigns(){
        $campaign = new Campaign;
        $campaign->name = 'Fun For Freedom';
        $campaign->type = Campaign::TYPE_CHARITY_FUNDRAISER;
        $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
        $campaign->channelTeam = "funforfreedom";
        $campaign->userId = $this->user1->id;
        $campaign->key = 'boss_key';
        $campaign->goalAmount = 17000;
        $campaign->donationDestination = Campaign::DONATION_CUSTOM_FUNDRAISER;
        $campaign->customCharityPaypal = 'donation.klyukin@gmail.com';
        $campaign->save();

        $this->campaign1 = $campaign;
        CampaignInvite::addInvite($this->user2->id, $campaign->id);
        $invite = CampaignInvite::findOne(['userId' => $this->user2->id, 'campaignId' => $campaign->id]);
        $invite->approve();

        $campaign = new Campaign;
        $campaign->name = 'Fun For Freedom 2';
        $campaign->type = Campaign::TYPE_CHARITY_FUNDRAISER;
        $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
        $campaign->channelTeam = "funforfreedom";
        $campaign->userId = $this->user2->id;
        $campaign->key = 'boss_key';
        $campaign->goalAmount = 17000;
        $campaign->donationDestination = Campaign::DONATION_CUSTOM_FUNDRAISER;
        $campaign->customCharityPaypal = 'donation.klyukin@gmail.com';
        $campaign->save();

        CampaignInvite::addInvite(1, $campaign->id);

        $campaign = new Campaign;
        $campaign->name = 'LinkSonicK';
        $campaign->type = Campaign::TYPE_CHARITY_FUNDRAISER;
        $campaign->layoutType = Campaign::LAYOUT_TYPE_SINGLE;
        $campaign->userId = $this->user1->id;
        $campaign->goalAmount = 15000;
        $campaign->donationDestination = Campaign::DONATION_CUSTOM_FUNDRAISER;
        $campaign->customCharityPaypal = 'wrong.paypal@gmail.com';
        $campaign->save();

        $campaign = new Campaign();
        $campaign->name = 'Awesome Games Done Quick';
        $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
        $campaign->userId = $this->user1->id;
        $campaign->goalAmount = 15000;
        $campaign->paypalAddress = '11@mail.ru';
        $campaign->save();

        $campaign = new Campaign();
        $campaign->name = 'Awesome Games Done Quick 2';
        $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
        $campaign->userId = $this->user1->id;
        $campaign->goalAmount = 15000;
        $campaign->paypalAddress = '11@mail.ru';
        $campaign->save();


        $campaign = new Campaign;
        $campaign->name = 'Awesome Games Done Quick';
        $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
        $campaign->userId = $this->adminUser->id;
        $campaign->goalAmount = 15000;
        $campaign->paypalAddress = '11@mail.ru';
        $campaign->save();

        CampaignInvite::addInvite($this->user1->id, $campaign->id);
        $invite = CampaignInvite::findOne(['userId' => $this->user1->id, 'campaignId' => $campaign->id]);
        $invite->approve();

        $campaign = new Campaign;
        $campaign->name = 'Parent Campaign';
        $campaign->type = Campaign::TYPE_CHARITY_FUNDRAISER;
        $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
        $campaign->userId = $this->adminUser->id;
        $campaign->goalAmount = 15000;
        $campaign->donationDestination = Campaign::DONATION_CUSTOM_FUNDRAISER;
        $campaign->customCharityPaypal = 'donation.klyukin@gmail.com';
        $campaign->save();

        CampaignInvite::addInvite($this->user1->id, $campaign->id);
        $invite = CampaignInvite::findOne(['userId' => $this->user1->id, 'campaignId' => $campaign->id]);
        $invite->approve();

        $parentCampaignId = $campaign->id;

        $campaign = new Campaign();
        $campaign->name = 'Fun For Freedom Child';
        $campaign->type = Campaign::TYPE_CHARITY_FUNDRAISER;
        $campaign->tiedToParent = true;
        $campaign->parentCampaignId = $parentCampaignId;
        $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
        $campaign->goalAmount = 15000;
        $campaign->userId = $this->user1->id;
        $campaign->startDate = 1405761880;
        $campaign->endDate = 1408761880;
        $campaign->teamEnable = true;
        $campaign->save();

        $this->childCampaign1 = $campaign;

        $campaign = new Campaign();
        $campaign->name = 'Fun For Freedom Child 2';
        $campaign->type = Campaign::TYPE_CHARITY_FUNDRAISER;
        $campaign->tiedToParent = true;
        $campaign->parentCampaignId = $parentCampaignId;
        $campaign->layoutType = Campaign::LAYOUT_TYPE_TEAM;
        $campaign->userId = $this->user1->id;
        $campaign->goalAmount = 15000;
        $campaign->teamEnable = true;
        $campaign->save();

        $this->childCampaign2 = $campaign;
    }

    private function sampleDonations(){
        $donations = [];
        $donations[] = [ 'nameFromForm' => 'Someone', 'email' => 'stas.msu@gmail.com', 'amount'=>320.00, 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Stanislav', 'email' => 'support@gmail.com', 'amount'=>150.25, 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Alex', 'email' => 'admin@gmail.com', 'amount'=>200.05, 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Dave', 'amount'=> 120.14];
        $donations[] = [ 'nameFromForm' => 'Crash', 'email' => 'someone@gmail.com', 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Noah', 'email' => 'noah@gmail.com', 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Nikki', 'email' => 'nikki@gmail.com', 'comments' => 'just some comments to test donations'];
        $donations[] = [ 'nameFromForm' => 'Yura', 'email' => 'yura@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'Hedgehog', 'email' => 'hedgehog@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'Sofia', 'email' => 'sofia@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'Sofia2', 'email' => 'sofia@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'Maria', 'email' => 'maria@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'George', 'email' => 'george@gmail.com'];
        $donations[] = [ 'nameFromForm' => 'Sofia', 'email' => 'sofia@gmail.com'];
        $donations[] = [ 'campaignId' => $this->childCampaign1->id, 'nameFromForm' => 'Sergey', 'email' => 'sergey@gmail.com'];
        $donations[] = [ 'campaignId' => $this->childCampaign1->id, 'nameFromForm' => 'Sergey2', 'email' => 'sergey2@gmail.com'];
        $donations[] = [ 'campaignId' => $this->childCampaign2->id, 'nameFromForm' => 'Sergey3', 'email' => 'sergey3@gmail.com'];
        $donations[] = [ 'campaignId' => $this->childCampaign2->id, 'nameFromForm' => 'Sergey4', 'email' => 'sergey4@gmail.com'];

        foreach ($donations as $donationArray){
            $donation = new Donation();
            $donation->campaignId = isset($donationArray['campaignId'])? $donationArray['campaignId'] : $this->campaign1->id;
            $donation->amount = isset($donationArray['amount']) ? $donationArray['amount'] : round(rand(1, 1000));
            $donation->createdDate = time();
            $donation->paymentDate = time();
            $donation->nameFromForm = isset($donationArray['nameFromForm']) ? $donationArray['nameFromForm'] : '';
            $donation->lastName = $donation->nameFromForm;
            $donation->email = isset($donationArray['email']) ? $donationArray['email'] : '';
            $donation->comments = isset($donationArray['comments'])? $donationArray['comments'] : '';
            $donation->save();
        }

        Campaign::updateDonationStatistics($this->campaign1->id);
        Campaign::updateDonationStatistics($this->childCampaign1->id);
        Campaign::updateDonationStatistics($this->childCampaign2->id);

        $user = User::find()->where(['name' => 'Admin'])->one();
    }

    private function sampleProAccounts(){
       $this->user1->prolong(\Yii::$app->params['yearSubscription']);
       $this->adminUser->prolong(\Yii::$app->params['monthSubscription']);
    }

    public function sampleStreamboard(){
        $regions = StreamboardRegion::GetRegions($this->user1);
        $region1 = $regions[0];
        $region2 = $regions[1];
     
        $region1->widgetType = StreamboardRegion::WIDGET_ALERTS;
        $region1->save();

        $region1->widgetAlerts->includeFollowers = true;
        $region1->widgetAlerts->includeSubscribers = true;
        $region1->widgetAlerts->includeDonations = true;
        $region1->widgetAlerts->animationDelaySeconds = 2;
        $region1->widgetAlerts->save();

        $region1->widgetAlerts->followersPreference->animationDuration = 10;
        $region1->widgetAlerts->followersPreference->fontSize = 46;
        $region1->widgetAlerts->followersPreference->fontStyle = 'Ubuntu';
        $region1->widgetAlerts->followersPreference->sound = '8-Bit-Arpeggio.mp3';
        $region1->widgetAlerts->followersPreference->soundType = WidgetAlertsPreference::FILE_TYPE_LIBRARY;
        $region1->widgetAlerts->followersPreference->image = 'Gnome300.gif';
        $region1->widgetAlerts->followersPreference->imageType = WidgetAlertsPreference::FILE_TYPE_LIBRARY;
        $region1->widgetAlerts->followersPreference->save();

        $region1->widgetAlerts->subscribersPreference->animationDuration = 5;
        $region1->widgetAlerts->subscribersPreference->fontSize = 30;
        $region1->widgetAlerts->subscribersPreference->fontStyle = 'Shadows Into Light';
        $region1->widgetAlerts->subscribersPreference->sound = 'Bonus-Coin-1.mp3';
        $region1->widgetAlerts->subscribersPreference->soundType = WidgetAlertsPreference::FILE_TYPE_LIBRARY;
        $region1->widgetAlerts->subscribersPreference->image = 'LeagueOfLegends300.gif';
        $region1->widgetAlerts->subscribersPreference->imageType = WidgetAlertsPreference::FILE_TYPE_LIBRARY;
        $region1->widgetAlerts->subscribersPreference->save();

        $region1->widgetAlerts->donationsPreference->animationDuration = 5;
        $region1->widgetAlerts->donationsPreference->fontSize = 16;
        $region1->widgetAlerts->donationsPreference->fontStyle = 'Lobster';
        $region1->widgetAlerts->donationsPreference->sound = 'Dark-Win.mp3';
        $region1->widgetAlerts->donationsPreference->soundType = WidgetAlertsPreference::FILE_TYPE_LIBRARY;
        $region1->widgetAlerts->donationsPreference->image = 'Mascot300.gif';
        $region1->widgetAlerts->donationsPreference->imageType = WidgetAlertsPreference::FILE_TYPE_LIBRARY;
        $region1->widgetAlerts->donationsPreference->save();

        $region2->save();
    }
}

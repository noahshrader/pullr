<?php

namespace frontend\controllers;

use common\components\message\ActivityMessage;
use common\models\base\BaseImage;
use common\models\FirstGiving;
use common\models\Campaign;
use frontend\models\site\ManualDonation;
use frontend\models\UploadCsv;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use common\components\Application;
use common\models\LayoutTeam;
use yii\web\Request;
use yii\web\Response;
use Yii;
use kartik\widgets\ActiveForm;
use common\components\UploadImage;
use common\models\Theme;
use common\models\Plan;
use common\models\Charity;
use common\models\User;
use common\models\CampaignInvite;
use common\models\mail\Mail;
use common\models\Donation;
use common\models\notifications\RecentActivityNotification;
use yii\web\UploadedFile;

class CampaignsController extends FrontendController {

    public function actionAdd() {
        $campaign = new Campaign();
        return $this->actionIndex($campaign);
    }

    public function actionEdit() {
        $campaign = $this->getCampaign();
        
        return $this->actionIndex($campaign, null, $campaign->status);
    }

    public function actionStatus() {
        $campaign = $this->getCampaign();
        $status = $_REQUEST['status'];
        $campaign->status = $status;
        $campaign->save();

        if (in_array($status, [Campaign::STATUS_PENDING, Campaign::STATUS_DELETED])){
            RecentActivityNotification::createNotification(
                \Yii::$app->user->id,
                ActivityMessage::messageCampaignEnded($campaign)
            );
        }

        $this->redirect('app/campaigns');
    }
    
    public function actionView(){
        $campaign = $this->getCampaign(true);
        
        return $this->actionIndex(null, $campaign, $campaign->status);
    }
    
    /**
     * 
     * @param $editCampaign Campaign - campaign to edit
     * @param $selectedCampaign Campaign  - campaign to view
     * @param $status string
     * @return type
     */
    public function actionIndex(Campaign $editCampaign = null, Campaign $selectedCampaign = null, $status = Campaign::STATUS_ACTIVE) {
        $isNewRecord = $editCampaign && $editCampaign->isNewRecord;

        //campaign save request
        if ($editCampaign && $editCampaign->load($_POST))
        {
            if (!empty($editCampaign->themeId) && !\Yii::$app->user->identity->hasAccessToTheme($editCampaign->themeId))
            {
                throw new Exception('You have no access to this theme');
            }

            if ($isNewRecord && !\Yii::$app->user->identity->canCreateMoreCampaigns())
            {
                throw new Exception('You have reached active campaigns limit');
            }

            //from html5 datetime-local tag to timestamp
            if ($editCampaign->startDate && !is_numeric($editCampaign->startDate)) {
                $editCampaign->startDate = (new \DateTime($editCampaign->startDate))->getTimestamp();
            }
            if ($editCampaign->endDate && !is_numeric($editCampaign->endDate)) {
                $editCampaign->endDate = (new \DateTime($editCampaign->endDate))->getTimestamp();
            }

            if (Yii::$app->request->getIsPost() && Yii::$app->request->post('firstgiving')) {

                $orgUuid = strip_tags(Yii::$app->request->post('firstgiving'));

                $firstGivingCharity = FirstGiving::getFromAPI($orgUuid);

                $editCampaign->charityId = $firstGivingCharity->charity->id;
            }

            if ($editCampaign->save()){
                UploadImage::UploadCampaignBackground($editCampaign);

                if ($isNewRecord) {
                    // dashboard "Campaign created" notification
                    RecentActivityNotification::createNotification(
                        \Yii::$app->user->id,
                        ActivityMessage::messageNewCampaign($editCampaign)
                    );

                    $this->redirect(['campaigns/edit', 'id' => $editCampaign->id]);
                }
            }
        }

        if ($editCampaign) {
            if (!$editCampaign->startDate) {
                $editCampaign->startDate = time();
            }
            if (!$editCampaign->endDate) {
                $editCampaign->endDate = time() + 60 * 60 * 24 * 4;
            }
            if (is_numeric($editCampaign->startDate)) {
                $editCampaign->startDate = strftime('%Y-%m-%dT%H:%M', $editCampaign->startDate);
            }
            if (is_numeric($editCampaign->endDate)) {
                $editCampaign->endDate = strftime('%Y-%m-%dT%H:%M', $editCampaign->endDate);
            }
        }

        $user = \Yii::$app->user->identity;
        $params = [];
        $params['editCampaign'] = $editCampaign;
        $params['campaigns'] = $user->getCampaigns($status)->orderBy('id DESC')->all();

        /* let's select first campaign when no campaign is selected*/
        if ( ($editCampaign == null) && ($selectedCampaign == null) && (sizeof($params['campaigns'])>0)){
            $selectedCampaign = $params['campaigns'][0];
        }
        if ($isNewRecord){
            $cloneCampaign = clone $editCampaign;
            $cloneCampaign->name = $cloneCampaign->name ? $cloneCampaign->name : 'New Campaign';
            array_unshift($params['campaigns'], $cloneCampaign);
        }
        
        $params['selectedCampaign'] = $selectedCampaign;
        $params['status'] = $status;
        
        /*         * from timestamp to html5 datetime-local tag */

        return $this->render('index', $params);
    }
    
    public function actionArchive(){
        return $this->actionIndex(null, null, Campaign::STATUS_PENDING);
    }
    
    public function actionTrash(){
        return $this->actionIndex(null, null, Campaign::STATUS_DELETED);
    }
    
    public function actionLayoutteams() {
        $id = $_REQUEST['id'];
        $teams = LayoutTeam::find()->where(['campaignId' => $id])->orderBy('date DESC')->all();

        $teamsOut = [];
        foreach ($teams as $team) {
            $teamsOut[] = $team->toArray();
        }

        return json_encode($teamsOut);
    }

    public function actionLayoutteamedit() {
        $id = $_REQUEST['id'];
        $layoutTeam = LayoutTeam::findOne($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_REQUEST['get']) && !isset($_REQUEST['save'])) {
            $layoutTeam->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($layoutTeam);
        }
        if ($layoutTeam->load($_POST) && $layoutTeam->save($_POST)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($layoutTeam);
        }
        $layout = Campaign::findOne($layoutTeam->campaignId);
        if ($layout->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            throw new \yii\web\ForbiddenHttpException();
        }
        return $this->renderAjax('layout-team-edit', ['layoutTeam' => $layoutTeam]);
    }

    public function actionLayoutteamremove() {
        $campaign = $this->getCampaign();
        $id = $campaign->id;

        $name = $_POST['name'];

        $campaignTeam = LayoutTeam::find()->where(['name' => $name, 'campaignId' => $id])->one();

        if ($campaignTeam) {
            $campaignTeam->delete();
        }
    }

    public function actionLayoutteamadd() {
        $campaign = $this->getCampaign();
        $id = $campaign->id;

        $name = $_POST['name'];

        $layoutTeam = new LayoutTeam();

        $layoutTeam->campaignId = $id;
        $layoutTeam->name = $name;

        $layoutTeam->save();
    }

    /**
     * get campaign and validate user has access to edit it.
     * @param boolean $childCampaignsAllowed if false ForbiddenHttpException will be throwed for campaign for which use is invited
     * true used when viewing campaign 
     * @return Campaign
     */
    public function getCampaign($childCampaignsAllowed = false) {
        $id = $_REQUEST['id'];
        $campaign = Campaign::findOne($id);

        if (!$campaign) {
            throw new NotFoundHttpException('Layout not found');
        }
        
        
        if ($campaign->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            $childFlag = false;
            if ($childCampaignsAllowed){
                $params = ['userId' => \Yii::$app->user->id, 'campaignId' => $campaign->id, 'status' => CampaignInvite::STATUS_ACTIVE];
                $childFlag = CampaignInvite::find()->where($params)->count() > 0;
            }
            
            if (!$childFlag){
                throw new \yii\web\ForbiddenHttpException();
            }
        }

        return $campaign;
    }

    public function actionGetcampaigninvites() {
        $campaign = $this->getCampaign();
        $invites = CampaignInvite::find()->where(['campaignId' => $campaign->id])
                        ->andWhere(['in', 'status', [CampaignInvite::STATUS_ACTIVE, CampaignInvite::STATUS_PENDIND]])->all();
        $invitesOut = [];
        foreach ($invites as $invite) {
            $inviteArray = $invite->toArray();
            $inviteArray['user'] = $invite->user->toArrayPrivate();
            $invitesOut[] = $inviteArray;
        }

        return json_encode($invitesOut);
    }

    public function actionCampaigninviteremove() {
        $campaign = $this->getCampaign();
        $userId = $_POST['userid'];
        $invite = CampaignInvite::findOne(['campaignId' => $campaign->id, 'userId' => $userId]);
        if ($invite) {
            $invite->status = CampaignInvite::STATUS_DELETED;
            $invite->lastChangeDate = time();
            $invite->save();
        }
    }

    /**
     * Find user by @email and invite him, if he is not still invited
     */
    public function actionCampaigninvite() {
        $campaign = $this->getCampaign();

        $uniqueName = $_POST['uniqueName'];

        $user = User::find()->where(['uniqueName' => $uniqueName])->andWhere(['not in', 'id', [\Yii::$app->user->id]])->one();

        if(isset($user))
        {
            CampaignInvite::addInvite($user->id, $campaign->id);

            if(!empty($user->email))
            {
                $content = $this->renderPartial('@console/views/mail/campaignInvite', [
                    'campaign' => $campaign,
                ]);

                Mail::sendMail($user->email, 'You was invited to fundraiser "'.$campaign->name.'"', $content, 'fundraiserInvite');
            }

            return 1;
        }

        return 0;
    }

    public function actionDefaulttheme()
    {
        $layoutType = $_POST['layoutType'];
        $plan = \Yii::$app->user->identity->getPlan();
        
        $themesQuery = Theme::find()->where(['status' => Theme::STATUS_ACTIVE, 'is_default' => Theme::THEME_IS_DEFAULT]);
        if ($plan == Plan::PLAN_BASE) {
            $themesQuery->andWhere(['plan' => Plan::PLAN_BASE]);
        }
        if ($layoutType) {
            $themesQuery->andWhere(['layoutType' => $layoutType]);
        }
        
        $theme = $themesQuery->one();
        
        return json_encode(array('id' => $theme->id, 'name' => $theme->name));
    }
    
    public function actionModalthemes() {
        $layoutType = $_POST['layoutType'];
        $plan = \Yii::$app->user->identity->getPlan();

        $themesQuery = Theme::find()->where(['status' => Theme::STATUS_ACTIVE])->orderBy('addedDate DESC');
        if ($plan == Plan::PLAN_BASE) {
            $themesQuery->andWhere(['plan' => Plan::PLAN_BASE]);
        }
        if ($layoutType) {
            $themesQuery->andWhere(['layoutType' => $layoutType]);
        }

        $themes = $themesQuery->all();
        return $this->renderPartial('modalThemes', [
                    'themes' => $themes, 'type' => $layoutType
        ]);
    }

    public function actionModalcharities() {
        $charities = Charity::findAll(['status' => Charity::STATUS_ACTIVE]);
        return $this->renderPartial('modalCharities', [
                    'charities' => $charities
        ]);
    }
    
    public function actionExportdonations(){
        $user = \Yii::$app->user->identity;
        if (isset($_REQUEST['id'])){
            $campaign = $this->getCampaign(true);
            $donations = $campaign->getDonations()->all();
        } else if (isset($_REQUEST['email'])) {
            $donations = Donation::findByEmailAndUserId($_REQUEST['email'], $user->id)->all();
        } else {
            throw new NotFoundHttpException();
        }
        
        $rows = [];
        $date = (new \DateTime())->setTimezone(new \DateTimeZone(Yii::$app->user->identity->getTimezone()));
        foreach ($donations as $donation){
            $rows[] = implode(',', [$donation->amount, $donation->name ? $donation->name : Donation::ANONYMOUS_NAME, $donation->email, $donation->comments, $date->setTimestamp($donation->paymentDate)->format('M j Y H:i:s')]);
        }
        // set the content type
        Header('Content-type: text/plain');
        // force save as dialog (and suggest filename)
        Header('Content-Disposition: attachment; filename="donations.csv"');
        // next echo the text
        echo implode(PHP_EOL, $rows);
        die;
    }

    public function actionImportdonations()
    {
        $model = new UploadCsv();

        if (Yii::$app->request->isPost) 
        {
            $model->load($_POST);
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) 
            {
                $fileName = \Yii::getAlias('@frontend') . '/web/usercsv/' . uniqid()  . '.' . $model->file->extension;
                $model->file->saveAs($fileName);
                Donation::importFromCsv($model->campaignId, $fileName);
                Campaign::updateDonationStatistics($model->campaignId);
                unlink($fileName);
                
                $this->redirect(["view", "id" => $model->campaignId]);
            }
        }
        
        throw new BadRequestHttpException();
    }

    /**
     * Create manual donation
     */
    public function actionManualdonation()
    {
        $manualDonation = new ManualDonation();

        if($manualDonation->load($_POST) && $manualDonation->validate())
        {
            $donation = new Donation();
            $donation->userId = \Yii::$app->user->id;
            $donation->createdDate = $donation->paymentDate = (new \DateTime($manualDonation->dateCreated))->getTimestamp();
            $donation->campaignId = $manualDonation->campaignId;
            $donation->amount = $manualDonation->amount;
            $donation->nameFromForm = $manualDonation->name;
            $donation->email = $manualDonation->email;
            $donation->comments = $manualDonation->comments;
            $donation->isManual = true;
            $donation->save();

            Campaign::updateDonationStatistics($donation->campaignId);

            // Dashboard "Donation received" notification
            RecentActivityNotification::createNotification($donation->campaign->userId, ActivityMessage::messageDonationReceived($donation));
            
            $this->redirect(["view", "id" => $donation->campaignId]);
        }
        
        throw new BadRequestHttpException();
    }

    /**
     * Remove user manual donation
     */
    public function actionDeletemanualdonation($donationId)
    {
        $donation = Donation::findOne(['id' => intval($donationId)]);
        if (!empty($donation) && ($donation->isManual == true) && (\Yii::$app->user->id == $donation->userId))
        {
            $donation->paymentDate = 0;
            $donation->save();
            
            Campaign::updateDonationStatistics($donation->campaignId);

            $this->redirect(["view", "id" => $donation->campaignId]);
        }

        throw new BadRequestHttpException();
    }

    /**
     * Removes campaign bg image
     * Called by Ajax
     *
     * @return bool
     * @throws BadRequestHttpException
     * @throws \Exception
     */
    public function actionBgdelete()
    {
        if (!Yii::$app->getRequest()->isAjax)
        {
            throw new BadRequestHttpException('Ajax only');
        }

        $campaignId = intval(Yii::$app->getRequest()->post('campaignId', 0));
        $campaign = Campaign::findOne(['id' => intval($campaignId)]);

        if (isset($campaign))
        {
            if (\Yii::$app->user->id !== $campaign->userId)
            {
                throw new BadRequestHttpException('You can alter campaigns you own');
            }

            if (!empty($campaign->backgroundImageId))
            {
                Yii::$app->db->transaction(function() use ($campaign)
                {
                    $baseImage = BaseImage::findOne(['id' => $campaign->backgroundImageId]);
                    if (isset($baseImage))
                    {
                        $baseImage->status = 'deleted';
                        $baseImage->save();
                    }

                    $campaign->backgroundImageId = null;
                    $campaign->save();
                });

                return true;
            }
        }

        return false;
    }
}

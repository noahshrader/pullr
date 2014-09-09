describe('Streamboard', function () {
    var $httpBackend = null,
        $interval = null;

    beforeEach(function () {
        module('pullr.streamboard.regions');
        module('pullr.streamboard.donations');
    })
    beforeEach(inject(function (_$httpBackend_, _$interval_) {
        $httpBackend = _$httpBackend_;
        $interval = _$interval_;
    }));

    afterEach(function() {
        $httpBackend.verifyNoOutstandingExpectation();
        $httpBackend.verifyNoOutstandingRequest();
    });

    describe('donationsService', function () {
        function loadSampleDonations(){
            var fixtures = loadJSONFixtures('donations.json');
            var data = fixtures['donations.json'];
            $httpBackend.expectGET('app/streamboard/get_donations_ajax?since_id=0').respond(data);
            $httpBackend.flush();
            return data;
        }
        it('Should request donations every 1 second', inject(function (donations) {
            var data = loadSampleDonations();

            expect(donations.stats).toBeDefined();
            expect(donations.donations).toBeDefined();
            expect(donations.lastDonationId).toBeDefined();
            var length = donations.donations.length ;
            var lastDonationId = donations.lastDonationId;
            expect(length).toBeGreaterThan(10);
            expect(lastDonationId).toBeGreaterThan(10);

            $httpBackend.expectGET('app/streamboard/get_donations_ajax?since_id='+lastDonationId).respond(data);
            $interval.flush(1000);
            $httpBackend.flush();

            expect(donations.donations.length).toBe(length);
            expect(donations.lastDonationId).toBe(lastDonationId);
        }))
        it('Should clear donations', inject(function(donations){
            loadSampleDonations();
            donations.clear();
            expect(donations.donations.length).toBe(0);
        }));
    });

    describe("regionsService", function () {
        describe('General variables check', function () {
            window.Pullr = window.Pullr || {};
            window.Pullr.twitchClientId = "l7mj3pfjvxpk2zv6ivr9jpisodqd5h0";
            window.Pullr.ANONYMOUS_NAME = "Anonymous";
            Pullr.ENV = "dev";
            Pullr.params = {"googleAPIKey": "AIzaSyBCaACEmXOZ9F2u9DF9O-U-1-_BmTfNQfE"};
            window.Pullr.user = {"id": 1, "name": "Klyukin", "uniqueName": "klyukin", "photo": "http:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/klyukin-profile_image-c5ca1ccc61c3c330-300x300.jpeg", "smallPhoto": "http:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/klyukin-profile_image-c5ca1ccc61c3c330-300x300.jpeg", "userFields": {"twitchPartner": 0, "twitchChannel": "supermcgamer"}};
            window.Pullr.Streamboard = {"AlertMediaManager": {"customImages": ["Gnome300.gif", "Pokemon300.gif"], "customSounds": ["Adventure time(Finn and Jake).mp3", "DJ Pechkin - Perfect Sound - F.mp3"], "libraryImages": ["Gnome300.gif", "LeagueOfLegends300.gif", "LegendOfZelda300.gif", "Mario300.gif", "Mascot300.gif", "Minecraft300.gif", "Pokemon300.gif", "Surprise300.gif", "TeamFortress2300.gif", "WorldOfWarcraft300.gif"], "librarySounds": ["8-Bit-Arpeggio.mp3", "Bonus-Coin-1.mp3", "Bonus-Coin-2.mp3", "Bonus-Coin-3.mp3", "Checkpoint.mp3", "Coin-1.mp3", "Coin-6.mp3", "Correct-Answer.mp3", "Crystal-Grab-Bonus.mp3", "Danger.mp3", "Dark-Win.mp3", "Elegant.mp3", "Fanfare.mp3", "Game-Impact.mp3", "Game-Result.mp3", "Glitter-Ding.mp3", "Gong-Hit.mp3", "Heavy-Bell-Impact.mp3", "Hi-Score.mp3", "Hit-8.mp3", "Level-Completed.mp3", "Light-Darkness.mp3", "Magical-Disappearance.mp3", "Secret-Area.mp3", "Small-Bonus.mp3", "Sparkle-Swell.mp3"], "PATH_TO_LIBRARY_SOUNDS": "streamboard\/alerts\/sounds\/", "PATH_TO_LIBRARY_IMAGES": "streamboard\/alerts\/images\/", "PATH_TO_CUSTOM_SOUNDS": "usermedia\/streamboard\/alerts\/sounds\/", "PATH_TO_CUSTOM_IMAGES": "usermedia\/streamboard\/alerts\/images\/"}};

            it('Pullr variable should be defined', function () {
                expect(window.Pullr).toBeDefined();
            });

            it('Pullr.user should be defined', function () {
                expect(window.Pullr.user).toBeDefined();
            });

            it('Pullr.user.name should be defined', function () {
                expect(window.Pullr.user.name).toBe('Klyukin');
            });

            it('Pullr.Streamboard should be defined', function () {
                expect(window.Pullr.Streamboard).toBeDefined();
            });

        });
        var REGIONS = [
            {"userId": 1, "regionNumber": "1", "backgroundColor": "#b97575", "widgetType": "widget_alerts", "widgetAlerts": {"userId": 1, "regionNumber": "1", "includeFollowers": true, "includeSubscribers": true, "includeDonations": true, "animationDelaySeconds": 2, "followersPreference": {"userId": 1, "regionNumber": "1", "preferenceType": "followers", "fontStyle": "Play", "fontSize": 46, "fontColor": "#ff0000", "animationDuration": 10, "volume": 15, "sound": "8-Bit-Arpeggio.mp3", "soundType": "Library", "image": "Gnome300.gif", "imageType": "Library"}, "subscribersPreference": {"userId": 1, "regionNumber": "1", "preferenceType": "subscribers", "fontStyle": "Shadows Into Light", "fontSize": 30, "fontColor": "#00ff00", "animationDuration": 5, "volume": 20, "sound": "Bonus-Coin-1.mp3", "soundType": "Library", "image": "LeagueOfLegends300.gif", "imageType": "Library"}, "donationsPreference": {"userId": 1, "regionNumber": "1", "preferenceType": "donations", "fontStyle": "Lobster", "fontSize": 16, "fontColor": "#6fc887", "animationDuration": 5, "volume": 9, "sound": "Correct-Answer.mp3", "soundType": "Library", "image": "Mascot300.gif", "imageType": "Library"}}, "widgetCampaignBar": {"campaignId": 4, "fontStyle": "", "fontSize": "10", "fontColor": "", "backgroundColor": "", "alertsEnable": false, "messagesEnable": false, "timerEnable": false, "progressBarEnable": false, "alertsModule": {"userId": 1, "regionNumber": "1", "preferenceType": null, "includeFollowers": false, "includeSubscribers": false, "includeDonations": false, "fontStyle": "", "fontSize": 10, "fontColor": "", "backgroundColor": "", "animationDirection": "", "animationDuration": 1, "animationDelay": 0, "volume": 0}, "messagesModule": {"userId": 1, "regionNumber": 1, "message1": "", "message2": "", "message3": "", "message4": "", "message5": "", "rotationSpeed": 1}, "timerModule": {"userId": 1, "regionNumber": 1, "timerType": null, "countDownFrom": 0, "countDownTo": 0, "countUpStartTime": 0, "countUpPauseTime": 0, "countUpStatus": 0}}, "widgetDonationFeed": {"userId": 1, "regionNumber": 1, "noDonationMessage": "some message here", "fontStyle": "", "fontSize": "10", "fontColor": "", "scrolling": true, "scrollSpeed": "Normal"}},
            {"userId": 1, "regionNumber": "2", "backgroundColor": "#80a99e", "widgetType": "widget_campaign_bar", "widgetAlerts": {"userId": 1, "regionNumber": "2", "includeFollowers": false, "includeSubscribers": false, "includeDonations": false, "animationDelaySeconds": 0, "followersPreference": {"userId": 1, "regionNumber": "2", "preferenceType": "followers", "fontStyle": "", "fontSize": 0, "fontColor": "", "animationDuration": 0, "volume": 100, "sound": "", "soundType": null, "image": "", "imageType": null}, "subscribersPreference": {"userId": 1, "regionNumber": "2", "preferenceType": "subscribers", "fontStyle": "", "fontSize": 0, "fontColor": "", "animationDuration": 0, "volume": 100, "sound": "", "soundType": null, "image": "", "imageType": null}, "donationsPreference": {"userId": 1, "regionNumber": "2", "preferenceType": "donations", "fontStyle": "", "fontSize": 0, "fontColor": "", "animationDuration": 0, "volume": 100, "sound": "", "soundType": null, "image": "", "imageType": null}}, "widgetCampaignBar": {"campaignId": 1, "fontStyle": "", "fontSize": "10", "fontColor": "", "backgroundColor": "", "alertsEnable": false, "messagesEnable": false, "timerEnable": false, "progressBarEnable": false, "alertsModule": {"userId": 1, "regionNumber": "2", "preferenceType": null, "includeFollowers": false, "includeSubscribers": false, "includeDonations": false, "fontStyle": "", "fontSize": 10, "fontColor": "", "backgroundColor": "", "animationDirection": "", "animationDuration": 1, "animationDelay": 0, "volume": 0}, "messagesModule": {"userId": 1, "regionNumber": 2, "message1": "", "message2": "", "message3": "", "message4": "", "message5": "", "rotationSpeed": 1}, "timerModule": {"userId": 1, "regionNumber": 2, "timerType": null, "countDownFrom": 0, "countDownTo": 0, "countUpStartTime": 0, "countUpPauseTime": 0, "countUpStatus": 0}}, "widgetDonationFeed": {"userId": 1, "regionNumber": 2, "noDonationMessage": "", "fontStyle": "", "fontSize": "", "fontColor": "", "scrolling": false, "scrollSpeed": "Slow"}}
        ];

        it('Should be requested', inject(function (regions) {
            $httpBackend.expectGET('app/streamboard/get_regions_ajax').respond(REGIONS);
            $httpBackend.flush();
            expect(regions.regions.length).toBe(2);
        }));
    });
});

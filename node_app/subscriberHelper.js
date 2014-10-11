var helper = require('./followerHelper.js');
var mysql = require('mysql');
var q = require('q');
var config = require('./config.json');
var SubscriberHelper = function(){
	helper.FollowerHelper.call(this);
}
var TWITCH_API_URL = 'https://api.twitch.tv/kraken';
var request = require('request');
var connection = mysql.createConnection({
	host : config.db.host,
	user : config.db.user,
	password : config.db.password,
	database: config.db.database
});



SubscriberHelper.prototype = new helper.FollowerHelper();
SubscriberHelper.prototype.constructor = SubscriberHelper;

SubscriberHelper.prototype.tableName = 'tbl_twitch_subscriptions';
SubscriberHelper.prototype.message = '%s just subscribed to your channel %s!';
SubscriberHelper.prototype.command = 'subscriptions';

SubscriberHelper.prototype.getApiLink = function(user, channelName){
	
	if (user.twitchPartner == 1 && user.twitchAccessToken != '') {

		var url = TWITCH_API_URL + '/channels/' + channelName + '/subscriptions?limit=100';		
		url += '&client_id=' + config.twitch.clientId;
		url += '&oauth_token=' + user.twitchAccessToken;
		return url;
	} else {
		console.log('User must be twitch partner to update subscribers');
		return false;
	}
	
}

SubscriberHelper.prototype.updateSubscriberByChannelName = function (user, channelName, savedFollowers) {	
	
	var userId = user.id;
	var _this = this;
	var url = _this.getApiLink(user, channelName);
	if (url == false) {
		return false;
	}
	var total = 0;
	var options = {
		url: url,
		headers: {
			'Client-ID': config.twitch.clientId
		}
	}

	var newUpdateTime = Math.round(new Date().getTime() / 1000);
	var finalCallback = function() {		
		_this.updateCurrentFollower(_this.updateIds, userId, newUpdateTime).then(function(){
			_this.deleteUnfollowUser(userId, newUpdateTime);	
		});
		
	}
	console.log(options);	
	request(options, function(error, response, body) {
		console.log(response);
		if ( ! error && response.statusCode == 200) {					
			body = JSON.parse(body);			
			total = body._total;
			console.log('Total followers: ', total);
			var count = body.subscriptions.length;
			if (count == 0 ) {
				_this.deleteUnfollowUser();
			}
			_this.pendingFollowerCountdown = total;
			//save to database		
			_this.createNotification(userId, channelName, body.subscriptions, savedFollowers);
			_this.saveNewFollowers(userId, body.subscriptions, savedFollowers, finalCallback);
			
			while (count < total) {				
				var nextUrl = url + '&offset=' + count;
				options.url = nextUrl;
				request(options, function(error, response, body) {
					if ( ! error && response.statusCode == 200) {
						var body = JSON.parse(body);																		
						_this.createNotification(userId, channelName, body.subscriptions, savedFollowers);
						_this.saveNewFollowers(userId, body.subscriptions, savedFollowers, finalCallback)					
						
					}
				});
				count += 100;
			}
			
		} else {
			console.log('Error while get followers...')
		}
	});	
};

function updateSubscribers() {	
	var subscriberHelper = new SubscriberHelper();
	subscriberHelper.getOnlineUser().then(function(onlineUserIds) {			
		for (var i=0; i < onlineUserIds.length; i++) {
			var user = onlineUserIds[i];
			console.log('---- Update for user ', user.name);
			subscriberHelper.getFollowersFromDb(user).then(function(results) {					
				subscriberHelper.updateSubscriberByChannelName(results.user, results.user.twitchChannel, results.savedFollowers);	
			});
		}
	});	
}

exports.updateSubscribers = updateSubscribers;

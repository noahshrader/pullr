var helper = require('./followerHelper.js');
var mysql = require('mysql');
var q = require('q');
var config = require('./config.json');
var SubscriberHelper = function(user){
	helper.FollowerHelper.call(this);
	this.user = user;
}
var TWITCH_API_URL = 'https://api.twitch.tv/kraken';
var request = require('request');
var connection = mysql.createConnection({
	host : config.db.host,
	user : config.db.user,
	password : config.db.password,
	database: config.db.database
});

connection.connect(function(err) {
	if (err) {
		console.log("Can't connect to database.");
	}
});


function getOnlineUser() {
	var time = 10 * 60;
	var deferred = q.defer();
	//get online user in the last 5 minutes
	connection.query('select u.*, uf.* from tbl_user u join tbl_user_fields uf on u.id = uf.userId where uf.twitchChannel != "" and uf.twitchChannel is not NULL and UNIX_TIMESTAMP() - last_login <= ?', [time], function(err, results){
		if ( ! err) {			
			deferred.resolve(results);
		} else {
			deferred.reject(new Error("Can't select online user"));
		}
	});
	return deferred.promise;
}

SubscriberHelper.prototype = new helper.FollowerHelper();
//SubscriberHelper.prototype.constructor = SubscriberHelper;

SubscriberHelper.prototype.checkCanPostNotification = function() {
	var deferred = q.defer();
	var _this = this;
	connection.query('select newSubscriber from ' + _this.notificationTable + ' where userId = ?', [this.user.id], function(err, result){
		if ( !err  && result.length > 0) {
			
			if (result[0].newSubscriber == 1) {

				_this.canPostNotification = true;				
			} else {
				_this.canPostNotification = false;
			}
			deferred.resolve(true);
		}
	});
	return deferred.promise;
}

SubscriberHelper.prototype.notificationTable = 'tbl_notification';
SubscriberHelper.prototype.tableName = 'tbl_twitch_subscriptions';
SubscriberHelper.prototype.message = '%s just subscribed to your channel %s!';
SubscriberHelper.prototype.command = 'subscriptions';

SubscriberHelper.prototype.getApiLink = function(){

	var _this = this;
	if (_this.user.twitchPartner == 1 && _this.user.twitchAccessToken != '') {

		var url = TWITCH_API_URL + '/channels/' + _this.user.twitchChannel + '/subscriptions?limit=100';		
		url += '&client_id=' + config.twitch.clientId;
		url += '&oauth_token=' + _this.user.twitchAccessToken;
		return url;
	} else {
		console.log('User must be twitch partner to update subscribers');
		return false;
	}
	
};

SubscriberHelper.prototype.updateTotalNumber = function(number) {
	var sql = 'insert into tbl_twitch_user(userId, subscribersNumber) values(?, ?) on duplicate key update subscribersNumber = ?';
	connection.query(sql, [this.user.id, number, number], function(err){
		if ( err ) {
			console.log('Error update total subscriber numbers');
		}
	});
};

SubscriberHelper.prototype.requestSubscribersAndUpdate = function () {	

	var _this = this;
	var userId = _this.user.id;
	
	var url = _this.getApiLink();
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
	
	console.log(options);	
	request(options, function(error, response, body) {
		
		if ( ! error && response.statusCode == 200) {					
			body = JSON.parse(body);			

			total = body._total;
			console.log('Total subscribers: ', total);
			var count = body.subscriptions.length;
			_this.insertIds = [];
			
			if (count == 0 ) {
				_this.deleteUnfollowUser();
			}

			_this.pendingFollowerCountdown = total - _this.savedFollowers.length;
			if ( _this.pendingFollowerCountdown == 0 ) {
				console.log('No different between api and database, exit... ');
				return false;
			}				
			
			_this.saveNewFollowers(body.subscriptions);
			_this.updateTotalNumber(total);

			while (count < total) {				
				var nextUrl = url + '&offset=' + count;
				options.url = nextUrl;
				request(options, function(error, response, body) {
					if ( ! error && response.statusCode == 200) {
						var body = JSON.parse(body);																		
						_this.saveNewFollowers(body.subscriptions);
					}
				});
				count += 100;
			}
			
		} else {
			console.log('Error while get subscribers from API...')
		}
	});	
};

SubscriberHelper.prototype.updateSubscribers = function(){
	var _this = this;

	_this.checkCanPostNotification().then(function(){
		_this.getFollowersFromDb().then(function() {		
			console.log('User: ', _this.user.name , ' has: ', _this.savedFollowers.length, ' subscribers');		
			_this.requestSubscribersAndUpdate();														
		});
	})
}
function updateSubscribers() {		
	getOnlineUser().then(function(onlineUserIds) {			
		for (var i=0; i < onlineUserIds.length; i++) {
			var user = onlineUserIds[i];			
			console.log('Starting update subscribers for user: ', user.name);
			var subscriberHelper = new SubscriberHelper(user);		
			subscriberHelper.updateSubscribers();			
		}
	});	
}

exports.updateSubscribers = updateSubscribers;

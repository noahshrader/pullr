var util = require('util');
var mysql = require('mysql');
var q = require('q');
var config = require('./config.json');
var connection = mysql.createConnection({
	host : config.db.host,
	user : config.db.user,
	password : config.db.password,
	database: config.db.database
});
var request = require('request');
var TWITCH_API_URL = 'https://api.twitch.tv/kraken';

var FollowerHelper = function(){
	
}

FollowerHelper.prototype.pendingFollowerCountdown = 0;
FollowerHelper.prototype.updateIds = [];
FollowerHelper.prototype.tableName = 'tbl_twitch_follow';
FollowerHelper.prototype.message = '%s just followed your channel %s !';

FollowerHelper.prototype.getApiLink = function(user, channelName){
	console.log(user.name);
	return TWITCH_API_URL + '/channels/' + channelName + '/follows?limit=100';	
}

FollowerHelper.prototype.getOnlineUser = function() {		
	var time = 5 * 60;
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


FollowerHelper.prototype.getFollowersFromDb = function (user) {
	var deferred = q.defer();			
	connection.query('select twitchUserId from ' + this.tableName + ' where userId = ? order by createdAt desc',[user.id] , function(error, results){
		if ( ! error ){			
			var savedFollowers = [];
			for (i=0;i<results.length;i++) {
				savedFollowers.push(results[i].twitchUserId);
			}
			deferred.resolve({
				user: user,
				savedFollowers: savedFollowers
			});
		} else {
			deferred.reject('error get followers from db')
		}
	});
	return deferred.promise;
};

FollowerHelper.prototype.saveNewFollowers = function(userId, followers, savedFollowers, finalCallback) {
	var _this = this;
	finalCallback = finalCallback || function(){};
	var ids = [];
	var batchs = [];

	for (i=0 ; i<followers.length ; i++) {
		var follower = followers[i];

		if (savedFollowers.indexOf(follower.user._id) < 0) {
			
			var updateDate = Math.round(new Date().getTime() / 1000);
			
			var createdAt = new Date(follower.created_at).getTime();
			if ( ! isNaN(createdAt) && createdAt != null) {
				createdAt = createdAt / 1000;
			}
			var fields = ['userId', 'twitchUserId', 'createdAt', 'name', 'display_name', 'jsonResponse', 'updateDate', 'createdAtPullr'];
			var row = {
				userId: userId, 
				twitchUserId: follower.user._id, 
				createdAt: createdAt,
				name: follower.user.name, 
				display_name: follower.user.display_name,			
				jsonResponse: JSON.stringify(follower), 
				updateDate: updateDate,
				createdAtPullr: updateDate
			};

			connection.query('INSERT INTO ' + _this.tableName + ' SET ?', row, function(err, result) {
				if ( ! err ) {
					console.log('Save 1 follower.');
					
					_this.pendingFollowerCountdown--;
					if (_this.pendingFollowerCountdown == 0) {
						finalCallback();
					}
				}
			});		
		} else {
			_this.updateIds.push(follower.user._id);
			_this.pendingFollowerCountdown--;
			if (_this.pendingFollowerCountdown == 0) {
				finalCallback();
			}
		}
	}
};

FollowerHelper.prototype.createNotification = function (userId, channelName, followers, savedFollowers) {
	for (i=0 ; i<followers.length ; i++) {
		var follower = followers[i];
		if (savedFollowers.indexOf(follower.user._id) < 0) {			
			var message = util.format(this.message, follower.user.display_name, channelName);				
			var row = {
				userId: userId,
				message: message,
				date: new Date().getTime()
			}
			connection.query('INSERT INTO tbl_notification_recent_activity SET ? ', row, function(err, result){
				if ( ! err ) {
					console.log('Create notification for follower')
				}
			});
		}
	}
}

FollowerHelper.prototype.updateFollowersByChannelName = function (user, channelName, savedFollowers) {	
	
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
		console.log('Final callback')
		_this.updateCurrentFollower(_this.updateIds, userId, newUpdateTime).then(function(){
			_this.deleteUnfollowUser(userId, newUpdateTime);	
		});
		
	}
	console.log(options);
	request(options, function(error, response, body) {
		if ( ! error && response.statusCode == 200) {					
			body = JSON.parse(body);
			total = body._total;
			console.log('Total followers: ', total);
			var count = body.follows.length;
			_this.pendingFollowerCountdown = total;
			//save to database		
			_this.createNotification(userId, channelName, body.follows, savedFollowers);
			_this.saveNewFollowers(userId, body.follows, savedFollowers, finalCallback);
			
			while (count < total) {				
				var nextUrl = url + '&offset=' + count;
				options.url = nextUrl;
				request(options, function(error, response, body) {
					if ( ! error && response.statusCode == 200) {
						var body = JSON.parse(body);																		
						_this.createNotification(userId, channelName, body.follows, savedFollowers);
						_this.saveNewFollowers(userId, body.follows, savedFollowers, finalCallback)					
						
					}
				});
				count += 100;
			}
			
		} else {
			console.log('Error while get followers...')
		}
	});	
};

FollowerHelper.prototype.updateCurrentFollower = function(ids, userId, time) {
	console.log('Update time of current follower');
	var deferred = q.defer();
	if (ids.length > 0) {
		var userString = '(' + ids.join(',') + ')';

		connection.query('update ' + this.tableName + ' set updateDate = ? where userId = ? and twitchUserId in ' + userString, [time, userId], function(err, result){
			if ( ! err) {
				deferred.resolve(true);
			}				
		});
	} else {
		deferred.resolve(true);
	}
	return deferred.promise;
};

FollowerHelper.prototype.deleteUnfollowUser = function(userId, time) {
	console.log('Delete unfollow user');

	connection.query('delete from ' + this.tableName + ' where userId = ? and updateDate <  ?' ,[userId, time], function(){

	});
};

function updateFollowers() {	
	var followerHelper = new FollowerHelper();
	followerHelper.getOnlineUser().then(function(onlineUserIds) {			
		for (var i=0; i < onlineUserIds.length; i++) {
			var user = onlineUserIds[i];
			console.log('---- Update for user ', user.name);
			followerHelper.getFollowersFromDb(user).then(function(results) {		
						
				followerHelper.updateFollowersByChannelName(results.user, results.user.twitchChannel, results.savedFollowers);		
								
				
			});
		}
	});	
}

exports.updateFollowers = updateFollowers;


exports.FollowerHelper = FollowerHelper;



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

connection.connect(function(err) {
	if (err) {
		console.log('error connect database');
	}
});

var request = require('request');

var TWITCH_API_URL = 'https://api.twitch.tv/kraken';


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

var FollowerHelper = function(user) {
	this.user = user;
	this.connection = mysql.createConnection({
		host : config.db.host,
		user : config.db.user,
		password : config.db.password,
		database: config.db.database
	});
	this.connection.connect(function(err) {
		if (err) {
			throw new Error("Can't connect to database");
		}
	});
}

FollowerHelper.prototype.connection = null;
FollowerHelper.prototype.user = null;
FollowerHelper.prototype.insertIds = [];
FollowerHelper.prototype.pendingFollowerCountdown = 0;
FollowerHelper.prototype.updateIds = [];
FollowerHelper.prototype.tableName = 'tbl_twitch_follow';
FollowerHelper.prototype.message = '%s just followed your channel %s !';
FollowerHelper.prototype.savedFollowers = [];
FollowerHelper.prototype.notificationTable = 'tbl_notification';
FollowerHelper.prototype.canPostNotification = false;
FollowerHelper.prototype.diffCount = 0;
FollowerHelper.prototype.getApiLink = function() {	
	return TWITCH_API_URL + '/channels/' + this.user.twitchChannel + '/follows?limit=100';	
}



FollowerHelper.prototype.checkCanPostNotification = function() {
	var deferred = q.defer();
	var _this = this;
	connection.query('select newFollower from ' + _this.notificationTable + ' where userId = ?', [this.user.id], function(err, result){
		if ( !err  && result.length > 0) {
			
			if (result[0].newFollower == 1) {

				_this.canPostNotification = true;				
			} else {
				_this.canPostNotification = false;
			}

			deferred.resolve(true);
		}
	});
	return deferred.promise;
}

FollowerHelper.prototype.getFollowersFromDb = function () {
	var _this = this;
	var deferred = q.defer();			
	connection.query('select twitchUserId from ' + this.tableName + ' where userId = ? order by createdAt desc',[_this.user.id] , function(error, results){
		if ( ! error ){			
			var savedFollowers = [];
			for (i=0;i<results.length;i++) {
				savedFollowers.push(results[i].twitchUserId);
			}
			_this.savedFollowers = savedFollowers;
			deferred.resolve(savedFollowers);
		} else {
			deferred.reject('error get data from db')
		}
	});
	return deferred.promise;
};

FollowerHelper.prototype.updateTotalNumber = function(number) {
	var sql = 'insert into tbl_twitch_user(userId, followersNumber) values(?, ?) on DUPLICATE key update followersNumber = ?';
	connection.query(sql, [this.user.id, number, number], function(err){
		if ( err ) {
			console.log('Error update total follower numbers');
			console.log(err);
		}
	});
};

FollowerHelper.prototype.saveNewFollowers = function(followers) {
	var _this = this;
	var ids = [];
	var batchs = [];
	var notificationsData = [];
	for (i=0 ; i<followers.length ; i++) {
		var follower = followers[i];

		if (_this.savedFollowers.indexOf(follower.user._id) < 0) {
			
			var updateDate = Math.round(new Date().getTime() / 1000);
			
			var createdAt = new Date(follower.created_at).getTime();
			if ( ! isNaN(createdAt) && createdAt != null) {
				createdAt = createdAt / 1000;
			}
			var fields = ['userId', 'twitchUserId', 'createdAt', 'name', 'display_name', 'jsonResponse', 'updateDate', 'createdAtPullr'];
			var row = [
				_this.user.id, 
				follower.user._id, 
				createdAt,
				follower.user.name, 
				follower.user.display_name,			
				JSON.stringify(follower), 
				updateDate,
				updateDate
			];
			batchs.push(row);
			notificationsData.push(follower.user.display_name);
			
			_this.savedFollowers.push(follower.user._id);
			_this.insertIds.push(follower.user._id);
			
			
		} 
	}	

	if ( batchs.length ) {
		connection.query('INSERT INTO ' + _this.tableName + ' (userId, twitchUserId, createdAt, `name`, display_name, jsonResponse, updateDate, createdAtPullr) VALUES ?', [batchs], function(err, result) {
		
			if ( ! err ) {
				if ( _this.canPostNotification ) {
					_this.createNotification(notificationsData);
				}
				_this.pendingFollowerCountdown -= batchs.length;
				//console.log('Save 1 follower. Left: ', _this.pendingFollowerCountdown);
									
				if (_this.pendingFollowerCountdown <= 0) {
					_this.finalCallback();
				}
			} else {
				console.log(err, result)
			}
		});			
	}

	
};

FollowerHelper.prototype.createNotification = function (displayNames) {
	
	var _this = this;
	var rows = [];
	for (var i=0; i< displayNames.length; i++) {
		var message = util.format(_this.message, displayNames[i], _this.user.twitchChannel);				
		var date = new Date().getTime() / 1000;
		var row = [
			_this.user.id,
			message,
			date
		];
		rows.push(row);
	}

	if (rows.length > 0) {
		connection.query('INSERT INTO tbl_notification_recent_activity(userId, message, `date`) VALUES ? ', [rows], function(err, result){
			
			if ( ! err ) {
				console.log(err);
			} else {
				console.log('success createNotification');
			}
		});	
	}
	

}

FollowerHelper.prototype.requestFollowersAndUpdate = function () {	
	var userId = this.user.id;
	var _this = this;

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
			console.log('API request return ', total, ' follower for user ', _this.user.name);
			var count = body.follows.length;
			_this.insertIds = [];
			if (count == 0 ) {
				_this.deleteUnfollowUser();
			}
			_this.pendingFollowerCountdown = total - _this.savedFollowers.length;
			if ( _this.pendingFollowerCountdown == 0 ) {
				console.log('No different between api and database, exit... ');
				return false;
			}				
			//save to database					
			_this.saveNewFollowers(body.follows);
			_this.updateTotalNumber(total);
			while (count < total) {				
				var nextUrl = url + '&offset=' + count;
				options.url = nextUrl;
				request(options, function(error, response, body) {
					if ( ! error && response.statusCode == 200) {
						var body = JSON.parse(body);																				
						_this.saveNewFollowers(body.follows)					
						
					} else {
						console.log('[1] Error while get followers...')			
						console.log(error, response);
					}
				});
				count += 100;
			}
			
		} else {
			console.log('[2] Error while get followers...')
			console.log(error, response);
		}
	});	
};

FollowerHelper.prototype.finalCallback = function(){
	console.log('Final callback')
	this.deleteUnfollowUser();	
}

FollowerHelper.prototype.arrayDiff = function(array1, array2) {
	var result = [];
	for (i=0; i<array1.length; i++) {
		var value = array1[i];
		if ( -1 == array2.indexOf(value)) {
			result.push(value);
		}
	}
	return result;
}

FollowerHelper.prototype.deleteUnfollowUser = function() {
	console.log('Delete unfollow/unsubscribe user');
	var _this = this;
	var ids = this.arrayDiff(_this.savedFollowers, _this.insertIds);
	if(ids.length > 0){
		var idString = '(' + ids.join(',') + ')';
		connection.query('delete from ' + this.tableName + ' where userId = ? and twitchUserId in ' + idString ,[_this.user.id], function(err) {
			if ( ! err) {
				console.log('Done deleting for user ', _this.user.name);
			} else {
				console.log('Error while delete data for ', _this.user.name);
			}
		});
	} else {
		console.log('Done deleting for user ', _this.user.name);
	}	
};

FollowerHelper.prototype.updateFollowersForUser = function() {
	var _this = this;
	this.checkCanPostNotification().then(function() {
		_this.getFollowersFromDb().then(function() {		
			console.log('User: ', _this.user.name , ' has: ', _this.savedFollowers.length, ' followers');		
			_this.requestFollowersAndUpdate();														
		});
	});
}

function updateFollowers() {

	getOnlineUser().then(function(onlineUserIds) {			
		
		for (var i=0; i < onlineUserIds.length; i++) {
			var user = onlineUserIds[i];			
			console.log('Starting update follower for user: ', user.name);
			var followerHelper = new FollowerHelper(user);		
			followerHelper.updateFollowersForUser();			
		}
	});	
}

exports.updateFollowers = updateFollowers;
exports.getOnlineUser = getOnlineUser();
exports.FollowerHelper = FollowerHelper;



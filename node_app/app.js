var fs = require('fs');
if (false == fs.existsSync( __dirname + '/config.json')) {
    console.log('Please copy config.json.dist to config.json and replace content with correct settings');
    return;
}

var followerHelper = require('./followerHelper.js');
var subscriberHelper = require('./subscriberHelper.js');




var timeoutTime = 10 * 60 * 1000;
function run() {
	//followerHelper.updateFollowers();
	subscriberHelper.updateSubscribers();
	setTimeout(function(){
		followerHelper.updateFollowers();
		subscriberHelper.updateSubscribers();
	}, timeoutTime);	
}

run();
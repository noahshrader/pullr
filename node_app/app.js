var followerHelper = require('./followerHelper.js');
var subscriberHelper = require('./subscriberHelper.js');


var timeoutTime = 10 * 60 * 1000;
function run() {
	followerHelper.updateFollowers();
	//subscriberHelper.updateSubscribers();
	setTimeout(function(){
		followerHelper.updateFollowers();
		subscriberHelper.updateSubscribers();
	}, timeoutTime);	
}

run();
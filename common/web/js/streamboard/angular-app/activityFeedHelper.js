(function() {
	angular
		.module('pullr.streamboard.regionsPanels')
		.service('activityFeedHelper', activityFeedHelper);

	function activityFeedHelper () {

		//Create activity feed array from donation, donation groups, followers, subscribers
		this.createActivityArray = createActivityArray;

		//Sort donation by property
		this.sortDonationByProperty = sortDonationByProperty;

		return this;

		function createActivityArray (donations,
																	groupDonationByEmail,
																	groupDonationByName,
																	enableGroupDonation,
																	groupBy,
																	followers,
																	subscribers,
																	showFollower,
																	showSubscriber) {

			var donationArray = createDonationActivityFeed(donations, groupDonationByEmail, groupDonationByName, enableGroupDonation, groupBy);
			var followersArray = createFollowerActivityFeed(followers, showFollower);
			var subscribersArray = createSubsriberActivityFeed(subscribers, showSubscriber);

			return [].concat(donationArray, followersArray, subscribersArray);
		}

		function createFollowerActivityFeed (followers, showFollower) {
			if (false == showFollower) {
				return [];
			}

			var followersArray = [];
			for (var i = 0; i < followers.length; i++ ) {
				var str = followers[i].display_name + ' (followed)';
				followersArray.push(str);
			}
			return followersArray;
		}

		function createSubsriberActivityFeed (subscribers, showSubscriber) {
			if (false == showSubscriber) {
				return [];
			}
			var subscribersArray = [];
			for (var i = 0; i < subscribers.length; i++ ) {
				var str = subscribers[i].display_name + ' (subscribed)';
				subscribersArray.push(str);
			}
			return subscribersArray;
		}

		function createDonationActivityFeed (donations, groupDonationByEmail, groupDonationByName, enableGroupDonation, groupBy) {
			var activityArray = [];

			//No Group Donation
			if (false == enableGroupDonation) {
				for (var i = 0; i < donations.length; i++) {
					var donationString = donations[i].displayName + ' ($' + number_format(donations[i].amount) + ')';
					activityArray.push(donationString);
				}
			} else {
				// Group Donation by Email

				if (groupBy == 'email' && groupDonationByEmail != null) {
					activityArray.push(createGroupDonationActivityFeed(groupDonationByEmail));
					// Group Donation By Name
				} else if (groupBy == 'name' && groupDonationByName != null) {
					activityArray.push(createGroupDonationActivityFeed(groupDonationByName));
				}

			}

			return activityArray;
		}

		function createGroupDonationActivityFeed(groupDonations) {
			var groupDonationActivityFeed = [];
			for ( var i = 0; i < groupDonations.length; i++ ) {
				var group = groupDonations[i];
				var nameList = [];
				var groupDonationString = ''
				for ( var j = 0; j < group.items.length; j++ ) {
					var person = group.items[j];
					nameList.push(person.name);
				}
				groupDonationString = nameList.join(', ') + ' ($' + number_format(group.amount) + ')';
				if (groupDonationString != '') {
					groupDonationActivityFeed.push(groupDonationString);
				}
			}
			return groupDonationActivityFeed.join(', ');
		}

		function sortDonationByProperty(donations, property) {
			if (!donations || !donations.length) {
				return;
			}
			return donations.sort(function(donation1, donation2) {
				return donation2[property] - donation1[property];
			});
		}


	}
})();

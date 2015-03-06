(function() {
	angular
		.module('pullr.streamboard.regionsPanels')
		.directive('activityFeed', activityFeed);

	function activityFeed ($rootScope, activityFeedHelper) {
		return {
			restrict: 'EA',
			templateUrl: 'activity-feed.html',
			scope: {
				region: '=',
				donations: '=',
				enableGroupDonation: '=',
				groupBy: '=',
				groupDonationByEmail: '=',
				groupDonationByName: '=',
				followers: '=',
				subscribers: '=',
				showFollower: '=',
				showSubscriber: '=',
				sortType: '=',
				noDonationMessage: '='
			},
			link: link
		};

		function link(scope, element, attrs) {
			scope.marqueeContent = '';
			var sortType = 'amount';
			var oldMarqueeContent = '';
			//Watch for changes

			scope.$watchCollection('[donations,	followers, subscribers,	showFollower,	showSubscriber,	enableGroupDonation, groupDonationByEmail, groupDonationByName,	groupBy]', function() {
				initActivityFeed();
			});

			function initActivityFeed () {
				//Sort donation
				sortDonations();
				//Create activity feed string
				activityArray = activityFeedHelper.createActivityArray(scope.donations,
																																scope.groupDonationByEmail,
																																scope.groupDonationByName,
																																scope.enableGroupDonation,
																																scope.groupBy,
																																scope.followers,
																																scope.subscribers,
																																scope.showFollower,
																																scope.showSubscriber);
				if ( activityArray.length > 0 ) {
					scope.marqueeContent = activityArray.join(', ');
				} else {
					scope.marqueeContent = scope.noDonationMessage || 'No activity!';
				}

				if (oldMarqueeContent != scope.marqueeContent) {
					//recalculate marquee
					$rootScope.$broadcast('recalculateMarquee');
					oldMarqueeContent = scope.marqueeContent;
				}

			}

			function sortDonations() {
				scope.donations = activityFeedHelper.sortDonationByProperty(scope.donations, sortType);
				scope.groupDonationByEmail = activityFeedHelper.sortDonationByProperty(scope.groupDonationByEmail, sortType);
				scope.groupDonationByName = activityFeedHelper.sortDonationByProperty(scope.groupDonationByName, sortType);
			}

		}
	}
})();

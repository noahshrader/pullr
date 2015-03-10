(function() {
	angular
		.module('pullr.streamboard.activityFeed', [])
		.directive('activityFeed', activityFeed);

	function activityFeed ($rootScope, activityFeedHelper) {
		return {
			restrict: 'EA',
			template: '{{marqueeContent}}',
			scope: {
				donations: '=',
				enableGroupDonation: '=',
				groupBy: '=',
				groupDonationByEmail: '=',
				groupDonationByName: '=',
				followers: '=',
				subscribers: '=',
				showFollower: '=',
				showSubscriber: '=',
				sortBy: '=',
				noDonationMessage: '=',
				noAnimation: '&'
			},
			link: link
		};

		function link(scope, element, attrs) {
			scope.marqueeContent = '';
			var oldMarqueeContent = '';
			//Watch for changes

			scope.$watchCollection('[donations,	followers, subscribers,	showFollower,' +
														'showSubscriber,	enableGroupDonation, groupDonationByEmail, ' +
														'groupDonationByName,	groupBy, sortBy]', function() {
				initActivityFeed();
				console.log('change detect');
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
					if (!scope.noAnimation) {
						$rootScope.$broadcast('recalculateMarquee');
					}
					oldMarqueeContent = scope.marqueeContent;
				}
			}

			function sortDonations() {

				//sort field
				var sortField = 'amount';
				var sortDirection = 'desc';
				switch (scope.sortBy) {
					case 'Time':
						sortField = 'time';
						sortDirection = 'desc';
						break;
					case 'Alphabet':
						sortField = 'name';
						sortDirection = 'asc';
						break;
					case 'Amount':
					default:
						sortField = 'amount';
						sortDirection = 'desc';
						break;
				}

				scope.donations = activityFeedHelper.sortDonationByProperty(scope.donations, sortField, sortDirection);
				//alway sort group donation by amount, but sort donation inside by sortField;
				scope.groupDonationByEmail = activityFeedHelper.sortDonationByProperty(scope.groupDonationByEmail, 'amount', sortDirection);
				scope.groupDonationByName = activityFeedHelper.sortDonationByProperty(scope.groupDonationByName, 'amount', sortDirection);

				if (scope.groupDonationByEmail) {
					for (var i = 0; i < scope.groupDonationByEmail.length; i++) {
						activityFeedHelper.sortDonationByProperty(scope.groupDonationByEmail[i].items, sortField, sortDirection);
					}
				}

				if (scope.groupDonationByName) {
					for (var i = 0; i < scope.groupDonationByName.length; i++) {
						activityFeedHelper.sortDonationByProperty(scope.groupDonationByName[i].items, sortField, sortDirection);
					}
				}
			}

		}
	}
})();

Short codes
=========

You can use any tag with **data-pullr** attribute set to some value. If that model/attribute exists its value will be inserted directly in that tag. If it doesn't exist nothing will happen.

Code example:
```
<h1  data-pullr='campaign-name'></h1>
<span data-pullr='campaign-startDateFormatted'></span> -
<span data-pullr='campaign-endDateFormatted'></span>
```

As result we can recieve something like:

```
<h1  data-pullr='campaign-name'>Fun For Freedom</h1>
<span data-pullr='campaign-startDateFormatted'>June 12, 2014</span> -
<span data-pullr='campaign-endDateFormatted'>June 16, 2014</span>

```

To view list of available fields you can type *Pullr.campaign* in javascript console on layout page. 

###Predefined charity
If all required condition met (donationDestination is *pre-approved charites* and campaign's type is *charity event* or *charity fundraiser* and predefined charity is selected) **campaign-charity** model is avaiable. 
Code example: 

`<span data-pullr="campaign-charity-name"></span>`

will generate something like:

`<span data-pullr="campaign-charity-name">PETA</span>`

###Campaign attributes
As for June 2014 list of available fields looks like:

```
alias: "Fun_For_Freedom"
amountRaised: "0"
appearance: ""
channelName: ""
channelTeam: "funforfreedom"
charity: null
charityId: 2
chat: 0
customCharity: ""
customCharityDescription: ""
customCharityPaypal: ""
date: "2014-06-12 01:35:37"
donationDestination: "Pre-approved Charities"
enableCustomLogo: 0
enableDonorComments: 1
enableGoogleAnalytics: 0
enableThankYouPage: 0
endDate: 1402861740
endDateFormatted: "June 16, 2014"
eventId: 1
facebookEnable: 0
facebookUrl: ""
formVisibility: 1
goalAmount: "17000"
googleAnalytics: ""
id: 1
includeYoutubeFeed: 0
key: "test_key"
layoutType: "Team Stream"
name: "Fun For Freedom"
numberOfDonations: 0
numberOfUniqueDonors: 0
paypalAddress: ""
photoId: null
primaryColor: "#000000"
secondaryColor: "#000000"
startDate: 1402516140
startDateFormatted: "June 12, 2014"
status: "active"
streamService: "Twitch"
tertiaryColor: "#000000"
thankYouPageText: ""
themeId: null
twitterEnable: 0
twitterName: ""
type: "Personal Tip Jar"
userId: 1
youtubeEnable: 0
youtubeLayout: ""
youtubeUrl: ""
```

###Charity's attributes
If charity avaliable list of available attributes for **campaign-charity**  as for June 2014 looks like:

```
`<span data-pullr="campaign-charity-name"></span>`
contact: "Stanislav Klyukin"
contactEmail: "stas.msu@gmail.com"
contactPhone: ""
date: "2014-06-12 01:35:36"
description: ""
id: 2
name: "PETA"
paypal: "paypal@peta.org"
photoId: null
status: "active"
type: "Animals"
url: ""
```

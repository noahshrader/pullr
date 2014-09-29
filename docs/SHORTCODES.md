Shortcodes
=========

You can use any available attribute within the API to display values. If data exists, its value will be inserted directly in that tag. If it doesn't exist nothing will happen.

Code example:
```
<h1>{{campaign.name}}</h1>
```

As result, we can recieve something like:

```
<h1>Fun For Freedom</h1>

```

To view list of available fields you can type *Pullr.campaign* in javascript console on layout page. 

###Predefined charity
If all required condition met (donationDestination is *pre-approved charites* and campaign's type is *charity event* or *charity fundraiser* and predefined charity is selected) **campaign-charity** model is avaiable. 
Code example: 

`<span data-pullr="campaign-charity-name"></span>`

will generate something like:

`<span data-pullr="campaign-charity-name">PETA</span>`

###Campaign attributes

```
Amount Raised: "{{campaign.amountRaised}}"
Amount Raised (Formatted): "{{campaign.amountRaisedFormatted}}"
Campaign Alias: "{{campaign.alias}}"
Campaign Name: "{{campaign.name}}"
Campaign Description: "{{campaign.description}}"
Campaign Start Date: "{{campaign.startDate}}"
Campaign Start Date (Formatted): "{{campaign.startDateFormatted}}"
Campaign End Date: "{{campaign.endDate}}"
Campaign End Date (Formatted): "{{campaign.endDateFormatted}}"
Campaign Goal Amount: "{{campaign.goalAmount}}"
Campaign Goal Amount (Formatted): "{{campaign.goalAmountFormatted}}"
Campaign Type: "{{campaign.type}}"
Campaign ID: "{{campaign.id}}"
Channel Name: "{{campaign.channelName}}"
Channel Team: "{{campaign.channelTeam}}"
Charity Name: "{{campaign.charity.name}}"
Charity ID: "{{campaign.charity.id}}"
Custom Charity Name: "{{campaign.customCharity}}"
Custom Charity PayPal: "{{campaign.customCharityPaypal}}"
Date Created: "{{campaign.date}}"
Facebook URL: "{{campaign.facebookUrl}}"
Fundraiser Type: "{{campaign.donationDestination}}"
Key: "{{campaign.key}}"
Layout Type: "{{campaign.layoutType}}"
Number of Donations: "{{campaign.numberOfDonations}}"
Number of Donors: "{{campaign.numberOfUniqueDonors}}"
Parent Campaign ID: "{{campaign.parentCampaignId}}"
PayPal Address (Personal Fundraiser): "{{campaign.paypalAddress}}"
Percent to Goal: "{{campaign.percentageOfGoal}}"
Primary Color: "{{campaign.primaryColor}}"
Secondary Color: "{{campaign.secondaryColor}}"
Status: "{{campaign.status}}"
Streaming Service: "{{campaign.streamService}}"
Thank You Page Text: "{{campaign.thankYouPageText}}"
Twitter Profile: "{{campaign.twitterName}}"
YouTube URL: "{{campaign.youtubeUrl}}"

```

###Layout
Layout: `<div pullr-campaign-layout></div>`

```

###Charity Attributes (Backend)
If charity avaliable list of available attributes for **campaign-charity**  as for June 2014 looks like:

```
contactEmail: "support@peta.org"
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
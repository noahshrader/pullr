function layoutTypeChanged() {
    var newValue = $(this).val();
    newValue = newValue.replace(' ', '');
    $('[data-campaign-layoutType]').attr('data-campaign-layoutType', newValue);
}

function campaignTypeChanged(){
    $('#campaign-paypaladdress').val('');
    $('#campaign-customcharity').val('');
    $('#campaign-customcharitypaypal').val('');
    $("#firstgiving").select2('val', '');

    $('#campaignEdit').attr('data-campaignType', $(this).val());
}

function donationDestinationChanged(){
    $("#firstgiving").select2('val', '');
    $('#campaign-customcharity').val('');
    $('#campaign-customcharitypaypal').val('');
    $('#donationDestination').attr('data-donationDestination', $(this).val());
}
function updateLayoutTeams() {
    var id = $('#campaignEdit').data('id');
    $.getJSON('app/campaigns/layoutteams', {id: id}, function(teams) {
        var $list = $('<ul>');
        for (var key in teams) {
            var team = teams[key];
            var $item = $('<li>').append($('<span>').text(team.name));
            $item.append($('<a href="javascript:void(0)" onclick="layoutTeamRemove(this)" class="icon mdi-navigation-close"></a>'))
            $item.append($('<a href="javascript:void(0)" class="'+(team.facebook?'active':'')+''+(team.twitter?'active':'')+''+(team.youtube?'active':'')+' icon-bubble" onclick="layoutTeamLink(this,'+team.id+')"></a> '))
            $list.append($item);
        }
        var $list = $('<div>').append($list);
        $('#layoutTeams').html($list.html());
    });
}

function layoutTeamLink(el, id){
    $('#modal-social-link .modal-content').load('app/campaigns/layoutteamedit', {id: id, get: true}, function(){
        $('#modal-social-link').modal('show');
    })
}
function layoutTeamRemove(el) {
    var id = $('#campaignEdit').data('id');
    var name = $(el).parents('li').find('span').text();
    $.post('app/campaigns/layoutteamremove', {id: id, name: name}, function() {
        updateLayoutTeams();
    })
}
function addNewLayoutTeam() {
    var id = $('#campaignEdit').data('id');
    var $el = $('#addLayoutTeam');
    var name = $el.val();
    if (name) {
        $.post('app/campaigns/layoutteamadd', {id: id, name: name}, function() {
            $el.val('');
            updateLayoutTeams();
        });
    }
}

function updateCampaignInvites(){
    var id = $('#campaignEdit').data('id');
    $.getJSON('app/campaigns/getcampaigninvites', {id: id}, function(invites) {
        var $list = $('<ul>');
        for (var key in invites) {
            var invite = invites[key];
            var $item = $('<li>').append($('<span>').text(invite.user.name));
            $item.attr('data-userid', invite.user.id);
            $item.append($('<a href="javascript:void(0)" onclick="campaignInviteRemove(this)" class="icon mdi-navigation-close"></a>'))
            $list.append($item);
        }
        var $list = $('<div>').append($list);
        $('#campaignInvitesUsers').html($list.html());
    });
}

function campaignInviteRemove(el) {
    var id = $('#campaignEdit').data('id');
    var userid = $(el).parents('li').data('userid');
    $.post('app/campaigns/campaigninviteremove', {id: id, userid: userid}, function() {
        updateCampaignInvites();
    })
}

function addNewCampaignInvite() {
    var id = $('#campaignEdit').data('id');
    var $el = $('#addCampaignInvite');
    var uniqueName = $el.val();
    if (uniqueName) {
        $.post('app/campaigns/campaigninvite', { id: id, uniqueName: uniqueName }, function (data) {
            var wrap = $(".combined-form-wrap");
            var wrapHelp = $(".combined-form-wrap  .help-block")
            if (data == 0)
            {
                wrap.addClass("has-error");
                wrapHelp.removeClass("hide");
            } else {
                wrap.removeClass("has-error");
                wrapHelp.addClass("hide");
                $el.val('');
                updateCampaignInvites();
            }
        });
    }
}

/* Toggles */
function initBootstrapSwitch() {
    $("#campaignEdit input[type='checkbox']").bootstrapSwitch();
    $("#campaign-enabledonations").on('switchChange.bootstrapSwitch', function() {
        var value = $('#campaign-enabledonations').bootstrapSwitch('state');
        if (value){
            $('.field-campaign-eventid').show('slow');
        } else {
            $('.field-campaign-eventid').hide('slow');
        }
    });
    $("#campaign-twitterenable").on('switchChange.bootstrapSwitch', function() {
        var value = $('#campaign-twitterenable').bootstrapSwitch('state');
        if (value){
            $('.field-campaign-twittername').fadeIn(300);
        } else {
            $('.field-campaign-twittername').fadeOut(300);
        }
    });
    $("#campaign-youtubeenable").on('switchChange.bootstrapSwitch', function() {
        var value = $('#campaign-youtubeenable').bootstrapSwitch('state');
        if (value){
            $('.field-campaign-youtubeurl').fadeIn(300);
        } else {
            $('.field-campaign-youtubeurl').fadeOut(300);
        }
    });
    $("#campaign-facebookenable").on('switchChange.bootstrapSwitch', function() {
        var value = $('#campaign-facebookenable').bootstrapSwitch('state');
        if (value){
            $('.field-campaign-facebookurl').fadeIn(300);
        } else {
            $('.field-campaign-facebookurl').fadeOut(300);
        }
    });
    $("#campaign-teamenable").on('switchChange.bootstrapSwitch', function() {
        var value = $("#campaign-teamenable").bootstrapSwitch('state');
        if (value){
            $('#campaignEdit').addClass('team-enabled');
        } else {
            $('#campaignEdit').removeClass('team-enabled');
        }
    });
    $("#campaign-tiedtoparent").on('switchChange.bootstrapSwitch', function() {
        var value = $('#campaign-tiedtoparent').bootstrapSwitch('state');
        if (value){
            $('.field-campaign-parentcampaignid').fadeIn(300);
        } else {
            $('.field-campaign-parentcampaignid').fadeOut(300);
        }
    });
    $("#campaign-enablethankyoupage").on('switchChange.bootstrapSwitch', function() {
        var value = $('#campaign-enablethankyoupage').bootstrapSwitch('state');
        if (value){
            $('.form-group.thankyoutext').fadeIn(300);
        } else {
            $('.form-group.thankyoutext').fadeOut(300);
        }
    });
}

function getDefaultTheme()
{
    var layoutType = $('#campaign-layouttype').val();
    $.ajax({
        url: 'app/campaigns/defaulttheme',
        type: 'POST',
        cache: false,
        data: {layoutType: layoutType},
        dataType: 'json',
        success: function(data) {
            $('#campaign-themeid').val(data.id);
            $('.theme-name span').text(data.name);
        }
    });
}

function layoutChooseTheme(){
    var layoutType = $('#campaign-layouttype').val();
    $('#modalThemes .modal-content').load('app/campaigns/modalthemes', {layoutType: layoutType}, function(){
        $('#modalThemes').modal('show');

    })
}

function selectTheme(el){
    var $theme = $(el).parents('.select-theme-container');
    var name = $theme.data('name');
    var id = $theme.data('id');
    $('#campaign-themeid').val(id);
    $('.theme-name span').text(name);
    $('.theme-name').removeClass('hidden');
    $('#sidepanelthree').removeClass('open expand');
    $('#sidepanel').removeClass('expand');
    $('.page-wrapper').removeClass('choosetheme-expand');
    $('#modalThemes').modal('hide');
}

function campaignChooseCharity(){
    $('#modalCharity .modal-content').load('app/campaigns/modalcharities', function(){
        $('#modalCharity').modal('show');
    });
}

function selectCharity(el){
    $el = $(el);
    var id = $el.data('id');
    var name = $el.data('name');
    $('#campaign-charityid').val(id);
    $('.charity-name span').text(name);
    $('.charity-name').removeClass('hidden');
    $('#modalCharity').modal('hide');
}

$(function() {
    $('#campaign-layouttype').change(layoutTypeChanged);
    $('[name="Campaign[type]"]').change(campaignTypeChanged);
    $('[name="Campaign[donationDestination]"]').change(donationDestinationChanged);
    updateLayoutTeams();
    updateCampaignInvites();
    initBootstrapSwitch();
});
/*We use js as Yii Js add text-aling right on load event*/
$( window ).load(function() {
    $('#masked-input').on('change', function(){
       $('#campaign-goalamount').val($(this).val());
    });

    $("#image-uploaded").click(function(){
        $.post('app/campaigns/bgdelete', {campaignId: $(this).data('campaignid')}, function(data) {
            $(".image-upload-preview").hide();
        });
    });

    $('.field-campaign-goalamount .help-block').insertAfter('.field-campaign-goalamount #masked-input');
});

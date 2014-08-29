function layoutTypeChanged() {
    var newValue = $(this).val();
    newValue = newValue.replace(' ', '');
    $('[data-campaign-layoutType]').attr('data-campaign-layoutType', newValue);
}

function campaignTypeChanged(){
    $('#campaignEdit').attr('data-campaignType', $(this).val());
}

function donationDestinationChanged(){
    $('#donationDestination').attr('data-donationDestination', $(this).val());
}
function updateLayoutTeams() {
    var id = $('#campaignEdit').data('id');
    $.getJSON('app/campaign/layoutteams', {id: id}, function(teams) {
        var $list = $('<ol>');
        for (var key in teams) {
            var team = teams[key];
            var $item = $('<li>').append($('<span>').text(team.name));
            $item.append($('<a href="javascript:void(0)" onclick="layoutTeamRemove(this)"><i class="icon icon-minus2"></i></a>'))
            $item.append($('<a href="javascript:void(0)" class="'+(team.facebook?'active':'')+'" onclick="layoutTeamLink(this,'+team.id+')"><i class="facebook">f</i></a> '))
            $item.append($('<a href="javascript:void(0)" class="'+(team.twitter?'active':'')+'" onclick="layoutTeamLink(this,'+team.id+')"><i class="twitter">t</i></a> '))
            $item.append($('<a href="javascript:void(0)" class="'+(team.youtube?'active':'')+'" onclick="layoutTeamLink(this,'+team.id+')"><i class="youtube">y</i></a> '))
            $list.append($item);
        }
        var $list = $('<div>').append($list);
        $('#layoutTeams').html($list.html());
    });
}


function layoutTeamLink(el, id){
    $('#modal-social-link .modal-content').load('app/campaign/layoutteamedit', {id: id, get: true}, function(){
        $('#modal-social-link').modal('show');
    })
}
function layoutTeamRemove(el) {
    var id = $('#campaignEdit').data('id');
    var name = $(el).parents('li').find('span').text();
    $.post('app/campaign/layoutteamremove', {id: id, name: name}, function() {
        updateLayoutTeams();
    })
}
function addNewLayoutTeam() {
    var id = $('#campaignEdit').data('id');
    var $el = $('#addLayoutTeam');
    var name = $el.val();
    if (name) {
        $.post('app/campaign/layoutteamadd', {id: id, name: name}, function() {
            $el.val('');
            updateLayoutTeams();
        });
    }
}

function updateCampaignInvites(){
    var id = $('#campaignEdit').data('id');
    $.getJSON('app/campaign/getcampaigninvites', {id: id}, function(invites) {
        var $list = $('<ol>');
        for (var key in invites) {
            var invite = invites[key];
            var $item = $('<li>').append($('<span>').text(invite.user.name+' ['+invite.user.email+']'));
            $item.attr('data-userid', invite.user.id);
            $item.append($('<a href="javascript:void(0)" onclick="campaignInviteRemove(this)"><i class="icon icon-minus2"></i></a>'))
            $list.append($item);
        }
        var $list = $('<div>').append($list);
        $('#campaignInvitesUsers').html($list.html());
    });
}

function campaignInviteRemove(el) {
    var id = $('#campaignEdit').data('id');
    var userid = $(el).parents('li').data('userid');
    $.post('app/campaign/campaigninviteremove', {id: id, userid: userid}, function() {
        updateCampaignInvites();
    })
}

function addNewCampaignInvite() {
    var id = $('#campaignEdit').data('id');
    var $el = $('#addCampaignInvite');
    var email = $el.val();
    if (email) {
        $.post('app/campaign/campaigninvite', {id: id, email: email}, function(data) {
            log(data);
            $el.val('');
            updateCampaignInvites();
        });
    }
}
function rememberAccordionState() {
    var last = localStorage.getItem('activeAccordionGroup');
    if (last != null && $('#' + last).length != 0) {
        //remove default collapse settings
        $("#accordion .collapse").removeClass('in');
        //show the last visible group
//        log(123);
//        log($("#"+last));
        $("#" + last).collapse("show");
    }

    //when a group is shown, save it as the active accordion group
    $("#accordion").bind('shown.bs.collapse', function() {
        var active = $("#accordion .in").attr('id');
        localStorage.setItem('activeAccordionGroup', active)
    });
}

function initBootstrapSwitch() {
    $("#campaignEdit input[type='checkbox']").bootstrapSwitch();
    $("#campaign-chat").on('click', function() {
        var value = $('#campaign-chat').bootstrapSwitch('state');
        if (value){
            $('.field-campaign-chattoggle').show('slow');
        } else {
            $('.field-campaign-chattoggle').hide('slow');
        }
    });
    $("#campaign-enabledonations").on('click', function() {
        var value = $('#campaign-enabledonations').bootstrapSwitch('state');
        if (value){
            $('.field-campaign-eventid').show('slow');
        } else {
            $('.field-campaign-eventid').hide('slow');
        }
    });
    
    $("#campaign-twitterenable").on('click', function() {
        var value = $('#campaign-twitterenable').bootstrapSwitch('state');
        if (value){
            $('.field-campaign-twittername').show('slow');
        } else {
            $('.field-campaign-twittername').hide('slow');
        }
    });
    
    $("#campaign-youtubeenable").on('click', function() {
        var value = $('#campaign-youtubeenable').bootstrapSwitch('state');
        if (value){
            $('.field-campaign-youtubeurl').show('slow');
            $('.field-campaign-includeyoutubefeed').show('slow');
        } else {
            $('.field-campaign-youtubeurl').hide('slow');
            $('.field-campaign-includeyoutubefeed').hide('slow');
        }
    });
    
    $("#campaign-facebookenable").on('click', function() {
        var value = $('#campaign-facebookenable').bootstrapSwitch('state');
        if (value){
            $('.field-campaign-facebookurl').show('slow');
        } else {
            $('.field-campaign-facebookurl').hide('slow');
        }
    });

    $("#campaign-teamenable").on('click', function() {
        var value = $(this).bootstrapSwitch('state');
        if (value){
            $('#campaignEdit').addClass('team-enabled');
        } else {
            $('#campaignEdit').removeClass('team-enabled');
        }
    });
    
    $("#campaign-enablecustomlogo").on('click', function() {
        var value = $('#campaign-enablecustomlogo').bootstrapSwitch('state');
        if (value){
            $('#logo-container').show('slow');
        } else {
            $('#logo-container').hide('slow');
        }
    });
    
    $("#campaign-tiedtoparent").on('click', function() {
        var value = $('#campaign-tiedtoparent').bootstrapSwitch('state');
        if (value){
            $('#collapseOne').addClass('isTied');
        } else {
            $('#collapseOne').removeClass('isTied');
        }
    });
}

function layoutChooseTheme(){
    var layoutType = $('#campaign-layouttype').val();
    $('#modalThemes .modal-content').load('app/campaign/modalthemes', {layoutType: layoutType}, function(){
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
    $('#modalCharity .modal-content').load('app/campaign/modalcharities', function(){
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
    $('[name="Campaign[donationDestination]"').change(donationDestinationChanged);
    updateLayoutTeams();
    updateCampaignInvites();
    // rememberAccordionState();
    initBootstrapSwitch();
});
/*We use js as Yii Js add text-aling right on load event*/
$( window ).load(function() {
    $('#masked-input').css('text-align', 'left');
    $('#masked-input').on('change', function(){
       $('#campaign-goalamount').val($(this).val());
    });
    $.extend($.inputmask.defaults, {
        'autoUnmask': true
    });
});

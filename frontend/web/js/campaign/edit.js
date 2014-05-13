function layoutTypeChanged() {
    var newValue = $(this).val();
    newValue = newValue.replace(' ', '');
    $('[data-layout-type]').attr('data-layout-type', newValue);
}

function campaignTypeChanged(){
    $('#campaignEdit').attr('data-campaignType', $(this).val());
}

function donationDestinationChanged(){
    $('#donationDestination').attr('data-donationDestination', $(this).val());
}
function updateLayoutTeams() {
    var id = $('.layout-edit').data('id');
    $.getJSON('app/campaign/layoutteams', {id: id}, function(teams) {
        var $list = $('<ol>');
        for (var key in teams) {
            var team = teams[key];
            var $item = $('<li>').append($('<span>').text(team.name));
            $item.append($('<a href="javascript:void(0)" onclick="layoutTeamRemove(this)"><i class="glyphicon glyphicon-remove"></i></a>'))
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
    var id = $('.layout-edit').data('id');
    var name = $(el).parents('li').find('span').text();
    $.post('app/campaign/layoutteamremove', {id: id, name: name}, function() {
        updateLayoutTeams();
    })
}
function addNewLayoutTeam() {
    var id = $('.layout-edit').data('id');
    var $el = $('#addLayoutTeam');
    var name = $el.val();
    if (name) {
        $.post('app/campaign/layoutteamadd', {id: id, name: name}, function() {
            $el.val('');
            updateLayoutTeams();
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
    
    $("#campaign-enablegoogleanalytics").on('click', function() {
        var value = $('#campaign-enablegoogleanalytics').bootstrapSwitch('state');
        log(value);
        if (value){
            $('.field-campaign-googleAnalytics').show('slow');
        } else {
            $('.field-campaign-googleAnalytics').hide('slow');
        }
    });
    
}

function layoutChooseTheme(){
    var layoutType = $('#layout-type').val();
    $('#modalThemes .modal-content').load('app/campaign/modalthemes', {type: layoutType}, function(){
        $('#modalThemes').modal('show');
    })
}

function selectTheme(el){
    var $theme = $(el).parents('.select-theme-container');
    var name = $theme.data('name');
    var id = $theme.data('id');
    $('#layout-themeid').val(id);
    $('.theme-name span').text(name);
    $('.theme-name').removeClass('hidden');
    $('#modalThemes').modal('hide');
}

$(function() {
    $('#layout-type').change(layoutTypeChanged);
    $('[name="Campaign[type]"]').change(campaignTypeChanged);
    $('[name="Campaign[donationDestination]"').change(donationDestinationChanged);
    updateLayoutTeams();
//    rememberAccordionState();
    initBootstrapSwitch();
});

function layoutTypeChanged() {
    var newValue = $(this).val();
    newValue = newValue.replace(' ', '');
    $('[data-layout-type]').attr('data-layout-type', newValue);
}

function updateLayoutTeams() {
    var id = $('.layout-edit').data('id');
    $.getJSON('pullrlayout/layoutteams', {id: id}, function(teams) {
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
//    var id = $('.layout-edit').data('id');
//    var value = $(el).data('value');
    $('#modal-social-link .modal-content').load('pullrlayout/layoutteamedit', {id: id, get: true}, function(){
        $('#modal-social-link').modal('show');
    })
}
function layoutTeamRemove(el) {
    var id = $('.layout-edit').data('id');
    var name = $(el).parents('li').find('span').text();
    $.post('pullrlayout/layoutteamremove', {id: id, name: name}, function() {
        updateLayoutTeams();
    })
}
function addNewLayoutTeam() {
    var id = $('.layout-edit').data('id');
    var $el = $('#addLayoutTeam');
    var name = $el.val();
    if (name) {
        $.post('pullrlayout/layoutteamadd', {id: id, name: name}, function() {
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
    $(".layout-edit input[type='checkbox']").bootstrapSwitch();
    $("#layout-chat").on('click', function() {
        var value = $('#layout-chat').bootstrapSwitch('state');
        if (value){
            $('.field-layout-chattoggle').show('slow');
        } else {
            $('.field-layout-chattoggle').hide('slow');
        }
    });
    $("#layout-enabledonations").on('click', function() {
        var value = $('#layout-enabledonations').bootstrapSwitch('state');
        if (value){
            $('.field-layout-eventid').show('slow');
        } else {
            $('.field-layout-eventid').hide('slow');
        }
    });
    
    
}

$(function() {
    $('#layout-type').change(layoutTypeChanged);
    updateLayoutTeams();
    rememberAccordionState();
    initBootstrapSwitch();
});

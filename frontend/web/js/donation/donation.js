function donatePageInit() {
    jQuery("#other-amount").on("change keyup paste",amountChangedEvent);

    jQuery("#other").hide();


    jQuery('.donationContainer').on('change', 'input[type="radio"].toggle', function() {
        if (this.checked) {
            jQuery('input[name="' + this.name + '"].checked').removeClass('checked');
            jQuery(this).addClass('checked');
        }
    });
    jQuery('.fieldamount input:radio').click(function() {
        var $els = $('.fieldamount input:radio[name=' + $(this).attr('name') + ']');
        $('.fieldamount input:radio[name=' + $(this).attr('name') + ']').parent().removeClass('active');
        var $active = $(this);
        $active.parent().addClass('active');
        $els.attr('checked',false);
        this.checked = true;
        amountChangedEvent();
    });
    jQuery('.donationContainer input[type="radio"].toggle:checked').addClass('checked');
    jQuery('a.closethis').click(function() {
        jQuery('#other').fadeOut(100);
        jQuery('.choice').fadeIn(200);
        jQuery('#otheramount').removeClass('checked');
        jQuery('#option1').attr('checked', 'checked');
        amountChangedEvent();
    });
    
    $('#otheramount').click(function(){
        $('#other').css('display','block');
    })
    amountChangedEvent();
}

function amountChangedEvent(){
    var value = $('[name=donation-amount]:checked').val();
    if (value=='other'){
        var value=parseInt($('#other-amount').val());
        if (!$.isNumeric(value) || value == 0){
            return;
        }
    }
    
    $('input#donation-amount').val(value);
    $('button.donate').text('Donate $'+value);
}

$(document).ready(donatePageInit);


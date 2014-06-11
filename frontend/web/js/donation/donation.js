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

/**
 *
 * format number with ",". Example - 1,200,322 
 */
function numberFormat(number){
    var str = '';
    var module = number % 1000;
    str = module + str;
    remainNumber = Math.floor(number/1000);
    if (remainNumber > 0){
        if (module<100){
            str = '0'+str;
        }
        if (module<10){
            str = '0'+str;
        }
        str = numberFormat(remainNumber) + ','+str;
    }
    
    return str;
}

function amountChangedEvent(){
    var value = $('[name=donation-amount]:checked').val();
    var undefinedFlag = false;
    if (typeof value === 'undefined'){
        value = 1;
        undefinedFlag = true;
    }
    
    if (value=='other'){
        var value=parseInt($('#other-amount').val());
        if (!$.isNumeric(value) || value == 0){
            return;
        }
    }
    
    $('input#donation-amount').val(value);
    $('button.donate').text('Donate $'+value);
    
    /**casting to int*/
    value=parseInt(value);
    if (!undefinedFlag){
        var $progressContainer = $('.form-progress');
        var amountRaised = parseInt($progressContainer.data('amountraised'));
        var goalAmount = parseInt($progressContainer.data('goalamount'));
        amountRaised+=value;
        $progressContainer.find('.amountRaised').text('$'+numberFormat(amountRaised));
        /*preventing division by zero*/
        goalAmount = Math.max(1, goalAmount);
        var percent = 100*amountRaised/goalAmount;
        $progressContainer.find('.progress').css('width',percent+'%');
    }
}

$(document).ready(donatePageInit);


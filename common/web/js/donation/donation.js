function donatePageInit() {
    $("#other-amount").on("change keyup paste", amountChangedEvent);

    $("#other").hide();

    $('.donationContainer').on('change', 'input[type="radio"].toggle', function () {
        if (this.checked) {
            $('input[name="' + this.name + '"].checked').removeClass('checked');
            $(this).addClass('checked');
        }
    });
    $('.fieldamount input:radio').click(function () {
        var $els = $('.fieldamount input:radio[name=' + $(this).attr('name') + ']');
        $('.fieldamount input:radio[name=' + $(this).attr('name') + ']').parent().removeClass('active');
        var $active = $(this);
        $active.parent().addClass('active');
        $els.attr('checked', false);
        this.checked = true;
        amountChangedEvent();
    });
    $('.donationContainer input[type="radio"].toggle:checked').addClass('checked');
    $('a.closethis').click(function () {
        $('#other').fadeOut(100);
        $('.choice').fadeIn(200);
        $('#otheramount').removeClass('checked');
        $('#option1').attr('checked', 'checked');
        amountChangedEvent();
    });

    // Character count for donation form text area
    $.fn.extend({
        limiter: function (limit, elem) {
            $(this).each(function(index, value){
                $(value).on("keyup focus", function () {
                    setCount(this, elem);
                });
                function setCount(src, elem) {
                    var chars = src.value.length;
                    if (chars > limit) {
                        src.value = src.value.substr(0, limit);
                        chars = limit;
                    }
                    elem.html(limit - chars);
                }
                setCount(value, elem);
            });
        }
    });

    var elem = $(".counter");
    $('textarea#donation-comments').limiter(600, elem);

    $('#otheramount').click(function () {
        $('#other').css('display', 'block');
    })
    amountChangedEvent();
}

function amountChangedEvent() {
    if ($('.tip-jar').length > 0) {
        var value = parseInt($('#other-amount').val());
        if (!$.isNumeric(value) || value == 0) {
            return;
        }
    } else {
        var value = $('[name=donation-amount]:checked').val();
        var undefinedFlag = false;
        if (typeof value === 'undefined') {
            value = 5;
            undefinedFlag = true;
        }

        if (value == 'other') {
            var value = parseInt($('#other-amount').val());
            if (!$.isNumeric(value) || value == 0) {
                return;
            }
        }
    }

    $('input#donation-amount').val(value);
    $('button.donate').text('Donate $' + number_format(value));

    /**casting to int*/
    value = parseInt(value);
    if (!undefinedFlag) {
        var $progressContainer = $('.form-progress');
        var amountRaised = parseInt($progressContainer.data('amountraised'));
        var goalAmount = parseInt($progressContainer.data('goalamount'));
        amountRaised += value;
        $progressContainer.find('.amountRaised').text('$' + number_format(amountRaised));
        /*preventing division by zero*/
        goalAmount = Math.max(1, goalAmount);
        var percent = 100 * amountRaised / goalAmount;
        $progressContainer.find('.progress').css('width', percent + '%');
    }
}

$(document).ready(donatePageInit);


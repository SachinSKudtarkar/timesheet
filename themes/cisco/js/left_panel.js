$(document).ready(function() {

    /* left - right column height */
    var leftHeight = $('.left-panal').height();
    var rightHeight = $('.right-panal').height();
    if (leftHeight > rightHeight)
    {
        $('.right-panal').css({'height': leftHeight});
    }
    else
    {
        rightHeight = rightHeight + 15;
        $('.left-panal').css({'height': rightHeight});
    }

});
/* left - right column height - X */
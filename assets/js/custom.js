// next prev
var divs = $('.show-section section');
var now = 0; // currently shown div
divs.hide().first().show(); // hide all divs except first

//show active step
// function showActiveStep()
// {
//     if ($('#step1').is(':visible'))
//     {
//         $(".step-bar .bar .fill").eq(now).addClass('w-100');
//     }
//     else if ($('#step2').is(':visible'))
//     {
//         $(".step-bar .bar .fill").eq(now).addClass('w-100');
//     }
//     else if ($('#step3').is(':visible'))
//     {
//         $(".step-bar .bar .fill").eq(now).addClass('w-100');
//     }
//     else if ($('#step4').is(':visible'))
//     {
//         $(".step-bar .bar .fill").eq(now).addClass('w-100');
//     }
//     else if ($('#step5').is(':visible'))
//     {
//         $(".step-bar .bar .fill").eq(now).addClass('w-100');
//     }
//     else
//     {
//     console.log("error");
//     }
// }


function next()
{
    divs.eq(now).hide();
    now = (now + 1 < divs.length) ? now + 1 : 0;
    divs.eq(now).show(); // show next
    console.log(now);
}
$(".prev").on('click', function()
{

    $('.radio-field').addClass('bounce-left');
    $('.radio-field').removeClass('bounce-right');
    $(".step-bar .bar .fill").eq(now).removeClass('w-100');
    divs.eq(now).hide();
    now = (now > 0) ? now - 1 : divs.length - 1;
    divs.eq(now).show(); // show previous
    console.log(now);

})


// quiz validation
var checkedradio = false;

function radiovalidate(stepnumber)
{
    var checkradio = $("#step"+stepnumber+" input").map(function()
    {
    if($(this).is(':checked'))
    {
        return true;
    }
    else
    {
        return false;
    }
    }).get();

    checkedradio = checkradio.some(Boolean);
}




// form validation
$(document).ready(function()
{

    // check step1
    $(".next").on('click', function()
    {
            $('#step1 .radio-field').removeClass('bounce-left');
            $('#step1 .radio-field').addClass('bounce-right');
            setTimeout(function()
            {
                next();
            }, 200)


    })


    // check last step
    $("#sub").on('click', function()
    {
        radiovalidate(5);

        if(checkedradio == false)
        {
            
        (function (el) {
            setTimeout(function () {
                el.children().remove('.reveal');
            }, 3000);
            }($('#error').append('<div class="reveal alert alert-danger">Choose an option!</div>')));
            
            radiovalidate(5);

        }

        else
        {
            showresult();
            $("#sub").html('done');

        }
    })
})
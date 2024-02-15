// function initializePopup() {
//     $('.dismiss, .close, .closei').click(function () {
//         $(".popup").hide();
//         $('body').removeClass('overlay');
//     });

//     var $box = $('.box');

//     $('.closei').each(function () {
//         var color = $(this).css('backgroundColor');
//         var content = $(this).html();
//         $(this).click(function () {
//             $box.css('backgroundColor', color);
//             $box.addClass('open');
//             $box.find('p').html(content);
//         });
//     });

//     $('.close').click(function () {
//         $box.removeClass('open');
//         $box.css('backgroundColor', 'transparent');
//     });

//     $('body').removeClass('overlay');
//     $("#pop-toggle").click(function () {
//         $(".popup").toggle();
//         $('body').toggleClass('overlay');
//     });

//     $(".close").click(function (e) {
//         e.preventDefault();
//         $(".popup").hide();
//         $('body').removeClass('overlay');
//     });
// }

// // Call the function when the document is ready
// $(document).ready(function () {
//     initializePopup();
// });

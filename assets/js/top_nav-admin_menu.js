/**
 * Created by RamS-NSET on 2/21/2017.
 */
//#EBE7E7
$(document.body).on('click',".right_a",function(){
    $(".table_a").animate({"left": "+=484px"}, "fast");
    $(this).removeClass('right_a');
    $(this).addClass('left_a');
    $(".table_img_a" ).prop( 'src',base_url+'assets/img/arrow_lt.png' );
});

$(document.body).on('mouseover',".right_a",function(){
    $(".table_a").animate({"left": "+=3px"},0);
});
$(document.body).on('mouseout',".right_a",function(){
    $(".table_a").animate({"left": "-=3px"},0);
});

$(document.body).on('click',".left_a",function(){
    $(".table_a").animate({"left": "-=484px"}, "fast");
    $(this).addClass('right_a');
    $(this).removeClass('left_a');
    $(".table_img_a" ).prop( 'src',base_url+'assets/img/arrow_rt.png' );
});
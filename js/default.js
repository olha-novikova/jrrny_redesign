/**
 * Created by Hamid Ashraf on 12/23/2015.
 */


jQuery(document).ready(function(){
    var iWidth = jQuery(document).width();
    var sMessage = '';

    if(parseInt(iWidth) <= 768) {
        sMessage = 'Touch Here to Add Images!';
        console.log(sMessage);
    }else{
        sMessage = 'Drag and Drop your images here or just click on the dashed area!';
        console.log(sMessage);
    }

    jQuery('.dz-message span').each(function(){
        jQuery(this).html(sMessage);
    });

    console.log(iWidth);
});
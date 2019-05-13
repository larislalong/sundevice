$(document).ready(function(){
    $('.select-linktype').change(function(){
        if ($(this).val() == 0){
            $('.id_element_input').parent().parent().hide();
             $('.linkblock_input').parent().parent().parent().parent().parent().show();
        } else {
            $('.id_element_input').parent().parent().show();
            $('.linkblock_input').parent().parent().parent().parent().parent().hide();
        }
    }).trigger('change');
});
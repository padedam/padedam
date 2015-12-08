$(document).ready(function () {
    taggable = $('#competence');
    taggable.val('sritis');
    taggable.select2({
        triggerChange: true,
        allowClear: true,
    });

    $("#nfqassistance_bundle_assistance_request_type_longDescription").on('change keyup paste input', function(e){
        delay(function(){
            var ta = $("#nfqassistance_bundle_assistance_request_type_longDescription");
            var words = ta.val();
            if(words.length > 3) {
                machTags(words);
            }
        }, 1000 );
    });

});

var machTags = function(word){
    $.post(
        taggable.data('suggest'),
        {tag:word},
        function(result){
            if(result.status == 'success') {
                taggable.val(result.tags).trigger("change");
            }
        },'json'
    );
}


var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();
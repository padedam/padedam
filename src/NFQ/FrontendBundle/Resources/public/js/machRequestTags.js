$(document).ready(function () {
    taggable = $('#competence');
    taggable.val('sritis');
    taggable.select2({
        triggerChange: true,
        allowClear: true,
        quietMillis: 250,
        minimumInputLength: 2,
        tags: false,
        ajax: {
            url: taggable.data('match'),
            dataType: 'json',
            data: function (term, page) {
                return {
                    tag: term
                }
            },
            results: function (data, page) {
                if(data.status == 'success') {
                    return {
                        results: data.tags
                    };
                }
            }
        }
    });

    $("#nfqassistance_bundle_assistance_request_type_longDescription").on('change keyup paste input', function(e){
        var ta = $("#nfqassistance_bundle_assistance_request_type_longDescription");
        var words = ta.val().split(' ');
        var lastWord = words.pop();
        if(lastWord.length > 3) {
            machTags(lastWord);
        }
    });

});

var machTags = function(word){
    $.post(
        taggable.data('match'),
        {tag:word},
        function(result){
            if(result.status == 'success') {
                taggable.val(result.tags).trigger("change");
            }
        },'json'
    );
}



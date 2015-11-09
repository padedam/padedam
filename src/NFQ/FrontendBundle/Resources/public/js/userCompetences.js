$(document).ready(function () {
    var taggable = $('#competence');
    taggable.val('narsieji');
    taggable.select2({
    initSelection: function (element, callback) {
            var obj_id = element.val();
            if (obj_id !== "")  {
                $.getJSON(
                    taggable.data('current'),
                    {obj_id: obj_id},
                    function (response) {
                        if (response.status == 'success') {
                            element.val(JSON.stringify(response.tags));
                            callback(response.tags);
                        } else {
                            /* alert(response.message);*/
                        }
                    });
            }
        },
        triggerChange: true,
        allowClear: true,
        quietMillis: 250,
        minimumInputLength: 2,
        tags: true,
        tokenSeparators: [',', ' '],
        createSearchChoice: function (term) {
            return {
                id: $.trim(term),
                text: $.trim(term) + ' (Naujas)'
            };
        },
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
        },
        formatSelectionTooBig: function (limit) {
            return "Daugiausiai raktažodžių galite parinkti " + limit;
        }
    }).on('select2-removed', function (e) {
       $.post(
           taggable.data('rem'),
            {tag: e.choice},
            function (response) {
                if (!response.success) {
                    e.preventDefault();
                }
            },'json'
        );
    }).on('select2-selecting', function (e) {
        var _data = e.choice;
        taggable.val(JSON.stringify(_data));
        $.post(
            taggable.data('save'),
            {tag: e.choice},
            function(response){},'json'
        );
    });
});
$(document).ready(function () {
    var taggable = $('#competence');
    taggable.val('narsieji');
    taggable.select2({
    initSelection: function (element, callback) {
            var obj_id = element.val();
            if (obj_id !== "")  {
                $.getJSON(
                    'http://symfony.local/app_dev.php/assistance/mytags',
                    {},
                    function (response) {
                        element.val(JSON.stringify(response));
                        callback(response);
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
            url: 'http://symfony.local/app_dev.php/assistance/mt',
            dataType: 'json',
            data: function (term, page) {
                return {
                    tag: term
                };
            },
            results: function (data, page) {
                return {
                    results: data
                };
            }
        },
        formatSelectionTooBig: function (limit) {
            return "Daugiausiai raktažodžių galite parinkti " + limit;
        }
    }).on('select2-removed', function (e) {
       $.post(
            'http://symfony.local/app_dev.php/assistance/rmt',
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
            'http://symfony.local/app_dev.php/assistance/st',
            {tag: e.choice},
            function(response){},'json'
        );
    });
});
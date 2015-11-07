$(document).ready(function () {
    var taggable = $('#competence');
    taggable.val('dummy');
    taggable.select2({
        initSelection: function (element, callback) {
            var obj_id = '';
            if (obj_id && obj_class) {
                $.getJSON(
                    'http://symfony.local/app_dev.php/assistance/mt',
                    {obj_id: obj_id},
                    function (response) {
                        element.val(JSON.stringify(response));
                        callback(response);
                    });
            }
        },
        triggerChange: true,
        placeholder: 'Pasirinkite bendras žymas',
        allowClear: true,
        quietMillis: 250,
        minimumInputLength: 2,
        tags: true,
        tokenSeparators: [','],
        createSearchChoice: function (term) {
            return {
                id: $.trim(term),
                text: $.trim(term) + ' (Naujas)'
            };
        },
        ajax: {
            url: '',
            dataType: 'json',
            data: function (term, page) {
                return {
                    q: term
                };
            },
            results: function (data, page) {
                return {
                    results: data
                };
            }
        },
        // maximumSelectionSize: 3,
        // override message for max tags
        formatSelectionTooBig: function (limit) {
            return "Daugiausiai raktažodžių galite parinkti " + limit;
        }
    }).on('select2-removed', function (e) {
        var tagId = e.choice.id;
        if (!isNaN(tagId)) {
            $.getJSON(
                'http://symfony.local/app_dev.php/assistance/rmt',
                {
                    obj_id: '869',
                    tag_id: tagId
                },
                function (response) {
                    if (!response.success) {
                        e.preventDefault();
                    }
                });
        }
    });
    taggable.on('change', function (e) {
        var _data = taggable.select2('data');
        taggable.val(JSON.stringify(_data));
    });
});
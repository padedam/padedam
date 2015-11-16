$(function(){
    var competences = function (element) {
        var taggable = element;
        taggable.val('narsieji');
        taggable.select2({
        initSelection: function (element, callback) {
                var obj_id = element.val();
                if (obj_id !== "")  {
                    $.getJSON(
                        taggable.data('current'),
                        {obj_id: obj_id, parent_id: taggable.data('parentid')},
                        function (response) {
                            if (response.status == 'success') {
                                element.val(JSON.stringify(response.tags));
                                callback(response.tags);
                            } else {
                                // alert(response.message);
                            }
                        });
                }
            },
            triggerChange: true,
            allowClear: true,
            quietMillis: 250,
            minimumInputLength: 2,
            tags: true,
            tokenSeparators: [',', ';'],
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
                        tag: term,
                        parent_id: taggable.data('parentid')
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
                    if (response.status != 'success') {
                        e.preventDefault();
                    }
                },'json'
            );
        }).on('select2-selecting', function (e) {
            var _data = e.choice;
            taggable.val(JSON.stringify(_data));
            $.post(
                taggable.data('save'),
                {tag: e.choice, parent_id: taggable.data('parentid')},
                function(response){
                    if (response.status == 'success') {
                        //ok, saved
                    }else{
                        /*alert('tag was not saved');*/
                    }
                },'json'
            );
        });
    };

    /** SELECT ALL ELEMENTS AND MAKE THEM SELECT2 **/
    $( ".form-select" ).each(function() {
        var id = $(this).attr('id');
        element = $("#" + id);
        competences(element, id);
    });

/* create a new competence category */
    $("#create_competence").click(function(e){
        e.preventDefault();
        var id = uniqId();
        var select = $('<select/>', {
            'class': 'form-control',
            id: id
        });

        var formgroup = $('<div/>', {
            'class' : 'form-group'
        });

        var label = $( '<label>', {
            'class': 'col-sm-2 control-label required',
            'text': 'Pagalbos sritis'
        });
        var col10 = $( '<div/>', {
            'class': 'col-sm-10'
        });

        $('#wrapper').append( formgroup.append( label).append( col10.append( select ) ) );
        $("#"+id).select2(

        );
    });

});

function uniqId() {
    return Math.round(new Date().getTime() + (Math.random() * 100));
}
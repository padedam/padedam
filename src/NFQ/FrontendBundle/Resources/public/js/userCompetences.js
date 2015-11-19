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
            //send delete tag request
            remTag(taggable.data('rem'), {tag: e.choice});
        }).on('select2-selecting', function (e) {
            var _data = e.choice;
            taggable.val(JSON.stringify(_data));
            saveTag(taggable.data('save'), e.choice, taggable.data('parentid'));
        });
    };

    /** SELECT ALL ELEMENTS AND MAKE THEM SELECT2 **/
    $( ".form-select" ).each(function() {
        var id = $(this).attr('id');
        var element = $("#" + id);
        competences(element);
    });


/* create a new competence category */
    $("#new_competence").change(function(e){
        e.preventDefault();
        var selval = $("#new_competence").val();
        var seltext = $("#new_competence option:selected" ).text();

        var input = $('<input/>', {
            'class': 'form-control',
            'id': "child_"+selval,
            'data-save': $("#new_competence").data('save'),
            'data-current': $("#new_competence").data('current'),
            'data-rem' :$("#new_competence").data('rem'),
            'data-match': $("#new_competence").data('match'),
            'data-parentid': selval
        });

        var formgroup = $('<div/>', {
            'class' : 'form-group'
        });

        var label = $( '<label>', {
            'class': 'col-sm-2 control-label required',
            'text': seltext
        });
        var col9 = $( '<div/>', {
            'class': 'col-sm-9'
        });
        var col1 = $( '<div/>', {
            'class': 'col-sm-1'
        });
        var remlink = $('<a/>', {
            'class': 'rem-cat',
            'data-id': selval,
            'data-text': seltext,
            'data-rem': $("#new_competence").data('rem'),
            'text': 'pašalinti',
            'href': '#'
        });

        //add element to DOM
        $('#wrapper').prepend( formgroup.append( label).append( col9.append( input )).append( col1.append( remlink) ));

        //make element select2
        var element = $("#" + "child_"+selval);
        competences(element);
        //remove selected element from select list and reset selection
        $("#new_competence option[value="+selval+"]").remove().prop('selectedIndex',0);
        saveTag($("#new_competence").data('save'), {id:selval, text:seltext}, null);

        //bind remove category button
        bindRemcat();
    });

    bindRemcat();

});

function saveTag(url, tag, parent){
    $.post(
        url,
        {tag: tag, parent_id: parent},
        function(response){
            return response;
        },'json'
    );
}

function remTag(url, data, parent){
    $.post(
        url,
        data,
        function (response) {
            if(parent === true && response.status == 'success'){
                $()
            }
        },'json'
    );
}

var bindRemcat = function(){
    $(".rem-cat").unbind('click').bind('click', function(e){
        e.preventDefault();
        var el = $(this);
        var id =el.data('id');
        //send delete tag request
        $.post(
            el.data('rem'),
            {'tag':{'id': el.data('id'), 'text': el.data('text')}},
            function (response) {
                if(response.status == 'success'){
                    el.parents('.form-group').hide('slow');
                }
            },'json'
        );
    });
}
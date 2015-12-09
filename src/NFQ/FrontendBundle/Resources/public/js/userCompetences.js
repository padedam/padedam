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
                            //showMessage(response.message, 'info');
                        }
                    });
            }
        },
        triggerChange: true,
        allowClear: true,
        quietMillis: 250,
        minimumInputLength: 4,
        tags: true,
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
                    //showMessage('išsaugota sėkmingai', 'success');
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
        $.post(
            taggable.data('rem'),
            {tag: e.choice},
            function (response) {
                if(response.status === 'success'){
                    showMessage('pašalinta sėkmingai', 'success');
                }else{
                    showMessage(response.message, 'danger');
                }
            },
            'json'
        );


    }).on('select2-selecting', function (e) {
        var _data = e.choice;
        taggable.val(JSON.stringify(_data));
        $.post(
            taggable.data('save'),
            {tag: e.choice, parent_id: taggable.data('parentid')},
            function(response){
                if(response.status === 'success'){
                    showMessage('susieta sėkmingai', 'success');
                }else{
                    showMessage(response.message, 'danger');
                }
            },
            'json'
        );
    });
};

var bindRemcat = function(){
    $(".rem-cat").unbind('click').bind('click', function(e){
        e.preventDefault();
        var el = $(this);
        //send delete tag request
        $.post(
            el.data('rem'),
            {'tag':{'id': el.data('id'), 'text': el.data('text')}},
            function (response) {
                if(response.status == 'success'){
                    el.parents('.form-group').hide('slow');
                    //add removed element to select list
                    $('#new_competence').append($('<option />', { value : el.data('id'), text: el.data('text') }));
                    $('#new_competence').select2("destroy").select2().off('select2-selecting').on('select2-selecting', function (e) {
                        var _data = e.choice;
                        createParentElement(e);
                    });
                    showMessage('kompetencija pašalinta sėkmingai', 'success');
                }else{
                    showMessage(response.message, 'danger');
                }
            },'json'
        );
    });
}


/* create a new competence category */
function createParentElement (e){
    var selval = e.choice.id;
    var seltext = e.choice.text;

    if(selval == 0) {
        throw new Error("Selected default value");
    }
    $.post(
        $("#new_competence").data('save'),
        {tag: {id: selval, text: seltext}, parent_id: null},
        function(response){
            if(response.status == 'success'){
                //add element to DOM
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
                    'class': 'col-sm-2 control-label required text-capitalize',
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

                $('#wrapper').prepend( formgroup.append( label).append( col9.append( input )).append( col1.append( remlink) ));

                //make element select2
                var element = $("#" + "child_"+selval);
                competences(element);

                //remove selected element from select list and reset selection
                $("#new_competence option[value="+selval+"]").remove();
                $("#new_competence").select2("destroy").select2().off('select2-selecting').on('select2-selecting', function (e) {
                    createParentElement(e);
                });
                //enable remove category button
                bindRemcat();
                showMessage('kompetencija pridėta sėkmingai', 'success');
            }else{
                showMessage(response.message, 'danger');
            }
        },'json'
    );
}

$(function(){
    /** SELECT ALL ELEMENTS AND MAKE THEM SELECT2 **/
    $( ".form-select" ).each(function() {
        var id = $(this).attr('id');
        var element = $("#" + id);
        competences(element);
    });

    /** make #new_competence select 2 **/
    var new_competence = $("#new_competence").select2({
        placeholder: "Pasirinkite sritį"
    }).off('select2-selecting').on('select2-selecting', function (e) {
        createParentElement(e);
    });

    bindRemcat();

});


function showMessage(message, type){
        var remlink = $('<button/>', {
            'class': 'close',
            'data-dismiss': 'alert',
            'type': 'button',
            'text': 'x',
        });
        var message = $('<div/>',{
            'text': message,
            'class': 'alert alert-dismissible text-center alert-'+type,
        });

        $('.alert-dismissible').remove();

        var obj = message.append(remlink);
        $(document.body).prepend(obj.hide().slideDown( 300 ).delay( 3000 ).fadeOut( 400 , function(){$(obj.remove())}));

}
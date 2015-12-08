$(function(){
    var dismisable = $(".alert-dismissible");
    dismisable.delay(5000).slideUp('fast', function(){$(dismisable.parent().remove())});
})
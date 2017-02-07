
/* Module-specific javascript can be placed here */

$(document).ready(function() {
    handleButton($('#et_save'),function() {
    });
    

    handleButton($('#et_cancel'),function(e) {
        if (m = window.location.href.match(/\/update\/[0-9]+/)) {
                window.location.href = window.location.href.replace('/update/','/view/');
        } else {
                window.location.href = baseUrl+'/patient/episodes/'+et_patient_id;
        }
        e.preventDefault();
    });

    handleButton($('#et_deleteevent'));

    handleButton($('#et_canceldelete'),function(e) {
        if (m = window.location.href.match(/\/delete\/([0-9]+)/)) {
                window.location.href = baseUrl+'/patient/parentEvent/'+m[1];
        } else {
                window.location.href = baseUrl+'/patient/episodes/'+et_patient_id;
        }
        e.preventDefault();
    });

    $('select.populate_textarea').unbind('change').change(function() {
        if ($(this).val() != '') {
            var cLass = $(this).parent().parent().parent().attr('class').match(/Element.*/);
            var el = $('#'+cLass+'_'+$(this).attr('id'));
            var currentText = el.text();
            var newText = $(this).children('option:selected').text();

            if (currentText.length == 0) {
                    el.text(ucfirst(newText));
            } else {
                    el.text(currentText+', '+newText);
            }
        }
    });

    $('.addTest').click(function(e) {
        e.preventDefault();

        var i = 0;

        $('tbody.transactions').children('tr').children('td').children('input:first').map(function() {
                var id = $(this).attr('name').match(/[0-9]+/);

                if (id >= i) {
                        i = id;
                }
        });

        $.ajax({
            'type': 'GET',
            'url': baseUrl+'/OphInDnaextraction/default/addTransaction?i='+i,
            'success': function(html) {
                    $('tbody.transactions').append(html);
                    $('#no-tests').hide();
            }
        });
    });

    $('.removeTransaction').die('click').live('click',function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        if(!$('.removeTransaction').length) {
                $('#no-tests').show();
        }
    });
    
    handleButton( $('#addNewStoragePopup'),function(e) {
        $.ajax({
            'type': 'POST',
            'data': {YII_CSRF_TOKEN: YII_CSRF_TOKEN},
            'url': baseUrl+'/OphInDnaextraction/default/GetNewStorageFields',
            'success': function(html) {
                    var storageDialog = new OpenEyes.UI.Dialog({
                    content: html,
                    title: "Add new storage",
                    autoOpen: false,
                    onClose: function() { enableButtons(); },
                    buttons: {
                        "Close" : {
                            text: "Close",
                            id: "my-button-id-close",
                            click: function(){
                                $( this ).dialog( "close" );
                                enableButtons();
                            }   
                        },
                        "Save":{
                            text: "Save",
                            id: "my-button-id-save",
                            click: function(){
                                
                                if(saveNewStorage()){
                                    $( this ).dialog( "close" );
                                    enableButtons();
                                }
                                
                            }   
                        }
                    }
                });

                storageDialog.open();
            },
        });
        
        
    });
});

function ucfirst(str) { str += ''; var f = str.charAt(0).toUpperCase(); return f + str.substr(1); }

function eDparameterListener(_drawing) {
    if (_drawing.selectedDoodle != null) {
            // handle event
    }
}

function getAvailableLetterNumberToBox( obj ){
    obj = $(obj);
    
    $.ajax({
        'type': 'POST',
        'url': baseUrl+'/OphInDnaextraction/default/getAvailableLetterNumberToBox',
        'data': {
                box_id: obj.val(),
                YII_CSRF_TOKEN: YII_CSRF_TOKEN
        },  
        'dataType': 'json',
        'success': function(response) {
            if (typeof(response.letter) != "undefined"){
                $('#dnaextraction_letter').val(response.letter);
                $('#dnaextraction_number').val(response.number);
                
                $('#dnaextraction_letter').prop('disabled', false);
                $('#dnaextraction_number').prop('disabled', false);
            } else {        
                $('#dnaextraction_letter').prop('disabled', true);
                $('#dnaextraction_number').prop('disabled', true);
            }
        }
    });
}

function saveNewStorage(){
    data = $('#dnaextraction_addNewStorageForm').serialize() + '&YII_CSRF_TOKEN=' + YII_CSRF_TOKEN;
    
    var result = false;
    $.ajax({
        'type': 'POST',
        'url': baseUrl+'/OphInDnaextraction/default/saveNewStorage',
        'data': data,
        'dataType': 'json',
        'async': false,
        'success': function(response) {
            if(response.s == '0'){
                new OpenEyes.UI.Dialog.Alert({
                    content: response.msg
                }).open();
            } else {
                
                refreshStorageSelect();
                result = true;
            }
        }
    });
    
    return result;
}

function refreshStorageSelect(){
    $.ajax({
        'type': 'GET',
        'url': baseUrl+'/OphInDnaextraction/default/refreshStorageSelect',    
        'dataType': 'json',
        'success': function(response) {
            
            var count = Object.keys(response).length;
            var option = '<option value="">- Select -</option>';
            
            for(var i = 0; i < count; i++){
                key = Object.keys(response)[i];
                value = Object.values(response)[i];
                
                option += '<option value="'+key+'">'+value+'</option>';
            }
            
            $('#Element_OphInDnaextraction_DnaExtraction_storage_id').html(option);
           
        }
    });
}



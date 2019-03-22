$(document).ready(function(){

    var acts = 0;

    $("#updtdate").datepicker({
        showOtherMonths: false,
        selectOtherMonths: false,
        changeMonth: true,
        changeYear: true,
        dateFormat: "d-mm-yy",
        regional: "ru",
        showWeek: false,
        firstDay: 1,
        numberOfMonths: 1
    }); 

    $('.thumb-frame').click(function(){
        $(this).toggleClass('selected');
    });

    function UpdtData() {
        $('.selected').each(function(ind){           
            var aid = $(this).attr('art');
            $.ajax({
                url: "mods/art-manip.php?type=3&aid="+aid+"&date="+$('#updtdate').val()+'&cat='+$('#cbCat').val(),
                beforeSend: function( xhr ) {
                    $('#state_ind').show();
                }
            }).done(function() {                
                acts--;
                $('#cAction').text(acts);
                $("#art_"+aid).remove();
            });
            acts++;
        });
        $('#cAction').text(acts);
    }

    function DelData(){
        $('.selected').each(function(ind){
            var aid = $(this).attr('art');
            $.ajax({
                url: "mods/art-manip.php?type=4&aid="+aid,
                beforeSend: function( xhr ) {
                    $('#state_ind').show();
                }
            }).done(function() {                
                acts--;
                $('#cAction').text(acts);
                $("#art_"+aid).remove();
            });
            acts++;
        });
        $('#cAction').text(acts);
    }

    $('#updtBtn').click(function(){
        UpdtData();
    });
    $('#delBtn').click(function(){
        DelData();
    });
});
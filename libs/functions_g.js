$(document).ready(function(){

    var acts = 0;
    var seld = 0;
    var sel_allow = true;

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
        if (sel_allow){
            $(this).toggleClass('selected');
            if($(this).hasClass('selected')){
                seld++;
            } else {
                seld--;
            }
            $("#nSelected").text(seld);
        }
    });

    function UpdtData() {
        $('.selected').each(function(ind){           
            var aid = $(this).attr('art');
            $.ajax({
                url: "mods/art-manip.php?type=3&aid="+aid+"&date="+$('#updtdate').val()+'&cat='+$('#cbCat').val()+"&dateupd="+$("#dateUpdCk").is(":checked"),
                beforeSend: function( xhr ) {
                    $('#state_ind').show();
                }
            }).done(function() {                
                acts--;
                seld--;
                $('#cAction').text(acts);
                $("#art_"+aid).remove();
                $("#nSelected").text(seld);                
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
                seld--;
                $('#cAction').text(acts);
                $("#art_"+aid).remove();
                $("#nSelected").text(seld);
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

    $('.thumb-frame').draggable({ 
        revert: "invalid",
        disabled: true,
        drag: function(event, ui){
            $(ui.helper).addClass("thumb_frame_ontop");
        }
    });

    $('.admin_cell_cat').droppable({
        tolerance: 'pointer',
        greedy: true,
        drop: function( event, ui ) {

            var cat_id = $( this ).attr('val');
            var art_id = $(ui.draggable).attr('art');
            var cat_dz = $( this )

            cat_dz.addClass( "ui-state-highlight" );   
            
            $.ajax({
                url: "mods/art-manip.php?type=3&aid="+art_id+"&date="+$('#updtdate').val()+'&cat='+cat_id+"&dateupd="+$("#dateUpdCk").is(":checked"),
                beforeSend: function( xhr ) {
                    $('#state_ind').show();
                }
            }).done(function() {   
                cat_dz.removeClass( "ui-state-highlight" );
                $("#art_"+art_id).remove();
                $("#nSelected").text(seld);
            });
            
        },
        deactivate: function( event, ui ) {
            $(ui.draggable).removeClass("thumb_frame_ontop");
        }
    });

    $("#dropZoneSwitch").click(function(){
        var hg_calc = 0;
        $(".dropZone").toggle().each(function(index){            
            switch(index){
               case 0:
                    hg_calc = $(this).height();                    
                break;
                case 1:                    
                    $(this).height(hg_calc-50);
                    sel_allow = !sel_allow;

                    if(!sel_allow){
                        $('.thumb-frame').draggable("enable");
                    } else {
                        $('.thumb-frame').draggable("disable");
                    }
                break;
            };
            
        });
    });

});
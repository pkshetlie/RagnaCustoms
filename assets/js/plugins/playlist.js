$(document).on('change','#add_playlist_form_playlist',function(){
    let t = $(this);
    const box = $("#add_playlist_form_newPlaylist");
    if(t.val() !== undefined && t.val().trim() !== "" && t.val() !== null){
        box.closest('.form-group').hide();
        box.removeAttr("required");
    }else{
        box.attr("required","required");
    }
});

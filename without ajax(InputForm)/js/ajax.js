function deleterec(id) {
    $.ajax({
        type: "POST",
        url: "main.php",
        data: {
            id: id,
            type: 'delete'
        },
        dataType: 'json',
        success: function(data) {}
    })
}


function submit() {
    $.ajax({
        type: "POST",
        url: "main.php",
        data: {
            type: 'add'
            
        },
        dataType: 'json',
        success: function(data) {}
    })
}


// edit or delete function 
function editrec(id) {
    $.ajax({
        type: "POST",
        url: "main.php",
        data: {
            id: id,
            type: 'edit'
        },
        dataType: 'json',
        success: function(data) {
            console.log("hello")
        },
        error:function(e){
            console.log("this is error",e)

        }
    })
}




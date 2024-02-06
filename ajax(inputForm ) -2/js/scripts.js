$(document).ready(function () {
    $("#submit").mouseleave(function () {
        $("span").fadeOut(1000);
    });
});

$(document).ready(function () {
    $("#alert_tab").fadeOut(1000);
});


$(document).ready(function () {
    $("#lang3").click(function () {
        $("#other_lan").toggle();
    });
});

$(document).ready(function () {
    $("#reset").click(function () {
        $("#output").fadeOut(0.5);
        $("#hid_inp").val("")
    });
});


//image
var loadimage = function (event) {
    console.log(event.target.files);
    var output = document.getElementById("output");
    output.src = URL.createObjectURL(event.target.files[0]);
    document.getElementById("output").style.display = "block";
}

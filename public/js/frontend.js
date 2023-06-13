function checkScreenSize() {
    let newWindowWidth = $(window).width();
    console.log(newWindowWidth)
    if (newWindowWidth >= 1200) {
        $("#exampleModal").modal("hide");
    }
}

$(function () {
    $(window).on("resize", function (e) {
        checkScreenSize();
    });
    checkScreenSize();
});
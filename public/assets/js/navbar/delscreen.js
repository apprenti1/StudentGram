let delscreen_switch = false;
let delscreen;
function delscreenUpd() {
    if (delscreen_switch) {
        delscreen.filter = "opacity(0)";
        delscreen. backdropFilter = "none";
        delscreen_switch = false;
        setTimeout(()=>{
            delscreen.visibility = "hidden";
        }, 500);
    } else {
        delscreen.visibility = "visible";
        delscreen.filter = "opacity(1)";
        delscreen. backdropFilter = "blur(3px)";

        delscreen_switch = true;
    }
}
document.addEventListener("DOMContentLoaded",()=>{
    delscreen = document.getElementById("delscreen").style
    console.log(delscreen);
});
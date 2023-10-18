console.log("hehe");
var commenttoggle = 0;
var card = document.getElementById("viewCard")
function viewcard() {
    console.log("test");
    if (commenttoggle == 0) {
        commenttoggle = 1;
        card.style.height = "110px";
        card.style.margin = "var(--bs-gutter-y)";
        card.style.padding = "10px";
        card.style.visibility = "visible";
        setTimeout(() => {
            card.style.filter = "opacity(1)";
        }, 500);
    }else{
        commenttoggle = 0;
        card.style.filter = "opacity(0)";
        card.style.height = "0px";
        card.style.margin = "0";
        card.style.padding = "0";
        setTimeout(()=>{
            card.style.visibility="hidden"
        }, 500);
        

    }
}
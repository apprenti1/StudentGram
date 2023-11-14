console.log("hehe");
function viewcard(id="") {
    let card = document.getElementById("viewCard"+id)
    let commenttoggle = card.dataset.commenttoggle;
    console.log("test");
    if (commenttoggle == 0) {
        card.dataset.commenttoggle = 1;
        card.style.height = "110px";
        card.style.margin = "var(--bs-gutter-y)";
        card.style.padding = "10px";
        card.style.visibility = "visible";
        setTimeout(() => {
            card.style.filter = "opacity(1)";
        }, 500);
    }else{
        card.dataset.commenttoggle = 0;
        card.style.filter = "opacity(0)";
        card.style.height = "0px";
        card.style.margin = "0";
        card.style.padding = "0";
        setTimeout(()=>{
            card.style.visibility="hidden"
        }, 500);
        

    }
}
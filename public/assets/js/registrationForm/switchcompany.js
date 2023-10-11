document.addEventListener("DOMContentLoaded", ()=>{
    document.getElementById("companycheck").addEventListener("change", ()=>{
        if (document.getElementById("companycheck").checked) {
            console.log(document.getElementsByClassName("inputcompany"));
            Array.from(document.getElementsByClassName("inputcompany")).forEach(element => {
                element.required = true;
            });
            document.getElementById("companyform").style.height = "200px";
            setTimeout(() => {
                document.getElementById("companyform").style.visibility = "visible";
                document.getElementById("companyform").style.filter = "opacity(1)";
            }, 600);
        }
        else{
            document.getElementById("companyform").style.height = "0";
            document.getElementById("companyform").style.filter = "opacity(0)";
            /* document.getElementsByClassName("inputcompany").forEach(element => {
                element.required = false;
            }); */
            setTimeout(() => {
                document.getElementById("companyform").style.visibility = "hidden";
            }, 600);
        }




    })
})
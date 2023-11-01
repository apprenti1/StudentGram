document.addEventListener("DOMContentLoaded", ()=>{
    document.getElementById("companycheck").addEventListener("change", ()=>{
        if (document.getElementById("companycheck").checked) {
            console.log(document.getElementsByClassName("inputcompany"));
            document.getElementById("etudiantform").style.filter = "opacity(0)";
            document.getElementById("etudiantform").style.value = "";
            document.getElementById("etudiantform").required = false;
            Array.from(document.getElementsByClassName("inputcompany")).forEach(element => {
                element.required = true;
            });
            document.getElementById("companyform").style.height = "200px";
            setTimeout(() => {
                document.getElementById("etudiantform").style.visibility = "hidden";
                document.getElementById("companyform").style.visibility = "visible";
                document.getElementById("companyform").style.filter = "opacity(1)";
            }, 600);
        }
        else{
            document.getElementById("etudiantform").style.visibility = "visible";
            document.getElementById("companyform").style.height = "0";
            document.getElementById("companyform").style.filter = "opacity(0)";
            Array.from(document.getElementsByClassName("inputcompany")).forEach(element => {
                element.required = false;
            });
            setTimeout(() => {
                document.getElementById("companyform").style.visibility = "hidden";
                document.getElementById("etudiantform").style.filter = "opacity(1)";
                document.getElementById("etudiantform").required = true;
            }, 600);
        }




    })
})
document.addEventListener("DOMContentLoaded", ()=>{
    function companycheck() {
        if (document.getElementById("companycheck").checked) {
            document.getElementsByClassName("etudiantinput")[0].required = false;
            console.log(document.getElementsByClassName("inputcompany"));
            document.getElementById("etudiantform").style.filter = "opacity(0)";
            document.getElementById("etudiantform").firstElementChild.firstElementChild.selected = "selected"
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
            document.getElementsByClassName("etudiantinput")[0].required = true;
            document.getElementById("etudiantform").style.visibility = "visible";
            document.getElementById("companyform").style.height = "0";
            document.getElementById("companyform").style.filter = "opacity(0)";
            document.getElementById("adress").value = "";
            document.getElementById("rue").value = "";
            document.getElementById("cp").value = "";
            document.getElementById("ville").value = "";
            document.getElementById("nom_entreprise").value = "";
            document.getElementById("fonction_employe").value = "";
            Array.from(document.getElementsByClassName("inputcompany")).forEach(element => {
                element.required = false;
            });
            setTimeout(() => {
                document.getElementById("companyform").style.visibility = "hidden";
                document.getElementById("etudiantform").style.filter = "opacity(1)";
            }, 600);
        }


    }
    companycheck();
    document.getElementById("companycheck").addEventListener("change", companycheck)
})
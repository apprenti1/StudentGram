//document.addEventListener('DOMContentLoaded', adresse);
document.getElementById('adress').addEventListener('input', adresse);
function adresse(){
    const requester = new XMLHttpRequest();
    requester.open("GET", "https://api-adresse.data.gouv.fr/search/?q="+document.getElementById('adress').value+"&autocomplete=1");
    requester.send();
    requester.responseType = "json";
    requester.onload = () => {
        if (requester.readyState == 4 && requester.status == 200) {
        var data = requester.response;
        console.log(data);
        if (data.features.length!=0) {
            document.getElementById('rue').value = data.features[0].properties.housenumber+" "+data.features[0].properties.street;
            document.getElementById('cp').value = data.features[0].properties.postcode;
            document.getElementById('ville').value = data.features[0].properties.city;
            document.getElementById('adresstext').innerHTML = data.features[0].properties.label;
            console.log(document.getElementById('rue').value)
            console.log(document.getElementById('cp').value)
            console.log(document.getElementById('ville').value)
        }
        } else {
        console.log(`Error: ${xhr.status}`);
        document.getElementById('rue').value = null;
        document.getElementById('cp').value = null;
        document.getElementById('ville').value = null;
        document.getElementById('adresstext').innerHTML = "Aucune adresse semblable trouvée !!!";
        }
    };
}
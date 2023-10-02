addEventListener("DOMContentLoaded", (event) => {
    var input = document.querySelector('div#inputPP input');
    input.addEventListener("change", ()=>{
        if (input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var base64Data = e.target.result;
                if(document.querySelector('div#inputPP span')) {
                    document.querySelector('div#inputPP span').remove();
                }
                document.querySelector('div#inputPP').style = "background: url("+base64Data+") no-repeat; background-size: contain;";

            };
            reader.readAsDataURL(input.files[0]);
        }
    })
});

<form method="GET" class="form-inline my-2 my-lg-0" action = "">
            <input class="form-control mr-sm-2" type="search" name="search" id="search" placeholder="Keresés" aria-label="Keresés">
            <button class="btn btn-secondary my-2 my-sm-0" id="s-button" name="s-button" type="submit">Keresés</button>
            </form>

            <script>
document.addEventListener('DOMContentLoaded', function() {
   // const addToCartButtons = document.querySelectorAll('.add-to-cart');
   // a script a beírt kereső stringet megfelelő url-be teszi a kereséshez
document.getElementById("s-button").addEventListener("click", mySearch);
function mySearch(){
    var searchString = document.getElementById("search").value;
    submitOK = "true";
    //lekérem az aktuális domaint a kereséshz
    var currentDomain = window.location.hostname;
    //alert (currentDomain);
    var urlString = "/product/search/";
    // a domainhez hozzáteszem  a searchUrl-t
    var a = "http://"
    
    var searchUrl = a+currentDomain + urlString + searchString;
      // alert(searchUrl);    
   try {
    //document.getElementById("s-button").innerHTML = "Ide kattintottál";
      
        const sameOriginContext = window.open(searchUrl);
   }
   catch (e){
      alert("hiba, újra!");
      consol.log(e)
   }
    
}
       

    });
//};

</script>

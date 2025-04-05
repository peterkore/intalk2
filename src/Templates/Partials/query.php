<form method="GET" class="form-inline my-2 my-lg-0" action = "">
            <input class="form-control mr-sm-2" type="search" minlength="3" name="search" id="search" placeholder="Keresés" aria-label="Keresés">
            <button class="btn btn-secondary my-2 my-sm-0" id="s-button" name="s-button" type="submit">Keresés</button>
            </form>

            <script>
document.addEventListener('DOMContentLoaded', function() {
   // 
   // a script a beírt kereső stringet megfelelő url-be teszi a kereséshez
document.getElementById("s-button").addEventListener("click", mySearch);
function mySearch(){
    var searchString = document.getElementById("search").value;

    //if(searchString.length < 2 OR searchString.length > 8){
      //  alert("Hiba, a beírt karakterek száma minimum 2 maximumt 8 lehet");
        

    //}else{
        submitOK = "true";
    //lekérem az aktuális domaint a kereséshz
    var currentDomain = window.location.hostname;
    //alert (currentDomain);
    var urlString = "/product/search/";
    // a domainhez hozzáteszem  a keresés Url részt
    // a domain protocol lekérése, http v https
    const full = location.protocol;
    //alert (full);
    var searchUrl = full + urlString + searchString;
      // alert(searchUrl);    
   try {
   
      
        const sameOriginContext = window.open(searchUrl);
   }
   catch (e){
      alert("hiba, újra!");
      consol.log(e)
   }

    //}
  
    
}
       

    });

</script>

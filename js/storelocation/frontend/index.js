//hàm khởi tạo autocomplete
function initMap() {
    var input = document.getElementById('search-input');
    var lat = document.getElementById('lat');
    var lng = document.getElementById('lng');
    //định dạng kiểu dữ liệu trả về
    var autocomplete = new google.maps.places.Autocomplete(input,{
        types: ['geocode']
    });
    //nhập lng,lat của địa điểm được chọn khi người dùng chọn một địa điểm từ những kết quả gợi ý.
    autocomplete.addListener('place_changed', function () {
        lng.value='';
        lat.value='';
        var place = autocomplete.getPlace();
        lng.value= place.geometry.location.lng();
        lat.value= place.geometry.location.lat();
    })
}
// ngăn người dùng submit khi dùng phím enter khi input name lng,lat chưa được điền giá trị
var lng = document.getElementById('lng');
var lat = document.getElementById('lat');
var form = document.getElementById('person-form-validate');
document.addEventListener('keypress',function(e){
    if (e.keyCode == 13) {
        if(lng.value=='' || lat.value==''){
            e.preventDefault();
        }
    }
});
//ngăn người dùng submit khi input name lng,lat chưa được điền giá trị
form.addEventListener('submit',function(e){
    if(lng.value=='' || lat.value==''){
        e.preventDefault();
    }
});

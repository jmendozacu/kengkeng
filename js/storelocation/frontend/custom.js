
(function($){
    var url = window.location.href.split('/');
    $(document).on('click','.count-review',function(){
        $('.loadding-img').show();
        //lấy id store
        var store_id = $(this).attr('data-store-id');
        var url = window.location.href.split('/');

        //gửi thông tin tới action ajax
        new Ajax.Request(
            '/'+url[3]+"/storelisting/index/ajax",
            {
                method: 'post',
                onFailure: '',
                onSuccess: function (data) {
                    console.log(data);
                    $('.loadding-img').hide();
                    $('#modal-review .modal-body').html('');
                    $('#modal-review .modal-body').append(data.responseJSON.outputHtml);
                    $('#modal-review').modal('show');
                },
                parameters: {store_id:store_id}
            }
        );
    })
    $(document).on('click','#navbar-collapse',function(){
                $('#navbar').toggleClass('in');
    });
})(jQuery)
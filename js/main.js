$(function(){
    const SUCCESS = $('.alert-success');
    const ERROR = $('.alert-danger');
    $('header').css({'margin-bottom': '2rem'})
    $('.no-result').css({'font-size': '2rem', 'margin': '2rem 0'});
    $('#input').css({'margin-bottom': '1rem'});
    $('#mail').css({'color': 'white'});
    $('.change-logo').css({'margin-top': '1rem'});
    $('.list-text').css({'padding': '2rem 0'});
    updateSelect = (selectedValue) =>
    {
        this.value = selectedValue;
        console.log(this.value);
    }

    $(document).on("click", ".open-AddBookDialog", function () {
        let myBookId = $(this).data('id');
        $(".modal-footer #bookId").val( myBookId );
    });
    hideModal = () =>
    {
        SUCCESS.hide();
        ERROR.hide();
    }
    createHtmlTimeout =  (amountMs) =>
    {
        return new Promise(resolve => {
            setTimeout(() => {

                resolve(hideModal());


            }, amountMs);
        });
    }

    createHtmlTimeout(5000);





});
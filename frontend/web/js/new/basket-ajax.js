function changeItem(id, number) {
    number = parseInt(number);

    if (!number){
        return false;
    }

    $.ajax({
        url: '/shopping-cart/change-item',
        type: 'post',
        data: {
            idItem : id,
            numberItem : number,
            _csrf: csrfVar
        },
        success: function (data) {
            document.getElementById("sumAllItem").innerHTML = data.totalPrice;
            document.getElementById(id + "count").innerHTML = data.countItem;
            document.getElementById("totalCount").innerHTML = data.totalCount;
        }
    });
}

window.onload = function () {
    $(document).ready(function () {
        $('.cart-amount').each(function () {
            var current = $(this);

            current.find('a.cart-amount-minus').click(function () {
                var data = current.find('input').val();

                if (data > 1) {
                    //delItem(current.find('input').attr('id'));
                    current.find('input').val(parseInt(data) - 1);
                    changeItem(current.find('input').attr('id'), parseInt(data) - 1);
                }
                return false;
            });

            current.find('a.cart-amount-plus').click(function () {
                var data = current.find('input').val();

                if (data < 1000) {
                    //plusItem(current.find('input').attr('id'));
                    current.find('input').val(parseInt(data) + 1);
                    changeItem(current.find('input').attr('id'), parseInt(data) + 1);
                }
                return false;
            });
        });
    });
};
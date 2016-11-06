function addProduct(productId) {
    var quantity = $("#quantity_" + productId).val();

    $.ajax({
        url: Routing.generate('shopping_cart_add_product'),
        type: "post",
        data: {
            orderId: orderId,
            productId: productId,
            quantity: quantity
        },
        success: function (response) {
            if (response.order) {
                var order = $.parseJSON(response.order);

                var orderHtmlContent = "";

                $.each(order.order_item, function (index, item) {
                    var quantityPostfix = "gr.";
                    if (item.product.sold_by == "units") {
                        quantityPostfix = "ud.";
                    }

                    orderHtmlContent += '' +
                        '<li class="row" id="orderItem_' + item.id + '"> ' +
                        '<span class="quantity m-r-50px">' + item.quantity + quantityPostfix + '</span> ' +
                        '<span class="itemName">' + item.product.name + '</span> ' +
                        '<span class="popbtn" style="padding: 18px;">' +
                        '<a class="glyphicon glyphicon-remove" ' +
                        'onclick="removeProduct(\'' + item.id + '\')">' +
                        '</a>' +

                        '</span> ' +
                        '<span class="price">' + item.price + ' €</span> ' +
                        '</li>'
                });

                var orderPrices = '' +
                    '<span class="itemName">' +
                    'Subtotal:<br>' +
                    'Descuento:<br>' +
                    'Total' +
                    '</span> ' +
                    '<span class="price">' +
                    order.sub_total + '€ <br>' +
                    '-' + order.discount + '€<br>' +
                    order.total + '€ </span> ' +
                    '<span class="order"> ' +
                    '<a class="text-center" href="/show/' + order.id + '">View your Order</a>' +
                    '</span> ';

                $("#orderContent").html(orderHtmlContent);
                $("#orderPrices").html(orderPrices);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }


    });
}

function removeProduct(orderItemId) {

    $.ajax({
        url: Routing.generate('shopping_cart_remove_product'),
        type: "post",
        data: {
            orderId: orderId,
            orderItemId: orderItemId
        },
        success: function (response) {
            var order = $.parseJSON(response.order);

            $("#orderItem_" + orderItemId).remove();

            var orderPrices = '' +
                '<span class="itemName">' +
                'Subtotal:<br>' +
                'Descuento:<br>' +
                'Total' +
                '</span> ' +
                '<span class="price">' +
                order.sub_total + '€ <br>' +
                '-' + order.discount + '€<br>' +
                order.total + '€ </span> ' +
                '<span class="order"> ' +
                '<a class="text-center">ORDER</a>' +
                '</span> ';

            $("#orderPrices").html(orderPrices);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }


    });
}
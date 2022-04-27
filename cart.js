console.log('cart.js has loaded successfully')

function updateCart() {


    let cart = JSON.parse(localStorage.getItem('cart'))
    let gstTaxEl = document.getElementById("gst")
    let qstTaxEl = document.getElementById("qst")
    let totalAmountEl = document.getElementById("totalAmount")
    let totalPriceEl = document.getElementById("totalPrice")
    let subPrice = 0.00
    let finalPrice = 0.00
    let totalAmountOfProducts = 0
    let gst = 0.00
    let qst = 0.00
    for (let product in cart) {


        totalAmountOfProducts += parseInt(cart[product]['amount'])
        subPrice += parseFloat(cart[product]['price']) * parseFloat(cart[product]['amount'])

    }
    gst = subPrice * 0.05
    qst = subPrice * 0.09975

    finalPrice = (subPrice + qst + gst).toFixed(2)

    qst = qst.toFixed(2)
    gst = gst.toFixed(2)


    gstTaxEl.innerHTML = gst + "$"
    qstTaxEl.innerHTML = qst + "$"
    totalAmountEl.innerHTML = totalAmountOfProducts + ""
    totalPriceEl.innerHTML = finalPrice + "$"
}


function updateCartProduct(productID, input, tablePriceID, summaryID, price) {
    let isItemValid = true
    let itemValidityCheck
    input.style.outlineColor = 'black'

    import('./itemValidity.js').then(function (method) {
            input.style.borderColor = 'black'
            itemValidityCheck = method.checkValidity
            isItemValid = itemValidityCheck(input)


            if (isItemValid) {
                let cartRequest = new XMLHttpRequest();
                cartRequest.open("post", "./addCart.php");
                cartRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                cartRequest.onload = function () {
                    let response = JSON.parse(cartRequest.response);


                    if (response.success) {

                        let value = parseFloat(input.value)
                        let updatedPrice = (parseFloat(price) * value).toFixed(2) + "$"
                        document.getElementById(tablePriceID).innerHTML = parseFloat(response['product_cost']).toFixed(2) + "$"
                        let summaryAmountEl = document.getElementById("summary_" + summaryID).getElementsByClassName('summaryCol2').item(0).getElementsByTagName('b').item(0)
                        summaryAmountEl.innerHTML = value + ""

                        let summaryPriceEl = document.getElementById("summary_" + summaryID).getElementsByClassName('summaryCol3').item(0).getElementsByTagName('b').item(0)
                        summaryPriceEl.innerHTML = parseFloat(response['product_cost']).toFixed(2) + "$"

                        document.getElementById("totalPrice").innerHTML = response['total_cart_cost'].toFixed(2) + "$";
                        document.getElementById("totalAmount").innerHTML = response['total_amount'];
                        document.getElementById("gst").innerHTML = response['gst'].toFixed(2) + "$"
                        document.getElementById("qst").innerHTML = response['qst'].toFixed(2) + "$"


                    } else {
                        input.style.outlineColor = 'red'
                    }
                }
                cartRequest.send("id=" + productID + "&amount=" + input.value + "&override_amount=true");
            } else {
                input.style.outlineColor = 'red'
            }


        }
    )


}


function removeFromCart(productID, productName, itemID) {
    let cartRequest = new XMLHttpRequest();
    cartRequest.open("post", "./removeCart.php");
    cartRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
    cartRequest.onload = function () {


        let response = JSON.parse(cartRequest.response);



        if (response.success) {
            document.getElementById("totalPrice").innerHTML = response['total_cart_cost'].toFixed(2) + "$";
            document.getElementById("totalAmount").innerHTML = response['total_amount'];
            document.getElementById("gst").innerHTML = response['gst'].toFixed(2) + "$"
            document.getElementById("qst").innerHTML = response['qst'].toFixed(2) + "$"

            let summaryElement = document.getElementById("summary_" + productName)
            let tableItemElement = document.getElementById(itemID)
            summaryElement.remove()
            tableItemElement.remove()
        }

    }


    cartRequest.send("id=" + productID)


}